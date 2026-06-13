<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\Sales\AllSalesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:invoice-list|invoice-create|invoice-edit|invoice-delete', ['only' => ['index','store']]);
         $this->middleware('permission:invoice-create', ['only' => ['create','store']]);
         $this->middleware('permission:invoice-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:invoice-delete', ['only' => ['destroy']]);
    }

    // public function index(){
    //     $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
    //         return view('backend.invoice.index',compact('allData'));

    // } // End Method
     public function index(AllSalesDataTable $dataTable){
        // $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
        $customers = Customer::orderBy('name')->get();
        $invoices = Invoice::orderBy('invoice_no')->get();
        return $dataTable->render('backend.invoice.index', compact('customers', 'invoices'));

    }

    public function create(){ 

        $unit = Unit::all();
        $category = Category::all();
        $costomer = Customer::all();

        // Get the maximum numeric invoice_no
        $maxInvoiceNo = Invoice::max(DB::raw('CAST(invoice_no AS UNSIGNED)'));
        if ($maxInvoiceNo) {
            $invoice_no = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $invoice_no = '01';
        }

        $date = Carbon::now()->format('Y-m-d');        
        return view('backend.invoice.create',compact('invoice_no','category','date','costomer','unit'));

    } // End Method



    public function store(Request $request) {
    // Validation rules
    $rules = [
        'date' => ['required', 'date'],
        'invoice_no' => ['required'],
        'category_id' => ['required', 'array', 'min:1'],
        'category_id.*' => ['required'],
        'product_id' => ['required', 'array', 'min:1'],
        'product_id.*' => ['required'],
        'unit_id' => ['required', 'array', 'min:1'],
        'unit_id.*' => ['required'],
        'paid_status' => ['required', 'in:full_paid,full_due,partial_paid'],
    ];

    if ($request->paid_status === 'partial_paid') {
        $rules['paid_amount'] = ['required', 'numeric', 'min:1', 'max:' . $request->estimated_amount];
    }

    if ($request->customer_id == '0') {
        $rules['name'] = ['required', 'string', 'max:255'];
        $rules['mobile_no'] = ['required', 'string', 'max:20'];
        $rules['email'] = ['required', 'email', 'max:255'];
    }

    $validated = $request->validate($rules);

    // Capture the invoice returned from the transaction
    $invoice = DB::transaction(function() use($request) {
        $invoice = Invoice::create([
            'invoice_no' => $request->invoice_no,
            'date' => $request->date,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        $count_category = count($request->category_id);
        for ($i = 0; $i < $count_category; $i++) {
            $invoice_details = new InvoiceDetail();
            $invoice_details->date = date('Y-m-d', strtotime($request->date));
            $invoice_details->invoice_id = $invoice->id;
            $invoice_details->category_id = $request->category_id[$i];
            $invoice_details->product_id = $request->product_id[$i];
            $invoice_details->selling_qty = $request->selling_qty[$i];
            $invoice_details->unit_price = $request->unit_price[$i];
            $invoice_details->selling_price = $request->selling_price[$i];
            $invoice_details->unit_id = $request->unit_id[$i];
            $invoice_details->save();

            // stock deduction
            $product = Product::find($request->product_id[$i]);
            $product->quantity = ((float)$product->quantity) - ((float)$invoice_details->selling_qty);
            $product->save();
        }

        // Customer handling
        if ($request->customer_id == '0') {
            $customer = Customer::create([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
            ]);
            $customer_id = $customer->id;
        } else {
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
            $payment->due_amount = 0;
            $payment_details->current_paid_amount = $request->estimated_amount;
        } elseif ($request->paid_status == 'full_due') {
            $payment->paid_amount = 0;
            $payment->due_amount = $request->estimated_amount;
            $payment_details->current_paid_amount = 0;
        } elseif ($request->paid_status == 'partial_paid') {
            $payment->paid_amount = $request->paid_amount;
            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
            $payment_details->current_paid_amount = $request->paid_amount;
        }
        $payment->save();

        $payment_details->invoice_id = $invoice->id;
        $payment_details->date = date('Y-m-d', strtotime($request->date));
        $payment_details->save();

        // return invoice object so it's available outside
        return $invoice;
    });

    // Now $invoice is available here
    if ($request->ajax()) {
        return response()->json([
            'status' => 200,
            'message' => 'Invoice Data Inserted Successfully',
            'print_url' => route('print.invoice', Crypt::encrypt($invoice->id)),
        ]);
    } else {
        return redirect()->route('print.invoice', Crypt::encrypt($invoice->id));
    }
}



       /**
     * Return the next available invoice number as JSON (for AJAX).
     */
    public function nextNumber()
    {
        $maxInvoiceNo = Invoice::max(DB::raw('CAST(invoice_no AS UNSIGNED)'));
        if ($maxInvoiceNo) {
            $invoice_no = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $invoice_no = '01';
        }
        return response()->json(['invoice_no' => $invoice_no]);
    }


    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $invoice = Invoice::findOrFail($id);
            DB::transaction(function() use($invoice){
                InvoiceDetail::where('invoice_id',$invoice->id)->delete(); 
                Payment::where('invoice_id',$invoice->id)->delete(); 
                PaymentDetail::where('invoice_id',$invoice->id)->delete(); 
                $invoice->delete();
            });
            $notification = array(
                'message' => 'Invoice Deleted Successfully', 
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification); 
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Invalid invoice ID', 
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }// End Method


    public function PrintInvoiceList(){

    $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
       return view('backend.invoice.print_invoice_list',compact('allData'));
    } // End Method


    //detail invoice print
    public function PrintInvoice($encryptedId){

        $id = Crypt::decrypt($encryptedId);
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        $companyInfo = Company::first();
        return view('backend.invoice.invoice_receipt',compact('invoice','companyInfo'));

    } // End Method


    public function DailyInvoiceReport(){
        $companyInfo = Company::first();
        return view('backend.invoice.daily_invoice_report',compact('companyInfo'));
    } // End Method


    public function DailyInvoicePdf(Request $request){

        $sdate = date('Y-m-d',strtotime($request->start_date));
        $edate = date('Y-m-d',strtotime($request->end_date));
        $allData = Invoice::whereBetween('date',[$sdate,$edate])->get();


        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        $companyInfo = Company::first();
        return view('backend.pdf.daily_invoice_report_pdf',compact('allData','start_date','end_date','companyInfo'));
    } // End Method


}
 