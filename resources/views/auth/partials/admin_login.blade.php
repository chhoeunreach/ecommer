@php
    $adminSiteName = get_setting('website_name') ?: env('APP_NAME');
    $adminLoginImageId = get_setting('admin_login_page_image');
    $adminLoginImage = $adminLoginImageId && \App\Models\Upload::find($adminLoginImageId) ? uploaded_asset($adminLoginImageId) : null;
@endphp

<div class="adm-login">
    <div class="adm-login__card">
        <aside class="adm-login__brand" @if ($adminLoginImage) style="background-image: linear-gradient(160deg, rgba(76, 31, 184, .88), rgba(109, 61, 241, .78)), url('{{ $adminLoginImage }}');" @endif>
            <span class="adm-login__orb adm-login__orb--one" aria-hidden="true"></span>
            <span class="adm-login__orb adm-login__orb--two" aria-hidden="true"></span>

            <div class="adm-login__brand-top">
                @if (get_setting('site_icon'))
                    <span class="adm-login__logo"><img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon') }}"></span>
                @endif
                <span class="adm-login__brand-name">{{ $adminSiteName }}</span>
            </div>

            <div class="adm-login__brand-copy">
                <h2>{{ translate('Run your shop from one place.') }}</h2>
                <p>{{ translate('Manage products, orders and customers with your admin dashboard.') }}</p>
                <ul>
                    <li><i class="las la-box" aria-hidden="true"></i>{{ translate('Products & stock') }}</li>
                    <li><i class="las la-receipt" aria-hidden="true"></i>{{ translate('Orders & payments') }}</li>
                    <li><i class="las la-users" aria-hidden="true"></i>{{ translate('Customers & reports') }}</li>
                </ul>
            </div>
        </aside>

        <section class="adm-login__panel">
            <a href="{{ url()->previous() }}" class="shadcn-back-home-link adm-login__back">
                <i class="las la-arrow-left fs-14" aria-hidden="true"></i>
                <span>{{ translate('Back to Previous Page') }}</span>
            </a>

            <div class="adm-login__head">
                <span class="adm-login__badge"><i class="las la-shield-alt" aria-hidden="true"></i>{{ translate('Admin') }}</span>
                <h1>{{ translate('Welcome back') }}</h1>
                <p>{{ translate('Login to your account') }}</p>
            </div>

            <form class="form-default" id="login-form" role="form" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="shadcn-form-group">
                    <label for="email" class="shadcn-form-label">{{ translate('Email') }}</label>
                    <div class="shadcn-input-wrap">
                        <input type="email" class="shadcn-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}"
                            name="email" id="email" autocomplete="off">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="shadcn-form-group">
                    <label for="password" class="shadcn-form-label">{{ translate('Password') }}</label>
                    <div class="shadcn-input-wrap">
                        <input type="password" class="shadcn-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="{{ translate('Password') }}" name="password" id="password">
                        <i class="password-toggle las la-eye shadcn-password-toggle"></i>
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-danger fs-12 mt-1 d-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                @if (get_setting('google_recaptcha') == 1 && get_setting('recaptcha_admin_login') == 1 && $errors->has('g-recaptcha-response'))
                    <span class="border rounded p-2 mb-3 bg-danger text-white d-block fs-12" role="alert">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                @endif

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <label class="aiz-checkbox mb-0">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="fs-13 text-slate-600">{{ translate('Remember Me') }}</span>
                        <span class="aiz-square-check"></span>
                    </label>
                    <a href="{{ route('password.request') }}" class="fs-12 text-primary fw-600 text-decoration-none">{{ translate('Forgot password?') }}</a>
                </div>

                <button type="submit" class="shadcn-btn-primary adm-login__submit">
                    <span>{{ translate('Login') }}</span>
                    <i class="las la-arrow-right fs-16" aria-hidden="true"></i>
                </button>
            </form>

            @if (env('DEMO_MODE') == 'On')
                <div class="adm-login__demo">
                    <div>
                        <strong>admin@example.com</strong>
                        <span>123456</span>
                    </div>
                    <button type="button" onclick="autoFillAdmin()">{{ translate('Copy') }}</button>
                </div>
            @endif
        </section>
    </div>
</div>
