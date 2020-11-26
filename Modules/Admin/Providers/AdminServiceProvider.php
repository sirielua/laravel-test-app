<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Admin';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'admin';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $providers = [
            RouteServiceProvider::class,
            \Yajra\DataTables\DataTablesServiceProvider::class,
            \Yajra\DataTables\ButtonsServiceProvider::class,
//            \Yajra\DataTables\HtmlServiceProvider::class,
            DataTablesHtmlServiceProvider::class,
            BladeComponentsServiceProvider::class,
        ];

        $app = $this->app;

        array_walk($providers, function($provider) use ($app) {
            $app->register($provider);
        });
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
        $this->bootViews();
        $this->bootTranslations();
        $this->bootFactories();

        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function bootConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function bootViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function bootTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function bootFactories()
    {
//        if (! app()->environment('production') && $this->app->runningInConsole()) {
//            app(Factory::class)->load(module_path($this->moduleName, 'Database/factories'));
//        }
    }
}
