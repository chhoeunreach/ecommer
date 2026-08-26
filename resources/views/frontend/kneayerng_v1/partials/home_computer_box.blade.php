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
<article class="aiz-card-box ky-skeleton-host ky-computer-card">
    @include('frontend.kneayerng_v1.partials.skeleton_card')
    <a href="{{ $product_url }}" class="ky-computer-card__media" aria-label="{{ $computer->name }}">
        <span class="ky-computer-card__new">{{ translate('New') }}</span>
        @if (!empty($discount_percent) && $discount_percent > 0)
            <span class="ky-computer-card__discount">-{{ $discount_percent }}%</span>
        @endif
        <span class="ky-computer-card__open" aria-hidden="true"><i class="las la-arrow-up"></i></span>
        <span class="ky-computer-card__image">
            <img
                class="lazyload mx-auto product-main-image"
                src="{{ uploaded_asset($computer->thumbnail_img) }}"
                alt="{{ $computer->name }}"
                title="{{ $computer->name }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </span>
    </a>

    <div class="ky-computer-card__body">
        <div class="ky-computer-card__meta">
            <span>{{ $computer->brand ? $computer->brand->name : translate('Computer') }}</span>
            @if ($computer->is_variant)
                <span class="ky-computer-card__config"><i class="las la-sliders-h" aria-hidden="true"></i>{{ translate('Customizable') }}</span>
            @endif
        </div>
        <h3 class="ky-computer-card__title">
            <a href="{{ $product_url }}" title="{{ $computer->name }}">{{ $computer->name }}</a>
        </h3>

        <div class="ky-computer-card__footer">
            <div class="ky-computer-card__pricing">
                <small>{{ translate('Starting at') }}</small>
                <span class="shadcn-price-current">{{ single_price($discounted_price) }}</span>
                @if ($price != $discounted_price)
                    <span class="shadcn-price-old">{{ single_price($price) }}</span>
                @endif
            </div>
            <a href="{{ $product_url }}" class="ky-computer-card__cta" aria-label="{{ translate('View details') }}: {{ $computer->name }}">
                <i class="las la-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
