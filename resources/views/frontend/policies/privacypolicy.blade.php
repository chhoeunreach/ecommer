@php
    $layout = 'frontend.layouts.app';

    if (addon_is_activated('portfolio_system')) {
        $user = auth()->user();

        if (
            !$user ||
            $user->verification_status == 0 ||
            optional($user->shop)->verification_status == 0
        ) {
            $layout = 'frontend.layouts.portfolio_app';
        }
    }
@endphp

@extends($layout)

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
@php
    $baseColor = get_setting('base_color', '#1b74e4');
    $baseColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $baseColor) ? $baseColor : '#1b74e4';
    $privacyColorDefaults = [
        'hero_start' => $baseColor,
        'hero_end' => '#0f172a',
        'hero_text' => '#ffffff',
        'accent' => '#10b981',
        'card_background' => '#ffffff',
        'heading' => '#111723',
        'text' => '#4e5561',
    ];
    $storedPrivacyColors = json_decode(get_setting('privacy_page_colors', '{}'), true);
    $storedPrivacyColors = is_array($storedPrivacyColors) ? $storedPrivacyColors : [];
    $privacyColors = [];
    foreach ($privacyColorDefaults as $colorKey => $defaultColor) {
        $colorValue = $storedPrivacyColors[$colorKey] ?? $defaultColor;
        $privacyColors[$colorKey] = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $colorValue)
            ? $colorValue
            : $defaultColor;
    }
@endphp
<div class="privacy-page-theme" style="
    --privacy-hero-start: {{ $privacyColors['hero_start'] }};
    --privacy-hero-end: {{ $privacyColors['hero_end'] }};
    --privacy-hero-text: {{ $privacyColors['hero_text'] }};
    --privacy-accent: {{ $privacyColors['accent'] }};
    --privacy-card-background: {{ $privacyColors['card_background'] }};
    --privacy-heading: {{ $privacyColors['heading'] }};
    --privacy-text: {{ $privacyColors['text'] }};
