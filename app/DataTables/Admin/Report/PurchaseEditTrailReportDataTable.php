<?php

namespace App\DataTables\Admin\Report;

use App\Models\PurchaseEditAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseEditTrailReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('purchase_no', function ($row) {
                return $row->purchase_no ?: optional($row->purchase)->purchase_no ?: '#' . $row->purchase_id;
            })
            ->addColumn('edited_by_name', function ($row) {
                return $row->editor ? $row->editor->name : 'System';
            })
            ->addColumn('edited_at_formatted', function ($row) {
                return $row->edited_at ? $row->edited_at->format('d M Y h:i A') : '-';
            })
            ->addColumn('line_summary', function ($row) {
                $lines = $row->lines ?? collect();
                $summary = [];

                foreach ($lines as $line) {
                    $product = $line->product_name_snapshot ?: optional($line->product)->name ?: 'Item';
                    $summary[] = $product . ' · Qty ' . number_format((float) $line->quantity_from, 2) . '→' . number_format((float) $line->quantity_to, 2) . ' · Price ' . number_format((float) $line->unit_cost_from, 2) . '→' . number_format((float) $line->unit_cost_to, 2);
                }

                if (empty($summary)) {
                    return '-';
                }

                return '<div class="small text-muted">' . e(implode('<br>', $summary)) . '</div>';
            })
            ->addColumn('delta_total', function ($row) {
                $delta = (float) ($row->after_total ?? 0) - (float) ($row->before_total ?? 0);
                $class = $delta >= 0 ? 'text-success' : 'text-danger';

                return '<span class="' . $class . '">' . ($delta >= 0 ? '+' : '') . number_format($delta, 2) . '</span>';
            })
            ->editColumn('before_total', function ($row) {
                return number_format((float) ($row->before_total ?? 0), 2);
            })
            ->editColumn('after_total', function ($row) {
                return number_format((float) ($row->after_total ?? 0), 2);
            })
            ->editColumn('reason', function ($row) {
                return $row->reason ?: '-';
            })
            ->rawColumns(['line_summary', 'delta_total'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PurchaseEditAudit $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['editor', 'purchase', 'lines.product'])
            ->orderByDesc('edited_at');

        if ($purchaseId = $this->request()->get('purchase_id')) {
            $query->where('purchase_id', $purchaseId);
        }

        if ($editedBy = $this->request()->get('edited_by')) {
            $query->where('edited_by', $editedBy);
        }

        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('edited_at', '>=', $fromDate);
        }

        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('edited_at', '<=', $toDate);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('purchaseedittrail-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                'buttons' => [
                    'colvis',
                    ['extend' => 'excel', 'footer' => true],
                    ['extend' => 'csv', 'footer' => true],
                    'reset',
                    'reload',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('purchase_no')->title('Purchase'),
            Column::make('edited_by_name')->title('Edited By'),
            Column::make('edited_at_formatted')->title('Edited At'),
            Column::make('reason')->title('Reason'),
            Column::make('before_total')->title('Before Total'),
            Column::make('after_total')->title('After Total'),
            Column::make('delta_total')->title('Delta Total'),
            Column::make('line_summary')->title('Changes')->searchable(false)->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PurchaseEditTrail_' . date('YmdHis');
    }
}
