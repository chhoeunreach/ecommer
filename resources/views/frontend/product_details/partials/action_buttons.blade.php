@php
    $buttonPadding = $buttonPadding ?? 'py-20px';
    $cartCount = $cartCount ?? '';
    $buyNowText = get_setting('product_detail_buy_now_text') ?: translate('Buy Now');
    $addToCartText = get_setting('product_detail_add_to_cart_text') ?: translate('Add to Cart');
    $customButtonText = get_setting('product_detail_custom_button_text') ?: translate('Contact Us');
    $customButtonUrl = get_setting('product_detail_custom_button_url')
        ?: route('custom-pages.show_custom_page', 'contact-us');
    $showCustomButton = (int) get_setting('product_detail_show_custom_button', 0) === 1
        && filter_var($customButtonUrl, FILTER_VALIDATE_URL);
@endphp

@if ((int) get_setting('product_detail_show_buy_now', 1) === 1)
    <button type="button"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-1 fs-14 fw-bold hov-opacity-70 has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 buy-now"
        style="background-color: {{ get_setting('product_detail_buy_now_bg_color', '#17171f') }}; color: {{ get_setting('product_detail_buy_now_text_color', '#ffffff') }};">
        {{ $buyNowText }}
    </button>
@endif

@if ((int) get_setting('product_detail_show_add_to_cart', 1) === 1)
    <button type="button" id="added_to_cart_btn"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-1 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 @if ($showCustomButton) mr-md-2 @endif add-to-cart"
        style="background-color: {{ get_setting('product_detail_add_to_cart_bg_color', '#dcebff') }}; color: {{ get_setting('product_detail_add_to_cart_text_color', '#3390f3') }};">
        {{ $addToCartText }} <span id="add_to_cart_count">{{ $cartCount }}</span>
    </button>
@endif

@if ($showCustomButton)
    <a href="{{ $customButtonUrl }}" @if ((int) get_setting('product_detail_custom_button_new_tab', 1) === 1) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border-0 rounded-1 fs-14 fw-bold hov-opacity-70 has-transition {{ $buttonPadding }} px-20px w-100"
        style="background-color: {{ get_setting('product_detail_custom_button_bg_color', '#19c553') }}; color: {{ get_setting('product_detail_custom_button_text_color', '#ffffff') }};">
        {{ $customButtonText }}
    </a>
@endif