">
<style>
    /* Premium Design Styles for Privacy Policy Page */
    .privacy-hero {
        background: linear-gradient(135deg, var(--privacy-hero-start) 0%, var(--privacy-hero-end) 100%);
        color: var(--privacy-hero-text);
        padding: 4.5rem 0;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--privacy-accent);
    }

    .privacy-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .privacy-hero-icon {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    }

    .privacy-hero-icon svg {
        width: 30px;
        height: 30px;
        fill: var(--privacy-hero-text);
    }

    .privacy-hero h1 {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
        font-family: 'Public Sans', sans-serif;
    }

    .privacy-hero-meta {
        font-size: 0.9rem;
        opacity: 0.85;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .privacy-hero-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .privacy-hero-meta-badge svg {
        width: 13px;
        height: 13px;
        fill: var(--privacy-accent);
    }

    .privacy-hero .breadcrumb {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .privacy-hero .breadcrumb-item a {
        color: color-mix(in srgb, var(--privacy-hero-text) 80%, transparent);
        transition: color 0.2s ease;
        text-decoration: none;
    }

    .privacy-hero .breadcrumb-item a:hover {
        color: var(--privacy-hero-text);
    }

    .privacy-hero .breadcrumb-item.active {
        color: var(--privacy-accent);
    }

    .privacy-hero .breadcrumb-item + .breadcrumb-item::before {
        color: color-mix(in srgb, var(--privacy-hero-text) 40%, transparent);
    }

    /* Content Area Layout */
    .privacy-wrapper {
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
        padding-bottom: 4rem;
    }

    .privacy-sidebar-card {
        background: var(--privacy-card-background);
        border-radius: 20px;
        border: 1px solid #eaeaea;
        padding: 1.75rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 110px;
        max-height: calc(100vh - 140px);
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .privacy-sidebar-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .privacy-sidebar-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--privacy-heading);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f2f3f8;
    }

    .privacy-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .privacy-nav-item {
        margin-bottom: 0.5rem;
    }

    .privacy-nav-link {
        display: block;
        padding: 0.6rem 1rem;
        color: var(--privacy-text);
        font-weight: 500;
        font-size: 0.95rem;
        border-radius: 10px;
        transition: all 0.25s ease;
        border-left: 3px solid transparent;
        text-decoration: none !important;
    }

    .privacy-nav-link:hover {
        color: var(--privacy-accent);
        background: color-mix(in srgb, var(--privacy-accent) 12%, transparent);
        padding-left: 1.25rem;
    }

    .privacy-nav-link.active {
        color: var(--privacy-accent);
        background: color-mix(in srgb, var(--privacy-accent) 12%, transparent);
        font-weight: 700;
        border-left: 3px solid var(--privacy-accent);
        padding-left: 1.25rem;
    }

    .privacy-content-card {
        background: var(--privacy-card-background);
        border-radius: 20px;
        border: 1px solid #eaeaea;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .privacy-links-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .privacy-link-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-height: 100%;
        color: var(--privacy-text);
        background: var(--privacy-card-background);
        border: 1px solid #eaeaea;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        text-decoration: none !important;
    }

    .privacy-link-card:hover {
        color: var(--privacy-text);
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
    }

    .privacy-link-card-image {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        background: #f5f6f8;
    }

    .privacy-link-card-body {
        padding: 1.5rem;
    }

    .privacy-link-card-title {
        margin-bottom: 0.65rem;
        color: var(--privacy-heading);
        font-size: 1.1rem;
        font-weight: 750;
    }

    .privacy-link-card-description {
        margin: 0;
        color: var(--privacy-text);
        font-size: 0.92rem;
        line-height: 1.65;
    }

    /* Styled Privacy Inner Content */
    .privacy-body {
        font-family: 'Public Sans', sans-serif;
        color: var(--privacy-text);
        font-size: 15.5px;
        line-height: 1.85;
    }

    .privacy-body h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--privacy-heading);
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .privacy-body h2::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 4px;
        background: var(--privacy-accent);
        border-radius: 2px;
    }

    .privacy-body h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--privacy-heading);
        margin-top: 2.75rem;
        margin-bottom: 1.25rem;
        position: relative;
        padding-left: 1rem;
        transition: all 0.3s ease;
        scroll-margin-top: 130px;
    }

    .privacy-body h3::before {
        content: "";
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 4px;
        background: var(--privacy-accent);
        border-radius: 2px;
    }

    .privacy-body p {
        margin-bottom: 1.5rem;
    }

    .privacy-body ul, .privacy-body ol {
        margin-bottom: 1.75rem;
        padding-left: 0;
    }

    .privacy-body ul > li {
        list-style: none;
        position: relative;
        padding-left: 1.75rem;
        margin-bottom: 0.75rem;
    }

    .privacy-body ul > li::before {
        content: "";
        position: absolute;
        left: 6px;
        top: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--privacy-accent);
        opacity: 0.75;
    }

    .privacy-body ol > li {
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
    }

    .privacy-body table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.75rem;
        font-size: 0.92rem;
    }

    .privacy-body table th,
    .privacy-body table td {
        border: 1px solid #eaeaea;
        padding: 0.65rem 0.9rem;
        text-align: left;
    }

    .privacy-body table th {
        background: #f8f9fb;
        color: var(--privacy-heading);
        font-weight: 700;
    }

    .privacy-body a {
        color: var(--privacy-accent);
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px dashed var(--privacy-accent);
        transition: all 0.2s ease;
    }

    .privacy-body a:hover {
        color: var(--privacy-accent);
        border-bottom-style: solid;
    }

    .privacy-body hr {
        border: 0;
        border-top: 1px solid #f2f3f8;
        margin: 2.5rem 0;
    }

    /* Highlight matching search text */
    .privacy-highlight {
        background-color: color-mix(in srgb, var(--privacy-accent) 35%, transparent);
        border-bottom: 2px solid var(--privacy-accent);
        padding: 0 2px;
        border-radius: 2px;
        font-weight: 600;
        color: var(--privacy-heading);
    }

    /* Mobile Quick Navigation selector */
    .privacy-mobile-nav-container {
        background: var(--privacy-card-background);
        border-radius: 16px;
        border: 1px solid #eaeaea;
        padding: 1.25rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }

    .privacy-mobile-nav-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--privacy-text);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Search bar styles */
    .privacy-search-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .privacy-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 12px;
        border: 1px solid #dfdfe6;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: var(--privacy-text);
        outline: none;
    }

    .privacy-search-input:focus {
        border-color: var(--privacy-accent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--privacy-accent) 12%, transparent);
    }

    .privacy-search-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        fill: #8f97ab;
        width: 16px;
        height: 16px;
        pointer-events: none;
        transition: fill 0.3s ease;
    }

    .privacy-search-input:focus + .privacy-search-icon {
        fill: var(--privacy-accent);
    }

    /* Scrollbar style */
    .privacy-sidebar-card::-webkit-scrollbar {
        width: 5px;
    }
    .privacy-sidebar-card::-webkit-scrollbar-track {
        background: #f8f9fa;
    }
    .privacy-sidebar-card::-webkit-scrollbar-thumb {
        background: #ced4da;
        border-radius: 4px;
    }
    .privacy-sidebar-card::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }

    /* Back to top button */
    .privacy-back-to-top {
        position: fixed;
        right: 1.5rem;
        bottom: 1.5rem;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--privacy-accent);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        border: none;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.25s ease;
        z-index: 999;
        cursor: pointer;
    }

    .privacy-back-to-top.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .privacy-back-to-top svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }

    @media print {
        .privacy-hero, .privacy-sidebar-card, .privacy-mobile-nav-container, .privacy-back-to-top, .privacy-links-grid {
            display: none !important;
        }
        .privacy-content-card {
            box-shadow: none;
            border: none;
            padding: 0;
        }
    }

    @media (max-width: 991px) {
        .privacy-hero {
            padding: 3rem 0;
            text-align: center;
        }
        .privacy-hero-icon {
            margin-left: auto;
            margin-right: auto;
        }
        .privacy-hero-meta {
            justify-content: center;
        }
        .privacy-hero .breadcrumb {
            justify-content: center !important;
        }
        .privacy-content-card {
            padding: 2rem;
        }
        .privacy-wrapper {
            margin-top: -1.5rem;
        }
        .privacy-links-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .privacy-links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Banner -->
<section class="privacy-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-left">
                <div class="privacy-hero-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                </div>
                <h1>{{ $page->getTranslation('title') }}</h1>
                <div class="privacy-hero-meta">
                    <span class="privacy-hero-meta-badge">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
                        {{ translate('Last updated') }}: {{ $page->updated_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ translate('Privacy Policy') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Main Outline Content -->
<div class="container privacy-wrapper">
    <!-- Mobile outline dropdown (Visible only on mobile/tablet) -->
    <div class="d-lg-none privacy-mobile-nav-container">
        <label class="privacy-mobile-nav-label" for="privacy-mobile-nav">{{ translate('Jump to Section') }}</label>
        <select id="privacy-mobile-nav" class="form-control select2" data-minimum-results-for-search="Infinity">
            <!-- Dynamically populated by Javascript -->
        </select>
    </div>

    <div class="row">
        <!-- Sidebar Navigation Outline (Desktop only) -->
        <div class="col-lg-3 d-none d-lg-block" id="privacy-sidebar-col">
            <div class="privacy-sidebar-card">
                <!-- Search bar inside sidebar -->
                <div class="privacy-search-wrapper">
                    <input type="text" id="privacy-search" class="privacy-search-input" placeholder="{{ translate('Search policy...') }}" autocomplete="off">
                    <svg class="privacy-search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>
                <div class="privacy-sidebar-title">{{ translate('Navigation') }}</div>
                <ul class="privacy-nav-list" id="privacy-sidebar-list">
                    <!-- Dynamically populated by Javascript -->
                </ul>
            </div>
        </div>

        <!-- Privacy Policy Body Card -->
        <div class="col-lg-9 col-12" id="privacy-content-col">
            <div class="privacy-content-card">
                <div class="privacy-body" id="privacy-body">
                    @php
                        echo $page->getTranslation('content');
                    @endphp
                </div>
            </div>
        </div>
    </div>

    @php
        $privacyCards = json_decode(get_setting('privacy_page_cards', '[]', App::getLocale()), true);
        $privacyCards = is_array($privacyCards) ? $privacyCards : [];
    @endphp
    @if(count($privacyCards) > 0)
        <div class="privacy-links-grid">
            @foreach($privacyCards as $card)
                @php $cardTag = !empty($card['link']) ? 'a' : 'div'; @endphp
                <{{ $cardTag }} class="privacy-link-card"
                    @if(!empty($card['link'])) href="{{ $card['link'] }}" @endif>
                    @if(!empty($card['image']))
                        <img class="privacy-link-card-image" src="{{ uploaded_asset($card['image']) }}"
                            alt="{{ $card['title'] ?? translate('Privacy information') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    @endif
                    <div class="privacy-link-card-body">
                        @if(!empty($card['title']))
                            <h3 class="privacy-link-card-title">{{ $card['title'] }}</h3>
                        @endif
                        @if(!empty($card['description']))
                            <p class="privacy-link-card-description">{{ $card['description'] }}</p>
                        @endif
                    </div>
                </{{ $cardTag }}>
            @endforeach
        </div>
    @endif
</div>

<!-- Back to top -->
<button type="button" id="privacy-back-to-top" class="privacy-back-to-top" aria-label="{{ translate('Back to top') }}">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8-8 8z"/></svg>
</button>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var $content = $('.privacy-body');
        var $sidebarList = $('#privacy-sidebar-list');
        var $mobileSelect = $('#privacy-mobile-nav');

        // 1. Scan h3 headings to build Table of Contents dynamically
        var headings = $content.find('h3');

        if (headings.length === 0) {
            // Fallback: search for h2 or other headings if no h3 is found
            headings = $content.find('h2');
        }

        if (headings.length === 0) {
            // If still no headings, hide the sidebar col and occupy full width
            $('#privacy-sidebar-col').hide();
            $('#privacy-content-col').removeClass('col-lg-9').addClass('col-lg-12');
            $('.privacy-mobile-nav-container').hide();
        } else {
            headings.each(function(index, el) {
                var $el = $(el);
                var titleText = $el.text().replace(/^\d+\.\s*/, '').trim(); // Remove leading numbers (e.g. "1. ") for TOC readability

                // Assign clean, unique ID if not present
                var id = $el.attr('id');
                if (!id) {
                    id = 'section-' + (index + 1);
                    $el.attr('id', id);
                }

                // Create sidebar link
                var sidebarItem = $('<li class="privacy-nav-item"></li>');
                var sidebarLink = $('<a class="privacy-nav-link" href="#' + id + '">' + titleText + '</a>');
                if (index === 0) {
                    sidebarLink.addClass('active');
                }
                sidebarItem.append(sidebarLink);
                $sidebarList.append(sidebarItem);

                // Create mobile dropdown selector option
                var mobileOption = $('<option value="#' + id + '">' + titleText + '</option>');
                $mobileSelect.append(mobileOption);
            });

            // Handle select2 dropdown init or styling if present
            if ($.fn.select2) {
                $mobileSelect.select2({
                    minimumResultsForSearch: Infinity
                });
            }
        }

        // 2. Smooth scrolling on clicking navigation links
        $(document).on('click', '.privacy-nav-link', function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            var $target = $(targetId);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 120
                }, 400);

                // Update active class
                $('.privacy-nav-link').removeClass('active');
                $(this).addClass('active');

                // Update URL hash without causing a page jump
                if (history.pushState) {
                    history.pushState(null, null, targetId);
                } else {
                    location.hash = targetId;
                }
            }
        });

        // 3. Navigation handling for mobile select dropdown
        $mobileSelect.on('change', function() {
            var targetId = $(this).val();
            var $target = $(targetId);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 120
                }, 400);
            }
        });

        // 4. ScrollSpy: Update active link as page is scrolled
        $(window).on('scroll', function() {
            var scrollPos = $(window).scrollTop() + 150; // offset for the sticky menu

            headings.each(function() {
                var $el = $(this);
                var top = $el.offset().top;

                if (scrollPos >= top) {
                    var id = $el.attr('id');
                    $('.privacy-nav-link').removeClass('active');
                    var $activeLink = $('.privacy-nav-link[href="#' + id + '"]');
                    $activeLink.addClass('active');

                    // Update mobile dropdown select value silently
                    if ($mobileSelect.val() !== '#' + id) {
                        $mobileSelect.val('#' + id);
                        if ($.fn.select2) {
                            $mobileSelect.trigger('change.select2');
                        }
                    }
                }
            });

            // Set first section active if we are at the top
            if ($(window).scrollTop() < 100 && headings.length) {
                $('.privacy-nav-link').removeClass('active');
                $('.privacy-nav-link').first().addClass('active');

                var firstId = headings.first().attr('id');
                if ($mobileSelect.val() !== '#' + firstId) {
                    $mobileSelect.val('#' + firstId);
                    if ($.fn.select2) {
                        $mobileSelect.trigger('change.select2');
                    }
                }
            }

            // Back to top visibility
            $('#privacy-back-to-top').toggleClass('show', $(window).scrollTop() > 400);
        });

        // 5. Client-side Real-time Search and Highlight
        $('#privacy-search').on('input', function() {
            var query = $(this).val().toLowerCase().trim();

            // Remove previous highlights
            removeHighlights($content);

            if (query.length < 2) {
                return;
            }

            highlightText($content, query);
        });

        function removeHighlights($container) {
            $container.find('.privacy-highlight').each(function() {
                var $this = $(this);
                $this.replaceWith($this.html());
            });
        }

        function highlightText($container, query) {
            var elements = $container.find('p, li, h3, h2');

            elements.each(function() {
                var $el = $(this);
                var textNodes = getTextNodesIn($el[0]);

                textNodes.forEach(function(node) {
                    var text = node.nodeValue;
                    var index = text.toLowerCase().indexOf(query);

                    if (index >= 0) {
                        var span = document.createElement('span');
                        span.className = 'privacy-highlight';

                        var matchedText = text.substr(index, query.length);
                        var remainingText = text.substr(index + query.length);

                        node.nodeValue = text.substr(0, index);

                        span.appendChild(document.createTextNode(matchedText));
                        node.parentNode.insertBefore(span, node.nextSibling);

                        var afterNode = document.createTextNode(remainingText);
                        node.parentNode.insertBefore(afterNode, span.nextSibling);
                    }
                });
            });
        }

        function getTextNodesIn(el) {
            var textNodes = [];

            function getTextNodes(node) {
                if (node.nodeType === 3) {
                    if (node.nodeValue && !/^\s*$/.test(node.nodeValue)) {
                        textNodes.push(node);
                    }
                } else if (node.nodeName !== 'SCRIPT' && node.nodeName !== 'STYLE') {
                    for (var i = 0, len = node.childNodes.length; i < len; ++i) {
                        getTextNodes(node.childNodes[i]);
                    }
                }
            }

            getTextNodes(el);
            return textNodes;
        }

        // 6. Back to top button click
        $('#privacy-back-to-top').on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 400);
        });
    });
</script>
@endsection
