@extends('frontend.layouts.app')

@php
    $c_meta_title = $computer->meta_title ?: $computer->name;
    $c_meta_description = $computer->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($computer->description ?? ''), 160);
    $c_meta_image = $computer->meta_img ? uploaded_asset($computer->meta_img) : uploaded_asset($computer->thumbnail_img);
    $computerBuyNowAlertEnabled = (int) get_setting('product_detail_buy_now_click_message_enabled', 0) === 1;
    $computerAddToCartAlertEnabled = (int) get_setting('product_detail_add_to_cart_click_message_enabled', 0) === 1;
    $computerBuyNowAlertMessage = trim((string) get_setting('product_detail_buy_now_click_message')) ?: translate('It is coming soon');
    $computerAddToCartAlertMessage = trim((string) get_setting('product_detail_add_to_cart_click_message')) ?: translate('It is coming soon');
@endphp

@section('meta_title'){{ $c_meta_title }}@stop
@section('meta_description'){{ $c_meta_description }}@stop
@section('meta_keywords'){{ $computer->tags }}@stop

@section('meta')
    <meta itemprop="name" content="{{ $c_meta_title }}">
    <meta itemprop="description" content="{{ $c_meta_description }}">
    <meta itemprop="image" content="{{ $c_meta_image }}">
    <meta name="twitter:card" content="product">
    <meta name="twitter:title" content="{{ $c_meta_title }}">
    <meta name="twitter:description" content="{{ $c_meta_description }}">
    <meta name="twitter:image" content="{{ $c_meta_image }}">
    <meta property="og:title" content="{{ $c_meta_title }}">
    <meta property="og:type" content="og:product">
    <meta property="og:url" content="{{ route('computers.show', $computer->id) }}">
    <meta property="og:image" content="{{ $c_meta_image }}">
    <meta property="og:description" content="{{ $c_meta_description }}">
    <meta property="og:site_name" content="{{ get_setting('website_name') ?: config('app.name') }}">
    <meta property="product:brand" content="{{ $computer->brand ? $computer->brand->name : config('app.name') }}">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($computer->price, 2, '.', '') }}">
@endsection

@section('style')
    <link rel="stylesheet" href="{{ static_asset('assets/css/computer-details.css?v=') }}{{ filemtime(public_path('assets/css/computer-details.css')) }}">
@endsection

