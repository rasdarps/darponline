<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use Illuminate\Support\Carbon;
use App\Models\PurchasePayment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchasePaymentDetail;
use Illuminate\Support\Facades\Crypt;
  
class PurchaseController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['index','store']]);
         $this->middleware('permission:purchase-create', ['only' => ['create','store']]);
         $this->middleware('permission:purchase-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
    }

    // RESTful Methods
    public function index(){
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
        return view('backend.purchase.index',compact('allData'));
    } // End Method

    public function create(){ 
        $unit = Unit::all();
        $category = Category::all();
        $suppliers = Supplier::all();
        $purchase_data = Purchase::orderBy('id','desc')->first();
        
        if ($purchase_data == null) {
           $firstReg = '0';
           $purchase_no = '0' . $firstReg+1;
        }else{
            $purchase_data = Purchase::orderBy('id','desc')->first()->purchase_no;
            $purchase_no = '0' . $purchase_data+1;
        }
        $date = date('Y-m-d');
        return view('backend.purchase.create',compact('purchase_no','category','date','suppliers','unit'));
    } // End Method

    public function store(Request $request){
        if ($request->category_id == null) {
            $notification = array(
                'message' => 'Sorry You do not select any item', 
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        else{
            if ($request->paid_amount > $request->estimated_amount) {
               $notification = array(
                'message' => 'Sorry Paid Amount is Maximum the total price', 
                'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } 
        else {

        // Use Model's fillable attributes
        DB::transaction(function() use($request){
            $purchase = Purchase::create([
                'purchase_no' => $request->purchase_no,
                'date' => $request->date,
                'description' => $request->description,
                'status' => 1,
                'created_by' => Auth::id(),
            ]);
            
           $count_category = count($request->category_id);
   
           for ($i=0; $i < $count_category ; $i++) { 

              $purchase_details = new PurchaseDetail();
              $purchase_details->date = date('Y-m-d',strtotime($request->date));
              $purchase_details->purchase_id = $purchase->id;
              $purchase_details->category_id = $request->category_id[$i];
              $purchase_details->product_id = $request->product_id[$i];
              $purchase_details->buying_qty = $request->buying_qty[$i];
              $purchase_details->unit_price = $request->unit_price[$i];
              $purchase_details->buying_price = $request->buying_price[$i];
              $purchase_details->unit_id = $request->unit_id[$i];
              $purchase_details->status = '1'; 
              $purchase_details->save(); 

              //stock addition control
              $product = Product::where('id',$request->product_id[$i])->first();
              $purchase_qty = ((float)($purchase_details->buying_qty))+((float)($product->quantity));
              $product->quantity = $purchase_qty;
              $product->save();
           }           

            // Handle supplier creation/selection
            if ($request->supplier_id == '0') {
                $supplier = Supplier::create([
                    'name' => $request->name,
                    'mobile_no' => $request->mobile_no,
                    'email' => $request->email,
                    'created_by' => Auth::id(),
                ]);
                $supplier_id = $supplier->id;
            } else{
                $supplier_id = $request->supplier_id;
            } 

            $purchase_payment = new PurchasePayment();
            $purchase_payment_details = new PurchasePaymentDetail();

            $purchase_payment->purchase_id = $purchase->id;
            $purchase_payment->supplier_id = $supplier_id;
            $purchase_payment->paid_status = $request->paid_status;
            $purchase_payment->discount_amount = $request->discount_amount;
            $purchase_payment->total_amount = $request->estimated_amount;

            if ($request->paid_status == 'full_paid') {
                $purchase_payment->paid_amount = $request->estimated_amount;
                $purchase_payment->due_amount = '0';
                $purchase_payment_details->current_paid_amount = $request->estimated_amount;
            } elseif ($request->paid_status == 'full_due') {
                $purchase_payment->paid_amount = '0';
                $purchase_payment->due_amount = $request->estimated_amount;
                $purchase_payment_details->current_paid_amount = '0';
            }elseif ($request->paid_status == 'partial_paid') {
                $purchase_payment->paid_amount = $request->paid_amount;
                $purchase_payment->due_amount = $request->estimated_amount - $request->paid_amount;
                $purchase_payment_details->current_paid_amount = $request->paid_amount;
            }
            $purchase_payment->save();

            $purchase_payment_details->purchase_id = $purchase->id; 
            $purchase_payment_details->date = date('Y-m-d',strtotime($request->date));
            $purchase_payment_details->save(); 
        }); 
        } // end else 
    }

     $notification = array(
        'message' => 'Purchase Data Inserted Successfully', 
        'alert-type' => 'success'
    );
    return redirect()->route('purchases.index')->with($notification);  
    } // End Method

    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $purchase = Purchase::findOrFail($id);
            
            DB::transaction(function() use($purchase){
                PurchaseDetail::where('purchase_id',$purchase->id)->delete(); 
                PurchasePayment::where('purchase_id',$purchase->id)->delete(); 
                PurchasePaymentDetail::where('purchase_id',$purchase->id)->delete(); 
                $purchase->delete();
            });

             $notification = array(
            'message' => 'Purchase Deleted Successfully', 
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 

        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Invalid purchase ID', 
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }// End Method


    public function PrintPurchaseList(){

    $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
       return view('backend.purchase.print_purchase_list',compact('allData'));
    } // End Method


    public function PrintPurchase($id){
        $purchase = Purchase::with('purchase_details')->findOrFail($id);
        return view('backend.pdf.purchase_pdf',compact('purchase'));

    } // End Method


    public function DailyPurchaseReport(){
        return view('backend.purchase.daily_purchase_report');
    } // End Method


    public function DailyPurchasePdf(Request $request){

        $sdate = date('Y-m-d',strtotime($request->start_date));
        $edate = date('Y-m-d',strtotime($request->end_date));
        $allData = Purchase::whereBetween('date',[$sdate,$edate])->where('status','1')->get();


        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        return view('backend.pdf.daily_purchase_report_pdf',compact('allData','start_date','end_date'));
    } // End Method
   
}
