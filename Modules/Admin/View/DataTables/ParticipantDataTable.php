<?php

namespace Modules\Admin\View\DataTables;

use Modules\Admin\Components\DataTables\AdminDataTable;
use Yajra\DataTables\Html\Column;
use App\Models\Participant;

class ParticipantDataTable extends AdminDataTable
{
    protected $tableId = 'participants-table';

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
       return app()->make(Participant::class)->newQuery();
    }

    protected function getAjaxUrl()
    {
        return route('admin::participants.index');
    }

    protected function routes($model)
    {
        return [
            'show' => route('admin::participants.show', $model->id),
            'destroy' => route('admin::participants.destroy', $model->id),
            'force-confirm' => route('admin::participants.force-confirm', $model->id),
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

            ->editColumn('registration_status', function ($model) {
                $confirmed = ($model->registration_status == Participant::STATUS_CONFIRMED);
                return $this->renderButtonCol($model, [
                    'label' => $confirmed ? 'Confirmed' : 'Unconfirmed',
                    'tag' => $confirmed ? 'success' : 'secondary',
                    'route' => $confirmed ? null : 'force-confirm',
                    'verb' => 'patch',
                ]);
            })

            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d H:i P');
            })

            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format('Y-m-d H:i P');
            })

            ->rawColumns(['selected', 'actions', 'registration_status'], $mergeDefaults = true);
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
//            Column::make('id')->search('id'),
            Column::make('first_name')->search('first_name'),
            Column::make('last_name')->search('last_name'),
            Column::make('phone')->search('phone'),
            Column::make('created_at'),
            Column::make('updated_at'),
            $this->defineButtonColumn('registration_status', 'Status'),
            $this->defineActionsColumn(),
        ];
    }
}
