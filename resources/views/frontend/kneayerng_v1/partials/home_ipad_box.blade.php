@php
    $product_url = route('ipads.show', $ipad->id);
    $price = $ipad->price;
    $discounted_price = $price;
    $discount_percent = 0;
    if ($ipad->discount > 0) {
        if ($ipad->discount_type == 'percent') {
            $discounted_price -= ($price * $ipad->discount) / 100;
            $discount_percent = $ipad->discount;
        } elseif ($ipad->discount_type == 'amount') {
            $discounted_price -= $ipad->discount;
            $discount_percent = $price > 0 ? round(($ipad->discount / $price) * 100) : 0;
        }
    }
@endphp
<article class="aiz-card-box ky-skeleton-host ky-ipad-card">
    @include('frontend.kneayerng_v1.partials.skeleton_card')

    <a href="{{ $product_url }}" class="ky-ipad-card__media" aria-label="{{ $ipad->name }}">
        <span class="ky-ipad-card__badges">
            <span class="ky-ipad-card__badge ky-ipad-card__badge--new">{{ translate('New') }}</span>
            @if (!empty($discount_percent) && $discount_percent > 0)
                <span class="ky-ipad-card__badge ky-ipad-card__badge--off">-{{ $discount_percent }}%</span>
            @endif
        </span>
        <span class="ky-ipad-card__open" aria-hidden="true"><i class="las la-arrow-right"></i></span>
        <img
            class="lazyload product-main-image ky-ipad-card__image"
            src="{{ uploaded_asset($ipad->thumbnail_img) }}"
            alt="{{ $ipad->name }}"
            title="{{ $ipad->name }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
    </a>

    <div class="ky-ipad-card__body">
        <span class="ky-ipad-card__brand">{{ $ipad->brand ? $ipad->brand->name : translate('iPad') }}</span>

        <h3 class="ky-ipad-card__title">
            <a href="{{ $product_url }}" title="{{ $ipad->name }}">{{ $ipad->name }}</a>
        </h3>

        @if ($ipad->has_warranty)
            <span class="ky-ipad-card__chip"><i class="las la-shield-alt" aria-hidden="true"></i>{{ translate('Warranty') }}</span>
        @endif

        <div class="ky-ipad-card__footer">
            <div class="ky-ipad-card__price">
                <small>{{ translate('Starting at') }}</small>
                <span class="ky-ipad-card__price-now">{{ single_price($discounted_price) }}</span>
                @if ($price != $discounted_price)
                    <span class="ky-ipad-card__price-was">{{ single_price($price) }}</span>
                @endif
            </div>
            <a href="{{ $product_url }}" class="ky-ipad-card__cta" aria-label="{{ translate('View details') }}: {{ $ipad->name }}">
                <i class="las la-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
