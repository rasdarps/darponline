@extends('layout.main')
@section('title') {{'Sale Receipt'}} @endsection

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Print Sale Receipt </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                                <li class="breadcrumb-item active">Sale Receipt</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
        <div class="receipt-container" id="invoiceArea">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
            
                            <div class="row">
                                <div class="col-12">
                                    <div class="Sale-title" align="center">
                                        
                                        <h5>
                                            Sales Receipt
                                        </h5>
                                        <p><strong>{{ $companyInfo->name }}</strong>
                                            <br>
                                        {{ $companyInfo->address }}
                                        <br>
                                        {{ $companyInfo->phone }} |  TIN: {{ $companyInfo->tin_number ?? 'N/A' }}</p>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            @php
                                            $cashierName = optional($Sale->createdBy)->name;
                                            $cashierFirstName = $cashierName ? explode(' ', trim($cashierName))[0] : 'N/A';
                                            @endphp
                                               <p>
                                                Receipt No # {{ $Sale->sale_no }} <br>
                                                Cashier: {{ $cashierFirstName }} 
                                               </p>  
                                           
                                        </div>
                                        <div class="col-6 mt-4 text-end">
                                                <p><strong>Sale Date & Time:</strong><br></p>
                                                <p>{{ optional($Sale->created_at)->format('d-m-Y h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @php
                        $payment = App\Models\Payment::where('sale_id',$Sale->id)->first();
                        $discountAmount = $payment->discount_amount ?? 0;
                        $taxableAmount = $payment->taxable_amount ?? 0;
                        $nhilAmount = $payment->nhil_amount ?? 0;
                        $getfundAmount = $payment->getfund_amount ?? 0;
                        $vatAmount = $payment->vat_amount ?? 0;
                        $paidAmount = $payment->paid_amount ?? 0;
                        $totalAmount = $payment->total_amount ?? 0;
                        $cashTendered = $payment->cash_tendered ?? 0;
                        $changeDue = $payment->change_due ?? 0;
                        @endphp   

                <div class="row">
                    <div class="col-12">
                        <div>
                            <div class="">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td><strong>Sl </strong></td>
                                                <td class="text-center"><strong>Category</strong></td>
                                                <td class="text-center"><strong>Product Name</strong></td>    
                                                <td class="text-center"><strong>Quantity</strong>
                                                </td>
                                                <td class="text-center"><strong>Unit Price (GhÂ¢) </strong>
                                                </td>
                                                <td class="text-end"><strong>Total Price (GhÂ¢)</strong>
                                                </td>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                    
                                            @php
                                            $total_sum = '0';
                                            @endphp
                                                    @foreach($Sale['sale_details'] as $key => $details)
                                                    <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                        <td class="text-center">{{ $details['category']['name'] }}</td>
                                                        <td class="text-center">{{ $details['product']['name'] }}</td>  
                                                        <td class="text-center">{{ $details->selling_qty }}</td>
                                                        <td class="text-center">{{ $details->unit_selling_price }}</td>
                                                        <td class="text-end">{{ number_format($details->selling_price, 2) }}</td>
                                                        
                                                    </tr>
                                                    @php
                                                    $total_sum += $details->selling_price;
                                                    @endphp
                                                    @endforeach
                                                    <tr>
                                                        
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center">
                                                            <strong>Subtotal</strong></td>
                                                        <td class="thick-line text-end">{{ number_format($total_sum, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Discount Amount</strong></td>
                                                        <td class="no-line text-end">{{ number_format($discountAmount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Taxable Subtotal</strong></td>
                                                        <td class="no-line text-end">{{ number_format($taxableAmount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>NHIL (2.5%)</strong></td>
                                                        <td class="no-line text-end">{{ number_format($nhilAmount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>GETFund (2.5%)</strong></td>
                                                        <td class="no-line text-end">{{ number_format($getfundAmount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>VAT (15%)</strong></td>
                                                        <td class="no-line text-end">{{ number_format($vatAmount, 2) }}</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Amount</strong></td>
                                                        <td class="no-line text-end">{{ number_format($paidAmount, 2) }}</td>
                                                    </tr> --}}
                                                    <tr>
                                                        
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Grand Amount</strong></td>
                                                        <td class="no-line text-end"><h5 class="m-0">GhÂ¢{{ number_format($totalAmount, 2) }}</h5></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Cash Tendered | Change Due</strong></td>
                                                        <td class="no-line text-end">{{ number_format($cashTendered, 2) }} | {{ number_format($changeDue, 2) }}</td>
                                                    </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                        <a href="javascript:void(0)" onclick="downloadPDF()" class="btn btn-primary waves-effect waves-light ms-2">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <a href="{{ route('sales.create') }}" class="btn btn-info waves-effect waves-light ms-2">
                                            <i class="fa fa-plus"></i> New Sales
                                        </a>   
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> <!-- end row -->
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- print area ends -->
    </div> <!-- container-fluid -->
</div> <!-- page content-->

@endsection

@section('styles')
    <style>
        /* Print styles for Epson TM-U220 receipt printer */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: "Courier New", monospace; /* classic receipt font */
                font-size: 5px;
                line-height: 1.2;
            }

            .no-print, .btn, .modal_btn {
                display: none !important; /* hide buttons/links */
            }

            .receipt-container {
                width: 100mm; /* typical Epson TM-U220 paper width */
                margin: 0 auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 2px 4px;
                text-align: left;
                border: none;
                /* word-wrap: break-word; */
                white-space: normal;
            }

            h1, h2, h3, h4 {
                margin: 0;
                font-size: 9px;
                text-align: center;
            }

            /* Force page to fit roll width */
            @page {
                size: 100mm auto;
                margin: 0;
            }
        }
    </style>

@endsection

@section('scripts')
<script>
    window.onload = function() {
        window.print();
    };
</script>

@endsection
