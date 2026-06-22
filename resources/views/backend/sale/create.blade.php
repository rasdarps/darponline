@extends('layout.main')
@section('title') {{'Create Sales Sale'}} @endsection

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="margin-top: 5px;">
                        <div class="card-header with-border">
                            <span class="card-title" style="font-size:20px;">Create | Sales Sale</span>
                            <a href="{{route('sales.index')}}" style="float:right">
                                <button type="button" class="btn btn-dark modal_btn">
                                    View Sales sales
                                </button>
                            </a>
                            <hr>
                            <div class="card-body">
                                {{-- backend error alerts --}}
                                <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" style="display: none;">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                                        <span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <ul id="errors"></ul>
                                </div>
                                <form method="post" action="{{ route('sales.store') }}" id="invoiceForm" novalidate>
                                    @csrf
                                        <div class="row mb-2">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <input class="form-control" name="sale_no" type="text" value="{{ $sale_no }}" id="sale_no" readonly style="background-color:#ddd;">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                <input class="form-control" name="current_stock_qty" type="text" id="current_stock_qty" readonly style="background-color:#ddd">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center justify-content-end">
                                                <input class="form-control" value="{{ $date }}" name="date" type="date" id="date" {{ !empty($preventBackdatedInvoices) ? 'min='.$date : '' }}>
                                            </div>
                                        </div>

                                        <div class="row align-items-end mb-2">
                                            {{-- //barcode field for product lookup --}}
                                            <div class="col-md-4">
                                                <div class="form-group mb-1 d-flex align-items-center">
                                                    <input type="text" id="barcode" class="form-control" placeholder="Scan Barcode" autofocus>
                                                    {{-- <small class="form-text text-muted">Scan product barcode here</small> --}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-1 d-flex align-items-center">
                                                    <select name="category_id" id="category_id" class="form-control select2" aria-label="Select category">
                                                        <option disabled selected value="">Select Category</option>
                                                        @foreach($category as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           
                                             <div class="col-md-4">
                                                <div class="form-group mb-1 d-flex align-items-center">
                                                    <select name="product_id" id="product_id" class="form-control select2" aria-label="Select product">
                                                        <option disabled selected value="">Select Product</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-1 d-flex align-items-center">
                                                    <select name="unit_id" id="unit_id" class="form-control select2" aria-label="Select unit">
                                                        <option disabled selected value="">Select Unit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-dark waves-effect waves-light fas fa-plus-circle addeventmore"> Add</button>
                                            </div>
                                        </div>
                                    {{-- <hr> --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                                <thead>
                                                    <tr style="background-color:#111827; text-align:center;" class="text-white">  
                                                        <th width="10%">Category</th>
                                                        <th width="12%">Barcode</th>
                                                        <th width="15%">Product Name</th>
                                                        <th width="10%">Qty</th>
                                                        <th width="15%">Selling Price</th>
                                                        <th width="10%">Per</th>
                                                        <th width="15%">Total Price(Ghc)</th>
                                                        <th width="7%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="addRow" class="addRow"></tbody>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6">Discount</td>
                                                        <td>
                                                            <input type="text" name="discount_amount" id="discount_amount" class="form-control estimated_amount" placeholder="Discount Amount" value="0" {{ empty($allowManualDiscountEntryByStaff) ? 'readonly' : '' }}>
                                                            @if (empty($allowManualDiscountEntryByStaff))
                                                                <small class="text-muted">Manual discount entry is disabled by settings.</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">Taxable Subtotal</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="taxable_amount" value="0.00" id="taxable_amount" class="form-control" readonly style="background-color: #ddd;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">NHIL (2.5%)</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="nhil_amount" value="0.00" id="nhil_amount" class="form-control" readonly style="background-color: #ddd;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">GETFund (2.5%)</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="getfund_amount" value="0.00" id="getfund_amount" class="form-control" readonly style="background-color: #ddd;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">VAT (15%)</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="vat_amount" value="0.00" id="vat_amount" class="form-control" readonly style="background-color: #ddd;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">Grand Total</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">Cash Tendered</td>
                                                        <td>
                                                            <input style="text-align: right;" type="number" step="0.01" min="0" name="cash_tendered" value="0" id="cash_tendered" class="form-control" placeholder="Enter cash tendered">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">Change Due</td>
                                                        <td>
                                                            <input style="text-align: right;" type="text" name="change_due" value="0.00" id="change_due" class="form-control" readonly style="background-color: #ddd;">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="form-group col-md-12">
                                            <textarea name="description" class="form-control" id="description" placeholder="Write Description Here"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-md-3 block_div">
                                            <label><strong>Paid Status <span class="text-danger">*</span></strong></label>
                                            <select name="paid_status" id="paid_status" class="form-control" readonly>
                                                {{-- <option value="">Select Payment Status</option> --}}
                                                <option value="full_paid">Full Paid</option>
                                                <option value="full_due" disabled>Credit</option>
                                                <option value="partial_paid" disabled>Partial Paid</option>
                                            </select>
                                            <input type="text" name="paid_amount" class="form-control paid_amount mt-2" placeholder="Enter Paid Amount" style="display:none;">
                                        </div>
                                        <div class="form-group col-md-9 block_div">
                                            <label><strong>Account Name <span class="text-danger">*</span></strong></label>
                                            <select name="customer_id" id="customer_id" class="form-control" readonly>
                                                <option value="" disabled>Select Customer</option>
                                                @foreach($costomer as $cust)
                                                    <option value="{{ $cust->id }}" 
                                                        {{ $cust->name === 'cash_sales' ? 'selected' : '' }}>
                                                        {{ $cust->name }}
                                                    </option>
                                                @endforeach
                                                <option value="0" disabled>New Customer</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Hidden Add Customer Form -->
                                    <div class="row new_customer mt-3 block_div">
                                        <div class="form-group col-md-4">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Customer Name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Customer Mobile No" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Customer Email">
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <center>
                                            <button type="submit" class="btn btn-success storeButton" id="storeButton">Submit Sale</button>
                                        </center>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('styles')
    <style>
        .block_div {
            display: none;
           
        }
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
            overflow: hidden;
            transform: translateY(0);
        }
        .card:hover {
            box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
            border-radius: 20px;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
            z-index: 1;
        }
        .card:hover::before {
            left: 100%;
        }
        .card-body {
            position: relative;
            z-index: 2;
        }
        .card {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            border: none;
        }
        .card:hover {
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        }
        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .select2 {
            width: 100% !important;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        const ALLOW_NEGATIVE_STOCK = @json((bool) ($allowNegativeStock ?? false));
        const PREVENT_BACKDATED_INVOICES = @json((bool) ($preventBackdatedInvoices ?? false));
        const ALLOW_MANUAL_DISCOUNT_ENTRY_BY_STAFF = @json((bool) ($allowManualDiscountEntryByStaff ?? true));

        $(document).ready(function (){
            // Handle form submission
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();
                // Client-side validation (Notiflix style)
                function notify(msg) {
                    console.log('Validation Error:', msg);
                    if (typeof Notiflix !== 'undefined') {
                        Notiflix.Notify.failure(msg, {
                            timeout: 4000,
                            showOnlyTheLastOne: false,
                            position: 'right-top',
                        });
                    } else {
                        alert(msg);
                    }
                }

                if(!$('#date').val().trim()) {
                    notify('Please enter a date');
                    return;
                }

                if (PREVENT_BACKDATED_INVOICES) {
                    var selectedDate = new Date($('#date').val());
                    var today = new Date();
                    selectedDate.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);
                    if (selectedDate < today) {
                        notify('Backdated invoices are disabled. Please use today\'s date.');
                        return;
                    }
                }
                if(!$('#category_id').val()) {
                    notify('Please select a category');
                    return;
                }
                if(!$('#product_id').val()) {
                    notify('Please select a product');
                    return;
                }
                if(!$('#unit_id').val()) {
                    notify('Please select a unit');
                    return;
                }

                // Validate at least one Sale item
                if($('#addRow').children('tr').length === 0) {
                    notify('Please add at least one Sale item.');
                    return;
                }

                // Validate Sale item fields (selling_qty, unit_selling_price > 0)
                var validItems = true;
                var validationError = null;
                var firstInvalidField = null;
                
                $('#addRow tr').each(function(index) {
                    if (!validItems) return false;
                    
                    var $row = $(this);
                    var $qtyField = $row.find('input.selling_qty');
                    var qty = $qtyField.val();
                    var price = $row.find('input.unit_selling_price').val();
                    var availableQty = parseFloat($row.find('input.available_stock_qty').val()) || 0;
                    
                    if (!qty || isNaN(qty) || parseFloat(qty) <= 0) {
                        validationError = 'Each item must have a valid quantity greater than 0.';
                        firstInvalidField = $qtyField;
                        validItems = false;
                        return false;
                    }
                    
                    if (!ALLOW_NEGATIVE_STOCK && parseFloat(qty) > availableQty) {
                        validationError = 'One of the selected items has negative stock so remove from invoice';
                        firstInvalidField = $qtyField;
                        validItems = false;
                        return false;
                    }
                    
                    if (!price || isNaN(price) || parseFloat(price) <= 0) {
                        validationError = 'Each item must have a valid unit selling price greater than 0.';
                        firstInvalidField = $row.find('input.unit_selling_price');
                        validItems = false;
                        return false;
                    }
                });
                
                if (!validItems) {
                    if (validationError) {
                        console.error('Validation Error:', validationError);
                        notify(validationError);
                        // Scroll to the error field
                        if (firstInvalidField && firstInvalidField.length) {
                            $('html, body').animate({
                                scrollTop: firstInvalidField.offset().top - 100
                            }, 500);
                            firstInvalidField.focus().css('border-color', '#dc3545');
                            setTimeout(function() {
                                firstInvalidField.css('border-color', '');
                            }, 3000);
                        }
                    }
                    return;
                }

                if(!$('#paid_status').val()) {
                    notify('Please select a paid status');
                    return;
                }

                var totalAmount = parseFloat($('#estimated_amount').val()) || 0;
                var cashTendered = parseFloat($('#cash_tendered').val());
                if(isNaN(cashTendered) || cashTendered < 0) {
                    notify('Please enter a valid cash tendered amount');
                    return;
                }

                if($('#paid_status').val() === 'full_paid' && cashTendered < totalAmount) {
                    notify('Cash tendered cannot be less than the grand total for full payment.');
                    return;
                }

                if(!$('#customer_id').val()) {
                    notify('Please select a customer name');
                    return;
                }

                // Validate paid amount if partial paid
                if($('#paid_status').val() === 'partial_paid') {
                    var paidAmount = $('input.paid_amount').val();
                    if(!paidAmount || isNaN(paidAmount) || parseFloat(paidAmount) <= 0) {
                        notify('Please enter a valid paid amount greater than 0 for partial payment.');
                        return;
                    }
                }

                // Validate new customer fields if "New Customer" is selected
                if($('#customer_id').val() === '0') {
                    var name = $('#name').val();
                    var mobile = $('#mobile_no').val();
                    var email = $('#email').val();
                    if(!name || !name.trim()) {
                        notify('Customer name is required.');
                        return;
                    }
                    if(!mobile || !/^\d{10}$/.test(mobile)) {
                        notify('Customer mobile number must be 10 digits.');
                        return;
                    }
                    if(!email || !/^\S+@\S+\.\S+$/.test(email)) {
                        notify('Please enter a valid customer email.');
                        return;
                    }
                }

                // Show loading
                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Loading.standard('Creating Sale, please wait...');
                } else {
                    console.log('Creating Sale...');
                }
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: new FormData(form),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (typeof Notiflix !== 'undefined') {
                            Notiflix.Loading.remove();
                        }
                        if (data.status === 200) {
                            if (typeof Notiflix !== 'undefined') {
                                Notiflix.Notify.success(data.message);
                            } else {
                                alert(data.message);
                            }


                            // ðŸš€ Open the receipt page
                            if (data.print_url) {
                                window.location.href = data.print_url;
                            }
                            
                            // Clear form fields
                            $('#invoiceForm')[0].reset();
                            // Remove all dynamic Sale items
                            $('#addRow').empty();
                            // Hide and clear error alert
                            $('#errorAlert').hide();
                            $('#errors').empty();
                            // Optionally reset select2 fields
                            if ($('.select2').length) {
                                $('.select2').val('').trigger('change');
                            }
                            
                            // Directly set next purchase number from backend response
                        if (data.next_no) {
                            $('#sale_no').val(data.next_no);
                        }
                        } else {
                            if (typeof Notiflix !== 'undefined') {
                                Notiflix.Notify.failure(data.message);
                            } else {
                                alert(data.message);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        if (typeof Notiflix !== 'undefined') {
                            Notiflix.Loading.remove();
                        }
                        $('#errors').empty();

                        if (xhr.status === 419 || xhr.status === 401) {
                            const sessionMessage = 'Your login session has expired due to inactivity.';
                            const sessionAction = 'Action: refresh the page and sign in again, then retry saving the Sale.';

                            $('#errorAlert').show();
                            $('#errors').append('<li>' + sessionMessage + '</li>');
                            $('#errors').append('<li>' + sessionAction + '</li>');

                            if (typeof Notiflix !== 'undefined') {
                                Notiflix.Notify.failure(sessionMessage + ' ' + sessionAction);
                            } else {
                                alert(sessionMessage + ' ' + sessionAction);
                            }

                            $('html, body').animate({
                                scrollTop: $('#errorAlert').offset().top - 100
                            }, 500);
                            return;
                        }

                        if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.error_code === 'SALE_NO_CLASH') {
                            $('#errorAlert').show();

                            const clashMessage = xhr.responseJSON.message || 'Sale number clash detected.';
                            const clashAction = xhr.responseJSON.action || 'Action: click Save Sale again.';

                            $('#errors').append('<li>' + clashMessage + '</li>');
                            $('#errors').append('<li>' + clashAction + '</li>');

                            if (xhr.responseJSON.next_no) {
                                $('#sale_no').val(xhr.responseJSON.next_no);
                            }

                            if (typeof Notiflix !== 'undefined') {
                                Notiflix.Notify.failure(clashMessage + ' ' + clashAction);
                            } else {
                                alert(clashMessage + ' ' + clashAction);
                            }

                            $('html, body').animate({
                                scrollTop: $('#errorAlert').offset().top - 100
                            }, 500);
                            return;
                        }

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            $('#errorAlert').show();
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $('#errors').append('<li>' + messages[0] + '</li>');
                                if (typeof Notiflix !== 'undefined') {
                                    Notiflix.Notify.failure(messages[0]);
                                } else {
                                    alert('Validation Error: ' + messages[0]);
                                }
                            });
                            $('html, body').animate({
                                scrollTop: $('#errorAlert').offset().top - 100
                            }, 500);
                        } else {
                            let duplicate = false;
                            if (xhr.responseText && xhr.status === 500) {
                                // Check for SQL duplicate entry error
                                if (xhr.responseText.includes('1062') && xhr.responseText.includes('sale_no')) {
                                    duplicate = true;
                                }
                            }
                            $('#errorAlert').show();
                            if (duplicate) {
                                $('#errors').append('<li>Duplicate Sale number. Please refresh the page and try again.</li>');
                                if (typeof Notiflix !== 'undefined') {
                                    Notiflix.Notify.failure('Duplicate Sale number. Please refresh the page and try again.');
                                } else {
                                    alert('Duplicate Sale number. Please refresh the page and try again.');
                                }
                            } else {
                                $('#errors').append('<li>An error occurred. Please try again later.</li>');
                                if (typeof Notiflix !== 'undefined') {
                                    Notiflix.Notify.failure('An error occurred. Please try again later.');
                                } else {
                                    alert('An error occurred. Please try again later.');
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>

    <script id="document-template" type="text/x-handlebars-template">
     
        <tr class="delete_add_more_item" id="delete_add_more_item">
            <input type="hidden" name="date" value="@{{date}}">
            <input type="hidden" name="sale_no" value="@{{sale_no}}">
            
    
            <td>
                <input type="hidden" name="category_id[]" value="@{{category_id}}">
                @{{ category_name }}
            </td>

            <td>
                <input type="hidden" name="barcode[]" value="@{{barcode}}">
                @{{ barcode }}
            </td>

            <td>
                <input type="hidden" name="product_id[]" value="@{{product_id}}">
                @{{ product_name }}
                <br>
                <small class="text-muted">Available Qty: @{{ available_qty }}</small>
                <input type="hidden" name="available_stock_qty[]" class="available_stock_qty" value="@{{ available_qty }}">
            </td>

            
            <td>
                <input type="number" min="1" class="form-control selling_qty text-right" id="selling_qty" name="selling_qty[]" value="{{ old('selling_qty') }}" onchange="checkQuantity()" oninput="$(this).css('border-color', '')"> 
            </td>

            <td>
                <!-- ðŸ”¹ Unit selling price auto-filled from products.selling_price -->
                <input type="number" 
                    class="form-control unit_selling_price text-right" 
                    name="unit_selling_price[]"
                    value="@{{unit_selling_price}}" 
                    readonly 
                    style="background-color:#ddd;">
            </td>

            <td>
                <input type="hidden" name="unit_id[]" value="@{{unit_id}}">
                @{{ unit_name }}
            </td>


            <td>
                <input type="number" class="form-control selling_price text-right" name="selling_price[]" value="0" readonly> 
                
            </td>

            <td>
                <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
            </td>

        </tr>

    </script>

    {{-- handles add more item in purchase --}}
    <script type="text/javascript">
        $(document).ready(function(){

            function round2(num) {
                return (Math.round((num + Number.EPSILON) * 100) / 100).toFixed(2);
            }

            function getNumeric(selector) {
                var value = parseFloat($(selector).val());
                return isNaN(value) ? 0 : value;
            }

            function updateTenderSummary() {
                var total = getNumeric('#estimated_amount');
                var cashTendered = getNumeric('#cash_tendered');
                var changeDue = Math.max(0, cashTendered - total);

                $('#change_due').val(round2(changeDue));
            }

             // ðŸ”¹ Handle barcode scan/entry
            $(document).on("change", "#barcode", function(){
                var barcode = $(this).val();
                if(!barcode) return;

                $.ajax({
                    url: "{{ route('products.findByBarcode') }}", // lookup route
                    type: "GET",
                    data: { barcode: barcode },
                    success: function(product){
                        if(product && product.id){
                            var date = $('#date').val();
                            var sale_no = $('#sale_no').val();

                            // ðŸ”¹ Update dropdowns and stock field so barcode flow matches manual flow
                        $('#category_id').val(product.category_id).trigger('change');
                        $('#product_id').html('<option value="'+product.id+'" selected>'+product.name+'</option>');
                        $('#unit_id').html('<option value="'+product.unit_id+'" selected>'+product.unit.name+'</option>');
                        $('#current_stock_qty').val(product.quantity);

                            // Prepare data for Handlebars row
                            var source = $("#document-template").html();
                            var template = Handlebars.compile(source);
                            var data = {
                                date: date,
                                sale_no: sale_no,
                                barcode: product.barcode,          // ðŸ”¹ added
                                category_id: product.category_id,
                                category_name: product.category.name,
                                product_id: product.id,
                                product_name: product.name,
                                available_qty: product.quantity,
                                unit_id: product.unit_id,
                                unit_name: product.unit.name,
                                unit_selling_price: product.selling_price // ðŸ”¹ add this
                            };
                            var html = template(data);
                            $("#addRow").append(html);

                            // Clear barcode field after scan
                            $('#barcode').val('');

                            // ðŸ”¹ Optional: show stock quantity
                            // $('#current_stock_qty').val(product.quantity);

                            Notiflix.Notify.success('Product added via barcode');
                        } else {
                            Notiflix.Notify.failure('Product not found for this barcode. Please select manually.');
                        }
                    },
                    error: function(){
                        Notiflix.Notify.failure('Error looking up barcode. Try again.');
                    }
                });
            });

            // Existing add button flow (manual selection)
            $(document).on("click", ".addeventmore", function(){
                var date = $('#date').val();
                var sale_no = $('#sale_no').val(); 
                var category_id  = $('#category_id').val();
                var category_name = $('#category_id').find('option:selected').text();
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();
                var selling_price = $('#product_id').find('option:selected').data('price'); // ðŸ”¹ new
                var available_qty = $('#product_id').find('option:selected').data('qty');
                var unit_id  = $('#unit_id').val();
                var unit_name = $('#unit_id').find('option:selected').text();

                function notifyPop(msg) {
                    if (typeof Notiflix !== 'undefined') {
                        Notiflix.Notify.failure(msg);
                    } else {
                        alert(msg);
                    }
                }

                if(date == ''){
                    notifyPop("Date is Required");
                    return false;
                }
                if(category_id == '' || category_name.trim() === 'Select Category'){
                    notifyPop("Category is Required");
                    return false;
                }
                if(product_id == '' || product_name.trim() === 'Select Product'){
                    notifyPop("Product Field is Required");
                    return false;
                }
                if(unit_id == '' || unit_name.trim() === 'Select Unit'){
                    notifyPop("Unit Field is Required");
                    return false;
                }

                var source = $("#document-template").html();
                var tamplate = Handlebars.compile(source);
                var data = {
                    date:date,
                    sale_no:sale_no, 
                    category_id:category_id,
                    category_name:category_name,
                    product_id:product_id,
                    product_name:product_name,
                    available_qty: available_qty,
                    unit_id:unit_id,
                    unit_name:unit_name,
                    barcode: '', // ðŸ”¹ empty if added manually
                    unit_selling_price: selling_price // ðŸ”¹ add this
                };
                var html = tamplate(data);
                $("#addRow").append(html);
            });

            $(document).on("click",".removeeventmore",function(event){
                $(this).closest(".delete_add_more_item").remove();
                totalAmountPrice();
            });

            $(document).on('keyup click','.unit_selling_price,.selling_qty', function(){
                var unit_selling_price = $(this).closest("tr").find("input.unit_selling_price").val();
                var qty = $(this).closest("tr").find("input.selling_qty").val();
                var total = unit_selling_price * qty;
                $(this).closest("tr").find("input.selling_price").val(total);
                $('#discount_amount').trigger('keyup');
            });

            $(document).on('keyup','#discount_amount',function(){
                totalAmountPrice();
            });

            $(document).on('keyup change','#cash_tendered', function(){
                updateTenderSummary();
            });

            // Calculate sum of amout in Sale 

            function totalAmountPrice(){
                var grossTotal = 0;
                $(".selling_price").each(function(){
                    var value = $(this).val();
                    if(!isNaN(value) && value.length != 0){
                        grossTotal += parseFloat(value);
                    }
                });

                var discountAmount = parseFloat($('#discount_amount').val()) || 0;
                if (!ALLOW_MANUAL_DISCOUNT_ENTRY_BY_STAFF) {
                    discountAmount = 0;
                    $('#discount_amount').val('0');
                }
                if (discountAmount < 0) {
                    discountAmount = 0;
                }

                var netTotal = grossTotal - discountAmount;
                if (netTotal < 0) {
                    netTotal = 0;
                }

                // Receipt-style tax-inclusive breakdown:
                // total = taxable + NHIL(2.5%) + GETFund(2.5%) + VAT(15%) = taxable * 1.20
                var taxableAmount = netTotal / 1.20;
                var nhilAmount = taxableAmount * 0.025;
                var getfundAmount = taxableAmount * 0.025;
                var vatAmount = taxableAmount * 0.15;

                $('#taxable_amount').val(round2(taxableAmount));
                $('#nhil_amount').val(round2(nhilAmount));
                $('#getfund_amount').val(round2(getfundAmount));
                $('#vat_amount').val(round2(vatAmount));

                $('#estimated_amount').val(round2(netTotal));
                updateTenderSummary();

            }  

        });


    </script>
 

    <script type="text/javascript">

        //auto pull product when category selected
        $(function(){
            $(document).on('change','#category_id',function(){
                var category_id = $(this).val();
                $.ajax({
                    url:"{{ route('get-product') }}",
                    type: "GET",
                    data:{category_id:category_id},
                    success:function(data){
                        var html = '<option value="">Select Product</option>';
                        $.each(data,function(key,v){
                            html += '<option value=" '+v.id+' " data-price="'+v.selling_price+'" data-qty="'+v.quantity+'"> '+v.name+'</option>';
                        });
                        $('#product_id').html(html);
                    }
                })
            });
        });

    </script>

    <!--Select Product to pull related Unit-->
    <script type="text/javascript">
        $(function(){
            $(document).on('change','#product_id',function(){
                var product_id = $(this).val();
                $.ajax({
                    url:"{{ route('get-unit') }}",
                    type: "GET",
                    data:{product_id:product_id},
                    success:function(data){
                        var html = '<option value="">Select Unit</option>';
                        $.each(data,function(key,v){
                            html += '<option value=" '+v.unit_id+' "> '+v.unit.name+'</option>';
                        });
                        $('#unit_id').html(html);
                    }
                })
            });
        });
    </script>
 
    <script type="text/javascript">
    //get current stock quantity when product selected
        $(function(){
            $(document).on('change','#product_id',function(){
                var product_id = $(this).val();
                $.ajax({
                    url:"{{ route('check-product-stock') }}",
                    type: "GET",
                    data:{product_id:product_id},
                    success:function(data){                   
                        $('#current_stock_qty').val(data);
                    }
                });
            });
        });

    </script>

    {{-- check quantity script --}}
    <script>
        function checkQuantity() {
            if (ALLOW_NEGATIVE_STOCK) {
                return;
            }

            var hasNegativeStock = false;

            $('#addRow tr').each(function() {
                var qtyField = $(this).find('input.selling_qty');
                var qty = parseFloat(qtyField.val()) || 0;
                var availableQty = parseFloat($(this).find('input.available_stock_qty').val()) || 0;

                if (qty > availableQty && qty > 0) {
                    hasNegativeStock = true;
                    qtyField.css('border-color', '#dc3545');
                } else {
                    // Clear red border if quantity is now valid
                    qtyField.css('border-color', '');
                }
            });

            if (hasNegativeStock) {
                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Notify.warning('Quantity exceeds available stock. Please adjust before submitting.', {
                        timeout: 4000,
                        position: 'right-top',
                    });
                } else {
                    alert('Quantity exceeds available stock. Please adjust before submitting.');
                }
            }
        }
    </script>
    

    <!-- Removed old .storeButton click validation handler. All validation now uses Notiflix in the main form submission handler. -->


    <script type="text/javascript">
    //payment status and customer show or hide
        $(document).on('change','#paid_status', function(){
            var paid_status = $(this).val();
            if (paid_status == 'partial_paid') {
                $('.paid_amount').show();
            }else{
                $('.paid_amount').hide();
            }
        });

        $(document).on('change','#customer_id', function(){
            var customer_id = $(this).val();
            if (customer_id == '0') {
                $('.new_customer').show();
            }else{
                $('.new_customer').hide();
            }
        });


    </script>
@endsection

