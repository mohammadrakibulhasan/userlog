<?php

namespace Rakibul\Userlog;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Rakibul\Userlog\Http\Middleware\ActivityLoggerMiddleware;

class UserActivityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        $files = new Filesystem;

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/config/activitylogger.php' => config_path('activitylogger.php'),
        ]);


        $this->publishes([
            __DIR__ . '/Http/Middleware' => app_path('Http/Middleware'),
        ], 'middleware');

        $this->app['router']->pushMiddlewareToGroup('auth', \Rakibul\Userlog\Http\Middleware\ActivityLoggerMiddleware::class);
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register package services
        $this->mergeConfigFrom(
            __DIR__ . '/config/activitylogger.php',
            'activitylogger'
        );
    }
}
