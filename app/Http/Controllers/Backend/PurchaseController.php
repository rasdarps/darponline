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
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Services\StockLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
  
class PurchaseController extends Controller

{
    function __construct()
    {
    $this->middleware('permission:purchase-list', ['only' => ['index']]);
    $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
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
        $products = Product::with(['category', 'unit'])->orderBy('name')->get();
        $suppliers = Supplier::all();

        // Get the maximum numeric purchase_no
        $maxPurchaseNo = Purchase::max(DB::raw('CAST(purchase_no AS UNSIGNED)'));
        if ($maxPurchaseNo) {
            $purchase_no = str_pad($maxPurchaseNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $purchase_no = '01';
        }

        $date = Carbon::now()->format('Y-m-d');        
       
         return view('backend.purchase.create',compact('purchase_no','category','date','suppliers','unit', 'products'));
    } // End Method


        public function store(Request $request)
    {
        // Backend validation
        $rules = [
            'date' => ['required', 'date'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,id'],
            'buying_qty' => ['required', 'array', 'min:1'],
            'buying_qty.*' => ['required', 'numeric', 'min:1'],
            'unit_cost_price' => ['required', 'array', 'min:1'],
            'unit_cost_price.*' => ['required', 'numeric', 'min:0.01'],
            'unit_selling_price' => ['required', 'array', 'min:1'],
            'unit_selling_price.*' => ['required', 'numeric', 'min:0.01'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
        ];

        // New supplier fields required if supplier_id requests inline creation
        if ($request->supplier_id === 'new_supplier') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['mobile_no'] = ['required', 'digits:10'];
            $rules['email'] = ['nullable', 'email', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['city'] = ['nullable', 'string', 'max:255'];
            $rules['type'] = ['nullable', 'string', 'max:255'];
        }

        $request->validate($rules);

        foreach ($request->unit_cost_price as $index => $costPrice) {
            $sellingPrice = $request->unit_selling_price[$index] ?? null;
            if ((float) $sellingPrice <= (float) $costPrice) {
                throw ValidationException::withMessages([
                    'unit_selling_price.' . $index => 'Unit selling price must be greater than unit cost price for item #' . ($index + 1) . '.',
                ]);
            }
        }

        $attempts = 0;
        $maxAttempts = 3;

        while ($attempts < $maxAttempts) {
            $attempts++;
            try {
                DB::transaction(function () use ($request) {

                    $maxPurchaseNo = (int) DB::table('purchases')
                        ->lockForUpdate()
                        ->selectRaw('COALESCE(MAX(CAST(purchase_no AS UNSIGNED)), 0) as max_no')
                        ->value('max_no');
                    $nextNo = str_pad($maxPurchaseNo + 1, 2, '0', STR_PAD_LEFT);

                    $purchase = Purchase::create([
                        'purchase_no' => $nextNo,
                        'date' => $request->date,
                        'description' => $request->description,
                        'created_by' => Auth::id(),
                    ]);

                    $countProducts = count($request->product_id);
                    for ($i = 0; $i < $countProducts; $i++) {
                        $product = Product::whereKey($request->product_id[$i])->lockForUpdate()->firstOrFail();

                        $categoryId = $product->category_id;
                        $unitId = $product->unit_id;

                        if (!$categoryId || !$unitId) {
                            throw ValidationException::withMessages([
                                'product_id.' . $i => 'Selected product is missing category or unit setup.',
                            ]);
                        }

                        $purchase_details = new PurchaseDetail();
                        $purchase_details->date = date('Y-m-d', strtotime($request->date));
                        $purchase_details->purchase_id = $purchase->id;
                        $purchase_details->category_id = $categoryId;
                        $purchase_details->product_id = $request->product_id[$i];
                        $purchase_details->buying_qty = $request->buying_qty[$i];
                        $purchase_details->unit_cost_price = $request->unit_cost_price[$i];
                        $purchase_details->buying_price = $request->buying_price[$i];
                        $purchase_details->unit_id = $unitId;
                        $purchase_details->save();

                        // Product row already locked at loop start.
                        $beforeQty = (float) $product->quantity;
                        $lineQty = (float) $purchase_details->buying_qty;
                        $afterQty = $beforeQty + $lineQty;
                        $product->quantity = $afterQty;

                        $priceChanged = false;

                        if (empty($product->cost_price) || $product->cost_price != $purchase_details->unit_cost_price) {
                            $product->cost_price = $purchase_details->unit_cost_price;
                            $priceChanged = true;
                        }

                        if (!empty($request->unit_selling_price[$i])) {
                            if (empty($product->selling_price) || $product->selling_price != $request->unit_selling_price[$i]) {
                                $product->selling_price = $request->unit_selling_price[$i];
                                $priceChanged = true;
                            }
                        }

                        $product->save();

                        if ($priceChanged) {
                            ProductPrice::create([
                                'product_id'    => $product->id,
                                'cost_price'    => $product->cost_price,
                                'selling_price' => $product->selling_price,
                                'created_by'    => Auth::id(),
                            ]);
                        }

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

                    if ($request->supplier_id === 'new_supplier') {
                        $supplier = Supplier::create([
                            'name' => $request->name,
                            'mobile_no' => $request->mobile_no,
                            'email' => $request->email,
                            'address' => $request->address,
                            'city' => $request->city,
                            'type' => $request->type,
                            'created_by' => Auth::id(),
                        ]);
                        $supplier_id = $supplier->id;
                    } else {
                        $supplier_id = $request->supplier_id;
                    }

                    $purchase_payment = new PurchasePayment();

                    $purchase_payment->purchase_id = $purchase->id;
                    $purchase_payment->supplier_id = $supplier_id;
                    $purchase_payment->discount_amount = $request->discount_amount;
                    $purchase_payment->total_amount = $request->estimated_amount;
                    $purchase_payment->paid_amount = $request->estimated_amount;
                    $purchase_payment->save();
                });

                break;
            } catch (QueryException $e) {
                $isDuplicatePurchaseNo = (string) ($e->getCode()) === '23000'
                    && str_contains(strtolower($e->getMessage()), 'purchase_no');

                if ($isDuplicatePurchaseNo && $attempts < $maxAttempts) {
                    continue;
                }

                throw $e;
            }
        }

        // Return JSON for AJAX, redirect for normal
        if ($request->ajax()) {
            // Calculate next number after commit
            $maxPurchaseNo = (int) DB::table('purchases')
                ->selectRaw('COALESCE(MAX(CAST(purchase_no AS UNSIGNED)), 0) as max_no')
                ->value('max_no');
            $nextNo = str_pad($maxPurchaseNo + 1, 2, '0', STR_PAD_LEFT);

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
                    $product = Product::whereKey($detail->product_id)->lockForUpdate()->first();
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
                    'paid_amount' => 0,
                    'total_amount' => 0,
                    'discount_amount' => 0,
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


    public function edit($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404);
        }

        $purchase = Purchase::with(['purchase_details.product', 'purchase_details.unit'])->findOrFail($id);

        if ($purchase->cancelled_at) {
            return redirect()->route('purchases.index')
                ->with(['message' => 'Cannot edit a cancelled purchase.', 'alert-type' => 'error']);
        }

        return view('backend.purchase.edit', compact('purchase', 'encryptedId'));
    } // End Method

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404);
        }

        $purchase = Purchase::with('purchase_details')->findOrFail($id);

        if ($purchase->cancelled_at) {
            return back()->withErrors(['edit_reason' => 'Cannot edit a cancelled purchase.'])->withInput();
        }

        $request->validate([
            'buying_qty'   => ['required', 'array', 'min:1'],
            'buying_qty.*' => ['required', 'numeric', 'min:0'],
            'edit_reason'  => ['required', 'string', 'max:500'],
        ]);

        $quantities = $request->buying_qty; // [ detail_id => new_qty ]

        // Guard: at least one line must remain with qty > 0
        $anyNonZero = collect($quantities)->contains(fn($q) => (float) $q > 0);
        if (! $anyNonZero) {
            return back()->withInput()->withErrors([
                'edit_reason' => 'Cannot zero out all items. To void this purchase entirely, use the Cancel button instead.',
            ]);
        }

        // Pre-validate stock guards before touching the DB
        $guardErrors = [];
        foreach ($quantities as $detailId => $newQty) {
            $newQty = (float) $newQty;
            $detail = $purchase->purchase_details->firstWhere('id', $detailId);
            if (! $detail) {
                continue;
            }
            $oldQty = (float) $detail->buying_qty;
            if ($newQty >= $oldQty) {
                continue; // increase or unchanged — no guard needed
            }
            $product     = $detail->product;
            $currentStock = (float) ($product->quantity ?? 0);
            $reduction   = $oldQty - $newQty;
            if ($reduction > $currentStock) {
                $guardErrors["buying_qty.{$detailId}"] = [
                    "{$product->name}: Cannot reduce by {$reduction} — only {$currentStock} units remain in stock (minimum allowed: " . ceil($oldQty - $currentStock) . ").",
                ];
            }
        }

        if (! empty($guardErrors)) {
            return back()->withInput()->withErrors($guardErrors);
        }

        DB::transaction(function () use ($purchase, $quantities, $request) {
            foreach ($quantities as $detailId => $newQty) {
                $newQty = (float) $newQty;

                $detail = PurchaseDetail::where('id', $detailId)
                    ->where('purchase_id', $purchase->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldQty = (float) $detail->buying_qty;
                if ($newQty == $oldQty) {
                    continue; // no change
                }

                $product      = Product::whereKey($detail->product_id)->lockForUpdate()->firstOrFail();
                $beforeStock  = (float) $product->quantity;
                $delta        = $newQty - $oldQty;
                $afterStock   = $beforeStock + $delta;

                // Race-condition guard inside transaction
                if ($afterStock < 0) {
                    throw ValidationException::withMessages([
                        "buying_qty.{$detailId}" => ["{$product->name}: Edit would cause negative stock. Rejected."],
                    ]);
                }

                if ($newQty == 0) {
                    $detail->delete();
                    $movementType = 'purchase_edit_decrease';
                    $qtyIn  = 0;
                    $qtyOut = $oldQty;
                } elseif ($delta > 0) {
                    $detail->buying_qty  = $newQty;
                    $detail->buying_price = round($newQty * (float) $detail->unit_cost_price, 2);
                    $detail->save();
                    $movementType = 'purchase_edit_increase';
                    $qtyIn  = $delta;
                    $qtyOut = 0;
                } else {
                    $detail->buying_qty  = $newQty;
                    $detail->buying_price = round($newQty * (float) $detail->unit_cost_price, 2);
                    $detail->save();
                    $movementType = 'purchase_edit_decrease';
                    $qtyIn  = 0;
                    $qtyOut = abs($delta);
                }

                $product->quantity = $afterStock;
                $product->save();

                app(StockLedgerService::class)->record([
                    'product_id'          => $product->id,
                    'movement_type'       => $movementType,
                    'reference_type'      => 'purchase',
                    'reference_id'        => $purchase->id,
                    'reference_detail_id' => $detailId,
                    'movement_at'         => now(),
                    'qty_in'              => $qtyIn,
                    'qty_out'             => $qtyOut,
                    'balance_before'      => $beforeStock,
                    'balance_after'       => $afterStock,
                    'unit_cost'           => $detail->unit_cost_price,
                    'reason'              => 'Purchase quantity edit',
                    'notes'               => $request->edit_reason,
                    'created_by'          => Auth::id(),
                ]);
            }

            // Stamp edit — Auditable trait auto-sets updated_by; updated_at is automatic
            $purchase->edit_reason = $request->edit_reason;
            $purchase->save();
        });

        return redirect()->route('purchases.index')
            ->with([
                'message'    => 'Purchase #' . $purchase->purchase_no . ' updated successfully.',
                'alert-type' => 'success',
            ]);
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
