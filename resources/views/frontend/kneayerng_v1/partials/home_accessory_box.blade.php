<div class="position-relative overflow-hidden rounded-2 img-aspect-ratio-320px product-card-wrapper">
    @php
        $product_url = route('accessories.show', $accessory->id);
    @endphp
    <a href="{{ $product_url }}" class="d-block w-100 h-100">
        <img class="w-100 h-100 lazyload has-transition product-main-image"
            src="{{ uploaded_asset($accessory->thumbnail_img) }}" data-src="{{ uploaded_asset($accessory->thumbnail_img) }}" alt="{{ $accessory->name }}"
            title="{{ $accessory->name }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
    </a>

    <!-- Badges -->
    <div class="position-absolute d-flex flex-column align-items-start badges-wrapper">
        @if ($accessory->discount > 0)
            @php
                $discount_percent = 0;
                if($accessory->discount_type == 'percent') {
                    $discount_percent = $accessory->discount;
                } elseif($accessory->discount_type == 'amount' && $accessory->price > 0) {
                    $discount_percent = round(($accessory->discount / $accessory->price) * 100);
                }
            @endphp
            @if($discount_percent > 0)
                <span class="fs-11 fw-600 text-center rounded-pill w-auto"
                    style="padding: 2px 8px; top:0px; background-color:var(--primary); color:white;">-{{ $discount_percent }}%</span>
            @endif
        @endif
    </div>

    <!-- Wishlist & Compare (Desktop on Hover) - Disabled for Accessory -->
    <!-- Add to Cart (Desktop on Hover) - Disabled for Accessory -->
</div>

<!-- Accessory Name -->
<a href="{{ $product_url }}" class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition" title="{{ $accessory->name }}">
    {{ $accessory->name }}
</a>
<!-- Price -->
<p class="mt-2 mb-0">
    @php
        $price = $accessory->price;
        $discounted_price = $price;
        if($accessory->discount > 0) {
            if($accessory->discount_type == 'percent') {
                $discounted_price -= ($price * $accessory->discount) / 100;
            } elseif($accessory->discount_type == 'amount') {
                $discounted_price -= $accessory->discount;
            }
        }
    @endphp
    <span class="fs-13 fs-md-16 text-dark fw-bold mr-1">{{ single_price($discounted_price) }}</span>
    @if ($price != $discounted_price)
        <del class="fs-11 fs-md-14 text-gray fw-400">{{ single_price($price) }}</del>
    @endif
</p>
