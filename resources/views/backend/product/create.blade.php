@extends('layout.main')
@section('title') {{'Create Product'}} @endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .card:hover {
        box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.3);
        transform: translateY(-5px);
    }
    .section-title {
        background: #0D85CF;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .section-title i {
        margin-right: 10px;
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
                        <span class="card-title" style="font-size:20px;">Create Product</span>
                        <a href="{{route('products.index')}}" style="float:right">
                            <button type="button" class="btn btn-primary modal_btn">View Products</button>
                        </a>
                    </div>
                    
                    <div class="form-div card-body">
                        <form action="{{ route('products.store') }}" method="POST" id="myForm" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Section 1: Basic Information -->
                            <div class="section-title">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <x-form.input 
                                        name="name" 
                                        label="Product Name" 
                                        :required="true"
                                        placeholder="Enter product name"
                                    />
                                    
                                    <x-form.textarea 
                                        name="description" 
                                        label="Full Description" 
                                        :editor="true"
                                        rows="8"
                                    />
                                </div>
                                
                                <div class="col-md-4">
                                    <x-form.select 
                                        name="category_id" 
                                        label="Category"
                                        :options="$categories->pluck('name', 'id')->toArray()"
                                        placeholder="Select Category"
                                        :required="true"

                                    />
                                    
                                    <x-form.select 
                                        name="brand_id" 
                                        label="Brand"
                                        :options="$brands->pluck('name', 'id')->toArray()"
                                        placeholder="Select Brand"
                                    />
                                    
                                    <x-form.select 
                                        name="unit_id" 
                                        label="Unit"
                                        :options="$units->pluck('name', 'id')->toArray()"
                                        placeholder="Select Unit"
                                        :required="true"
                                    />
                                    
                                    <x-form.input 
                                        name="tags" 
                                        label="Tags"
                                        placeholder="Add tags separated by comma"
                                        help="e.g., new, sale, popular, trending"
                                    />
                                </div>
                            </div>

                            <hr>

                            <!-- Section 2: Stock Information -->
                            <div class="section-title">
                                <i class="fas fa-boxes"></i> Stock Information
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.number 
                                        name="stock_level" 
                                        label="Low Stock Threshold"
                                        :value="1"
                                        :min="0"
                                        help="Alert for restock when stock reaches this level. Default is 1"
                                    />
                                    
                                    <x-form.number 
                                        name="min_order_quantity" 
                                        label="Minimum Order Quantity"
                                        :value="1"
                                        :min="1"
                                        help="Minimum order for online shop"
                                    />
                                    
                                    <x-form.number 
                                        name="max_order_quantity" 
                                        label="Maximum Order Quantity"
                                        placeholder="Unlimited"
                                        help="Maximum order for online shop"
                                    />
                                </div>
                                
                                <div class="col-md-6">
                                    <x-form.number 
                                        name="weight" 
                                        label="Weight (kg)"
                                        step="0.01"
                                        :min="0"
                                    />
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <x-form.number 
                                                name="length" 
                                                label="Length (cm)"
                                                step="0.01"
                                                :min="0"
                                            />
                                        </div>
                                        <div class="col-md-4">
                                            <x-form.number 
                                                name="width" 
                                                label="Width (cm)"
                                                step="0.01"
                                                :min="0"
                                            />
                                        </div>
                                        <div class="col-md-4">
                                            <x-form.number 
                                                name="height" 
                                                label="Height (cm)"
                                                step="0.01"
                                                :min="0"
                                            />
                                        </div>
                                    </div>
                                    
                                    <x-form.select 
                                        name="product_color_id" 
                                        label="Color"
                                        :options="$colors->pluck('name', 'id')->toArray() ?? []"
                                        placeholder="Select Color"
                                        help="Product color/variant"
                                    />
                                </div>
                            </div>

                            <hr>

                            <!-- Section 3: Images -->
                            <div class="section-title">
                                <i class="fas fa-images"></i> Product Images
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><i class="fas fa-star text-warning"></i> Featured Image</h4>
                                        </div>
                                        <div class="card-body">
                                            <x-uppy-file-input
                                                input-wrapper-class="col-lg-12 col-md-12"
                                                name="featured_image"
                                                label="Featured Image"
                                                :multiple="false"
                                                :maxFiles="1"
                                                :onlyPdf="false"
                                                :onlyImages="true"
                                                note="1 image file only, up to 4 MB. Recommended size: 600x600px"
                                                :enableWebcam="false"
                                                :enableImageEditor="true"
                                                :compress="true"
                                                :required="true"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><i class="fas fa-images text-primary"></i> Gallery Images</h4>
                                        </div>
                                        <div class="card-body">
                                            <x-uppy-file-input
                                                input-wrapper-class="col-lg-12 col-md-12"
                                                name="gallery_images"
                                                label="Gallery Images"
                                                :multiple="true"
                                                :maxFiles="2"
                                                :onlyPdf="false"
                                                :onlyImages="true"                                                        
                                                note="Up to 2 images, up to 4 MB each. Recommended size: 600x600px"
                                                :enableWebcam="false"
                                                :enableImageEditor="true"
                                                :compress="true"
                                                :required="false"
                                            />
                                        </div>
                                    </div>
                                </div>
                         
                            
                      

                            <hr>

                            <!-- Section 4: Settings -->
                            <div class="section-title">
                                <i class="fas fa-cog"></i> Product Settings
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Product Status</h4>
                                        </div>
                                        <div class="card-body">
                                            {{-- <x-form.switch-toggle 
                                                name="is_active" 
                                                label="Active"
                                                :checked="true"
                                                help="(Visible in admin panel)"
                                            /> --}}
                                            
                                            <x-form.switch-toggle 
                                                name="is_online" 
                                                label="Show on Website"
                                                :checked="true"
                                                help="(Visible to customers)"
                                                on-text="Enabled"
                                                off-text="Disabled"
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Product Badges</h4>
                                        </div>
                                        <div class="card-body">
                                            <x-form.switch-toggle 
                                            name="is_featured" 
                                            label="Featured Product"
                                            :checked="false"
                                            help="(Shows on homepage)"
                                            on-text="Enabled"
                                            off-text="Disabled"
                                        />
                                            
                                          
                                            
                                            <x-form.switch-toggle 
                                                name="is_new" 
                                                label="New Arrival"
                                                help='(Shows "New" badge)'
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Product
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        
        // Setup AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize Summernote editor
        $('.editor').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Form submission using custom ajax function
        $("#myForm").on('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            $('.error-text').html('');
            $('.is-invalid').removeClass('is-invalid');
                // Check featured image upload status
                let featuredImageStatus = { hasFiles: false, isUploading: false, isComplete: false };
                
                // Check featured image Uppy instance
                if (typeof uppy_featured_image !== 'undefined' && uppy_featured_image) {
                    
                    const featuredFiles = uppy_featured_image.getFiles();
                    featuredImageStatus.hasFiles = featuredFiles.length > 0;
                    
                    if (featuredImageStatus.hasFiles) {
                        // Check if all files are completely uploaded
                        featuredImageStatus.isComplete = featuredFiles.every(file => 
                            file.progress && (file.progress.uploadComplete || file.progress.uploadProgress === 100)
                        );
                        
                        // Check if still uploading
                        featuredImageStatus.isUploading = featuredFiles.some(file => 
                            file.progress && file.progress.uploadProgress && file.progress.uploadProgress < 100 && !file.progress.uploadComplete
                        );
                        
                        // If files exist but not complete and not uploading (meaning they were added but not uploaded)
                        const hasPendingUpload = featuredFiles.some(file => 
                            !file.progress || (!file.progress.uploadComplete && (!file.progress.uploadProgress || file.progress.uploadProgress === 0))
                        );
                        
                        if (hasPendingUpload) {
                            showFieldError('featured_image', 'Please click "Upload" button for the featured image before submitting');
                            Notiflix.Notify.warning('Featured image files are added but not uploaded. Please upload them first.');
                            document.getElementById('featured_image')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                        
                        if (featuredImageStatus.isUploading) {
                            showFieldError('featured_image', 'Please wait for featured image to finish uploading');
                            Notiflix.Notify.warning('Featured image is still uploading. Please wait...');
                            return;
                        }
                    }
                }
                
                // Check if featured image exists and is uploaded
                if (!featuredImageStatus.hasFiles || !featuredImageStatus.isComplete) {
                    showFieldError('featured_image', 'Please upload a featured image for the product');
                    Notiflix.Notify.warning('Please upload a featured image');
                    document.getElementById('featured_image')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                
                // Check gallery images - ONLY if files have been added/dropped
                let galleryImageStatus = { hasFiles: false, isUploading: false, isComplete: false, hasPendingUpload: false };
                
                if (typeof uppy_gallery_images !== 'undefined' && uppy_gallery_images) {

                    console.log("Fs");

                    const galleryFiles = uppy_gallery_images.getFiles();
                    galleryImageStatus.hasFiles = galleryFiles.length > 0;
                    console.log("Fs");

                    if (galleryImageStatus.hasFiles) {

                        
                        // Check if all files are completely uploaded
                        galleryImageStatus.isComplete = galleryFiles.every(file => 
                            file.progress && (file.progress.uploadComplete || file.progress.uploadProgress === 100)
                        );
                        
                        // Check if still uploading
                        galleryImageStatus.isUploading = galleryFiles.some(file => 
                            file.progress && file.progress.uploadProgress && file.progress.uploadProgress < 100 && !file.progress.uploadComplete
                        );
                        
                        // Check if files were added but not uploaded (no progress or not started)
                        galleryImageStatus.hasPendingUpload = galleryFiles.some(file => 
                            !file.progress || (!file.progress.uploadComplete && (!file.progress.uploadProgress || file.progress.uploadProgress === 0))
                        );
                        
                        // If files exist but not uploaded (pending)
                        if (galleryImageStatus.hasPendingUpload) {
                            showFieldError('gallery_images', 'Please click "Upload" button for gallery images before submitting');
                            Notiflix.Notify.warning('Gallery images are added but not uploaded. Please upload them first.');
                            document.getElementById('gallery_images')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                        
                        // If files are currently uploading
                        if (galleryImageStatus.isUploading) {
                            showFieldError('gallery_images', 'Please wait for gallery images to finish uploading');
                            Notiflix.Notify.warning('Gallery images are still uploading. Please wait...');
                            return;
                        }
                    }
                }
            // Client-side validation
            if (!validateProductForm()) {
                return;
            }
            
            // Make AJAX request using reusable function
            ajax(
                $(this).attr('action'),           // URL
                new FormData(this),                // Data
                {
                    element: 'form-div',           // Loading element
                    showLoading: true,
                    showGlobalError: true,
                    scrollToError: true,
                    success: function(response) {
                        if (response.status === 'success' || response.status === 200) {
                            $('#myForm')[0].reset();
                            if (typeof uppy_featured_image !== 'undefined') {
                                const files = uppy_featured_image.getFiles();
                                files.forEach(file => {
                                    uppy_featured_image.removeFile(file.id);
                                });
                            }

                            if (typeof uppy_gallery_images !== 'undefined') {
                                const files = uppy_gallery_images.getFiles();
                                files.forEach(file => {
                                    uppy_gallery_images.removeFile(file.id);
                                });
                            }
                            $('#category_id').val('').trigger('change');
                            $('#unit_id').val('').trigger('change');
                            $('#brand_id').val('').trigger('change');
                            $('#product_color_id').val('').trigger('change');

                            // Reset Summernote content
                            $('.editor').summernote('code', '');
                            
                            Notiflix.Notify.success(response.message || 'Product created successfully!');
                            // setTimeout(() => {
                            //     window.location.href = response.redirect || "{{ route('products.index') }}";
                            // }, 2000);
                        } else {
                            Notiflix.Notify.warning(response.message || 'Failed to create product');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Product creation error:', error);
                        // The reusable function already handles validation errors
                    }
                }
            );
        });
        
        // Product form specific validation
        function validateProductForm() {
            let isValid = true;
            
            // Clear previous errors
            clearAllErrors({ errorSelector: '.error-text', inputErrorClass: 'is-invalid' });
            
            // Validate product name
            const productName = $('input[name="name"]').val();
            if (!productName || productName.trim() === '') {
                showFieldError('name', 'Please enter a product name');
                console.log("1");

                isValid = false;
            }
            
            const categoryId = $('#category_id').val();
            if (!categoryId) {
                showFieldError('category_id', 'Please select a category');
                console.log("2");
                
                isValid = false;
            }
            // Validate unit
            const unitId = $('#unit_id').val();
            if (!unitId) {
                showFieldError('unit_id', 'Please select a unit');
                console.log("3");

                isValid = false;
            }
            // Featured image validation - double check
            if (typeof uppy_featured_image !== 'undefined' && uppy_featured_image) {
                const featuredFiles = uppy_featured_image.getFiles();
                if (featuredFiles.length === 0) {
                    showFieldError('featured_image', 'Please upload a featured image for the product');
                    console.log("4");

                    isValid = false;
                } else {
                    const allComplete = featuredFiles.every(file => 
                        file.progress && (file.progress.uploadComplete || file.progress.uploadProgress === 100)
                    );
                    if (!allComplete) {
                        showFieldError('featured_image', 'Please ensure featured image is fully uploaded');
                        console.log("4");

                        isValid = false;
                    }
                }
            } else {
                showFieldError('featured_image', 'Please upload a featured image for the product');
                console.log("4");

                isValid = false;
            }

            // Gallery image validation - only validate if files exist
            if (typeof uppy_gallary_images !== 'undefined' && uppy_gallary_images) {
                const galleryFiles = uppy_gallary_images.getFiles();
                if (galleryFiles.length > 0) {
                    // Check if any file is not uploaded
                    const allComplete = galleryFiles.every(file => 
                        file.progress && (file.progress.uploadComplete || file.progress.uploadProgress === 100)
                    );
                    
                    const hasPending = galleryFiles.some(file => 
                        !file.progress || (!file.progress.uploadComplete && (!file.progress.uploadProgress || file.progress.uploadProgress === 0))
                    );
                    
                    if (hasPending) {
                        showFieldError('gallery_images', 'Please upload the gallery images before submitting');
                        console.log("5");

                        isValid = false;
                    } else if (!allComplete) {
                        showFieldError('gallery_images', 'Please wait for gallery images to finish uploading');
                        console.log("5");

                        isValid = false;
                    }
                }
            }


            // Validate low stock threshold (if provided)
            const lowStockThreshold = $('input[name="stock_level"]').val();
            if (lowStockThreshold && lowStockThreshold < 0) {
                showFieldError('stock_level', 'Low stock threshold cannot be negative');
                console.log("6");

                isValid = false;
            }
            
            // Validate min order quantity
            const minOrderQty = $('input[name="min_order_quantity"]').val();
            if (minOrderQty && minOrderQty < 1) {
                showFieldError('min_order_quantity', 'Minimum order quantity must be at least 1');
                console.log("7");

                isValid = false;
            }
            
            // Validate max order quantity (if provided)
            const maxOrderQty = $('input[name="max_order_quantity"]').val();
            if (maxOrderQty && minOrderQty && parseInt(maxOrderQty) < parseInt(minOrderQty)) {
                showFieldError('max_order_quantity', 'Maximum order quantity cannot be less than minimum order quantity');
                console.log("8");

                isValid = false;
            }
            
            // Validate weight (if provided)
            const weight = $('input[name="weight"]').val();
            if (weight && weight < 0) {
                showFieldError('weight', 'Weight cannot be negative');
                console.log("9");

                isValid = false;
            }
            
            // Validate dimensions
            const length = $('input[name="length"]').val();
            const width = $('input[name="width"]').val();
            const height = $('input[name="height"]').val();
            
            if (length && length < 0) {
                showFieldError('length', 'Length cannot be negative');
                console.log("10");

                isValid = false;
            }
            
            if (width && width < 0) {
                showFieldError('width', 'Width cannot be negative');
                console.log("10");

                isValid = false;
            }
            
            if (height && height < 0) {
                showFieldError('height', 'Height cannot be negative');
                console.log("10");

                isValid = false;
            }
            
            if (!isValid) {
                Notiflix.Notify.warning('Please correct the errors before submitting');
                scrollToFirstError('is-invalid');
            }
            
            return isValid;
        }
        
        // Helper function to show field errors
        function showFieldError(fieldName, errorMessage) {
            const field = $('#' + fieldName + ', [name="' + fieldName + '"]').first();
            field.addClass('is-invalid');
            
            // Check if error element exists, if not create it
            let errorElement = field.parent().find('.error-text');
            if (errorElement.length === 0) {
                errorElement = $('<small class="error-text text-danger"></small>');
                field.after(errorElement);
            }
            errorElement.html(errorMessage);
        }
        
        // Helper function to clear all errors
        function clearAllErrors(options) {
            const defaults = {
                errorSelector: '.error-text',
                inputErrorClass: 'is-invalid'
            };
            const settings = {...defaults, ...options};
            
            $(settings.errorSelector).html('');
            $('.' + settings.inputErrorClass).removeClass(settings.inputErrorClass);
        }
        
        // Helper function to scroll to first error
        function scrollToFirstError(errorClass) {
            const firstError = $('.' + errorClass).first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
                firstError.focus();
            }
        }
        
        // Optional: Real-time validation triggers
        $('input, select, textarea').on('change blur', function() {
            $(this).removeClass('is-invalid');
            $(this).parent().find('.error-text').html('');
        });
        
    });
    </script>
@endsection