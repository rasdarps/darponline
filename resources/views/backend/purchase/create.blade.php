@extends('layout.main')
@section('title') {{'Create Purchase Sale'}} @endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="margin-top: 5px;">
                        <div class="card-header with-border">
                            <span class="card-title mobile-page-title" style="font-size:20px;">Create | Purchase Sale</span>
                            <a href="{{route('purchases.index')}}" class="mobile-header-action" style="float:right">
                                <button type="button" class="btn btn-dark modal_btn mobile-header-btn">
                                    View Purchases Sale
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
                                <form method="post" action="{{ route('purchases.store') }}" id="purchaseForm">
                                    @csrf
                                        <div class="row mb-2">
                                            <div class="col-md-4 d-flex align-items-center">
                                                {{-- <label for="purchase_no" class="mb-0 mr-2" style="min-width:70px;"><strong>Number</strong></label> --}}
                                                <input class="form-control" name="purchase_no" type="text" value="{{ $purchase_no }}" id="purchase_no" readonly style="background-color:#ddd;">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center">
                                                {{-- <label for="current_stock_qty" class="mb-0 mr-2" style="min-width:60px;"><strong>Stock</strong></label> --}}
                                                <input class="form-control" name="current_stock_qty" type="text" id="current_stock_qty" readonly style="background-color:#ddd">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center justify-content-end">
                                                {{-- <label for="date" class="mb-0 mr-2" style="min-width:60px;"><strong>Date <span class="text-danger">*</span></strong></label> --}}
                                                <input class="form-control" value="{{ $date }}" name="date" type="date" id="date">
                                            </div>
                                        </div>
                                    <div class="row align-items-end mb-2">
                                        {{-- //barcode field for product lookup --}}
                                        <div class="col-md-4">
                                            <div class="form-group mb-1">
                                                <input type="text" id="barcode" class="form-control" placeholder="Scan Barcode" autofocus>
                                                <div class="mt-2 d-flex align-items-center">
                                                    <button type="button" id="startCameraScan" class="btn btn-outline-primary camera-scan-btn">
                                                        <i class="fas fa-camera mr-1"></i> Scan With Camera
                                                    </button>
                                                    <button type="button" id="stopCameraScan" class="btn btn-outline-secondary ml-2 camera-scan-btn" style="display:none;">Stop Camera</button>
                                                </div>
                                                {{-- <small class="form-text text-muted">Scan product barcode here</small> --}}
                                            </div>
                                            <div id="cameraScanWrap" class="mt-2" style="display:none;">
                                                <video id="barcodeCameraPreview" autoplay playsinline muted style="width:100%;max-height:220px;border:1px solid #ddd;border-radius:4px;background:#111;"></video>
                                                <small class="text-muted">Point camera at barcode. Works best over HTTPS on phone and desktop browsers.</small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-1 d-flex align-items-center">
                                                {{-- <label for="category_id" class="mb-0 mr-2" style="min-width:90px;"><strong>Category <span class="text-danger">*</span></strong></label> --}}
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
                                                {{-- <label for="product_id" class="mb-0 mr-2" style="min-width:90px;"><strong>Product <span class="text-danger">*</span></strong></label> --}}
                                                <select name="product_id" id="product_id" class="form-control select2" aria-label="Select product">
                                                    <option disabled selected value="">Select Product</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-1 d-flex align-items-center">
                                                {{-- <label for="unit_id" class="mb-0 mr-2" style="min-width:60px;"><strong>Unit <span class="text-danger">*</span></strong></label> --}}
                                                <select name="unit_id" id="unit_id" class="form-control select2" aria-label="Select unit">
                                                    <option disabled selected value="">Select Unit</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-dark waves-effect waves-light fas fa-plus-circle addeventmore mobile-action-btn"> Add</button>
                                        </div>
                                    </div>
                                    {{-- <hr> --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive mobile-item-table-wrap">
                                            <table class="table-sm table-bordered mobile-item-table" width="100%" style="border-color: #ddd;">
                                                <thead>
                                                    <tr style="background-color:#111827; text-align:center;" class="text-white">
                                                        <th width="10%">Category</th>
                                                        <th width="12%">Barcode</th>
                                                        <th width="15%">Name</th>
                                                        <th width="10%">Qty</th>
                                                        <th width="15%">Cost Price</th>
                                                        <th width="10%">Sell Price</th>
                                                        <th width="10%">Per</th>
                                                        <th width="15%">Total (Ghc)</th>
                                                        <th width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="addRow" class="addRow"></tbody>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="7">Discount</td>
                                                        <td>
                                                            <input type="text" name="discount_amount" id="discount_amount" class="form-control estimated_amount" placeholder="amount">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7">Grand Total</td>
                                                        <td>
                                                            <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
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
                                            <label><strong>Supplier Name <span class="text-danger">*</span></strong></label>
                                            <select name="supplier_id" id="supplier_id" class="form-control" readonly>
                                                <option value="" disabled>Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" 
                                                        {{ $supplier->name === 'cash_purchase' ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                                <option value="0" disabled>New Supplier</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Hidden Add Supplier Form -->
                                    <div class="row new_supplier mt-3 block_div">
                                        <div class="form-group col-md-4">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Supplier Name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Supplier Mobile No" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Supplier Email">
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <center>
                                            <button type="submit" class="btn btn-success storeButton mobile-submit-btn" id="storeButton">Submit Purchase</button>
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

@endsection 

@section('styles')
    <style>
        .block_div {
            display: none;
        }
        /* Ensure main content is not hidden under sidebar */
        /* Remove custom margin, let theme handle sidebar spacing */
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

        .camera-scan-btn {
            border-color: #0f766e;
            color: #0f766e;
        }

        .camera-scan-btn:hover,
        .camera-scan-btn:focus,
        .camera-scan-btn:active {
            background-color: #0f766e !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
        }

        @media (max-width: 767.98px) {
            .mobile-page-title {
                display: block;
                margin-bottom: 10px;
            }

            .mobile-header-action {
                float: none !important;
                display: block;
                width: 100%;
            }

            .mobile-header-btn,
            .mobile-action-btn,
            .mobile-submit-btn {
                width: 100%;
            }

            .mobile-item-table-wrap {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .mobile-item-table {
                min-width: 920px;
            }
        }

        @media (max-width: 575.98px) {
            .page-content .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card-header.with-border {
                padding: 12px 12px 10px;
            }

            .card-body {
                padding: 10px;
            }

            .card-body .row.mb-2,
            .card-body .row.mt-3,
            .card-body .form-row.mt-3,
            .card-body .form-group.mt-4 {
                margin-top: 8px !important;
                margin-bottom: 8px !important;
            }

            .card-body .form-control,
            .card-body .btn,
            .card-body .select2-selection--single {
                min-height: 40px;
            }

            .mobile-item-table th,
            .mobile-item-table td {
                font-size: 12px;
                padding: 0.3rem 0.4rem;
            }

            #description {
                min-height: 88px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://unpkg.com/@zxing/library@0.21.3/umd/index.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function (){
        // Handle form submission
        $('#purchaseForm').on('submit', function(e) {
            e.preventDefault();

            function notify(msg) {
                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Notify.failure(msg, {
                        timeout: 3000,
                        showOnlyTheLastOne: true,
                        position: 'right-top',
                    });
                } else {
                    alert(msg);
                }
            }

            // --- client-side validation (unchanged) ---
            if(!$('#date').val().trim()) { notify('Please enter a date'); return; }
            var addedItemsCount = $('#addRow').children('tr').length;
            if (addedItemsCount === 0) {
                if(!$('#category_id').val()) { notify('Please select a category'); return; }
                if(!$('#product_id').val()) { notify('Please select a product'); return; }
                if(!$('#unit_id').val()) { notify('Please select a unit'); return; }
                notify('Please add at least one purchase item.');
                return;
            }
            var validItems = true;
            $('#addRow tr').each(function() {
                var qty = $(this).find('input.buying_qty').val();
                var price = $(this).find('input.unit_cost_price').val();
                var sellingPrice = $(this).find('input.unit_selling_price').val();
                if (!qty || isNaN(qty) || parseFloat(qty) <= 0) { notify('Each item must have a valid quantity greater than 0.'); validItems = false; return false; }
                if (!price || isNaN(price) || parseFloat(price) <= 0) { notify('Each item must have a valid unit cost price greater than 0.'); validItems = false; return false; }
                if (!sellingPrice || isNaN(sellingPrice) || parseFloat(sellingPrice) <= 0) { notify('Each item must have a valid selling price greater than 0.'); validItems = false; return false; }
                if (parseFloat(sellingPrice) <= parseFloat(price)) { notify('Each item selling price must be greater than its cost price.'); validItems = false; return false; }
            });
            if (!validItems) return;
            if(!$('#paid_status').val()) { notify('Please select a paid status'); return; }
            if(!$('#supplier_id').val()) { notify('Please select a supplier'); return; }
            if($('#paid_status').val() === 'partial_paid') {
                var paidAmount = $('input.paid_amount').val();
                if(!paidAmount || isNaN(paidAmount) || parseFloat(paidAmount) <= 0) {
                    notify('Please enter a valid paid amount greater than 0 for partial payment.');
                    return;
                }
            }
            if($('#supplier_id').val() === '0') {
                var name = $('#name').val();
                var mobile = $('#mobile_no').val();
                var email = $('#email').val();
                if(!name || !name.trim()) { notify('Supplier name is required.'); return; }
                if(!mobile || !/^\d{10}$/.test(mobile)) { notify('Supplier mobile number must be 10 digits.'); return; }
                if(!email || !/^\S+@\S+\.\S+$/.test(email)) { notify('Please enter a valid supplier email.'); return; }
            }

            // Show loading
            if (typeof Notiflix !== 'undefined') {
                Notiflix.Loading.standard('Creating purchase, please wait...');
            } else {
                console.log('Creating purchase...');
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
                        // Clear form fields
                        $('#purchaseForm')[0].reset();
                        $('#addRow').empty();
                        $('#errorAlert').hide();
                        $('#errors').empty();
                        if ($('.select2').length) {
                            $('.select2').val('').trigger('change');
                        }
                        // Directly set next purchase number from backend response
                        if (data.next_no) {
                            $('#purchase_no').val(data.next_no);
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
                            if (xhr.responseText.includes('1062') && xhr.responseText.includes('purchase_no')) {
                                duplicate = true;
                            }
                        }
                        $('#errorAlert').show();
                        if (duplicate) {
                            $('#errors').append('<li>Duplicate purchase number. Please refresh the page and try again.</li>');
                            if (typeof Notiflix !== 'undefined') {
                                Notiflix.Notify.failure('Duplicate purchase number. Please refresh the page and try again.');
                            } else {
                                alert('Duplicate purchase number. Please refresh the page and try again.');
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

    {{-- Handles delete and add item --}}
       <script id="document-template" type="text/x-handlebars-template">
     
        <tr class="delete_add_more_item">
            <input type="hidden" name="date" value="@{{date}}">
            <input type="hidden" name="purchase_no" value="@{{purchase_no}}">
    
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
            </td>


            <td>
                <input type="number" min="1" class="form-control buying_qty text-right" name="buying_qty[]" value=""> 
            </td>

            <td>
                <input type="number" class="form-control unit_cost_price text-right" name="unit_cost_price[]" value=""> 
            </td>

            <td>
                <input type="number" class="form-control unit_selling_price text-right" name="unit_selling_price[]" value=""> 
            </td>

            <td>
                <input type="hidden" name="unit_id[]" value="@{{unit_id}}">
                @{{ unit_name }}
            </td>

            <td>
                <input type="number" class="form-control buying_price text-right" name="buying_price[]" value="" readonly> 
            </td>

            <td>
                <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
            </td>

        </tr>

    </script>

     {{-- handles add more item in purchase --}}
    <script type="text/javascript">
        $(document).ready(function(){
            var cameraStream = null;
            var detector = null;
            var scanRafId = null;
            var zxingReader = null;
            var zxingControls = null;
            var scanLocked = false;

            function notifyScan(message, type) {
                if (typeof Notiflix !== 'undefined') {
                    if (type === 'success') {
                        Notiflix.Notify.success(message);
                    } else if (type === 'warning') {
                        Notiflix.Notify.warning(message);
                    } else {
                        Notiflix.Notify.failure(message);
                    }
                } else {
                    alert(message);
                }
            }

            function triggerBarcodeLookup(scannedBarcode) {
                if (!scannedBarcode) return;
                $('#barcode').val(scannedBarcode).trigger('change');
            }

            function stopCameraScan() {
                if (scanRafId) {
                    cancelAnimationFrame(scanRafId);
                    scanRafId = null;
                }

                if (zxingControls && typeof zxingControls.stop === 'function') {
                    zxingControls.stop();
                }
                zxingControls = null;

                if (zxingReader && typeof zxingReader.reset === 'function') {
                    zxingReader.reset();
                }
                zxingReader = null;

                if (cameraStream) {
                    cameraStream.getTracks().forEach(function(track){ track.stop(); });
                    cameraStream = null;
                }

                var video = document.getElementById('barcodeCameraPreview');
                if (video) {
                    video.pause();
                    video.srcObject = null;
                }

                $('#cameraScanWrap').hide();
                $('#startCameraScan').show();
                $('#stopCameraScan').hide();
                scanLocked = false;
            }

            function onBarcodeScanned(codeText) {
                var cleanCode = $.trim(codeText || '');
                if (!cleanCode || scanLocked) return;

                scanLocked = true;
                triggerBarcodeLookup(cleanCode);
                notifyScan('Barcode scanned from camera', 'success');
                stopCameraScan();
            }

            function startNativeDetector(videoEl) {
                if (!detector) {
                    detector = new BarcodeDetector({
                        formats: ['code_128', 'ean_13', 'ean_8', 'upc_a', 'upc_e', 'code_39', 'qr_code']
                    });
                }

                var detectFrame = function() {
                    if (!cameraStream) return;

                    detector.detect(videoEl)
                        .then(function(codes) {
                            if (codes && codes.length > 0) {
                                onBarcodeScanned(codes[0].rawValue);
                                return;
                            }
                            scanRafId = requestAnimationFrame(detectFrame);
                        })
                        .catch(function() {
                            scanRafId = requestAnimationFrame(detectFrame);
                        });
                };

                detectFrame();
            }

            function startZxingFallback(videoEl) {
                if (!window.ZXing || !window.ZXing.BrowserMultiFormatReader) {
                    notifyScan('Camera scanner fallback is not available in this browser.', 'error');
                    stopCameraScan();
                    return;
                }

                zxingReader = new window.ZXing.BrowserMultiFormatReader();
                zxingReader.decodeFromVideoDevice(null, videoEl, function(result, err, controls) {
                    if (controls) {
                        zxingControls = controls;
                    }

                    if (result) {
                        onBarcodeScanned(result.getText());
                    }
                });
            }

            async function startCameraScan() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    notifyScan('Camera is not supported on this device/browser.', 'error');
                    return;
                }

                if (!(window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
                    notifyScan('Camera scanning requires HTTPS in production.', 'warning');
                    return;
                }

                stopCameraScan();

                try {
                    cameraStream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { ideal: 'environment' } },
                        audio: false
                    });

                    var video = document.getElementById('barcodeCameraPreview');
                    video.srcObject = cameraStream;
                    await video.play();

                    $('#cameraScanWrap').show();
                    $('#startCameraScan').hide();
                    $('#stopCameraScan').show();

                    if ('BarcodeDetector' in window) {
                        startNativeDetector(video);
                    } else {
                        startZxingFallback(video);
                    }
                } catch (error) {
                    notifyScan('Unable to start camera. Please allow camera permission and retry.', 'error');
                    stopCameraScan();
                }
            }

            $(document).on('click', '#startCameraScan', function() {
                startCameraScan();
            });

            $(document).on('click', '#stopCameraScan', function() {
                stopCameraScan();
            });

            $(window).on('beforeunload', function() {
                stopCameraScan();
            });

            $(document).on('keydown', '#barcode', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $(this).trigger('change');
                }
            });

            function resetProductSelectionFields() {
                $('#category_id').val(null).trigger('change');
                $('#product_id').html('<option value="">Select Product</option>').val(null).trigger('change');
                $('#unit_id').html('<option value="">Select Unit</option>').val(null).trigger('change');
                $('#current_stock_qty').val('');
            }

            // ðŸ”¹ Handle barcode scan/entry
            $(document).on("change", "#barcode", function(){
                var barcode = $.trim($(this).val());
                if(!barcode) return;

                $.ajax({
                    url: "{{ route('products.findByBarcode') }}", // lookup route
                    type: "GET",
                    data: { barcode: barcode },
                    success: function(product){
                        if(product && product.id){
                            var date = $('#date').val();
                            var purchase_no = $('#purchase_no').val();

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
                                purchase_no: purchase_no,
                                barcode: product.barcode,          // ðŸ”¹ added
                                category_id: product.category_id,
                                category_name: product.category.name,
                                product_id: product.id,
                                product_name: product.name,
                                unit_id: product.unit_id,
                                unit_name: product.unit.name,
                            };
                            var html = template(data);
                            $("#addRow").append(html);

                            // Clear barcode field after scan
                            $('#barcode').val('');
                            resetProductSelectionFields();

                            // ðŸ”¹ Optional: show stock quantity
                            // $('#current_stock_qty').val(product.quantity);

                            Notiflix.Notify.success('Product added via barcode');
                            $('#barcode').focus();
                        } else {
                            var backendMessage = product && product.message ? product.message : 'Product not found for this barcode.';
                            Notiflix.Notify.failure(backendMessage);
                            $('#barcode').val('');
                            $('#barcode').focus();
                        }
                    },
                    error: function(xhr){
                        var backendMessage = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error looking up barcode. Try again.';
                        Notiflix.Notify.failure(backendMessage);
                        $('#barcode').val('');
                        $('#barcode').focus();
                    }
                });
            });

            // Existing add button flow (manual selection)
            $(document).on("click", ".addeventmore", function(){
                var date = $('#date').val();
                var purchase_no = $('#purchase_no').val(); 
                var category_id  = $('#category_id').val();
                var category_name = $('#category_id').find('option:selected').text();
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();
                var unit_id  = $('#unit_id').val();
                var unit_name = $('#unit_id').find('option:selected').text();

                function notifyPop(msg) {
                    if (typeof Notiflix !== 'undefined') {
                        Notiflix.Notify.failure(msg);
                    } else if (typeof $.notify !== 'undefined') {
                        $.notify(msg, {globalPosition: 'top right', className:'error'});
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
                var template = Handlebars.compile(source);
                var data = {
                    date: date,
                    purchase_no: purchase_no, 
                    category_id: category_id,
                    category_name: category_name,
                    product_id: product_id,
                    product_name: product_name,
                    unit_id: unit_id,
                    unit_name: unit_name,
                    barcode: '' // ðŸ”¹ empty if added manually
                };
                var html = template(data);
                $("#addRow").append(html);
                resetProductSelectionFields();
                $('#barcode').val('').focus();
            });

            // Remove row
            $(document).on("click",".removeeventmore",function(event){
                $(this).closest(".delete_add_more_item").remove();
                totalAmountPrice();
            });

            // Calculate row total
            $(document).on('keyup click','.unit_cost_price,.buying_qty', function(){
                var unit_cost_price = $(this).closest("tr").find("input.unit_cost_price").val();
                var qty = $(this).closest("tr").find("input.buying_qty").val();
                var total = unit_cost_price * qty;
                $(this).closest("tr").find("input.buying_price").val(total);
                $('#discount_amount').trigger('keyup');
            });

            // Discount recalculation
            $(document).on('keyup','#discount_amount',function(){
                totalAmountPrice();
            });

            // Calculate sum of all items
            function totalAmountPrice(){
                var sum = 0;
                $(".buying_price").each(function(){
                    var value = $(this).val();
                    if(!isNaN(value) && value.length != 0){
                        sum += parseFloat(value);
                    }
                });

                var discount_amount = parseFloat($('#discount_amount').val());
                if(!isNaN(discount_amount) && discount_amount.length != 0){
                    sum -= parseFloat(discount_amount);
                }

                $('#estimated_amount').val(sum);
            }  

        });
    </script>
 
    {{-- auto pull product when category selected --}}
    <script type="text/javascript">

        
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
                            html += '<option value="'+v.id+'"> '+v.name+'</option>';
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
                            html += '<option value="'+v.unit_id+'"> '+v.unit.name+'</option>';
                        });
                        $('#unit_id').html(html);
                    }
                })
            });
        });
    </script>

    {{-- get current stock quantity when product selected --}}
    <script type="text/javascript">
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

    {{-- //payment status and customer show or hide --}}
    <script type="text/javascript">
        $(document).on('change','#paid_status', function(){
            var paid_status = $(this).val();
            if (paid_status == 'partial_paid') {
                $('.paid_amount').show();
            }else{
                $('.paid_amount').hide();
            }
        });

        $(document).on('change','#supplier_id', function(){
            var supplier_id = $(this).val();
            if (supplier_id == '0') {
                $('.new_supplier').show();
            }else{
                $('.new_supplier').hide();
            }
        });


    </script>  
@endsection