@section('content')
@php
    $computerVariants = $computer->computer_variants;
    $facetDefinitions = ['Storage' => 'storage', 'Display' => 'display', 'RAM' => 'ram', 'CPU' => 'cpu', 'Chip' => 'chip'];
    $facets = [];

    if ($computerVariants->isNotEmpty()) {
        $defaultVariant = $computerVariants->first();
        $rawPrice = $defaultVariant->price > 0 ? $defaultVariant->price : $computer->price;
        $stockQty = (int) $computerVariants->sum('stock');
        $defaultSku = $computer->sku ?? 'N/A';

        foreach ($facetDefinitions as $label => $field) {
            $values = $computerVariants->pluck($field)->filter()->unique()->values();
            if ($values->isNotEmpty()) $facets[$field] = ['label' => $label, 'values' => $values];
        }
        $colorList = $computerVariants->pluck('color')->filter()->unique()->values();
    } else {
        $defaultStock = $computer->stocks->first();
        $defaultVariant = null;
        $rawPrice = ($defaultStock && $defaultStock->price > 0) ? $defaultStock->price : $computer->price;
        $stockQty = (int) ($defaultStock->qty ?? $computer->stock);
        $defaultSku = $defaultStock->sku ?? ($computer->sku ?? 'N/A');

        foreach ($facetDefinitions as $label => $field) {
            $values = $computer->stocks->pluck($field)->filter()->unique()->values();
            if ($values->isNotEmpty()) $facets[$field] = ['label' => $label, 'values' => $values];
        }
        $colorCodes = collect(json_decode($computer->colors ?? '[]', true) ?: []);
        $colorList = $colorCodes->map(fn ($code) => get_single_color_name($code));
    }

    if ($colorList->isEmpty()) {
        $colorCodes = collect(json_decode($computer->colors ?? '[]', true) ?: []);
        $colorList = $colorCodes->map(fn ($code) => get_single_color_name($code));
    }

    $discountedPrice = \App\Utility\CartUtility::discount_calculation($computer, $rawPrice);
    $discountPercent = 0;
    if ($rawPrice != $discountedPrice) {
        $discountPercent = $computer->discount_type === 'percent'
            ? round($computer->discount)
            : ($rawPrice > 0 ? round(($computer->discount / $rawPrice) * 100) : 0);
    }

    $galleryIds = $computer->gallery ? array_filter(array_map('trim', explode(',', $computer->gallery))) : [];
    $allImages = array_values(array_filter(array_unique(array_merge([$computer->thumbnail_img], $galleryIds))));
    $imageUrls = array_map(fn ($id) => uploaded_asset($id), $allImages);
    $mainImage = $imageUrls[0] ?? static_asset('assets/img/placeholder.jpg');

    $showContactSales = (int) get_setting('product_detail_show_contact_sales', 1) === 1;
    $contactSalesText = get_setting('product_detail_contact_sales_text') ?: translate('Contact Sales');
    $contactSalesNewTab = (int) get_setting('product_detail_contact_sales_new_tab', 1) === 1;
    $isTelegramUrl = static function ($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        return $scheme === 'https' && in_array($host, ['t.me', 'telegram.me', 'www.telegram.me'], true);
    };
    $typeContactSalesUrl = trim((string) get_setting('computer_detail_contact_sales_telegram_url'));
    $configuredContactSalesUrl = trim((string) get_setting('product_detail_contact_sales_telegram_url'));
    $chatButtonUrl = trim((string) get_setting('product_detail_chat_button_url'));
    $footerTelegramUrl = collect(footer_social_links())->firstWhere('platform', 'telegram')['url'] ?? '';
    $contactSalesUrl = $isTelegramUrl($typeContactSalesUrl)
        ? $typeContactSalesUrl
        : ($isTelegramUrl($configuredContactSalesUrl)
            ? $configuredContactSalesUrl
            : ($isTelegramUrl($chatButtonUrl)
                ? $chatButtonUrl
                : ($isTelegramUrl($footerTelegramUrl) ? $footerTelegramUrl : 'https://t.me/')));
@endphp

