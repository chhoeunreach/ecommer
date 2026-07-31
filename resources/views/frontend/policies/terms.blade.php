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
    $termsColorDefaults = [
        'hero_start' => $baseColor,
        'hero_end' => '#111723',
        'hero_text' => '#ffffff',
        'accent' => '#ffc519',
        'card_background' => '#ffffff',
        'heading' => '#111723',
        'text' => '#4e5561',
    ];
    $storedTermsColors = json_decode(get_setting('terms_page_colors', '{}'), true);
    $storedTermsColors = is_array($storedTermsColors) ? $storedTermsColors : [];
    $termsColors = [];
    foreach ($termsColorDefaults as $colorKey => $defaultColor) {
        $colorValue = $storedTermsColors[$colorKey] ?? $defaultColor;
        $termsColors[$colorKey] = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $colorValue)
            ? $colorValue
            : $defaultColor;
    }
@endphp
<div class="terms-page-theme" style="
    --terms-hero-start: {{ $termsColors['hero_start'] }};
    --terms-hero-end: {{ $termsColors['hero_end'] }};
    --terms-hero-text: {{ $termsColors['hero_text'] }};
    --terms-accent: {{ $termsColors['accent'] }};
    --terms-card-background: {{ $termsColors['card_background'] }};
    --terms-heading: {{ $termsColors['heading'] }};
    --terms-text: {{ $termsColors['text'] }};
