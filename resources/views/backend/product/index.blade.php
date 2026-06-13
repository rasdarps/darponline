{{-- filepath: c:\xampp\htdocs\rictPOS\resources\views\backend\product\index.blade.php --}}
@php
use Illuminate\Support\Facades\Crypt;
@endphp

@extends('layout.main')
@section('title') {{'View Product'}} @endsection

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header with-border d-flex justify-content-between align-items-center">
                        <span class="card-title" style="font-size:20px;">
                            <i class="fas fa-boxes"></i> Products Management
                        </span>
                        <div>
                            <a href="{{route('products.create')}}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Product
                            </a>
                            <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
                                <i class="fas fa-trash"></i> Bulk Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select id="filter-category" class="form-control select2">
                                            <option value="">All</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select id="filter-status" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Online Status</label>
                                        <select id="filter-online" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">On Website</option>
                                            <option value="0">Hidden</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stock Status</label>
                                        <select id="filter-stock" class="form-control">
                                            <option value="">All</option>
                                            <option value="in_stock">In Stock</option>
                                            <option value="low_stock">Low Stock</option>
                                            <option value="out_of_stock">Out of Stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Featured</label>
                                        <select id="filter-featured" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Featured</option>
                                            <option value="0">Not Featured</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-filter btn-block" id="apply-filters">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Summary -->
                        <div id="filter-summary" class="filter-summary">
                            <i class="fas fa-info-circle"></i> Active Filters:
                            <span id="filter-summary-text"></span>
                            <button class="btn btn-sm btn-link" id="clear-filters">Clear All</button>
                        </div>
                        
                        <!-- Products Table -->
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="productViewModal" tabindex="-1" role="dialog" aria-labelledby="productViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productViewModalLabel">
                    <i class="fas fa-box"></i> Product Details
                </h5>
                <a type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body" id="product-details-content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading product details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{ $dataTable->scripts() }}
    <script>
    // ============ DEFINE MISSING FUNCTION FIRST ============
    window.addFilterSummary = function() {
        console.log('DataTable initialized - setting up filters');
        // Initialize all toggle functions after DataTable loads
        if (typeof initializeToggles === 'function') {
            initializeToggles();
            initializeFeaturedToggles();
            initializeDeleteButtons();
            initializeBulkActions();
        }
        updateFilterSummary();
    };
    
    $(document).ready(function() {
        // Initialize Select2
       
        
        // Global AJAX setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // ============ DEFINE ALL FUNCTIONS GLOBALLY ============
        
        // Update filter summary
        window.updateFilterSummary = function() {
            var filters = [];
            
            // Get current filter values
            var categoryId = $('#filter-category').val();
            var status = $('#filter-status').val();
            var online = $('#filter-online').val();
            var stock = $('#filter-stock').val();
            var featured = $('#filter-featured').val();
            
            // Get selected text for display
            var categoryName = $('#filter-category option:selected').text();
            var statusText = $('#filter-status option:selected').text();
            var onlineText = $('#filter-online option:selected').text();
            var stockText = $('#filter-stock option:selected').text();
            var featuredText = $('#filter-featured option:selected').text();
            
            // Build filter badges
            if (categoryId && categoryId !== '') {
                filters.push('<span class="badge badge-primary mr-1 mb-1">Category: ' + categoryName + '</span>');
            }
            if (status && status !== '') {
                var statusClass = status == '1' ? 'badge-success' : 'badge-danger';
                filters.push('<span class="badge ' + statusClass + ' mr-1 mb-1">Status: ' + statusText + '</span>');
            }
            if (online && online !== '') {
                var onlineClass = online == '1' ? 'badge-success' : 'badge-secondary';
                filters.push('<span class="badge ' + onlineClass + ' mr-1 mb-1">Online: ' + onlineText + '</span>');
            }
            if (stock && stock !== '') {
                var stockClass = '';
                if (stock == 'in_stock') stockClass = 'badge-success';
                else if (stock == 'low_stock') stockClass = 'badge-warning';
                else if (stock == 'out_of_stock') stockClass = 'badge-danger';
                filters.push('<span class="badge ' + stockClass + ' mr-1 mb-1">Stock: ' + stockText + '</span>');
            }
            if (featured && featured !== '') {
                var featuredClass = featured == '1' ? 'badge-info' : 'badge-secondary';
                filters.push('<span class="badge ' + featuredClass + ' mr-1 mb-1">Featured: ' + featuredText + '</span>');
            }
            
            // Update the summary display
            if (filters.length > 0) {
                $('#filter-summary-text').html(filters.join(' '));
                $('#filter-summary').show();
            } else {
                $('#filter-summary').hide();
            }
        };
        
        // Apply filters
        window.applyFilters = function() {

                var table = $('#product-table');
                if (!table) {
                console.error('DataTable not initialized yet');
                return;
            }

            table.on('preXhr.dt', function(e,settings,data){
                data.category_id = $('#filter-category').val() || '';
                data.status = $('#filter-status').val() || '';
                data.online_status = $('#filter-online').val() || '';
                data.stock_filter = $('#filter-stock').val() || '';
                data.featured = $('#filter-featured').val() || '';
            
            });
            
          
          console.log("Sdf");
          
            
            // Reload table with new URL
            table.DataTable().ajax.reload();
            
            // Update filter summary
            updateFilterSummary();
        };
        
        // Initialize status toggles
        window.initializeToggles = function() {
            $('.toggle-status').off('change').on('change', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                
                Notiflix.Loading.pulse('Updating...');
                
                $.ajax({
                    url: '{{ route("products.toggle-status") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        status: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Notiflix.Loading.remove();
                        if (response.status === 'success') {
                            Notiflix.Notify.success('Product status updated');
                        } else {
                            checkbox.prop('checked', !isChecked);
                            Notiflix.Notify.failure(response.message || 'Failed to update');
                        }
                    },
                    error: function() {
                        Notiflix.Loading.remove();
                        checkbox.prop('checked', !isChecked);
                        Notiflix.Notify.failure('Failed to update status');
                    }
                });
            });
            
            // Online toggle
            $('.toggle-online').off('change').on('change', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                
                Notiflix.Loading.pulse('Updating...');
                
                $.ajax({
                    url: '{{ route("products.toggle-online") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        status: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Notiflix.Loading.remove();
                        if (response.status === 'success') {
                            Notiflix.Notify.success('Online visibility updated');
                            var icon = checkbox.closest('.custom-control').find('i');
                            if (isChecked) {
                                icon.removeClass('fa-eye-slash text-muted').addClass('fa-globe text-success');
                            } else {
                                icon.removeClass('fa-globe text-success').addClass('fa-eye-slash text-muted');
                            }
                        } else {
                            checkbox.prop('checked', !isChecked);
                            Notiflix.Notify.failure(response.message || 'Failed to update');
                        }
                    },
                    error: function() {
                        Notiflix.Loading.remove();
                        checkbox.prop('checked', !isChecked);
                        Notiflix.Notify.failure('Failed to update online status');
                    }
                });
            });
        };
        
        // Initialize featured toggles
        window.initializeFeaturedToggles = function() {
            $('.toggle-featured').off('change').on('change', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                
                Notiflix.Loading.pulse('Updating...');
                
                $.ajax({
                    url: '{{ route("products.toggle-featured") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        status: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Notiflix.Loading.remove();
                        if (response.status === 'success') {
                            Notiflix.Notify.success('Featured status updated');
                        } else {
                            checkbox.prop('checked', !isChecked);
                            Notiflix.Notify.failure(response.message || 'Failed to update');
                        }
                    },
                    error: function() {
                        Notiflix.Loading.remove();
                        checkbox.prop('checked', !isChecked);
                        Notiflix.Notify.failure('Failed to update featured status');
                    }
                });
            });
        };
        
        // Initialize delete buttons
        window.initializeDeleteButtons = function() {
            $('.delete-product').off('click').on('click', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                Notiflix.Confirm.show(
                    'Delete Product',
                    `Are you sure you want to delete "${name}"? This action cannot be undone.`,
                    'Yes, Delete',
                    'Cancel',
                    function() {
                        Notiflix.Loading.pulse('Deleting product...');
                        
                        $.ajax({
                            url: '{{ route("products.destroy", "") }}/' + id,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Notiflix.Loading.remove();
                                if (response.status === 'success') {
                                    Notiflix.Notify.success('Product deleted successfully');
                                    window.LaravelDataTables['products-table'].ajax.reload();
                                } else {
                                    Notiflix.Notify.failure(response.message || 'Failed to delete product');
                                }
                            },
                            error: function() {
                                Notiflix.Loading.remove();
                                Notiflix.Notify.failure('Failed to delete product');
                            }
                        });
                    }
                );
            });
        };
        
        // Initialize bulk actions
        window.initializeBulkActions = function() {
            // Select all checkbox
            $('#select-all').off('change').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkDeleteButton();
            });
            
            // Individual checkbox change
            $(document).on('change', '.product-checkbox', function() {
                updateBulkDeleteButton();
            });
            
            // Bulk delete button
            $('#bulk-delete-btn').off('click').on('click', function() {
                var selectedIds = [];
                $('.product-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                
                if (selectedIds.length === 0) {
                    Notiflix.Notify.warning('Please select products to delete');
                    return;
                }
                
                Notiflix.Confirm.show(
                    'Bulk Delete',
                    `Are you sure you want to delete ${selectedIds.length} products? This action cannot be undone.`,
                    'Yes, Delete',
                    'Cancel',
                    function() {
                        Notiflix.Loading.pulse('Deleting products...');
                        
                        $.ajax({
                            url: '{{ route("products.bulk-delete") }}',
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Notiflix.Loading.remove();
                                if (response.status === 'success') {
                                    Notiflix.Notify.success(response.message);
                                    window.LaravelDataTables['products-table'].ajax.reload();
                                } else {
                                    Notiflix.Notify.failure(response.message || 'Failed to delete products');
                                }
                            },
                            error: function() {
                                Notiflix.Loading.remove();
                                Notiflix.Notify.failure('Failed to delete products');
                            }
                        });
                    }
                );
            });
        };
        
        // Update bulk delete button visibility
        window.updateBulkDeleteButton = function() {
            var selectedCount = $('.product-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#bulk-delete-btn').show().html(`<i class="fas fa-trash"></i> Delete Selected (${selectedCount})`);
            } else {
                $('#bulk-delete-btn').hide();
            }
        };
        
        // Add CSS for filter badges
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .filter-summary {
                    background: #f8f9fa;
                    padding: 10px 15px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                    border-left: 3px solid #007bff;
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 8px;
                }
                .filter-summary .badge {
                    padding: 5px 8px;
                    font-size: 12px;
                    margin-right: 5px;
                }
                .filter-summary .btn-link {
                    padding: 0;
                    margin-left: 10px;
                }
                .badge-primary { background-color: #007bff; color: white; }
                .badge-success { background-color: #28a745; color: white; }
                .badge-danger { background-color: #dc3545; color: white; }
                .badge-warning { background-color: #ffc107; color: #212529; }
                .badge-info { background-color: #17a2b8; color: white; }
                .badge-secondary { background-color: #6c757d; color: white; }
            `)
            .appendTo('head');
        
        // View product details
        $(document).on('click', '.view-product', function(e) {
            e.stopPropagation();
            var productId = $(this).data('id');
            if (productId) {
                loadProductDetails(productId);
            }
        });      
        
        // Load product details for quick view
        window.loadProductDetails = function(id) {
            if (!id) {
                console.error('No product ID provided');
                return;
            }
            
            Notiflix.Loading.pulse('Loading product details...');
            
            var url = "{{ route('products.show', ':id') }}";
            url = url.replace(':id', id);
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        $('#product-details-content').html(response.html);
                        $('#productViewModal').modal({
                            backdrop: 'static',
                            keyboard: true
                        }).modal('show');
                    } else {
                        Notiflix.Notify.failure(response.message || 'Failed to load product details');
                    }
                },
                error: function(xhr) {
                    Notiflix.Loading.remove();
                    console.error('AJAX Error:', xhr);
                    
                    if (xhr.status === 404) {
                        Notiflix.Notify.failure('Product not found');
                    } else if (xhr.status === 500) {
                        Notiflix.Notify.failure('Server error. Please try again.');
                    } else {
                        Notiflix.Notify.failure('Failed to load product details. Please try again.');
                    }
                }
            });
        };
        
        // ============ EVENT HANDLERS ============
        
        // Apply filters button
        $('#apply-filters').on('click', function() {
            applyFilters();
        });
        
        // Clear filters button
        $('#clear-filters').on('click', function() {
            $('#filter-category').val('').trigger('change');
            $('#filter-status').val('');
            $('#filter-online').val('');
            $('#filter-stock').val('');
            $('#filter-featured').val('');
            applyFilters();
        });
        
        // Make product row clickable for quick view
        $(document).on('click', '#products-table tbody tr', function(e) {
            // Don't trigger if clicking on buttons, checkboxes, or switches
            if ($(e.target).is('button') || 
                $(e.target).is('input') || 
                $(e.target).closest('button').length || 
                $(e.target).closest('.custom-control').length ||
                $(e.target).closest('.btn-group').length) {
                return;
            }
            
            var productId = $(this).find('.delete-product').data('id');
            
            if (!productId) {
                productId = $(this).find('[data-id]').first().data('id');
            }
            
            if (productId) {
                console.log('Loading product details for ID:', productId);
                loadProductDetails(productId);
            } else {
                console.warn('No product ID found for row:', this);
            }
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl + N: Create new product
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route("products.create") }}';
            }
            // Ctrl + F: Focus on search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                $('.dataTables_filter input').focus();
            }
        });
        
        // Initialize DataTable event handlers after table is loaded
        if (window.LaravelDataTables && window.LaravelDataTables['products-table']) {
            window.LaravelDataTables['products-table'].on('draw.dt', function() {
                initializeToggles();
                initializeFeaturedToggles();
                initializeDeleteButtons();
                initializeBulkActions();
                updateFilterSummary();
            });
        }
        
        // Initial setup
        setTimeout(function() {
            initializeToggles();
            initializeFeaturedToggles();
            initializeDeleteButtons();
            initializeBulkActions();
            updateFilterSummary();
        }, 500);
    });
    </script>
@endsection