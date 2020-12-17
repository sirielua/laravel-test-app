<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeComponentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap your package's services.
     */
    public function boot()
    {
        Blade::componentNamespace('Modules\\Admin\\View\\Components', 'admin');
        Blade::componentNamespace('Modules\\Admin\\View\\Components\\Architect', 'architect');
    }
}
