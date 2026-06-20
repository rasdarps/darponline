<?php

namespace App\DataTables\Admin\Settings;

use App\Models\StockAdjustment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockAdjustmentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy ? $row->createdBy->name : '';
            })
            ->addColumn('type_badge', function ($row) {
                if ($row->adjustment_type === 'increase') {
                    return '<span class="badge bg-success">Increase</span>';
                }
                if ($row->adjustment_type === 'mixed') {
                    return '<span class="badge bg-warning text-dark">Mixed</span>';
                }
                return '<span class="badge bg-danger">Decrease</span>';
            })
            ->addColumn('action_button', function ($row) {
                $showUrl = route('stock-adjustments.show', Crypt::encrypt($row->id));
                $printUrl = route('stock-adjustments.print', Crypt::encrypt($row->id));
                $editUrl = route('stock-adjustments.edit', Crypt::encrypt($row->id));
                $deleteUrl = route('stock-adjustments.destroy', Crypt::encrypt($row->id));
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <a class="btn btn-sm btn-primary" href="' . $showUrl . '"><i class="fas fa-eye"></i></a>
                    <a class="btn btn-sm btn-secondary" target="_blank" href="' . $printUrl . '"><i class="fas fa-print"></i></a>
                    <a class="btn btn-sm btn-success" href="' . $editUrl . '"><i class="fas fa-edit"></i></a>
                    <form style="display:inline-block" action="' . $deleteUrl . '" method="POST">
                        ' . $csrf . '
                        ' . $method . '
                        <button type="button" class="btn-delete delete-confirm-btn btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                ';
            })
            ->editColumn('adjustment_date', function ($row) {
                return $row->adjustment_date
                    ? Carbon::parse($row->adjustment_date)->format('d-m-Y')
                    : '';
            })
            ->editColumn('total_qty', function ($row) {
                return number_format((float) $row->total_qty, 2);
            })
            ->editColumn('total_value', function ($row) {
                return number_format((float) $row->total_value, 2);
            })
            ->rawColumns(['type_badge', 'action_button'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(StockAdjustment $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('createdBy')
            ->orderBy('adjustment_date', 'desc')
            ->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock-adjustment-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'row'l>Bfrtip",
                'buttons' => ['colvis', 'excel', 'csv', 'reset', 'reload'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('adjustment_date')->title('Date'),
            Column::make('reference_no')->title('Reference No'),
            Column::make('type_badge')->title('Type'),
            Column::make('reason')->title('Reason'),
            Column::make('total_qty')->title('Total Qty'),
            Column::make('total_value')->title('Total Value'),
            Column::make('created_by_name')->title('Created By'),
            Column::make('action_button')->title('Actions')->searchable(false)->printable(false)->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'StockAdjustment_' . date('YmdHis');
    }
}
