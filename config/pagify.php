<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page Builder Layout Configuration
    |--------------------------------------------------------------------------
    |
    | The following options define the layout and section settings that the
    | page builder will use when rendering content and managing styles/scripts.
    |
    */

    'layout' => 'layouts.builder',  // Default builder layout.
    'section' => 'content',         // Section where the builder content will be injected.
    'style_var' => 'styles',        // Variable to hold page-specific styles.
    'script_var' => 'scripts',      // Variable to hold page-specific scripts.

    /*
    |--------------------------------------------------------------------------
    | Asset Paths
    |--------------------------------------------------------------------------
    |
    | Configuration for asset paths used by the page builder to locate
    | its required resources, such as CSS, JS, and image files.
    |
    */

    'assets_path' => public_path('vendor/pagify'),   // Public path to builder assets.

    /*
    |--------------------------------------------------------------------------
    | Site Layout Configuration
    |--------------------------------------------------------------------------
    |
    | These settings define the layout and sections for the main site when
    | the page builder integrates with the broader application.
    |
    */

    'site_layout' => 'layouts.pb-site',   // Default site layout for page builder pages.
    'site_section' => 'site_content',     // Section where the site content will be injected.
    'site_style_var' => 'styles',         // Variable for site-wide styles.
    'site_script_var' => 'scripts',       // Variable for site-wide scripts.

    /*
    |--------------------------------------------------------------------------
    | Page Preview & Routing
    |--------------------------------------------------------------------------
    |
    | Customize the route settings for previewing pages and managing
    | URL structure and middleware for the page builder.
    |
    */

    'preview_route' => 'page/{slug}',         // Route pattern for previewing pages.
    'url_prefix' => '',                       // Prefix for page builder URLs.
    'route_middleware' => [],                 // Middleware applied to page builder routes.

];
