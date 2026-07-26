<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ExpenseVoucherItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:dashboard-view');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboardData = $this->buildDashboardData();

        return view('admin.index', $dashboardData);
    }

    public function insight()
    {
        $dashboardData = $this->buildDashboardData();

        return view('admin.insight', $dashboardData);
    }

    private function buildDashboardData(): array
    {
        $total_purchase = Purchase::get()->count(); 
        $total_invoice = Sale::get()->count();
        $pending_purchase = Purchase::get()->count();
        $approved_purchase =Purchase::get()->count();
        $total_sales = Payment::sum('total_amount');
        $total_purchases = PurchasePayment::sum('total_amount');
        $total_stock_balance = (float) Product::sum('quantity');
        $total_stock_value = (float) Product::selectRaw('COALESCE(SUM(quantity * cost_price), 0) as total')
            ->value('total');
       
        $products = DB::select('SELECT * FROM products WHERE quantity < stock_level');
        
        // Sales trend for last 30 days.
        $trendStart = Carbon::today()->subDays(29);
        $dailySales = DB::table('sales')
            ->join('payments', 'sales.id', '=', 'payments.sale_id')
            ->select(
                DB::raw('DATE(sales.date) as sale_date'),
                DB::raw('SUM(payments.total_amount) as total')
            )
            ->whereDate('sales.date', '>=', $trendStart->toDateString())
            ->groupBy(DB::raw('DATE(sales.date)'))
            ->orderBy(DB::raw('DATE(sales.date)'))
            ->pluck('total', 'sale_date')
            ->toArray();

        $salesTrendLabels = [];
        $salesTrendTotals = [];
        $salesTrendMovingAverage = [];

        for ($offset = 0; $offset < 30; $offset++) {
            $day = $trendStart->copy()->addDays($offset);
            $dayKey = $day->toDateString();
            $salesTrendLabels[] = $day->format('d M');
            $salesTrendTotals[] = round((float) ($dailySales[$dayKey] ?? 0), 2);
        }

        foreach ($salesTrendTotals as $index => $value) {
            $windowStart = max(0, $index - 6);
            $windowSlice = array_slice($salesTrendTotals, $windowStart, $index - $windowStart + 1);
            $windowAvg = count($windowSlice) > 0 ? array_sum($windowSlice) / count($windowSlice) : 0;
            $salesTrendMovingAverage[] = round($windowAvg, 2);
        }

        // Top category contribution by sales amount.
        $topCategoryRows = DB::table('sale_details')
            ->join('categories', 'sale_details.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(sale_details.selling_price) as total_sales')
            )
            ->groupBy('sale_details.category_id', 'categories.name')
            ->orderByDesc('total_sales')
            ->limit(6)
            ->get();

        $topCategoryLabels = $topCategoryRows->pluck('category_name')->values()->all();
        $topCategoryTotals = $topCategoryRows
            ->pluck('total_sales')
            ->map(fn ($value) => round((float) $value, 2))
            ->values()
            ->all();

        // Stock health risk: products below stock level ordered by shortfall.
        $stockRiskRows = Product::query()
            ->select('name', 'quantity', 'stock_level')
            ->whereColumn('quantity', '<', 'stock_level')
            ->orderByRaw('(stock_level - quantity) DESC')
            ->limit(8)
            ->get();

        $stockRiskLabels = $stockRiskRows->pluck('name')->values()->all();
        $stockRiskShortfall = $stockRiskRows
            ->map(function ($row) {
                return round(max(0, (float) $row->stock_level - (float) $row->quantity), 2);
            })
            ->values()
            ->all();

        // Expense insight: spent amount grouped by expense category.
        $expenseInsightRows = ExpenseVoucherItem::query()
            ->join('expense_categories', 'expense_voucher_items.expense_category_id', '=', 'expense_categories.id')
            ->select(
                'expense_categories.name as category_name',
                DB::raw('SUM(expense_voucher_items.amount) as total_spent')
            )
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->havingRaw('SUM(expense_voucher_items.amount) > 0')
            ->orderByDesc('total_spent')
            ->limit(8)
            ->get();

        $expenseInsightLabels = $expenseInsightRows->pluck('category_name')->values()->all();
        $expenseInsightTotals = $expenseInsightRows
            ->pluck('total_spent')
            ->map(fn ($value) => round((float) $value, 2))
            ->values()
            ->all();

        // Monthly sales for current year only (Jan-Dec).
        $currentYear = Carbon::today()->year;
        $monthlySalesRaw = DB::table('sales')
            ->join('payments', 'sales.id', '=', 'payments.sale_id')
            ->select(
                DB::raw('MONTH(sales.date) as sale_month'),
                DB::raw('SUM(payments.total_amount) as total')
            )
            ->whereYear('sales.date', $currentYear)
            ->groupBy(DB::raw('MONTH(sales.date)'))
            ->orderBy(DB::raw('MONTH(sales.date)'))
            ->pluck('total', 'sale_month')
            ->toArray();

        $monthlySalesLabels = [];
        $monthlySalesTotals = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlySalesLabels[] = Carbon::createFromDate($currentYear, $month, 1)->format('M');
            $monthlySalesTotals[] = round((float) ($monthlySalesRaw[$month] ?? 0), 2);
        }

        $company = Company::first();
        return compact(
            'total_purchase',
            'total_invoice',
            'total_sales',
            'total_purchases',
            'total_stock_balance',
            'total_stock_value',
            'pending_purchase',
            'products',
            'approved_purchase',
            'salesTrendLabels',
            'salesTrendTotals',
            'salesTrendMovingAverage',
            'topCategoryLabels',
            'topCategoryTotals',
            'stockRiskLabels',
            'stockRiskShortfall',
            'expenseInsightLabels',
            'expenseInsightTotals',
            'currentYear',
            'monthlySalesLabels',
            'monthlySalesTotals',
            'company'
        );
    }

    
}
