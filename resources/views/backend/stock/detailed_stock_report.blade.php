@extends('layout.main')
@section('title') {{'View Detailed Stock Report'}} @endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Detail Stock Report</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Detailed Stock Report</h4>

                        <!-- 🔎 Filter boxes -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Product</label>
                                <select id="filter_product" class="form-control select2">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Category</label>
                                <select id="filter_category" class="form-control select2">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Transaction Type</label>
                                <select id="filter_transaction_type" class="form-control select2">
                                    <option value="">All Types</option>
                                    <option value="Purchase">Purchase</option>
                                    <option value="Sale">Sale</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Created By</label>
                                <select id="filter_created_by" class="form-control select2">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>From Date</label>
                                <input type="date" id="filter_from_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>To Date</label>
                                <input type="date" id="filter_to_date" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="reset_filters" class="btn btn-dark w-100">Reset Filters</button>
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
            // ✅ Get the existing DataTable instance created by Yajra
            let table = $('#detailedstocksreport-table').DataTable();

            // Reload table when filters change
            $('#filter_product, #filter_category, #filter_transaction_type, #filter_created_by, #filter_from_date, #filter_to_date').on('change', function() {
                table.ajax.reload();
            });

            // Reset filters
            $('#reset_filters').on('click', function() {
                $('#filter_product').val(null).trigger('change');
                $('#filter_category').val(null).trigger('change');
                $('#filter_transaction_type').val(null).trigger('change');
                $('#filter_created_by').val(null).trigger('change');
                $('#filter_from_date').val('');
                $('#filter_to_date').val('');
                table.ajax.reload();
            });

            // Pass filter values into AJAX request
            table.on('preXhr.dt', function(e, settings, data) {
                data.product_id = $('#filter_product').val();
                data.category_id = $('#filter_category').val();
                data.transaction_type = $('#filter_transaction_type').val();
                data.created_by = $('#filter_created_by').val();
                data.from_date = $('#filter_from_date').val();
                data.to_date = $('#filter_to_date').val();
            });

            // Handle DataTables errors gracefully
            $.fn.dataTable.ext.errMode = 'none';
            $('#detailedstocksreport-table').on('error.dt', function(e, settings, techNote, message) {
                Notiflix.Notify.failure("DataTables error: " + message);
            });
        });
    </script>
@endsection

@stack('scripts')
