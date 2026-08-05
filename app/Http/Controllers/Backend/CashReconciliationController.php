<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\CashReconciliationDataTable;
use App\Http\Controllers\Controller;
use App\Models\CashReconciliation;
use App\Models\ExpenseVoucherItem;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CashReconciliationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:cash-reconciliation-list|cash-reconciliation-show', ['only' => ['index']]);
        $this->middleware('permission:cash-reconciliation-create', ['only' => ['create', 'store', 'expected']]);
        $this->middleware('permission:cash-reconciliation-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cash-reconciliation-show', ['only' => ['show']]);
        $this->middleware('permission:cash-reconciliation-approve', ['only' => ['approve']]);
        $this->middleware('permission:cash-reconciliation-reverse-approval', ['only' => ['reverseApproval']]);
    }

    public function index(CashReconciliationDataTable $dataTable)
    {
        /** @var User|null $currentUser */
        $currentUser = Auth::user();
        $viewerIsSuperAdmin = $currentUser && strtolower((string) ($currentUser->user_type ?? '')) === 'super admin';
        $viewerCanSeeAllCashiers = $currentUser
            && ($viewerIsSuperAdmin || $currentUser->hasRole('Manager') || $currentUser->hasRole('Supervisor'));

        $isRestrictedToOwnReconciles = ! $viewerCanSeeAllCashiers;

        return $dataTable->render('backend.cash_reconciliation.index', compact('isRestrictedToOwnReconciles'));
    }

    public function create()
    {
        $businessDate = now()->format('Y-m-d');
        $openingFloat = 0;
        $userId = (int) Auth::id();

        $expected = $this->computeExpectedForUser($userId, $businessDate, $openingFloat);

        $timingGate = $this->reconciliationTimingGate();
        $businessDateStatuses = $this->buildBusinessDateStatuses($userId);

        return view('backend.cash_reconciliation.create', compact('businessDate', 'openingFloat', 'expected', 'timingGate', 'businessDateStatuses'));
    }

    public function edit($routeId)
    {
        try {
            $decryptedId = $routeId;

            try {
                $decryptedId = Crypt::decryptString((string) $routeId);
            } catch (\Throwable $e) {
                // Keep the original route value when it is already numeric.
            }

            $reconciliation = CashReconciliation::with(['user', 'createdBy', 'approvedBy', 'reversedBy'])
                ->find($decryptedId);

            if (! $reconciliation && is_numeric($routeId)) {
                $reconciliation = CashReconciliation::with(['user', 'createdBy', 'approvedBy', 'reversedBy'])
                    ->find((int) $routeId);
            }

            if (! $reconciliation) {
                return redirect()->route('cash-reconciliations.index')->with('error', 'Cash reconciliation not found.');
            }

            if ($reconciliation->status === 'approved') {
                return redirect()->route('cash-reconciliations.show', $reconciliation->id)
                    ->with('error', 'Approved reconciliation is locked. Reverse approval before editing.');
            }

            $expected = $this->computeExpectedForUser(
                (int) $reconciliation->user_id,
                (string) $reconciliation->business_date->format('Y-m-d'),
                (float) $reconciliation->opening_float
            );

            return view('backend.cash_reconciliation.edit', compact('reconciliation', 'expected'));
        } catch (\Throwable $e) {
            return redirect()->route('cash-reconciliations.index')->with('error', 'Invalid reconciliation ID.');
        }
    }

    public function expected(Request $request)
    {
        $validated = $request->validate([
            'business_date' => 'required|date',
            'opening_float' => 'nullable|numeric|min:0',
        ]);

        $openingFloat = (float) ($validated['opening_float'] ?? 0);
        $expected = $this->computeExpectedForUser(Auth::id(), $validated['business_date'], $openingFloat);

        return response()->json($expected);
    }

    public function store(Request $request)
    {
        if ($gate = $this->reconciliationTimingGate()) {
            return redirect()->route('cash-reconciliations.create')
                ->with('reconciliation_time_gate', $gate)
                ->with('error', $gate['message'])
                ->with('info', $gate['action']);
        }

        $validated = $request->validate([
            'business_date' => 'required|date',
            'opening_float' => 'required|numeric|min:0',
            'physical_counted_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        $existing = CashReconciliation::where('business_date', $validated['business_date'])
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'A reconciliation already exists for this cashier and date. Please edit the existing record if it has been reopened.');
        }

        $openingFloat = (float) $validated['opening_float'];
        $physicalCash = (float) $validated['physical_counted_cash'];
        $expected = $this->computeExpectedForUser($userId, $validated['business_date'], $openingFloat);

        $variance = round($physicalCash - $expected['expected_closing_cash'], 2);
        $varianceType = 'balanced';

        if ($variance > 0) {
            $varianceType = 'overage';
        } elseif ($variance < 0) {
            $varianceType = 'shortage';
        }

        CashReconciliation::create([
            'business_date' => $validated['business_date'],
            'user_id' => $userId,
            'opening_float' => $openingFloat,
            'expected_cash_sales' => $expected['expected_cash_sales'],
            'expected_cash_expenses' => $expected['expected_cash_expenses'],
            'expected_closing_cash' => $expected['expected_closing_cash'],
            'physical_counted_cash' => $physicalCash,
            'variance' => $variance,
            'variance_type' => $varianceType,
            'status' => 'submitted',
            'notes' => $validated['notes'] ?? null,
            'created_by' => $userId,
        ]);

        return redirect()->route('cash-reconciliations.index')->with('success', 'Daily cash reconciliation submitted successfully.');
    }

    private function reconciliationTimingGate(): ?array
    {
        if (! config('pos.enforce_reconciliation_timing', false)) {
            return null;
        }

        $startTime = (string) config('pos.reconciliation_start_time', '15:00');
        if (preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $startTime) !== 1) {
            $startTime = '15:00';
        }

        $now = now();
        $currentTime = $now->format('H:i');
        if ($currentTime >= $startTime) {
            return null;
        }

        $startLabel = Carbon::createFromFormat('H:i', $startTime)->format('g:i A');
        $currentLabel = $now->format('g:i A');

        return [
            'message' => 'Cash reconciliation is not open yet. You can start reconciliation after ' . $startLabel . '.',
            'action' => 'Current time is ' . $currentLabel . '. Please wait until ' . $startLabel . ' or contact your Manager/Supervisor if this needs adjustment.',
            'start_time' => $startTime,
            'start_time_label' => $startLabel,
            'current_time_label' => $currentLabel,
        ];
    }

    private function buildBusinessDateStatuses(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $today = Carbon::today();
        $todayString = $today->toDateString();

        $salesDates = Sale::query()
            ->where('created_by', $userId)
            ->whereNull('cancelled_at')
            ->whereDate('date', '<=', $todayString)
            ->selectRaw('DATE(`date`) as business_date')
            ->distinct()
            ->pluck('business_date')
            ->map(static function ($value) {
                return Carbon::parse((string) $value)->toDateString();
            })
            ->values();

        $reconciliations = CashReconciliation::query()
            ->where('user_id', $userId)
            ->whereDate('business_date', '<=', $todayString)
            ->get(['business_date', 'status'])
            ->keyBy(static function ($row) {
                return optional($row->business_date)->format('Y-m-d');
            });

        $reconciliationDates = $reconciliations->keys()->filter()->values();

        $trackedDates = collect([$todayString])
            ->merge($salesDates)
            ->merge($reconciliationDates)
            ->unique()
            ->sort()
            ->values();

        $salesDateLookup = array_flip($salesDates->all());

        return $trackedDates
            ->map(function (string $businessDate) use ($reconciliations, $salesDateLookup, $todayString) {
                $reconciliation = $reconciliations->get($businessDate);
                $hasSales = isset($salesDateLookup[$businessDate]);

                $statusKey = 'open';
                $statusLabel = 'Open';
                $badgeClass = 'badge bg-primary';
                $statusNote = 'Reconciliation can be started.';

                if ($reconciliation) {
                    if ((string) $reconciliation->status === 'approved') {
                        $statusKey = 'reconciled';
                        $statusLabel = 'Reconciled';
                        $badgeClass = 'badge bg-success';
                        $statusNote = 'Reconciliation has been approved.';
                    } else {
                        $statusKey = 'pending_approval';
                        $statusLabel = 'Pending Approval';
                        $badgeClass = 'badge bg-warning text-dark';
                        $statusNote = 'Submitted and waiting for Manager/Supervisor approval.';
                    }
                } elseif ($hasSales && $businessDate < $todayString) {
                    $statusKey = 'blocked';
                    $statusLabel = 'Blocked';
                    $badgeClass = 'badge bg-danger';
                    $statusNote = 'Sales exist but reconciliation has not been submitted.';
                }

                return [
                    'business_date' => $businessDate,
                    'business_date_label' => Carbon::parse($businessDate)->format('d-m-Y'),
                    'status_key' => $statusKey,
                    'status_label' => $statusLabel,
                    'badge_class' => $badgeClass,
                    'status_note' => $statusNote,
                ];
            })
            ->reverse()
            ->values()
            ->all();
    }

    public function show($routeId)
    {
        try {
            $decryptedId = $routeId;

            try {
                $decryptedId = Crypt::decryptString((string) $routeId);
            } catch (\Throwable $e) {
                // Keep the original route value when it is already numeric.
            }

            $reconciliation = CashReconciliation::with(['user', 'createdBy', 'approvedBy', 'reversedBy'])
                ->find($decryptedId);

            if (! $reconciliation && is_numeric($routeId)) {
                $reconciliation = CashReconciliation::with(['user', 'createdBy', 'approvedBy', 'reversedBy'])
                    ->find((int) $routeId);
            }

            if (! $reconciliation) {
                return redirect()->route('cash-reconciliations.index')->with('error', 'Cash reconciliation not found.');
            }

            return view('backend.cash_reconciliation.show', compact('reconciliation'));
        } catch (\Throwable $e) {
            return redirect()->route('cash-reconciliations.index')->with('error', 'Invalid reconciliation ID.');
        }
    }

    public function update(Request $request, $routeId)
    {
        try {
            $reconciliation = $this->findReconciliation($routeId);

            if (! $reconciliation) {
                return redirect()->route('cash-reconciliations.index')->with('error', 'Cash reconciliation not found.');
            }

            if ($reconciliation->status === 'approved') {
                return redirect()->route('cash-reconciliations.show', $reconciliation->id)
                    ->with('error', 'Approved reconciliation is locked. Reverse approval before editing.');
            }

            $validated = $request->validate([
                'business_date' => 'required|date',
                'opening_float' => 'required|numeric|min:0',
                'physical_counted_cash' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
            ]);

            $duplicateExists = CashReconciliation::where('business_date', $validated['business_date'])
                ->where('user_id', $reconciliation->user_id)
                ->where('id', '!=', $reconciliation->id)
                ->exists();

            if ($duplicateExists) {
                return redirect()->back()->withInput()->with('error', 'Another reconciliation already exists for this cashier and date.');
            }

            $expected = $this->computeExpectedForUser((int) $reconciliation->user_id, $validated['business_date'], (float) $validated['opening_float']);
            $variance = round((float) $validated['physical_counted_cash'] - $expected['expected_closing_cash'], 2);

            $varianceType = 'balanced';
            if ($variance > 0) {
                $varianceType = 'overage';
            } elseif ($variance < 0) {
                $varianceType = 'shortage';
            }

            $reconciliation->update([
                'business_date' => $validated['business_date'],
                'opening_float' => (float) $validated['opening_float'],
                'expected_cash_sales' => $expected['expected_cash_sales'],
                'expected_cash_expenses' => $expected['expected_cash_expenses'],
                'expected_closing_cash' => $expected['expected_closing_cash'],
                'physical_counted_cash' => (float) $validated['physical_counted_cash'],
                'variance' => $variance,
                'variance_type' => $varianceType,
                'status' => 'submitted',
                'notes' => $validated['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('cash-reconciliations.index')->with('success', 'Cash reconciliation updated and resubmitted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('cash-reconciliations.index')->with('error', 'Invalid reconciliation ID.');
        }
    }

    public function approve(Request $request, $routeId)
    {
        try {
            $reconciliation = $this->findReconciliation($routeId);

            if (! $reconciliation) {
                return redirect()->route('cash-reconciliations.index')->with('error', 'Cash reconciliation not found.');
            }

            if ($reconciliation->status === 'approved') {
                return redirect()->back()->with('error', 'Reconciliation is already approved.');
            }

            $reconciliation->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('cash-reconciliations.show', $reconciliation->id)
                ->with('success', 'Reconciliation approved and locked successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('cash-reconciliations.index')->with('error', 'Invalid reconciliation ID.');
        }
    }

    public function reverseApproval(Request $request, $routeId)
    {
        try {
            $reconciliation = $this->findReconciliation($routeId);

            if (! $reconciliation) {
                return redirect()->route('cash-reconciliations.index')->with('error', 'Cash reconciliation not found.');
            }

            if ($reconciliation->status !== 'approved') {
                return redirect()->back()->with('error', 'Only approved reconciliations can be reversed.');
            }

            $validated = $request->validate([
                'reversal_reason' => 'required|string|max:1000',
            ]);

            $reconciliation->update([
                'status' => 'reopened',
                'reversed_by' => Auth::id(),
                'reversed_at' => now(),
                'reversal_reason' => $validated['reversal_reason'],
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('cash-reconciliations.show', $reconciliation->id)
                ->with('success', 'Approval reversed. Cashier can now edit and resubmit.');
        } catch (\Throwable $e) {
            return redirect()->route('cash-reconciliations.index')->with('error', 'Invalid reconciliation ID.');
        }
    }

    private function findReconciliation($routeId, array $relations = [])
    {
        $query = CashReconciliation::query();

        if (! empty($relations)) {
            $query->with($relations);
        }

        if (is_numeric($routeId)) {
            $reconciliation = $query->find((int) $routeId);

            if ($reconciliation) {
                return $reconciliation;
            }
        }

        try {
            $decryptedId = Crypt::decryptString((string) $routeId);

            if (is_numeric($decryptedId)) {
                return $query->find((int) $decryptedId);
            }
        } catch (\Throwable $e) {
            // Ignore and return null below.
        }

        return null;
    }

    private function computeExpectedForUser(int $userId, string $businessDate, float $openingFloat): array
    {
        $expectedCashSales = (float) Payment::query()
            ->join('sales', 'sales.id', '=', 'payments.sale_id')
            ->where('sales.created_by', $userId)
            ->whereDate('sales.date', $businessDate)
            ->whereNull('sales.cancelled_at')
            ->sum('payments.paid_amount');

        $expectedCashExpenses = (float) ExpenseVoucherItem::query()
            ->whereHas('voucher', function ($q) use ($userId, $businessDate) {
                $q->where('created_by', $userId)
                    ->whereDate('voucher_date', $businessDate);
            })
            ->sum('amount');

        $expectedClosingCash = round($openingFloat + $expectedCashSales - $expectedCashExpenses, 2);

        return [
            'expected_cash_sales' => round($expectedCashSales, 2),
            'expected_cash_expenses' => round($expectedCashExpenses, 2),
            'expected_closing_cash' => $expectedClosingCash,
        ];
    }
}

