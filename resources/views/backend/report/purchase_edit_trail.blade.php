@extends('layout.main')

@section('title', 'Purchase Edit Trail')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Purchase Edit Trail</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Purchase Edit Trail</h4>
                        <p class="card-title-desc">Review detailed purchase edits with server-side filters and line-level change details.</p>

                        <form method="GET" action="{{ route('reports.purchase-edit-trail') }}" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Purchase</label>
                                <select name="purchase_id" class="form-control select2" data-placeholder="Select purchase">
                                    <option value="">All Purchases</option>
                                    @foreach($purchases as $purchase)
                                        <option value="{{ $purchase->id }}" @selected(request('purchase_id') == $purchase->id)>
                                            {{ $purchase->purchase_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Edited By</label>
                                <select name="edited_by" class="form-control select2" data-placeholder="Select user">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(request('edited_by') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-grid gap-2 w-100">
                                    <button type="submit" class="btn btn-dark">Filter</button>
                                    <a href="{{ route('reports.purchase-edit-trail') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive w-100">
                            {!! $dataTable->table(['class' => 'table table-bordered table-striped mb-0'], true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
    <script>
        $(function () {
            $('.select2').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select an option'
            });
        });
    </script>
@endsection
