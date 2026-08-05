@extends('layout.main')
@section('title') {{ 'Edit Purchase #' . $purchase->purchase_no }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Purchase #{{ $purchase->purchase_no }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">All Purchases</a></li>
                            <li class="breadcrumb-item active">Edit #{{ $purchase->purchase_no }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($purchase->edit_reason)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Last edited:</strong>
                {{ $purchase->updated_at ? $purchase->updated_at->format('d-m-Y H:i') : '' }}
                @if($purchase->updatedBy)
                    by <strong>{{ $purchase->updatedBy->name }}</strong>
                @endif
                — <em>{{ $purchase->edit_reason }}</em>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title" style="font-size:18px;">
                            Purchase #{{ $purchase->purchase_no }}
                            <small class="text-muted ms-2">Date: {{ $purchase->date->format('d-m-Y') }}</small>
                        </span>
                    </div>
                    <div class="card-body">

                        <div class="alert alert-warning py-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Note:</strong> You can edit <strong>quantity</strong> and <strong>unit cost price</strong> per line.
                            The <em>Already Sold (Min)</em> column shows the minimum quantity you must keep.
                            Setting a quantity to <strong>0</strong> removes that item from the purchase.
                            If this is the latest purchase for a product, updating its cost price will also update the product's current cost price.
                        </div>

                        <form action="{{ route('purchases.update', $encryptedId) }}" method="POST" novalidate>
                            @csrf
                            @method('PATCH')

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th class="text-center" style="width:150px;">Unit Cost Price</th>
                                            <th class="text-center">Original Qty</th>
                                            <th class="text-center">Already Sold (Min)</th>
                                            <th class="text-center" style="width:150px;">New Qty</th>
                                            <th class="text-end">New Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchase->purchase_details as $index => $detail)
                                            @php
                                                $currentStock = $detail->product ? (float) $detail->product->quantity : 0;
                                                $rawMin       = (float) $detail->buying_qty - $currentStock;
                                                $minQty       = $rawMin > 0 ? (int) ceil($rawMin) : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $detail->product->name ?? 'Unknown Product' }}</strong></td>
                                                <td>{{ $detail->unit->name ?? '—' }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="unit_cost_price[{{ $detail->id }}]"
                                                        class="form-control cost-input @error('unit_cost_price.' . $detail->id) is-invalid @enderror"
                                                        value="{{ old('unit_cost_price.' . $detail->id, $detail->unit_cost_price) }}"
                                                        min="0.01"
                                                        step="0.01"
                                                    >
                                                    @error('unit_cost_price.' . $detail->id)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td class="text-center">{{ $detail->buying_qty }}</td>
                                                <td class="text-center">
                                                    @if($minQty > 0)
                                                        <span class="badge bg-warning text-dark" title="This many units have been sold">{{ $minQty }}</span>
                                                    @else
                                                        <span class="badge bg-success">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="buying_qty[{{ $detail->id }}]"
                                                        class="form-control qty-input @error('buying_qty.' . $detail->id) is-invalid @enderror"
                                                        value="{{ old('buying_qty.' . $detail->id, $detail->buying_qty) }}"
                                                        min="{{ $minQty }}"
                                                        step="1"
                                                        data-old="{{ $detail->buying_qty }}"
                                                        data-product="{{ $detail->product->name ?? 'Item' }}"
                                                    >
                                                    @error('buying_qty.' . $detail->id)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="text-warning small mt-1 zero-warning" style="display:none;">
                                                        <i class="fas fa-exclamation-triangle"></i> Setting to 0 removes this item.
                                                    </div>
                                                </td>
                                                <td class="text-end row-total">
                                                    {{ number_format($detail->buying_qty * $detail->unit_cost_price, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-4">
                                <label for="edit_reason" class="form-label">
                                    <strong>Reason for Edit <span class="text-danger">*</span></strong>
                                </label>
                                <textarea
                                    id="edit_reason"
                                    name="edit_reason"
                                    class="form-control @error('edit_reason') is-invalid @enderror"
                                    rows="2"
                                    maxlength="500"
                                    placeholder="Enter reason for editing this purchase..."
                                >{{ old('edit_reason') }}</textarea>
                                @error('edit_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>

                                @can('purchase-cancel')
                                    @if(!$purchase->cancelled_at)
                                        <button type="button" class="btn btn-danger ms-auto"
                                            data-bs-toggle="modal" data-bs-target="#cancelFromEditModal">
                                            <i class="fas fa-ban me-1"></i> Cancel This Purchase
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@can('purchase-cancel')
    @if(!$purchase->cancelled_at)
        <div class="modal fade" id="cancelFromEditModal" tabindex="-1" aria-labelledby="cancelFromEditModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelFromEditModalLabel">
                            Cancel Purchase <span class="text-danger">#{{ $purchase->purchase_no }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger py-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Warning:</strong> This will reverse all stock added by this purchase and zero its financial values.
                        </div>
                        <div class="mb-3">
                            <label for="cancel_reason_edit" class="form-label">
                                Reason for Cancellation <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="cancel_reason_edit" rows="3"
                                placeholder="Enter reason for cancellation..."></textarea>
                            <div id="cancel_reason_error" class="text-danger small mt-1" style="display:none;">
                                Please provide a reason for cancellation.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="confirmCancelFromEdit">
                            <i class="fas fa-ban me-1"></i> Confirm Cancellation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endcan
@endsection

@section('scripts')
<script src="{{ asset('notiflix/notiflix-aio-3.2.8.min.js') }}"></script>
<script>
    // Live row total update + zero warning
    function recalcRow(row) {
        const qty  = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const total = (qty * cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        row.querySelector('.row-total').textContent = total;
        row.querySelector('.zero-warning').style.display = qty === 0 ? '' : 'none';
    }

    document.querySelectorAll('.qty-input, .cost-input').forEach(function (input) {
        input.addEventListener('input', function () {
            recalcRow(this.closest('tr'));
        });
    });

    @can('purchase-cancel')
    @if(!$purchase->cancelled_at)
    document.getElementById('confirmCancelFromEdit')?.addEventListener('click', function () {
        const reason = document.getElementById('cancel_reason_edit').value.trim();
        if (!reason) {
            document.getElementById('cancel_reason_error').style.display = '';
            return;
        }
        document.getElementById('cancel_reason_error').style.display = 'none';

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...';

        fetch('{{ route("purchases.cancel", Crypt::encrypt($purchase->id)) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: '_token={{ csrf_token() }}&reason=' + encodeURIComponent(reason),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 200) {
                Notiflix.Notify.success(data.message, { timeout: 3000 });
                setTimeout(() => window.location.href = '{{ route("purchases.index") }}', 1500);
            } else {
                Notiflix.Notify.failure(data.message || 'Failed to cancel purchase.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-ban me-1"></i> Confirm Cancellation';
            }
        })
        .catch(() => {
            Notiflix.Notify.failure('An error occurred. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-ban me-1"></i> Confirm Cancellation';
        });
    });
    @endif
    @endcan
</script>
@endsection
