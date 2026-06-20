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
use App\Models\Product;
use App\Models\StockAdjustmentItem;
use App\Models\User;
use Illuminate\Http\Request;

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
}
