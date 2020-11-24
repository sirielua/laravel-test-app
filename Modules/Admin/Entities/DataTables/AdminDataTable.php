<?php

/**
 *
 * Controller implementation:
 *
 * Single action version:
 *
 * public function index(UserDataTable $dataTable)
 * {
 *      return $dataTable->render('users.index');
 * }
 *
 * or if you want separate action for ajax request:
 *
 * public function index(Request $request, UserDataTable $dataTable)
 * {
 *      return view('admin::users.index', ['dataTable' => $dataTable->html()]);
 * }
 *
 * public function grid(Request $request, UserDataTable $dataTable)
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
            ->parameters(['aaa' => 'bbbb'])
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

    protected function defineButtonColumn($attribute, $title = null, $width = 100)
    {
        if (is_null($title)) {
            $title = Column::titleFormat($attribute);
        }

        return Column::make($attribute)
            ->title($title)
            ->orderable(true)
            ->searchable(true)
            ->exportable(true)
            ->printable(true)
            ->width($width);
    }

    /**
     * [
        'label' => 'Activate',
        'style' => '' // button|outline|no-border|badge|pill, basic is default style,
        'tag' => '' // primary|secondary|success|info|warning|danger|focus|light|dark|link,
        'route' => 'activate', // see @routes for routes list
        'verb' => 'patch', // get|post|put|patch, get is default verb
       ]
     *
     * @param type $model
     * @param array $options
     */
    protected function renderButtonCol($model, array $options)
    {
        $routes = $this->routes($model);
        $this->filterButtonOptions($options);

        return view('admin::_templates.datatables.button', [
            'label' => $options['label'],
            'style' => $options['style'], // button|outline|no-border|badge|pill, basic is default style,
            'tag' => $options['tag'], // primary|secondary|success|info|warning|danger|focus|light|dark|link,
            'route' => $routes[$options['route']],
            'verb' => $options['verb'],
        ])->render();
    }

    private function filterButtonOptions(array &$options)
    {
        $options['style'] = $options['style'] ?? 'badge';
        $options['tag'] = $options['tag'] ?? 'primary';
        $options['verb'] = $options['verb'] ?? 'get';
    }

    protected function defineDropdownColumn($attribute, $title = null, $width = 100)
    {
        if (is_null($title)) {
            $title = Column::titleFormat($attribute);
        }

        return Column::make($attribute)
            ->title($title)
            ->orderable(true)
            ->searchable(true)
            ->exportable(true)
            ->printable(true)
            ->width($width);
    }

    /**
     * Options example:
     *
     * [
        'label' => 'Options',
        'style' => '' // basic|outline, basic is default style,
        'tag' => '' // primary|secondary|success|info|warning|danger|focus|light|dark|link,
        'options' => [
            [
                'label' => 'Activate',
                'tag' => 'success',
                'route' => 'activate', // see @routes for routes list
                'verb' => 'patch', // get|post|put|patch, get is default verb
            ],
            [
                'label' => 'Deactivate',
                'tag' => 'danger',
                'route' => 'deactivate',
                'verb' => 'patch',
            ],
            [
                'label' => 'Edit',
                'tag' => 'primary',
                'route' => 'update',
            ],
        ]
     *
     * @param type $model
     * @param array $options
     */
    protected function renderDropdownCol($model, array $options)
    {
        $routes = $this->routes($model);
        $this->filterDropdownOptions($options);

        return view('admin::_templates.datatables.dropdown', ['options' => $options, 'routes' => $routes])->render();
    }

    private function filterDropdownOptions(array &$options)
    {
        $options['style'] = $options['style'] ?? 'basic';
        $options['tag'] = $options['tag'] ?? 'primary';
        $options['options'] = $options['options'] ?? [];

        foreach ($options['options'] as $key => $option) {
            $options['options'][$key]['tag'] = $option['tag'] ?? null;
            $options['options'][$key]['verb'] = $option['verb'] ?? 'get';
        }
    }

    public function htmlBuilder()
    {
        return app('datatables.html');
    }
}
