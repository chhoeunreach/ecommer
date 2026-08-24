@extends('auth.layouts.authentication')

@section('content')
    <div class="aiz-main-wrapper d-flex flex-column justify-content-center bg-slate-50 min-vh-100 py-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-9">
                    <div class="shadcn-auth-card">
                        <!-- Header -->
                        <div class="shadcn-auth-header">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ route('home') }}" class="shadcn-back-home-link">
                                    <i class="las la-arrow-left fs-14"></i>
                                    <span>{{ translate('Back to Home') }}</span>
                                </a>
                                @if(get_setting('site_icon') != null)
                                    <a href="{{ route('home') }}" class="size-40px d-inline-block">
                                        <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                    </a>
                                @endif
                            </div>
                            <h1 class="shadcn-auth-title">{{ translate('Create an Account') }}</h1>
                            <p class="shadcn-auth-subtitle">{{ translate('Enter your information to create your account') }}</p>
                        </div>

                        <!-- Form -->
                        <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="shadcn-form-group">
                                <label for="name" class="shadcn-form-label">{{ translate('Full Name') }}</label>
                                <div class="shadcn-input-wrap">
                                    <input type="text" class="shadcn-input {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                                        value="{{ old('name') }}" placeholder="{{ translate('Enter your full name') }}" name="name" required>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <!-- Email or Phone -->
                            <div id="emailOrPhoneDiv" class="shadcn-form-group">
                                <label for="email-or-phone" class="shadcn-form-label">{{ translate('Email or Phone') }}</label>
                                <div class="shadcn-phone-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                    <input type="hidden" name="country_code" value="855">
                                    <input type="text" id="email-or-phone" name="email"
                                        class="shadcn-phone-input"
                                        value="{{ old('email') }}" placeholder="{{ translate('Enter email or phone number') }}"
                                        autocomplete="off" required>
                                    @if(get_setting('customer_registration_verify') == '1')
                                        <button class="btn btn-primary ml-1 mr-1 rounded-2 fs-13" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)" style="height: 34px; padding: 0 12px; flex-shrink: 0;">
                                            {{ translate('Verify') }}
                                        </button>
                                    @endif
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('email') }}</span>
                                @endif

                                <div class="mt-2 d-none" id="verification-code-group">
                                    <label class="shadcn-form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                    <div class="shadcn-input-wrap">
                                        <input type="text" class="shadcn-input @error('verification_code') is-invalid @enderror"
                                            name="code" id="verification_code" placeholder="{{ translate('Enter 6-digit code') }}" maxlength="6">
                                        <button type="button" class="btn btn-primary ml-2 rounded-2" id="verifyOtpBtn">
                                            <i class="las la-arrow-right"></i>
                                        </button>
                                    </div>
                                    @error('otp')
                                        <span class="text-danger fs-12 mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="shadcn-form-group">
                                <label for="password" class="shadcn-form-label">{{ translate('Password') }}</label>
                                <div class="shadcn-input-wrap">
                                    <input type="password" class="shadcn-input {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                        placeholder="{{ translate('Create a password (min 6 characters)') }}" name="password" required>
                                    <i class="password-toggle las la-eye shadcn-password-toggle"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <!-- Confirm Password -->
                            <div class="shadcn-form-group">
                                <label for="password_confirmation" class="shadcn-form-label">{{ translate('Confirm Password') }}</label>
                                <div class="shadcn-input-wrap">
                                    <input type="password" class="shadcn-input" placeholder="{{ translate('Confirm your password') }}" name="password_confirmation" required>
                                    <i class="password-toggle las la-eye shadcn-password-toggle"></i>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="mb-4">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" name="checkbox_example_1" required>
                                    <span class="fs-13 text-slate-600">
                                        {{ translate('By signing up, I agree to the') }} 
                                        <a href="{{ route('terms') }}" class="text-primary fw-600">{{ translate('Terms & Conditions') }}</a>
                                    </span>
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="shadcn-btn-primary" id="createAccountBtn">
                                <span>{{ translate('Create Account') }}</span>
                                <i class="las la-arrow-right fs-16"></i>
                            </button>
                        </form>

                        <!-- Social Login -->
                        @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                            <div class="shadcn-divider">
                                <span>{{ translate('Or join with') }}</span>
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
                            {{ translate('Already have an account?') }}
                            <a href="{{ route('user.login') }}">{{ translate('Sign In') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        <script type="text/javascript">
            document.getElementById('reg-form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'g-recaptcha-response');
                        input.setAttribute('value', token);
                        e.target.appendChild(input);
                        e.target.submit();
                    });
                });
            });
        </script>
    @endif
    @include('auth.verifyEmailOrPhone')

    <script>
        const regVerifyRequired = {{ get_setting('customer_registration_verify') ? 'true' : 'false' }};
        const createBtn = $('#createAccountBtn');
        const termsCheckbox = $('input[name="checkbox_example_1"]');
        function toggleCreateBtn() {
            const termsChecked = termsCheckbox.is(':checked');
            const regVerified = regVerifyRequired ? (verifyBtn && verifyBtn.classList.contains('disabled')) : true;
            let enableBtn = false;
            if (regVerifyRequired) {
                enableBtn = termsChecked && regVerified;
            } else {
                enableBtn = termsChecked;
            }
            createBtn.prop('disabled', !enableBtn);
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCreateBtn(); 
            termsCheckbox.on('change', toggleCreateBtn); 
        });
    </script>
@endsection