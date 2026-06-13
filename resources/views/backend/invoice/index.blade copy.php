@extends('layout.main')
@section('title') {{'View Invoice'}} @endsection

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Inovice All</h4>  
                    </div>
                </div>
            </div>
            <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{ route('invoices.create') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Add Inovice </i></a> <br>  <br>               

                        <h4 class="card-title">Inovice All Data </h4>      
                  
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-confirm-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let form = this.closest('form');
                    Notiflix.Confirm.show(
                        'Delete Confirmation',
                        'Are you sure you want to delete this customer?',
                        'Yes',
                        'No',
                        function okCb() {
                            form.submit();
                        },
                        function cancelCb() {
                            // Do nothing
                        }
                    );
                });
            });
        });
    </script>
@endsection

@stack('scripts')