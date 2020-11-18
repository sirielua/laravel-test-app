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

            ->editColumn('status', function ($model) {
                return $this->renderButtonCol($model, [
                    'label' => $model->is_active ? 'Active' : 'Inactive',
                    'tag' => $model->is_active ? 'success' : 'secondary',
                    'route' => $model->is_active ? 'deactivate' : 'activate',
                    'verb' => 'patch',
                ]);
            })

            ->editColumn('is_active', function ($model) {
                return $this->renderDropdownCol($model, [
                    'label' => $model->is_active ? 'Active' : 'Inactive',
                    'tag' => $model->is_active ? 'success' : 'secondary',
                    'options' => [
                        [
                            'label' => 'Activate',
                            'tag' => 'success',
                            'route' => 'activate',
                            'verb' => 'patch',
                        ],
                        [
                            'label' => 'Deactivate',
                            'tag' => 'secondary',
                            'route' => 'deactivate',
                            'verb' => 'patch',
                        ],
                    ]
                ]);
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
            $this->defineButtonColumn('status'),
            $this->defineDropdownColumn('is_active', 'Status'),
            $this->defineActionsColumn(),
        ];
    }
}
