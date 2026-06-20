@extends('layout.main')
@section('title') {{ 'View Expense Vouchers' }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">All Expense Vouchers</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('expense-vouchers.create') }}" class="btn btn-dark" style="float:right;">
                            <i class="fas fa-plus-circle"></i> Add Expense Voucher
                        </a>
                        <br><br>

                        <h4 class="card-title">All Expense Vouchers</h4>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
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
@endsection

@stack('scripts')
