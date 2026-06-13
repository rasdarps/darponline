@extends('layout.main')
@section('title') {{'Create Category'}} @endsection

@section('content')


<div class="page-content">
    <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title" style="font-size:20px;">Create | Category </span>

                            {{-- @can('create asset category') --}}
                            <a href="{{route('categories.index')}}" style="float:right">
                                <button type="button" class="btn btn-primary modal_btn">
                                    View Categories
                                </button>
                            </a>

                              <hr>

                            {{-- @endcan --}}

                            <div class="card-body">

                                <form action="{{ route('categories.store') }}" method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf

                                    {{-- backend error alerts --}}
                                    <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" style="display: none;">
                                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                                            <span><i class="mdi mdi-close"></i></span>
                                        </button>
                                        <ul id="errors"></ul>
                                    </div>

                                    <div class="row ">

                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="name"><strong>Category <span class="text-danger">*</span></strong></label>
                                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="enter category" onkeypress="return isCharKey(event)">
                                                <small class="text-muted">This will be displayed on the website</small>

                                            </div>

                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
                                                <small class="text-muted">Brief description of the category (optional)</small>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="show_on_homepage" class="custom-control-input" id="show_on_homepage" 
                                                           value="1" {{ isset($category) && $category->show_on_homepage ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="show_on_homepage">Show on Online Shop Website</label>
                                                </div>
                                                <small class="text-muted">Display this category on the homepage of the online shop</small>
                                            </div>

                                        </div>

                                        <div class="col-md-6 mx-auto">
                                            <!-- Category Image -->
                                            
                                                <div class="card-body py-16pt text-center card_content">
                                                  
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
                                                    :required="false"
                                                />
                                                    <p class="card-subtitle text-70 mb-24pt">you can edit the file by clicking on the pen icon</p>

                                                </div>
                                          
                                                   
                                           
                                        </div>
                                    </div>

                                        
                                  
                                    
                                    {{-- </div> --}}
                                    {{-- row 1 ends --}}

                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success mt-3">Submit</button>
                                        </div>
                                    </div>
                                    {{-- row 2 ends --}}
                            
                                </form>
                            
                            </div>
                                
                        </div>
                        

                    <div>
                </div>
                <!-- end col 12-->
                </div>
            <!-- end row -->
             </div>
        <!-- container fluid ends -->
    </div>

</div>
    <!-- page content ends -->

@endsection 

@section('styles')
<style>

   

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

   

    .card:hover {
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
    }

        /* .card{
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        } */

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
</style>
@endsection

@section('scripts')


<script type="text/javascript">
        $(document).ready(function (){
           
            // Handle form submission

            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                // Client-side validation
                if(!$('#name').val().trim()) {
                    if (typeof Notiflix !== 'undefined') {
                        Notiflix.Notify.failure('Please enter a category name');
                    } else {
                        alert('Please enter a category name');
                    }
                    return;
                }
                

                Notiflix.Loading.standard('Form submitting, please wait...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Notiflix.Loading.remove();
                        if (data.status === 200) {
                            Notiflix.Notify.success(data.message);
                            $('#myForm')[0].reset();
                            //reset uppy dashboard
                            if (typeof uppy_category_image !== 'undefined') {
                                const files = uppy_category_image.getFiles();
                                files.forEach(file => {
                                    uppy_category_image.removeFile(file.id);
                                });
                            }
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        } else {
                            Notiflix.Notify.failure(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        Notiflix.Loading.remove();
                        $('#errors').empty();
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            $('#errorAlert').show();
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $('#errors').append('<li>' + messages[0] + '</li>');
                                Notiflix.Notify.failure(messages[0]);
                            });
                            $('html, body').animate({
                                scrollTop: $('#errorAlert').offset().top - 100
                            }, 500);
                        } else {
                            $('#errorAlert').show();
                            $('#errors').append('<li>An error occurred. Please try again later.</li>');
                            Notiflix.Notify.failure('An error occurred. Please try again later.');
                        }
                    }
                });
            });

            
        });
</script>

@endsection