<main class="computer-product-page">
    <div class="computer-shell">
        <nav class="computer-breadcrumb" aria-label="{{ translate('Breadcrumb') }}">
            <a href="{{ route('home') }}">{{ translate('Home') }}</a><i class="las la-angle-right" aria-hidden="true"></i>
            <a href="{{ route('computers.index') }}">{{ translate('Computers') }}</a><i class="las la-angle-right" aria-hidden="true"></i>
            <span class="computer-breadcrumb__current" aria-current="page">{{ $computer->name }}</span>
        </nav>

        <article class="computer-product-card">
            <section class="computer-gallery" aria-label="{{ translate('Product images') }}">
                <div class="computer-gallery__stage" id="computer-gallery-stage">
                    @if($rawPrice != $discountedPrice)<span class="computer-gallery__badge">-{{ $discountPercent }}%</span>@endif
                    <button type="button" class="computer-gallery__share" data-toggle="modal" data-target="#shareModal" aria-label="{{ translate('Share this product') }}"><i class="las la-share-alt" aria-hidden="true"></i></button>
                    <img src="{{ $mainImage }}" alt="{{ $computer->name }}" id="computer-main-img">
                </div>

                @if(count($imageUrls) > 1)
                    <div class="computer-gallery__thumbs" role="list" aria-label="{{ translate('Choose product image') }}">
                        @foreach($imageUrls as $index => $imageUrl)
                            <button type="button" class="computer-thumb-box {{ $index === 0 ? 'active' : '' }}" onclick="showComputerImage({{ $index }}, this)" aria-label="{{ translate('View image') }} {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"><img src="{{ $imageUrl }}" alt=""></button>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="computer-details">
                <div class="computer-eyebrow">
                    @if($computer->brand)<span class="computer-brand"><i class="las la-laptop" aria-hidden="true"></i>{{ $computer->brand->getTranslation('name') }}</span>@endif
                    <span class="computer-status {{ $stockQty > 0 ? 'is-in-stock' : 'is-out-of-stock' }}">
                        <span class="computer-status__dot" aria-hidden="true"></span>
                        {{ $stockQty > 0 ? translate('In stock') . ' (' . $stockQty . ')' : translate('Out of Stock') }}
                    </span>
                </div>

                <h1 class="computer-title">{{ $computer->name }}</h1>
                <div class="computer-price" aria-live="polite">
                    <div>
                        <span class="computer-price__label">{{ translate('Price') }}</span>
                        <span class="computer-price__current" id="chosen_price">{{ single_price($discountedPrice) }}</span>
                        @if($rawPrice != $discountedPrice)<del class="computer-price__original">{{ single_price($rawPrice) }}</del>@endif
                    </div>
                    @if($discountPercent > 0)<span class="computer-price__saving">{{ translate('Save') }} {{ $discountPercent }}%</span>@endif
                </div>

                <form id="option-choice-form" class="product-details-page">
                    @csrf
                    <input type="hidden" name="id" value="{{ $computer->id }}">
                    <input type="hidden" name="product_type" value="computer">

                    @if($colorList->isNotEmpty() || count($facets) > 0)
                        <div class="computer-configurator">
                            <h2 class="computer-configurator__heading">{{ translate('Choose your configuration') }}</h2>
                            @if($colorList->isNotEmpty())
                                <fieldset class="computer-option-group">
                                    <legend>{{ translate('Color') }}</legend>
                                    <div class="computer-option-list">
                                        @foreach($colorList as $index => $colorName)
                                            @php
                                                $colorModel = \App\Models\Color::where('name', $colorName)->first();
                                                $colorHex = $colorModel ? $colorModel->code : '#6d3df1';
                                            @endphp
                                            <x-computer-option name="color" :value="$colorName" :checked="$index === 0" :color="$colorHex" />
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endif

                            @foreach($facets as $field => $facet)
                                <fieldset class="computer-option-group">
                                    <legend>{{ translate($facet['label']) }}</legend>
                                    <div class="computer-option-list">
                                        @foreach($facet['values'] as $index => $value)
                                            <x-computer-option :name="$field" :value="$value" :checked="$index === 0" />
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endforeach

                            <div class="computer-variant-meta">
                                <span><i class="las la-barcode mr-1" aria-hidden="true"></i>{{ translate('SKU') }}: <strong id="variant_sku">{{ $defaultSku }}</strong></span>
                                <span><i class="las la-check-circle mr-1" aria-hidden="true"></i><strong id="available-quantity">{{ $stockQty }}</strong> {{ translate('items available') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="computer-purchase">
                        <div class="computer-quantity-row">
                            <label for="computer-quantity-input">{{ translate('Quantity') }}</label>
                            <div class="computer-quantity">
                                <button type="button" data-type="minus" data-field="quantity" disabled aria-label="{{ translate('Decrease quantity') }}"><i class="las la-minus" aria-hidden="true"></i></button>
                                <input id="computer-quantity-input" type="text" name="quantity" class="input-number" value="1" min="1" max="{{ $stockQty > 0 ? $stockQty : 1 }}" inputmode="numeric" aria-label="{{ translate('Quantity') }}">
                                <button type="button" data-type="plus" data-field="quantity" aria-label="{{ translate('Increase quantity') }}"><i class="las la-plus" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div class="computer-actions {{ $stockQty < 1 ? 'd-none' : '' }}">
                            <button type="button"
                                @if ($computerBuyNowAlertEnabled)
                                    data-click-title="{{ translate('Coming Soon') }}" data-click-message="{{ $computerBuyNowAlertMessage }}"
                                    onclick="return showProductActionMessage(this)"
                                @else
                                    onclick="buyNow()"
                                @endif
                                class="computer-action computer-action--primary buy-now"><i class="las la-bolt" aria-hidden="true"></i>{{ translate('Buy Now') }}</button>
                            <button type="button"
                                @if ($computerAddToCartAlertEnabled)
                                    data-click-title="{{ translate('Coming Soon') }}" data-click-message="{{ $computerAddToCartAlertMessage }}"
                                    onclick="return showProductActionMessage(this)"
                                @else
                                    onclick="addToCart()"
                                @endif
                                class="computer-action computer-action--secondary add-to-cart"><i class="las la-shopping-bag" aria-hidden="true"></i>{{ translate('Add to Cart') }}</button>
                            @if ($showContactSales)
                                <a href="{{ $contactSalesUrl }}" data-telegram-url="{{ $contactSalesUrl }}"
                                    data-product-name="{{ $computer->getTranslation('name') }}"
                                    data-product-url="{{ route('computers.show', $computer->id) }}"
                                    onclick="return contactSalesOnTelegram(this)"
                                    @if ($contactSalesNewTab) target="_blank" rel="noopener noreferrer" @endif
                                    class="computer-action computer-action--contact contact-sales-btn"><i class="lab la-telegram-plane" aria-hidden="true"></i>{{ $contactSalesText }}</a>
                            @endif
                            <a href="{{ route('custom-pages.show_custom_page', 'contact-us') }}" class="computer-action computer-action--contact"><i class="las la-headset" aria-hidden="true"></i>{{ translate('Need help choosing? Contact us') }}</a>
                        </div>
                        <button type="button" class="computer-action computer-action--contact out-of-stock w-100 {{ $stockQty >= 1 ? 'd-none' : '' }}" disabled>{{ translate('Out of Stock') }}</button>
                    </div>
                </form>

                @if($computer->has_warranty && $computer->warranty)
                    <div class="computer-warranty">
                        <span class="computer-warranty__icon"><i class="las la-shield-alt" aria-hidden="true"></i></span>
                        <div><h3>{{ translate('Official warranty included') }}</h3><p>{{ $computer->warranty->getTranslation('text') }}</p></div>
                    </div>
                @endif
            </section>
        </article>

        <section class="computer-content-card">
            <ul class="nav nav-tabs computer-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-description" role="tab" aria-selected="true"><i class="las la-file-alt mr-1"></i>{{ translate('Overview') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-specs" role="tab" aria-selected="false"><i class="las la-list-alt mr-1"></i>{{ translate('Specifications') }}</a></li>
                @if($computer->has_warranty && $computer->warranty)<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-warranty" role="tab" aria-selected="false"><i class="las la-shield-alt mr-1"></i>{{ translate('Warranty') }}</a></li>@endif
            </ul>
            <div class="computer-content-card__body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-description" role="tabpanel">
                        @if($computer->description)
                            <div class="computer-description aiz-editor-data">{!! $computer->description !!}</div>
                        @else
                            <div class="computer-description">{{ translate('Experience high performance with') }} <strong>{{ $computer->name }}</strong>. @if($defaultVariant) {{ translate('Powered by') }} <strong>{{ $defaultVariant->chip }} {{ $defaultVariant->cpu }}</strong>, {{ translate('with') }} <strong>{{ $defaultVariant->ram }}</strong> {{ translate('RAM and') }} <strong>{{ $defaultVariant->storage }}</strong> {{ translate('storage') }}. @endif</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="tab-specs" role="tabpanel">
                        <dl class="computer-spec-list">
                            <div class="computer-spec"><dt>{{ translate('Computer Model') }}</dt><dd>{{ $computer->name }}</dd></div>
                            <div class="computer-spec"><dt>{{ translate('SKU Code') }}</dt><dd>{{ $computer->sku ?? 'N/A' }}</dd></div>
                            @if($computer->brand)<div class="computer-spec"><dt>{{ translate('Brand') }}</dt><dd>{{ $computer->brand->getTranslation('name') }}</dd></div>@endif
                            @if($defaultVariant)
                                <div class="computer-spec"><dt>{{ translate('Processor / Chip') }}</dt><dd>{{ trim($defaultVariant->chip . ' ' . $defaultVariant->cpu) }}</dd></div>
                                <div class="computer-spec"><dt>{{ translate('Storage') }}</dt><dd>{{ isset($facets['storage']) ? $facets['storage']['values']->implode(', ') : $defaultVariant->storage }}</dd></div>
                                <div class="computer-spec"><dt>{{ translate('RAM') }}</dt><dd>{{ isset($facets['ram']) ? $facets['ram']['values']->implode(', ') : $defaultVariant->ram }}</dd></div>
                                <div class="computer-spec"><dt>{{ translate('Display') }}</dt><dd>{{ $defaultVariant->display }}</dd></div>
                            @endif
                        </dl>
                    </div>
                    @if($computer->has_warranty && $computer->warranty)<div class="tab-pane fade" id="tab-warranty" role="tabpanel"><div class="computer-description">{{ $computer->warranty->getTranslation('text') }}</div></div>@endif
                </div>
            </div>
        </section>
    </div>
</main>

<div class="computer-page-loading" id="computer-page-loading" aria-hidden="true"><span></span></div>

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header px-4 py-3"><h5 class="modal-title fs-16 fw-700">{{ translate('Share this computer') }}</h5><button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-center mb-4" style="gap: 12px">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="btn btn-primary rounded-circle size-44px d-flex align-items-center justify-content-center" aria-label="Facebook"><i class="lab la-facebook-f fs-20"></i></a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($computer->name) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="btn btn-info rounded-circle size-44px d-flex align-items-center justify-content-center text-white" aria-label="Twitter"><i class="lab la-twitter fs-20"></i></a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($computer->name . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="btn btn-success rounded-circle size-44px d-flex align-items-center justify-content-center" aria-label="WhatsApp"><i class="lab la-whatsapp fs-20"></i></a>
                </div>
                <div class="input-group"><input type="text" class="form-control" value="{{ url()->current() }}" readonly id="shareLinkInput"><div class="input-group-append"><button type="button" class="btn btn-primary" onclick="copyShareLink()"><i class="las la-copy mr-1"></i>{{ translate('Copy') }}</button></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var computerImages = @json($imageUrls);

    function showComputerImage(index, thumb) {
        if (!computerImages[index]) return;
        var stage = document.getElementById('computer-gallery-stage');
        var mainImage = document.getElementById('computer-main-img');
        stage.classList.add('is-changing');
        window.setTimeout(function () {
            mainImage.src = computerImages[index];
            stage.classList.remove('is-changing');
        }, 120);
        document.querySelectorAll('.computer-thumb-box').forEach(function (button) {
            button.classList.remove('active');
            button.setAttribute('aria-pressed', 'false');
        });
        thumb.classList.add('active');
        thumb.setAttribute('aria-pressed', 'true');
    }

    function copyShareLink() {
        var input = document.getElementById('shareLinkInput');
        var copied = function () { AIZ.plugins.notify('success', '{{ translate("Link copied to clipboard!") }}'); };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(copied);
        } else {
            input.select();
            document.execCommand('copy');
            copied();
        }
    }

    window.getVariantPrice = function (triggerEl) {
        var $form = $('#option-choice-form');
        var data = $form.serializeArray();
        var $loading = $('#computer-page-loading');
        if (triggerEl) data.push({ name: 'changed_field', value: triggerEl.name });
        $loading.addClass('is-visible').attr('aria-hidden', 'false');

        $.ajax({
            type: 'POST',
            url: '{{ route('computers.variant_price') }}',
            data: data,
            success: function (res) {
                $('#chosen_price').html(res.price);
                $('#variant_sku').text(res.sku);
                $('#available-quantity').text(res.quantity);
                if (res.image) $('#computer-main-img').attr('src', res.image);
                ['color', 'storage', 'display', 'ram', 'cpu', 'chip'].forEach(function (field) {
                    if (res[field]) $form.find('input[name="' + field + '"][value="' + res[field] + '"]').prop('checked', true);
                });
                var $quantity = $form.find('input[name="quantity"]');
                $quantity.attr('max', Math.max(res.max_limit, 1));
                if (parseInt($quantity.val(), 10) > res.max_limit && res.max_limit > 0) $quantity.val(res.max_limit);
                $('.computer-actions').toggleClass('d-none', res.in_stock == 0);
                $('.out-of-stock').toggleClass('d-none', res.in_stock != 0);
            },
            error: function () { AIZ.plugins.notify('danger', '{{ translate("Unable to update this configuration. Please try again.") }}'); },
            complete: function () { $loading.removeClass('is-visible').attr('aria-hidden', 'true'); }
        });
    };
</script>
@endsection
