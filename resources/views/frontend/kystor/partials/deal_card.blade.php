@php
    $productUrl = route('product', $product->slug);
@endphp

<article class="kystore-deal-card">
    <a href="{{ $productUrl }}" class="kystore-deal-image">
        <img class="lazyload"
            src="{{ static_asset('assets/img/placeholder.jpg') }}"
            data-src="{{ get_image($product->thumbnail) }}"
            alt="{{ $product->getTranslation('name') }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        @if (discount_in_percentage($product) > 0)
            <span>-{{ discount_in_percentage($product) }}%</span>
        @endif
    </a>
    <div class="kystore-deal-content">
        <div class="kystore-product-rating">{!! renderStarRating($product->rating) !!}</div>
        <h3><a href="{{ $productUrl }}">{{ $product->getTranslation('name') }}</a></h3>
        <div class="kystore-deal-price">
            <strong>{{ home_discounted_base_price($product) }}</strong>
            @if (home_base_price($product) != home_discounted_base_price($product))
                <del>{{ home_base_price($product) }}</del>
            @endif
        </div>
        <a href="{{ $productUrl }}" class="kystore-deal-link">
            {{ translate('View deal') }} <i class="las la-arrow-right"></i>
        </a>
    </div>
</article>
