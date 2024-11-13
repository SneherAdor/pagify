<?php

namespace Millat\Pagify\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Millat\Pagify\Models\Page;
use Millat\Pagify\Facades\Settings;
use Illuminate\Support\Facades\Validator;
use Millat\Pagify\Services\BlockService;
use Millat\Pagify\Services\FileUploadService;
use Millat\Pagify\Services\StyleService;

class PageBuilderController extends Controller 
{
    /**
     * The path to the blocks directory.
     *
     * @var string
     */
    public $pb_path;

    /**
     * The path to the blocks view directory.
     *
     * @var string
     */
    public $pb_view;

    /**
     * FileUploadService instance.
     *
     * @var FileUploadService
     */
    protected $fileUploadService;

    /**
     * Create a new PageBuilderController instance.
     *
     * @param FileUploadService $fileUploadService
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display the page builder.
     *
     * @param int $pageId
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function build($pageId, Request $request) 
    {
        $page = Page::findById($pageId);

        if (!$page) {
            abort(404);
        }

        $grid_templates =   [];
        
        foreach (StyleService::getGrids() as $grid) {
            $grid_templates[$grid] = view('pagify::components.add-grid-placeholder', [
                'columns' => StyleService::getColumns($grid),
                'grid' => $grid
            ])->render();
        }

        $componentTabs = [];

        foreach (BlockService::get() as $key => $block) {
            config()->set('pagify.current_directory', $block['id']);
        
            $components[$block['id']] = [
                'directory' => $key,
                'settings' => $block,
                'template' => view()->exists($block['view']) ? view($block['view'])->render() : view('pagify::components.no-view')->render(),
            ];
        
            $componentTabs[$block['tab']][$block['id']] = $block['id'];
        }

        config()->set('pagify.page_id', $page->id);

        return view('pagify::pagify', compact('grid_templates', 'components', 'page', 'componentTabs'));
    }

    /**
     * Get the settings for a specific section.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSectionSettings(Request $request) 
    {
        $pageId = $request->input('page_id');
        $directory = $request->input('directory');
        $sectionId = $request->input('id');
        $gridId = $request->input('grid_id');

        $settings = BlockService::get($directory);

        if (!empty($settings['fields'])) {
            $settingsHtml = $this->getPageSectionSettings($pageId, $sectionId, $settings['fields']);
        }

        $json = [
            'type' => 'success', 
            'section_data' => Page::settings($pageId) ?? [], 
            'settings' => $settingsHtml ?? '', 
            'styles' => $this->getSectionStyles($pageId, $gridId)
        ];

        return response(json_encode($json), 200);
    }

    /**
     * Set the settings for a specific section.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePageSettings(Request $request)
    {
        $settings = $sectionId = null;
        if (!empty($request->get('settings')))
            $settings = $request->get('settings');
        if (!empty($request->get('current_section_data'))) {
            parse_str($request->get('current_section_data'), $form_data);
            parse_str($request->get('current_advanced_settings'), $advanced_form_data);
            $sectionId = $form_data['section_id'];
            unset($form_data['_method']);
            unset($form_data['_token']);
            unset($form_data['section_id']);
            $settings['section_data'][$sectionId]['settings'] = [];
            foreach ($form_data as $key => $value) {
                $isArray = 0;
                if (is_array($value))
                    $isArray = 1;
                $settings['section_data'][$sectionId]['settings'][$key]['value'] = $value;
                $settings['section_data'][$sectionId]['settings'][$key]['is_array'] = $isArray;
            }

            $grid_id = $advanced_form_data['grid_id'] ?? null;
            unset($advanced_form_data['grid_id']);
            $settings['section_data'][$grid_id]['styles'] = [];
            foreach ($advanced_form_data as $key => $value) {
                if ($value != 'rgba(0,0,0,0)') {
                    $settings['section_data'][$grid_id]['styles'][$key] = $value;
                }
            }

            if (
                empty($settings['section_data'][$grid_id]['styles']['content_width']) || 
                (!empty($settings['section_data'][$grid_id]['styles']['content_width']) && 
                $settings['section_data'][$grid_id]['styles']['content_width'] == 'full_width')
                ) {
                unset($settings['section_data'][$grid_id]['styles']['boxed_slider_input']);
            }
        }

        Page::store($request->input('page_id'), $settings);
        
        if (!empty($sectionId)) {
            config()->set('pagify.page_id', $request->input('page_id'));
            $sectionHtml = $this->getSectionHtml($sectionId, $request->input('directory'));
        }

        return response()->json(
            [
                'success' => [
                    'type'          => 'success',
                    'title'         => __('Congratulations'),
                    'message'       => __('Page settings updated successfully'),
                ],
                'html' => $sectionHtml ?? '',
                'css' => !empty($grid_id) ? StyleService::getComponentStyles($grid_id) : '',
                'custom_attributes' => !empty($grid_id) ? StyleService::getCustomAttributes($grid_id) : '',
                'bgOverlay' => !empty($grid_id) ? StyleService::getSectionBackground($grid_id) : '',
                'classes' => !empty($grid_id) ? getClasses($grid_id) : '',
                'sectionData' =>  $settings['section_data'] ?? []
            ]
        );
    }

    /**
     * Display the page builder in an iframe.
     *
     * @param int $pageId
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function iframe($pageId, Request $request)
    {
        $page = Page::findById($pageId);
        $edit = true;
        
        if (!$page) {
            abort(404);
        }

        $grid_templates =   [];
        foreach (StyleService::getGrids() as $grid) {
            $columns = StyleService::getColumns($grid);
            $grid_templates[$grid] = view('pagify::components.add-grid-placeholder', compact('columns', 'grid'))->render();
        }

        $componentTabs = [];

        foreach (BlockService::get() as $key => $block) {

            config()->set('pagify.current_directory', $block['id']);

            $components[$block['id']] = [
                'directory' => $key,
                'settings' => $block,
                'template' => view()->exists($block['view']) ? view($block['view'], compact('page', 'edit'))->render() : view('pagify::components.no-view', compact('page', 'edit'))->render(),
            ];

            $componentTabs[$block['tab']][$block['id']] = $block['id'];
        }


        config()->set('pagify.page_id', $page->id);
        
        return view('pagify::pagify-iframe', compact('grid_templates', 'components', 'page', 'componentTabs', 'edit'));
    }

    /**
     * Get the HTML for the specified section.
     *
     * @param string $sectionId
     * @param string|null $directory
     * @return string
     */
    public function getSectionHtml(string $sectionId, ?string $directory = null): string
    {
        if (!$sectionId || !$directory) {
            return '';
        }

        config()->set('pagify.current_section_id', $sectionId);
        
        $viewPath = BlockService::get($directory)['view'];
        if (view()->exists($viewPath)) {
            return view($viewPath, ['edit' => true])->render();
        }
        
        return __('pagify::pagify.no_view', ['block' => $directory]);
    }

