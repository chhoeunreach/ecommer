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
    $isTelegramUrl = static function ($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return $scheme === 'https' && in_array($host, ['t.me', 'telegram.me', 'www.telegram.me'], true);
    };
    $configuredContactSalesUrl = trim((string) get_setting('product_detail_contact_sales_telegram_url'));
    $footerTelegramUrl = collect(footer_social_links())
        ->firstWhere('platform', 'telegram')['url'] ?? '';
    $contactSalesUrl = $isTelegramUrl($configuredContactSalesUrl)
        ? $configuredContactSalesUrl
        : ($isTelegramUrl($chatButtonUrl)
            ? $chatButtonUrl
            : ($isTelegramUrl($footerTelegramUrl) ? $footerTelegramUrl : 'https://t.me/'));
    $showContactSales = (int) get_setting('product_detail_show_contact_sales', 1) === 1;
    $contactSalesText = get_setting('product_detail_contact_sales_text') ?: translate('Contact Sales');
    $contactSalesBgColor = get_setting('product_detail_contact_sales_bg_color', '#ffffff');
    $contactSalesTextColor = get_setting('product_detail_contact_sales_text_color', '#168acd');
    $contactSalesNewTab = (int) get_setting('product_detail_contact_sales_new_tab', 1) === 1;
@endphp

@if ((int) get_setting('product_detail_show_buy_now', 1) === 1)
    <button type="button"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 buy-now product-action-btn product-action-btn--buy d-inline-flex align-items-center justify-content-center"
        style="background-color: {{ get_setting('product_detail_buy_now_bg_color', '#17171f') }}; color: {{ get_setting('product_detail_buy_now_text_color', '#ffffff') }}; min-height: 44px;">
        <span class="product-action-btn__content">
            <i class="las la-bolt" aria-hidden="true"></i>
            <span>{{ $buyNowText }}</span>
        </span>
    </button>
@endif

@if ((int) get_setting('product_detail_show_add_to_cart', 1) === 1)
    <button type="button" id="added_to_cart_btn"
        @if (Auth::check() || get_setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
        class="border-0 rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 add-to-cart product-action-btn product-action-btn--cart d-inline-flex align-items-center justify-content-center"
        style="background-color: {{ get_setting('product_detail_add_to_cart_bg_color', '#dcebff') }}; color: {{ get_setting('product_detail_add_to_cart_text_color', '#3390f3') }}; min-height: 44px;">
        <span class="product-action-btn__content">
            <i class="las la-shopping-cart" aria-hidden="true"></i>
            <span>{{ $addToCartText }}</span>
            <span id="add_to_cart_count">{{ $cartCount }}</span>
        </span>
    </button>
@endif

@if ($showContactSales)
    <a href="{{ $contactSalesUrl }}" data-telegram-url="{{ $contactSalesUrl }}"
        data-product-name="{{ $detailedProduct->getTranslation('name') }}"
        onclick="return contactSalesOnTelegram(this)"
        @if ($contactSalesNewTab) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 @if ($showCustomButton || $showChatButton) mr-md-2 @endif contact-sales-btn product-action-btn product-action-btn--contact"
        style="background-color: {{ $contactSalesBgColor }}; border-color: #229ed9 !important; color: {{ $contactSalesTextColor }}; min-height: 44px;">
        <span class="product-action-btn__content">
            <i class="lab la-telegram-plane" aria-hidden="true"></i>
            <span>{{ $contactSalesText }}</span>
        </span>
    </a>
@endif

@if ($showCustomButton)
    <a href="{{ $customButtonUrl }}" @if ((int) get_setting('product_detail_custom_button_new_tab', 1) === 1) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border-0 rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 @if ($showChatButton) mr-md-2 @endif product-action-btn"
        style="background-color: {{ get_setting('product_detail_custom_button_bg_color', '#19c553') }}; color: {{ get_setting('product_detail_custom_button_text_color', '#ffffff') }}; min-height: 44px;">
        <span class="product-action-btn__content"><i class="las la-link" aria-hidden="true"></i><span>{{ $customButtonText }}</span></span>
    </a>
@endif

@if ($showChatButton)
    <a href="{{ $chatButtonUrl }}" @if ((int) get_setting('product_detail_chat_button_new_tab', 1) === 1) target="_blank" rel="noopener noreferrer" @endif
        class="d-flex align-items-center justify-content-center text-center border-0 rounded-2 fs-14 fw-bold has-transition {{ $buttonPadding }} px-20px w-100 mb-2 mb-md-0 product-action-btn"
        style="background-color: {{ get_setting('product_detail_chat_button_bg_color', '#1677ff') }}; color: {{ get_setting('product_detail_chat_button_text_color', '#ffffff') }}; min-height: 44px;">
        <span class="product-action-btn__content"><i class="las la-comments" aria-hidden="true"></i><span>{{ $chatButtonText }}</span></span>
    </a>
@endif
