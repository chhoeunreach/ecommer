@extends('frontend.layouts.app')

@php
    $productDetailsLayout = get_setting('product_details_layout', 'v1');
    $productDetailsLayout = in_array($productDetailsLayout, ['v1', 'v2'], true) ? $productDetailsLayout : 'v1';
    
    $p_meta_title = $accessory->meta_title ?: $accessory->name;
    $p_meta_description = $accessory->meta_description ? $accessory->meta_description : \Illuminate\Support\Str::limit(strip_tags($accessory->description), 160);
    $p_meta_image = $accessory->meta_img ? uploaded_asset($accessory->meta_img) : uploaded_asset($accessory->thumbnail_img);
@endphp

@section('meta_title'){{ $p_meta_title }}@stop
@section('meta_description'){{ $p_meta_description }}@stop
@section('meta_keywords'){{ $accessory->tags }}@stop

@section('meta')
    @if ($productDetailsLayout === 'v2')
        <link rel="stylesheet" href="{{ static_asset('assets/css/product-details-v2.css?v=') }}{{ @filemtime(public_path('assets/css/product-details-v2.css')) }}">
    @endif
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $p_meta_title }}">
    <meta itemprop="description" content="{{ $p_meta_description }}">
    <meta itemprop="image" content="{{ $p_meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:title" content="{{ $p_meta_title }}">
    <meta name="twitter:description" content="{{ $p_meta_description }}">
    <meta name="twitter:image" content="{{ $p_meta_image }}">
    <meta name="twitter:data1" content="{{ single_price($accessory->price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $p_meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('accessories.show', $accessory->id) }}" />
    <meta property="og:image" content="{{ $p_meta_image }}" />
    <meta property="og:description" content="{{ $p_meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('website_name') ?: config('app.name') }}" />
    <meta property="og:price:amount" content="{{ single_price($accessory->price) }}" />
    <meta property="product:brand" content="{{ $accessory->brand ? $accessory->brand->name : config('app.name') }}">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($accessory->price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $accessory->slug }}">
    <meta property="product:price:currency" content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ config('services.facebook_pixel.id') }}">
@endsection

