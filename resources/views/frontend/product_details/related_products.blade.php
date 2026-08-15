<style>
    .related-product-container .related-product-card {
        background: #fff;
        border: 1px solid #e4e9ef;
        border-radius: 10px;
        height: 100%;
        margin: 4px;
        overflow: hidden;
        padding: 10px;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
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
        border-color: #b8d8fa;
        box-shadow: 0 7px 20px rgba(31, 41, 55, .09);
        transform: translateY(-2px);
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

<div class="related-product-container py-20px px-30px border bg-white border-light-gray rounded-2">
    <p class="fs-20 fw-bold text-dark">{{ translate('Related Products') }}</p>

    <div class="aiz-carousel related-products-carousel arrow-x-0 arrow-inactive-none {{ $relatedProductCount === 1 ? 'related-products-single' : '' }}"
        data-items="{{ $relatedDesktopItems }}" data-xxl-items="{{ $relatedDesktopItems }}"
        data-xl-items="{{ $relatedDesktopItems }}" data-lg-items="{{ $relatedLgItems }}"
        data-md-items="{{ $relatedMdItems }}" data-sm-items="{{ $relatedSmItems }}"
        data-xs-items="{{ $relatedXsItems }}" data-arrows="false" data-dots="false"
        data-autoplay="false" data-infinite="false">

        <!--Single-->
        @forelse ($relatedProducts as $key => $related_product)
        <div class="carousel-box px-1 py-2">
          <div class="related-product-card">
            <div
                class="img h-90px w-90px h-sm-100px w-sm-100px h-md-150px w-md-150px h-lg-170px w-lg-170px h-xxl-190px w-xxl-190px overflow-hidden position-relative image-hover-effect related-product-image">
                <a href="{{ route('product', $related_product->slug) }}" title="">
                    <img class="lazyload img-fit m-auto has-transition product-main-image"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                        alt="{{ $related_product->name }}" onerror=" this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                    <img class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                        src="{{ get_first_product_image($related_product->thumbnail, $related_product->photos) }}" alt="{{ $related_product->name }}"
                        title="" onerror=" this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
            </div>
            <div class="related-product-content">
                <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-1 h-35px">
                    <a href="{{ route('product', $related_product->slug) }}" class="text-reset hov-text-primary hov-text-primary">{{ $related_product->name }}</a>
                </h3>
                <div class="related-product-price fw-700 fs-14 mb-1 mt-2">
                    <span >{{ home_discounted_base_price($related_product) }}</span>
                    @if (home_base_price($related_product) != home_discounted_base_price($related_product))
                        <del
                            class="fw-700 opacity-60 ml-1">{{ home_base_price($related_product) }}</del>
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
