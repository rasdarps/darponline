@extends('client_layout.main')
@section('header')
    <style>
         .login-form-container input {
            margin-bottom:0 !important;"
        }

        .mb-sm-0{
            margin-bottom:2% !important;"

        }
    </style>
@endsection
@section('content')
<div class="customer-page theme-default-margin">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="note form-success text-centet" id="ResetSuccess" style="display:none;">
                    We&#39;ve sent you an email with a link to update your password.
                </div>
                <div class="login">
                   
                    <div id="CustomerLoginForm">
                        <form method="post" action="{{route('shop.login')}}" id="customer_login_form" accept-charset="UTF-8"
                            >
                        @csrf
                             

                            <div class="login-form-container">
                                <div class="login-text">
                                    <h2>Login</h2>
                                    <p>Please login using account detail bellow.</p>
                                </div>
                                
                                <div class="login-form">
                                    <div id="global_error_alert" class="alert alert-danger" style="display: none;">
                                        <strong>Login Failed!</strong>
                                        <div id="global_error_list"></div>
                                    </div>
                                    <div class="form-group">
                
                                            <input type="email" name="email" id="email" class="input-full"
                                            placeholder="Email" autocorrect="off" autocapitalize="off" autofocus> 
                                                <span class="text-danger error-text" data-field="email"></span>
                

                                    </div>
                                    <div class="form-group">
                
                                        <input type="password" value="" name="password" id="password"
                                        class="input-full" placeholder="Password">
                                            <span class="text-danger error-text" data-field="password"></span>
            

                                    </div>
                                    

                                   

                                    <div class="login-toggle-btn">
                                        <div class="form-action-button">
                                            <button type="submit" class="theme-default-button">Sign In</button>

                                            <a href="#recover" id="RecoverPassword">Forgot your password?</a>

                                        </div>
                                        <div class="account-optional-action">
                                            <a href="{{route('shop.registration_page')}}" id="customer_register_link">Create account</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="RecoverPasswordForm" style="display: none;">
                        <form method="post" action="#" accept-charset="UTF-8"><input type="hidden"
                                name="form_type" value="recover_customer_password" /><input type="hidden" name="utf8"
                                value="✓" />


                            <div class="login-form-container">
                                <div class="login-text">
                                    <h2>Reset your password</h2>
                                    <p>We will send you an email to reset your password.</p>
                                </div>
                                <div class="login-form">
                                    <input type="email" value="" name="email" id="RecoverEmail" class="input-full"
                                        placeholder="Email" autocorrect="off" autocapitalize="off">
                                    <div class="login-toggle-btn">
                                        <div class="form-action-button">
                                            <button type="submit" class="theme-default-button">Submit</button>
                                            <a href="#" id="HideRecoverPasswordLink">Cancel</a>
                                        </div>
                                    </div>
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

            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                });

                 // Setup real-time validation for all forms
                setupRealtimeValidation();
               // login form submission
                $("#customer_login_form").on('submit', function(e) {
                    e.preventDefault();
                    $('.error-text').html('');
                    $('.is-invalid').removeClass('is-invalid');
                    $('#global_error_alert').hide();
                    
                    

                    
                    // Make AJAX request using reusable function
                    ajax(
                        $(this).attr('action'),           // URL
                        new FormData(this),                // Data
                        {
                            element: 'login-form-container',  // Loading element
                            showLoading: true,
                            showGlobalError: true,
                            scrollToError: true,
                            success: function(response) {
                                if (response.status === 'success') {

                                    $('#customer_login_form')[0].reset();

                                    Notiflix.Notify.success(response.message || 'Login successful!');
                                    setTimeout(() => {
                                        window.location.href = response.redirect || "{{route('shop.home')}}";
                                    }, 1500);
                                } else {
                                    Notiflix.Notify.warning(response.message || 'Login failed');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Custom error handling for registration
                                console.log('Login error:', error);
                                // The reusable function already handles validation errors
                            }
                        }
                    );
                });
            });

     

    </script>
@endsection