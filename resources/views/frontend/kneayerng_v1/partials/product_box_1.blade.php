@php
    $cart_added = [];
    $product_url = route('product', $product->slug);
    if ($product->auction_product == 1) {
        $product_url = route('auction-product', $product->slug);
    }
@endphp
<div class="aiz-card-box shadcn-product-card ky-skeleton-host">
    @include('frontend.kneayerng_v1.partials.skeleton_card')
    <div class="shadcn-card-img-wrap">
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100 position-relative">
            <img
                class="lazyload mx-auto product-main-image"
                src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <img
                class="lazyload mx-auto product-hover-image"
                src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Badges -->
        <div class="shadcn-badges-wrap">
            @if (discount_in_percentage($product) > 0)
                <span class="shadcn-badge shadcn-badge-discount">-{{ discount_in_percentage($product) }}%</span>
            @endif
            @if ($product->wholesale_product)
                <span class="shadcn-badge shadcn-badge-wholesale">{{ translate('Wholesale') }}</span>
            @endif
            @php
                $customLabels = get_custom_labels($product->custom_label_id);
            @endphp
            @if ($customLabels)
                @foreach ($customLabels as $customLabel)
                    <span class="shadcn-badge" style="background-color: {{ $customLabel->background_color }}; color: {{ $customLabel->text_color }};">
                        {{ $customLabel->text }}
                    </span>
                @endforeach
            @endif
        </div>

        @if ($product->auction_product == 0)
            <!-- Actions Overlay (Top Right) -->
            <div class="shadcn-actions-wrap">
                <!-- Wishlist Icon -->
                <a href="javascript:void(0)" class="shadcn-action-btn" onclick="addToWishList({{ $product->id }})"
                    aria-label="{{ translate('Add to wishlist') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </a>

                <!-- Compare Icon -->
                <a href="javascript:void(0)" class="shadcn-action-btn" onclick="addToCompare({{ $product->id }})"
                    aria-label="{{ translate('Add to compare') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 3h5v5"/>
                        <path d="M8 21H3v-5"/>
                        <path d="M21 3 14 10"/>
                        <path d="M3 21l7-7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <!-- Card Content -->
    <div class="shadcn-card-body">
        <div class="shadcn-card-content">
            <!-- Product Title -->
            <h3 class="shadcn-product-title">
                <a href="{{ $product_url }}">
                    {{ $product->getTranslation('name') }}
                </a>
            </h3>
            @if ($product->auction_product == 0)
                <div class="ky-product-variant">{{ translate('New') }}</div>
            @endif

            <!-- Pricing -->
            <div class="shadcn-price-container">
                @if ($product->auction_product == 0)
                    <span class="shadcn-price-current">{{ home_discounted_base_price($product) }}</span>
                    @if (home_base_price($product) != home_discounted_base_price($product))
                        <span class="shadcn-price-old">{{ home_base_price($product) }}</span>
                    @endif
                @else
                    <span class="shadcn-price-current">{{ single_price($product->starting_bid) }}</span>
                @endif
            </div>
        </div>

        <!-- Action Button (Add to Cart / Option / Bid) -->
        <div class="shadcn-card-action">
            @if ($product->auction_product == 0)
                @php
                    $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                    $attributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
                @endphp

                @if ( (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0) )
                    <a class="shadcn-cta-btn ky-options-button @if (in_array($product->id, $cart_added)) active @endif"
                        href="javascript:void(0)" onclick="showAddToCartRightCanvas({{ $product->id }})">
                        <i class="las la-sliders-h"></i>
                        <span>{{ translate('Select Options') }}</span>
                    </a>
                @else
                    <a class="shadcn-cta-btn ky-add-button @if (in_array($product->id, $cart_added)) active @endif"
                        href="javascript:void(0)" @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $product->id }})" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-cart ky-desktop-cart-icon"></i>
                        <i class="las la-plus ky-mobile-plus-icon" aria-hidden="true"></i>
                        <span>{{ translate('Add to Cart') }}</span>
                    </a> 
                @endif
            @elseif ($product->auction_product == 1 && $product->auction_start_date <= strtotime('now') && $product->auction_end_date >= strtotime('now'))
                @php
                    $carts = get_user_cart();
                    if (count($carts) > 0) {
                        $cart_added = $carts->pluck('product_id')->toArray();
                    }
                    $highest_bid = $product->bids->max('amount');
                    $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                    $gst_rate = gst_applicable_product_rate($product->id);
                @endphp
                <a class="shadcn-cta-btn @if (in_array($product->id, $cart_added)) active @endif"
                    href="javascript:void(0)" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }}, {{ $gst_rate }})">
                    <i class="las la-gavel"></i>
                    <span>{{ translate('Place Bid') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
