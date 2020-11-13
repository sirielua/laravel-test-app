<?php

namespace Modules\Admin\Entities\DataTables;

use App\User;
use Yajra\DataTables\Html\Column;

class UsersDataTable extends AdminDataTable
{
    protected $tableId = 'users-table';

    protected $checkboxColName = 'selected';

    protected $actionsColName = 'actions';

    protected $pageLength = 25;

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
       return app()->make(User::class)->newQuery();
    }

    protected function getAjaxUrl()
    {
        return route('admin::users.index');
    }

    protected function routes($model)
    {
        return [
            'show' => route('admin::users.show', $model->id),
            'edit' => route('admin::users.edit', $model->id),
            'destroy' => route('admin::users.destroy', $model->id),

            'activate' => route('admin::users.activate', $model->id),
            'deactivate' => route('admin::users.deactivate', $model->id),
        ];
    }

    /**
     * Build DataTable class.
     *
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {


        return datatables()
            ->eloquent($query)

            ->addColumn('selected', function ($model) {
                return $this->renderCheckboxCol($model);
            })

            ->addColumn('actions', function ($model) {
                return $this->renderActionsCol($model);
            })

            ->editColumn('is_active', function ($model) {
                $routes = $this->routes($model);

                return $model->is_active ?
                    '<a href="' . $routes['deactivate'] . '" class="dataTableControl mb-2 mr-2 badge badge-pill badge-success" data-method="PATCH">Active</a>'
                    :
                    '<a href="' . $routes['activate'] . '" class="dataTableControl mb-2 mr-2 badge badge-pill badge-danger" data-method="PATCH">Inactive</a>';

//                return $this->renderSwitchCol($model, [
//                    ['Active', 'success', $routes['deactivate'], $model->is_active],
//                    ['Inactive', 'danger', $routes['activate'], !$model->is_active],
//                ]);
            })

            ->editColumn('status', function ($model) {
                $routes = $this->routes($model);

                return $model->is_active ?
                    '<div class="dropdown d-inline-block">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-success btn-sm">Active</button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="' . $routes['activate'] . '" type="button" tabindex="0" class="dataTableControl dropdown-item" data-method="PATCH">Activate</a>
                            <a href="' . $routes['deactivate'] . '" type="button" tabindex="0" class="dataTableControl dropdown-item" data-method="PATCH">Deactivate</a>
                        </div>
                    </div>'
                    :
                    '<div class="dropdown d-inline-block">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-danger btn-sm">Inactive</button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="' . $routes['activate'] . '" type="button" tabindex="0" class="dataTableControl dropdown-item" data-method="PATCH">Activate</a>
                            <a href="' . $routes['deactivate'] . '" type="button" tabindex="0" class="dataTableControl dropdown-item" data-method="PATCH">Deactivate</a>
                        </div>
                    </div>';

                //return $this->renderSwitchCol($model);
            })

            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d H:i P');
            })

            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format('Y-m-d H:i P');
            })

            ->rawColumns(['selected', 'actions', 'is_active', 'status'], $mergeDefaults = true);
    }

    /**
     * Define columns to display in html table
     *
     * @return array
     */
    protected function defineColumns()
    {
        return [
            $this->defineCheckboxCol(),
            Column::make('id')->search('id'),
            Column::make('name')->search('name'),
            Column::make('email')->search('email'),
            Column::make('created_at'),
            Column::make('updated_at'),
            $this->defineSwitchColumn('is_active'),
            $this->defineSelectColumn('status'),
            $this->defineActionsColumn(),
        ];
    }
}
