<?php

namespace App\DataTables\Admin\Report;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProfitReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'profitreport.action')

            ->addColumn('customer_name', function($row) {
                return $row->payment && $row->payment->customer
                    ? $row->payment->customer->name
                    : '';
            })

            ->addColumn('created_by_name', function($row) {
                return $row->createdBy ? $row->createdBy->name : '';
            })

            ->addColumn('sales_total', function($row) {
                return number_format((float) ($row->sales_total ?? 0), 2);
            })

            ->addColumn('cost_total', function($row) {
                return number_format((float) ($row->cost_total ?? 0), 2);
            })

            ->addColumn('profit_total', function($row) {
                return number_format((float) ($row->profit_total ?? 0), 2);
            })

             ->editColumn('date', function($row)
            {
                return  Carbon::parse($row->date)->format('jS F, Y');
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

            
            ->rawColumns(['sales_total','cost_total','profit_total','edit_button'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Invoice $model): QueryBuilder
    {
        $salesSub = DB::table('payments')
            ->selectRaw('invoice_id, SUM(total_amount) as sales_total')
            ->groupBy('invoice_id');

        $costSub = DB::table('invoice_details as idt')
            ->selectRaw(
                'idt.invoice_id, SUM(idt.selling_qty * COALESCE((
                    SELECT pd.unit_cost_price
                    FROM purchase_details pd
                    WHERE pd.product_id = idt.product_id
                    ORDER BY pd.date DESC, pd.id DESC
                    LIMIT 1
                ), 0)) as cost_total'
            )
            ->groupBy('idt.invoice_id');

        $query = $model->newQuery()
            ->leftJoinSub($salesSub, 'sales', function ($join) {
                $join->on('sales.invoice_id', '=', 'invoices.id');
            })
            ->leftJoinSub($costSub, 'costs', function ($join) {
                $join->on('costs.invoice_id', '=', 'invoices.id');
            })
            ->select(
                'invoices.*',
                DB::raw('COALESCE(sales.sales_total, 0) as sales_total'),
                DB::raw('COALESCE(costs.cost_total, 0) as cost_total'),
                DB::raw('(COALESCE(sales.sales_total, 0) - COALESCE(costs.cost_total, 0)) as profit_total')
            )
            ->with(['payment.customer', 'createdBy'])
            ->orderBy('date', 'desc');

            // Apply filters
        if ($invoiceId = $this->request()->get('invoice_id')) {
            $query->where('invoices.id', $invoiceId);
        }

        if ($customerId = $this->request()->get('customer_id')) {
            $query->whereHas('payment.customer', function ($q) use ($customerId) {
                $q->where('id', $customerId);
            });
        }

        if ($createdBy = $this->request()->get('created_by')) {
            $query->where('invoices.created_by', $createdBy);
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
                    ->setTableId('profitreport-table')
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
            Column::make('invoice_no'),
            Column::make('customer_name')->title('Customer Name'),
            Column::make('created_by_name')->title('Created By'),
            Column::make('sales_total')->title('Net Sales (Gh¢)'),
            Column::make('cost_total')->title('COGS Latest Cost (Gh¢)'),
            Column::make('profit_total')->title('Profit (Gh¢)'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProfitReport_' . date('YmdHis');
    }
}
