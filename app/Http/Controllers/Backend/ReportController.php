<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\ExpenseReportDataTable;
use App\DataTables\Admin\Report\ProfitReportDataTable;
use App\DataTables\Admin\Report\PurchaseEditTrailReportDataTable;
use App\DataTables\Admin\Report\StockAdjustmentReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CashReconciliation;
use App\Models\ExpenseCategory;
use App\Models\ExpenseVoucherItem;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PurchaseEditAudit;
use App\Models\PurchasePayment;
use App\Models\StockLedger;
use App\Models\StockAdjustmentItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report-profit', ['only' => ['profitReport', 'profitSummary']]);
        $this->middleware('permission:report-expense', ['only' => ['expenseReport']]);
        $this->middleware('permission:report-stock-adjustment', ['only' => ['stockAdjustmentReport']]);
        $this->middleware('permission:report-summary-ledger', ['only' => ['summaryLedger']]);
    }

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
        // $allData = Sale::orderBy('date','desc')->orderBy('id','desc')->get();
        $sales = Sale::orderBy('sale_no')->get();
        $users = User::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        return $dataTable->render('backend.report.profit', compact('sales', 'users', 'customers'));

    }

    public function profitSummary(Request $request)
    {
        $salesSub = DB::table('payments')
            ->selectRaw('sale_id, SUM(total_amount) as sales_total')
            ->groupBy('sale_id');

        $costSub = DB::table('sale_details as idt')
            ->selectRaw(
                'idt.sale_id, SUM(idt.selling_qty * COALESCE((
                    SELECT pd.unit_cost_price
                    FROM purchase_details pd
                    WHERE pd.product_id = idt.product_id
                    ORDER BY pd.date DESC, pd.id DESC
                    LIMIT 1
                ), 0)) as cost_total'
            )
            ->groupBy('idt.sale_id');

        $invoiceQuery = Sale::query()
            ->leftJoinSub($salesSub, 'sales_agg', function ($join) {
                $join->on('sales_agg.sale_id', '=', 'sales.id');
            })
            ->leftJoinSub($costSub, 'costs_agg', function ($join) {
                $join->on('costs_agg.sale_id', '=', 'sales.id');
            });

        if ($invoiceId = $request->get('sale_id')) {
            $invoiceQuery->where('sales.id', $invoiceId);
        }

        if ($customerId = $request->get('customer_id')) {
            $invoiceQuery->whereHas('payment.customer', function ($q) use ($customerId) {
                $q->where('id', $customerId);
            });
        }

        if ($createdBy = $request->get('created_by')) {
            $invoiceQuery->where('sales.created_by', $createdBy);
        }

        if ($fromDate = $request->get('from_date')) {
            $invoiceQuery->whereDate('sales.date', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $invoiceQuery->whereDate('sales.date', '<=', $toDate);
        }

        $invoiceSummary = $invoiceQuery->selectRaw('
                COALESCE(SUM(sales_agg.sales_total), 0) as gross_sales,
                COALESCE(SUM(costs_agg.cost_total), 0) as gross_cost,
                COALESCE(SUM(sales_agg.sales_total), 0) - COALESCE(SUM(costs_agg.cost_total), 0) as gross_profit
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

        $cashAdjustments = $this->approvedCashAdjustmentTotals($request);
        $cashShortageLoss = $cashAdjustments['shortage_loss'];
        $cashOverageGain = $cashAdjustments['overage_gain'];
        $cashNetAdjustment = $cashOverageGain - $cashShortageLoss;

        $stockAdjustments = $this->stockAdjustmentTotals($request);
        $stockShortageLoss = $stockAdjustments['shortage_loss'];
        $stockOverageGain = $stockAdjustments['overage_gain'];
        $stockNetAdjustment = $stockOverageGain - $stockShortageLoss;

        return response()->json([
            'gross_sales' => (float) ($invoiceSummary->gross_sales ?? 0),
            'gross_cost' => (float) ($invoiceSummary->gross_cost ?? 0),
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'cash_shortage_loss' => $cashShortageLoss,
            'cash_overage_gain' => $cashOverageGain,
            'cash_net_adjustment' => $cashNetAdjustment,
            'stock_shortage_loss' => $stockShortageLoss,
            'stock_overage_gain' => $stockOverageGain,
            'stock_net_adjustment' => $stockNetAdjustment,
            'net_profit' => $grossProfit - $totalExpenses - $cashShortageLoss + $cashOverageGain - $stockShortageLoss + $stockOverageGain,
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

        return $dataTable->render('backend.report.expense', compact('users', 'categories', 'monthTotal', 'overallTotal'));
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

        return $dataTable->render('backend.report.stock_adjustment', compact('users', 'products', 'monthTotal', 'overallTotal'));
    }

    public function purchaseAuditReport(PurchaseEditTrailReportDataTable $dataTable)
    {
        $purchases = \App\Models\Purchase::orderBy('purchase_no')->get(['id', 'purchase_no']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return $dataTable->render('backend.report.purchase_edit_trail', compact('purchases', 'users'));
    }

    public function summaryLedger()
    {
        $totalPurchases = (float) PurchasePayment::sum('total_amount');
        $totalSales = (float) Payment::sum('total_amount');
        $currentStockValue = (float) Product::selectRaw('COALESCE(SUM(quantity * cost_price), 0) as total')->value('total');
        $totalExpenses = (float) ExpenseVoucherItem::sum('amount');

        $salesSub = DB::table('payments')
            ->selectRaw('sale_id, SUM(total_amount) as sales_total')
            ->groupBy('sale_id');

        $costSub = DB::table('sale_details as idt')
            ->selectRaw(
                'idt.sale_id, SUM(idt.selling_qty * COALESCE((
                    SELECT pd.unit_cost_price
                    FROM purchase_details pd
                    WHERE pd.product_id = idt.product_id
                    ORDER BY pd.date DESC, pd.id DESC
                    LIMIT 1
                ), 0)) as cost_total'
            )
            ->groupBy('idt.sale_id');

        $grossSummary = Sale::query()
            ->leftJoinSub($salesSub, 'sales', function ($join) {
                $join->on('sales.sale_id', '=', 'sales.id');
            })
            ->leftJoinSub($costSub, 'costs', function ($join) {
                $join->on('costs.sale_id', '=', 'sales.id');
            })
            ->selectRaw('COALESCE(SUM(sales.sales_total), 0) - COALESCE(SUM(costs.cost_total), 0) as gross_profit')
            ->first();

        $grossProfit = (float) ($grossSummary->gross_profit ?? 0);
        $cashAdjustments = $this->approvedCashAdjustmentTotals();
        $cashShortageLoss = $cashAdjustments['shortage_loss'];
        $cashOverageGain = $cashAdjustments['overage_gain'];
        $cashNetAdjustment = $cashOverageGain - $cashShortageLoss;
        $stockAdjustments = $this->stockAdjustmentTotals();
        $stockShortageLoss = $stockAdjustments['shortage_loss'];
        $stockOverageGain = $stockAdjustments['overage_gain'];
        $stockNetAdjustment = $stockOverageGain - $stockShortageLoss;
        $netProfit = $grossProfit - $totalExpenses - $cashShortageLoss + $cashOverageGain - $stockShortageLoss + $stockOverageGain;

        return view('backend.report.summary_ledger', compact(
            'totalPurchases',
            'currentStockValue',
            'totalExpenses',
            'totalSales',
            'grossProfit',
            'cashShortageLoss',
            'cashOverageGain',
            'cashNetAdjustment',
            'stockShortageLoss',
            'stockOverageGain',
            'stockNetAdjustment',
            'netProfit'
        ));
    }

    private function approvedCashAdjustmentTotals(Request $request = null): array
    {
        $query = CashReconciliation::query()->where('status', 'approved');

        if ($request) {
            if ($fromDate = $request->get('from_date')) {
                $query->whereDate('business_date', '>=', $fromDate);
            }

            if ($toDate = $request->get('to_date')) {
                $query->whereDate('business_date', '<=', $toDate);
            }

            if ($createdBy = $request->get('created_by')) {
                $query->where('user_id', $createdBy);
            }
        }

        $shortageLoss = (float) (clone $query)
            ->where('variance', '<', 0)
            ->sum(DB::raw('ABS(variance)'));

        $overageGain = (float) (clone $query)
            ->where('variance', '>', 0)
            ->sum('variance');

        return [
            'shortage_loss' => round($shortageLoss, 2),
            'overage_gain' => round($overageGain, 2),
        ];
    }

    private function stockAdjustmentTotals(Request $request = null): array
    {
        $query = StockLedger::query()
            ->where('reference_type', 'stock_adjustment')
            ->whereIn('movement_type', ['adjustment_increase', 'adjustment_decrease']);

        if ($request) {
            if ($fromDate = $request->get('from_date')) {
                $query->whereDate('movement_at', '>=', $fromDate);
            }

            if ($toDate = $request->get('to_date')) {
                $query->whereDate('movement_at', '<=', $toDate);
            }

            if ($createdBy = $request->get('created_by')) {
                $query->where('created_by', $createdBy);
            }
        }

        $stockOverageGain = (float) (clone $query)
            ->where('movement_type', 'adjustment_increase')
            ->selectRaw('COALESCE(SUM(qty_in * COALESCE(unit_cost, 0)), 0) as total')
            ->value('total');

        $stockShortageLoss = (float) (clone $query)
            ->where('movement_type', 'adjustment_decrease')
            ->selectRaw('COALESCE(SUM(qty_out * COALESCE(unit_cost, 0)), 0) as total')
            ->value('total');

        return [
            'shortage_loss' => round($stockShortageLoss, 2),
            'overage_gain' => round($stockOverageGain, 2),
        ];
    }
}

