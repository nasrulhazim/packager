<?php

namespace Vendor\Package\Providers;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path_to_view = __DIR__.'/../Views';

        // load view from
        $this->loadViewsFrom($path_to_view, 'package');

        // publish view
        $this->publishes([
            $path_to_view => resource_path('views/vendor/package'),
        ]);

        // publish migration
        $this->publishes([
            __DIR__.'/../Migrations/' => database_path('migrations')
        ], 'package_migrations');

        // publish configuration file
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('package.php')
        ], 'package_config');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
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
