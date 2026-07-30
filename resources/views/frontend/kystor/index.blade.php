@extends('frontend.layouts.app')

@section('content')
@php
    $lang = optional(get_system_language())->code;
    $sliderImageIds = json_decode(get_setting('home_slider_images', null, $lang), true) ?: [];
    $sliders = get_slider_images($sliderImageIds);
    $sliderLinks = json_decode(get_setting('home_slider_links', null, $lang), true) ?: [];
    $bestSellingProducts = get_best_selling_products(12);
    $todaysDealProducts = get_todays_deal_products(6);
    $featuredProducts = get_featured_products();
    $flashDeal = get_featured_flash_deal();
    $categoryRail = collect($featured_categories)->take(10);
    $hotCategoryCollection = collect($hot_categories);
    $heroCategories = ($hotCategoryCollection->isNotEmpty() ? $hotCategoryCollection : $categoryRail)->take(6);
@endphp

<main class="kystore-home">
    <section class="kystore-hero-shell">
        <div class="container">
            <div class="kystore-service-bar">
                <div class="kystore-service-item">
                    <span class="kystore-service-icon"><i class="las la-truck"></i></span>
                    <span><strong>{{ translate('Fast delivery') }}</strong><small>{{ translate('Across the country') }}</small></span>
                </div>
                <div class="kystore-service-item">
                    <span class="kystore-service-icon"><i class="las la-shield-alt"></i></span>
                    <span><strong>{{ translate('Secure payment') }}</strong><small>{{ translate('Protected checkout') }}</small></span>
                </div>
                <div class="kystore-service-item">
                    <span class="kystore-service-icon"><i class="las la-undo-alt"></i></span>
                    <span><strong>{{ translate('Easy returns') }}</strong><small>{{ translate('Shop with confidence') }}</small></span>
                </div>
                <div class="kystore-service-item">
                    <span class="kystore-service-icon"><i class="las la-headset"></i></span>
                    <span><strong>{{ translate('Friendly support') }}</strong><small>{{ translate('Here when you need us') }}</small></span>
                </div>
            </div>

            <div class="row gutters-16">
                <div class="col-lg-8">
                    <div class="kystore-hero-card">
                        @if (count($sliders) > 0)
                            <div class="aiz-carousel kystore-hero-slider" data-autoplay="true" data-infinite="true"
                                data-arrows="true" data-dots="true">
                                @foreach ($sliders as $key => $slider)
                                    <div class="carousel-box">
                                        <a href="{{ $sliderLinks[$key] ?? route('search') }}" class="kystore-hero-slide">
                                            <img
                                                src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder-rect.jpg') }}"
                                                alt="{{ env('APP_NAME') }} {{ translate('collection') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            <span class="kystore-hero-shade"></span>
                                            <!-- <span class="kystore-hero-copy">
                                                <span class="kystore-eyebrow">{{ translate('Fresh picks for you') }}</span>
                                                <strong>{{ translate('Discover something worth keeping') }}</strong>
                                                <span class="kystore-hero-cta">{{ translate('Shop collection') }} <i class="las la-arrow-right"></i></span>
                                            </span> -->
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="kystore-hero-fallback">
                                <div class="kystore-hero-fallback-copy">
                                    <span class="kystore-eyebrow">{{ translate('Welcome to') }} {{ get_setting('site_name') }}</span>
                                    <h1>{{ translate('Everyday finds, thoughtfully selected.') }}</h1>
                                    <p>{{ translate('Explore useful products, standout style, and prices that feel right.') }}</p>
                                    <a href="{{ route('search') }}" class="kystore-btn kystore-btn-light">
                                        {{ translate('Start shopping') }} <i class="las la-arrow-right"></i>
                                    </a>
                                </div>
                                @if (count($bestSellingProducts) > 0)
                                    <div class="kystore-hero-products" aria-label="{{ translate('Popular products') }}">
                                        <span class="kystore-hero-orbit kystore-hero-orbit-one"></span>
                                        <span class="kystore-hero-orbit kystore-hero-orbit-two"></span>
                                        @foreach ($bestSellingProducts->take(3) as $key => $heroProduct)
                                            <a href="{{ route('product', $heroProduct->slug) }}"
                                                class="kystore-floating-product kystore-floating-product-{{ $key + 1 }}">
                                                <span class="kystore-floating-product-image">
                                                    <img src="{{ get_image($heroProduct->thumbnail) }}"
                                                        alt="{{ $heroProduct->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </span>
                                                <span class="kystore-floating-product-info">
                                                    <small>{{ translate('Popular pick') }}</small>
                                                    <strong>{{ $heroProduct->getTranslation('name') }}</strong>
                                                    <b>{{ home_discounted_base_price($heroProduct) }}</b>
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">
                    <div class="kystore-side-stack">
                        <div class="kystore-category-panel">
                            <div class="kystore-panel-heading">
                                <div>
                                    <span class="kystore-eyebrow">{{ translate('Browse faster') }}</span>
                                    <h2>{{ translate('Popular categories') }}</h2>
                                </div>
                                <a href="{{ route('categories.all') }}" aria-label="{{ translate('All Categories') }}">
                                    <i class="las la-arrow-right"></i>
                                </a>
                            </div>
                            <div class="kystore-mini-category-grid">
                                @forelse ($heroCategories as $category)
                                    @php $categoryName = $category->getTranslation('name'); @endphp
                                    <a href="{{ route('products.category', $category->slug) }}" class="kystore-mini-category">
                                        <span class="kystore-mini-category-image">
                                            <img class="lazyload"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ isset($category->banner) ? uploaded_asset($category->banner) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ $categoryName }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </span>
                                        <span>{{ $categoryName }}</span>
                                    </a>
                                @empty
                                    <a href="{{ route('categories.all') }}" class="kystore-empty-link">
                                        <i class="las la-th-large"></i>
                                        <span>{{ translate('Explore all categories') }}</span>
                                    </a>
                                @endforelse
                            </div>
                        </div>

                        @if ($flashDeal)
                            <a href="{{ route('flash-deal-details', $flashDeal->slug) }}" class="kystore-flash-card">
                                <img src="{{ uploaded_asset($flashDeal->banner) }}" alt="{{ $flashDeal->title }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                <span class="kystore-flash-overlay"></span>
                                <span class="kystore-flash-copy">
                                    <span><i class="las la-bolt"></i> {{ translate('Limited time') }}</span>
                                    <strong>{{ $flashDeal->title }}</strong>
                                    <small>{{ translate('Shop the flash sale') }} <i class="las la-arrow-right"></i></small>
                                </span>
                            </a>
                        @else
                            <a href="{{ route('search') }}" class="kystore-flash-card kystore-flash-fallback">
                                <span class="kystore-flash-copy">
                                    <span><i class="las la-sparkles"></i> {{ translate('New this week') }}</span>
                                    <strong>{{ translate('A better way to discover your next favorite') }}</strong>
                                    <small>{{ translate('Explore products') }} <i class="las la-arrow-right"></i></small>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($categoryRail->isNotEmpty())
        <section class="kystore-section kystore-category-section">
            <div class="container">
                <div class="kystore-section-heading">
                    <div>
                        <span class="kystore-eyebrow">{{ translate('Shop your way') }}</span>
                        <h2>{{ translate('Featured categories') }}</h2>
                    </div>
                    <a href="{{ route('categories.all') }}" class="kystore-text-link">
                        {{ translate('View all') }} <i class="las la-arrow-right"></i>
                    </a>
                </div>
                <div class="aiz-carousel kystore-category-carousel" data-items="6" data-xl-items="5"
                    data-lg-items="4" data-md-items="3" data-sm-items="2.5" data-xs-items="1.65"
                    data-arrows="true" data-dots="false" data-infinite="false">
                    @foreach ($categoryRail as $key => $category)
                        @php
                            $categoryName = $category->getTranslation('name');
                            $categoryImage = optional($category->coverImage)->file_name;
                            $categoryIcons = ['la-tshirt', 'la-gift', 'la-motorcycle', 'la-mobile-alt', 'la-car', 'la-fire-alt'];
                            $categoryIcon = $categoryIcons[$key % count($categoryIcons)];
                        @endphp
                        <div class="carousel-box">
                            <a href="{{ route('products.category', $category->slug) }}" class="kystore-category-card">
                                <span class="kystore-category-image {{ $categoryImage ? 'has-image' : 'no-image' }}">
                                    @if ($categoryImage)
                                        <img class="lazyload"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ my_asset($categoryImage) }}"
                                            alt="{{ $categoryName }}"
                                            onerror="this.onerror=null;this.closest('.kystore-category-image').classList.remove('has-image');this.closest('.kystore-category-image').classList.add('no-image');this.style.display='none';">
                                    @endif
                                    <span class="kystore-category-placeholder kystore-category-placeholder-{{ ($key % 4) + 1 }}">
                                        <i class="las {{ $categoryIcon }}"></i>
                                        <b>{{ mb_strtoupper(mb_substr($categoryName, 0, 1)) }}</b>
                                    </span>
                                </span>
                                <strong>{{ $categoryName }}</strong>
                                <small>{{ translate('Explore now') }} <i class="las la-arrow-right"></i></small>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (count($bestSellingProducts) > 0 || count($todaysDealProducts) > 0)
        <section class="kystore-section">
            <div class="container">
                <div class="row gutters-16 align-items-stretch">
                    @if (count($bestSellingProducts) > 0)
                        <div class="col-lg-8">
                            <div class="kystore-product-surface h-100">
                                <div class="kystore-section-heading">
                                    <div>
                                        <span class="kystore-eyebrow">{{ translate('Loved by shoppers') }}</span>
                                        <h2>{{ translate('Best sellers') }}</h2>
                                    </div>
                                    <a href="{{ route('best-selling') }}" class="kystore-text-link">
                                        {{ translate('View all') }} <i class="las la-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="aiz-carousel kystore-product-carousel gutters-10" data-items="4"
                                    data-xl-items="4" data-lg-items="3" data-md-items="3" data-sm-items="2"
                                    data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false">
                                    @foreach ($bestSellingProducts as $product)
                                        <div class="carousel-box">
                                            @include('frontend.kystor.partials.product_box_1', ['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (count($todaysDealProducts) > 0)
                        <div class="col-lg-4 mt-3 mt-lg-0">
                            <div class="kystore-deal-surface h-100">
                                <div class="kystore-section-heading kystore-section-heading-light">
                                    <div>
                                        <span class="kystore-eyebrow">{{ translate('Today only') }}</span>
                                        <h2>{{ translate("Today's deals") }}</h2>
                                    </div>
                                    <a href="{{ route('todays-deal') }}"><i class="las la-arrow-right"></i></a>
                                </div>
                                <div class="aiz-carousel kystore-deal-carousel" data-items="1" data-arrows="false"
                                    data-dots="true" data-autoplay="true" data-infinite="true">
                                    @foreach ($todaysDealProducts as $product)
                                        <div class="carousel-box">
                                            @include('frontend.kystor.partials.deal_card', ['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @php
        $homeBanner1Images = json_decode(get_setting('home_banner1_images', null, $lang), true) ?: [];
        $homeBanner1Links = json_decode(get_setting('home_banner1_links', null, $lang), true) ?: [];
    @endphp
    @if (count($homeBanner1Images) > 0)
        <section class="kystore-section kystore-banner-section">
            <div class="container">
                <div class="row gutters-16">
                    @foreach (array_slice($homeBanner1Images, 0, 3) as $key => $image)
                        <div class="{{ $key === 0 && count($homeBanner1Images) > 1 ? 'col-lg-7' : 'col-lg' }} mt-3 mt-lg-0">
                            <a href="{{ $homeBanner1Links[$key] ?? route('search') }}" class="kystore-banner-card">
                                <span class="kystore-banner-fallback-copy">
                                    <span class="kystore-eyebrow">{{ translate('Made for discovery') }}</span>
                                    <strong>{{ translate('More value. More choice. More to explore.') }}</strong>
                                    <small>{{ translate('Shop the latest collection') }} <i class="las la-arrow-right"></i></small>
                                </span>
                                <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($image) }}" alt="{{ env('APP_NAME') }} {{ translate('promotion') }}"
                                    onerror="this.onerror=null;this.style.display='none';">
                                <span class="kystore-banner-arrow"><i class="las la-arrow-up"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (count($featuredProducts) > 0)
        <section class="kystore-section kystore-muted-section">
            <div class="container">
                <div class="kystore-section-heading">
                    <div>
                        <span class="kystore-eyebrow">{{ translate("Editor's selection") }}</span>
                        <h2>{{ translate('Featured products') }}</h2>
                    </div>
                    <a href="{{ route('featured-products') }}" class="kystore-text-link">
                        {{ translate('View all') }} <i class="las la-arrow-right"></i>
                    </a>
                </div>
                @if (count($featuredProducts) === 1)
                    @php
                        $featuredProduct = $featuredProducts->first();
                        $featuredProductUrl = $featuredProduct->auction_product == 1
                            ? route('auction-product', $featuredProduct->slug)
                            : route('product', $featuredProduct->slug);
                    @endphp
                    <article class="kystore-feature-spotlight">
                        <a href="{{ $featuredProductUrl }}" class="kystore-feature-visual">
                            <span class="kystore-feature-watermark">{{ translate('Featured') }}</span>
                            <img src="{{ get_image($featuredProduct->thumbnail) }}"
                                alt="{{ $featuredProduct->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            @if (discount_in_percentage($featuredProduct) > 0)
                                <span class="kystore-feature-discount">-{{ discount_in_percentage($featuredProduct) }}%</span>
                            @endif
                        </a>
                        <div class="kystore-feature-content">
                            <span class="kystore-eyebrow">{{ translate('The standout pick') }}</span>
                            <div class="kystore-product-rating">{!! renderStarRating($featuredProduct->rating) !!}</div>
                            <h3>{{ $featuredProduct->getTranslation('name') }}</h3>
                            <p>{{ translate('A featured find selected for its value, quality, and everyday usefulness.') }}</p>
                            <div class="kystore-feature-price">
                                <strong>{{ home_discounted_base_price($featuredProduct) }}</strong>
                                @if (home_base_price($featuredProduct) != home_discounted_base_price($featuredProduct))
                                    <del>{{ home_base_price($featuredProduct) }}</del>
                                @endif
                            </div>
                            <div class="kystore-feature-actions">
                                <a href="{{ $featuredProductUrl }}" class="kystore-btn kystore-btn-dark">
                                    {{ translate('View product') }} <i class="las la-arrow-right"></i>
                                </a>
                                <button type="button" onclick="addToWishList({{ $featuredProduct->id }})"
                                    aria-label="{{ translate('Add to wishlist') }}">
                                    <i class="lar la-heart"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                @else
                    <div class="kystore-product-grid">
                        @foreach ($featuredProducts as $product)
                            @include('frontend.kystor.partials.product_box_1', ['product' => $product])
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    <div id="section_home_categories" class="kystore-home-categories"></div>

    @if (addon_is_activated('auction'))
        <div id="auction_products" class="kystore-section"></div>
    @endif

    @if (addon_is_activated('preorder'))
        <section class="kystore-section">
            @include('preorder.frontend.home_page.kystor.newest_preorder')
        </section>
    @endif

    <section class="kystore-section kystore-new-arrivals">
        <div class="container">
            <div class="kystore-section-heading">
                <div>
                    <span class="kystore-eyebrow">{{ translate('Just landed') }}</span>
                    <h2>{{ translate('New arrivals') }}</h2>
                </div>
                <a href="{{ route('search') }}" class="kystore-text-link">
                    {{ translate('Shop all') }} <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
        <div id="section_newest"></div>
        <div class="container text-center d-none" id="view-more-container">
            <div class="kystore-load-more">
                <span class="kystore-load-more-line"></span>
                <button type="button" class="kystore-btn kystore-btn-dark" id="view-more-btn">
                    <span>{{ translate('Load more products') }}</span>
                    <i class="las la-arrow-down"></i>
                </button>
                <span class="kystore-load-more-line"></span>
            </div>
        </div>
    </section>

    <section class="kystore-newsletter-callout">
        <div class="container">
            <div class="kystore-newsletter-inner">
                <div>
                    <span class="kystore-eyebrow">{{ translate('Stay in the loop') }}</span>
                    <h2>{{ translate('Good finds should never be hard to discover.') }}</h2>
                    <p>{{ translate('Browse the latest products and limited offers selected for you.') }}</p>
                </div>
                <a href="{{ route('search') }}" class="kystore-btn kystore-btn-light">
                    {{ translate('Explore the store') }} <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
<script>
    let kystorePage = 1;

    $(document).on('click', '#view-more-btn', function () {
        const button = $(this);
        const originalContent = button.html();

        kystorePage += 1;
        button.prop('disabled', true).html(
            '<span>{{ translate("Loading") }}</span><i class="las la-spinner la-spin"></i>'
        );

        $.post('{{ route('home.section.newest_products') }}', {
            _token: '{{ csrf_token() }}',
            page: kystorePage,
            limit: 12
        }).done(function (data) {
            if ($.trim(data) === '') {
                button.html('<span>{{ translate("No more products") }}</span><i class="las la-check"></i>');
                return;
            }

            $('#newest-products-list').append(data);
            button.prop('disabled', false).html(originalContent);
            AIZ.plugins.slickCarousel();
        }).fail(function () {
            button.prop('disabled', false).html(originalContent);
            AIZ.plugins.notify('danger', '{{ translate("Something went wrong!") }}');
        });
    });

    function toggleViewMoreButton() {
        const hasProducts = $.trim($('#section_newest').html()).length > 0;
        $('#view-more-container').toggleClass('d-none', !hasProducts);
    }
</script>
@endsection
