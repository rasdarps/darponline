@extends('client_layout.main')
 @section('header')
 <style>
     .register-form-container input {
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
          <div class="login">
            <div class="register-form-container">
              <div class="login-text">
                <h2>Create Account</h2>
                
                <p>Please Register using account detail bellow.</p>
                
              </div>
              <div class="register-form" >
                <form method="post" action="{{route('shop.register')}}" id="create_customer_form" class="form-content" accept-charset="UTF-8"><input type="hidden" name="form_type" value="create_customer" /><input type="hidden" name="utf8" value="✓" />
                    @csrf

                     <!-- Global Error Alert -->
                    <div id="global_error_alert" class="alert alert-danger" style="display: none;">
                        <strong>Registration Failed!</strong>
                        <div id="global_error_list"></div>
                    </div>
    
                    <div class="form-group row">
                        <div class="col-sm-6 mb-sm-1">
                            <input type="text" name="first_name" class="form-control form-control-user" id="exampleFirstName"
                                placeholder="Full Name" value="{{old('first_name')}}" >
                                
                                <span class="text-danger error-text" data-field="first_name"></span>

                        </div>      
                        <div class="col-sm-6">
                            <input type="text" name="last_name" class="form-control form-control-user" id="exampleLastName"
                            placeholder="Last Name" value="{{old('last_name')}}" >
                      
                            <span class="text-danger error-text" data-field="last_name"></span>

                        </div>   
                    </div>
                    <div class="form-group ">
                        <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail"
                        placeholder="Email Address" value="{{old('email')}}" >

                        
                        <span class="text-danger error-text" data-field="email"></span>

                    </div>
                
                    <div class="form-group row">
                        <div class="col-sm-6 mb-sm-0">

                            <input type="address" name="address" class="form-control form-control-user" id="address"
                                placeholder="Address" value="{{old('address')}}" >

                                
                                <span class="text-danger error-text" data-field="address"></span>

                        </div>

                        <div class="col-sm-6 mb-sm-0">

                            <input type="text" name="mobile_no"  class="form-control form-control-user" id="mobile_no"
                                placeholder="Mobile Number" onkeypress="return isNumberKey(event)" maxlength="10" value="{{old('mobile_no')}}" >

                                
                                <span class="text-danger error-text" data-field="mobile_no"></span>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-sm-0">
                            <input type="password" name="password" class="form-control form-control-user"
                                id="exampleInputPassword" placeholder="Password" >
                                
                                <span class="text-danger error-text" data-field="password"></span>

                        </div>
                        <div class="col-sm-6">
                            <input type="password" name="password_confirmation" class="form-control form-control-user"
                                id="exampleRepeatPassword" placeholder="Repeat Password" >
                                <span class="text-danger error-text" data-field="password_confirmation"></span>

                        </div>
                    </div>

                    <div class="form-action-button">

                        <button type="submit"  class="theme-default-button btn btn-primary btn-user btn-block">
                            Register 
                        </button>
                    </div>
                    <hr>
                    <a href="index.html" class="btn btn-google btn-block">
                        <i class="fab fa-google fa-fw"></i> Register with Google
                    </a>
                    <div class="account-optional-action">
                        <a href="https://new-99.myshopify.com">Return to Store</a>
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
                PasswordStrengthIndicator();
               // Registration form submission
                $("#create_customer_form").on('submit', function(e) {
                    e.preventDefault();
                    $('.error-text').html('');
                    $('.is-invalid').removeClass('is-invalid');
                    $('#global_error_alert').hide();
                    // Client-side validation
                    if (!validateRegistrationForm()) {
                        return;
                    }
                    

                    
                    // Make AJAX request using reusable function
                    ajax(
                        $(this).attr('action'),           // URL
                        new FormData(this),                // Data
                        {
                            element: 'register-form-container',  // Loading element
                            showLoading: true,
                            showGlobalError: true,
                            scrollToError: true,
                            success: function(response) {
                                if (response.status === 'success') {

                                    $('#create_customer_form')[0].reset();

                                    Notiflix.Notify.success(response.message || 'Registration successful!');
                                    setTimeout(() => {
                                        window.location.href = response.redirect || "{{route('shop.login')}}";
                                    }, 100);
                                } else {
                                    Notiflix.Notify.warning(response.message || 'Registration failed');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Custom error handling for registration
                                console.log('Registration error:', error);
                                // The reusable function already handles validation errors
                            }
                        }
                    );
                });
            });

            // Registration form specific validation
                function validateRegistrationForm() {
                    let isValid = true;
                    
                    // Clear previous errors
                    clearAllErrors({ errorSelector: '.error-text', inputErrorClass: 'is-invalid' });
                    
                    // Validate email
                    const email = $('input[name="email"]').val();
                    const emailRegex = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
                    if (email && !emailRegex.test(email)) {
                        showFieldError('email', 'Please enter a valid email address');
                        isValid = false;
                    }
                    
                    // Validate password match
                    const password = $('input[name="password"]').val();
                    const passwordConfirmation = $('input[name="password_confirmation"]').val();
                    if (password !== passwordConfirmation) {
                        showFieldError('password_confirmation', 'Passwords do not match');
                        isValid = false;
                    }
                    
                    // Validate password strength
                    if (password && password.length < 8) {
                        showFieldError('password', 'Password must be at least 8 characters');
                        isValid = false;
                    }
                    
                    // Validate mobile number (optional)
                    const mobileNo = $('input[name="mobile_no"]').val();
                    if (mobileNo && !/^\d{10,15}$/.test(mobileNo)) {
                        showFieldError('mobile_no', 'Please enter a valid mobile number (10-15 digits)');
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        Notiflix.Notify.warning('Please correct the errors before submitting');
                        scrollToFirstError('is-invalid');
                    }
                    
                    return isValid;
                }
                // Live password strength indicator (optional enhancement)
                function PasswordStrengthIndicator() {
                    const passwordInput = $('input[name="password"]');
                    const strengthIndicator = $('<div class="password-strength mt-1"><small class="text-muted">Password strength: </small><span class="strength-text"></span></div>');
                    passwordInput.after(strengthIndicator);
                    
                    passwordInput.on('keyup', function() {
                        const password = $(this).val();
                        const strength = calculatePasswordStrength(password);
                        const strengthText = ['Very Weak', 'Weak', 'Medium', 'Strong', 'Very Strong'];
                        const strengthClass = ['text-danger', 'text-danger', 'text-warning', 'text-info', 'text-success'];
                        
                        if (password.length > 0) {
                            strengthIndicator.find('.strength-text').text(strengthText[strength]).css('color', strengthClass[strength]);
                        } else {
                            strengthIndicator.find('.strength-text').text('');
                        }
                    });
                }

                function calculatePasswordStrength(password) {
                    let strength = 0;
                    if (password.length >= 8) strength++;
                    if (password.length >= 12) strength++;
                    if (password.match(/[a-z]+/)) strength++;
                    if (password.match(/[A-Z]+/)) strength++;
                    if (password.match(/[0-9]+/)) strength++;
                    if (password.match(/[$@#&!]+/)) strength++;
                    return Math.min(4, Math.floor(strength / 1.5));
                }

    </script>
@endsection

