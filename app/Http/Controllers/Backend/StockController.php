<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\Stock\ProductWiseStockReportDataTable;
use App\DataTables\Admin\Report\Stock\StockReportDataTable;
use App\DataTables\Admin\Report\Stock\StocksDataTable;
use App\DataTables\Admin\Report\Stock\StockValueReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
 
class StockController extends Controller
{
    // public function StockReport(){

    //     $allData = Product::orderBy('name','asc')->orderBy('category_id','asc')->get();
    //     return view('backend.stock.stock_report',compact('allData'));

    // } // End Method

    public function StockReport(StocksDataTable $dataTable){

       $units = Unit::orderBy('name')->get();
       $brands = Brand::orderBy('name')->get();
       $categories = Category::orderBy('name')->get(); 
       return $dataTable->render('backend.stock.stock_report', compact('units', 'brands', 'categories'));

    }

    public function StockLedgerReport(StockReportDataTable $dataTable)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return $dataTable->render('backend.stock.stock_ledger_report', compact('products', 'categories', 'users'));
    }

    public function ProductWiseStockTrail(ProductWiseStockReportDataTable $dataTable)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return $dataTable->render('backend.stock.product_wise_stock_trail', compact('products', 'users'));
    }


    public function StockValue(StockValueReportDataTable $dataTable){
        $products = Product::orderBy('name')->get();

        return $dataTable->render('backend.stock.stock_value', compact('products'));

    } // End Method



    public function StockReportPdf(){

        $allData = Product::orderBy('name','asc')->orderBy('category_id','asc')->get();
        return view('backend.pdf.stock_report_pdf',compact('allData'));

    } // End Method


    //search by Category & product wise report
    public function StockCategoryWise(){

       
        $category = Category::all();
        return view('backend.stock.category_product_wise_report',compact('category'));

    } // End Method


    public function CategoryWisePdf(Request $request){

        $allData = Product::orderBy('category_id','asc')->orderBy('name','asc')->where('category_id',$request->category_id)->get();
        return view('backend.pdf.category_wise_report_pdf',compact('allData'));

    } // End Method

     //search by product wise
    public function ProductWisePdf(Request $request){

        $product = Product::where('category_id',$request->category_id)->where('id',$request->product_id)->first();
        return view('backend.pdf.product_wise_report_pdf',compact('product'));
    } // End Method



    

}
 