<?php

use Millat\Pagify\Facades\Settings;
use Millat\Pagify\Models\Page;
use Millat\Pagify\Services\BlockService;
use Illuminate\Support\Facades\URL;

if (!function_exists('pagify')) {
    /**
     * Retrieve the setting for a specific key in the current page section.
     *
     * @param string $key
     * @return mixed
     */
    function pagify(string $key)
    {
        $pageId = config('pagify.page_id');
        $sectionId = config('pagify.current_section_id');
        $directory = config('pagify.current_directory');

        $setting = Page::sectionSettings($pageId, $sectionId, $key);

        // Return default value from the block if no setting is found
        return !empty($setting) ? $setting : BlockService::getDefaultValues($directory, $key);
    }
}

if (!function_exists('getClasses')) {
    /**
     * Get the CSS classes for a grid section on the page.
     *
     * @param string|null $gridId
     * @return string
     */
    function getClasses(?string $gridId = null): string
    {
        $pageId = config('pagify.page_id');
        $gridId = $gridId ?? config('pagify.current_grid_id');
        
        $pageSettings = Page::settings($pageId);

        // Return the classes for the specified grid section
        return $pageSettings['section_data'][$gridId]['styles']['classes'] ?? '';
    }
}

if (!function_exists('getSectionSetting')) {
    /**
     * Retrieve a setting for a section with given parameters and fields.
     *
     * @param array $params
     * @param array $fields
     * @return mixed
     */
    function getSectionSetting(array $params = [], array $fields = [])
    {
        return Settings::getSectionSetting($params, $fields);
    }
}

if (!function_exists('getField')) {
    /**
     * Get a specific field value.
     *
     * @param string $field
     * @return mixed
     */
    function getField(string|array $field)
    {
        return Settings::getField($field);
    }
}

if (!function_exists('previewUrl')) {
    /**
     * Generate the preview URL for a given page slug.
     *
     * @param string $slug
     * @return string
     */
    function previewUrl(string $slug): string
    {
        $route = config('pagify.preview_route');

        // Replace {slug} in the route with the actual slug and return the URL
        return URL::to(str_replace('{slug}', $slug, $route));
    }
}
