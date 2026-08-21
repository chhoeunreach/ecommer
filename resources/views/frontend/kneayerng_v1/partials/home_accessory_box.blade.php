@php
    $product_url = route('accessories.show', $accessory->id);
    $price = $accessory->price;
    $discounted_price = $price;
    if ($accessory->discount > 0) {
        if ($accessory->discount_type == 'percent') {
            $discounted_price -= ($price * $accessory->discount) / 100;
            $discount_percent = $accessory->discount;
        } elseif ($accessory->discount_type == 'amount') {
            $discounted_price -= $accessory->discount;
            $discount_percent = $price > 0 ? round(($accessory->discount / $price) * 100) : 0;
        }
    }
@endphp
<div class="aiz-card-box shadcn-product-card ky-skeleton-host ky-accessory-card">
    @include('frontend.kneayerng_v1.partials.skeleton_card')
    <div class="shadcn-card-img-wrap">
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100 position-relative">
            <img
                class="lazyload mx-auto product-main-image"
                src="{{ uploaded_asset($accessory->thumbnail_img) }}"
                alt="{{ $accessory->name }}"
                title="{{ $accessory->name }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Badges -->
        <div class="shadcn-badges-wrap">
            @if (!empty($discount_percent) && $discount_percent > 0)
                <span class="shadcn-badge shadcn-badge-discount">-{{ $discount_percent }}%</span>
            @endif
        </div>
    </div>

    <!-- Card Content -->
    <div class="shadcn-card-body">
        <div>
            <!-- Accessory Title -->
            <h3 class="shadcn-product-title">
                <a href="{{ $product_url }}" title="{{ $accessory->name }}">
                    {{ $accessory->name }}
                </a>
            </h3>

            <!-- Pricing -->
            <div class="shadcn-price-container">
                <span class="shadcn-price-current">{{ single_price($discounted_price) }}</span>
                @if ($price != $discounted_price)
                    <span class="shadcn-price-old">{{ single_price($price) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
