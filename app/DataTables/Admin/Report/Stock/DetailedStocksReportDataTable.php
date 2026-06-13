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
    // public function dataTable($query): DataTableAbstract
    // {
    //     return datatables()->eloquent($query)
    //         ->addColumn('action', 'detailedstocksreport.action')
    //         ->editColumn('product_name', fn($row) => $row->product_name)
    //         ->editColumn('category', fn($row) => $row->category)
    //         ->editColumn('unit', fn($row) => $row->unit)
    //         ->editColumn('transaction_type', fn($row) => $row->transaction_type)
    //         ->editColumn('date', function($row) {
    //         // Format with Carbon: Day Month Year, Time
    //         return Carbon::parse($row->date)->format('d M Y, h:i A');
    //     })
    //         ->editColumn('quantity', fn($row) => $row->quantity)
    //         ->editColumn('created_by', fn($row) => $row->created_by)
    //         ->rawColumns(['product_name','category','unit','transaction_type','date','quantity','created_by'])
    //         ->setRowId('id');
    // }

//     public function dataTable($query): \Yajra\DataTables\DataTableAbstract
// {
//     return datatables()->eloquent($query)
//         ->addColumn('stock_in', function($row) {
//             return $row->transaction_type === 'Purchase' ? $row->quantity : 0;
//         })
//         ->addColumn('stock_out', function($row) {
//             return $row->transaction_type === 'Sale' ? $row->quantity : 0;
//         })
       
//         ->editColumn('date', fn($row) => \Carbon\Carbon::parse($row->date)->format('d M Y, h:i A'))
//         ->rawColumns(['stock_in','stock_out','date'])
//         ->setRowId('id');
// }

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
            ->parameters([
                'dom'          => "<'row'l>Bfrtip",
                'buttons'      => ['colvis', 'excel', 'csv', 'reset', 'reload'],
            ]);
    }

    public function getColumns(): array
    {
        return [
            // Column::make('product_name')->title('Product'),
            // Column::make('category')->title('Category'),
            // Column::make('unit')->title('Unit'),
            // Column::make('transaction_type')->title('Type'),
            // Column::make('date')->title('Date'),
            // Column::make('quantity')->title('Quantity'),
            // Column::make('created_by')->title('Created By'),

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
