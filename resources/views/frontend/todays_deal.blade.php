@extends('frontend.layouts.app')

@section('meta')
    @php
        $dealAccent = get_setting('base_color', '#3490f3');
        $dealAccent = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $dealAccent) ? $dealAccent : '#3490f3';
    @endphp
    <style>
        .todays-deal-page {
            --deal-accent: {{ $dealAccent }};
            --deal-border: rgba(24, 32, 51, .09);
            background: #f6f7fb;
            padding: clamp(24px, 4vw, 56px) 0 clamp(48px, 6vw, 84px);
        }

        .todays-deal-container {
            width: min(calc(100% - 32px), 1440px);
            margin: 0 auto;
        }

        .todays-deal-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .todays-deal-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            color: var(--deal-accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .todays-deal-heading h1 {
            margin: 0;
            color: #202332;
            font-size: clamp(26px, 3vw, 40px);
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .todays-deal-count {
            flex-shrink: 0;
            padding: 8px 14px;
            border: 1px solid var(--deal-border);
            border-radius: 999px;
            background: #fff;
            color: #707583;
            font-size: 13px;
            font-weight: 600;
        }

        .todays-deal-banner {
            aspect-ratio: 5 / 1;
            margin-bottom: clamp(24px, 3vw, 40px);
            overflow: hidden;
            border-radius: 20px;
            background: #e9ecf2;
            box-shadow: 0 14px 38px rgba(24, 32, 51, .08);
        }

        .todays-deal-banner img {
            display: block;
            width: 100%;
            height: 100% !important;
            object-fit: cover;
            object-position: center;
        }

        .todays-deal-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: clamp(12px, 1.5vw, 22px);
        }

        /* Product Card Base */
        .deal-product-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }

        .deal-product-card:hover {
            z-index: 2;
            border-color: #e2e8f0;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .deal-product-card .aiz-card-box {
            height: 100% !important;
            padding: 12px !important;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }

        /* Image Container */
        .deal-product-card .aiz-card-box > .position-relative {
            height: 200px !important;
            border-radius: 14px;
            overflow: hidden;
            background-color: #f8f9fa;
            position: relative !important;
        }

        .deal-product-card .product-main-image,
        .deal-product-card .product-hover-image {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            transition: transform 0.4s ease, opacity 0.4s ease !important;
        }

        .deal-product-card .product-hover-image {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        .deal-product-card:hover .product-main-image {
            transform: scale(1.05);
        }

        .deal-product-card:hover .product-hover-image {
            opacity: 1 !important;
            visibility: visible !important;
            transform: scale(1.05);
        }

        /* Fix Cart / Select Option Button position - Floating circular icon bottom-right */
        .deal-product-card .cart-btn {
            position: absolute !important;
            top: auto !important;
            bottom: 10px !important;
            right: 10px !important;
            left: auto !important;
            transform: translateY(10px) !important;
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #0f172a !important;
            opacity: 0 !important;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            z-index: 5 !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
        }

        .deal-product-card:hover .cart-btn {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .deal-product-card .cart-btn:hover {
            background: #0f172a !important;
            color: #ffffff !important;
            transform: scale(1.1) !important;
        }

        .deal-product-card .cart-btn-text {
            display: none !important;
        }

        .deal-product-card .cart-btn i,
        .deal-product-card .cart-btn svg {
            font-size: 18px !important;
            color: inherit !important;
        }

        /* Top Right Wishlist & Compare Icons */
        .deal-product-card .aiz-p-hov-icon {
            top: 10px !important;
            right: 10px !important;
            left: auto !important;
            transform: translateY(-10px) !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 6px !important;
            opacity: 0 !important;
            transition: all 0.3s ease !important;
            z-index: 5 !important;
        }

        .deal-product-card:hover .aiz-p-hov-icon {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .deal-product-card .aiz-p-hov-icon a {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(4px) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
            margin: 0 !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
        }

        .deal-product-card .aiz-p-hov-icon a:hover {
            background: #ffffff !important;
            transform: scale(1.1) !important;
        }

        .deal-product-card .aiz-p-hov-icon svg path {
            fill: #475569 !important;
            transition: fill 0.2s ease !important;
        }

        .deal-product-card .aiz-p-hov-icon a:hover svg path {
            fill: #e11d48 !important;
        }

        /* Discount Tag Pill */
        .deal-product-card .absolute-top-left {
            top: 10px !important;
            left: 10px !important;
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%) !important;
            color: #ffffff !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 4px 10px !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3) !important;
            z-index: 3 !important;
            letter-spacing: 0.5px !important;
            width: auto !important;
            line-height: 1.2 !important;
        }

        /* Product Title & Price Section Below Image */
        .deal-product-card .aiz-card-box > .p-2,
        .deal-product-card .aiz-card-box > .p-md-3 {
            padding: 12px 4px 4px !important;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .deal-product-card h3 {
            height: auto !important;
            min-height: 38px;
            text-align: left !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
            margin-bottom: 6px !important;
        }

        .deal-product-card h3 a {
            color: #1e293b !important;
        }

        .deal-product-card h3 + div {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            margin-top: auto !important;
            padding-top: 4px !important;
        }

        .deal-product-card .disc-amount {
            margin-right: 0 !important;
            opacity: 1 !important;
            display: inline-block !important;
        }

        .deal-product-card .disc-amount del {
            color: #94a3b8 !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: line-through !important;
        }

        .deal-product-card h3 + div span.fw-700 {
            color: #0f172a !important;
            font-size: 16px !important;
            font-weight: 800 !important;
        }

        .todays-deal-empty {
            grid-column: 1 / -1;
            padding: 64px 24px;
            border: 1px dashed #ccd0da;
            border-radius: 18px;
            background: #fff;
            color: #777c89;
            text-align: center;
        }

        /* The site-wide floating quick-nav buttons are fixed to the left
           edge at this breakpoint and up; this page's container otherwise
           sits close enough to that edge that the first card ends up
           underneath them. */
        @media (min-width: 992px) {
            .todays-deal-page { padding-left: 130px; }
        }

        @media (max-width: 1199px) {
            .todays-deal-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }

        @media (max-width: 991px) {
            .todays-deal-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .todays-deal-banner { aspect-ratio: 3 / 1; }
        }

        @media (max-width: 767px) {
            .todays-deal-page { padding-top: 20px; }
            .todays-deal-container { width: min(calc(100% - 20px), 1440px); }
            .todays-deal-heading { align-items: flex-start; margin-bottom: 18px; }
            .todays-deal-count { margin-top: 4px; }
            .todays-deal-banner { aspect-ratio: 2 / 1; border-radius: 15px; }
            .todays-deal-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .deal-product-card { border-radius: 16px; }
            .deal-product-card:hover { transform: none; }
            .deal-product-card .aiz-card-box { padding: 8px !important; }
            .deal-product-card .aiz-card-box > .position-relative { height: 140px !important; border-radius: 10px; }
            .deal-product-card .aiz-card-box > .position-relative img { padding: 0 !important; }
            .deal-product-card .aiz-p-hov-icon-mobile { bottom: 7px !important; }
            .deal-product-card .aiz-p-hov-icon-mobile a { transform: none; }
            .deal-product-card h3 { font-size: 12px !important; min-height: 32px; }
            .deal-product-card h3 + div { flex-wrap: wrap; font-size: 14px !important; }
            .deal-product-card h3 + div span.fw-700 { font-size: 14px !important; }
            .deal-product-card .absolute-top-left { font-size: 10px !important; padding: 2px 7px !important; top: 6px !important; left: 6px !important; }
        }

        @media (max-width: 420px) {
            .todays-deal-heading { display: block; }
            .todays-deal-count { display: inline-flex; margin-top: 12px; }
        }
    </style>
@endsection

@section('content')
    <section class="todays-deal-page">
        <div class="todays-deal-container">
            <header class="todays-deal-heading">
                <div>
                    <span class="todays-deal-eyebrow">
                        {{ translate('Limited-time offers') }}
                    </span>
                    <h1>{{ translate("Today's Deals") }}</h1>
                </div>
                <span class="todays-deal-count">
                    {{ count($todays_deal_products) }} {{ translate('products') }}
                </span>
            </header>

            <!-- Banner -->
            @php
                $lang = get_system_language()->code;
                $todays_deal_banner = get_setting('todays_deal_banner', null, $lang);
                $todays_deal_banner_small = get_setting('todays_deal_banner_small', null, $lang);
            @endphp
            @if ($todays_deal_banner != null || $todays_deal_banner_small != null)
                <div class="todays-deal-banner hov-scale-img d-none d-md-block">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ uploaded_asset($todays_deal_banner) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
                <div class="todays-deal-banner hov-scale-img d-md-none">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ $todays_deal_banner_small != null ? uploaded_asset($todays_deal_banner_small) : uploaded_asset($todays_deal_banner) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
            @endif
            <!-- Products Section -->
            <div class="todays-deal-grid">
                @forelse ($todays_deal_products as $key => $product)
                    <article class="deal-product-card">
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                    </article>
                @empty
                    <div class="todays-deal-empty">
                        <i class="las la-box-open la-3x mb-3"></i>
                        <p class="mb-0 fw-600">{{ translate('No deals are available right now.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