@section('content')
    <div class="product-details product-details-{{ $productDetailsLayout }}">
        <!--PRODUCT DETAILS TOP SECTION START-->
        <div class="container">
            <div class="pt-30px pb-6 product-hero-card">
                <div class="row product-hero-row">
                    <!--LEFT SIDE SLIDER-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="product-slider-wrapper mb-2rem mb-lg-0">
                            <!--BREADCRUMB-->
                            <ul class="breadcrumb bg-transparent pt-0 px-0 pb-10px d-flex align-items-center">
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="{{route('accessories.index')}}">{{translate('Accessories')}}</a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition  text-reset">
                                    {{ strlen($accessory->name) > 50 ? substr($accessory->name, 0, 50).'...' : $accessory->name }}
                                </li>
                            </ul>
                            
                            <!-- Image Gallery -->
                            @php
                                $photos = $accessory->gallery != null ? explode(',', $accessory->gallery) : [];
                                $total_gallery= count($photos);
                            @endphp
                            <div class="row product-gallery-layout">
                                <div class="col-md-2 col-lg-3 col-xl-2 order-2 order-md-1">
                                    <!--THUMBNAILS SLIDER-->
                                    <div class="thumb-container position-relative overflow-hidden rounded-corner-8px">
                                        <div class="swiper thumb-slider w-100 h-100">
                                            <div class="swiper-wrapper">
                                                <!-- Thumbnail Image -->
                                                <div class="swiper-slide rounded-corner-8px border border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center">
                                                    <img src="{{ uploaded_asset($accessory->thumbnail_img) }}" class="img-fluid object-fit-cover object-position-center" alt="">
                                                </div>
                                                @foreach ($photos as $key => $photo)
                                                    <div class="swiper-slide rounded-corner-8px border border-light-gray bg-light cursor-pointer overflow-hidden d-flex align-items-center justify-content-center">
                                                        <img src="{{ uploaded_asset($photo) }}" class="img-fluid object-fit-cover object-position-center" alt="">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!--Thumb Button-->
                                        @if($total_gallery > 5)
                                        <div class="thumb-slider-btn position-absolute bottom-0 left-0 w-100 d-flex">
                                            <button type="button" class="thumb-btn-up border-0  d-flex align-items-center justify-content-center cursor-pointer">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111723"><path d="m480-555.69-184 184L267.69-400 480-612.31 692.31-400 664-371.69l-184-184Z"/></svg>
                                                </span>
                                            </button>
                                            <button type="button" class="thumb-btn-down border-0  d-flex align-items-center justify-content-center cursor-pointer">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111723"><path d="M480-371.69 267.69-584 296-612.31l184 184 184-184L692.31-584 480-371.69Z"/></svg>
                                                </span>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- MAIN SLIDER -->
                                <div class="col-md-10 col-lg-9 col-xl-10 pl-lg-0 order-1 order-md-2">
                                    <div class="swiper main-slider position-relative d-flex align-items-center justify-content-center">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide rounded-corner-8px border border-light-gray bg-light overflow-hidden lightbox-item">
                                                <img src="{{ uploaded_asset($accessory->thumbnail_img) }}" class="img-fluid w-100 h-100 lightbox-source product-gallery-image" alt="">
                                                <div class="img-preview-btn wd-show-product-gallery-wrap rounded-pill overflow-hidden">
                                                    <a href="#" class="border-0 bg-transparent d-inline-flex align-items-center woodmart-show-product-gallery">
                                                        <span class="preview-icon-container w-50px h-50px d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white">
                                                            <i class="las la-expand-arrows-alt fs-16 text-dark"></i>
                                                        </span>
                                                        <span class="fs-14 fw-400 text-dark preview-btn-text flex-shrink-0">{{translate('Click to Enlarge')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                            @foreach ($photos as $key => $photo)
                                            <!--Single-->
                                            <div class="swiper-slide rounded-corner-8px border  border-light-gray bg-light overflow-hidden lightbox-item">
                                                <img src="{{ uploaded_asset($photo) }}" class="img-fluid w-100 h-100 lightbox-source product-gallery-image" alt="">
                                                <div class="img-preview-btn wd-show-product-gallery-wrap rounded-pill overflow-hidden">
                                                    <a href="#" class="border-0 bg-transparent d-inline-flex align-items-center woodmart-show-product-gallery">
                                                        <span class="preview-icon-container w-50px h-50px d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white">
                                                            <i class="las la-expand-arrows-alt fs-16 text-dark"></i>
                                                        </span>
                                                        <span class="fs-14 fw-400 text-dark preview-btn-text flex-shrink-0">{{translate('Click to Enlarge')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <!--Swipper Buttons -->
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--RIGHT SIDE-->
                    <div class="col-sm-12 col-lg-6 product-info-panel">
                        <div class="d-flex align-items-center justify-content-end pb-20px right-side-cws">
                            <div>
                                <button type="button" data-toggle="modal" data-target="#social-share-modal" class="p-0 d-flex align-items-center bg-transparent fs-12 fw-400 text-gray border-0 hov-text-dark has-transition">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <g id="Group_39026" data-name="Group 39026" transform="translate(-1577 -240)">
                                                <path id="Arrow" d="M5.121,22.366a.556.556,0,0,1-.181-.029.583.583,0,0,1-.4-.583c0-.087.6-8.547,9.083-9.211V9.5a.583.583,0,0,1,1-.408l5.75,5.873a.583.583,0,0,1,0,.816l-5.75,5.873a.583.583,0,0,1-1-.408V18.264c-5.663.216-7.985,3.787-8.008,3.831a.583.583,0,0,1-.492.271Zm9.666-11.437v2.159a.583.583,0,0,1-.562.583,8.263,8.263,0,0,0-8.157,6.208,12.051,12.051,0,0,1,8.1-2.8H14.2a.583.583,0,0,1,.583.583v2.159l4.37-4.445Z" transform="translate(1572.462 232.082)" fill="#9d9da6"/>
                                                <rect id="Rectangle_24159" data-name="Rectangle 24159" width="16" height="16" transform="translate(1577 240)" fill="none"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="ml-2 d-block share">{{translate('Share')}}</span>
                                </button>
                            </div>
                        </div>

                        <h1 class="fs-20 fw-600 text-dark mb-3 product-detail-title">{{ $accessory->name }}</h1>
                        
                        <!--Brand Name and Ask about Start-->
                        <div class="d-flex flex-wrap flex-column flex-sm-row align-items-sm-center justify-content-between product-meta-line">
                            @if ($accessory->brand != null)
                                <div class="d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark pr-2">{{ translate('Brand') }}</span>
                                    <span class="fs-14 fw-400 text-blue"><a href="{{route('products.brand', $accessory->brand->slug)}}">{{ $accessory->brand->name }}</a></span>
                                </div>
                            @endif
                        </div>

                        <!-- Todays Deal Start-->
                        <div class="mt-4 mb-3 product-promotion-area">
                            <style>
                            .product-price-card-new {
                                background: #ffffff;
                                border: 1px solid #e5e7eb;
                                padding: 20px 24px;
                                border-radius: 12px;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                                margin-bottom: 24px;
                                position: relative;
                                overflow: hidden;
                            }
                            .price-text-main {
                                font-size: 28px;
                                font-weight: 800;
                                color: #111827;
                                letter-spacing: -0.02em;
                                display: flex;
                                align-items: baseline;
                                gap: 6px;
                                margin: 0;
                                line-height: 1.2;
                                flex-wrap: wrap;
                            }
                            .price-badge-container {
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                margin-top: 12px;
                                flex-wrap: wrap;
                            }
                            @media (max-width: 576px) {
                                .product-price-card-new {
                                    padding: 16px 16px;
                                }
                                .price-text-main {
                                    font-size: 24px;
                                }
                            }
                            </style>
                            
                            <form id="option-choice-form" class="product-details-page">
                                @csrf
                                <input type="hidden" name="id" value="{{ $accessory->id }}">
                                
                                <h5 class="fs-16 fw-600 text-gray product-section-kicker">{{ translate('Pricing') }}</h5>
                                
                                <div class="product-price-card-new mt-2">
                                    <div>
                                        <div class="price-text-main">
                                            {{ single_price($accessory->price) }}
                                        </div>
                                    </div>
                                    <input type="hidden" name="quantity" value="1">
                                </div>
                                
                                <div class="border-dashed border-1 border-soft-light rounded-2 overflow-hidden mt-4 mb-1 px-20px pt-15px pb-20px purchase-panel">
                                    <div class="d-flex pb-10px flex-wrap align-items-center justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between justify-content-md-start w-100 w-md-auto mb-3 mb-md-0">
                                            <div class="mr-2 mr-md-4 text-dark fs-14 fw-bold">{{ translate('Quantity') }}:</div>
                                            <div class="product-quantity d-flex align-items-center bg-white rounded-1 overflow-hidden ml-auto ml-md-0 border border-gray">
                                                <button class="btn btn-icon btn-sm btn-light border-0 rounded-0" type="button" data-type="minus" data-field="quantity" disabled="">
                                                    <i class="las la-minus"></i>
                                                </button>
                                                <input type="text" name="quantity" class="form-control input-number border-0 text-center fs-14 fw-600 w-50px px-1 py-1 h-auto" placeholder="1" value="1" min="1" max="10">
                                                <button class="btn btn-icon btn-sm btn-light border-0 rounded-0" type="button" data-type="plus" data-field="quantity">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="border-soft-light my-20px">
                                    
                                    <div class="d-flex flex-wrap product-action-buttons">
                                        @include('frontend.product_details.partials.action_buttons', ['buttonPadding' => 'py-2', 'productObj' => $accessory])
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--PRODUCT DETAILS TOP SECTION END-->

        <!-- ======== PRODUCT DETAILS NAV TAB START ======== -->
        <div id="smart-bar-trigger"></div>
        <div class="product-details-nav-tab mb-5">
            <div class="nav-tab-header bg-white">
                <div class="container mb-32px">
                    <div class="tab-scroll-wrapper">
                        <ul id="tabLinks" class="m-0 p-0 d-flex position-relative" type="none">
                            <li class="mr-2rem"><a href="#description" class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Description')}}</a></li>
                            <span class="tab-underline"></span>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container d-flex flex-column">
                <!--DESCRIPTION SECTION START-->
                <section id="description">
                    <div class="py-30px px-30px border bg-white border-light-gray rounded-2">
                        <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                            <?php echo $accessory->description; ?>
                        </div>
                    </div>
                </section>
                <!--DESCRIPTION SECTION END-->
            </div>
        </div>
        <!-- ======== PRODUCT DETAILS NAV TAB END ======== -->
    </div>

    <!-- STICKY MOBILE ACTION BAR (MOBILE OPTIMIZATION) -->
    <div class="d-none d-md-none fixed-bottom bg-white border-top shadow-lg p-2 z-1030 px-3 py-2 mobile-product-action-bar" aria-hidden="true">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mr-2">
                <div class="fs-10 text-uppercase text-muted fw-600">{{ translate('Price') }}</div>
                <div class="fs-15 fw-700 text-primary">
                    {{ single_price($accessory->price) }}
                </div>
            </div>
            <div class="d-flex flex-grow-1 justify-content-end ml-2">
                @include('frontend.product_details.partials.action_buttons', ['buttonPadding' => 'py-2', 'productObj' => $accessory])
            </div>
        </div>
    </div>

@endsection

@section('modal')

    @include('frontend.partials.image_viewer')
    
   <!-- Product Share Modal -->
    <div class="modal fade" id="social-share-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column pb-0 border-0">
                    <div class="w-80px h-80px rounded-circle bg-light border border-white border-width-3 link-circle-box d-flex align-items-center justify-content-center"> 
                        <i class="las la-link fs-28"></i>
                    </div>
                    <button type="button" class="close fs-10 w-25px h-25px rounded-circle bg-white hov-bg-soft-light has-transition d-flex align-items-center justify-content-center mr-1 mb-1" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <div class="col-12 col-lg-10 col-xl-8 mx-auto text-center">
                        <h4 class="fs-20 fw-700 text-dark">{{translate('Share with Friends')}}</h4>
                        <span class="fs-14 text-gray fw-400">{{translate('Trading is more effective when you share products with friends!')}}</span>
                    </div>
                    <div class="my-4 my-lg-5">
                        <h5 class="fs-16 fw-600 text-dark">{{translate('Share you link')}}</h5>
                        <div class="py-3 px-3 bg-light rounded-2 border-0 d-flex align-items-center justify-content-between share-link">
                            <span class="fs-14 text-gray fw-400 flex-grow-1 text-truncate-1 has-transition">{{route('accessories.show', $accessory->id)}}</span>
                            <button type="button" id="link-cpurl-btn" data-attrcpy="{{ translate('Copied') }}" data-url="{{route('accessories.show', $accessory->id)}}" onclick="CopyToClipboard(this)" class="border-0 bg-transparent flex-shrink-0 copy-link-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                                    <path id="Path_45213" data-name="Path 45213" d="M124.8-867.2a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-9.6a1.541,1.541,0,0,1,.47-1.13,1.541,1.541,0,0,1,1.13-.47H132a1.541,1.541,0,0,1,1.13.47,1.541,1.541,0,0,1,.47,1.13v9.6a1.541,1.541,0,0,1-.47,1.13,1.541,1.541,0,0,1-1.13.47Zm0-1.6H132v-9.6h-7.2Zm-3.2,4.8a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-11.2h1.6v11.2h8.8v1.6Zm3.2-4.8v0Z" transform="translate(-120 880)" fill="#919199"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                     <div class="pb-3">
                        <h5 class="fs-16 fw-600 text-dark">{{translate('Share to')}}</h5>
                        <div class="aiz-share text-center"></div>
                     </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            var mobileActionBar = document.querySelector('.mobile-product-action-bar');
            var primaryActionButtons = document.querySelector('#option-choice-form .product-action-buttons');

            if (mobileActionBar && primaryActionButtons && 'IntersectionObserver' in window) {
                var actionButtonObserver = new IntersectionObserver(function (entries) {
                    var originalButtonsVisible = entries[0].isIntersecting;
                    mobileActionBar.classList.toggle('d-none', originalButtonsVisible);
                    mobileActionBar.setAttribute('aria-hidden', originalButtonsVisible ? 'true' : 'false');
                }, {
                    threshold: 0.2,
                    rootMargin: '0px 0px -60px 0px'
                });
                actionButtonObserver.observe(primaryActionButtons);
            }

            /*------ Thumbnails Swiper ------*/
            var thumbSwiper = new Swiper(".thumb-slider", {
                direction: "vertical",
                slidesPerView: 5,
                spaceBetween: 16,
                watchSlidesProgress: true,
                breakpoints: {
                    0: { direction: "horizontal", slidesPerView: 3, spaceBetween: 10 },
                    768: { direction: "vertical", slidesPerView: 5 }
                }
            });

            /*------ Product Main Swiper Slide ------ */
            var mainSwiper = new Swiper(".main-slider", {
                spaceBetween: 10,
                thumbs: { swiper: thumbSwiper },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
            });

            const thumbBtnUp = document.querySelector(".thumb-btn-up");
            const thumbBtnDown = document.querySelector(".thumb-btn-down");

            if (thumbBtnUp && thumbBtnDown) {
                thumbBtnUp.addEventListener("click", function(e) {
                    e.preventDefault(); e.stopPropagation(); thumbSwiper.slidePrev();
                });
                thumbBtnDown.addEventListener("click", function(e) {
                    e.preventDefault(); e.stopPropagation(); thumbSwiper.slideNext();
                });
                function updateThumbButtons(swiper) {
                    thumbBtnUp.classList.toggle("disabled", swiper.isBeginning);
                    thumbBtnDown.classList.toggle("disabled", swiper.isEnd);
                }
                updateThumbButtons(thumbSwiper);
                thumbSwiper.on("slideChange", function () { updateThumbButtons(this); });
            }
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
        }

        function contactSalesOnTelegram(button) {
            var form = document.getElementById('option-choice-form');
            var selectedValue = function (name) {
                if (!form) return '';
                var input = form.querySelector('input[name="' + name + '"]:checked');
                return input ? String(input.value).trim() : '';
            };
            var textValue = function (selector) {
                var element = document.querySelector(selector);
                return element ? element.textContent.trim() : '';
            };
            var addDetail = function (lines, label, value) {
                if (value) lines.push(label + ': ' + value);
            };

            var lines = [
                @json('សួស្តី! ខ្ញុំចង់សាកសួរព័ត៌មានបន្ថែមអំពីផលិតផលខាងក្រោម៖'),
                '',
                @json('ឈ្មោះផលិតផល') + ': ' + (button.dataset.productName || document.title)
            ];

            addDetail(lines, @json('ចំនួន'), form ? (form.querySelector('input[name="quantity"]') || {}).value : '');
            addDetail(lines, @json('តម្លៃ'), '{{ single_price($accessory->price) }}');
            addDetail(lines, @json('តំណភ្ជាប់ផលិតផល'), window.location.href.split('#')[0]);
            lines.push('', @json('សូមជួយផ្តល់ព័ត៌មានបន្ថែមផង។ អរគុណ!'));

            // Update the link's own href and let the browser perform a real navigation
            // instead of window.open(): mobile browsers only hand a t.me link off to the
            // Telegram app on a genuine top-level navigation, not on a JS-opened popup.
            try {
                var telegramUrl = new URL(button.dataset.telegramUrl, window.location.origin);
                telegramUrl.searchParams.set('text', lines.join('\n'));
                button.href = telegramUrl.toString();
            } catch (error) {
                // Leave button.href as the plain telegram URL already set in the markup.
            }

            return true;
        }

        // Override getVariantPrice so it doesn't trigger ajax for accessories and hide buttons
        window.getVariantPrice = function() {
            var qty = parseInt($('#option-choice-form input[name=quantity]').val());
            if (!isNaN(qty) && qty > 0) {
                $('#add_to_cart_count').text('(' + String(qty).padStart(2, '0') + ')');
            }
        };

        // Setup quantity buttons
        $('[data-type="plus"]').on('click', function(e){

            e.preventDefault();
            var input = $("input[name='" + $(this).data('field') + "']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                input.val(currentVal + 1).change();
            } else {
                input.val(0);
            }
        });
        $('[data-type="minus"]').on('click', function(e){
            e.preventDefault();
            var input = $("input[name='" + $(this).data('field') + "']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                input.val(currentVal - 1).change();
            } else {
                input.val(1);
            }
        });
    </script>
@endsection
