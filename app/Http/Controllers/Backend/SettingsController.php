<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:settings-manage', ['only' => ['index', 'update']]);
    }

    public function index()
    {
        $allowedReconciliationStartTimes = $this->allowedReconciliationStartTimes();

        return view('backend.settings.index', [
            'sessionLifetimeMinutes' => $this->getIntSetting('session_lifetime_minutes', (int) config('session.lifetime', 120)),
            'sessionExpireOnClose' => $this->getBoolSetting('session_expire_on_close', (bool) config('session.expire_on_close', false)),
            'enforcePreviousDayReconciliation' => $this->getBoolSetting(
                'enforce_previous_day_reconciliation',
                (bool) config('pos.enforce_previous_day_reconciliation', false)
            ),
            'enforceReconciliationTiming' => $this->getBoolSetting(
                'enforce_reconciliation_timing',
                (bool) config('pos.enforce_reconciliation_timing', false)
            ),
            'reconciliationStartTime' => $this->getStringSetting(
                'reconciliation_start_time',
                (string) config('pos.reconciliation_start_time', '15:00')
            ),
            'allowedReconciliationStartTimes' => $allowedReconciliationStartTimes,
            'allowNegativeStock' => $this->getBoolSetting(
                'allow_negative_stock',
                (bool) config('pos.allow_negative_stock', false)
            ),
            'preventBackdatedInvoices' => $this->getBoolSetting(
                'prevent_backdated_invoices',
                (bool) config('pos.prevent_backdated_invoices', false)
            ),
            'allowManualDiscountEntryByStaff' => $this->getBoolSetting(
                'allow_manual_discount_entry_by_staff',
                (bool) config('pos.allow_manual_discount_entry_by_staff', true)
            ),
            'requirePasswordChangeOnFirstLogin' => $this->getBoolSetting(
                'require_password_change_on_first_login',
                (bool) config('pos.require_password_change_on_first_login', false)
            ),
            'enableDailyEmailReport' => $this->getBoolSetting(
                'enable_daily_email_report',
                (bool) config('pos.enable_daily_email_report', false)
            ),
            'dailyEmailReportTime' => $this->getIntSetting(
                'daily_email_report_time',
                (int) config('pos.daily_email_report_time', 18)
            ),
            'allowPurchaseEdit' => $this->getBoolSetting(
                'allow_purchase_edit',
                (bool) config('pos.allow_purchase_edit', true)
            ),
            'purchaseEditWindowHours' => $this->getIntSetting(
                'purchase_edit_window_hours',
                (int) config('pos.purchase_edit_window_hours', 24)
            ),
        ]);
    }

    public function update(Request $request)
    {
        $allowedReconciliationStartTimes = $this->allowedReconciliationStartTimes();

        $validated = $request->validate([
            'session_lifetime_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'session_expire_on_close' => ['nullable', 'boolean'],
            'enforce_previous_day_reconciliation' => ['nullable', 'boolean'],
            'enforce_reconciliation_timing' => ['nullable', 'boolean'],
            'reconciliation_start_time' => [
                'required_if:enforce_reconciliation_timing,true',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($allowedReconciliationStartTimes) {
                    if ($value === null || $value === '') {
                        return;
                    }

                    if (! in_array((string) $value, $allowedReconciliationStartTimes, true)) {
                        $fail('Reconciliation start time must be between 3:00 PM and 11:30 PM in 30-minute steps.');
                    }
                },
            ],
            'allow_negative_stock' => ['nullable', 'boolean'],
            'prevent_backdated_invoices' => ['nullable', 'boolean'],
            'allow_manual_discount_entry_by_staff' => ['nullable', 'boolean'],
            'require_password_change_on_first_login' => ['nullable', 'boolean'],
            'enable_daily_email_report' => ['nullable', 'boolean'],
            'daily_email_report_time' => ['required_if:enable_daily_email_report,true', 'integer', 'min:18', 'max:23'],
            'allow_purchase_edit' => ['nullable', 'boolean'],
            'purchase_edit_window_hours' => ['required_if:allow_purchase_edit,true', 'integer', 'in:24,48,72,96,120,144,168'],
        ]);

        $values = [
            'session_lifetime_minutes' => (string) $validated['session_lifetime_minutes'],
            'session_expire_on_close' => $request->boolean('session_expire_on_close') ? '1' : '0',
            'enforce_previous_day_reconciliation' => $request->boolean('enforce_previous_day_reconciliation') ? '1' : '0',
            'enforce_reconciliation_timing' => $request->boolean('enforce_reconciliation_timing') ? '1' : '0',
            'reconciliation_start_time' => (string) ($validated['reconciliation_start_time'] ?? config('pos.reconciliation_start_time', '15:00')),
            'allow_negative_stock' => $request->boolean('allow_negative_stock') ? '1' : '0',
            'prevent_backdated_invoices' => $request->boolean('prevent_backdated_invoices') ? '1' : '0',
            'allow_manual_discount_entry_by_staff' => $request->boolean('allow_manual_discount_entry_by_staff') ? '1' : '0',
            'require_password_change_on_first_login' => $request->boolean('require_password_change_on_first_login') ? '1' : '0',
            'enable_daily_email_report' => $request->boolean('enable_daily_email_report') ? '1' : '0',
            'daily_email_report_time' => (string) ($validated['daily_email_report_time'] ?? 18),
            'allow_purchase_edit' => $request->boolean('allow_purchase_edit') ? '1' : '0',
            'purchase_edit_window_hours' => (string) ($validated['purchase_edit_window_hours'] ?? 24),
        ];

        DB::transaction(function () use ($values) {
            foreach ($values as $key => $value) {
                AppSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });

        Cache::forget('runtime_app_settings');

        return redirect()->route('settings.index')->with([
            'message' => 'Settings updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    private function getIntSetting(string $key, int $default): int
    {
        $raw = AppSetting::getValue($key);
        if ($raw === null || !is_numeric($raw)) {
            return $default;
        }

        return (int) $raw;
    }

    private function getBoolSetting(string $key, bool $default): bool
    {
        $raw = AppSetting::getValue($key);
        if ($raw === null) {
            return $default;
        }

        return filter_var($raw, FILTER_VALIDATE_BOOLEAN);
    }

    private function getStringSetting(string $key, string $default): string
    {
        $raw = AppSetting::getValue($key);
        if ($raw === null || $raw === '') {
            return $default;
        }

        return (string) $raw;
    }

    private function allowedReconciliationStartTimes(): array
    {
        $times = [];

        for ($hour = 15; $hour <= 23; $hour++) {
            $times[] = sprintf('%02d:00', $hour);
            $times[] = sprintf('%02d:30', $hour);
        }

        return $times;
    }
}
