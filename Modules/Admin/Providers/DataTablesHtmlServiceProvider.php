<?php

namespace Modules\Admin\Providers;

use Yajra\DataTables\HtmlServiceProvider;
use Modules\Admin\Components\DataTables\HtmlBuilder;

class DataTablesHtmlServiceProvider extends HtmlServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        $this->app->bind('datatables.html', function () {
            return $this->app->make(HtmlBuilder::class);
        });
    }
}

