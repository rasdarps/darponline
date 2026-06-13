@extends('layout.main')
@section('title') {{'View Stock Report'}} @endsection

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Stock Report All</h4>

                    

                </div>
            </div>
        </div>
        <!-- end page title -->
                        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Stock Report </h4>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
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
@endsection

@stack('scripts')