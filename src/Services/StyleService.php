<?php

namespace Millat\Pagify\Services;

use Millat\Pagify\Models\Page;

class StyleService
{
    /**
     * Get the list of CSS attributes and their types.
     *
     * @return array
     */
    public static function getAttributes(): array
    {
        return [
            "width" => "width-height-type",
            "height" => "width-height-type",
            "min-width" => "width-height-type",
            "min-height" => "width-height-type",
            "max-width" => "width-height-type",
            "max-height" => "width-height-type",
            "margin-top" => "margin-type",
            "margin-right" => "margin-type",
            "margin-bottom" => "margin-type",
            "margin-left" => "margin-type",
            "padding-top" => "padding-type",
            "padding-right" => "padding-type",
            "padding-bottom" => "padding-type",
            "padding-left" => "padding-type",
            "z-index" => "",
            "background-size" => "",
            "background-position" => "",
            "background-color" => "",
            "image" => "",
        ];
    }

    /**
     * Get the column classes based on the grid layout.
     *
     * @param string $grid
     * @return array
     */
    public static function getColumns(string $grid): array
    {
        switch ($grid) {
            case '2x8x2':
                return ['col-2', 'col-8', 'col-2'];
            case '3x3x6':
                return ['col-3', 'col-3', 'col-6'];
            case '3x4':
                return ['col-3', 'col-3', 'col-3', 'col-3'];
            case '3x6x3':
                return ['col-3', 'col-6', 'col-3'];
            case '3x9':
                return ['col-3', 'col-9'];
            case '4x3':
                return ['col-lg-4', 'col-lg-4', 'col-lg-4'];
            case '5':
                return ['col-2-4', 'col-2-4', 'col-2-4', 'col-2-4', 'col-2-4'];
            case '6':
                return ['col-2', 'col-2', 'col-2', 'col-2', 'col-2', 'col-2'];
            case '6x2':
                return ['col-md-6', 'col-md-6'];
            case '6x3x3':
                return ['col-md-6 col-12', 'col-md-3 col-6', 'col-md-3 col-6'];
            case '9x3':
                return ['col-9', 'col-3'];
            case '12x1':
                return ['col-12'];
            default:
                return ['col'];
        }
    }

    /**
     * Get the list of available grid layouts.
     *
     * @return array
     */
    public static function getGrids(): array
    {
        return ['12x1', '6x2', '4x3', '3x4', '5', '6', '3x9', '9x3', '3x3x6', '6x3x3', '3x6x3', '2x8x2'];
    }

    /**
     * Generate the container style based on style settings.
     *
     * @param array $styleSettings
     * @return string
     */
    public static function getContainerStyles() 
    {
        $gridId = config('pagify.current_grid_id');

        if (empty($gridId)) {
            return 'class="container"';
        }

        $page = Page::findById(config('pagify.page_id'));
        
        $styleSettings = $page->settings['section_data'][$gridId]['styles'] ?? [];

        if (!empty($styleSettings['content_width']) && $styleSettings['content_width'] == 'full_width') {
            return 'class="container-fluid px-0"';
        }

        if (!empty($styleSettings['content_width']) && $styleSettings['content_width'] == 'boxed') {
            $containerStyles =  'class="container"';
            if (!empty($styleSettings['boxed_slider_input']))
                $containerStyles .= ' style="max-width: ' . $styleSettings['boxed_slider_input'] . 'px"';
            return $containerStyles;
        }

        return 'class="container"';
    }

    /**
     * Get the section background overlay if it exists.
     *
     * @param string|null $gridId
     * @return string
     */
    public static function getSectionBackground(?string $gridId = null): string
    {
        $pageSettings = Page::settings(config('pagify.page_id'));
        $gridId = $gridId ?? config('pagify.current_grid_id');

        if (!empty($pageSettings['section_data'][$gridId]['styles']['background-overlay-color'])) {
            $overlayColor = $pageSettings['section_data'][$gridId]['styles']['background-overlay-color'];

            return <<<HTML
            <div class="pb-bg-overlay" style="background-color: {$overlayColor};
                position: absolute; left: 0; top: 0; width: 100%; height: 100%;">
            </div>
            HTML;
        }

        return '';
    }

    /**
     * Get the component styles for a given grid ID.
     *
     * @param string|null $gridId
     * @return string
     */
    public static function getComponentStyles($gridId = null) 
    {
        $css = '';

        if (empty($gridId)) {
            return $css;
        }

        $pageId = config('pagify.page_id');
        $page = Page::findById($pageId);

        $styleSettings = $page->settings['section_data'][$gridId]['styles'] ?? [];

        if (!$styleSettings) {
            return $css;
        }

        $attributes = self::getAttributes();

        foreach ($attributes as $attribute => $valueType) {

            if (!isset($styleSettings[$attribute]) || $styleSettings[$attribute] == "") {
                continue;
            }

            if ($attribute == 'image') {
                $bg = json_decode($styleSettings[$attribute][0], true);
                $css .= 'background-image:url(\'' . $bg['thumbnail'] . '\');';
            } else {
                $css .= $attribute . ':' . $styleSettings[$attribute] . ($styleSettings[$valueType] ?? '') . ';';
            }
        }
        
        return $css;
    }

    /**
     * Get the overall CSS styles for the current grid.
     *
     * @return string
     */
    public static function getCss(): string
    {
        return self::getComponentStyles(config('pagify.current_grid_id'));
    }

    /**
     * Get custom attributes for the grid section.
     *
     * @param string|null $gridId
     * @return string
     */
    public static function getCustomAttributes(?string $gridId = null): string
    {
        $pageSettings = Page::settings(config('pagify.page_id'));
        $gridId = $gridId ?? config('pagify.current_grid_id');

        return $pageSettings['section_data'][$gridId]['styles']['custom_attributes'] ?? '';
    }
}
