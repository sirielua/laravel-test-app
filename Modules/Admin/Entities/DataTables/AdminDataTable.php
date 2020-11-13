<?php

/**
 *
 * Controller implementation:
 *
 * Single action version:
 *
 * public function index(UsersDataTable $dataTable)
 * {
 *      return $dataTable->render('users.index');
 * }
 *
 * or if you want separate action for ajax request:
 *
 * public function index(Request $request, UsersDataTable $dataTable)
 * {
 *      return view('admin::users.index', ['dataTable' => $dataTable->html()]);
 * }
 *
 * public function grid(Request $request, UsersDataTable $dataTable)
 * {
 *      $isAjax = $request->ajax() && $request->wantsJson();
 *      return $isAjax ? $dataTable->ajax() : abort(403);
 * }
 *
 * then you need to render your datatable html:
 *
 * {{ $dataTable->table($attributes = [], $drawFooter = false, $drawSearch = true) }}
 *
 * and add automatically generated javascript needed for table to operate
 * @push('scripts')
 *      {{ $dataTable->scripts() }}
 * @endpush
 *
 *
 * return $dataTable->render('users.index');
 */
namespace Modules\Admin\Entities\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use Illuminate\Database\Eloquent\Model;

abstract class AdminDataTable extends DataTable
{
    protected $tableId = 'admin-data-table';

    protected $checkboxColName = 'selected';

    protected $actionsColName = 'actions';

    protected $pageLength = 25;

    abstract protected function getAjaxUrl();

    /**
     * Get query source of dataTable.
     *
     * Example realization:
     *
     * public function query()
     * {
     *     return app()->make(User::class)->newQuery();
     * }
     *
     * @return mixed
     */
    abstract public function query();

    /**
     * Build DataTable class.
     *
     * Example realization:
     *
     * public function dataTable($query)
     * {
     *      return datatables()
     *          ->eloquent($query)
     *          ->addColumn('selected', function ($model) {
     *              return $this->renderCheckboxCol($model);
     *          })
     *          ->addColumn('actions', function ($model) {
     *              return $this->renderActionsCol($model);
     *          })
     *          ->editColumn('created_at', function ($model) {
     *              return date('Y-m-d H:i P', strtotime($model->created_at) );
     *          })
     *          ->editColumn('updated_at', function ($model) {
     *              return date('Y-m-d H:i P', strtotime($model->updated_at) );
     *          })
     *          ->rawColumns(['selected', 'actions'], $merge = true);
     * }
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    abstract public function dataTable($query);

    /**
     * Define columns to display in html table
     *
     * Example realization:
     *
     * protected function defineColumns()
     * {
     *      return [
     *          $this->defineCheckboxCol(),
     *          Column::make('id')->search('id'),
     *          Column::make('name')->search('name'),
     *          Column::make('email')->search('email'),
     *          Column::make('created_at'),
     *          Column::make('updated_at'),
     *          $this->defineActionsColumn(),
     *      ];
     * }
     *
     * @return array
     */
    abstract protected function defineColumns();

    protected function routes($model)
    {
        return [
            'show' => url('/{id}', ['id' => $model->id]),
            'copy' => url('/{id}/copy', ['id' => $model->id]),
            'update' => url('/{id}/edit', ['id' => $model->id]),
            'delete' => url('/{id}', ['id' => $model->id]),
        ];
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->processing(false)
            ->parameters([])
            ->setTableId($this->tableId)
            ->columns($this->defineColumns())
            ->minifiedAjax()
            ->ajax(['url' => $this->getAjaxUrl()])
            ->orderBy(1)
            ->pageLength($this->pageLength);

//        return $this->builder()->buttons(
//            // Default Buttons
//            Button::make('print'), // print, pdf, excel, csv, copy
//            // Editor Buttons
//            Button::make('create'), // create, export, reset, reload, export, reset
//        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Table_' . date('YmdHis');
    }

    protected function defineCheckboxCol($name = 'selected')
    {
        return Column::make($name)
            ->titleAttr($name)
            ->title('<input type="checkbox" class="check-all" />')
            ->orderable(false)
            ->searchable(false)
            ->exportable(false)
            ->printable(false);
    }

    protected function renderCheckboxCol($model)
    {
        return '<input type="checkbox" value="' . $model->id . '" name="selected[]">';
    }

    protected function defineActionsColumn($name = 'actions', $width = 250)
    {
        return Column::computed($name)
            ->searchable(false)
            ->exportable(false)
            ->printable(false)
            ->width($width);
    }

    protected function renderActionsCol($model)
    {
        $routes = $this->routes($model);
        return view('admin::_templates.datatables.actions', ['routes' => $routes])->render();
    }

    protected function defineSwitchColumn($name, $width = 100)
    {
        return Column::computed($name)
            ->searchable(true)
            ->exportable(true)
            ->printable(true)
            ->width($width);
    }

//    protected function renderSwitchCol($model)
//    {
//
//    }

    protected function defineSelectColumn($name, $width = 100)
    {
        return Column::computed($name)
            ->searchable(true)
            ->exportable(true)
            ->printable(true)
            ->width($width);
    }

//    protected function renderSelectCol($model)
//    {
//
//    }

    public function htmlBuilder()
    {
        return app('datatables.html');
    }
}
