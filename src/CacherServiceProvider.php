<?php

namespace Ouodev\Cacher;

use Illuminate\Http\Request;
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
        if (app()->routesAreCached() || !config('cacher.routes')) {
            return;
        }

        Route::prefix(config('cacher.prefix'))
            ->middleware(config('cacher.middlewares'))
            ->group(function () {
                Route::get('/cache', function (Request $request) {
                    Artisan::call('optimize');

                    info('Cache executed successfully.');

                    return $request->expectsJson()
                        ? response()->json([
                            'status' => 'success',
                            'message' => 'Cache executed successfully.',
                        ])
                        : redirect()->to(config('cacher.prefix'));
                });

                Route::get('/clear', function (Request $request) {
                    Artisan::call('optimize:clear');

                    info('Cache cleared successfully.');

                    return  $request->expectsJson()
                        ? response()->json([
                            'status' => 'success',
                            'message' => 'Cache cleared successfully.',
                        ])
                        : redirect()->to(config('cacher.prefix'));
                });
            });
    }
}
