@extends('layout.main')
@section('title') {{'Print Sale'}} @endsection
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Print Inovice All</h4>

                                     

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

    <a href="{{ route('sales.index') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> New Sales </i></a> <br>  <br>               

                    <h4 class="card-title">Inovice | Data </h4>
                    

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th> 
                            <th>Sale No </th>
                            <th>Date </th>
                            <th>Description</th>  
                            <th>Amount (Gh¢)</th>
                            <th>Action</th>
                            
                        </thead>


                        <tbody>
                        	 
                        	@foreach($allData as $key => $item)
            <tr>
                <td> {{ $key+1}} </td>
                <td> {{ $item['payment']['customer']['name'] }} </td> 
                <td> {{ $item->sale_no }} </td> 
                <td> {{ date('d-m-Y',strtotime($item->date))  }} </td> 
                 
                  
                 <td>  {{ $item->description }} </td> 

                <td>  {{ $item['payment']['total_amount'] }} </td>

                <td>
                    @if(! $item->printed_at)
                        @can('sale-print')
                            <a href="{{ route('print.sale', Crypt::encrypt($item->id)) }}" class="btn btn-danger sm" title="Print Sale" >  <i class="fa fa-print"></i> </a>
                        @endcan
                    @else
                        <span class="badge bg-success">Already Printed</span>
                        @can('sale-reprint')
                            <button type="button" class="btn btn-primary btn-sm ms-1 btn-reprint-sale" data-no="{{ $item->sale_no }}" data-url="{{ route('sales.reprint', Crypt::encrypt($item->id)) }}">
                                <i class="fa fa-repeat"></i> Reprint
                            </button>
                        @endcan
                    @endif
                </td>
               
            </tr>
                        @endforeach
                        
                        </tbody>
                    </table>

                    <form id="reprintSaleForm" method="POST" style="display:none;">
                        @csrf
                        <input type="hidden" name="reason" id="reprint_reason">
                    </form>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        
                     
                        
                    </div> <!-- container-fluid -->
                </div>
 

@endsection

@section('scripts')
    <script>
        $(document).on('click', '.btn-reprint-sale', function() {
            const saleNo = $(this).data('no');
            const actionUrl = $(this).data('url');
            if (!(window.Notiflix && Notiflix.Confirm && typeof Notiflix.Confirm.prompt === 'function')) {
                Notiflix.Notify.failure('Reprint reason dialog is unavailable.');
                return;
            }

            Notiflix.Confirm.prompt(
                'Reprint Sale',
                'Enter reason for reprint of Sale #' + saleNo + ':',
                '',
                'Submit',
                'Cancel',
                function(clientAnswer) {
                    const reason = (clientAnswer || '').trim();

                    if (!reason) {
                        Notiflix.Notify.failure('Please enter a reason for reprint.');
                        return;
                    }

                    $('#reprintSaleForm').attr('action', actionUrl).find('#reprint_reason').val(reason);
                    $('#reprintSaleForm')[0].submit();
                }
            );
        });
    </script>
@endsection
