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
use App\Models\User;
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
            $this->middleware('permission:invoice-edit|invoice-delete', ['only' => ['edit','update', 'cancel']]);
    }

    // public function index(){
    //     $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
    //         return view('backend.invoice.index',compact('allData'));

    // } // End Method
     public function index(AllSalesDataTable $dataTable){
        // $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
          $customers = Customer::orderBy('name')->get();
          $invoices = Invoice::orderBy('invoice_no')->get();
          $users = User::orderBy('name')->get();
          return $dataTable->render('backend.invoice.index', compact('customers', 'invoices', 'users'));

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



    public function store(Request $request)
    {
        $grossTotal = collect($request->selling_price ?? [])->sum(function ($value) {
            return (float) $value;
        });

        $discountAmount = (float) ($request->discount_amount ?? 0);
        if ($discountAmount < 0) {
            $discountAmount = 0;
        }

        $netTotal = max(0, $grossTotal - $discountAmount);

        // Tax-inclusive receipt model:
        // total = taxable + NHIL(2.5%) + GETFund(2.5%) + VAT(15%) = taxable * 1.20
        $taxableAmount = $netTotal / 1.20;
        $nhilAmount = $taxableAmount * 0.025;
        $getfundAmount = $taxableAmount * 0.025;
        $vatAmount = $taxableAmount * 0.15;

        $calculated = [
            'discount_amount' => round($discountAmount, 2),
            'total_amount' => round($netTotal, 2),
            'taxable_amount' => round($taxableAmount, 2),
            'nhil_amount' => round($nhilAmount, 2),
            'getfund_amount' => round($getfundAmount, 2),
            'vat_amount' => round($vatAmount, 2),
        ];

        $cashTendered = (float) ($request->cash_tendered ?? 0);
        if ($cashTendered < 0) {
            $cashTendered = 0;
        }

        $calculated['cash_tendered'] = round($cashTendered, 2);
        $calculated['change_due'] = round(max(0, $cashTendered - $calculated['total_amount']), 2);

        // Validation rules
        $rules = [
            'date' => ['required', 'date'],
            'category_id' => ['required', 'array', 'min:1'],
            'category_id.*' => ['required'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required'],
            'unit_id' => ['required', 'array', 'min:1'],
            'unit_id.*' => ['required'],
            'paid_status' => ['required', 'in:full_paid,full_due,partial_paid'],
        ];

        if ($request->paid_status === 'partial_paid') {
            $rules['paid_amount'] = ['required', 'numeric', 'min:1', 'max:' . $calculated['total_amount']];
        }

        if ($request->paid_status === 'full_paid') {
            $rules['cash_tendered'] = ['required', 'numeric', 'min:' . $calculated['total_amount']];
        } else {
            $rules['cash_tendered'] = ['nullable', 'numeric', 'min:0'];
        }

        if ($request->customer_id == '0') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['mobile_no'] = ['required', 'string', 'max:20'];
            $rules['email'] = ['required', 'email', 'max:255'];
        }

        $validated = $request->validate($rules);

        // Capture the invoice returned from the transaction
        $invoice = DB::transaction(function() use($request, $calculated) {

            // Generate next invoice number
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNo = $lastInvoice ? str_pad(((int)$lastInvoice->invoice_no + 1), 2, '0', STR_PAD_LEFT) : '01';

            // Create invoice record with backend-generated number
            $invoice = Invoice::create([
                'invoice_no' => $nextNo,
                'date' => $request->date,
                'description' => $request->description,
                'sales_source' => 'pos',
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
                $invoice_details->unit_selling_price = $request->unit_selling_price[$i];
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
            $payment->discount_amount = $calculated['discount_amount'];
            $payment->total_amount = $calculated['total_amount'];
            $payment->taxable_amount = $calculated['taxable_amount'];
            $payment->nhil_amount = $calculated['nhil_amount'];
            $payment->getfund_amount = $calculated['getfund_amount'];
            $payment->vat_amount = $calculated['vat_amount'];
            $payment->cash_tendered = $calculated['cash_tendered'];
            $payment->change_due = $calculated['change_due'];

            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = $calculated['total_amount'];
                $payment->due_amount = 0;
                $payment_details->current_paid_amount = $calculated['total_amount'];
            } elseif ($request->paid_status == 'full_due') {
                $payment->paid_amount = 0;
                $payment->due_amount = $calculated['total_amount'];
                $payment_details->current_paid_amount = 0;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = $request->paid_amount;
                $payment->due_amount = $calculated['total_amount'] - $request->paid_amount;
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
            // Calculate next number after commit
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNo = $lastInvoice ? str_pad(((int)$lastInvoice->invoice_no + 1), 2, '0', STR_PAD_LEFT) : '01';

            return response()->json([
                'status' => 200,
                'message' => 'Invoice Data Inserted Successfully',
                'print_url' => route('print.invoice', Crypt::encrypt($invoice->id)),
                'next_no' => $nextNo, // send next invoice number to frontend
            ]);
        } else {
            return redirect()->route('print.invoice', Crypt::encrypt($invoice->id));
        }
    }


    public function cancel(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $invoice = Invoice::with('invoice_details')->findOrFail($id);

            if ($invoice->cancelled_at) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Invoice #' . $invoice->invoice_no . ' is already cancelled.',
                ], 422);
            }

            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ], [
                'reason.required' => 'Please provide a reason for cancellation.',
            ]);

            DB::transaction(function () use ($invoice, $validated) {
                // Restore stock for every line item
                foreach ($invoice->invoice_details as $detail) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $product->quantity = ((float) $product->quantity) + ((float) $detail->selling_qty);
                        $product->save();
                    }
                }

                // Zero-out line values so cancelled invoice does not affect quantity/value reporting
                InvoiceDetail::where('invoice_id', $invoice->id)->update([
                    'selling_qty' => 0,
                    'unit_selling_price' => 0,
                    'selling_price' => 0,
                ]);

                // Zero-out payment values so cancelled invoice does not affect financial totals
                Payment::where('invoice_id', $invoice->id)->update([
                    'paid_status' => 'full_due',
                    'discount_amount' => 0,
                    'total_amount' => 0,
                    'taxable_amount' => 0,
                    'nhil_amount' => 0,
                    'getfund_amount' => 0,
                    'vat_amount' => 0,
                    'cash_tendered' => 0,
                    'change_due' => 0,
                    'paid_amount' => 0,
                    'due_amount' => 0,
                ]);

                // Zero-out payment detail entries tied to this invoice
                PaymentDetail::where('invoice_id', $invoice->id)->update([
                    'current_paid_amount' => 0,
                ]);

                // Stamp cancellation on the invoice
                $invoice->update([
                    'cancelled_at'        => now(),
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $validated['reason'],
                ]);
            });

            return response()->json([
                'status'  => 200,
                'message' => 'Invoice #' . $invoice->invoice_no . ' has been cancelled and stock has been restored.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 422,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed to cancel invoice.',
            ], 400);
        }
    } // End Method

    public function PrintInvoiceList(){

       $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
       return view('backend.invoice.print_invoice_list',compact('allData'));
    } // End Method


    //detail invoice print
    public function PrintInvoice($encryptedId){

        $id = Crypt::decrypt($encryptedId);
        $invoice = Invoice::with(['invoice_details.category', 'invoice_details.product', 'createdBy'])->findOrFail($id);
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
 