@extends('layout.main')
@section('title') {{'Create Invoice'}} @endsection

<style>
    .sell-card{
        background:#144E4E !important;
    }

    .buy-card{
        background:#f89d13 !important;
    }

    .purchase_count{
        background:#5a04e4 !important;

    }

    .invoice_count{
        background:#d10363 !important;

    }



</style>

@section('content')


<div class="page-content">
<div class="container-fluid">

<!-- start page title -->
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Dashboard</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">RICT | POS</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

</div>
</div>
</div>
<!-- end page title -->

 {{-- <!-- This button will submit a GET request to the /backup route when clicked -->
 <form action="{{ route('backup') }}" method="GET">
    @csrf
    <!-- Display a button with the label "Backup Database" -->
    <button type="submit">Backup Database</button>
</form> --}}

<div class="row">
    <div class="col-xl-3 col-md-6">
    <a href="{{ route('invoices.create') }}">
    <div class="card sell-card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <p class="text-truncate font-size-14 mb-2">Need to Sell</p>
                
                <h4 class="mb-2" style="color:#fff;">Create Sale</h4>
               
                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i></span>Sales Order</p>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-light text-primary rounded-3">
                    <i class="ri-shopping-cart-2-line font-size-24"></i>  
                </span>
            </div>
        </div>                                            
    </div><!-- end cardbody -->
    </div><!-- end card -->
    </a>
</div><!-- end col --> 

<div class="col-xl-3 col-md-6">
    <a href="{{ route('purchases.create') }}">
    <div class="card buy-card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <p class="text-truncate font-size-14 mb-2">Need to buy</p>
                <h4 class="mb-2" style="color:#fff">Purchase</h4>
                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i></span>Purchase Order</p>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-light text-success rounded-3">
                    <i class="ri-shopping-cart-2-line font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
    </div><!-- end card -->
    </a>
</div><!-- end col --> 

<div class="col-xl-3 col-md-6">
    <a href="#">
    <div class="card purchase_count">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <p class="text-truncate font-size-14 mb-2" style="color:#fff;">Total Purchases</p>
                <h4 class="mb-2" style="color:#fff">{{ $total_purchase }}</h4>
                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i></span>Count</p>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-light text-primary rounded-3">
                    <i class="fa fa-bars font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
    </div><!-- end card -->
    </a>
</div><!-- end col -->

<div class="col-xl-3 col-md-6">
    <a href="#">
    <div class="card invoice_count">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <p class="text-truncate font-size-14 mb-2" style="color:#fff;">Total Invoices</p>
                <h4 class="mb-2" style="color:#fff">{{ $total_invoice }}</h4>
                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i></span>Count</p>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-light text-success rounded-3">
                    <i class="fa fa-bars font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
    </div><!-- end card -->
    </a>
    </div><!-- end col -->
</div><!-- end row --> 

<div class="row">
    <div class="row">
    <div class="col-xl-12">
    <div class="card">
    <div class="card-body">
        <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
         
        </div>

        <h4 class="card-title mb-4">Re-Stock Alert</h4>

        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
            <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Remaining Stock</th>           
                <th>Stock Level</th>
                <th>Stock Status</th>
            </thead>


            <tbody>
                 
                @foreach($products as $key => $product)
            <tr style="color:red">
                <td> {{ $key+1}} </td>
                <td> {{ $product->name }} </td>
                
                <td>{{ $product->quantity }}</td>
                
                <td> {{ $product->stock_level }} </td>
                <td> 
                    @if($product->quantity <=  $product->stock_level)
                    <div class="font-size-13" bis_skin_checked="1">
                        <a href="{{ route('purchases.create') }}" title="Reorder Product">
                            <i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2">
                            </i>Reorder
                        </a>
                    </div>
                    @elseif($product->quantity >  $product->stock_level)
                    <div class="font-size-13" bis_skin_checked="1">
                        <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2">
                        </i>OK</div>
                    @endif
                     </td> 
               
            </tr>
            @endforeach
            
            </tbody>
        </table>


    </div><!-- end card -->
</div><!-- end card -->
</div>
<!-- end col -->
 


</div>
<!-- end row -->
</div>

</div>

@endsection