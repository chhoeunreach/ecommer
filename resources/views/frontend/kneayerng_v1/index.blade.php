@extends('frontend.layouts.app')

@section('content')

<style>
    /* Hero Banner Display Fix */
    .kneayerng-home > .hero-banner-carousel,
    .kneayerng-home .hero-banner-carousel {
        background: transparent !important;
    }

    /* Banner Images - Sharp 0px Corners (No Border Radius) */
    .hero-banner-container,
    .hero-banner-container img,
    .mega-banner-container,
    .mega-banner-container img,
    .banner-lg-container,
    .banner-lg-container img,
    .banner-lg-container-two,
    .banner-lg-container-two img,
    .fd-banner-container,
    .fd-banner-container img,
    .landing-banner-carousel img,
    .landing-banner-carousel .banner-lg-container,
    .kneayerng-home .hero-banner-container,
    .kneayerng-home .hero-banner-container img,
    .kneayerng-home .mega-banner-container,
    .kneayerng-home .mega-banner-container img,
    .kneayerng-home .banner-lg-container,
    .kneayerng-home .banner-lg-container img,
    .kneayerng-home .banner-lg-container-two,
    .kneayerng-home .banner-lg-container-two img {
        border-radius: 0px !important;
    }

    .hero-banner-container,
    .kneayerng-home .hero-banner-container,
    .kneayerng-home .mega-banner-container {
        background: transparent !important;
        box-shadow: none !important;
        transform: translateZ(0);
        aspect-ratio: auto !important;
        height: auto !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .hero-banner-container img,
    .kneayerng-home .hero-banner-container img,
    .kneayerng-home .mega-banner-container img {
        width: 100% !important;
        height: auto !important;
        object-fit: cover !important;
        object-position: center !important;
        display: block !important;
    }

    .hero-banner-wrapper,
    .kneayerng-home .hero-banner-wrapper {
        padding: 10px 0 20px !important;
        background: transparent !important;
    }

    /* Redesigned Category Cards */
    .bg-soft-primary-light {
        background: rgba(240, 246, 255, 0.7);
    }
    
    .hov-bg-soft-primary:hover {
        background: #eef5ff !important;
        border-color: #3390f3 !important;
    }

    .ky-category-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.04) !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03) !important;
        transition: all 0.3s ease !important;
    }

    .ky-category-card:hover {
        box-shadow: 0 10px 24px rgba(0,0,0,0.08) !important;
        border-color: rgba(51, 144, 243, 0.2) !important;
        transform: translateY(-3px);
    }

    .ky-category-card .ky-category-card-header {
        min-height: 52px;
        gap: 12px;
    }

    .ky-category-card .ky-category-card-title {
        min-width: 0;
    }

    .ky-category-card .ky-category-view-all {
        flex-shrink: 0;
        white-space: nowrap;
    }

    .ky-category-cover-new {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        height: 180px;
    }

    .ky-category-cover-new img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .ky-category-card:hover .ky-category-cover-new img {
        transform: scale(1.08);
    }

    .ky-child-category-list {
        gap: 8px;
    }

    .ky-child-category-link {
        background: #f8fafc;
        border: 1px solid transparent;
        border-radius: 8px;
        padding: 6px 10px;
        transition: all 0.2s;
    }

    .ky-child-category-link:hover {
        background: #eff6ff !important;
        border-color: #bfdbfe;
    }

    .ky-child-category-image {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .ky-child-category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 374px) {
        .ky-category-card .ky-category-card-body {
            padding: 12px !important;
        }

        .ky-child-category-link {
            padding: 6px !important;
        }

        .ky-child-category-image {
            width: 28px !important;
            height: 28px !important;
        }
    }

    /* Product Cards Premium Look */
    .custom-product-slider .slick-slide > div > div {
        background: #ffffff;
        border-radius: 16px;
        padding: 12px;
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        margin: 10px 5px;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .fd-product-slider .slick-slide > div > div,
    .td-product-slider .slick-slide > div > div {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .fd-product-card,
    .td-product-card {
        background: #ffffff !important;
        border-radius: 16px !important;
        padding: 10px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05) !important;
        border: 1px solid rgba(0,0,0,0.06) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        margin: 6px 4px;
    }

    .fd-product-card:hover,
    .td-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(149, 157, 165, 0.18) !important;
        border-color: rgba(51, 144, 243, 0.3) !important;
    }

    /* Image Aspect Ratio & Strict Hover Clip Fix */
    .img-aspect-ratio-200px,
    .img-aspect-ratio-250px,
    .img-aspect-ratio-300px,
    .featured-categories-slider .hov-scale-img {
        aspect-ratio: 1 / 1;
        width: 100%;
        background: #f8fafc;
        border-radius: 14px !important;
        overflow: hidden !important;
        position: relative !important;
        isolation: isolate !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    .img-aspect-ratio-200px img,
    .img-aspect-ratio-250px img,
    .img-aspect-ratio-300px img,
    .featured-categories-slider .hov-scale-img img,
    .hov-scale-img img {
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
        max-height: 100% !important;
        object-fit: contain !important;
        border-radius: 14px !important;
        transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    a.hov-scale-img:hover img,
    div.hov-scale-img:hover img {
        transform: scale(1.08) !important;
    }

    /* Category Blocks */
    .ky-category-main a {
        border-radius: 20px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        transition: transform 0.3s ease;
    }
    .ky-category-main a:hover {
        transform: scale(1.02);
    }

    .ky-category-children .category-card {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 16px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .ky-category-children .category-card:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        background: #ffffff !important;
    }

    /* Section Headings */
    h5.fw-bold {
        letter-spacing: -0.5px;
        color: #1a1a2e;
    }
    
    /* Buttons */
    .bg-dark.rounded-pill {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        box-shadow: 0 4px 15px rgba(26, 26, 46, 0.3);
        border: none !important;
        transition: all 0.3s ease !important;
    }
    .bg-dark.rounded-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26, 26, 46, 0.4);
    }
    
    /* Main container breathing room */
    .feactured-best-selling-product-section {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    /* Modern Flash and Today's Deals section styling */
    @keyframes ky-pulse-glow {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.15); }
    }

    .ky-deals-shell {
        background: radial-gradient(circle at 10% 20%, rgba(239, 68, 68, 0.04) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.04) 0%, transparent 40%),
                    #f8fafc;
    }

    .ky-deals-shell > .row {
        margin-right: 0;
        margin-left: 0;
    }

    .ky-deal-panel {
        min-width: 0;
        padding: clamp(20px, 2vw, 30px) !important;
    }

    .ky-deal-heading {
        min-height: 68px;
        gap: 16px;
    }

    .ky-deal-heading-copy {
        min-width: 0;
    }

    .ky-deal-kicker {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .ky-deal-kicker-flash {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(249, 115, 22, 0.14) 100%);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.18);
    }

    .ky-deal-kicker-flash i {
        display: inline-block;
        animation: ky-pulse-glow 2s infinite ease-in-out;
    }

    .ky-deal-kicker-today {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.14) 100%);
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.18);
    }

    .ky-deal-title {
        margin: 0;
        color: #0f172a;
        font-size: clamp(20px, 1.7vw, 26px) !important;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .ky-deal-subtitle {
        display: block;
        max-width: 360px;
        margin-top: 4px;
        color: #64748b !important;
        font-size: 13px;
        line-height: 1.45;
    }

    .ky-deal-actions {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        gap: 9px;
    }

    .ky-deal-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        white-space: nowrap;
        min-height: 38px;
        padding: 8px 18px;
        border-radius: 999px;
        background: #0f172a;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12);
        color: #ffffff !important;
        font-size: 12px;
        font-weight: 700;
        transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ky-deal-button:hover {
        background: #2563eb;
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.28);
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    .ky-deal-button i {
        transition: transform 200ms ease;
    }

    .ky-deal-button:hover i {
        transform: translateX(4px);
    }

    .ky-countdown-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .ky-countdown-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .ky-deals-shell .aiz-count-down {
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
    }

    .ky-deals-shell .aiz-count-down .countdown-item {
        min-width: 32px;
        height: 30px;
        padding: 0 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.25);
    }

    .ky-deals-shell .aiz-count-down .countdown-separator {
        color: #ef4444;
        font-size: 15px;
        font-weight: 800;
        margin: 0 1px;
    }

    .ky-flash-content {
        display: grid;
        grid-template-columns: minmax(140px, 0.65fr) minmax(0, 1.65fr);
        gap: clamp(14px, 1.5vw, 22px);
        align-items: stretch;
        margin-top: 20px;
    }

    .ky-flash-banner {
        width: 100% !important;
        height: 100% !important;
        min-height: 0;
        aspect-ratio: 4 / 5;
        border-radius: 18px !important;
        background: #f1f5f9;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
        position: relative;
        overflow: hidden;
    }

    .ky-banner-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 3;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .ky-flash-banner img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
        transition: transform 400ms ease;
    }

    .ky-flash-banner:hover img {
        transform: scale(1.05);
    }

    .ky-products-area {
        min-width: 0;
    }

    .ky-deals-shell .fd-product-slider.slick-slider,
    .ky-deals-shell .td-product-slider.slick-slider {
        margin-top: -6px !important;
        margin-bottom: -12px !important;
    }

    .ky-deals-shell .fd-product-slider.slick-slider .slick-list,
    .ky-deals-shell .td-product-slider.slick-slider .slick-list {
        padding: 6px 0 12px !important;
    }

    .ky-deals-shell .fd-product-slider,
    .ky-deals-shell .td-product-slider {
        overflow: hidden;
    }

    .ky-deals-shell .ky-static-row {
        display: flex;
        flex-wrap: wrap;
        row-gap: 14px;
        padding-top: 6px;
        padding-bottom: 6px;
    }

    .ky-deals-shell .ky-static-row .ky-deal-slide {
        flex: 1 1 135px;
        max-width: 175px;
    }

    .ky-deals-shell .fd-product-card,
    .ky-deals-shell .td-product-card {
        position: relative;
        max-width: 175px;
        margin: 0 4px;
        padding: 8px !important;
        border: 1px solid #f1f5f9 !important;
        border-radius: 14px !important;
        background: #ffffff !important;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.03) !important;
        transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .ky-deals-shell .fd-product-card:hover,
    .ky-deals-shell .td-product-card:hover {
        border-color: rgba(99, 102, 241, 0.3) !important;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08) !important;
        transform: translateY(-3px);
    }

    .ky-deal-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        padding: 2px 6px;
        border-radius: 5px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.25);
    }

    .ky-deals-shell .ky-deal-product-image {
        aspect-ratio: 1 / 1;
        max-height: 135px;
        border-radius: 10px !important;
        background: #f8fafc !important;
        position: relative;
        overflow: hidden;
    }

    .ky-deals-shell .ky-deal-product-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain !important;
        transition: transform 300ms ease;
    }

    .ky-deals-shell .fd-product-card:hover .ky-deal-product-image img,
    .ky-deals-shell .td-product-card:hover .ky-deal-product-image img {
        transform: scale(1.06);
    }

    .ky-product-name {
        min-height: 32px;
        margin-top: 6px !important;
        color: #1e293b !important;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.35;
        transition: color 180ms ease;
    }

    .ky-product-name:hover {
        color: #2563eb !important;
    }

    .ky-stock-bar {
        width: 100%;
        height: 4px;
        border-radius: 99px;
        background: #fee2e2;
        overflow: hidden;
        margin-top: 5px;
    }

    .ky-stock-bar-fill {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(90deg, #ef4444 0%, #f97316 100%);
    }

    .ky-stock-text {
        font-size: 9px;
        font-weight: 700;
        color: #ef4444;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .ky-product-footer {
        min-height: 36px;
        margin-top: 6px !important;
        padding-top: 6px !important;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ky-price-current {
        color: #0f172a;
        font-size: 13px;
        font-weight: 800;
    }

    .ky-price-original {
        color: #94a3b8;
        font-size: 10px;
        font-weight: 500;
        text-decoration: line-through;
        margin-top: -1px;
    }

    .ky-cart-button {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9 !important;
        color: #0f172a !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 200ms ease;
    }

    .ky-cart-button:hover {
        background: #2563eb !important;
        color: #ffffff !important;
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .ky-today-products {
        margin-top: 20px;
    }

    @media (max-width: 1199px) {
        .ky-flash-content {
            grid-template-columns: minmax(150px, 0.55fr) minmax(0, 2fr);
        }

        .ky-flash-banner {
            aspect-ratio: 5 / 4;
        }
    }

    @media (max-width: 767px) {
        .kneayerng-home .ky-deals-shell .ky-deal-panel {
            padding: 18px 14px !important;
        }

        .ky-deal-heading {
            min-height: 0;
        }

        .ky-deal-button {
            min-height: 36px;
            padding: 7px 14px;
        }

        .kneayerng-home .ky-deals-shell .ky-flash-content {
            display: block;
            margin-top: 16px;
        }

        .kneayerng-home .ky-deals-shell .ky-flash-banner.fd-banner-container {
            width: 100% !important;
            height: auto !important;
            min-height: 0;
            aspect-ratio: 16 / 9;
            margin-bottom: 14px;
        }
    }

    /* Modern responsive deal hub */
    .kneayerng-home .ky-deals-shell {
        padding: clamp(14px, 1.8vw, 24px) !important;
    }

    .kneayerng-home .ky-deals-grid {
        display: grid;
        gap: clamp(16px, 1.8vw, 24px);
        margin: 0;
    }

    .kneayerng-home .ky-deals-grid-split {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .kneayerng-home .ky-deals-grid-single {
        grid-template-columns: minmax(0, 1fr);
    }

    .kneayerng-home .ky-deals-grid .ky-deal-panel {
        width: 100%;
        max-width: none;
        min-width: 0;
        padding: clamp(20px, 2vw, 28px) !important;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        transition: box-shadow 300ms ease, border-color 300ms ease;
    }

    .kneayerng-home .ky-deals-grid .ky-deal-panel:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.07);
    }

    .kneayerng-home .ky-deals-grid .ky-flash-panel {
        background: linear-gradient(145deg, #ffffff 0%, #fffdfd 60%, #fff7f7 100%);
        border: 1px solid rgba(254, 202, 202, 0.6);
    }

    .kneayerng-home .ky-deals-grid .ky-today-panel {
        background: linear-gradient(145deg, #ffffff 0%, #fdfbf5 60%, #fffdfa 100%);
        border: 1px solid rgba(224, 231, 255, 0.7);
    }

    .kneayerng-home .ky-deals-grid .ky-deal-heading {
        min-height: 72px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.7);
    }

    .kneayerng-home .ky-deals-grid .ky-deal-title {
        font-size: clamp(20px, 1.7vw, 26px) !important;
    }

    .kneayerng-home .ky-deals-grid .ky-flash-content {
        grid-template-columns: minmax(130px, 0.65fr) minmax(0, 1.55fr);
        gap: clamp(14px, 1.4vw, 20px);
        align-items: stretch;
        margin-top: 0;
    }

    .kneayerng-home .ky-deals-grid .ky-flash-content > a {
        align-self: stretch;
    }

    .kneayerng-home .ky-deals-grid .ky-flash-banner.fd-banner-container {
        width: 100% !important;
        height: 100% !important;
        min-height: 0;
        aspect-ratio: auto;
        border-radius: 16px !important;
        background: #f1f5f9;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .kneayerng-home .ky-deals-grid .ky-flash-banner img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
    }

    .kneayerng-home .ky-deals-grid .ky-today-products {
        margin-top: 0;
    }

    .kneayerng-home .ky-deals-grid .fd-product-card,
    .kneayerng-home .ky-deals-grid .td-product-card {
        margin: 0 5px;
        padding: 10px !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03) !important;
    }

    .kneayerng-home .ky-deals-grid .ky-deal-product-image {
        border-radius: 14px !important;
        background: #f8fafc !important;
    }

    .kneayerng-home .ky-deals-grid .ky-product-name {
        min-height: 38px;
        margin-top: 10px !important;
        color: #1e293b !important;
        font-weight: 650;
    }

    .kneayerng-home .ky-deals-grid .ky-product-footer {
        min-height: 40px;
        margin-top: 8px !important;
        padding-top: 8px !important;
    }

    @media (max-width: 991px) {
        .kneayerng-home .ky-deals-grid-split {
            grid-template-columns: minmax(0, 1fr);
        }

        .kneayerng-home .ky-deals-grid .ky-flash-content {
            grid-template-columns: minmax(150px, 0.5fr) minmax(0, 2fr);
        }
    }

    @media (max-width: 575px) {
        .kneayerng-home .ky-deals-shell {
            padding: 10px !important;
        }

        .kneayerng-home .ky-deals-grid .ky-deal-panel {
            padding: 18px 14px !important;
            border-radius: 18px;
        }

        .kneayerng-home .ky-deals-grid .ky-deal-heading {
            min-height: 0;
            margin-bottom: 16px;
            padding-bottom: 14px;
        }

        .kneayerng-home .ky-deals-grid .ky-flash-content {
            display: block;
        }
    }
        .kneayerng-home .ky-deals-grid .ky-flash-banner.fd-banner-container {
            aspect-ratio: 16 / 9;
        }

        .kneayerng-home .ky-deals-grid .ky-flash-banner img {
            object-fit: contain !important;
        }

        .kneayerng-home .ky-deals-grid .ky-products-area {
            margin-top: 16px;
        }

        .kneayerng-home .ky-deals-grid .fd-product-card,
        .kneayerng-home .ky-deals-grid .td-product-card {
            margin: 0 4px;
        }
    }
</style>

    @php $lang = get_system_language()->code; @endphp
    <main class="kneayerng-home" id="kneayerng-home">
        <!-- Home Banner Start -->
        <div class="aiz-carousel arrow-x-0 arrow-inactive-none hero-banner-carousel d-none  d-md-block" data-items="1" data-full-hd-items="1" data-xxl-items="1"
            data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows='false'
            data-autoplay="true" data-infinite="true">
            @if (get_setting('home_slider_images', null, $lang) != null)
                @php
                    $decoded_slider_images = json_decode(
                        get_setting('home_slider_images', null, $lang),
                        true,
                    );
                    $sliders = get_slider_images($decoded_slider_images);
                    $home_slider_links = get_setting('home_slider_links', null, $lang);
                    $home_slider_colors = get_setting('home_slider_colors', null, $lang);
                @endphp
                @foreach ($sliders as $key => $slider)
                    <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}" class="d-block w-100 hero-banner-wrapper">
                        <div class="hero-banner-container hov-scale-img overflow-hidden" >
                            <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <div class="aiz-carousel arrow-x-0 arrow-inactive-none hero-banner-carousel d-md-none" data-items="1" data-full-hd-items="1" data-xxl-items="1"
            data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows='false'
            data-autoplay="true" data-infinite="true">
            @if (get_setting('home_slider_images', null, $lang) != null)
                @php
                    $desktop_slider_ids = json_decode(get_setting('home_slider_images', null, $lang), true) ?? [];
                    $mobile_slider_ids = json_decode(get_setting('small_home_slider_images', null, $lang) ?: '[]', true) ?? [];
                    $small_decoded_slider_images = collect($desktop_slider_ids)
                        ->map(fn ($desktopId, $key) => !empty($mobile_slider_ids[$key]) ? $mobile_slider_ids[$key] : $desktopId)
                        ->all();
                    $small_sliders = get_slider_images($small_decoded_slider_images);
                    $home_slider_links = get_setting('home_slider_links', null, $lang);
                    $home_slider_colors = get_setting('home_slider_colors', null, $lang);
                @endphp
                @foreach ($small_sliders as $key => $small_slider)
                    <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}" class="d-block w-100 hero-banner-wrapper">
                        <div class="hero-banner-container hov-scale-img overflow-hidden" >
                            <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                src="{{ $small_slider ? my_asset($small_slider->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <!-- Home Banner End -->

        <!-- Flash & Todays Deals Start -->
        @php
            $flash_deal = get_featured_flash_deal();
            $todays_deal_products = filter_products(App\Models\Product::where('todays_deal', '1'))->orderBy('id', 'desc')->get();
            $todays_deal_title_sub_text = get_setting('todays_deal_title_sub_text', null);
            $showFlashDeals = get_setting('enable_flash_deal') == 1 && $flash_deal != null;
            $showTodaysDeals = get_setting('enable_todays_deal') == 1 && $todays_deal_products->isNotEmpty();
        @endphp
        @if ($showFlashDeals || $showTodaysDeals)
            <div class="border-bottom">
                <div class="layout-container ky-deals-shell mx-auto px-0">
                    <div class="row ky-deals-grid @if ($showFlashDeals && $showTodaysDeals) ky-deals-grid-split @else ky-deals-grid-single @endif">
                        <!-- Flash Deal -->
                        @if ($showFlashDeals)
                            <div class="ky-deal-panel ky-flash-panel @if ($showTodaysDeals) col-xl-6 @else col-12 @endif">
                                <div class="ky-deal-heading d-flex align-items-center justify-content-between">
                                    <div class="ky-deal-heading-copy">
                                        <span class="ky-deal-kicker ky-deal-kicker-flash"><i class="las la-bolt"></i> {{ translate('Limited time') }}</span>
                                        <h2 class="ky-deal-title">{{ translate('Flash Deals') }}</h2>
                                        <div class="ky-countdown-row">
                                            <span class="ky-countdown-label">{{ translate('Ends in') }}</span>
                                            <div class="aiz-count-down align-items-center" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                        </div>
                                    </div>
                                    <div class="ky-deal-actions">
                                        <a href="{{ route('flash-deals') }}" class="ky-deal-button">
                                            <span>{{ translate('All Deals') }}</span> <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="ky-flash-content">
                                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="d-block h-100 position-relative">
                                        <div class="ky-flash-banner fd-banner-container overflow-hidden">
                                            <span class="ky-banner-badge"><i class="las la-fire text-warning"></i> {{ translate('Hot Deal') }}</span>
                                            <img class="w-100 h-100"
                                                src="{{ $flash_deal->banner ? uploaded_asset($flash_deal->banner) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ $flash_deal->title }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </div>
                                    </a>
                                    <div class="ky-products-area">
                                            @php
                                                $flash_deal_products = get_flash_deal_products($flash_deal->id);
                                                $flash_visible_count = $flash_deal_products->filter(function ($fdp) {
                                                    return $fdp->product != null && $fdp->product->published != 0;
                                                })->count();
                                                $flash_max_items = $showTodaysDeals ? 4 : 5;
                                                $flash_use_carousel = $flash_visible_count > $flash_max_items;
                                            @endphp
                                            <div class="{{ $flash_use_carousel ? 'aiz-carousel arrow-x-0 arrow-inactive-none' : 'ky-static-row' }} fd-product-slider"
                                                @if ($flash_use_carousel)
                                                    @if ($showTodaysDeals)
                                                        data-items="3" data-full-hd-items="3" data-xxl-items="3" data-xl-items="3" data-lg-items="3"
                                                    @else
                                                        data-items="5" data-full-hd-items="5" data-xxl-items="4" data-xl-items="4" data-lg-items="4"
                                                    @endif
                                                    data-md-items="2.5" data-sm-items="2" data-xs-items="2" data-arrows='false' data-autoplay="true" data-autoplay-speed="10000" data-infinite="true"
                                                @endif>
                                                @foreach ($flash_deal_products as $key => $flash_deal_product)
                                                    @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                                        @php
                                                            $product_url = route('product', $flash_deal_product->product->slug);
                                                            if ($flash_deal_product->product->auction_product == 1) {
                                                                $product_url = route('auction-product', $flash_deal_product->product->slug);
                                                            }
                                                        @endphp    
                                                        <div class="h-100 py-1 ky-deal-slide">
                                                            <div class="h-100 d-flex flex-column justify-content-between fd-product-card overflow-hidden">
                                                                <!-- Discount Badge -->
                                                                @if ($flash_deal_product->discount != null && $flash_deal_product->discount > 0)
                                                                    <span class="ky-deal-badge">
                                                                        -{{ $flash_deal_product->discount }}{{ $flash_deal_product->discount_type == 'percent' ? '%' : '' }}
                                                                    </span>
                                                                @endif

                                                                <div>
                                                                    <!-- Thumbnail Image -->
                                                                    <a href="{{ $product_url }}" title="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                        class="ky-deal-product-image d-block text-center mx-auto position-relative">
                                                                        <img class="w-100 h-100 lazyload"
                                                                            src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                                            data-src="" alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                    </a>

                                                                    <!-- Product Title -->
                                                                    <a href="{{ $product_url }}" title="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                        class="ky-product-name d-block text-truncate-2">
                                                                        {{ $flash_deal_product->product->getTranslation('name') }}
                                                                    </a>

                                                                    <!-- Stock Availability Bar -->
                                                                    <div class="ky-stock-bar">
                                                                        <div class="ky-stock-bar-fill" style="width: 78%;"></div>
                                                                    </div>
                                                                    <div class="ky-stock-text">
                                                                        <i class="las la-fire"></i> {{ translate('Limited Stock') }}
                                                                    </div>
                                                                </div>

                                                                <!-- Price & Cart Button -->
                                                                <div class="ky-product-footer">
                                                                    <div class="overflow-hidden mr-1">
                                                                        @if ($flash_deal_product->auction_product == 0)
                                                                            <div class="ky-price-current text-truncate">
                                                                                {{ home_discounted_base_price($flash_deal_product->product) }}
                                                                            </div>
                                                                            @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                                <div class="ky-price-original text-truncate">
                                                                                    {{ home_base_price($flash_deal_product->product) }}
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <button type="button" class="ky-cart-button flex-shrink-0"
                                                                        onclick="showAddToCartModal({{ $flash_deal_product->product->id }})" title="{{ translate('Add to Cart') }}">
                                                                        <i class="las la-shopping-cart fs-15"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif    
                                                @endforeach
                                            </div>
                                        </div>
                                </div>
                            </div>
                        @endif

                        @if ($showTodaysDeals)
                            <div class="ky-deal-panel ky-today-panel @if ($showFlashDeals) col-xl-6 @else col-12 @endif">
                                <!-- Heading -->
                                <div class="ky-deal-heading d-flex align-items-center justify-content-between">
                                    <div class="ky-deal-heading-copy">
                                        <span class="ky-deal-kicker ky-deal-kicker-today"><i class="las la-star"></i> {{ translate('Picked for today') }}</span>
                                        <h2 class="ky-deal-title">{{ translate("Today's Deals") }}</h2>
                                        <span class="ky-deal-subtitle">
                                            {{ $todays_deal_title_sub_text ?: translate('Fresh offers selected for you today') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('todays-deal') }}" class="ky-deal-button">
                                        <span>{{ translate('View All') }}</span> <i class="las la-arrow-right"></i>
                                    </a>
                                </div>

                                <!-- Slider -->
                                @php
                                    $todays_visible_count = $todays_deal_products->count();
                                    $todays_max_items = $showFlashDeals ? 4 : 6;
                                    $todays_use_carousel = $todays_visible_count > $todays_max_items;
                                @endphp
                                <div class="ky-today-products {{ $todays_use_carousel ? 'aiz-carousel arrow-x-0 arrow-inactive-none' : 'ky-static-row' }} td-product-slider"
                                    @if ($todays_use_carousel)
                                        @if ($showFlashDeals)
                                            data-items="4" data-full-hd-items="4" data-xxl-items="4" data-xl-items="4" data-lg-items="4"
                                        @else
                                            data-items="6" data-full-hd-items="6" data-xxl-items="5" data-xl-items="4" data-lg-items="4"
                                        @endif
                                        data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='false'
                                        data-autoplay="true" data-autoplay-speed="10000" data-infinite="true"
                                    @endif>
                                    @foreach ($todays_deal_products as $key => $product)
                                            <div class="h-100 py-1 ky-deal-slide">
                                                <div class="h-100 d-flex flex-column justify-content-between td-product-card overflow-hidden">
                                                    <div>
                                                        <!-- Thumbnail Image -->
                                                        <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                            class="ky-deal-product-image d-block text-center mx-auto position-relative">
                                                            <img class="w-100 h-100 lazyload"
                                                                src="{{ get_image($product->thumbnail) }}" data-src=""
                                                                alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        </a>

                                                        <!-- Product Title -->
                                                        <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                            class="ky-product-name d-block text-truncate-2">
                                                            {{ $product->getTranslation('name') }}
                                                        </a>
                                                    </div>

                                                    <!-- Price & Cart Button -->
                                                    <div class="ky-product-footer">
                                                        <div class="overflow-hidden mr-1">
                                                            <div class="ky-price-current text-truncate">
                                                                {{ home_discounted_base_price($product) }}
                                                            </div>
                                                            @if (home_base_price($product) != home_discounted_base_price($product))
                                                                <div class="ky-price-original text-truncate">
                                                                    {{ home_base_price($product) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <button type="button" class="ky-cart-button flex-shrink-0"
                                                            onclick="showAddToCartModal({{ $product->id }})" title="{{ translate('Add to Cart') }}">
                                                            <i class="las la-shopping-cart fs-15"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <!-- Flash & Todays Deals End -->

        <!-- Featured Categories Start -->
        @php
            $featured_category_texts = json_decode(get_setting('featured_category_texts'), true) ?? [];
            $featured_category_title_sub_text = get_setting('featured_categories_title_sub_text', null);
        @endphp
        @if (get_setting('enable_featured_categories') == 1)
            @if (count($featured_categories) > 0)
                <div class="layout-container mx-auto px-3 py-30px">
                    <div class="row gutters-16">
                        <div class="col-12 col-md-auto  mt-lg-4 pt-2 mb-2 mb-md-0">
                            <h5 class="fs-20 fw-bold text-dark m-0"> {{ translate('Featured Categories') }} </h5>
                            <p class="fs-14 fw-400 text-dark mt-1 mb-4 mb-md-5"> {{ $featured_category_title_sub_text }} </p>
                            <a href="{{ route('categories.all') }}"
                                class="fs-12 fw-bold text-white bg-dark rounded-pill px-3 py-3 hov-opacity-80 has-transition">{{ translate('View All Categories') }}
                            </a>
                        </div>
                        <div class="col mt-4 mt-md-0">
                            <!-- Slider -->
                            <div class="aiz-carousel arrow-x-0 arrow-inactive-none featured-categories-slider" data-items="10"
                                data-full-hd-items="10" data-xxl-items="8" data-xl-items="5.5" data-lg-items="4.2"
                                data-md-items="3.2" data-sm-items="3" data-xs-items="3" data-arrows='false'
                                data-autoplay="true" data-autoplay-speed="10000" data-infinite="true">
                                @foreach ($featured_categories as $key => $category)
                                    @php
                                        $category_name = $category->getTranslation('name');
                                    @endphp
                                    <div class="">
                                        <a href="{{ route('products.category', $category->slug) }}"
                                            class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-250px">
                                            <img class="w-100 lazyload  has-transition"
                                                src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                alt="{{ $category->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </a>
                                        <div class="mt-3 mb-0 text-center">
                                            <a href="{{ route('products.category', $category->slug) }}" title="{{ $category_name }}"
                                                class="fs-13 fs-md-16 text-reset hov-text-blue fw-semibold d-block text-center mb-1 has-transition text-truncate">{{ $category_name }}</a>
                                            <span class="fs-11 fs-md-14 text-muted fw-400 d-block text-center">
                                                {{ $featured_category_texts[$category->id] ?? null }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <!-- Featured Categories End -->

        <!-- Banner Section Start -->
        @if (get_setting('enable_banner_1') == 1)
            @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang); @endphp
            @if ($homeBanner1Images != null)
                @php
                    $banner_1_imags = json_decode($homeBanner1Images);
                    $home_banner1_links = get_setting('home_banner1_links', null, $lang);
                @endphp
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none landing-banner-carousel landing-banner-one" data-items="2" data-full-hd-items="2" data-xxl-items="2"
                    data-xl-items="2" data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                    data-autoplay="true" data-infinite="true">
                    @foreach ($banner_1_imags as $key => $value)
                        <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}" class="d-block">
                            <div class="banner-lg-container hov-scale-img overflow-hidden">
                                <img class="img-fit w-100 h-100 lazyload  has-transition"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Banner Section End -->
 
        <!-- Featured Product & Best Selling Start -->
        @if (get_setting('enable_featured_products') == 1 || get_setting('enable_best_selling_products') == 1)
            <div class="border-bottom ky-product-showcase-shell">
                <div class="layout-container mx-auto px-3 feactured-best-selling-product-section ky-product-showcase">
                    <div class="row">
                        @php
                            $featured_products_title_sub_text = get_setting('featured_products_title_sub_text', null);
                            $best_selling_products_title_sub_text = get_setting('best_selling_products_title_sub_text', null);
                        @endphp
                        <!-- Featured Products Start -->
                        @if (get_setting('enable_featured_products') == 1 && count(get_featured_products()) > 0)
                            <section class="@if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) col-lg-6 @else col-lg-12 @endif py-30px featured-products-wrapper ky-showcase-panel" aria-labelledby="featured-products-heading">
                                <!-- Heading -->
                                <div class="d-flex align-items-center justify-content-between ky-showcase-heading">
                                    <div class="flex-grow-1 ky-showcase-heading-copy">
                                        <h5 id="featured-products-heading" class="fs-20 fs-md-20 fw-bold mb-0">{{ translate('Featured Products') }}</h5>
                                        @if (!empty($featured_products_title_sub_text) && trim(strtolower($featured_products_title_sub_text)) !== trim(strtolower(translate('Featured Products'))))
                                            <span class="fs-14 fw-400 text-reset ky-showcase-subtitle">{{ $featured_products_title_sub_text }}</span>
                                        @endif
                                    </div>
                                    <div class="ky-showcase-action">
                                        <a href="{{route('featured-products')}}"
                                            class="fs-12 fw-bold text-white bg-dark rounded-pill hov-opacity-80 has-transition ky-showcase-view-all">{{ translate('View All') }} <span aria-hidden="true">&rarr;</span></a>
                                    </div>
                                </div>

                                <!-- Slider -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none custom-product-slider overflow-hidden mt-4 ky-showcase-slider"
                                    @if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) 
                                        data-items="4.2" data-full-hd-items="4.2" data-xxl-items="3" data-xl-items="3" data-lg-items="3" 
                                    @else 
                                        data-items="8" data-full-hd-items="8" data-xxl-items="6" data-xl-items="5" data-lg-items="4" 
                                    @endif
                                    data-md-items="4" data-sm-items="3" data-xs-items="2" data-arrows='false' data-autoplay="true" data-infinite="true">
                                    @foreach (get_featured_products() as $key => $product)
                                        <article class="ky-showcase-card ky-skeleton-host">
                                            @include('frontend.kneayerng_v1.partials.skeleton_card')
                                            @if (discount_in_percentage($product) > 0)
                                                <span class="ky-mobile-discount-badge">-{{ discount_in_percentage($product) }}%</span>
                                            @endif
                                            <button type="button" class="ky-mobile-favorite" onclick="addToWishList({{ $product->id }})" aria-label="{{ translate('Add to wishlist') }}">
                                                <i class="lar la-heart"></i>
                                            </button>
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px ky-showcase-image">
                                                <img class="w-100 lazyload  has-transition"
                                                    src="{{ get_image($product->thumbnail) }}" data-src="{{ get_image($product->thumbnail) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="fs-14 fw-600 text-reset d-block mt-3 product-title hov-text-blue has-transition ky-showcase-title">{{ $product->getTranslation('name') }}
                                            </a>
                                            <span class="ky-showcase-variant">{{ translate('New') }}</span>
                                            <p class="mt-2 mb-0 ky-showcase-price">
                                                <span class="fs-13 fs-md-16 text-dark fw-bold mr-1 ky-showcase-current-price">{{ home_discounted_base_price($product) }}</span>
                                                @if (home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($product) }}</del>
                                                @endif
                                            </p>
                                            @php
                                                $showcaseColors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                                                $showcaseAttributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
                                                $showcaseHasOptions = (is_array($showcaseColors) && count($showcaseColors) > 0) || (is_array($showcaseAttributes) && count($showcaseAttributes) > 0);
                                            @endphp
                                            @if ($product->auction_product == 0)
                                                <button type="button" class="ky-mobile-card-action {{ $showcaseHasOptions ? 'has-options' : 'is-simple' }}"
                                                    @if ($showcaseHasOptions)
                                                        onclick="showAddToCartRightCanvas({{ $product->id }})"
                                                    @elseif (Auth::check() || get_Setting('guest_checkout_activation') == 1)
                                                        onclick="addToCartSingleProduct({{ $product->id }})"
                                                    @else
                                                        onclick="showLoginModal()"
                                                    @endif
                                                    aria-label="{{ $showcaseHasOptions ? translate('Select Options') : translate('Add to Cart') }}">
                                                    @if ($showcaseHasOptions)
                                                        <i class="las la-sliders-h" aria-hidden="true"></i>
                                                        <span>{{ translate('Select Options') }}</span>
                                                    @else
                                                        <i class="las la-shopping-bag" aria-hidden="true"></i>
                                                        <span>{{ translate('Add to Cart') }}</span>
                                                    @endif
                                                </button>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        <!-- Featured Products End -->

                        <!-- Best Selling Start -->
                        @php
                            $best_selling_products = get_best_selling_products(20);
                        @endphp
                        @if (get_setting('best_selling') == 1 && count($best_selling_products) > 0 && get_setting('enable_best_selling_products') == 1)
                            <section class="@if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) col-lg-6 @else col-lg-12 @endif py-30px best-selling-products-wrapper ky-showcase-panel" aria-labelledby="best-selling-heading">
                                <!-- Heading -->
                                <div class="d-flex align-items-center justify-content-between ky-showcase-heading">
                                    <div class="flex-grow-1 ky-showcase-heading-copy">
                                        <h5 id="best-selling-heading" class="fs-20 fs-md-20 fw-bold mb-0">{{ translate('Best Selling') }}</h5>
                                        @if (!empty($best_selling_products_title_sub_text) && trim(strtolower($best_selling_products_title_sub_text)) !== trim(strtolower(translate('Best Selling'))))
                                            <span class="fs-14 fw-400 text-reset ky-showcase-subtitle">{{ $best_selling_products_title_sub_text }}</span>
                                        @endif
                                    </div>
                                    <div class="ky-showcase-action">
                                        <a href="{{route('best-selling')}}"
                                            class="fs-12 fw-bold text-white bg-dark rounded-pill hov-opacity-80 has-transition ky-showcase-view-all">{{ translate('View All') }} <span aria-hidden="true">&rarr;</span></a>
                                    </div>
                                </div>

                                <!-- Slider -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none custom-product-slider overflow-hidden mt-4 ky-showcase-slider"
                                    @if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) 
                                        data-items="4.2" data-full-hd-items="4.2" data-xxl-items="3" data-xl-items="3" data-lg-items="3" 
                                    @else 
                                        data-items="8" data-full-hd-items="8" data-xxl-items="6" data-xl-items="5" data-lg-items="4" 
                                    @endif
                                    data-md-items="4" data-sm-items="3" data-xs-items="2" data-arrows='false'
                                    data-autoplay="true" data-infinite="true">
                                    @foreach ($best_selling_products as $key => $product)
                                        <article class="ky-showcase-card ky-skeleton-host">
                                            @include('frontend.kneayerng_v1.partials.skeleton_card')
                                            @if (discount_in_percentage($product) > 0)
                                                <span class="ky-mobile-discount-badge">-{{ discount_in_percentage($product) }}%</span>
                                            @endif
                                            <button type="button" class="ky-mobile-favorite" onclick="addToWishList({{ $product->id }})" aria-label="{{ translate('Add to wishlist') }}">
                                                <i class="lar la-heart"></i>
                                            </button>
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px ky-showcase-image">
                                                <img class="w-100 lazyload  has-transition"
                                                    src="{{ get_image($product->thumbnail) }}"
                                                    data-src="{{ get_image($product->thumbnail) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('product', $product->slug) }}"
                                                class="fs-14 fw-600 text-reset d-block mt-3 product-title hov-text-blue has-transition ky-showcase-title">{{ $product->getTranslation('name') }}
                                            </a>
                                            <span class="ky-showcase-variant">{{ translate('New') }}</span>
                                            <p class="mt-2 mb-0 ky-showcase-price">
                                                <span class="fs-13 fs-md-16 text-dark fw-bold mr-1 ky-showcase-current-price">{{ home_discounted_base_price($product) }}</span>
                                                @if (home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($product) }}</del>
                                                @endif
                                            </p>
                                            @php
                                                $showcaseColors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                                                $showcaseAttributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
                                                $showcaseHasOptions = (is_array($showcaseColors) && count($showcaseColors) > 0) || (is_array($showcaseAttributes) && count($showcaseAttributes) > 0);
                                            @endphp
                                            @if ($product->auction_product == 0)
                                                <button type="button" class="ky-mobile-card-action {{ $showcaseHasOptions ? 'has-options' : 'is-simple' }}"
                                                    @if ($showcaseHasOptions)
                                                        onclick="showAddToCartRightCanvas({{ $product->id }})"
                                                    @elseif (Auth::check() || get_Setting('guest_checkout_activation') == 1)
                                                        onclick="addToCartSingleProduct({{ $product->id }})"
                                                    @else
                                                        onclick="showLoginModal()"
                                                    @endif
                                                    aria-label="{{ $showcaseHasOptions ? translate('Select Options') : translate('Add to Cart') }}">
                                                    @if ($showcaseHasOptions)
                                                        <i class="las la-sliders-h" aria-hidden="true"></i>
                                                        <span>{{ translate('Select Options') }}</span>
                                                    @else
                                                        <i class="las la-shopping-bag" aria-hidden="true"></i>
                                                        <span>{{ translate('Add to Cart') }}</span>
                                                    @endif
                                                </button>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        <!-- Best Selling End -->
                    </div>
                </div>
            </div>
        @endif    
        <!-- Featured Product & Best Selling End -->

        <!-- Categories -->
        @php
            $mainCategories = json_decode(get_setting('main_categories'), true) ?? [];
            $childCategories = json_decode(get_setting('child_categories'), true) ?? [];
        @endphp

        @if (get_setting('enable_categories') == 1 && count($mainCategories) > 0)

        <!-- Category Section Redesign Start -->
        @if (isset($mainCategories) && count($mainCategories) > 0)
            <div class="py-4 py-lg-5 bg-white border-bottom">
                <div class="layout-container mx-auto px-3">
                    <div class="row gutters-20">
                        @foreach ($mainCategories as $key => $mainCategoryId)
                            @php
                                $mainCategory = \App\Models\Category::find($mainCategoryId);
                                if (!$mainCategory) {
                                    continue;
                                }
                                $selectedChildIds = $childCategories[$key] ?? [];
                                $selectedChildren = \App\Models\Category::whereIn('id', $selectedChildIds)->get();
                                $mainCategoryImageId = $mainCategory->cover_image ?: ($mainCategory->banner ?: $mainCategory->icon);
                                $mainCategoryImage = $mainCategoryImageId
                                    ? uploaded_asset($mainCategoryImageId)
                                    : static_asset('assets/img/placeholder.jpg');
                            @endphp

                            <div class="col-12 col-md-6 col-xl-4 mb-4">
                                <div class="card ky-category-card h-100 overflow-hidden bg-white">
                                    <!-- Category Card Header -->
                                    <div class="ky-category-card-header p-3 bg-soft-primary-light border-bottom d-flex align-items-center justify-content-between">
                                        <h5 class="ky-category-card-title fs-16 fs-md-18 fw-bold text-dark m-0 text-truncate">
                                            {{ $mainCategory->getTranslation('name') }}
                                        </h5>
                                        <a href="{{ route('products.category', $mainCategory->slug) }}" class="ky-category-view-all fs-12 fw-bold text-primary animate-underline-blue d-inline-flex align-items-center">
                                            <span>{{ translate('View All') }}</span>
                                            <i class="las la-angle-right ml-1 fs-14"></i>
                                        </a>
                                    </div>

                                    <!-- Category Card Body -->
                                    <div class="ky-category-card-body p-3 d-flex flex-column h-100">
                                        @if(count($selectedChildren) > 0)
                                            <div class="row gutters-12 h-100 align-items-stretch">
                                                <!-- Main Cover Image -->
                                                <div class="col-5">
                                                    <a href="{{ route('products.category', $mainCategory->slug) }}" class="ky-category-cover-new d-block h-100 border border-gray-200">
                                                        <img src="{{ $mainCategoryImage }}"
                                                            alt="{{ $mainCategory->getTranslation('name') }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </a>
                                                </div>

                                                <!-- Child Categories -->
                                                <div class="col-7 pl-1">
                                                    <div class="ky-child-category-list d-flex h-100 flex-column justify-content-center">
                                                        @foreach ($selectedChildren->take(3) as $childCategory)
                                                            @php
                                                                $childCategoryImageId = $childCategory->cover_image ?: ($childCategory->banner ?: $childCategory->icon);
                                                                $childCategoryImage = $childCategoryImageId
                                                                    ? uploaded_asset($childCategoryImageId)
                                                                    : static_asset('assets/img/placeholder.jpg');
                                                            @endphp
                                                            <a href="{{ route('products.category', $childCategory->slug) }}"
                                                               class="ky-child-category-link d-flex align-items-center text-reset has-transition border border-gray-100">
                                                                <div class="ky-child-category-image flex-shrink-0 mr-2 border border-gray-200">
                                                                    <img src="{{ $childCategoryImage }}"
                                                                        alt="{{ $childCategory->getTranslation('name') }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                </div>
                                                                <div class="overflow-hidden flex-grow-1">
                                                                    <div class="fs-13 fw-bold text-dark text-truncate" title="{{ $childCategory->getTranslation('name') }}">
                                                                        {{ $childCategory->getTranslation('name') }}
                                                                    </div>
                                                                    <div class="fs-11 text-muted">{{ translate('Shop Now') }}</div>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Layout without children -->
                                            <div class="d-flex align-items-center justify-content-center flex-grow-1">
                                                <a href="{{ route('products.category', $mainCategory->slug) }}" class="ky-category-cover-new d-block w-100 border border-gray-200" style="height: 200px;">
                                                    <img src="{{ $mainCategoryImage }}"
                                                        alt="{{ $mainCategory->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <!-- Category Section Redesign End -->

        @endif
        <!-- Categories -->

        <!-- Auction Start -->
        @if (get_setting('enable_auction_products') == 1)
            @if (addon_is_activated('auction'))
                <div id="auction_products"></div>
            @endif
        @endif
        <!-- Auction Product End -->


        <!-- Classified Adds Start -->
        @if (get_setting('enable_classified_products') == 1)
            @if (get_setting('classified_product') == 1)
                @php
                    $classified_products = get_home_page_classified_products();
                    $classified_title_sub_text = get_setting('classified_title_sub_text', null);
                @endphp
                @if (count($classified_products) > 0)
                    <div class="border-bottom">
                        <div class="layout-container mx-auto px-3 py-30px">
                            <!-- Heading -->
                            <div class="d-flex flex-wrap  align-items-start justify-content-between mb-1" style="gap: 12px">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Classified Ads') }}</h5>
                                    <span
                                        class="d-block w-100 fs-14 fw-400 text-reset text-truncate">{{ $classified_title_sub_text }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('customer.products') }}"
                                        class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                </div>
                            </div>
                            <!-- Banner & Slider -->
                            <div class="row d-flex mt-3">
                                <div class="col-12 col-md-auto">
                                    <!-- MD Screen Only -->
                                    <div class="d-none d-md-block h-100">
                                        <a href="{{ route('customer.product', $product->slug) }}"class="d-block w-100 h-100">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch  classified-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset(get_setting('classified_banner_image', null, get_system_language()->code)) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ env('APP_NAME') }} promo">
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Mobile Screen Only  (Upload Antother Image For height 180px) -->
                                    <div class="d-md-none">
                                        <a href="{{ route('customer.product', $product->slug) }}" class="d-block w-100 h-180px">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch  classified-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset(get_setting('classified_banner_image_small', null, get_system_language()->code)) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ env('APP_NAME') }} promo">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col mt-3 mt-md-0">
                                    <!-- Slider -->
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none custom-product-slider overflow-hidden"
                                        id="auction-product-slider" data-items="4" data-rows="2" data-full-hd-items="4"
                                        data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="1" data-sm-items="1.1"
                                        data-xs-items="1.1" data-arrows='false' data-autoplay="true" data-infinite="true">
                                        @foreach ($classified_products as $key => $product)
                                            <div class="d-flex">
                                                <a href="{{ route('customer.product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                    class="d-block overflow-hidden hov-scale-img rounded-2 w-150px h-150px mr-2 mr-lg-3  flex-shrink-0">
                                                    <img class="img-fit w-100 h-100 lazyload  has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ get_image($product->thumbnail) }}"
                                                        alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                                <div class="pl-1">
                                                    <a href="{{ route('customer.product', $product->slug) }}"
                                                        class="fs-14 fw-400 text-reset hov-text-blue has-transition text-truncate-2">
                                                        {{ $product->getTranslation('name') }}
                                                    </a>
                                                    <p class="fs-16 fw-bold text-dark mt-3 mb-3">{{ single_price($product->unit_price) }}</p>
                                                    @if ($product->conditon == 'new')
                                                        <button type="button" class="border-0 fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('New') }}</button>
                                                    @elseif($product->conditon == 'used')
                                                        <button type="button" class="border-0 fs-12 fw-bold text-white bg-danger px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('Used') }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
        <!-- Classified Adds End -->


        <!-- Banner Section Start -->
        @if (get_setting('enable_banner_2') == 1)
            @php 
                $homeBanner2Images = get_setting('home_banner2_images', null, $lang); 
            @endphp
            @if ($homeBanner2Images != null)
                @php
                    $banner_2_images = json_decode($homeBanner2Images, true) ?? [];
                    $data_md = count($banner_2_images) >= 2 ? 2 : 1;
                    $home_banner2_links = get_setting('home_banner2_links', null, $lang);
                @endphp
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none landing-banner-carousel landing-banner-two" data-items="4" data-full-hd-items="4" data-xxl-items="3"
                    data-xl-items="3" data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @foreach ($banner_2_images as $key => $value)
                        <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}" class="d-block">
                            <div class="banner-lg-container-two hov-scale-img overflow-hidden">
                                <img class="img-fit w-100 h-100 lazyload  has-transition"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Banner Section End -->

        <!-- Pre Order Adds Start -->
        @if (get_setting('enable_preorder_products') == 1)
            @if (addon_is_activated('preorder'))
                @php
                    $newest_preorder_products = \App\Models\PreorderProduct::where('is_published', 1)
                        ->where(function ($query) {
                            $query->whereHas('user', function ($q) {
                                $q->where('user_type', 'admin');
                            })->orWhereHas('user.shop', function ($q) {
                                $q->where('verification_status', 1);
                            });
                        })
                        ->latest()
                        ->limit(12)
                        ->get();
                    $preorder_title_sub_text = get_setting('preorder_title_sub_text', null);    
                @endphp
                @if (count($newest_preorder_products) > 0)
                    <div class="border-bottom">
                        <div class="layout-container mx-auto px-3 py-30px">
                            <!-- Heading -->
                            <div class="d-flex flex-wrap  align-items-start justify-content-between mb-1" style="gap: 12px">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Preorder Products') }}</h5>
                                    <span
                                        class="d-block w-100 fs-14 fw-400 text-reset text-truncate">{{ $preorder_title_sub_text }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('all_preorder_products') }}"
                                        class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                </div>
                            </div>
                            <!-- Banner & Slider -->
                            @php
                                $newest_preorder_banner_image = get_setting('newest_preorder_banner_image', null, $lang);
                                $newest_preorder_banner_image_small = get_setting('newest_preorder_banner_image_small', null, $lang);
                            @endphp
                            <div class="row d-flex mt-3">
                                <div class="col-12 col-md-auto">
                                    <!-- MD Screen Only -->
                                    <div class="d-none d-md-block h-100">
                                        <a href="{{ route('all_preorder_products') }}" class="d-block w-100 h-100">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch preorder-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset($newest_preorder_banner_image) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ translate('Newest Preorder Products') }}">
                                            </div>
                                        </a>
                                    </div>
                                     <!-- Mobile Screen Only (Upload Antother Image For height 180px) -->
                                    <div class="d-md-none">
                                        <a href="{{ route('all_preorder_products') }}" class="d-block w-100 h-180px">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch preorder-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset($newest_preorder_banner_image_small) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ translate('Newest Preorder Products') }}">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col mt-3 mt-md-0  pre-order-product-wrapper">
                                    <!-- Slider -->
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden"
                                        data-items="7" data-full-hd-items="7" data-xxl-items="4.6" data-xl-items="4.4"
                                        data-lg-items="4" data-md-items="3" data-sm-items="3" data-xs-items="2"
                                        data-arrows='false' data-autoplay="true" data-infinite="true">
                                        @foreach ($newest_preorder_products as $key => $product)
                                            <div class="">
                                                <a href="{{ route('preorder-product.details', $product->product_slug) }}"
                                                    class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px">
                                                    <img class="w-100 lazyload  has-transition"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                        data-src="{{ uploaded_asset($product->thumbnail) }}"
                                                        alt="{{ $product->product_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                                <a href="{{ route('preorder-product.details', $product->product_slug) }}"
                                                    class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition text-truncate-2">{{ $product->product_name }}
                                                </a>
                                                <div class="rating rating-mr-2 mt-2 d-flex" style="gap: 8px">
                                                    {{ renderStarRating($product->rating) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
        <!-- Pre Order Product End -->

        <!-- Shop by Sellers, Shop by Brands Start -->
        @if (get_setting('enable_shop_by_seller') == 1 || get_setting('enable_shop_by_brand') == 1)
            <div class="border-bottom">
                <div class="layout-container mx-auto px-3 seller-brand-shop-section">
                    <div class="row">
                        <!-- Shop by Sellers Start -->
                        @if (get_setting('vendor_system_activation') == 1 && get_setting('enable_shop_by_seller') == 1 )
                            @php
                                $best_selers = get_best_sellers(6);
                                $shop_by_seller_title_sub_text = get_setting('shop_by_seller_title_sub_text', null);
                            @endphp
                            @if (count($best_selers) > 0)
                                <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-lg-6 @else col-lg-12 border-0 @endif py-30px featured-products-wrapper">
                                    <!-- Heading -->
                                    <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Shop by Sellers') }}</h5>
                                            <span
                                                class="fs-14 fw-400 text-reset d-block text-truncate">{{ $shop_by_seller_title_sub_text }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('sellers') }}"
                                                class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                        </div>
                                    </div>
                                    {{-- <div class="row gutters-16 mt-4 shop-by-seller">
                                        @foreach ($best_selers as $key => $seller)
                                            <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-6 col-md-4 col-lg-6 col-xl-4 col-xxl-3 @else col-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 @endif mb-3">
                                                <a href="{{ route('shop.visit', $seller->slug) }}"
                                                    class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                    <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($seller->logo) }}"
                                                        alt="{{ $seller->name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                                <a href="{{ route('shop.visit', $seller->slug) }}"
                                                    class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition">{{ $seller->name }}
                                                </a>
                                                <div class="rating rating-mr-1 mt-1 mb-1 d-flex" style="gap: 8px">
                                                    {{ renderStarRating($seller->rating) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('shop.visit', $seller->slug) }}"
                                                        class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center w-100 py-2">
                                                        <span class="pr-2">
                                                            {{ translate('Visit Store') }}
                                                        </span>
                                                        <i class="las la-arrow-right fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div> --}}

                                    <!-- Slider -->
                                   <div class="mt-4 shop-by-seller">
                                        <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                            @if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) 
                                                    data-items="4" data-full-hd-items="4" data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="2"
                                            @else 
                                                data-items="6" data-full-hd-items="6" data-xxl-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3"
                                            @endif
                                            data-sm-items="2" data-xs-items="2" data-arrows='false'
                                            data-autoplay="false" data-infinite="true">
                                                @foreach ($best_selers as $key => $seller)
                                                    <div>
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                            <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($seller->logo) }}"
                                                                alt="{{ $seller->name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                        </a>
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition">{{ $seller->name }}
                                                        </a>
                                                        <div class="rating rating-mr-1 mt-1 mb-1 d-flex" style="gap: 8px">
                                                            {{ renderStarRating($seller->rating) }}
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('shop.visit', $seller->slug) }}"
                                                                class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center w-100 py-2">
                                                                <span class="pr-2">
                                                                    {{ translate('Visit Store') }}
                                                                </span>
                                                                <i class="las la-arrow-right fs-18"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                        </div>
                                   </div>

                                </div>
                            @endif    
                        @endif    
                        <!-- Shop by Sellers End -->

                        <!-- Shop by Brands Start -->
                        @if (get_setting('top_brands') != null && get_setting('enable_shop_by_brand') == 1 )
                            @php
                                $shop_by_brand_title_sub_text = get_setting('shop_by_brand_title_sub_text', null);
                            @endphp
                            <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-lg-6 @else col-lg-12 @endif py-30px best-selling-products-wrapper">
                                <!-- Heading -->
                                <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Shop by Brands') }}</h5>
                                        <span
                                            class="fs-14 fw-400 text-reset d-block text-truncate">{{ $shop_by_brand_title_sub_text }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route('brands.all') }}"
                                            class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                    </div>
                                </div>
                                <!-- Products -->
                                {{-- <div class="row gutters-16 mt-4 shop-by-brand"> --}}
                                    @php
                                        $top_brands = json_decode(get_setting('top_brands'));
                                        $brands = get_brands($top_brands);
                                        $shop_by_brand_title_sub_text = get_setting('shop_by_brand_title_sub_text', null);
                                    @endphp
                                    {{-- @foreach ($brands as $brand)
                                        <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-6 col-md-4 col-lg-6 col-xl-4 col-xxl-3 @else col-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 @endif mb-3">
                                            <a href="{{ route('products.brand', $brand->slug) }}"
                                                class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                    src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                    alt="{{ $brand->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('products.brand', $brand->slug) }}"
                                                class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition text-center">{{ $brand->getTranslation('name') }}
                                            </a>
                                            <div class="text-center">
                                                <a href="{{ route('products.brand', $brand->slug) }}"
                                                    class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center justify-content-center w-100 py-2 w-100">
                                                    <span class="pr-2">
                                                        {{ translate('View All Products') }}
                                                    </span>
                                                    <i class="las la-arrow-right fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach --}}
                                {{-- </div> --}}

                                
                                <!-- Product Slider -->
                                <div class="mt-4 shop-by-brand">
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                        @if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) 
                                                data-items="4" data-full-hd-items="4" data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="2"
                                        @else 
                                            data-items="6" data-full-hd-items="6" data-xxl-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3"
                                        @endif
                                        data-sm-items="2" data-xs-items="2" data-arrows='false'
                                        data-autoplay="false" data-infinite="true">
                                            @foreach ($brands as $brand)
                                                <div>
                                                    <a href="{{ route('products.brand', $brand->slug) }}"
                                                        class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                        <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                            src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                            alt="{{ $brand->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </a>
                                                    <a href="{{ route('products.brand', $brand->slug) }}"
                                                        class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition text-center">{{ $brand->getTranslation('name') }}
                                                    </a>
                                                    <div class="text-center">
                                                        <a href="{{ route('products.brand', $brand->slug) }}"
                                                            class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center justify-content-center w-100 py-2 w-100">
                                                            <span class="pr-2">
                                                                {{ translate('View All Products') }}
                                                            </span>
                                                            <i class="las la-arrow-right fs-18"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Shop by Brands End -->
                    </div>
                </div>
            </div>
        @endif

        <!-- Shop by Sellers, Shop by Brands End -->

        <!-- Mega Banner Start -->
        @if (get_setting('enable_banner_3') == 1)
            @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
            @if ($homeBanner3Images != null)
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none mega-banner-carousel d-none d-md-block" data-items="1" data-full-hd-items="1" data-xxl-items="1"
                    data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @php
                        $banner_3_imags = json_decode($homeBanner3Images);
                        $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                        $home_banner3_colors = get_setting('home_banner3_colors', null, $lang);
                    @endphp
                    @foreach ($banner_3_imags as $key => $value)
                        <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}" class="d-block w-100 mega-banner-wrapper">
                            <div class="mega-banner-container hov-scale-img overflow-hidden">
                                <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            @php
                $smallHomeBanner3Images = get_setting('small_home_banner3_images', null, $lang);
                $desktopBanner3Ids = json_decode($homeBanner3Images, true) ?? [];
                $mobileBanner3Ids = json_decode($smallHomeBanner3Images ?: '[]', true) ?? [];
                $resolvedMobileBanner3Ids = collect($desktopBanner3Ids)
                    ->map(fn ($desktopId, $key) => !empty($mobileBanner3Ids[$key]) ? $mobileBanner3Ids[$key] : $desktopId)
                    ->all();
            @endphp
            @if (count($resolvedMobileBanner3Ids) > 0)
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none mega-banner-carousel d-md-none" data-items="1" data-full-hd-items="1" data-xxl-items="1"
                    data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @php
                        $banner_3_imags_small = $resolvedMobileBanner3Ids;
                        $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                        $home_banner3_colors = get_setting('home_banner3_colors', null, $lang);
                    @endphp
                    @foreach ($banner_3_imags_small as $key => $value)
                        <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}" class="d-block w-100 mega-banner-wrapper">
                            <div class="mega-banner-container hov-scale-img overflow-hidden">
                                <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Mega Banner End -->

        <!-- Products Start -->
        <div class="layout-container px-3 py-5 mx-auto" id="nexa-product-wrapper" data-products-per-row="8">
            <div class="ky-newest-heading">
                <div>
                    <h2>{{ translate('New Products') }}</h2>
                    <p>{{ translate('Fresh arrivals selected for you') }}</p>
                </div>
                <a href="{{ route('search') }}" class="ky-newest-see-all">
                    <span>{{ translate('See All') }}</span><i class="las la-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
            <div id="section_newest">
                <div class="products-wrapper-grid ky-initial-skeleton-grid" role="status" aria-label="{{ translate('Loading products') }}">
                    @for ($skeletonIndex = 0; $skeletonIndex < 8; $skeletonIndex++)
                        <div class="grid-item single-product-item ky-initial-skeleton-card">
                            @include('frontend.kneayerng_v1.partials.skeleton_card')
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Computers Section -->
            @if(isset($computers) && count($computers) > 0)
            <div class="mt-5 ky-computers-section">
                <div class="ky-computers-heading">
                    <div class="ky-computers-heading__title">
                        <span class="ky-computers-heading__icon"><i class="las la-laptop" aria-hidden="true"></i></span>
                        <div>
                            <h3>{{ translate('New Computers') }}</h3>
                            <p>{{ translate('Discover the latest performance machines') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('computers.index') }}" class="ky-computers-view-all">
                        <span>{{ translate('View all') }}</span><i class="las la-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="products-wrapper-grid" id="newest-computers-list">
                    @foreach ($computers as $computer)
                        <div class="grid-item single-product-item">
                            @include('frontend.' . get_setting('homepage_select') . '.partials.home_computer_box', [
                                'computer' => $computer
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Accessories Section -->
            @if(isset($accessories) && count($accessories) > 0)
            <div class="mt-5 ky-accessories-section">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('New Accessories') }}</span>
                    </h3>
                    <a href="{{ route('accessories.index') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md ky-accessories-view-all">{{ translate('View all') }} <span aria-hidden="true">&rarr;</span></a>
                </div>
                <div class="products-wrapper-grid" id="newest-accessories-list">
                    @foreach ($accessories as $accessory)
                        <div class="grid-item single-product-item">
                            @include('frontend.' . get_setting('homepage_select') . '.partials.home_accessory_box', [
                                'accessory' => $accessory
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Load More Button -->
            <div class="mt-5 mb-5 d-flex align-items-center justify-content-center d-none" id="view-more-container">
                <button type="button" id="view-more-btn"
                    class="flex-shrink-0 border border-dashed border-gray-400 rounded-1 bg-white hov-bg-dark text-gray hov-text-white has-transition fs-16 fw-bold py-2 px-4 py-md-3 px-md-4 w-200px w-md-300px w-lg-400px">
                    {{ translate('Load More') }}
                    <i id="spinner-icon" class="las la-lg la-spinner la-spin d-none"></i>
                </button>
            </div>
        </div>
        <!-- Products End -->


        <!-- Back to Top Start -->
        <button id="backToTop" aria-label="Back to top" class="has-transition" data-toggle="tooltip" data-placement="left" title="Back to Top">
            <i class="las la-arrow-up fs-20"></i>
        </button>
         <!-- Back to Top End -->
    </main>
@endsection

@section('script')
<script>
    // Countdown for mobile view
    function startSimpleCountdown(endDate) {
        function update() {
            const now = new Date();
            const diff = endDate - now;
            if (diff > 0) {
                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / (60 * 60 * 24));
                const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
                const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
                const secs = totalSeconds % 60;

                document.getElementById("simple-days").textContent = days.toString().padStart(2, '0');
                document.getElementById("simple-hours").textContent = hours.toString().padStart(2, '0');
                document.getElementById("simple-mins").textContent = mins.toString().padStart(2, '0');
                document.getElementById("simple-secs").textContent = secs.toString().padStart(2, '0');
            } else {
                document.querySelector(".mobile-countdown-simple").textContent = "Sale ended";
                clearInterval(timer);
            }
        }

        update();
        const timer = setInterval(update, 1000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const countdownEl = document.querySelector('.mobile-countdown-simple');
        if (!countdownEl) return;

        const endDateStr = countdownEl.dataset.endDate;
        if (endDateStr) {
            const parsedEndDate = new Date(endDateStr.replace(/-/g, '/'));
            startSimpleCountdown(parsedEndDate);
        }
    });

    let page = 1;

    $(document).on('click', '#view-more-btn', function() {

        const $button = $(this);
        const originalText = $button.html();

        page++;

        $button.html('{{ translate("Loading...") }} <i id="spinner-icon" class="las la-lg la-spinner la-spin"></i>');
        $button.prop('disabled', true);

                let loadMoreLimit = 30;

        @if (in_array(get_setting('homepage_select'), ['nexa', 'kneayerng_v1']))

            let perRow = parseInt($('#nexa-product-wrapper').attr('data-products-per-row')) || 4;

                    loadMoreLimit = perRow * 5;

        @endif

        $.post('{{ route('home.section.newest_products') }}', {
            _token: '{{ csrf_token() }}',
            page: page,
            limit: loadMoreLimit
        }, function(data) {

            $button.prop('disabled', false);
            $button.html(originalText);

            if ($.trim(data) === '') {

                $button.prop('disabled', true)
                    .text('{{ translate("No More Products") }}');

            } else {

                $('#newest-products-list').append(data);

                AIZ.plugins.slickCarousel();
            }

        }).fail(function() {

            $button.prop('disabled', false);

            $button.html('{{ translate("Error, Try Again") }} <i id="spinner-icon" class="las la-lg la-spinner la-spin d-none"></i>');

        });

    });

    $(window).on('load', function() {
        $('.hot-category-box').addClass('d-flex flex-column justify-content-center align-items-center');
    });

    function toggleViewMoreButton() {
        if ($.trim($('#section_newest').html()).length > 0) {
            $('#view-more-container').removeClass('d-none').addClass('d-block');
        } else {
            $('#view-more-container').removeClass('d-block').addClass('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const home = document.getElementById('kneayerng-home');

        if (!home) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const sections = Array.from(home.children).filter(function (element) {
            return element.matches('.border-bottom, .layout-container, .aiz-carousel');
        });

        if (reduceMotion || !('IntersectionObserver' in window)) {
            sections.forEach(function (section) {
                section.classList.add('ky-reveal', 'is-visible');
            });
        } else {
            const revealObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, {
                rootMargin: '0px 0px -8% 0px',
                threshold: 0.08
            });

            sections.forEach(function (section) {
                section.classList.add('ky-reveal');
                revealObserver.observe(section);
            });
        }

        const prepareProductCards = function () {
            home.querySelectorAll('.products-wrapper-grid .grid-item:not(.ky-card-ready)').forEach(function (card, index) {
                card.classList.add('ky-card-ready');
                card.style.setProperty('--ky-card-delay', Math.min(index % 8, 7) * 45 + 'ms');
            });
        };

        const prepareSkeletonCards = function () {
            home.querySelectorAll('.ky-skeleton-host:not(.ky-skeleton-bound)').forEach(function (card) {
                card.classList.add('ky-skeleton-bound');

                const primaryImage = card.querySelector('.product-main-image, .ky-showcase-image img');
                const revealCard = function () {
                    card.classList.add('ky-skeleton-loaded');
                };

                if (!primaryImage) {
                    revealCard();
                    return;
                }

                const hasPendingLazySource = Boolean(primaryImage.dataset.src) && !primaryImage.classList.contains('lazyloaded');
                if (primaryImage.complete && primaryImage.naturalWidth > 0 && !hasPendingLazySource) {
                    revealCard();
                    return;
                }

                primaryImage.addEventListener('lazyloaded', revealCard, { once: true });
                primaryImage.addEventListener('load', function () {
                    if (!primaryImage.dataset.src || primaryImage.classList.contains('lazyloaded')) {
                        revealCard();
                    }
                });
                primaryImage.addEventListener('error', revealCard, { once: true });

                // Never leave real product content covered if an image request stalls.
                window.setTimeout(revealCard, 8000);
            });
        };

        prepareProductCards();
        prepareSkeletonCards();

        const newestSection = document.getElementById('section_newest');
        if (newestSection && 'MutationObserver' in window) {
            new MutationObserver(function () {
                prepareProductCards();
                prepareSkeletonCards();
            }).observe(newestSection, {
                childList: true,
                subtree: true
            });
        }
    });

</script>


<!-- Bact to Top Start -->
<script>
    {
        const btn = document.getElementById('backToTop');

        if (btn) {
            const SCROLL_THRESHOLD = 100;
            let rafId = null;

            const toggle = () => {
                btn.classList.toggle('show', window.scrollY > SCROLL_THRESHOLD);
                rafId = null;
            };

            window.addEventListener('scroll', () => {
                if (rafId) return;
                rafId = requestAnimationFrame(toggle);
            }, { passive: true });

            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

        
            toggle();
        }
    }
</script>
<!-- Bact to Top End -->
@endsection
