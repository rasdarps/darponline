<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $total_purchase = Purchase::get()->count(); 
        $total_invoice =Invoice::get()->count();
        $pending_purchase = Purchase::get()->count();
        $approved_purchase =Purchase::get()->count();
        $total_sales = Payment::sum('total_amount');
        $total_purchases = PurchasePayment::sum('total_amount');
       
        $products = DB::select('SELECT * FROM products WHERE quantity < stock_level');
        
        // Monthly sales distribution using invoice + invoice_details
        $monthlySales = DB::table('invoices')
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->select(
                DB::raw('MONTH(invoices.date) as month'),
                DB::raw('SUM(payments.total_amount) as total')
            )
            ->groupBy(DB::raw('MONTH(invoices.date)'))
            ->orderBy(DB::raw('MONTH(invoices.date)'))
            ->pluck('total', 'month')
            ->toArray();

        // Prepare labels and data for chart
        $months = [];
        $totals = [];
        foreach ($monthlySales as $month => $total) {
            $months[] = date("F", mktime(0, 0, 0, $month, 1)); // Convert month number to name
            $totals[] = $total;
        }

        return view('admin.index', compact(
            'total_purchase',
            'total_invoice',
            'total_sales',
            'total_purchases',
            'pending_purchase',
            'products',
            'approved_purchase',
            'months',
            'totals'
        ));
    }

    
}
