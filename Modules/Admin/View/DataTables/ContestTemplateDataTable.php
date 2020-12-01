<?php

namespace Modules\Admin\View\DataTables;

use Modules\Admin\Components\DataTables\AdminDataTable;
use Yajra\DataTables\Html\Column;
use App\Models\Contest;
use Illuminate\Support\Facades\Storage;

class ContestTemplateDataTable extends AdminDataTable
{
    protected $tableId = 'templates-table';

    protected $checkboxColName = 'selected';

    protected $actionsColName = 'actions';

    protected $defaultOrder = [4, 'asc'];

    protected $pageLength = 25;

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
       return app()->make(Contest::class)->newQuery();
    }

    protected function getAjaxUrl()
    {
        return route('admin::contest-templates.index');
    }

    protected function routes($model)
    {
        return [
            'show' => route('admin::contest-templates.show', $model->id),
            'edit' => route('admin::contest-templates.edit', $model->id),
            'destroy' => route('admin::contest-templates.destroy', $model->id),

            'activate' => route('admin::contest-templates.activate', $model->id),
            'deactivate' => route('admin::contest-templates.deactivate', $model->id),
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
                return $this->renderButtonCol($model, [
                    'label' => $model->is_active ? 'Active' : 'Inactive',
                    'tag' => $model->is_active ? 'success' : 'secondary',
                    'route' => $model->is_active ? 'deactivate' : 'activate',
                    'verb' => 'patch',
                ]);
            })

            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d H:i P');
            })

            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format('Y-m-d H:i P');
            })

            ->addColumn('headline', function ($model) {
                return $model->headline . '<hr />' . $model->subheadline;
            })

            ->addColumn('explaining_text', function ($model) {
                return $model->explaining_text . '<hr />' . ($model->banner ? '<img src="' . Storage::url($model->banner) . '" style="max-width: 400px;" />' : null);
            })

            ->rawColumns(['selected', 'actions', 'is_active', 'headline', 'explaining_text'], $mergeDefaults = true)
            ->blacklist(['selected', 'actions']); // unordarable, unsearchable
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
//            Column::make('id')->width(100)->search('id'),
            Column::make('headline')->search('headline'),
            Column::make('explaining_text')->orderable(false)->search('explaining_text'),
            $this->defineButtonColumn('is_active', 'Status'),
            Column::make('created_at'),
//            Column::make('updated_at'),
            $this->defineActionsColumn(),
        ];
    }
}