    /**
     * Generate HTML for page section settings based on the provided fields.
     *
     * @param int $pageId
     * @param string $sectionId
     * @param array $fields
     * @param array $params (optional) Additional parameters
     * @return string
     */
    public function getPageSectionSettings(int $pageId, string $sectionId, array $fields, array $params = []): string
    {
        $html = '';
        $db_value = '';

        if (is_array($fields) && !empty($fields)) {
            $tab_key = !empty($params['tab_key']) ? $params['tab_key'] : '';

            $html = '<ul class="op-themeform__wrap">';
            foreach ($fields as $field) {
                $field['tab_key'] = $tab_key;

                if (empty($params['repeater_id'])) {
                    $id = !empty($field['id']) ? $field['id'] : '';
                    $db_value = Page::sectionSettings($pageId, $sectionId, $id);
                    $field['db_value'] = $db_value;

                    if (!empty($db_value)) {
                        $field['value'] = $db_value;
                    }
                } else {
                    $field['repeater_id'] = !empty($params['repeater_id']) ? $params['repeater_id'] : '';
                    $field['index'] = !empty($params['index']) ? $params['index'] : '';
                }

                $html .= Settings::getField($field);
            }
            $html .= '</ul>';
            $html .= '<script src="' . asset( config('pagify.assets_path') . '/js/settings.js') . '"></script>';
        }

        return $html;
    }

    /**
     * Retrieve the styles for a specific section of a page and render the styles view.
     *
     * @param int $pageId
     * @param string $sectionId
     * @return string
     */
    public function getSectionStyles(int $pageId, string $sectionId): string
    {
        $pageSettings = Page::findById($pageId);
        $styles = $pageSettings->settings['section_data'][$sectionId]['styles'] ?? [];

        return view('pagify::components.styles', ['styles' => $styles])->render();
    }

    /**
     * Update file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFiles(Request $request)
    {
        $result = $this->fileUploadService->uploadFiles($request);

        return response()->json([
            'type'    => $result['status'],
            'message' => $result['status'] === 'error' ? $result['message'] : null,
            'files'   => $result['status'] === 'success' ? $result['files'] : null,
        ]);
    }
}
