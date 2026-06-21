<?php

namespace App\DataTables\Admin\Settings;

use App\Models\CashReconciliation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CashReconciliationDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('cashier_name', function ($row) {
                return $row->user->name ?? '';
            })
            ->addColumn('approved_by_name', function ($row) {
                return $row->approvedBy->name ?? '';
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name ?? '';
            })
            ->addColumn('action_button', function ($row) {
                $showUrl = route('cash-reconciliations.show', Crypt::encryptString((string) $row->id));
                $editUrl = route('cash-reconciliations.edit', Crypt::encryptString((string) $row->id));
                $canEdit = in_array($row->status, ['submitted', 'reopened'], true);

                $buttons = '<a class="btn btn-sm btn-primary" href="' . $showUrl . '"><i class="fas fa-eye"></i></a>';

                if ($canEdit) {
                    $buttons .= ' <a class="btn btn-sm btn-success" href="' . $editUrl . '"><i class="fas fa-edit"></i></a>';
                }

                return $buttons;
            })
            ->editColumn('business_date', function ($row) {
                return $row->business_date ? Carbon::parse($row->business_date)->format('d-m-Y') : '';
            })
            ->editColumn('expected_cash_sales', function ($row) {
                return number_format((float) $row->expected_cash_sales, 2);
            })
            ->editColumn('expected_cash_expenses', function ($row) {
                return number_format((float) $row->expected_cash_expenses, 2);
            })
            ->editColumn('expected_closing_cash', function ($row) {
                return number_format((float) $row->expected_closing_cash, 2);
            })
            ->editColumn('physical_counted_cash', function ($row) {
                return number_format((float) $row->physical_counted_cash, 2);
            })
            ->editColumn('variance', function ($row) {
                $value = (float) $row->variance;
                $class = $value < 0 ? 'text-danger' : ($value > 0 ? 'text-success' : 'text-primary');
                return '<span class="' . $class . '">' . number_format($value, 2) . '</span>';
            })
            ->editColumn('variance_type', function ($row) {
                if ($row->variance_type === 'shortage') {
                    return '<span class="badge bg-danger">Shortage</span>';
                }

                if ($row->variance_type === 'overage') {
                    return '<span class="badge bg-success">Overage</span>';
                }

                return '<span class="badge bg-primary">Balanced</span>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status === 'approved') {
                    return '<span class="badge bg-success">Approved</span>';
                }

                if ($row->status === 'reopened') {
                    return '<span class="badge bg-warning text-dark">Reopened</span>';
                }

                return '<span class="badge bg-info text-dark">Submitted</span>';
            })
            ->rawColumns(['variance', 'variance_type', 'status', 'action_button'])
            ->setRowId('id');
    }

    public function query(CashReconciliation $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'createdBy', 'approvedBy'])
            ->orderBy('business_date', 'desc')
            ->orderBy('id', 'desc');

        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('business_date', '>=', $fromDate);
        }

        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('business_date', '<=', $toDate);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cash-reconciliation-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'row'l>Bfrtip",
                'buttons' => ['colvis', 'excel', 'csv', 'reset', 'reload'],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('business_date')->title('Date'),
            Column::make('cashier_name')->title('Cashier'),
            Column::make('expected_cash_sales')->title('Expected Sales'),
            Column::make('expected_cash_expenses')->title('Cash Expenses'),
            Column::make('expected_closing_cash')->title('Expected Closing'),
            Column::make('physical_counted_cash')->title('Physical Cash'),
            Column::make('variance')->title('Variance'),
            Column::make('variance_type')->title('Type'),
            Column::make('status')->title('Status'),
            Column::make('approved_by_name')->title('Approved By'),
            // Column::make('created_by_name')->title('Created By'),
            Column::make('action_button')->title('Action')->searchable(false)->printable(false)->exportable(false),
        ];
    }

    protected function filename(): string
    {
        return 'CashReconciliation_' . date('YmdHis');
    }
}
