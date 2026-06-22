<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
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
        
        $total_purchase = Purchase::get()->count(); 
        $total_invoice =Sale::get()->count();
        $pending_purchase = Purchase::get()->count();
        $approved_purchase =Purchase::get()->count();
        $total_sales = Payment::sum('total_amount');
        $total_purchases = PurchasePayment::sum('total_amount');
        $total_stock_balance = (float) Product::sum('quantity');
        $total_stock_value = (float) Product::selectRaw('COALESCE(SUM(quantity * cost_price), 0) as total')
            ->value('total');
       
        $products = DB::select('SELECT * FROM products WHERE quantity < stock_level');
        
        // Monthly sales distribution using Sale + sale_details
        $monthlySales = DB::table('sales')
            ->join('payments', 'sales.id', '=', 'payments.sale_id')
            ->select(
                DB::raw('MONTH(sales.date) as month'),
                DB::raw('SUM(payments.total_amount) as total')
            )
            ->groupBy(DB::raw('MONTH(sales.date)'))
            ->orderBy(DB::raw('MONTH(sales.date)'))
            ->pluck('total', 'month')
            ->toArray();

        // Prepare labels and data for chart
        $months = [];
        $totals = [];
        foreach ($monthlySales as $month => $total) {
            $months[] = date("F", mktime(0, 0, 0, $month, 1)); // Convert month number to name
            $totals[] = $total;
        }

        $company = Company::first();

        return view('admin.index', compact(
            'total_purchase',
            'total_invoice',
            'total_sales',
            'total_purchases',
            'total_stock_balance',
            'total_stock_value',
            'pending_purchase',
            'products',
            'approved_purchase',
            'months',
            'totals',
            'company'
        ));
    }

    
}

