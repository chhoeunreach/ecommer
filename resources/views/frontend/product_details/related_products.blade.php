<style>
    .related-product-container .related-product-card {
        background: #fff;
        border: 1px solid #e4e4e7;
        border-radius: 0.85rem;
        height: 100%;
        margin: 4px;
        overflow: hidden;
        padding: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
        transition: border-color .25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .25s cubic-bezier(0.4, 0, 0.2, 1), transform .25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .related-product-container .aiz-carousel {
        margin-right: -4px;
        margin-left: -4px;
        overflow: hidden;
    }

    .related-product-container .slick-track {
        display: flex;
        margin-left: 0;
    }

    .related-product-container .slick-slide {
        float: none;
        height: auto;
    }

    .related-product-container .slick-slide > div,
    .related-product-container .carousel-box {
        height: 100%;
    }

    .related-product-container .related-product-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08), 0 4px 6px -2px rgba(15, 23, 42, 0.03);
        transform: translateY(-4px);
    }

    .related-product-container .related-product-image {
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        border-radius: 8px;
        height: auto !important;
        margin-left: auto;
        margin-right: auto;
        max-width: 190px;
        width: 100% !important;
    }

    .related-product-container .related-product-image a {
        display: block;
        height: 100%;
        position: relative;
        width: 100%;
    }

    .related-product-container .related-product-image img {
        backface-visibility: hidden;
        height: 100% !important;
        inset: 0;
        object-fit: contain;
        opacity: 1;
        padding: 6px;
        position: absolute !important;
        transform: scale(1);
        transition: opacity .35s ease, transform .45s ease !important;
        width: 100% !important;
        will-change: opacity, transform;
    }

    .related-product-container .related-product-image .product-hover-image {
        opacity: 0;
        transform: scale(1.025);
    }

    .related-product-container .related-product-card:hover .product-main-image:not(.product-hover-image) {
        opacity: 0;
        transform: scale(1.025);
    }

    .related-product-container .related-product-card:hover .product-hover-image {
        opacity: 1;
        transform: scale(1);
    }

    .related-product-container .related-product-content {
        padding: 10px 2px 2px;
    }

    .related-product-container .related-product-price {
        color: #202534;
        line-height: 1.35;
    }

    .related-product-container .related-products-single .related-product-card {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr);
        align-items: center;
        gap: 16px;
        max-width: 460px;
        min-height: 184px;
    }

    .related-product-container .related-products-single .related-product-image {
        max-width: 160px;
        margin: 0;
    }

    .related-product-container .related-products-single .related-product-content {
        min-width: 0;
        padding: 6px 8px 6px 0;
    }

    @media (max-width: 767px) {
        .related-product-container .related-products-single .related-product-card {
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 12px;
            min-height: 136px;
        }

        .related-product-container .related-products-single .related-product-image {
            max-width: 112px;
            margin: 0;
        }

        .related-product-container .related-products-single .related-product-content {
            min-width: 0;
            padding: 4px 4px 4px 0;
        }
    }

    @media (max-width: 575px) {
        .related-product-container > p {
            margin-bottom: 10px;
            font-size: 18px !important;
        }

        .related-product-container .related-product-card {
            margin: 3px;
            padding: 8px;
        }

        .related-product-container .related-product-content {
            padding-top: 8px;
        }

        .related-product-container .related-product-price del {
            display: block;
            margin-left: 0 !important;
        }
    }
</style>

@php
    $relatedProducts = get_related_products_by_category($detailedProduct->category_id)
        ->where('id', '!=', $detailedProduct->id)
        ->values();
    $relatedProductCount = $relatedProducts->count();
    $relatedXsItems = max(1, min(2, $relatedProductCount));
    $relatedSmItems = max(1, min(3, $relatedProductCount));
    $relatedMdItems = max(1, min(4, $relatedProductCount));
    $relatedLgItems = max(1, min(5, $relatedProductCount));
    $relatedDesktopItems = max(1, min(6, $relatedProductCount));
@endphp

