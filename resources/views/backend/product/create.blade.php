@extends('layout.main')
@section('title') {{'Create Product'}} @endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.3);
        transform: translateY(-5px);
        border-radius: 20px;
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
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

    .is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-control:focus,
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0F766E;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.2);
    }
</style>

@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header with-border">
                        <span class="card-title" style="font-size:20px;">Create | Product</span>
                        <a href="{{ route('products.index') }}" style="float:right">
                            <button type="button" class="btn btn-primary modal_btn">View Products</button>
                        </a>
                        <hr>
                    </div>
                    
                    <div class="form-div card-body">
                        <form action="{{ route('products.store') }}" method="POST" id="myForm" enctype="multipart/form-data">
                            @csrf
                            <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" style="display: none;">
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                                    <span><i class="mdi mdi-close"></i></span>
                                </button>
                                <ul id="errors"></ul>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input 
                                        name="name" 
                                        label="Product Name" 
                                        :required="true"
                                        placeholder="Enter product name"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select 
                                        name="category_id" 
                                        label="Category"
                                        :options="$categories->pluck('name', 'id')->toArray()"
                                        placeholder="Select Category"
                                        :required="true"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.select 
                                        name="unit_id" 
                                        label="Unit"
                                        :options="$units->pluck('name', 'id')->toArray()"
                                        placeholder="Select Unit"
                                        :required="true"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input 
                                        name="tags" 
                                        label="Tags"
                                        placeholder="Add tags separated by comma"
                                        help="e.g., new, sale, popular, trending"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-form.number 
                                        name="stock_level" 
                                        label="Low Stock Threshold"
                                        :value="1"
                                        :min="0"
                                        help="Alert for restock when stock reaches this level. Default is 1"
                                    />
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success mt-3">
                                        <i class="fas fa-save"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#myForm").on('submit', function(e) {
            e.preventDefault();

            $('.error-text').html('');
            $('.is-invalid').removeClass('is-invalid');

            if (!validateProductForm()) {
                return;
            }

            ajax(
                $(this).attr('action'),
                new FormData(this),
                {
                    element: 'form-div',
                    showLoading: true,
                    showGlobalError: true,
                    scrollToError: true,
                    success: function(response) {
                        if (response.status === 'success' || response.status === 200) {
                            $('#myForm')[0].reset();
                            $('#category_id').val('').trigger('change');
                            $('#unit_id').val('').trigger('change');

                            Notiflix.Notify.success(response.message || 'Product created successfully!');
                        } else {
                            Notiflix.Notify.warning(response.message || 'Failed to create product');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Product creation error:', error);
                    }
                }
            );
        });

        function validateProductForm() {
            let isValid = true;

            clearAllErrors({ errorSelector: '.error-text', inputErrorClass: 'is-invalid' });

            const productName = $('input[name="name"]').val();
            if (!productName || productName.trim() === '') {
                showFieldError('name', 'Please enter a product name');
                isValid = false;
            }

            const categoryId = $('#category_id').val();
            if (!categoryId) {
                showFieldError('category_id', 'Please select a category');
                isValid = false;
            }

            const unitId = $('#unit_id').val();
            if (!unitId) {
                showFieldError('unit_id', 'Please select a unit');
                isValid = false;
            }

            const lowStockThreshold = $('input[name="stock_level"]').val();
            if (lowStockThreshold && lowStockThreshold < 0) {
                showFieldError('stock_level', 'Low stock threshold cannot be negative');
                isValid = false;
            }

            if (!isValid) {
                Notiflix.Notify.warning('Please correct the errors before submitting');
                scrollToFirstError('is-invalid');
            }

            return isValid;
        }

        function showFieldError(fieldName, errorMessage) {
            const field = $('#' + fieldName + ', [name="' + fieldName + '"]').first();
            field.addClass('is-invalid');

            let errorElement = field.parent().find('.error-text');
            if (errorElement.length === 0) {
                errorElement = $('<small class="error-text text-danger"></small>');
                field.after(errorElement);
            }
            errorElement.html(errorMessage);
        }

        function clearAllErrors(options) {
            const defaults = {
                errorSelector: '.error-text',
                inputErrorClass: 'is-invalid'
            };
            const settings = {...defaults, ...options};

            $(settings.errorSelector).html('');
            $('.' + settings.inputErrorClass).removeClass(settings.inputErrorClass);
        }

        function scrollToFirstError(errorClass) {
            const firstError = $('.' + errorClass).first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
                firstError.focus();
            }
        }

        $('input, select, textarea').on('change blur', function() {
            $(this).removeClass('is-invalid');
            $(this).parent().find('.error-text').html('');
        });
    });
    </script>
@endsection