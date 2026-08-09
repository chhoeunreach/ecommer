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

        .deal-product-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--deal-border);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 7px 22px rgba(24, 32, 51, .045);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .deal-product-card:hover {
            z-index: 2;
            border-color: var(--deal-accent);
            box-shadow: 0 18px 38px rgba(24, 32, 51, .12);
            transform: translateY(-5px);
        }

        .deal-product-card .aiz-card-box {
            height: 100% !important;
            padding: 10px 10px 6px !important;
            background: #fff;
        }

        .deal-product-card .aiz-card-box > .position-relative {
            height: auto !important;
            aspect-ratio: 1 / 1;
            border-radius: 13px;
            background: #f4f5f8;
        }

        .deal-product-card .aiz-card-box > .position-relative img {
            object-fit: contain;
            padding: 8px;
        }

        .deal-product-card .aiz-card-box > .p-2,
        .deal-product-card .aiz-card-box > .p-md-3 {
            padding: 14px 4px 10px !important;
        }

        .deal-product-card h3 {
            height: 40px !important;
            text-align: left !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            line-height: 1.45 !important;
        }

        .deal-product-card h3 + div {
            justify-content: flex-start !important;
            min-height: 24px;
            margin-top: 10px !important;
            font-size: 15px !important;
        }

        .deal-product-card h3 + div span.fw-700 {
            color: var(--deal-accent);
            font-size: 16px;
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
            .deal-product-card { border-radius: 14px; }
            .deal-product-card:hover { transform: none; }
            .deal-product-card .aiz-card-box { padding: 7px 7px 4px !important; }
            .deal-product-card .aiz-card-box > .position-relative { border-radius: 10px; }
            .deal-product-card .aiz-card-box > .position-relative img { padding: 5px; }
            .deal-product-card .aiz-p-hov-icon-mobile { bottom: 7px !important; }
            .deal-product-card .aiz-p-hov-icon-mobile a { transform: none; }
            .deal-product-card h3 { font-size: 13px !important; }
            .deal-product-card h3 + div { flex-wrap: wrap; font-size: 13px !important; }
            .deal-product-card h3 + div span.fw-700 { font-size: 14px; }
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
                        <i class="las la-bolt"></i>{{ translate('Limited-time offers') }}
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
