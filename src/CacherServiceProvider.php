<?php

namespace Ouodev\Cacher;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CacherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cacher.php' => config_path('cacher.php'),
            ], 'cacher');
        }

        $this->defineRoutes();
    }

    /**
     * Define the Cacher routes.
     */
    protected function defineRoutes(): void
    {
        if (app()->routesAreCached() || config('cacher.routes', true) === false) {
            return;
        }

        Route::prefix(config('cacher.prefix', 'admin'))
            ->middleware(config('cacher.middlewares', ['web', 'auth:web']))
            ->group(function () {
                Route::get('/cache', function () {
                        Artisan::call('config:cache');
                        Artisan::call('view:cache');
                        Artisan::call('route:cache');

                        info('Cache executed successfully.');

                        return redirect()->back();
                    }
                );

                Route::get('/clear', function () {
                        Artisan::call('config:clear');
                        Artisan::call('view:clear');
                        Artisan::call('route:clear');

                        info('Cache cleared successfully.');

                        return redirect()->back();
                    }
                );
            });
    }
}
