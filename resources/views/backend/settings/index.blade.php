@extends('layout.main')
@section('title') {{ 'Settings' }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header with-border">
                        <span class="card-title" style="font-size:20px;">System Settings</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Toggles - 2 Columns per Row -->
                            <div class="row">
                                <!-- Session Settings -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">

                                        <label for="session_lifetime_minutes"><strong>Session Lifetime (minutes)</strong></label>
                                        <input
                                            type="number"
                                            id="session_lifetime_minutes"
                                            name="session_lifetime_minutes"
                                            min="1"
                                            max="1440"
                                            class="form-control"
                                            value="{{ old('session_lifetime_minutes', $sessionLifetimeMinutes) }}"
                                            required
                                        >
                                        <small class="text-muted d-block mb-3">Users are logged out after this many minutes of inactivity.</small>

                                        <hr class="my-2">

                                        <input type="hidden" name="session_expire_on_close" value="0">
                                        <div class="custom-control custom-switch mb-2">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="session_expire_on_close"
                                                name="session_expire_on_close"
                                                value="1"
                                                {{ old('session_expire_on_close', $sessionExpireOnClose) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="session_expire_on_close">
                                                <strong>Expire Session When Browser Closes</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When enabled, login ends when the browser is closed.</small>

                                    </div>
                                </div>

                                <!-- Reconciliation Settings -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">

                                        <input type="hidden" name="enforce_previous_day_reconciliation" value="0">
                                        <div class="custom-control custom-switch mb-2">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="enforce_previous_day_reconciliation"
                                                name="enforce_previous_day_reconciliation"
                                                value="1"
                                                {{ old('enforce_previous_day_reconciliation', $enforcePreviousDayReconciliation) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="enforce_previous_day_reconciliation">
                                                <strong>Require Previous Pending Reconciliation Before Today's Sales</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mb-3">When enabled, cashier must clear all previous pending reconciliation dates before creating sales for today.</small>

                                        <hr class="my-2">

                                        <input type="hidden" name="enforce_reconciliation_timing" value="0">
                                        <div class="custom-control custom-switch mb-2">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="enforce_reconciliation_timing"
                                                name="enforce_reconciliation_timing"
                                                value="1"
                                                onchange="toggleReconciliationTimeField()"
                                                {{ old('enforce_reconciliation_timing', $enforceReconciliationTiming) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="enforce_reconciliation_timing">
                                                <strong>Enable Reconciliation Start Time Rule</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mb-3">When enabled, cashier can only start reconciliation after configured time.</small>

                                        <label for="reconciliation_start_time"><strong>Reconciliation Start Time</strong></label>
                                        <select
                                            id="reconciliation_start_time"
                                            name="reconciliation_start_time"
                                            class="form-control"
                                            {{ old('enforce_reconciliation_timing', $enforceReconciliationTiming) ? '' : 'disabled' }}
                                        >
                                            @foreach($allowedReconciliationStartTimes as $timeValue)
                                                @php($displayTime = \Illuminate\Support\Carbon::createFromFormat('H:i', $timeValue)->format('g:i A'))
                                                <option value="{{ $timeValue }}" {{ old('reconciliation_start_time', $reconciliationStartTime) === $timeValue ? 'selected' : '' }}>
                                                    {{ $displayTime }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-2">Allowed range: 3:00 PM to 11:30 PM (30-minute steps).</small>

                                    </div>
                                </div>

                                <!-- Allow Negative Stock -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="allow_negative_stock" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="allow_negative_stock"
                                                name="allow_negative_stock"
                                                value="1"
                                                {{ old('allow_negative_stock', $allowNegativeStock) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="allow_negative_stock">
                                                <strong>Allow Negative Stock (Overselling)</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When enabled, sales can continue even when available stock is not enough.</small>
                                    </div>
                                </div>

                                <!-- Prevent Backdated Invoices -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="prevent_backdated_invoices" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="prevent_backdated_invoices"
                                                name="prevent_backdated_invoices"
                                                value="1"
                                                {{ old('prevent_backdated_invoices', $preventBackdatedInvoices) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="prevent_backdated_invoices">
                                                <strong>Prevent Backdated Invoices</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When enabled, users can only create invoices with today\'s date.</small>
                                    </div>
                                </div>

                                <!-- Allow Manual Discount Entry by Staff -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="allow_manual_discount_entry_by_staff" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="allow_manual_discount_entry_by_staff"
                                                name="allow_manual_discount_entry_by_staff"
                                                value="1"
                                                {{ old('allow_manual_discount_entry_by_staff', $allowManualDiscountEntryByStaff) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="allow_manual_discount_entry_by_staff">
                                                <strong>Allow Manual Discount Entry by Staff</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When disabled, discount input is locked and treated as 0 during checkout.</small>
                                    </div>
                                </div>

                                <!-- Require Password Change on First Login -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="require_password_change_on_first_login" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="require_password_change_on_first_login"
                                                name="require_password_change_on_first_login"
                                                value="1"
                                                {{ old('require_password_change_on_first_login', $requirePasswordChangeOnFirstLogin) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="require_password_change_on_first_login">
                                                <strong>Require Password Change on First Login</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When enabled, users flagged for first login must change password before using the system.</small>
                                    </div>
                                </div>

                            </div>

                            <!-- Daily Email Report Settings -->
                            <div class="row mt-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="enable_daily_email_report" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="enable_daily_email_report"
                                                name="enable_daily_email_report"
                                                value="1"
                                                onchange="toggleEmailTimeField()"
                                                {{ old('enable_daily_email_report', $enableDailyEmailReport) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="enable_daily_email_report">
                                                <strong>Enable Daily Transaction Email Report</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">When enabled, daily sales, expenses, and cash shortage summary is sent automatically or manually.</small>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <label for="daily_email_report_time"><strong>Daily Email Report Send Time</strong></label>
                                        <select
                                            id="daily_email_report_time"
                                            name="daily_email_report_time"
                                            class="form-control"
                                            {{ old('enable_daily_email_report', $enableDailyEmailReport) ? '' : 'disabled' }}
                                        >
                                            @for ($hour = 18; $hour <= 23; $hour++)
                                                <option value="{{ $hour }}" {{ old('daily_email_report_time', $dailyEmailReportTime) == $hour ? 'selected' : '' }}>
                                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00 ({{ $hour == 18 ? '6 PM' : ($hour == 23 ? '11 PM' : ($hour - 12) . ' PM') }})
                                                </option>
                                            @endfor
                                        </select>
                                        <small class="text-muted d-block mt-2">Select the time between 6 PM and 12 AM for automatic email sending.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchase Edit Settings -->
                            <div class="row mt-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group border rounded p-3 h-100">
                                        <input type="hidden" name="allow_purchase_edit" value="0">
                                        <div class="custom-control custom-switch mb-2">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="allow_purchase_edit"
                                                name="allow_purchase_edit"
                                                value="1"
                                                onchange="togglePurchaseEditField()"
                                                {{ old('allow_purchase_edit', $allowPurchaseEdit) ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="allow_purchase_edit">
                                                <strong>Allow Purchase Invoice Editing</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mb-3">When enabled, purchase invoices can be edited within the configured time window.</small>

                                        <label for="purchase_edit_window_hours"><strong>Edit Window</strong></label>
                                        <select
                                            id="purchase_edit_window_hours"
                                            name="purchase_edit_window_hours"
                                            class="form-control"
                                            {{ old('allow_purchase_edit', $allowPurchaseEdit) ? '' : 'disabled' }}
                                        >
                                            @foreach([24 => '24 hours (1 day)', 48 => '48 hours (2 days)', 72 => '72 hours (3 days)', 96 => '96 hours (4 days)', 120 => '120 hours (5 days)', 144 => '144 hours (6 days)', 168 => '168 hours (7 days)'] as $hours => $label)
                                                <option value="{{ $hours }}" {{ old('purchase_edit_window_hours', $purchaseEditWindowHours) == $hours ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-2">Purchases older than this window cannot be edited.</small>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Save Settings</button>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEmailTimeField() {
    const checkbox = document.getElementById('enable_daily_email_report');
    const timeSelect = document.getElementById('daily_email_report_time');

    if (!checkbox || !timeSelect) return;

    timeSelect.disabled = !checkbox.checked;
}

function toggleReconciliationTimeField() {
    const checkbox = document.getElementById('enforce_reconciliation_timing');
    const timeSelect = document.getElementById('reconciliation_start_time');

    if (!checkbox || !timeSelect) {
        return;
    }

    timeSelect.disabled = !checkbox.checked;
}

function togglePurchaseEditField() {
    const checkbox  = document.getElementById('allow_purchase_edit');
    const select    = document.getElementById('purchase_edit_window_hours');
    if (!checkbox || !select) return;
    select.disabled = !checkbox.checked;
}

document.addEventListener('DOMContentLoaded', function () {
    toggleEmailTimeField();
    toggleReconciliationTimeField();
    togglePurchaseEditField();
});
</script>
@endsection
