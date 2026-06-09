<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
 
class StockController extends Controller
{
    public function StockReport(){

        $allData = Product::orderBy('name','asc')->orderBy('category_id','asc')->get();
        return view('backend.stock.stock_report',compact('allData'));

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
 