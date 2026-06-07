<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Purchase;
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
       
        $products = DB::select('SELECT * FROM products WHERE quantity < stock_level');
        
        return view('admin.index', compact('total_purchase','total_invoice','pending_purchase',
        'products','approved_purchase'));
    }

    
}
