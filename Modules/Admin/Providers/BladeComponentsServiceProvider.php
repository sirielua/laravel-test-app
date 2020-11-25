<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
//use Illuminate\View\Compilers\BladeCompiler;

class BladeComponentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap your package's services.
     */
    public function boot()
    {
        Blade::componentNamespace('Modules\\Admin\\View\\Components\\Architect', 'architect');
    }
}
