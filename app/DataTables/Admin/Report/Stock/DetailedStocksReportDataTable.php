<?php

namespace App\DataTables\Admin\Report\Stock;

use App\Models\StockMovement;
use Carbon\Carbon;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DetailedStocksReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Results from query() method.
     */
        public function dataTable($query): DataTableAbstract
            {
                return datatables()->eloquent($query)
                    ->addColumn('stock_in', function ($row) {
                        return $row->transaction_type === 'Purchase' ? $row->quantity : 0;
                    })
                    ->addColumn('stock_out', function ($row) {
                        return $row->transaction_type === 'Sale' ? $row->quantity : 0;
                    })
                    ->editColumn('date', function ($row) {
                        return $row->date ? Carbon::parse($row->date)->format('d M Y, h:i A') : null;
                    })
                    ->editColumn('product_name', fn($row) => $row->product_name)
                    ->editColumn('category', fn($row) => $row->category)
                    ->editColumn('unit', fn($row) => $row->unit)
                    ->editColumn('transaction_type', fn($row) => $row->transaction_type)
                    ->editColumn('created_by', fn($row) => $row->created_by)
                    ->rawColumns(['stock_in','stock_out','date','product_name','category','unit','transaction_type','created_by'])
                    ->setRowId('id');
            }


    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        $query = StockMovement::query();

        // Apply filters
        if ($productId = $this->request()->get('product_id')) {
            $query->where('product_id', $productId);
        }
        if ($categoryId = $this->request()->get('category_id')) {
            $query->where('category_id', $categoryId);
        }
        if ($transactionType = $this->request()->get('transaction_type')) {
            $query->where('transaction_type', $transactionType);
        }
        if ($createdBy = $this->request()->get('created_by')) {
            $query->where('created_by', $createdBy);
        }
        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('date', '<=', $toDate);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('detailedstocksreport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->footerCallback('function (row, data, start, end, display) {
                var api = this.api();

                var intVal = function (value) {
                    if (typeof value === "string") {
                        return value.replace(/[^0-9.-]/g, "") * 1;
                    }

                    if (typeof value === "number") {
                        return value;
                    }

                    return 0;
                };

                var totalStockIn = api.column(5, { page: "current" }).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                var totalStockOut = api.column(6, { page: "current" }).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                var formatValue = function (value) {
                    return Number(value).toLocaleString("en-US", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                };

                $(api.column(5).footer()).html(formatValue(totalStockIn));
                $(api.column(6).footer()).html(formatValue(totalStockOut));
            }')
            ->parameters([
                'dom'          => "<'row'l>Bfrtip",
                'buttons'      => [
                    'colvis',
                    ['extend' => 'excel', 'footer' => true],
                    ['extend' => 'csv', 'footer' => true],
                    'reset',
                    'reload'
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('product_name')->title('Product'),
            Column::make('category')->title('Category'),
            Column::make('unit')->title('Unit'),
            Column::make('transaction_type')->title('Type'),
            Column::make('date')->title('Date'),
            Column::make('stock_in')->title('Stock In'),
            Column::make('stock_out')->title('Stock Out'),
            Column::make('created_by')->title('Created By'),
        ];
    }

    protected function filename(): string
    {
        return 'DetailedStocksReport_' . date('YmdHis');
    }
}
