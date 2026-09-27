<?php

namespace App\DataTables\Admin\Report\Sales;

use App\Models\SaleReceiptEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaleReceiptAuditDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('event_at', function ($row) {
                return $row->event_at ? Carbon::parse($row->event_at)->format('d-m-Y h:i A') : '';
            })
            ->addColumn('printed_by_name', function ($row) {
                return $row->event_type === 'original_print' ? ($row->operator->name ?? '') : '';
            })
            ->addColumn('reprinted_by_name', function ($row) {
                return $row->event_type === 'reprint' ? ($row->operator->name ?? '') : '';
            })
            ->addColumn('reprint_note', function ($row) {
                return $row->event_type === 'reprint' ? (string) ($row->reason ?? '') : '';
            })
            ->addColumn('verified_by_name', function ($row) {
                return $row->event_type === 'delivery' ? ($row->operator->name ?? '') : '';
            })
            ->addColumn('verified_at_display', function ($row) {
                return $row->event_type === 'delivery' && $row->event_at
                    ? Carbon::parse($row->event_at)->format('d-m-Y h:i A')
                    : '';
            })
            ->editColumn('delivery_status', function ($row) {
                if ($row->sale && $row->sale->delivered_at) {
                    return '<span class="badge bg-success">Delivered</span>';
                }

                return '<span class="badge bg-secondary">Pending</span>';
            })
            ->editColumn('event_type', function ($row) {
                return match ($row->event_type) {
                    'original_print' => 'Original Print',
                    'reprint' => 'Reprint',
                    'delivery' => 'Delivery',
                    default => str_replace('_', ' ', ucfirst((string) $row->event_type)),
                };
            })
            ->rawColumns(['delivery_status'])
            ->setRowId('id');
    }

    public function query(SaleReceiptEvent $model): QueryBuilder
    {
        $printCounts = SaleReceiptEvent::query()
            ->selectRaw('sale_id, COUNT(*) as total_print_count')
            ->whereIn('event_type', ['original_print', 'reprint'])
            ->groupBy('sale_id');

        $query = $model->newQuery()
            ->selectRaw('sale_receipt_events.*, COALESCE(print_counts.total_print_count, 0) as total_print_count')
            ->with(['sale.payment.customer', 'operator'])
            ->leftJoin('sales', 'sales.id', '=', 'sale_receipt_events.sale_id')
            ->leftJoin('users', 'users.id', '=', 'sale_receipt_events.operator_id')
            ->leftJoinSub($printCounts, 'print_counts', function ($join) {
                $join->on('print_counts.sale_id', '=', 'sale_receipt_events.sale_id');
            });

        $viewer = Auth::user();
        $viewerIsSuperAdmin = $viewer && strtolower((string) ($viewer->user_type ?? '')) === 'super admin';

        if (! $viewerIsSuperAdmin) {
            $query->whereRaw("LOWER(COALESCE(users.user_type, '')) <> 'super admin'");
        }

        if ($saleId = $this->request()->get('sale_id')) {
            $query->where('sale_receipt_events.sale_id', $saleId);
        }

        if ($operatorId = $this->request()->get('operator_id')) {
            $query->where('sale_receipt_events.operator_id', $operatorId);
        }

        if ($eventType = $this->request()->get('event_type')) {
            $query->where('sale_receipt_events.event_type', $eventType);
        }

        if ($fromDate = $this->request()->get('from_date')) {
            $query->whereDate('sale_receipt_events.event_at', '>=', $fromDate);
        }

        if ($toDate = $this->request()->get('to_date')) {
            $query->whereDate('sale_receipt_events.event_at', '<=', $toDate);
        }

        if (($deliveryStatus = $this->request()->get('delivery_status')) === 'delivered') {
            $query->whereNotNull('sales.delivered_at');
        } elseif ($deliveryStatus === 'pending') {
            $query->whereNull('sales.delivered_at');
        }

        return $query->orderByDesc('sale_receipt_events.event_at');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sale-receipt-audit-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(9)
            ->parameters([
                'dom' => "<'row'l>Bfrtip",
                'buttons' => [
                    'colvis',
                    ['extend' => 'excel', 'footer' => true],
                    ['extend' => 'csv', 'footer' => true],
                    'reset',
                    'reload',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('sale.sale_no')->title('Invoice No'),
            Column::computed('total_print_count')->title('Print Count')->searchable(false)->orderable(false),
            Column::make('event_type')->title('Print Event'),
            Column::make('printed_by_name')->title('Printed By')->searchable(false)->orderable(false),
            Column::make('reprinted_by_name')->title('Reprinted By')->searchable(false)->orderable(false),
            Column::make('reprint_note')->title('Reprint Note')->searchable(false)->orderable(false),
            Column::make('delivery_status')->title('Delivery Status')->searchable(false)->orderable(false),
            Column::make('verified_by_name')->title('Verified By')->searchable(false)->orderable(false),
            Column::make('verified_at_display')->title('Verified At')->searchable(false)->orderable(false),
            Column::make('event_at')->title('Print Time'),
        ];
    }

    protected function filename(): string
    {
        return 'SaleReceiptAudit_' . date('YmdHis');
    }
}