<div class="related-product-container py-3 py-md-20px px-3 px-md-30px border-md bg-white border-light-gray rounded-2 mobile-section-container">
    <p class="fs-20 fw-bold text-dark mobile-section-title">{{ translate('Related Products') }}</p>
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
        .related-product-card {
            background: transparent;
            border: none;
        }
        .modern-product-img-wrapper {
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .carousel-box .related-product-card:hover .modern-product-img-wrapper {
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transform: translateY(-4px);
        }
        .carousel-box .related-product-card .product-hover-image {
            transition: all 0.4s ease;
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
        .related-product-image:hover .premium-action-pill {
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
        @media (max-width: 576px) {
            .related-product-card {
                padding: 8px;
                border-radius: 14px;
            }
            .modern-product-img-wrapper {
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
            .related-product-card h3 {
                font-size: 12px !important;
            }
            .related-product-card .fs-18 {
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
    <div class="aiz-carousel related-products-carousel arrow-x-0 arrow-inactive-none {{ $relatedProductCount === 1 ? 'related-products-single' : '' }}"
        data-items="{{ $relatedDesktopItems }}" data-xxl-items="{{ $relatedDesktopItems }}"
        data-xl-items="{{ $relatedDesktopItems }}" data-lg-items="{{ $relatedLgItems }}"
        data-md-items="{{ $relatedMdItems }}" data-sm-items="{{ $relatedSmItems }}"
        data-xs-items="{{ $relatedXsItems }}" data-arrows="false" data-dots="false"
        data-autoplay="false" data-infinite="false">

        <!--Single-->
        @forelse ($relatedProducts as $key => $related_product)
        <div class="carousel-box px-2 py-2 mb-4">
          <div class="related-product-card d-flex flex-column h-100" style="padding: 12px;">
            <div
                class="img h-130px h-sm-160px h-md-190px h-lg-220px w-100 position-relative image-hover-effect overflow-hidden modern-product-img-wrapper related-product-image mb-3" style="background-color: #f8f9fa;">
                @if(home_base_price($related_product) != home_discounted_base_price($related_product))
                    <span class="premium-discount-badge">-{{ discount_in_percentage($related_product) }}%</span>
                @endif
                <a href="{{ route('product', $related_product->slug) }}" title="{{ $related_product->name }}" class="d-block w-100 h-100 position-relative">
                    <img class="lazyload w-100 h-100 product-main-image" style="object-fit: cover; transition: all 0.4s ease;"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                        alt="{{ $related_product->name }}" onerror=" this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                    <img class="lazyload w-100 h-100 product-main-image product-hover-image position-absolute top-0 left-0" style="object-fit: cover;"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ get_first_product_image($related_product->thumbnail, $related_product->photos) }}" alt="{{ $related_product->name }}"
                        title="" onerror=" this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
                <div class="premium-action-pill">
                    <a href="javascript:void(0)" onclick="showAddToCartModal({{ $related_product->id }})" class="premium-action-btn" title="{{ translate('Add to cart') }}">
                        <i class="las la-sliders-h"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="addToWishList({{ $related_product->id }})" class="premium-action-btn" title="{{ translate('Add to wishlist') }}">
                        <i class="la la-heart-o"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="addToCompare({{ $related_product->id }})" class="premium-action-btn" title="{{ translate('Add to compare') }}">
                        <i class="las la-sync"></i>
                    </a>
                </div>
            </div>
            <div class="px-1 d-flex flex-column justify-content-between flex-grow-1">
                <div>
                    <h3 class="fw-600 fs-15 text-truncate-2 lh-1-4 mb-1">
                        <a href="{{ route('product', $related_product->slug) }}" class="text-dark hov-text-primary text-decoration-none">{{ $related_product->name }}</a>
                    </h3>
                    <div class="rating rating-mr-1 mb-2 fs-10" style="color: #f59e0b;">
                        {{ renderStarRating($related_product->rating) }}
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap mt-auto">
                    <span class="fw-800 fs-18 text-primary mr-2">{{ home_discounted_base_price($related_product) }}</span>
                    @if (home_base_price($related_product) != home_discounted_base_price($related_product))
                        <del
                            class="fw-500 fs-13 text-gray">{{ home_base_price($related_product) }}</del>
                    @endif
                </div>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center w-100">
            <h5 class="fs-16 fw-bold text-dark">{{ translate('No related products found!') }}</h5>
            <span>
               <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#e3e3e3"><path d="M626-533q22.5 0 38.25-15.75T680-587q0-22.5-15.75-38.25T626-641q-22.5 0-38.25 15.75T572-587q0 22.5 15.75 38.25T626-533Zm-292 0q22.5 0 38.25-15.75T388-587q0-22.5-15.75-38.25T334-641q-22.5 0-38.25 15.75T280-587q0 22.5 15.75 38.25T334-533Zm146.17 116Q413-417 358.5-379.5T278-280h53q22-42 62.17-65 40.18-23 87.5-23 47.33 0 86.83 23.5T630-280h52q-25-63-79.83-100-54.82-37-122-37ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 340q142.38 0 241.19-98.81Q820-337.63 820-480q0-142.38-98.81-241.19T480-820q-142.37 0-241.19 98.81Q140-622.38 140-480q0 142.37 98.81 241.19Q337.63-140 480-140Z"/></svg>
            </span>
        </div>
        @endforelse
    </div>
</div>
