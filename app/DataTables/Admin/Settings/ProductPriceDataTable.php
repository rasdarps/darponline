<?php

namespace App\DataTables\Admin\Settings;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPriceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'productprice.action')
            ->addColumn('created_by_name', function ($row) {
                return optional($row->createdBy)->name ?? 'N/A';
            })
            ->addColumn('edit_button', function($row) {
                    $editUrl = route('product-prices.edit', Crypt::encrypt($row->id));
                    $deleteUrl = route('product-prices.destroy', Crypt::encrypt($row->id));
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <form style="display:inline-block" action="'.$editUrl.'" method="GET">
                            '.$csrf.'
                            <button style="border:none; background:green; cursor:pointer;" class="table-edit-btn btn btn-sm btn-success">
                                <i class="fas fa-edit" style="color:#fff; justify-content:center;"></i>
                            </button>
                        </form>
                        <form style="display:inline-block" action="'.$deleteUrl.'" method="POST">
                            '.$csrf.'
                            '.$method.'
                            <button type="button" style="border:none; background:red; cursor:pointer;" class="btn-delete btn btn-sm btn-danger">
                                <i class="fas fa-trash" style="color:#fff; justify-content:center;"></i>
                            </button>
                        </form>
                    ';
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

             ->rawColumns(['edit_button'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['createdBy'])
            ->latest('created_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('productprice-table')
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
            Column::make('cost_price')->title('Cost Price'),
            Column::make('selling_price')->title('Selling Price'),
            Column::make('created_by_name')->title('Created By'),
            Column::make('created_at'),
            Column::make('edit_button')->title('Actions')
            ->searchable(false)->printable(false)->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductPrice_' . date('YmdHis');
    }
}
