<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Report\Sales\AllSalesDataTable;
use App\DataTables\Admin\Report\Sales\SaleReceiptAuditDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReceiptEvent;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Models\CashReconciliation;
use App\Services\StockLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    function __construct()
    {
    $this->middleware('permission:sale-list', ['only' => ['index']]);
    $this->middleware('permission:sale-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:sale-cancel', ['only' => ['cancel']]);
    $this->middleware('permission:sale-print|sale-list', ['only' => ['printSaleList']]);
    $this->middleware('permission:sale-print', ['only' => ['printSale']]);
    $this->middleware('permission:sale-reprint', ['only' => ['reprintSale']]);
    $this->middleware('permission:sale-receipt-print-audit-view', ['only' => ['receiptAuditReport']]);
    $this->middleware('permission:sale-delivery-verify', ['only' => ['deliveryVerification', 'verifyDelivery']]);
    $this->middleware('permission:sale-list', ['only' => ['dailyTotalSales']]);
    }

     public function index(AllSalesDataTable $dataTable){
        // $allData = Sale::orderBy('date','desc')->orderBy('id','desc')->get();
          $customers = Customer::orderBy('name')->get();
          $sales = Sale::orderBy('sale_no')->get();
          $users = User::orderBy('name')->get();
                                        /** @var User|null $currentUser */
                                        $currentUser = Auth::user();
                                        $isRestrictedToOwnSales = $currentUser && $currentUser->hasRole('Cashier');
                    return $dataTable->render('backend.sale.index', compact('customers', 'sales', 'users', 'isRestrictedToOwnSales'));

    }

    public function create(){ 

        if ($gate = $this->previousDayReconciliationGate()) {
            return redirect()->route('cash-reconciliations.create')
                ->with('reconciliation_gate', $gate)
                ->with('error', $gate['message'])
                ->with('info', $gate['action']);
        }

        $unit = Unit::all();
        $category = Category::all();
        $products = Product::with(['category', 'unit'])->orderBy('name')->get();
        $costomer = Customer::all();

        // Get the maximum numeric sale_no
        $maxInvoiceNo = Sale::max(DB::raw('CAST(sale_no AS UNSIGNED)'));
        if ($maxInvoiceNo) {
            $sale_no = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $sale_no = '01';
        }

        $date = Carbon::now()->format('Y-m-d');        
        $allowNegativeStock = (bool) config('pos.allow_negative_stock', false);
        $preventBackdatedInvoices = (bool) config('pos.prevent_backdated_invoices', false);
        $allowManualDiscountEntryByStaff = (bool) config('pos.allow_manual_discount_entry_by_staff', true);

        return view('backend.sale.create',compact(
            'sale_no',
            'category',
            'date',
            'costomer',
            'products',
            'unit',
            'allowNegativeStock',
            'preventBackdatedInvoices',
            'allowManualDiscountEntryByStaff'
        ));

    } // End Method



    public function store(Request $request)
    {
        if ($gate = $this->previousDayReconciliationGate()) {
            throw ValidationException::withMessages([
                'reconciliation' => $gate['message'] . ' ' . $gate['action'],
            ]);
        }

        $allowNegativeStock = (bool) config('pos.allow_negative_stock', false);
        $preventBackdatedInvoices = (bool) config('pos.prevent_backdated_invoices', false);
        $allowManualDiscountEntryByStaff = (bool) config('pos.allow_manual_discount_entry_by_staff', true);

        $grossTotal = collect($request->selling_price ?? [])->sum(function ($value) {
            return (float) $value;
        });

        $discountAmount = $allowManualDiscountEntryByStaff
            ? (float) ($request->discount_amount ?? 0)
            : 0;

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
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,id'],
            'selling_qty' => ['required', 'array', 'min:1'],
            'selling_qty.*' => ['required', 'numeric', 'min:0.01'],
            'unit_selling_price' => ['required', 'array', 'min:1'],
            'unit_selling_price.*' => ['required', 'numeric', 'min:0.01'],
            'selling_price' => ['required', 'array', 'min:1'],
            'selling_price.*' => ['required', 'numeric', 'min:0.01'],
            'cash_tendered' => ['required', 'numeric', 'min:' . $calculated['total_amount']],
        ];

        if ($request->customer_id === 'new_customer') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['mobile_no'] = ['required', 'string', 'max:20'];
            $rules['email'] = ['nullable', 'email', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        $request->validate($rules);

        if ($preventBackdatedInvoices && Carbon::parse($request->date)->isBefore(Carbon::today())) {
            throw ValidationException::withMessages([
                'date' => 'Backdated invoices are disabled. Please use today\'s date.',
            ]);
        }

        $Sale = null;
        $attempts = 0;
        $maxAttempts = 3;
        $saleNoClashDetected = false;

        while ($attempts < $maxAttempts) {
            $attempts++;
            try {
                $Sale = DB::transaction(function() use($request, $calculated, $allowNegativeStock) {

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

                    $Sale->forceFill([
                        'delivery_code' => $this->generateDeliveryCode((int) $Sale->id),
                    ])->saveQuietly();

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

                        $sale_details = new SaleDetail();
                        $sale_details->date = date('Y-m-d', strtotime($request->date));
                        $sale_details->sale_id = $Sale->id;
                        $sale_details->category_id = $categoryId;
                        $sale_details->product_id = $request->product_id[$i];
                        $sale_details->selling_qty = $request->selling_qty[$i];
                        $sale_details->unit_selling_price = $request->unit_selling_price[$i];
                        $sale_details->selling_price = $request->selling_price[$i];
                        $sale_details->unit_id = $unitId;
                        $sale_details->save();

                        // Product row already locked at loop start.
                        $beforeQty = (float) $product->quantity;
                        $lineQty = (float) $sale_details->selling_qty;

                        if ($lineQty <= 0) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'selling_qty.' . $i => 'Quantity for item #' . ($i + 1) . ' must be greater than zero.',
                            ]);
                        }

                        if (! $allowNegativeStock && $beforeQty < $lineQty) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'selling_qty.' . $i => 'One of the selected items has negative stock so remove from invoice',
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

                    if ($request->customer_id === 'new_customer') {
                        $customer = Customer::create([
                            'name' => $request->name,
                            'mobile_no' => $request->mobile_no,
                            'email' => $request->email,
                            'address' => $request->address,
                            'created_by' => Auth::id(),
                        ]);
                        $customer_id = $customer->id;
                    } else {
                        $customer_id = $request->customer_id;
                    }

                    $payment = new Payment();

                    $payment->sale_id = $Sale->id;
                    $payment->customer_id = $customer_id;
                    $payment->discount_amount = $calculated['discount_amount'];
                    $payment->total_amount = $calculated['total_amount'];
                    $payment->taxable_amount = $calculated['taxable_amount'];
                    $payment->nhil_amount = $calculated['nhil_amount'];
                    $payment->getfund_amount = $calculated['getfund_amount'];
                    $payment->vat_amount = $calculated['vat_amount'];
                    $payment->cash_tendered = $calculated['cash_tendered'];
                    $payment->change_due = $calculated['change_due'];
                    $payment->paid_amount = $calculated['total_amount'];
                    $payment->save();

                    return $Sale;
                });

                break;
            } catch (QueryException $e) {
                $isDuplicateInvoiceNo = (string) ($e->getCode()) === '23000'
                    && str_contains(strtolower($e->getMessage()), 'sale_no');

                if ($isDuplicateInvoiceNo) {
                    if ($attempts < $maxAttempts) {
                        continue;
                    }

                    $saleNoClashDetected = true;
                    break;
                }

                throw $e;
            }
        }

        if (!$Sale && $saleNoClashDetected) {
            $maxInvoiceNo = (int) DB::table('sales')
                ->selectRaw('COALESCE(MAX(CAST(sale_no AS UNSIGNED)), 0) as max_no')
                ->value('max_no');
            $nextNo = str_pad($maxInvoiceNo + 1, 2, '0', STR_PAD_LEFT);

            $message = 'Sale number clash detected. Another checkout used the same Sale number.';
            $action = 'Action: click Save Sale again to continue with Sale #' . $nextNo . '.';

            if ($request->ajax()) {
                return response()->json([
                    'status' => 409,
                    'error_code' => 'SALE_NO_CLASH',
                    'message' => $message,
                    'action' => $action,
                    'next_no' => $nextNo,
                ], 409);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['sale_no' => $message . ' ' . $action]);
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
                    'discount_amount' => 0,
                    'total_amount' => 0,
                    'taxable_amount' => 0,
                    'nhil_amount' => 0,
                    'getfund_amount' => 0,
                    'vat_amount' => 0,
                    'cash_tendered' => 0,
                    'change_due' => 0,
                    'paid_amount' => 0,
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

    private function previousDayReconciliationGate(): ?array
    {
        if (! config('pos.enforce_previous_day_reconciliation', false)) {
            return null;
        }

        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return null;
        }

        $today = Carbon::today()->toDateString();

        $salesDates = Sale::query()
            ->where('created_by', $userId)
            ->whereDate('date', '<', $today)
            ->whereNull('cancelled_at')
            ->selectRaw('DATE(`date`) as business_date')
            ->distinct()
            ->orderBy('business_date')
            ->pluck('business_date')
            ->map(static function ($value) {
                return Carbon::parse((string) $value)->toDateString();
            })
            ->values();

        if ($salesDates->isEmpty()) {
            return null;
        }

        $reconciliationsByDate = CashReconciliation::query()
            ->where('user_id', $userId)
            ->whereIn('business_date', $salesDates->all())
            ->get(['business_date', 'status'])
            ->keyBy(static function ($row) {
                return optional($row->business_date)->format('Y-m-d');
            });

        $missingDates = [];
        $unapprovedDates = [];

        foreach ($salesDates as $date) {
            $reconcile = $reconciliationsByDate->get($date);

            if (! $reconcile) {
                $missingDates[] = $date;
                continue;
            }

            if ((string) $reconcile->status !== 'approved') {
                $unapprovedDates[] = $date;
            }
        }

        if (empty($missingDates) && empty($unapprovedDates)) {
            return null;
        }

        $allPendingDates = collect(array_merge($missingDates, $unapprovedDates))
            ->unique()
            ->sort()
            ->values()
            ->all();

        $formatDates = static function (array $dates): string {
            return collect($dates)
                ->map(static function ($date) {
                    return Carbon::parse((string) $date)->format('d-m-Y');
                })
                ->implode(', ');
        };

        $pendingDatesLabel = $formatDates($allPendingDates);
        $missingDatesLabel = $formatDates($missingDates);
        $unapprovedDatesLabel = $formatDates($unapprovedDates);

        $message = 'Sales creation is blocked. You have pending cash reconciliation for previous date(s): ' . $pendingDatesLabel . '.';

        $actionParts = [];
        if (! empty($missingDates)) {
            $actionParts[] = 'Submit reconciliation for missing date(s): ' . $missingDatesLabel . '.';
        }
        if (! empty($unapprovedDates)) {
            $actionParts[] = 'Tell your Manager/Supervisor to approve submitted reconciliation for date(s): ' . $unapprovedDatesLabel . '.';
        }

        return [
            'message' => $message,
            'action' => 'Action: ' . implode(' ', $actionParts),
            'missing_dates' => $missingDates,
            'unapproved_dates' => $unapprovedDates,
            'pending_dates' => $allPendingDates,
        ];
    }

    public function printSaleList(){

       $allData = Sale::with(['payment.customer', 'printedBy', 'deliveredBy'])
           ->orderBy('date','desc')
           ->orderBy('id','desc')
           ->get();
         return view('backend.sale.print_sale_list',compact('allData'));
    } // End Method


    //detail Sale print
    public function printSale($encryptedId){

        $id = Crypt::decrypt($encryptedId);
        $receiptData = DB::transaction(function () use ($id) {
            $sale = Sale::with(['sale_details.category', 'sale_details.product', 'createdBy', 'printedBy', 'deliveredBy', 'payment.customer'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($sale->printed_at) {
                return [
                    'redirect' => true,
                    'message' => 'Sale #' . $sale->sale_no . ' has already been printed.',
                ];
            }

            $sale->forceFill([
                'printed_at' => now(),
                'printed_by' => Auth::id(),
            ])->save();

            $this->recordReceiptEvent($sale, 'original_print');

            return [
                'redirect' => false,
                'sale' => $sale->fresh(['sale_details.category', 'sale_details.product', 'createdBy', 'printedBy', 'deliveredBy', 'payment.customer']),
            ];
        });

        if (! empty($receiptData['redirect'])) {
            return redirect()->route('sales.index')->with('error', $receiptData['message']);
        }

        $companyInfo = Company::first();
        $Sale = $receiptData['sale'];
        $printMode = 'original';

        return view('backend.sale.sale_receipt',compact('Sale','companyInfo','printMode'));

    } // End Method

    public function reprintSale(Request $request, $encryptedId)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ], [
            'reason.required' => 'Please provide a reason for the reprint.',
        ]);

        $id = Crypt::decrypt($encryptedId);

        $receiptData = DB::transaction(function () use ($id, $validated, $request) {
            $sale = Sale::with(['sale_details.category', 'sale_details.product', 'createdBy', 'printedBy', 'deliveredBy', 'payment.customer'])
                ->lockForUpdate()
                ->findOrFail($id);

            if (! $sale->printed_at) {
                return [
                    'redirect' => true,
                    'message' => 'Original print must be recorded before a reprint is allowed.',
                ];
            }

            $this->recordReceiptEvent($sale, 'reprint', $validated['reason'], $request);

            return [
                'redirect' => false,
                'sale' => $sale->fresh(['sale_details.category', 'sale_details.product', 'createdBy', 'printedBy', 'deliveredBy', 'payment.customer']),
            ];
        });

        if (! empty($receiptData['redirect'])) {
            return redirect()->route('sales.index')->with('error', $receiptData['message']);
        }

        $companyInfo = Company::first();
        $Sale = $receiptData['sale'];
        $printMode = 'reprint';

        return view('backend.sale.sale_receipt',compact('Sale','companyInfo','printMode'));

    }

    public function receiptAuditReport(SaleReceiptAuditDataTable $dataTable)
    {
        $viewer = Auth::user();
        $viewerIsSuperAdmin = $viewer && strtolower((string) ($viewer->user_type ?? '')) === 'super admin';

        $salesQuery = Sale::query()
            ->select('sales.id', 'sales.sale_no')
            ->join('sale_receipt_events', 'sale_receipt_events.sale_id', '=', 'sales.id')
            ->join('users', 'users.id', '=', 'sale_receipt_events.operator_id')
            ->distinct();

        if (! $viewerIsSuperAdmin) {
            $salesQuery->whereRaw("LOWER(COALESCE(users.user_type, '')) <> 'super admin'");
        }

        $sales = $salesQuery->orderBy('sales.sale_no')->get();

        $usersQuery = User::query()->select(['id', 'name', 'username']);
        if (! $viewerIsSuperAdmin) {
            $usersQuery->whereRaw("LOWER(COALESCE(user_type, '')) <> 'super admin'");
        }

        $users = $usersQuery->orderBy('name')->get();

        return $dataTable->render('backend.report.sale_receipt_audit', compact('sales', 'users', 'viewerIsSuperAdmin'));

    }

    public function deliveryVerification()
    {
        $companyInfo = Company::first();

        return view('backend.sale.delivery_verification', compact('companyInfo'));
    }

    public function verifyDelivery(Request $request)
    {
        $validated = $request->validate([
            'delivery_code' => ['required', 'string', 'max:40'],
        ]);

        $normalizedCode = $this->normalizeDeliveryCode($validated['delivery_code']);

        if ($normalizedCode === '') {
            return response()->json([
                'status' => 422,
                'message' => 'Please scan or enter a valid receipt delivery code.',
            ], 422);
        }

        $result = DB::transaction(function () use ($normalizedCode, $request) {
            $sale = Sale::where('delivery_code', $normalizedCode)
                ->lockForUpdate()
                ->first();

            if (! $sale) {
                return [
                    'status' => 404,
                    'message' => 'No sale was found for that delivery code.',
                ];
            }

            if ($sale->cancelled_at) {
                return [
                    'status' => 422,
                    'message' => 'Cancelled sales cannot be marked delivered.',
                ];
            }

            if ($sale->delivered_at) {
                return [
                    'status' => 409,
                    'message' => 'This sale was already marked delivered at ' . $sale->delivered_at->format('d-m-Y h:i A') . '.',
                ];
            }

            $sale->forceFill([
                'delivered_at' => now(),
                'delivered_by' => Auth::id(),
            ])->save();

            $this->recordReceiptEvent($sale, 'delivery', null, $request);

            return [
                'status' => 200,
                'message' => 'Sale #' . $sale->sale_no . ' marked delivered successfully.',
                'sale_no' => $sale->sale_no,
                'delivery_code' => $sale->delivery_code,
                'delivered_at' => $sale->delivered_at?->format('d-m-Y h:i A'),
            ];
        });

        return response()->json($result, $result['status']);

    } // End Method

    public function dailyTotalSales(Request $request)
    {
        /** @var User|null $currentUser */
        $currentUser = Auth::user();
        $canSelectCashier = $currentUser && $currentUser->hasAnyRole(['Super Admin', 'Manager', 'Supervisor']);

        $validationRules = [
            'date' => ['nullable', 'date'],
        ];

        if ($canSelectCashier) {
            $validationRules['user_id'] = ['nullable', 'integer', 'exists:users,id'];
        }

        $validated = $request->validate($validationRules);
        $selectedDate = $validated['date'] ?? Carbon::now()->toDateString();

        $cashiers = collect();
        $selectedUserId = (int) $currentUser->id;
        $selectedUser = $currentUser;

        if ($canSelectCashier) {
            $cashiers = User::role('Cashier')->orderBy('name')->get(['id', 'name', 'username']);

            $selectedUserId = (int) ($validated['user_id'] ?? 0);
            if (! $selectedUserId && $cashiers->isNotEmpty()) {
                $selectedUserId = (int) $cashiers->first()->id;
            }

            $selectedUser = $cashiers->firstWhere('id', $selectedUserId);
            if (! $selectedUser && $cashiers->isNotEmpty()) {
                $selectedUser = $cashiers->first();
                $selectedUserId = (int) $selectedUser->id;
            }

            if (! $selectedUser) {
                $selectedUser = $currentUser;
                $selectedUserId = (int) $currentUser->id;
            }
        }

        $totalSalesAmount = Payment::query()
            ->join('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereDate('sales.date', $selectedDate)
            ->whereNull('sales.cancelled_at')
            ->where('sales.created_by', $selectedUserId)
            ->sum('payments.total_amount');

        $companyInfo = Company::first();

        return view('backend.sale.daily_total_sales', [
            'selectedDate' => $selectedDate,
            'username' => $selectedUser->username ?? $selectedUser->name,
            'canSelectCashier' => $canSelectCashier,
            'cashiers' => $cashiers,
            'selectedUserId' => $selectedUserId,
            'totalSalesAmount' => (float) $totalSalesAmount,
            'companyInfo' => $companyInfo,
        ]);
    }

    private function generateDeliveryCode(int $saleId): string
    {
        do {
            $candidate = sprintf('DLV-%08d-%s', $saleId, strtoupper(Str::random(6)));
        } while (Sale::where('delivery_code', $candidate)->exists());

        return $candidate;
    }

    private function recordReceiptEvent(Sale $sale, string $eventType, ?string $reason = null, ?Request $request = null): SaleReceiptEvent
    {
        return SaleReceiptEvent::create([
            'sale_id' => $sale->id,
            'operator_id' => Auth::id(),
            'event_type' => $eventType,
            'reason' => $reason,
            'event_at' => now(),
            'ip_address' => $request?->ip() ?: request()->ip(),
            'user_agent' => $request?->userAgent() ?: request()->userAgent(),
        ]);
    }

    private function normalizeDeliveryCode(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

}
 
