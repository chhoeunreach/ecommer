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
    $chatButtonText = get_setting('product_detail_chat_button_text') ?: translate('Chat With Us');
    $chatButtonUrl = trim((string) get_setting('product_detail_chat_button_url'));
    $chatButtonScheme = strtolower((string) parse_url($chatButtonUrl, PHP_URL_SCHEME));
    $showChatButton = (int) get_setting('product_detail_show_chat_button', 0) === 1
        && filter_var($chatButtonUrl, FILTER_VALIDATE_URL)
        && in_array($chatButtonScheme, ['http', 'https'], true);
    $contactSalesUsesChat = (int) get_setting('conversation_system', 0) === 1;
    $contactSalesUrl = route('custom-pages.show_custom_page', 'contact-us');
@endphp

@if ((int) get_setting('product_detail_show_buy_now', 1) === 1)
    <button type="button"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-2 fs-14 fw-bold hov-opacity-70 has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 buy-now d-inline-flex align-items-center justify-content-center"
        style="background-color: {{ get_setting('product_detail_buy_now_bg_color', '#17171f') }}; color: {{ get_setting('product_detail_buy_now_text_color', '#ffffff') }}; min-height: 44px;">
        {{ $buyNowText }}
    </button>
@endif

@if ((int) get_setting('product_detail_show_add_to_cart', 1) === 1)
    <button type="button" id="added_to_cart_btn"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 add-to-cart d-inline-flex align-items-center justify-content-center"
        style="background-color: {{ get_setting('product_detail_add_to_cart_bg_color', '#dcebff') }}; color: {{ get_setting('product_detail_add_to_cart_text_color', '#3390f3') }}; min-height: 44px;">
        {{ $addToCartText }} <span id="add_to_cart_count">{{ $cartCount }}</span>
    </button>
@endif

@if ($contactSalesUsesChat)
    <button type="button" onclick="show_chat_modal()"
        class="d-inline-flex align-items-center justify-content-center text-center border rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 @if ($showCustomButton || $showChatButton) mr-md-2 @endif contact-sales-btn"
        style="background-color: #ffffff; border-color: #3390f3 !important; color: #1677d2; min-height: 44px;">
        <i class="las la-headset fs-18 mr-2" aria-hidden="true"></i>
        {{ translate('Contact Sales') }}
    </button>
@else
    <a href="{{ $contactSalesUrl }}"
        class="d-flex align-items-center justify-content-center text-center border rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 @if ($showCustomButton || $showChatButton) mr-md-2 @endif contact-sales-btn"
        style="background-color: #ffffff; border-color: #3390f3 !important; color: #1677d2; min-height: 44px;">
        <i class="las la-headset fs-18 mr-2" aria-hidden="true"></i>
        {{ translate('Contact Sales') }}
    </a>
@endif

@if ($showCustomButton)
    <a href="{{ $customButtonUrl }}" @if ((int) get_setting('product_detail_custom_button_new_tab', 1) === 1) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border-0 rounded-2 fs-14 fw-bold hov-opacity-70 has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 @if ($showChatButton) mr-md-2 @endif"
        style="background-color: {{ get_setting('product_detail_custom_button_bg_color', '#19c553') }}; color: {{ get_setting('product_detail_custom_button_text_color', '#ffffff') }}; min-height: 44px;">
        {{ $customButtonText }}
    </a>
@endif

@if ($showChatButton)
    <a href="{{ $chatButtonUrl }}" @if ((int) get_setting('product_detail_chat_button_new_tab', 1) === 1) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border-0 rounded-2 fs-14 fw-bold hov-opacity-70 has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0"
        style="background-color: {{ get_setting('product_detail_chat_button_bg_color', '#1677ff') }}; color: {{ get_setting('product_detail_chat_button_text_color', '#ffffff') }}; min-height: 44px;">
        <i class="las la-comments fs-18 mr-2" aria-hidden="true"></i>
        {{ $chatButtonText }}
    </a>
@endif
