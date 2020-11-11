<?php

namespace App\Providers;

use App;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Str;

/**
 * Provides correct routes and urls when launched 
 * in production enviroment (App::environment('production'))
 * when working from subfolder.
 * APP_DIR enviroment variable should be specified for correct behavior
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        if($subdir = $this->getSubDir()) {
            $this->attuneUrlGenerationFromSubdir($subdir);
        }
        
        parent::boot();
    }

    /**
     * Returns installation subdirectory if any
     * 
     * @return str || null
     */
    protected function getSubDir()
    {
        return App::environment('production') ? env('APP_DIR', null) : null;
    }
    
    protected function attuneUrlGenerationFromSubdir($subdir)
    {
        $urlGenerator = app(UrlGenerator::class);
        $urlGenerator->formatPathUsing(function ($path, $route) use ($subdir) {
            if($route === null) {
                $path = '/'.$subdir.$path;
            }

            return $path;
        });
    }
    
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    
    
    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::prefix(App::environment('production') ? env('APP_DIR') : '')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix(App::environment('production') ? env('APP_DIR') . '/api' : 'api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
    
}
