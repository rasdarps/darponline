@extends('layouts.app')
@section('title') {{'Login'}} @endsection


@section('content')
<style>
    body {
        position: relative;
        background-color: #0b1f2a !important;
        background-image:
            radial-gradient(circle at 12% 18%, rgba(255, 193, 7, 0.24) 0%, rgba(255, 193, 7, 0) 38%),
            radial-gradient(circle at 85% 82%, rgba(56, 189, 248, 0.22) 0%, rgba(56, 189, 248, 0) 40%),
            linear-gradient(130deg, rgba(7, 31, 43, 0.9) 0%, rgba(15, 23, 42, 0.82) 52%, rgba(9, 35, 48, 0.9) 100%),
            url('{{ asset('images/posbg1.jpg') }}') !important;
        background-repeat: no-repeat, no-repeat, no-repeat, no-repeat;
        background-size: auto, auto, cover, cover;
        background-position: left top, right bottom, center center, center center;
        background-attachment: fixed;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background-image: linear-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.045) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(circle at center, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.1) 78%);
        opacity: 0.28;
        z-index: 0;
    }

    #app {
        position: relative;
        z-index: 1;
    }

    .auth-shell {
        min-height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        padding: 18px 0;
    }

    .auth-welcome {
        margin: 0;
        text-align: center;
        color: #f8fafc;
        font-size: clamp(1.5rem, 2.2vw, 2rem);
        font-weight: 700;
        letter-spacing: 0.3px;
        text-shadow: 0 4px 20px rgba(2, 6, 23, 0.55);
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 14px;
        box-shadow: 0 20px 45px rgba(2, 12, 27, 0.45);
        overflow: hidden;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(2px);
    }

    .auth-header {
        padding: 22px 24px;
        background: #0f172a;
        color: #ffffff;
    }

    .auth-header h5 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .auth-header p {
        margin: 6px 0 0;
        opacity: 0.85;
        font-size: 13px;
    }

    .auth-body {
        padding: 24px;
    }

    .auth-body label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }

    .auth-body .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        min-height: 46px;
    }

    .auth-body .form-control:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.12);
    }

    .auth-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-auth {
        background: #0f172a;
        border: none;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        padding: 10px 18px;
    }

    .btn-auth:hover {
        background: #1e293b;
        color: #fff;
    }

    .auth-link {
        color: #0f172a;
        font-weight: 600;
        text-decoration: none;
    }

    .auth-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 991.98px) {
        body {
            background-attachment: scroll;
            background-size: auto, auto, cover, cover;
        }

        .auth-shell {
            min-height: auto;
            padding: 14px 0 10px;
            gap: 12px;
        }

        .auth-welcome {
            font-size: clamp(1.25rem, 4.8vw, 1.55rem);
            padding: 0 10px;
        }

        .auth-card {
            max-width: 520px;
        }
    }

    @media (max-width: 575.98px) {
        .auth-shell {
            padding: 8px 0 6px;
            gap: 10px;
        }

        .auth-card {
            border-radius: 12px;
        }

        .auth-header {
            padding: 18px 16px;
        }

        .auth-header h5 {
            font-size: 1rem;
        }

        .auth-header p {
            font-size: 12px;
        }

        .auth-body {
            padding: 16px;
        }

        .auth-body .form-control {
            min-height: 44px;
            font-size: 0.95rem;
        }

        .btn-auth {
            width: 100%;
            padding: 11px 18px;
        }

        .auth-actions {
            margin-top: 14px;
        }
    }

    @media (min-width: 1400px) {
        .auth-shell {
            gap: 20px;
        }

        .auth-card {
            max-width: 560px;
        }

        .auth-welcome {
            font-size: clamp(1.7rem, 1.8vw, 2.15rem);
        }
    }
</style>

<div class="auth-shell">
    <p class="auth-welcome">Welcome to Factory Mall Price POS System</p>
    <div class="auth-card">
        <div class="auth-header">
            <h5>{{ __('Admin Login') }}</h5>
            <p style="margin-top:4px; opacity:0.75;">Sign in to access your dashboard.</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="login">{{ __('Email or Username') }}</label>
                    <input id="login" type="text" class="form-control @error('login') @error('email') @error('username') is-invalid @enderror @enderror @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="Enter email or username">

                    @error('login')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('username')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter password">

                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="auth-actions">
                    <button id="loginSubmitBtn" type="submit" class="btn btn-auth">
                        {{ __('Login') }}
                    </button>
                </div>

                <div class="text-center mt-3" style="font-size:12px; color:#64748b; font-weight:600;">
                    RICT POS V 1.0
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Notiflix !== 'undefined') {
                Notiflix.Notify.init({
                    position: 'right-top',
                    timeout: 3000,
                    showOnlyTheLastOne: true
                });
            }

            var form = document.getElementById('loginForm');
            var loginInput = document.getElementById('login');
            var passwordInput = document.getElementById('password');
            var submitBtn = document.getElementById('loginSubmitBtn');

            function notify(msg) {
                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Notify.failure(msg);
                } else {
                    alert(msg);
                }
            }

            form.addEventListener('submit', function (e) {
                var login = (loginInput.value || '').trim();
                var password = (passwordInput.value || '').trim();

                if (!login) {
                    e.preventDefault();
                    notify('Please enter your email or username.');
                    loginInput.focus();
                    return;
                }

                if (!password) {
                    e.preventDefault();
                    notify('Please enter your password.');
                    passwordInput.focus();
                    return;
                }

                if (typeof Notiflix !== 'undefined') {
                    Notiflix.Loading.standard('Logging in...');
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Please wait...';
            });
        });
    </script>
@endsection
