@extends('layout.main')
@section('title') {{'Create Invoice'}} @endsection

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">All Purchase List</h4>

                                     

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

    <a href="{{ route('purchases.create') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Add Purchase </i></a> <br>  <br>               

                    <h4 class="card-title">Purchase All Data </h4>
                    

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date </th>
                            <th>Purchase No </th>
                            <th>Supplier Name</th>      
                             
                            <th>Amount(Gh¢)</th>
                            <th>Action</th>
                            
                        </thead>


                        <tbody>
                        	 
                        	@foreach($allData as $key => $item)
            <tr>
                <td> {{ $key+1}} </td>
                <td> {{ date('d-m-Y',strtotime($item->date))  }} </td>
                <td> {{ $item->purchase_no }} </td> 
                <td> {{ $item['purchase_payment']['supplier']['name'] }} </td>     
                
                <td>  {{ $item['purchase_payment']['total_amount'] }} </td>

                <td>
                    <a href="{{ route('print.purchase',$item->id) }}" class="btn btn-info sm" title="Print purchase" >  <i class="fa fa-print"></i> </a>
                    <form action="{{ route('purchases.destroy', Crypt::encrypt($item->id)) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger sm delete-confirm-btn" title="Delete Data"><i class="fas fa-trash-alt"></i></button>
                    </form>

                
                </td>
               
            </tr>
                        @endforeach
                        
                        </tbody>
                    </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        
                     
                        
                    </div> <!-- container-fluid -->
                </div>
 

@endsection

{{-- @push('scripts') --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-confirm-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let form = this.closest('form');
                    Notiflix.Confirm.show(
                        'Delete Confirmation',
                        'Are you sure you want to delete this purchase?',
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