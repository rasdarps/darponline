@extends('layout.main')
@section('title') {{'Dashboard'}} @endsection

<style>
    .sell-card{ background:#144E4E !important; }
    .buy-card{ background:#f89d13 !important; }
    .purchase_count{ background:#5a04e4 !important; }
    .invoice_count{ background:#d10363 !important; }
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $company->name }} | POS</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
  
@can('dashboard-view')
    <!-- Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('purchases.index') }}">
                <div class="card purchase_count">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2" style="color:#fff;">Total Purchases</p>
                                <h4 class="mb-2" style="color:#fff">{{ number_format($total_purchases, 2) }}</h4>
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i></span>Purchases</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="fa fa-bars font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="{{ route('invoices.index') }}">
                <div class="card invoice_count">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2" style="color:#fff;">Total Sales</p>
                                <h4 class="mb-2" style="color:#fff">{{ number_format($total_sales, 2) }}</h4>
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i></span>Sales</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="fa fa-bars font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="#">
                <div class="card sell-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Stock Balance</p>
                                <h4 class="mb-2" style="color:#fff;">{{ number_format($total_stock_balance, 2) }}</h4>
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i></span>Sales Order</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-stack-line font-size-24"></i>
                                </span>
                            </div>
                        </div>                                            
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="#">
                <div class="card buy-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Stock Value</p>
                                <h4 class="mb-2" style="color:#fff">{{ number_format($total_stock_value, 2) }}</h4>
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i></span>Purchase Order</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-money-dollar-circle-line font-size-24"></i> 
                                </span>
                            </div>
                        </div>                                              
                    </div>
                </div>
            </a>
        </div>
    </div><!-- end row -->

    <!--chart-->
    <!-- Monthly Sales Chart -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Monthly Sales Distribution</h4>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Re-Stock Alert -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Re-Stock Alert</h4>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Remaining Stock</th>           
                                <th>Stock Level</th>
                                <th>Stock Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $key => $product)
                            <tr style="color:red">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->stock_level }}</td>
                                <td>
                                    @if($product->quantity <= $product->stock_level)
                                        <div class="font-size-13">
                                            <a href="{{ route('purchases.create') }}" title="Reorder Product">
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>Reorder
                                            </a>
                                        </div>
                                    @else
                                        <div class="font-size-13">
                                            <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>OK
                                        </div>
                                    @endif
                                </td> 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endcan

</div><!-- end container-fluid -->
</div><!-- end page-content -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Sales (Gh¢)',
                data: @json($totals),
                backgroundColor: 'rgba(75, 192, 192, 1)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return 'Gh¢ ' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    },
                    color: '#000',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Gh¢ ' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

@endsection
