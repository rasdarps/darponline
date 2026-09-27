@extends('layout.main')
@section('title') {{ 'Receipt Print Audit' }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Receipt Print Audit</h4>
                </div>
            </div>
        </div>

        <div class="card d-print-none mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Invoice No</label>
                        <select id="filter_sale_id" class="form-control select2">
                            <option value="">All Sales</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}">{{ $sale->sale_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Operator</label>
                        <select id="filter_operator_id" class="form-control select2">
                            <option value="">All Operators</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->username ?? $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Print Event</label>
                        <select id="filter_event_type" class="form-control">
                            <option value="">All Types</option>
                            <option value="original_print">Original Print</option>
                            <option value="reprint">Reprint</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Delivery Status</label>
                        <select id="filter_delivery_status" class="form-control">
                            <option value="">All</option>
                            <option value="delivered">Delivered</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Printed From</label>
                        <input type="date" id="filter_from_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Printed To</label>
                        <input type="date" id="filter_to_date" class="form-control">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="button" id="apply_filters" class="btn btn-primary">Filter</button>
                        <button type="button" id="reset_filters" class="btn btn-dark">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Receipt Print Events</h5>
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
    <script>
        $(function() {
            const table = $('#sale-receipt-audit-table').DataTable();

            $('#filter_sale_id, #filter_operator_id, #filter_event_type, #filter_delivery_status, #filter_from_date, #filter_to_date').on('change', function() {
                table.ajax.reload();
            });

            $('#apply_filters').on('click', function() {
                table.ajax.reload();
            });

            $('#reset_filters').on('click', function() {
                $('#filter_sale_id').val(null).trigger('change');
                $('#filter_operator_id').val(null).trigger('change');
                $('#filter_event_type').val('');
                $('#filter_delivery_status').val('');
                $('#filter_from_date').val('');
                $('#filter_to_date').val('');
                table.ajax.reload();
            });

            table.on('preXhr.dt', function(e, settings, data) {
                data.sale_id = $('#filter_sale_id').val();
                data.operator_id = $('#filter_operator_id').val();
                data.event_type = $('#filter_event_type').val();
                data.delivery_status = $('#filter_delivery_status').val();
                data.from_date = $('#filter_from_date').val();
                data.to_date = $('#filter_to_date').val();
            });
        });
    </script>
@endsection