@php
    $productUrl = $product->auction_product == 1
        ? route('auction-product', $product->slug)
        : route('product', $product->slug);
    $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
    $attributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
    $hasVariants = (is_array($colors) && count($colors) > 0)
        || (is_array($attributes) && count($attributes) > 0);
@endphp

<article class="kystore-product-card">
    <div class="kystore-product-media">
        <a href="{{ $productUrl }}" class="kystore-product-image image-hover-effect">
            <img class="lazyload product-main-image"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <img class="lazyload product-hover-image"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <div class="kystore-product-badges">
            @if (discount_in_percentage($product) > 0)
                <span class="kystore-badge kystore-badge-sale">-{{ discount_in_percentage($product) }}%</span>
            @endif
            @if ($product->wholesale_product)
                <span class="kystore-badge">{{ translate('Wholesale') }}</span>
            @endif
        </div>

        @if ($product->auction_product == 0)
            <div class="kystore-product-actions">
                <button type="button" onclick="addToWishList({{ $product->id }})"
                    aria-label="{{ translate('Add to wishlist') }}" data-toggle="tooltip"
                    data-title="{{ translate('Add to wishlist') }}">
                    <i class="las la-heart"></i>
                </button>
                <button type="button" onclick="addToCompare({{ $product->id }})"
                    aria-label="{{ translate('Add to compare') }}" data-toggle="tooltip"
                    data-title="{{ translate('Add to compare') }}">
                    <i class="las la-random"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="kystore-product-content">
        <div class="kystore-product-rating">
            {!! renderStarRating($product->rating) !!}
        </div>
        <h3>
            <a href="{{ $productUrl }}" title="{{ $product->getTranslation('name') }}">
                {{ $product->getTranslation('name') }}
            </a>
        </h3>

        <div class="kystore-product-footer">
            <div class="kystore-product-price">
                @if ($product->auction_product == 0)
                    <strong>{{ home_discounted_base_price($product) }}</strong>
                    @if (home_base_price($product) != home_discounted_base_price($product))
                        <del>{{ home_base_price($product) }}</del>
                    @endif
                @else
                    <small>{{ translate('Starting bid') }}</small>
                    <strong>{{ single_price($product->starting_bid) }}</strong>
                @endif
            </div>

            @if ($product->auction_product == 0)
                @if ($hasVariants)
                    <button type="button" class="kystore-add-button"
                        onclick="showAddToCartRightCanvas({{ $product->id }})"
                        aria-label="{{ translate('Select options') }}">
                        <i class="las la-sliders-h"></i>
                    </button>
                @else
                    <button type="button" class="kystore-add-button"
                        @if (Auth::check() || get_setting('guest_checkout_activation') == 1)
                            onclick="addToCartSingleProduct({{ $product->id }})"
                        @else
                            onclick="showLoginModal()"
                        @endif
                        aria-label="{{ translate('Add to Cart') }}">
                        <i class="las la-plus"></i>
                    </button>
                @endif
            @else
                <a href="{{ $productUrl }}" class="kystore-add-button" aria-label="{{ translate('View product') }}">
                    <i class="las la-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
</article>
