@extends('layout.main')
@section('title') {{ 'Create Cash Reconciliation' }} @endsection

@section('content')
<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Daily Cash Reconciliation</h4>
                    <a href="{{ route('cash-reconciliations.index') }}" class="btn btn-dark btn-sm">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('reconciliation_gate'))
                            @php($gate = session('reconciliation_gate'))
                            <div class="alert alert-warning border-warning" role="alert">
                                <h5 class="alert-heading mb-2"><i class="fas fa-exclamation-triangle me-1"></i> Pending Cash Reconciliation Required</h5>
                                <p class="mb-2">{{ $gate['message'] ?? session('error') }}</p>

                                @if(!empty($gate['missing_dates']))
                                    <p class="mb-1"><strong>Missing Reconciliation Date(s):</strong></p>
                                    <ul class="mb-2">
                                        @foreach($gate['missing_dates'] as $date)
                                            <li>{{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if(!empty($gate['unapproved_dates']))
                                    <p class="mb-1"><strong>Submitted But Not Approved Date(s):</strong></p>
                                    <ul class="mb-2">
                                        @foreach($gate['unapproved_dates'] as $date)
                                            <li>{{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <hr>
                                <p class="mb-0">{{ $gate['action'] ?? session('info') }}</p>
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @php($timeGate = $timingGate ?? session('reconciliation_time_gate'))
                        @if($timeGate)
                            <div class="alert alert-warning border-warning" role="alert">
                                <h5 class="alert-heading mb-2"><i class="fas fa-clock me-1"></i> Reconciliation Time Gate</h5>
                                <p class="mb-2">{{ $timeGate['message'] ?? session('error') }}</p>
                                <p class="mb-0">{{ $timeGate['action'] ?? session('info') }}</p>
                            </div>
                        @endif

                        @if(session('info') && !session('reconciliation_gate'))
                            <div class="alert alert-info">{{ session('info') }}</div>
                        @endif

                        @if(!empty($businessDateStatuses))
                            <div class="card border mb-4">
                                <div class="card-body py-3">
                                    <h5 class="card-title mb-3">Business-Date Status Tracking</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Business Date</th>
                                                    <th>Status</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($businessDateStatuses as $dateStatus)
                                                    <tr>
                                                        <td>{{ $dateStatus['business_date_label'] }}</td>
                                                        <td><span class="{{ $dateStatus['badge_class'] }}">{{ $dateStatus['status_label'] }}</span></td>
                                                        <td>{{ $dateStatus['status_note'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Open: can reconcile. Reconciled: approved. Pending Approval: waiting for Manager/Supervisor. Blocked: prior sales date with no submitted reconciliation.
                                    </small>
                                </div>
                            </div>
                        @endif

                        <h4 class="card-title">Enter Physical Cash For The Day</h4>

                        @if(!$timeGate)
                        <form action="{{ route('cash-reconciliations.store') }}" method="POST" id="cash-reconciliation-create-form">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Business Date</label>
                                    <input type="date" id="business_date" name="business_date" class="form-control" value="{{ old('business_date', $businessDate) }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Opening Float</label>
                                    <input type="number" step="0.01" min="0" id="opening_float" name="opening_float" class="form-control" value="{{ old('opening_float', number_format((float) $openingFloat, 2, '.', '')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Physical Counted Cash</label>
                                    <input type="number" step="0.01" min="0" id="physical_counted_cash" name="physical_counted_cash" class="form-control" value="{{ old('physical_counted_cash') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Expected Cash Sales (POS only)</label>
                                    <input type="text" id="expected_cash_sales" class="form-control" value="{{ number_format((float) $expected['expected_cash_sales'], 2, '.', '') }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Expected Cash Expenses</label>
                                    <input type="text" id="expected_cash_expenses" class="form-control" value="{{ number_format((float) $expected['expected_cash_expenses'], 2, '.', '') }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Expected Closing Cash</label>
                                    <input type="text" id="expected_closing_cash" class="form-control" value="{{ number_format((float) $expected['expected_closing_cash'], 2, '.', '') }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Variance</label>
                                    <input type="text" id="variance" class="form-control" value="0.00" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Variance Type</label>
                                    <input type="text" id="variance_type" class="form-control" value="Balanced" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Notes / Explanation</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Optional note for shortage/overage...">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit Reconciliation</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    const expectedUrl = "{{ route('cash-reconciliations.expected') }}";

    function notifyFailure(message) {
        if (window.Notiflix && Notiflix.Notify) {
            Notiflix.Notify.failure(message);
        } else {
            alert(message);
        }
    }

    function toNumber(value) {
        const n = parseFloat(value);
        return Number.isNaN(n) ? 0 : n;
    }

    function formatMoney(value) {
        return Number(value || 0).toFixed(2);
    }

    function refreshVariance() {
        const expected = toNumber($('#expected_closing_cash').val());
        const physical = toNumber($('#physical_counted_cash').val());
        const variance = physical - expected;

        $('#variance').val(formatMoney(variance));

        if (variance > 0) {
            $('#variance_type').val('Overage');
        } else if (variance < 0) {
            $('#variance_type').val('Shortage');
        } else {
            $('#variance_type').val('Balanced');
        }
    }

    function loadExpected() {
        $.get(expectedUrl, {
            business_date: $('#business_date').val(),
            opening_float: $('#opening_float').val()
        }).done(function (res) {
            $('#expected_cash_sales').val(formatMoney(res.expected_cash_sales));
            $('#expected_cash_expenses').val(formatMoney(res.expected_cash_expenses));
            $('#expected_closing_cash').val(formatMoney(res.expected_closing_cash));
            refreshVariance();
        });
    }

    $('#business_date, #opening_float').on('change input', function () {
        loadExpected();
    });

    $('#physical_counted_cash').on('change input', function () {
        refreshVariance();
    });

    $('#cash-reconciliation-create-form').on('submit', function (event) {
        const businessDate = ($('#business_date').val() || '').trim();
        const openingFloat = ($('#opening_float').val() || '').trim();
        const physicalCountedCash = ($('#physical_counted_cash').val() || '').trim();

        if (!businessDate) {
            event.preventDefault();
            notifyFailure('Please select the business date.');
            $('#business_date').focus();
            return;
        }

        if (!openingFloat || Number(openingFloat) < 0) {
            event.preventDefault();
            notifyFailure('Please enter a valid opening float.');
            $('#opening_float').focus();
            return;
        }

        if (!physicalCountedCash || Number(physicalCountedCash) < 0) {
            event.preventDefault();
            notifyFailure('Please enter a valid physical counted cash value.');
            $('#physical_counted_cash').focus();
        }
    });

    refreshVariance();
});
</script>
@endsection
