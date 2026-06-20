<?php

namespace App\DataTables\Admin\Report\Sales;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AllSalesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'allsales.action')

            ->addColumn('edit_button', function($row) {
                $printUrl = route('print.invoice', Crypt::encrypt($row->id));

                $html = '<a href="' . $printUrl . '" class="btn btn-sm btn-info">Print <i class="fa fa-print"></i></a>';

                if (!$row->cancelled_at) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $html .= ' <button type="button"
                        class="btn btn-sm btn-warning btn-cancel-invoice ms-1"
                        data-id="' . $encryptedId . '"
                        data-no="' . e($row->invoice_no) . '">
                        <i class="fas fa-ban"></i> Cancel
                    </button>';
                }

                return $html;
            })

            ->addColumn('cancelled_status', function($row) {
                if ($row->cancelled_at) {
                    return '<span class="badge bg-danger" title="' . e($row->cancellation_reason) . '">Cancelled</span>';
                }
                return '<span class="badge bg-success">Active</span>';
            })

            ->addColumn('customer_name', function($row) {
                return $row->payment && $row->payment->customer
                    ? $row->payment->customer->name
                    : '';
            })

            ->addColumn('created_by_name', function($row) {
                return $row->createdBy ? $row->createdBy->name : '';
            })

            ->addColumn('cancelled_by_name', function($row) {
                return $row->cancelledBy ? $row->cancelledBy->name : '';
            })

            ->addColumn('amount', function($row) {
                return $row->payment 
                    ? number_format($row->payment->total_amount, 2) 
                    : number_format(0, 2);
            })

             ->editColumn('date', function($row)
            {
                return  Carbon::parse($row->date)->format('d-m-Y');
            })

              ->editColumn('created_at', function($row)
            {
                return  Carbon::parse($row->created_at)->format('d-m-Y g:i A');
            })
            ->editColumn('updated_at', function($row) {
                return $row->updated_at
                    ? Carbon::parse($row->updated_at)->format('d-m-Y g:i A')
                    : '';
            })

            
            ->rawColumns(['amount', 'edit_button', 'cancelled_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Invoice $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['payment.customer', 'createdBy', 'cancelledBy'])
            ->orderBy('date', 'ASC');

        // Apply filters
        if ($invoiceId = $this->request()->get('invoice_id')) {
            $query->where('id', $invoiceId);
        }

        if ($customerId = $this->request()->get('customer_id')) {
            $query->whereHas('payment.customer', function ($q) use ($customerId) {
                $q->where('id', $customerId);
            });
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

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('allsales-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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

                        var amountColIndex = api.columns().indexes().toArray().find(function (idx) {
                            return (api.settings()[0].aoColumns[idx] || {}).data === "amount";
                        });

                        if (typeof amountColIndex === "undefined") {
                            return;
                        }

                        var totalAmount = api.column(amountColIndex, { page: "current" }).data().reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                        var formatValue = function (value) {
                            return Number(value).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        };

                        $(api.column(amountColIndex).footer()).html(formatValue(totalAmount));
                    }')
                    ->parameters([
                        'dom'          => "<'row'l>Bfrtip",  //row counter button
                        'buttons'      => [
                            'colvis',
                            ['extend' => 'excel', 'footer' => true],
                            ['extend' => 'csv', 'footer' => true],
                            'reset',
                            'reload'
                        ],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('date'),
            Column::make('invoice_no'),
            Column::make('sales_source')->title('Sales Source'),
            Column::make('customer_name')->title('Customer Name'),
            Column::make('amount')->title('Amount (Gh¢)'),
            // Column::make('cancelled_status')->title('Status')
            //     ->searchable(true)->printable(true)->exportable(true),
            Column::make('created_by_name')->title('Created By'),
            Column::make('cancelled_by_name')->title('Cancelled By')
                ->searchable(true)->printable(true)->exportable(true),
            Column::make('edit_button')->title('Actions')
                ->searchable(false)->printable(false)->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AllSales_' . date('YmdHis');
    }
}
