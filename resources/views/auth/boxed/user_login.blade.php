@extends('auth.layouts.authentication')

@section('content')
    <div class="aiz-main-wrapper d-flex flex-column justify-content-center bg-slate-50 min-vh-100 py-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-9">
                    <div class="shadcn-auth-card">
                        <!-- Header -->
                        <div class="shadcn-auth-header">
                            @if(get_setting('site_icon') != null)
                                <div class="size-48px mb-3">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                </div>
                            @endif
                            <h1 class="shadcn-auth-title">{{ translate('Welcome Back') }}</h1>
                            <p class="shadcn-auth-subtitle">{{ translate('Enter your credentials to access your account') }}</p>
                        </div>

                        <!-- Form -->
                        <form class="form-default loginForm" id="user-login-form" role="form" action="{{ route('login') }}" method="POST">
                            @csrf

                            <!-- Email / Phone -->
                            <div class="shadcn-form-group">
                                <label for="email-or-phone" class="shadcn-form-label">{{ translate('Email or Phone') }}</label>
                                <div class="shadcn-input-wrap">
                                    <input type="text" id="email-or-phone" name="email" 
                                        class="shadcn-input {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                        value="{{ old('email') }}" 
                                        placeholder="{{ translate('Enter your email or phone number') }}" 
                                        autocomplete="off" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <!-- Password -->
                            <div class="password-login-block">
                                <div class="shadcn-form-group">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="password" class="shadcn-form-label mb-0">{{ translate('Password') }}</label>
                                        <a href="{{ route('password.request') }}" class="fs-12 text-primary fw-600 text-decoration-none">{{ translate('Forgot password?') }}</a>
                                    </div>
                                    <div class="shadcn-input-wrap">
                                        <input type="password" class="shadcn-input {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                            placeholder="{{ translate('Enter your password') }}" name="password" id="password" required>
                                        <i class="password-toggle las la-eye shadcn-password-toggle"></i>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="fs-13 text-slate-600">{{ translate('Remember me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                    @if(get_setting('login_with_otp'))
                                        <a href="javascript:void(0);" class="fs-12 text-slate-600 fw-500 toggle-login-with-otp" onclick="toggleLoginPassOTP(this)">{{ translate('Login with OTP') }}</a>
                                    @endif
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="shadcn-btn-primary submit-button">
                                <span>{{ translate('Sign In') }}</span>
                                <i class="las la-arrow-right fs-16"></i>
                            </button>
                        </form>

                        <!-- Social Login -->
                        @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                            <div class="shadcn-divider">
                                <span>{{ translate('Or continue with') }}</span>
                            </div>
                            <div class="row gutters-10">
                                @if (get_setting('google_login') == 1)
                                    <div class="col-6 mb-2">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="shadcn-social-btn">
                                            <i class="lab la-google text-danger fs-18"></i>
                                            <span>Google</span>
                                        </a>
                                    </div>
                                @endif
                                @if (get_setting('facebook_login') == 1)
                                    <div class="col-6 mb-2">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="shadcn-social-btn">
                                            <i class="lab la-facebook-f text-primary fs-18"></i>
                                            <span>Facebook</span>
                                        </a>
                                    </div>
                                @endif
                                @if (get_setting('apple_login') == 1)
                                    <div class="col-6 mb-2">
                                        <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="shadcn-social-btn">
                                            <i class="lab la-apple text-dark fs-18"></i>
                                            <span>Apple</span>
                                        </a>
                                    </div>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <div class="col-6 mb-2">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="shadcn-social-btn">
                                            <i class="lab la-twitter text-info fs-18"></i>
                                            <span>Twitter</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Footer -->
                        <div class="shadcn-auth-footer">
                            {{ translate("Don't have an account?") }}
                            <a href="{{ route('user.registration') }}">{{ translate('Sign Up') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection