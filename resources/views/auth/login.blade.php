{{-- filepath: c:\xampp\htdocs\rictPOS\resources\views\auth\login.blade.php --}}
@extends('layouts.app')
@section('title') {{'Login'}} @endsection


@section('content')
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="color:#034141; font-weight:bold;">
                <div class="card-header" style="background-color:#034141; color:#fff">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <label for="login" class="col-md-4 col-form-label text-md-end">{{ __('Email or Username') }}</label>


                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control @error('login') @error('email') @error('username') is-invalid @enderror @enderror @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="Enter email or username">

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#034141; color:#fff; border:none;">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Notiflix CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix/dist/notiflix-3.2.6.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/notiflix/dist/notiflix-3.2.6.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                });
                var login = $('#login').val().trim();
                var password = $('#password').val().trim();
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
                if (!login) {
                    notify('Please enter your email or username.');
                    $('#login').focus();
                    return false;
                }
                if (!password) {
                    notify('Please enter your password.');
                    $('#password').focus();
                    return false;
                }

                // Show Notiflix loading
                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Loading.standard('Logging in...');
                }

                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: $(form).serialize(),
                    success: function(data, status, xhr) {
                        if (typeof Notiflix !== 'undefined') {
                            Notiflix.Loading.remove();
                        }
                        // If login is successful, Laravel usually redirects (status 200 or 302)
                        if (xhr.responseURL && xhr.responseURL !== window.location.href) {
                            window.location.href = xhr.responseURL;
                        } else if (data && typeof data === 'object' && data.message) {
                            Notiflix.Notify.success(data.message);
                        } else {
                            Notiflix.Notify.success('Login successful!');
                            setTimeout(function(){ location.reload(); }, 1000);
                        }
                    },
                    error: function(xhr) {
                        if (typeof Notiflix !== 'undefined') {
                            Notiflix.Loading.remove();
                        }
                        let msg = 'Login failed. Please check your credentials.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        notify(msg);
                    }
                });
            });
        });
    </script>
@endsection
