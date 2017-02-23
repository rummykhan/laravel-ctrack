<?php

namespace RummyKhan\Mongomies;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MongomiesServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        Route::group([
            'middleware' => 'web'
        ], function ($router) {
            require __DIR__.'/routes/web.php';
        });

        $this->publishes([
            __DIR__ . '/config/mongomies.php' => config_path('mongomies.php')
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/views', 'mongomies');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/mongomies'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

}