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


        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'index');


        $this->publishes([
            __DIR__ . '/Http/Middleware' => app_path('Http/Middleware'),
        ], 'middleware');

        $this->app['router']->pushMiddlewareToGroup('auth', \Rakibul\Userlog\Http\Middleware\ActivityLoggerMiddleware::class);
        
        $this->app['view']->composer('admin.layouts.sidebar', function ($view) {
            $view->with('menu', array_merge($view->getData()['menu'] ?? [], [
                'userActivity' => [
                    'url' => route('user-activity'),
                    'label' => 'User Activity',
                    'icon' => 'fas fa-tasks',
                ],
            ]));
        });
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
