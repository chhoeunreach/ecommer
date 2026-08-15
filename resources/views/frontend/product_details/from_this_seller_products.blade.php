<div
    class="frequently-bought-container py-20px px-30px border bg-white border-light-gray rounded-2">
    @php
        $shopslug = $detailedProduct->user->shop
            ? $detailedProduct->user->shop->slug
            : 'in-house';
    @endphp
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <p class="fs-20 fw-bold text-dark m-0">{{ translate('Products from this Seller') }}</p>
        <a type="button"
            class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center"
            href="{{ route('same_seller_products', $shopslug) }}">
            <span><i class="las la-angle-right fs-20 fw-600"></i></span>
            <span class="fs-12 mr-2 text">{{ translate('View All') }}</span>
        </a>
    </div>

    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="6" data-xxl-items="6"
        data-xl-items="6" data-lg-items="5" data-md-items="4" data-sm-items="4"
        data-xs-items="3" data-arrows="false" data-dots="false" data-autoplay="true"
        data-infinite="true">

        <!--Single-->
        @forelse (get_same_seller_products($detailedProduct->user_id , 20) as $key => $same_seller_product)
        <div class="carousel-box px-2 mb-3">
            <div class="bg-white border border-light-gray rounded-3 hov-shadow-sm has-transition p-2 d-flex flex-column h-100">
                <div class="img h-90px h-sm-120px h-md-150px h-lg-170px h-xxl-190px w-100 rounded-2 overflow-hidden position-relative image-hover-effect">
                    <a href="{{ route('product', $same_seller_product->slug) }}" title="{{ $same_seller_product->getTranslation('name') }}" class="d-block w-100 h-100 position-relative">
                        <img class="lazyload img-fit m-auto has-transition product-main-image w-100 h-100"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($same_seller_product->thumbnail_img) }}"
                            alt="{{ $same_seller_product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                        <img class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute w-100 h-100 top-0 left-0"
                            src="{{ get_first_product_image($same_seller_product->thumbnail, $same_seller_product->photos) }}" alt="{{ $same_seller_product->getTranslation('name') }}"
                            title="{{ $same_seller_product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                </div>
                <div class="mt-2 flex-grow-1 d-flex flex-column justify-content-between">
                    <h3 class="fw-500 fs-13 text-truncate-2 lh-1-4 mb-2">
                        <a href="{{ route('product', $same_seller_product->slug) }}" class="text-reset hov-text-primary">{{ $same_seller_product->getTranslation('name') }}</a>
                    </h3>
                    <div class="d-flex align-items-center flex-wrap">
                         <span class="fw-800 fs-14 text-dark">{{ home_discounted_base_price($same_seller_product) }}</span>
                        @if (home_base_price($same_seller_product) != home_discounted_base_price($same_seller_product))
                            <del class="fw-500 fs-12 text-gray ml-2">{{ home_base_price($same_seller_product) }}</del>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-2 w-100">
            <h5 class="fs-16 fw-bold text-dark">{{ translate('No products from this seller found!') }}</h5>
        </div>
        @endforelse
    </div>
</div>