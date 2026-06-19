<?php

namespace App\DataTables\Admin\Report\Stock;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StocksDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'stocks.action')

           ->addColumn('buying_total', function($row) {
                return '<span class="btn btn-sm btn-primary">'
                    .($row->purchasedetails_sum_buying_qty ?? 0).'</span>';
            })

            ->addColumn('selling_total', function($row) {
                return '<span class="btn btn-sm btn-danger">'
                    .($row->invoicedetails_sum_selling_qty ?? 0).'</span>';
            })

            ->addColumn('current_stock', function($row) {
                $buying_total = $row->purchasedetails_sum_buying_qty ?? 0;
                $selling_total = $row->invoicedetails_sum_selling_qty ?? 0;
                $stock = $buying_total - $selling_total;

                return '<span class="btn btn-sm btn-info">'.$stock.'</span>';
            })

            ->rawColumns(['action', 'buying_total', 'selling_total', 'current_stock'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['category', 'brand', 'unit'])
            ->withSum('purchasedetails', 'buying_qty')
            ->withSum('invoicedetails', 'selling_qty')
            ->orderBy('products.name', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('stocks-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                   ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom'          => "<'row'l>Bfrtip",  //row counter button
                        'buttons'      => ['colvis', 'excel', 'csv', 'reset', 'reload'],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
           Column::make('name')->title('Product Name'),
            Column::make('category.name')->title('Category'),
            Column::make('unit.name')->title('Unit'),
            Column::make('buying_total')->title('Stock In'),
            Column::make('selling_total')->title('Stock Out'),
            Column::make('current_stock')->title('Current Stock'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Stocks_' . date('YmdHis');
    }
}
