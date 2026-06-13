<?php

namespace App\DataTables\Admin\Report\Purchase;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AllPurchasesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'allpurchases.action')
             ->addColumn('edit_button', function($row) {
                $printUrl = route('print.purchase', Crypt::encrypt($row->id));
                
                return '<a href="'.$printUrl.'" class="btn btn-sm btn-info">Print <i class="fa fa-print"></i></a>';
            })

            ->addColumn('supplier_name', function($row) {
                return $row->purchase_payment && $row->purchase_payment->supplier
                    ? $row->purchase_payment->supplier->name
                    : '';
            })

            ->addColumn('amount', function($row) {
                return $row->purchase_payment 
                    ? number_format($row->purchase_payment->total_amount, 2) 
                    : number_format(0, 2);
            })

             ->editColumn('date', function($row)
            {
                return  Carbon::parse($row->date)->format('jS F, Y g:i A');
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
    public function query(Purchase $model): QueryBuilder
    {
        $query = $model->newQuery()
                 ->with(['purchase_payment', 'supplier'])
                 ->orderBy('date', 'desc');

        // Apply filters
        if ($purchaseinvoiceId = $this->request()->get('purchase_id')) {
            $query->where('id', $purchaseinvoiceId);
        }

        if ($supplierId = $this->request()->get('supplier_id')) {
            $query->whereHas('purchase_payment.supplier', function ($q) use ($supplierId) {
                $q->where('id', $supplierId);
            });
        }

        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('date', '>=', $fromDate);
        }

        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('date', '<=', $toDate);
        }

        return $query;

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('allpurchases-table')
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
            Column::make('date'),
            Column::make('purchase_no'),
            Column::make('supplier_name')->title('Supplier Name'),
            Column::make('amount')->title('Amount (Gh¢)'),
            Column::make('edit_button')->title('Actions')
            ->searchable(false)->printable(false)->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AllPurchases_' . date('YmdHis');
    }
}
