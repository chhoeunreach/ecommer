<div
    class="frequently-bought-container py-3 py-md-20px px-3 px-md-30px border-md bg-white border-light-gray rounded-2 mobile-section-container">
    @php
        $shopslug = $detailedProduct->user->shop
            ? $detailedProduct->user->shop->slug
            : 'in-house';
    @endphp
    <style>
        @media (max-width: 576px) {
            .mobile-section-container {
                border: none !important;
                border-radius: 0 !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
                margin-left: -15px !important;
                margin-right: -15px !important;
                width: calc(100% + 30px) !important;
            }
            .mobile-section-title {
                font-size: 16px !important;
            }
        }
        .premium-app-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            padding: 12px;
            border: 1px solid #f1f5f9;
        }
        .premium-app-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            transform: translateY(-5px);
            border-color: #e2e8f0;
        }
        .premium-img-wrapper {
            border-radius: 14px;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .premium-app-card .product-hover-image {
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }
        .premium-app-card:hover .product-main-image:not(.product-hover-image) {
            opacity: 0;
        }
        .premium-app-card:hover .product-hover-image {
            opacity: 1;
            visibility: visible;
        }
        .premium-action-pill {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%) translateY(15px);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.5);
            gap: 12px;
        }
        .premium-app-card:hover .premium-action-pill {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .premium-action-btn {
            color: #64748b;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .premium-action-btn i {
            font-size: 18px;
        }
        .premium-action-btn:hover {
            color: var(--primary);
            background-color: rgba(0,0,0,0.03);
            transform: scale(1.1);
        }
            .premium-app-card h3 {
                font-size: 12px !important;
            }
        @media (max-width: 576px) {
            .premium-app-card {
                padding: 8px;
                border-radius: 14px;
            }
            .premium-img-wrapper {
                border-radius: 10px;
            }
            .premium-quick-view {
                width: 30px;
                height: 30px;
                bottom: 6px;
                right: 6px;
            }
            .premium-quick-view i {
                font-size: 16px !important;
            }
            .premium-app-card h3 {
                font-size: 12px !important;
            }
            .premium-app-card .fs-16 {
                font-size: 14px !important;
            }
        }
        .premium-discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3);
            z-index: 3;
            letter-spacing: 0.5px;
        }
    </style>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <p class="fs-20 fw-bold text-dark m-0 mobile-section-title">{{ translate('Products from this Seller') }}</p>
        <a type="button"
            class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center"
            href="{{ route('same_seller_products', $shopslug) }}">
            <span><i class="las la-angle-right fs-20 fw-600"></i></span>
            <span class="fs-12 mr-2 text">{{ translate('View All') }}</span>
        </a>
    </div>

    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="6" data-xxl-items="6"
        data-xl-items="6" data-lg-items="5" data-md-items="4" data-sm-items="3"
        data-xs-items="2" data-arrows="false" data-dots="false" data-autoplay="true"
        data-infinite="true">

        <!--Single-->
        @forelse (get_same_seller_products($detailedProduct->user_id , 20) as $key => $same_seller_product)
        <div class="carousel-box px-1 px-md-2 mb-4">
            <div class="d-flex flex-column h-100 premium-app-card">
                <div class="img h-130px h-sm-160px h-md-190px h-lg-220px w-100 position-relative image-hover-effect premium-img-wrapper mb-3">
                    @if(home_base_price($same_seller_product) != home_discounted_base_price($same_seller_product))
                        <span class="premium-discount-badge">-{{ discount_in_percentage($same_seller_product) }}%</span>
                    @endif
                    <a href="{{ route('product', $same_seller_product->slug) }}" title="{{ $same_seller_product->getTranslation('name') }}" class="d-block w-100 h-100 position-relative">
                        <img class="lazyload w-100 h-100 product-main-image" style="object-fit: cover; transition: all 0.4s ease;"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($same_seller_product->thumbnail_img) }}"
                            alt="{{ $same_seller_product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                        <img class="lazyload w-100 h-100 product-main-image product-hover-image position-absolute top-0 left-0" style="object-fit: cover;"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ get_first_product_image($same_seller_product->thumbnail, $same_seller_product->photos) }}" alt="{{ $same_seller_product->getTranslation('name') }}"
                            title="{{ $same_seller_product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                    <div class="premium-action-pill">
                        <a href="javascript:void(0)" onclick="showAddToCartModal({{ $same_seller_product->id }})" class="premium-action-btn" title="{{ translate('Add to cart') }}">
                            <i class="las la-sliders-h"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="addToWishList({{ $same_seller_product->id }})" class="premium-action-btn" title="{{ translate('Add to wishlist') }}">
                            <i class="la la-heart-o"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="addToCompare({{ $same_seller_product->id }})" class="premium-action-btn" title="{{ translate('Add to compare') }}">
                            <i class="las la-sync"></i>
                        </a>
                    </div>
                </div>
                <div class="px-2 d-flex flex-column justify-content-between flex-grow-1 pb-1">
                    <div>
                        <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-1">
                            <a href="{{ route('product', $same_seller_product->slug) }}" class="text-dark hov-text-primary text-decoration-none">{{ $same_seller_product->getTranslation('name') }}</a>
                        </h3>
                        <div class="rating rating-mr-1 mb-2 fs-10" style="color: #f59e0b;">
                            {{ renderStarRating($same_seller_product->rating) }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap mt-auto">
                        <span class="fw-800 fs-16 text-primary mr-2">{{ home_discounted_base_price($same_seller_product) }}</span>
                        @if (home_base_price($same_seller_product) != home_discounted_base_price($same_seller_product))
                            <del class="fw-500 fs-12 text-gray">{{ home_base_price($same_seller_product) }}</del>
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