">
<style>
    /* Premium Design Styles for Terms Page */
    .terms-hero {
        background: linear-gradient(135deg, var(--terms-hero-start) 0%, var(--terms-hero-end) 100%);
        color: var(--terms-hero-text);
        padding: 4.5rem 0;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--terms-accent);
    }
    
    .terms-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .terms-hero-icon {
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

    .terms-hero-icon svg {
        width: 30px;
        height: 30px;
        fill: var(--terms-hero-text);
    }

    .terms-hero h1 {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
        font-family: 'Public Sans', sans-serif;
    }

    .terms-hero-meta {
        font-size: 0.9rem;
        opacity: 0.85;
        font-weight: 500;
    }

    .terms-hero .breadcrumb {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .terms-hero .breadcrumb-item a {
        color: color-mix(in srgb, var(--terms-hero-text) 80%, transparent);
        transition: color 0.2s ease;
        text-decoration: none;
    }

    .terms-hero .breadcrumb-item a:hover {
        color: var(--terms-hero-text);
    }

    .terms-hero .breadcrumb-item.active {
        color: var(--terms-accent);
    }

    .terms-hero .breadcrumb-item + .breadcrumb-item::before {
        color: color-mix(in srgb, var(--terms-hero-text) 40%, transparent);
    }

    /* Content Area Layout */
    .terms-wrapper {
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
        padding-bottom: 4rem;
    }

    .terms-sidebar-card {
        background: var(--terms-card-background);
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

    .terms-sidebar-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .terms-sidebar-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--terms-heading);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f2f3f8;
    }

    .terms-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .terms-nav-item {
        margin-bottom: 0.5rem;
    }

    .terms-nav-link {
        display: block;
        padding: 0.6rem 1rem;
        color: var(--terms-text);
        font-weight: 500;
        font-size: 0.95rem;
        border-radius: 10px;
        transition: all 0.25s ease;
        border-left: 3px solid transparent;
        text-decoration: none !important;
    }

    .terms-nav-link:hover {
        color: var(--terms-accent);
        background: color-mix(in srgb, var(--terms-accent) 12%, transparent);
        padding-left: 1.25rem;
    }

    .terms-nav-link.active {
        color: var(--terms-accent);
        background: color-mix(in srgb, var(--terms-accent) 12%, transparent);
        font-weight: 700;
        border-left: 3px solid var(--terms-accent);
        padding-left: 1.25rem;
    }

    .terms-content-card {
        background: var(--terms-card-background);
        border-radius: 20px;
        border: 1px solid #eaeaea;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .terms-links-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .terms-link-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-height: 100%;
        color: var(--terms-text);
        background: var(--terms-card-background);
        border: 1px solid #eaeaea;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        text-decoration: none !important;
    }

    .terms-link-card:hover {
        color: var(--terms-text);
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
    }

    .terms-link-card-image {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        background: #f5f6f8;
    }

    .terms-link-card-body {
        padding: 1.5rem;
    }

    .terms-link-card-title {
        margin-bottom: 0.65rem;
        color: var(--terms-heading);
        font-size: 1.1rem;
        font-weight: 750;
    }

    .terms-link-card-description {
        margin: 0;
        color: var(--terms-text);
        font-size: 0.92rem;
        line-height: 1.65;
    }

    /* Styled Terms Inner Content */
    .terms-body {
        font-family: 'Public Sans', sans-serif;
        color: var(--terms-text);
        font-size: 15.5px;
        line-height: 1.85;
    }

    .terms-body h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--terms-heading);
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .terms-body h2::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 4px;
        background: var(--terms-accent);
        border-radius: 2px;
    }

    .terms-body h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--terms-heading);
        margin-top: 2.75rem;
        margin-bottom: 1.25rem;
        position: relative;
        padding-left: 1rem;
        transition: all 0.3s ease;
        scroll-margin-top: 130px;
    }

    .terms-body h3::before {
        content: "";
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 4px;
        background: var(--terms-accent);
        border-radius: 2px;
    }

    .terms-body p {
        margin-bottom: 1.5rem;
    }

    .terms-body ul, .terms-body ol {
        margin-bottom: 1.75rem;
        padding-left: 0;
    }

    .terms-body ul > li {
        list-style: none;
        position: relative;
        padding-left: 1.75rem;
        margin-bottom: 0.75rem;
    }

    .terms-body ul > li::before {
        content: "";
        position: absolute;
        left: 6px;
        top: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--terms-accent);
        opacity: 0.75;
    }

    .terms-body ol > li {
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
    }

    .terms-body a {
        color: var(--terms-accent);
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px dashed var(--terms-accent);
        transition: all 0.2s ease;
    }

    .terms-body a:hover {
        color: var(--terms-accent);
        border-bottom-style: solid;
    }

    .terms-body hr {
        border: 0;
        border-top: 1px solid #f2f3f8;
        margin: 2.5rem 0;
    }

    /* Highlight matching search text */
    .terms-highlight {
        background-color: color-mix(in srgb, var(--terms-accent) 35%, transparent);
        border-bottom: 2px solid var(--terms-accent);
        padding: 0 2px;
        border-radius: 2px;
        font-weight: 600;
        color: var(--terms-heading);
    }

    /* Mobile Quick Navigation selector */
    .terms-mobile-nav-container {
        background: var(--terms-card-background);
        border-radius: 16px;
        border: 1px solid #eaeaea;
        padding: 1.25rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }

    .terms-mobile-nav-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--terms-text);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Search bar styles */
    .terms-search-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .terms-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 12px;
        border: 1px solid #dfdfe6;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: var(--terms-text);
        outline: none;
    }

    .terms-search-input:focus {
        border-color: var(--terms-accent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--terms-accent) 12%, transparent);
    }

    .terms-search-icon {
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

    .terms-search-input:focus + .terms-search-icon {
        fill: var(--terms-accent);
    }

    /* Scrollbar style */
    .terms-sidebar-card::-webkit-scrollbar {
        width: 5px;
    }
    .terms-sidebar-card::-webkit-scrollbar-track {
        background: #f8f9fa;
    }
    .terms-sidebar-card::-webkit-scrollbar-thumb {
        background: #ced4da;
        border-radius: 4px;
    }
    .terms-sidebar-card::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }

    @media (max-width: 991px) {
        .terms-hero {
            padding: 3rem 0;
            text-align: center;
        }
        .terms-hero-icon {
            margin-left: auto;
            margin-right: auto;
        }
        .terms-hero .breadcrumb {
            justify-content: center !important;
        }
        .terms-content-card {
            padding: 2rem;
        }
        .terms-wrapper {
            margin-top: -1.5rem;
        }
        .terms-links-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .terms-links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Banner -->
<section class="terms-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-left">
                <div class="terms-hero-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                </div>
                <h1>{{ $page->getTranslation('title') }}</h1>
                <div class="terms-hero-meta">
                    <span>{{ translate('Last updated') }}: {{ $page->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ translate('Terms & conditions') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Main Outline Content -->
<div class="container terms-wrapper">
    <!-- Mobile outline dropdown (Visible only on mobile/tablet) -->
    <div class="d-lg-none terms-mobile-nav-container">
        <label class="terms-mobile-nav-label" for="terms-mobile-nav">{{ translate('Jump to Section') }}</label>
        <select id="terms-mobile-nav" class="form-control select2" data-minimum-results-for-search="Infinity">
            <!-- Dynamically populated by Javascript -->
        </select>
    </div>

    <div class="row">
        <!-- Sidebar Navigation Outline (Desktop only) -->
        <div class="col-lg-3 d-none d-lg-block" id="terms-sidebar-col">
            <div class="terms-sidebar-card">
                <!-- Search bar inside sidebar -->
                <div class="terms-search-wrapper">
                    <input type="text" id="terms-search" class="terms-search-input" placeholder="{{ translate('Search terms...') }}" autocomplete="off">
                    <svg class="terms-search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>
                <div class="terms-sidebar-title">{{ translate('Navigation') }}</div>
                <ul class="terms-nav-list" id="terms-sidebar-list">
                    <!-- Dynamically populated by Javascript -->
                </ul>
            </div>
        </div>

        <!-- Terms & Conditions Body Card -->
        <div class="col-lg-9 col-12" id="terms-content-col">
            <div class="terms-content-card">
                <div class="terms-body">
                    @php
                        echo $page->getTranslation('content');
                    @endphp
                </div>
            </div>
        </div>
    </div>

    @php
        $termsCards = json_decode(get_setting('terms_page_cards', '[]', App::getLocale()), true);
        $termsCards = is_array($termsCards) ? $termsCards : [];
    @endphp
    @if(count($termsCards) > 0)
        <div class="terms-links-grid">
            @foreach($termsCards as $card)
                @php $cardTag = !empty($card['link']) ? 'a' : 'div'; @endphp
                <{{ $cardTag }} class="terms-link-card"
                    @if(!empty($card['link'])) href="{{ $card['link'] }}" @endif>
                    @if(!empty($card['image']))
                        <img class="terms-link-card-image" src="{{ uploaded_asset($card['image']) }}"
                            alt="{{ $card['title'] ?? translate('Terms information') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    @endif
                    <div class="terms-link-card-body">
                        @if(!empty($card['title']))
                            <h3 class="terms-link-card-title">{{ $card['title'] }}</h3>
                        @endif
                        @if(!empty($card['description']))
                            <p class="terms-link-card-description">{{ $card['description'] }}</p>
                        @endif
                    </div>
                </{{ $cardTag }}>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var $content = $('.terms-body');
        var $sidebarList = $('#terms-sidebar-list');
        var $mobileSelect = $('#terms-mobile-nav');
        
        // 1. Scan h3 headings to build Table of Contents dynamically
        var headings = $content.find('h3');
        
        if (headings.length === 0) {
            // Fallback: search for h2 or other headings if no h3 is found
            headings = $content.find('h2');
        }
        
        if (headings.length === 0) {
            // If still no headings, hide the sidebar col and occupy full width
            $('#terms-sidebar-col').hide();
            $('#terms-content-col').removeClass('col-lg-9').addClass('col-lg-12');
            return;
        }
        
        headings.each(function(index, el) {
            var $el = $(el);
            var titleText = $el.text().replace(/^\d+\.\s*/, '').trim(); // Remove leading numbers (e.g. "1. ") for TOC readability
            
            // Assign clean, unique ID if not present
            var id = $el.attr('id');
            if (!id) {
                // Slugify the title
                id = 'section-' + (index + 1);
                $el.attr('id', id);
            }
            
            // Create sidebar link
            var sidebarItem = $('<li class="terms-nav-item"></li>');
            var sidebarLink = $('<a class="terms-nav-link" href="#' + id + '">' + titleText + '</a>');
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
        if($.fn.select2) {
            $mobileSelect.select2({
                minimumResultsForSearch: Infinity
            });
        }
        
        // 2. Smooth scrolling on clicking navigation links
        $(document).on('click', '.terms-nav-link', function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            var $target = $(targetId);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 120
                }, 400);
                
                // Update active class
                $('.terms-nav-link').removeClass('active');
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
                    $('.terms-nav-link').removeClass('active');
                    var $activeLink = $('.terms-nav-link[href="#' + id + '"]');
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
            if ($(window).scrollTop() < 100) {
                $('.terms-nav-link').removeClass('active');
                $('.terms-nav-link').first().addClass('active');
                
                var firstId = headings.first().attr('id');
                if ($mobileSelect.val() !== '#' + firstId) {
                    $mobileSelect.val('#' + firstId);
                    if ($.fn.select2) {
                        $mobileSelect.trigger('change.select2');
                    }
                }
            }
        });
        
        // 5. Client-side Real-time Search and Highlight
        $('#terms-search').on('input', function() {
            var query = $(this).val().toLowerCase().trim();
            
            // Remove previous highlights
            removeHighlights($content);
            
            if (query.length < 2) {
                return;
            }
            
            highlightText($content, query);
        });

        function removeHighlights($container) {
            $container.find('.terms-highlight').each(function() {
                var $this = $(this);
                $this.replaceWith($this.html());
            });
        }

        function highlightText($container, query) {
            // Avoid executing search inside tags, comments, or script elements
            // We traverse text nodes directly to avoid corrupting HTML formatting
            var elements = $container.find('p, li, h3, h2');
            
            elements.each(function() {
                var $el = $(this);
                var html = $el.html();
                
                // Skip if element has already highlighted content or tags we shouldn't touch
                // Fast search via text nodes or direct regex if HTML is safe
                // To be safe and prevent breaking inner HTML links/tags:
                // We use standard DOM text nodes replacement
                var textNodes = getTextNodesIn($el[0]);
                
                textNodes.forEach(function(node) {
                    var text = node.nodeValue;
                    var index = text.toLowerCase().indexOf(query);
                    
                    if (index >= 0) {
                        var span = document.createElement('span');
                        span.className = 'terms-highlight';
                        
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
            
            function whitespace(char) {
                return (char === " " || char === "\n" || char === "\r" || char === "\t");
            }
            
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
    });
</script>
@endsection
