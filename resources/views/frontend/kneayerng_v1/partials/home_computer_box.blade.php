@php
    $product_url = route('computers.show', $computer->id);
    $price = $computer->price;
    $discounted_price = $price;
    $discount_percent = 0;
    if ($computer->discount > 0) {
        if ($computer->discount_type == 'percent') {
            $discounted_price -= ($price * $computer->discount) / 100;
            $discount_percent = $computer->discount;
        } elseif ($computer->discount_type == 'amount') {
            $discounted_price -= $computer->discount;
            $discount_percent = $price > 0 ? round(($computer->discount / $price) * 100) : 0;
        }
    }
@endphp
<div class="aiz-card-box shadcn-product-card ky-skeleton-host ky-computer-card">
    @include('frontend.kneayerng_v1.partials.skeleton_card')
    <div class="shadcn-card-img-wrap">
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100 position-relative">
            <img
                class="lazyload mx-auto product-main-image"
                src="{{ uploaded_asset($computer->thumbnail_img) }}"
                alt="{{ $computer->name }}"
                title="{{ $computer->name }}"
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
            <!-- Computer Title -->
            <h3 class="shadcn-product-title">
                <a href="{{ $product_url }}" title="{{ $computer->name }}">
                    {{ $computer->name }}
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
