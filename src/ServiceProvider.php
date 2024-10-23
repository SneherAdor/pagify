<?php

namespace Millat\Pagify;

use Exception;
use Millat\Pagify\Settings;
use Millat\Pagify\PageSettings;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load and merge the package configuration file
        $configPath = __DIR__ . '/../config/pagify.php';
        $this->mergeConfigFrom($configPath, 'pagify');

        // Load and merge the package configuration file
        $configPath = __DIR__ . '/../config/blocks.php';
        $this->mergeConfigFrom($configPath, 'blocks');

        // Bind the Settings class to the 'settings' service container
        $this->app->bind('settings', function () {
            return new Settings();
        });

        // Bind the PageSettings class to the 'page_settings' service container
        $this->app->bind('pageSettings', function () {
            return new PageSettings();
        });

        // Require the helper functions
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         // Conditionally load the route
        if (file_exists($appRoutePath = base_path('routes/pagify.php'))) {
            $this->loadRoutesFrom($appRoutePath);
        } else {
            $this->loadRoutesFrom(__DIR__.'/../routes/pagify.php');
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pagify');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pagify');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../lang' => resource_path('lang'),
        ], 'lang');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/pagify.php' => config_path('pagify.php'),
        ], 'config');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/pagify'),
            __DIR__ . '/../resources/views/layouts/pb-site.blade.php' => resource_path('views/layouts/pb-site.blade.php'),
        ], 'assets');

        // Publish routes
        $this->publishes([
            __DIR__ . '/../routes' => base_path('routes'),
        ], 'routes');

        // Ensure configuration is published when not running in console
        if (!$this->app->runningInConsole() && empty(config('pagify'))) {
            throw new Exception("No pagify config found. Please run: php artisan vendor:publish --provider=\"Millat\\Pagify\\ServiceProvider\" --tag=config");
        }
        
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_12_29_072511_create_pages_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_pages_table.php'),
          ], 'pagify-migrations');
    }
}
