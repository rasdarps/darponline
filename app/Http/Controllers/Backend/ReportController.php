<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\ExpenseReportDataTable;
use App\DataTables\Admin\Report\ProfitReportDataTable;
use App\DataTables\Admin\Report\StockAdjustmentReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ExpenseCategory;
use App\Models\ExpenseVoucherItem;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PurchasePayment;
use App\Models\StockAdjustmentItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profitReport(ProfitReportDataTable $dataTable){
        // $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
        $invoices = Invoice::orderBy('invoice_no')->get();
        $users = User::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        return $dataTable->render('backend.report.profit', compact('invoices', 'users', 'customers'));

    }

    public function profitSummary(Request $request)
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

        $invoiceQuery = Invoice::query()
            ->leftJoinSub($salesSub, 'sales', function ($join) {
                $join->on('sales.invoice_id', '=', 'invoices.id');
            })
            ->leftJoinSub($costSub, 'costs', function ($join) {
                $join->on('costs.invoice_id', '=', 'invoices.id');
            });

        if ($invoiceId = $request->get('invoice_id')) {
            $invoiceQuery->where('invoices.id', $invoiceId);
        }

        if ($customerId = $request->get('customer_id')) {
            $invoiceQuery->whereHas('payment.customer', function ($q) use ($customerId) {
                $q->where('id', $customerId);
            });
        }

        if ($createdBy = $request->get('created_by')) {
            $invoiceQuery->where('invoices.created_by', $createdBy);
        }

        if ($fromDate = $request->get('from_date')) {
            $invoiceQuery->whereDate('date', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $invoiceQuery->whereDate('date', '<=', $toDate);
        }

        $invoiceSummary = $invoiceQuery->selectRaw('
                COALESCE(SUM(sales.sales_total), 0) as gross_sales,
                COALESCE(SUM(costs.cost_total), 0) as gross_cost,
                COALESCE(SUM(sales.sales_total), 0) - COALESCE(SUM(costs.cost_total), 0) as gross_profit
            ')
            ->first();

        $expenseQuery = ExpenseVoucherItem::query()
            ->whereHas('voucher', function ($q) use ($request) {
                if ($fromDate = $request->get('from_date')) {
                    $q->whereDate('voucher_date', '>=', $fromDate);
                }

                if ($toDate = $request->get('to_date')) {
                    $q->whereDate('voucher_date', '<=', $toDate);
                }
            });

        $totalExpenses = (float) $expenseQuery->sum('amount');
        $grossProfit = (float) ($invoiceSummary->gross_profit ?? 0);

        return response()->json([
            'gross_sales' => (float) ($invoiceSummary->gross_sales ?? 0),
            'gross_cost' => (float) ($invoiceSummary->gross_cost ?? 0),
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'net_profit' => $grossProfit - $totalExpenses,
        ]);
    }

    public function expenseReport(ExpenseReportDataTable $dataTable)
    {
        $users = User::orderBy('name')->get();
        $categories = ExpenseCategory::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $monthTotal = ExpenseVoucherItem::query()
            ->whereHas('voucher', function ($q) {
                $q->whereMonth('voucher_date', now()->month)
                    ->whereYear('voucher_date', now()->year);
            })
            ->sum('amount');

        $overallTotal = ExpenseVoucherItem::query()->sum('amount');

        return $dataTable->render('backend.Report.expense', compact('users', 'categories', 'monthTotal', 'overallTotal'));
    }

    public function stockAdjustmentReport(StockAdjustmentReportDataTable $dataTable)
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name']);

        $monthTotal = StockAdjustmentItem::query()
            ->whereHas('adjustment', function ($q) {
                $q->whereMonth('adjustment_date', now()->month)
                    ->whereYear('adjustment_date', now()->year);
            })
            ->sum('amount');

        $overallTotal = StockAdjustmentItem::query()->sum('amount');

        return $dataTable->render('backend.Report.stock_adjustment', compact('users', 'products', 'monthTotal', 'overallTotal'));
    }

    public function summaryLedger()
    {
        $totalPurchases = (float) PurchasePayment::sum('total_amount');
        $totalSales = (float) Payment::sum('total_amount');
        $currentStockValue = (float) Product::selectRaw('COALESCE(SUM(quantity * cost_price), 0) as total')->value('total');
        $totalExpenses = (float) ExpenseVoucherItem::sum('amount');

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

        $grossSummary = Invoice::query()
            ->leftJoinSub($salesSub, 'sales', function ($join) {
                $join->on('sales.invoice_id', '=', 'invoices.id');
            })
            ->leftJoinSub($costSub, 'costs', function ($join) {
                $join->on('costs.invoice_id', '=', 'invoices.id');
            })
            ->selectRaw('COALESCE(SUM(sales.sales_total), 0) - COALESCE(SUM(costs.cost_total), 0) as gross_profit')
            ->first();

        $grossProfit = (float) ($grossSummary->gross_profit ?? 0);
        $netProfit = $grossProfit - $totalExpenses;

        return view('backend.Report.summary_ledger', compact(
            'totalPurchases',
            'currentStockValue',
            'totalExpenses',
            'totalSales',
            'grossProfit',
            'netProfit'
        ));
    }
}
