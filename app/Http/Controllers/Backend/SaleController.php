<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\Sales\AllSalesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Services\StockLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SaleController extends Controller
{
    function __construct()
    {
    $this->middleware('permission:sale-list', ['only' => ['index']]);
    $this->middleware('permission:sale-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:sale-cancel', ['only' => ['cancel']]);
    $this->middleware('permission:sale-print', ['only' => ['printSaleList', 'printSale']]);
    }

     public function index(AllSalesDataTable $dataTable){
        // $allData = Sale::orderBy('date','desc')->orderBy('id','desc')->get();
          $customers = Customer::orderBy('name')->get();
          $sales = Sale::orderBy('sale_no')->get();
          $users = User::orderBy('name')->get();
          return $dataTable->render('backend.sale.index', compact('customers', 'sales', 'users'));

    }

    public function create(){ 

        $unit = Unit::all();
        $category = Category::all();
        $costomer = Customer::all();

        // Get the maximum numeric sale_no
        $maxInvoiceNo = Sale::max(DB::raw('CAST(sale_no AS UNSIGNED)'));
        if ($maxInvoiceNo) {
            $sale_no = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $sale_no = '01';
        }

        $date = Carbon::now()->format('Y-m-d');        
        return view('backend.sale.create',compact('sale_no','category','date','costomer','unit'));

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

        $request->validate($rules);

        $Sale = null;
        $attempts = 0;
        $maxAttempts = 3;

        while ($attempts < $maxAttempts) {
            $attempts++;
            try {
                $Sale = DB::transaction(function() use($request, $calculated) {

                    $maxInvoiceNo = (int) DB::table('sales')
                        ->lockForUpdate()
                        ->selectRaw('COALESCE(MAX(CAST(sale_no AS UNSIGNED)), 0) as max_no')
                        ->value('max_no');
                    $nextNo = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);

                    $Sale = Sale::create([
                        'sale_no' => $nextNo,
                        'date' => $request->date,
                        'description' => $request->description,
                        'created_by' => Auth::id(),
                    ]);

                    $count_category = count($request->category_id);
                    for ($i = 0; $i < $count_category; $i++) {
                        $sale_details = new SaleDetail();
                        $sale_details->date = date('Y-m-d', strtotime($request->date));
                        $sale_details->sale_id = $Sale->id;
                        $sale_details->category_id = $request->category_id[$i];
                        $sale_details->product_id = $request->product_id[$i];
                        $sale_details->selling_qty = $request->selling_qty[$i];
                        $sale_details->unit_selling_price = $request->unit_selling_price[$i];
                        $sale_details->selling_price = $request->selling_price[$i];
                        $sale_details->unit_id = $request->unit_id[$i];
                        $sale_details->save();

                        // Lock row to prevent lost updates under concurrent sales.
                        $product = Product::whereKey($request->product_id[$i])->lockForUpdate()->firstOrFail();
                        $beforeQty = (float) $product->quantity;
                        $lineQty = (float) $sale_details->selling_qty;

                        if ($lineQty <= 0 || $beforeQty < $lineQty) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'selling_qty.' . $i => 'Insufficient stock for item #' . ($i + 1) . '.',
                            ]);
                        }

                        $afterQty = $beforeQty - $lineQty;
                        $product->quantity = $afterQty;
                        $product->save();

                        app(StockLedgerService::class)->record([
                            'product_id' => $product->id,
                            'movement_type' => 'sale',
                            'reference_type' => 'Sale',
                            'reference_id' => $Sale->id,
                            'reference_detail_id' => $sale_details->id,
                            'movement_at' => $Sale->date,
                            'qty_in' => 0,
                            'qty_out' => $lineQty,
                            'balance_before' => $beforeQty,
                            'balance_after' => $afterQty,
                            'unit_price' => $sale_details->unit_selling_price,
                            'reason' => 'Sale stock-out',
                            'notes' => $Sale->description,
                            'created_by' => Auth::id(),
                        ]);
                    }

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

                    $payment->sale_id = $Sale->id;
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

                    $payment_details->sale_id = $Sale->id;
                    $payment_details->date = date('Y-m-d', strtotime($request->date));
                    $payment_details->save();

                    return $Sale;
                });

                break;
            } catch (QueryException $e) {
                $isDuplicateInvoiceNo = (string) ($e->getCode()) === '23000'
                    && str_contains(strtolower($e->getMessage()), 'sale_no');

                if ($isDuplicateInvoiceNo && $attempts < $maxAttempts) {
                    continue;
                }

                throw $e;
            }
        }

        // Now $Sale is available here
        if ($request->ajax()) {
            // Calculate next number after commit
            $maxInvoiceNo = (int) DB::table('sales')
                ->selectRaw('COALESCE(MAX(CAST(sale_no AS UNSIGNED)), 0) as max_no')
                ->value('max_no');
            $nextNo = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);

            return response()->json([
                'status' => 200,
                'message' => 'Sale Data Inserted Successfully',
                'print_url' => route('print.sale', Crypt::encrypt($Sale->id)),
                'next_no' => $nextNo, // send next Sale number to frontend
            ]);
        } else {
            return redirect()->route('print.sale', Crypt::encrypt($Sale->id));
        }
    }


    public function cancel(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $Sale = Sale::with('sale_details')->findOrFail($id);

            if ($Sale->cancelled_at) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Sale #' . $Sale->sale_no . ' is already cancelled.',
                ], 422);
            }

            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ], [
                'reason.required' => 'Please provide a reason for cancellation.',
            ]);

            DB::transaction(function () use ($Sale, $validated) {
                // Restore stock for every line item
                foreach ($Sale->sale_details as $detail) {
                    $product = Product::whereKey($detail->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $beforeQty = (float) $product->quantity;
                        $lineQty = (float) $detail->selling_qty;
                        $afterQty = $beforeQty + $lineQty;
                        $product->quantity = $afterQty;
                        $product->save();

                        //StockLedgerService record to log stock movement
                        app(StockLedgerService::class)->record([
                            'product_id' => $product->id,
                            'movement_type' => 'sale_cancel',
                            'reference_type' => 'Sale',
                            'reference_id' => $Sale->id,
                            'reference_detail_id' => $detail->id,
                            'movement_at' => now(),
                            'qty_in' => $lineQty,
                            'qty_out' => 0,
                            'balance_before' => $beforeQty,
                            'balance_after' => $afterQty,
                            'unit_price' => $detail->unit_selling_price,
                            'reason' => 'Sale cancellation',
                            'notes' => $validated['reason'],
                            'created_by' => Auth::id(),
                        ]);
                    }
                }

                // Zero-out line values so cancelled Sale does not affect quantity/value reporting
                SaleDetail::where('sale_id', $Sale->id)->update([
                    'selling_qty' => 0,
                    'unit_selling_price' => 0,
                    'selling_price' => 0,
                ]);

                // Zero-out payment values so cancelled Sale does not affect financial totals
                Payment::where('sale_id', $Sale->id)->update([
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

                // Zero-out payment detail entries tied to this Sale
                PaymentDetail::where('sale_id', $Sale->id)->update([
                    'current_paid_amount' => 0,
                ]);

                // Stamp cancellation on the Sale
                $Sale->update([
                    'cancelled_at'        => now(),
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $validated['reason'],
                ]);
            });

            return response()->json([
                'status'  => 200,
                'message' => 'Sale #' . $Sale->sale_no . ' has been cancelled and stock has been restored.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 422,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed to cancel Sale.',
            ], 400);
        }
    } // End Method

    public function printSaleList(){

       $allData = Sale::orderBy('date','desc')->orderBy('id','desc')->get();
         return view('backend.sale.print_sale_list',compact('allData'));
    } // End Method


    //detail Sale print
    public function printSale($encryptedId){

        $id = Crypt::decrypt($encryptedId);
        $Sale = Sale::with(['sale_details.category', 'sale_details.product', 'createdBy'])->findOrFail($id);
        $companyInfo = Company::first();
        return view('backend.sale.sale_receipt',compact('Sale','companyInfo'));

    } // End Method

}
 
