@extends('layout.main')
@section('title') {{'View Credit Customer'}} @endsection

@section('content')


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex  align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Credit Customer All</h4>

                                     

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Credit Customer Data </h4>
                    

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th> 
                            <th>Sale No </th>
                            <th>Date</th>
                            <th class="text-end">Due Amount (Gh¢)</th>
                            <th>Action</th>
                            
                        </thead>


                        <tbody>
                        	 
                        	@foreach($allData as $key => $item)
    <tr>
        <td> {{ $key+1}} </td>
        <td> {{ $item['customer']['name'] }} </td> 
        <td> {{ $item['sale']['sale_no'] }}   </td> 
        <td> {{  date('d-m-Y',strtotime($item['sale']['date'])) }} </td> 
        <td class="text-end"> {{ $item->due_amount }} </td> 
        <td>
    <a href="{{ route('customer.edit.sale',$item->sale_id) }}" class="btn btn-info sm" title="Edit Data">  <i class="fas fa-edit"></i> </a>

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
