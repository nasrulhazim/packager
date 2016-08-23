<?php

namespace [[namespace_vendor]]\[[namespace_package]]\Providers;

use Illuminate\Support\ServiceProvider;

class [[class_package]]ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path_to_view = __DIR__.'/../../resources/views';

        // load view from
        $this->loadViewsFrom($path_to_view, '[[package]]');

        // publish view
        $this->publishes([
            $path_to_view => resource_path('views/vendor/[[package]]'),
        ]);

        // publish migration
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], '[[package]]_migrations');

        // publish seed
        $this->publishes([
            __DIR__.'/../../database/seeds/' => database_path('seeds')
        ], '[[package]]_seeds');

        // publish configuration file
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('[[package]].php')
        ], '[[package]]_config');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../../routes/routes.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
