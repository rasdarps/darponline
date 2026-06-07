<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

use App\Models\InvoiceDetail;
use App\Models\PaymentDetail;
use Illuminate\Support\Carbon;
use App\Rules\ProductStockRule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:invoice-list|invoice-create|invoice-edit|invoice-delete', ['only' => ['index','store']]);
         $this->middleware('permission:invoice-create', ['only' => ['create','store']]);
         $this->middleware('permission:invoice-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:invoice-delete', ['only' => ['destroy']]);
    }

    public function index(){
        $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
            return view('backend.invoice.index',compact('allData'));

    } // End Method

    // public function ApprovedList(){
    //     $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
    //         return view('backend.invoice.invoice_approved_list',compact('allData'));

    // } // End Method


    public function create(){ 

        $unit = Unit::all();
        $category = Category::all();
        $costomer = Customer::all();
        $invoice_data = Invoice::orderBy('id','desc')->first();
        
        if ($invoice_data == null) {
           $firstReg = '0';
           $invoice_no = '0' . $firstReg+1;
        }else{
            $invoice_data = Invoice::orderBy('id','desc')->first()->invoice_no;
            $invoice_no = '0' . $invoice_data+1;
        }
        $date = date('Y-m-d');
        return view('backend.invoice.create',compact('invoice_no','category','date','costomer','unit'));

    } // End Method


    public function store(Request $request){
            try{ 
            if ($request->category_id == null) {

                $notification = array(
                    'message' => 'Sorry You did not select a product category', 
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);

                }//end if statement 1

            else{
                    if ($request->paid_amount > $request->estimated_amount) {

                    $notification = array(
                    'message' => 'Sorry Paid Amount is more than the total price', 
                    'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);

                    }//end if statement 2 
                    
                
            else{
            
                $invoice = new Invoice();
                $invoice->invoice_no = $request->invoice_no;
                $invoice->date = date('Y-m-d',strtotime($request->date));
                $invoice->description = $request->description;
                $invoice->status = '1';
                $invoice->created_by = Auth::user()->id; 
                
                
                DB::transaction(function() use($request,$invoice){
                    if ($invoice->save()) {
                    $count_category = count($request->category_id);
                    for ($i=0; $i < $count_category ; $i++) { 

                        $invoice_details = new InvoiceDetail();
                        $invoice_details->date = date('Y-m-d',strtotime($request->date));
                        $invoice_details->invoice_id = $invoice->id;
                        $invoice_details->category_id = $request->category_id[$i];
                        $invoice_details->product_id = $request->product_id[$i];
                        $invoice_details->selling_qty = $request->selling_qty[$i];
                        $invoice_details->unit_price = $request->unit_price[$i];
                        $invoice_details->selling_price = $request->selling_price[$i];
                        $invoice_details->unit_id = $request->unit_id[$i];
                        $invoice_details->status = '1'; 
                        $invoice_details->save();
                    
                        //stock deduction control
                        $product = Product::where('id',$request->product_id[$i])->first();
                        $selling_qty = ((float)($product->quantity))-((float)($invoice_details->selling_qty));
                        $product->quantity = $selling_qty;
                        //dd($product);
                        $product->save();

                        //$product->quantity = ((float)$product->quantity) - ((float)$request->selling_qty);
                        
                        }//end for loop

                        if ($request->customer_id == '0') {
                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->mobile_no = $request->mobile_no;
                            $customer->email = $request->email;
                            $customer->save();
                            $customer_id = $customer->id;
                        } else{
                            $customer_id = $request->customer_id;
                        } 

                        $payment = new Payment();
                        $payment_details = new PaymentDetail();

                        $payment->invoice_id = $invoice->id;
                        $payment->customer_id = $customer_id;
                        $payment->paid_status = $request->paid_status;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;

                        if ($request->paid_status == 'full_paid') {
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;
                        } elseif ($request->paid_status == 'full_due') {
                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';
                        }elseif ($request->paid_status == 'partial_paid') {
                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;
                        }
                        $payment->save();

                        $payment_details->invoice_id = $invoice->id; 
                        $payment_details->date = date('Y-m-d',strtotime($request->date));
                        $payment_details->save(); 

                
                    }//end if statement 3 

                }); //db transaction ends

        

                } // end 2nd else 
            } // end 1st else statement

        //end catch staement
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1644) {
                $errorMessage = $e->errorInfo[2];
                // Display error message to the user
                session()->flash('error_message', $errorMessage);
            } else 
            
            {

            //success/error handling    
            $notification = array(
                    'message' => 'Invoice Data Inserted Successfully', 
                    'alert-type' => 'success'
                );
            return redirect()->route('invoice.add')->with($notification); 

        }//end try else
        // Redirect back to previous page
        return redirect()->back();
      }//end try

    } // End Method



    public function InvoiceDelete($id){

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        InvoiceDetail::where('invoice_id',$invoice->id)->delete(); 
        Payment::where('invoice_id',$invoice->id)->delete(); 
        PaymentDetail::where('invoice_id',$invoice->id)->delete(); 

         $notification = array(
        'message' => 'Invoice Deleted Successfully', 
        'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 

    }// End Method



    // public function InvoiceApprove($id){

    //     $invoice = Invoice::with('invoice_details')->findOrFail($id);
    //     return view('backend.invoice.invoice_approve',compact('invoice'));

    // }// End Method


    // public function ApprovalStore(Request $request, $id){

    //     foreach($request->selling_qty as $key => $val){
    //         $invoice_details = InvoiceDetail::where('id',$key)->first();
    //         $product = Product::where('id',$invoice_details->product_id)->first();
    //         if($product->quantity < $request->selling_qty[$key]){

    //     $notification = array(
    //     'message' => 'Sorry stock quantity is less than invoice quantity', 
    //     'alert-type' => 'error'
    // );
    // return redirect()->back()->with($notification); 

    //         }
    //     } // End foreach 

    //     $invoice = Invoice::findOrFail($id);
    //     $invoice->updated_by = Auth::user()->id;
    //     $invoice->status = '1';

    //     DB::transaction(function() use($request,$invoice,$id){
    //         foreach($request->selling_qty as $key => $val){
    //          $invoice_details = InvoiceDetail::where('id',$key)->first();

    //          $invoice_details->status = '1';
    //          $invoice_details->save();

    //          $product = Product::where('id',$invoice_details->product_id)->first();
    //          $product->quantity = ((float)$product->quantity) - ((float)$request->selling_qty[$key]);
    //          $product->save();
    //         } // end foreach

    //         $invoice->save();
    //     });

    // $notification = array(
    //     'message' => 'Invoice Approve Successfully', 
    //     'alert-type' => 'success'
    // );
    // return redirect()->route('invoice.pending.list')->with($notification);  

    // } // End Method


    public function PrintInvoiceList(){

    $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
       return view('backend.invoice.print_invoice_list',compact('allData'));
    } // End Method


    public function PrintInvoice($id){
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        return view('backend.pdf.invoice_pdf',compact('invoice'));

    } // End Method


    public function DailyInvoiceReport(){
        return view('backend.invoice.daily_invoice_report');
    } // End Method


    public function DailyInvoicePdf(Request $request){

        $sdate = date('Y-m-d',strtotime($request->start_date));
        $edate = date('Y-m-d',strtotime($request->end_date));
        $allData = Invoice::whereBetween('date',[$sdate,$edate])->where('status','1')->get();


        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        return view('backend.pdf.daily_invoice_report_pdf',compact('allData','start_date','end_date'));
    } // End Method


}
 