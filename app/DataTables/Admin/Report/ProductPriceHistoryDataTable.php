<?php

namespace App\DataTables\Admin\Report;

use App\Models\ProductPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPriceHistoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'productpricehistory.action')
            ->addColumn('created_by_name', function ($row) {
                return optional($row->createdBy)->name ?? 'N/A';
            })
             ->editColumn('created_at', function($row)
            {
                return  Carbon::parse($row->created_at)->format('jS F, Y g:i A');
            })
            ->editColumn('updated_at', function($row) {
                return $row->updated_at
                    ? Carbon::parse($row->updated_at)->format('jS F, Y g:i A')
                    : '';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductPrice $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->select('product_prices.*')
            ->with('product')
            ->with(['createdBy'])
            ->latest('created_at');

        if ($productId = $this->request()->get('product_id')) {
            $query->where('product_prices.product_id', $productId);
        }

        if ($createdBy = $this->request()->get('created_by')) {
            $query->where('product_prices.created_by', $createdBy);
        }

        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('product_prices.created_at', '>=', $fromDate);
        }

        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('product_prices.created_at', '<=', $toDate);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('productpricehistory-table')
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
            Column::make('created_at')->title('Date Created'),
            Column::make('product.name')->title('Product Name'),
            Column::make('cost_price')->title('Cost Price'),
            Column::make('selling_price')->title('Selling Price'),
            Column::make('created_by_name')->title('Created By'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductPriceHistory_' . date('YmdHis');
    }
}
