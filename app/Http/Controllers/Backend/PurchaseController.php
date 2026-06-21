<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\Purchase\AllPurchasesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\PurchasePaymentDetail;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Services\StockLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
  
class PurchaseController extends Controller

{
    function __construct()
    {
    $this->middleware('permission:purchase-list', ['only' => ['index']]);
    $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:purchase-cancel', ['only' => ['cancel']]);
    $this->middleware('permission:purchase-print', ['only' => ['PrintPurchaseList', 'PrintPurchase']]);
    }

   
    public function index(AllPurchasesDataTable $dataTable){
        $suppliers = Supplier::orderBy('name')->get();
        $purchase_invoices = Purchase::orderBy('purchase_no')->get();
        $users = User::orderBy('name')->get();
        return $dataTable->render('backend.purchase.index', compact('suppliers', 'purchase_invoices', 'users'));
    } // End Method

    public function create(){ 
        $unit = Unit::all();
        $category = Category::all();
        $suppliers = Supplier::all();

        // Get the maximum numeric purchase_no
        $maxPurchaseNo = Purchase::max(DB::raw('CAST(purchase_no AS UNSIGNED)'));
        if ($maxPurchaseNo) {
            $purchase_no = str_pad($maxPurchaseNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $purchase_no = '01';
        }

        $date = Carbon::now()->format('Y-m-d');        
       
       return view('backend.purchase.create',compact('purchase_no','category','date','suppliers','unit'));
    } // End Method


        public function store(Request $request)
    {
        // Backend validation
        $rules = [
            'date' => ['required', 'date'],
            'category_id' => ['required', 'array', 'min:1'],
            'category_id.*' => ['required'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required'],
            'unit_id' => ['required', 'array', 'min:1'],
            'unit_id.*' => ['required'],
            'buying_qty' => ['required', 'array', 'min:1'],
            'buying_qty.*' => ['required', 'numeric', 'min:1'],
            'unit_cost_price' => ['required', 'array', 'min:1'],
            'unit_cost_price.*' => ['required', 'numeric', 'min:0.01'],
            'unit_selling_price' => ['required', 'array', 'min:1'],
            'unit_selling_price.*' => ['required', 'numeric', 'min:0.01'],
            'paid_status' => ['required', Rule::in(['full_paid', 'full_due', 'partial_paid'])],
        ];

        // paid_amount required if partial_paid
        if ($request->paid_status === 'partial_paid') {
            $rules['paid_amount'] = ['required', 'numeric', 'min:1', 'max:' . $request->estimated_amount];
        }

        // New supplier fields required if supplier_id == 0
        if ($request->supplier_id == '0') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['mobile_no'] = ['required', 'digits:10'];
            $rules['email'] = ['required', 'email', 'max:255'];
        }

        $validated = $request->validate($rules);

        foreach ($request->unit_cost_price as $index => $costPrice) {
            $sellingPrice = $request->unit_selling_price[$index] ?? null;
            if ((float) $sellingPrice <= (float) $costPrice) {
                throw ValidationException::withMessages([
                    'unit_selling_price.' . $index => 'Unit selling price must be greater than unit cost price for item #' . ($index + 1) . '.',
                ]);
            }
        }

        DB::transaction(function () use ($request) {

            // Generate next purchase number here
            $lastPurchase = Purchase::orderBy('id', 'desc')->first();
            $nextNo = $lastPurchase ? str_pad(((int)$lastPurchase->purchase_no + 1), 2, '0', STR_PAD_LEFT) : '01';

            // Create purchase record with backend-generated number
            $purchase = Purchase::create([
                'purchase_no' => $nextNo,
                'date' => $request->date,
                'description' => $request->description,
                'created_by' => Auth::id(),
            ]);

            $count_category = count($request->category_id);
            for ($i = 0; $i < $count_category; $i++) {
                $purchase_details = new PurchaseDetail();
                $purchase_details->date = date('Y-m-d', strtotime($request->date));
                $purchase_details->purchase_id = $purchase->id;
                $purchase_details->category_id = $request->category_id[$i];
                $purchase_details->product_id = $request->product_id[$i];
                $purchase_details->buying_qty = $request->buying_qty[$i];
                $purchase_details->unit_cost_price = $request->unit_cost_price[$i];
                $purchase_details->buying_price = $request->buying_price[$i];
                $purchase_details->unit_id = $request->unit_id[$i];
                $purchase_details->save();

                // Update product stock and prices
                $product = Product::find($request->product_id[$i]);
                $beforeQty = (float) $product->quantity;
                $lineQty = (float) $purchase_details->buying_qty;
                $afterQty = $beforeQty + $lineQty;
                $product->quantity = $afterQty;

                $priceChanged = false;

                // Update cost_price if changed or missing
                if (empty($product->cost_price) || $product->cost_price != $purchase_details->unit_cost_price) {
                    $product->cost_price = $purchase_details->unit_cost_price;
                    $priceChanged = true;
                }

                // Update selling_price if provided in request
                if (!empty($request->unit_selling_price[$i])) {
                    if (empty($product->selling_price) || $product->selling_price != $request->unit_selling_price[$i]) {
                        $product->selling_price = $request->unit_selling_price[$i];
                        $priceChanged = true;
                    }
                }

                // Always save product after possible changes
                $product->save();

                // Save history if either cost or selling price changed
                if ($priceChanged) {
                    ProductPrice::create([
                        'product_id'    => $product->id,
                        'cost_price'    => $product->cost_price,
                        'selling_price' => $product->selling_price,
                        'created_by'    => Auth::id(),
                    ]);
                }

                //StockLedgerService record to log stock movement
                app(StockLedgerService::class)->record([
                    'product_id' => $product->id,
                    'movement_type' => 'purchase',
                    'reference_type' => 'purchase',
                    'reference_id' => $purchase->id,
                    'reference_detail_id' => $purchase_details->id,
                    'movement_at' => $purchase->date,
                    'qty_in' => $lineQty,
                    'qty_out' => 0,
                    'balance_before' => $beforeQty,
                    'balance_after' => $afterQty,
                    'unit_cost' => $purchase_details->unit_cost_price,
                    'reason' => 'Purchase stock-in',
                    'notes' => $purchase->description,
                    'created_by' => Auth::id(),
                ]);
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
            } else {
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
                $purchase_payment->due_amount = 0;
                $purchase_payment_details->current_paid_amount = $request->estimated_amount;
            } elseif ($request->paid_status == 'full_due') {
                $purchase_payment->paid_amount = 0;
                $purchase_payment->due_amount = $request->estimated_amount;
                $purchase_payment_details->current_paid_amount = 0;
            } elseif ($request->paid_status == 'partial_paid') {
                $purchase_payment->paid_amount = $request->paid_amount;
                $purchase_payment->due_amount = $request->estimated_amount - $request->paid_amount;
                $purchase_payment_details->current_paid_amount = $request->paid_amount;
            }
            $purchase_payment->save();

            $purchase_payment_details->purchase_id = $purchase->id;
            $purchase_payment_details->date = date('Y-m-d', strtotime($request->date));
            $purchase_payment_details->save();
        });

        // Return JSON for AJAX, redirect for normal
        if ($request->ajax()) {
            // Calculate next number after commit
            $lastPurchase = Purchase::orderBy('id', 'desc')->first();
            $nextNo = $lastPurchase ? str_pad(((int)$lastPurchase->purchase_no + 1), 2, '0', STR_PAD_LEFT) : '01';

            return response()->json([
                'status' => 200,
                'message' => 'Purchase Data Inserted Successfully',
                'next_no' => $nextNo, // send next number to frontend
            ]);
        } else {
            $notification = [
                'message' => 'Purchase Data Inserted Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('purchases.create')->with($notification);
        }
    }


    public function cancel(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $purchase = Purchase::with('purchase_details')->findOrFail($id);

            if ($purchase->cancelled_at) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Purchase #' . $purchase->purchase_no . ' is already cancelled.',
                ], 422);
            }

            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ], [
                'reason.required' => 'Please provide a reason for cancellation.',
            ]);

            DB::transaction(function () use ($purchase, $validated) {
                // Reverse stock-in for every line item. If stock has been consumed, block cancellation.
                foreach ($purchase->purchase_details as $detail) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $beforeQty = (float) $product->quantity;
                        $lineQty = (float) $detail->buying_qty;
                        $newQty = $beforeQty - $lineQty;
                        if ($newQty < 0) {
                            throw ValidationException::withMessages([
                                'reason' => ['Cannot cancel this purchase because some purchased stock has already been used/sold.'],
                            ]);
                        }
                        $product->quantity = $newQty;
                        $product->save();

                        app(StockLedgerService::class)->record([
                            'product_id' => $product->id,
                            'movement_type' => 'purchase_cancel',
                            'reference_type' => 'purchase',
                            'reference_id' => $purchase->id,
                            'reference_detail_id' => $detail->id,
                            'movement_at' => now(),
                            'qty_in' => 0,
                            'qty_out' => $lineQty,
                            'balance_before' => $beforeQty,
                            'balance_after' => $newQty,
                            'unit_cost' => $detail->unit_cost_price,
                            'reason' => 'Purchase cancellation',
                            'notes' => $validated['reason'],
                            'created_by' => Auth::id(),
                        ]);
                    }
                }

                // Zero-out line values so cancelled purchase does not affect quantity/value reporting
                PurchaseDetail::where('purchase_id', $purchase->id)->update([
                    'buying_qty' => 0,
                    'unit_cost_price' => 0,
                    'buying_price' => 0,
                ]);

                // Zero-out payment values so cancelled purchase does not affect financial totals
                PurchasePayment::where('purchase_id', $purchase->id)->update([
                    'paid_status' => 'full_paid',
                    'paid_amount' => 0,
                    'due_amount' => 0,
                    'total_amount' => 0,
                    'discount_amount' => 0,
                ]);

                // Zero-out payment detail entries tied to this purchase
                PurchasePaymentDetail::where('purchase_id', $purchase->id)->update([
                    'current_paid_amount' => 0,
                ]);

                // Stamp cancellation on the purchase
                $purchase->update([
                    'cancelled_at'        => now(),
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $validated['reason'],
                ]);
            });

            return response()->json([
                'status'  => 200,
                'message' => 'Purchase #' . $purchase->purchase_no . ' has been cancelled successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 422,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 400,
                'message' => 'Failed to cancel purchase.',
            ], 400);
        }
    } // End Method


    public function PrintPurchaseList(){

    $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->get();
       return view('backend.purchase.print_purchase_list',compact('allData'));
    } // End Method


    //detail purchase print
    public function PrintPurchase($encryptedId){
        
        $id = Crypt::decrypt($encryptedId);
        $purchase = Purchase::with('purchase_details')->findOrFail($id);
        $companyInfo = Company::first();

        return view('backend.purchase.purchase_receipt',compact('purchase','companyInfo'));

    } // End Method

}
