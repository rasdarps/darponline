<?php

namespace App\DataTables\Admin\Settings;

use App\Models\ExpenseVoucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseVoucherDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('created_by_name', function ($row) {
                if (!Gate::allows('user-audit-view')) {
                    return '';
                }
                $viewer = auth()->user();
                $viewerIsSuperAdmin = $viewer && strtolower((string) ($viewer->user_type ?? '')) === 'super admin';
                $createdByUser = $row->createdBy;

                if (!$viewerIsSuperAdmin && $createdByUser && strtolower((string) ($createdByUser->user_type ?? '')) === 'super admin') {
                    return '';
                }

                return $createdByUser ? $createdByUser->name : '';
            })
            ->addColumn('items_count', function ($row) {
                return (string) ($row->items_count ?? 0);
            })
            ->addColumn('edit_button', function ($row) {
                $showUrl = route('expense-vouchers.show', Crypt::encrypt($row->id));
                $printUrl = route('expense-vouchers.print', Crypt::encrypt($row->id));
                $editUrl = route('expense-vouchers.edit', Crypt::encrypt($row->id));
                $deleteUrl = route('expense-vouchers.destroy', Crypt::encrypt($row->id));
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $buttons = [];

                if (Gate::allows('expense-list')) {
                    $buttons[] = '
                    <a style="border:none; background:#0d6efd; cursor:pointer;" class="btn btn-sm btn-primary" href="' . $showUrl . '">
                        <i class="fas fa-eye" style="color:#fff; justify-content:center;"></i>
                    </a>';
                    $buttons[] = '
                    <a style="border:none; background:#6f42c1; cursor:pointer;" class="btn btn-sm btn-secondary" href="' . $printUrl . '" target="_blank" rel="noopener">
                        <i class="fas fa-print" style="color:#fff; justify-content:center;"></i>
                    </a>';
                }

                if (Gate::allows('expense-edit')) {
                    $buttons[] = '
                    <form style="display:inline-block" action="' . $editUrl . '" method="GET">
                        ' . $csrf . '
                        <button style="border:none; background:green; cursor:pointer;" class="table-edit-btn btn btn-sm btn-success">
                            <i class="fas fa-edit" style="color:#fff; justify-content:center;"></i>
                        </button>
                    </form>';
                }

                if (Gate::allows('expense-delete')) {
                    $buttons[] = '
                    <form style="display:inline-block" action="' . $deleteUrl . '" method="POST">
                        ' . $csrf . '
                        ' . $method . '
                        <button type="button" style="border:none; background:red; cursor:pointer;" class="btn-delete delete-confirm-btn btn btn-sm btn-danger">
                            <i class="fas fa-trash" style="color:#fff; justify-content:center;"></i>
                        </button>
                    </form>';
                }

                if (empty($buttons)) {
                    return '<span class="text-muted">No actions</span>';
                }

                return implode(' ', $buttons);
            })
            ->editColumn('voucher_date', function ($row) {
                return $row->voucher_date
                    ? Carbon::parse($row->voucher_date)->format('d-m-Y')
                    : '';
            })
            ->editColumn('total_amount', function ($row) {
                return number_format((float) $row->total_amount, 2);
            })
            ->rawColumns(['edit_button'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ExpenseVoucher $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('createdBy')
            ->withCount('items')
            ->orderBy('voucher_date', 'desc')
            ->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expense-voucher-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'row'l>Bfrtip",
                'buttons' => ['colvis', 'excel', 'csv', 'reset', 'reload'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('voucher_date')->title('Date'),
            Column::make('reference_no')->title('Reference No'),
            Column::make('items_count')->title('Lines'),
            Column::make('total_amount')->title('Total Amount'),
            Column::make('notes')->title('Notes'),
        ];

        if (Gate::allows('user-audit-view')) {
            array_splice($columns, 4, 0, [Column::make('created_by_name')->title('Created By')]);
        }

        if (Gate::allows('expense-list') || Gate::allows('expense-edit') || Gate::allows('expense-delete')) {
            $columns[] = Column::make('edit_button')->title('Actions')
                ->searchable(false)->printable(false)->exportable(false);
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ExpenseVoucher_' . date('YmdHis');
    }
}
