@extends('layout.main')
@section('title') {{'View Unit'}} @endsection
@section('styles')
    <style>
        .custom-control-switch {
            display: inline-block;
        }

        .status-text {
            margin-left: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Success highlight animation */
        .bg-success-light {
            background-color: #d4edda !important;
            transition: background-color 0.3s ease;
        }

        /* Description preview styling */
        .description-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .description-preview {
            font-size: 13px;
            color: #666;
            flex: 1;
        }

        .view-description-btn {
            white-space: nowrap;
            padding: 2px 8px;
            font-size: 12px;
        }

        .view-description-btn i {
            margin-right: 3px;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background: #034141;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-header .close {
            color: white;
            opacity: 1;
        }

        .modal-header .close:hover {
            color: white;
            opacity: 0.8;
        }

        .description-content {
            margin-top: 15px;
        }

        .description-content label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .well {
            background-color: #f8f9fa;
            border-left: 4px solid #034141;
            padding: 15px;
            border-radius: 5px;
            line-height: 1.6;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Toggle switch styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* DataTable custom styling */
        #category-table_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 5px 15px;
        }

        #category-table tbody tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .description-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .view-description-btn {
                margin-top: 5px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Category All</h4>         
                    </div>
                </div>
            </div>
            <!-- end page title -->
                        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('categories.create') }}" class="btn btn-dark" style="float:right;"><i class="fas fa-plus-circle"> Add Category </i></a> <br>  <br>               

                            <h4 class="card-title">Category All Data </h4>
                            
                            <div class="table-responsive">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->                    
        </div> <!-- container-fluid -->
    </div>
 
@endsection
@section('scripts')
    {{ $dataTable->scripts() }}

    <script>

        $(document).ready(function() {
            initializeToggles();
            initializeDescriptionModals();
        });
        // Function to initialize all toggle switches
        function initializeToggles() {
            $('.toggle-website-status').off('change').on('change', function() {
               

                var checkbox = $(this);
                var categoryId = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                var statusText = checkbox.closest('.custom-control').find('.status-text');
                
                // Show loading state
                Notiflix.Loading.pulse('Updating...');
                
                // Make AJAX request to update status
                $.ajax({
                    url: '/admin/categories/update-website-status',
                    type: 'POST',
                    data: {
                        id: categoryId,
                        show_on_homepage: isChecked ? 1 : 0,
                    },
                    headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                    success: function(response) {
                        Notiflix.Loading.remove();
                        
                        if (response.status === 'success') {
                            // Update status text
                            statusText.text(isChecked ? 'Visible' : 'Hidden');
                            
                            // Show success message
                            Notiflix.Notify.success('Category visibility updated successfully');
                            refreshDataTableById('#category-table');
                            // Add visual feedback
                            checkbox.closest('tr').addClass('bg-success-light');
                            setTimeout(function() {
                                checkbox.closest('tr').removeClass('bg-success-light');
                            }, 1000);
                        } else {
                            // Revert checkbox state
                            checkbox.prop('checked', !isChecked);
                            Notiflix.Notify.failure(response.message || 'Failed to update status');
                        }
                    },
                    error: function(xhr) {
                        Notiflix.Loading.remove();
                        // Revert checkbox state
                        checkbox.prop('checked', !isChecked);
                        
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Notiflix.Notify.failure(errorMessage);
                    }
                });
            });
        }
        // Function to initialize description modals
        function initializeDescriptionModals() {
            $('.view-description-btn').off('click').on('click', function() {
                var categoryId = $(this).data('id');
                var categoryName = $(this).data('name');
                var description = $(this).data('description');
                
                // Create modal HTML
                var modalHtml = `
                    <div class="modal fade" id="descriptionModal-${categoryId}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"  style="color: #ffff !important;>
                                        <i class="fas fa-info-circle"></i> Category Description
                                    </h5>
                                    <a type="button" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                                <div class="modal-body">
                                    <div class="category-info">
                                        <h6>Category: <strong>${escapeHtml(categoryName)}</strong></h6>
                                        <hr>
                                        <div class="description-content">
                                            <label>Description:</label>
                                            <div class="well well-sm" style="padding: 15px; background-color: #f9f9f9; border-left: 4px solid #007bff;">
                                                ${escapeHtml(description) || '<em>No description provided</em>'}
                                            </div>
                                        </div>
                                        <div class="mt-3 text-muted">
                                            <small><i class="fas fa-clock"></i> Last updated: ${new Date().toLocaleString()}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                            <i class="fas fa-times"></i> Close
                                    </button>
                                    <button type="button" class="btn btn-primary copy-description-btn" data-description="${escapeHtml(description)}">
                                        <i class="fas fa-copy"></i> Copy Description
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if present
                if ($('#descriptionModal-' + categoryId).length) {
                    $('#descriptionModal-' + categoryId).remove();
                }
                
                // Append modal to body
                $('body').append(modalHtml);
                
                // Show modal
                $('#descriptionModal-' + categoryId).modal('show');
                
                // Remove modal from DOM when hidden
                $('#descriptionModal-' + categoryId).on('hidden.bs.modal', function() {
                    $(this).remove();
                });
                
                // Handle copy button
                $('#descriptionModal-' + categoryId + ' .copy-description-btn').off('click').on('click', function() {
                    var descriptionText = $(this).data('description');
                    copyToClipboard(descriptionText);
                });
            });
        }

        // Utility function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        // Utility function to copy text to clipboard
        function copyToClipboard(text) {
            if (!text || text === 'No description provided') {
                Notiflix.Notify.warning('Nothing to copy');
                return;
            }
            
            // Create temporary textarea
            var textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            
            // Show success notification
            Notiflix.Notify.success('Description copied to clipboard!');
        }

        // Optional: Add bulk update for website visibility
        function bulkUpdateWebsiteVisibility() {
            var selectedIds = [];
            $('.category-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                Notiflix.Notify.warning('Please select categories to update');
                return;
            }
            
            Notiflix.Confirm.show(
                'Bulk Update',
                `Update website visibility for ${selectedIds.length} categories?`,
                'Show on Website',
                'Hide from Website',
                function() {
                    updateBulkStatus(selectedIds, 1);
                },
                function() {
                    updateBulkStatus(selectedIds, 0);
                }
            );
        }

        function updateBulkStatus(ids, status) {
            Notiflix.Loading.pulse('Updating categories...');
            
            $.ajax({
                url: '/admin/categories/bulk-update-website',
                type: 'POST',
                data: {
                    ids: ids,
                    show_on_homepage: status,
                },
                headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        Notiflix.Notify.success(`Updated ${response.updated_count} categories`);
                        // Reload DataTable
                        refreshDataTableById('category-table');
                    } else {
                        Notiflix.Notify.failure(response.message);
                    }
                },
                error: function() {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure('Failed to update categories');
                }
            });
        }
    </script>
@endsection