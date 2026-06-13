@extends('layout.main')
@section('title') {{'View Purchases'}} @endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">All Purchases</h4>  
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ route('purchases.create') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fas fa-plus-circle"></i> Add Purchase
                        </a> 
                        <br><br>               

                        <h4 class="card-title">Purchases Data</h4>

                        <!-- 🔎 Filter boxes -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Purchase Number</label>
                                <select id="filter_purchase_id" class="form-control select2">
                                    <option value="">All Purchases</option>
                                    @foreach($purchase_invoices as $purchase_invoice)
                                        <option value="{{ $purchase_invoice->id }}">{{ $purchase_invoice->purchase_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Supplier Name</label>
                                <select id="filter_supplier_id" class="form-control">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>From Date</label>
                                <input type="date" id="filter_from_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>To Date</label>
                                <input type="date" id="filter_to_date" class="form-control">
                            </div>
                        </div>

                        <!-- Reset Filters Button -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button id="reset_filters" class="btn btn-secondary">Reset Filters</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
</div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
    <script>
        $(function() {
            let table = $('#allpurchases-table').DataTable();

            // Reload table when filters change
            $('#filter_purchase_id, #filter_supplier_id, #filter_from_date, #filter_to_date').on('change', function() {
                table.ajax.reload();
            });

            // Reset filters
            $('#reset_filters').on('click', function() {
                $('#filter_purchase_id').val('');
                $('#filter_supplier_id').val('');
                $('#filter_from_date').val('');
                $('#filter_to_date').val('');
                table.ajax.reload();
            });

            // Pass filter values into AJAX
            table.on('preXhr.dt', function(e, settings, data) {
                data.purchase_id = $('#filter_purchase_id').val();
                data.supplier_id = $('#filter_supplier_id').val();
                data.from_date = $('#filter_from_date').val();
                data.to_date = $('#filter_to_date').val();
            });

            // ✅ Footer totals calculation for Amount column (index 3)
            table.on('draw.dt', function() {
                let intVal = i => typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : (typeof i === 'number' ? i : 0);

                let totalAmount = table.column(3, {page:'current'}).data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                $(table.column(3).footer()).html('Gh¢ ' + totalAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            });

            // Handle DataTables errors gracefully
            $.fn.dataTable.ext.errMode = 'none';
            $('#allpurchases-table').on('error.dt', function(e, settings, techNote, message) {
                Notiflix.Notify.failure("DataTables error: " + message);
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        /* Ensure main content is not hidden under sidebar */
        /* Remove custom margin, let theme handle sidebar spacing */
        
        .select2 {
            width: 100% !important;
        }
    </style>

@stack('scripts')
