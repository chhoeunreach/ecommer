@extends('frontend.layouts.app')

@section('content')
@php
    $baseColor = get_setting('base_color', '#1b74e4');
    $baseColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $baseColor) ? $baseColor : '#1b74e4';
    $sortedBrands = $brands->sortBy(fn ($brand) => mb_strtolower($brand->getTranslation('name')))->values();
    $brandInitials = $sortedBrands
        ->map(fn ($brand) => mb_strtoupper(mb_substr(trim($brand->getTranslation('name')), 0, 1)))
        ->filter()
        ->unique()
        ->sort()
        ->values();
@endphp

<div class="minima-brands" style="--brands-primary: {{ $baseColor }};">
    <style>
        .minima-brands {
            min-height: 65vh;
            color: #292933;
            background: #fff;
        }

        .brands-page-head {
            padding: 2.5rem 0 2rem;
            border-bottom: 1px solid #e9e9ee;
            background: #fafafa;
        }

        .brands-page-label {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: .6rem;
            color: var(--brands-primary);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .brands-page-label::before {
            display: inline-block;
            width: 24px;
            height: 2px;
            content: '';
            background: var(--brands-primary);
        }

        .brands-page-head h1 {
            margin: 0;
            color: #292933;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .brands-page-description {
            margin: .5rem 0 0;
            color: #777782;
            font-size: .9rem;
        }

        .brands-page-head .breadcrumb {
            justify-content: flex-end;
            margin: 0;
            padding: 0;
            background: transparent;
            font-size: .8rem;
        }

        .brands-page-head .breadcrumb-item a {
            color: #777782;
        }

        .brands-page-head .breadcrumb-item.active {
            color: #292933;
            font-weight: 600;
        }

        .brands-main {
            padding: 2rem 0 4rem;
        }

        .brands-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .brands-search {
            position: relative;
            width: min(100%, 360px);
        }

        .brands-search i {
            position: absolute;
            top: 50%;
            left: .85rem;
            color: #8d8d96;
            font-size: 1rem;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .brands-search input {
            width: 100%;
            height: 42px;
            padding: 0 2.6rem 0 2.4rem;
            border: 1px solid #dedee5;
            border-radius: 0;
            outline: none;
            color: #292933;
            background: #fff;
            font-size: .85rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .brands-search input:focus {
            border-color: var(--brands-primary);
            box-shadow: inset 3px 0 0 var(--brands-primary);
        }

        .brands-search-clear {
            position: absolute;
            top: 50%;
            right: .45rem;
            display: none;
            width: 30px;
            height: 30px;
            padding: 0;
            border: 0;
            color: #777782;
            background: transparent;
            font-size: 1.1rem;
            line-height: 30px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .brands-search-clear.is-visible { display: block; }

        .brands-result-count {
            margin: 0;
            color: #777782;
            font-size: .8rem;
        }

        .brands-result-count strong {
            color: #292933;
            font-weight: 700;
        }

        .brands-index {
            display: flex;
            gap: .25rem;
            margin-bottom: 1.5rem;
            padding-bottom: .65rem;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .brand-index-button {
            min-width: 32px;
            height: 32px;
            flex: 0 0 auto;
            padding: 0 .55rem;
            border: 0;
            border-bottom: 2px solid transparent;
            color: #777782;
            background: transparent;
            font-size: .76rem;
            font-weight: 700;
            cursor: pointer;
            transition: color .2s ease, border-color .2s ease;
        }

        .brand-index-button:hover,
        .brand-index-button.is-active {
            border-bottom-color: var(--brands-primary);
            color: var(--brands-primary);
        }

        .brands-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            border-top: 1px solid #e5e5ea;
            border-left: 1px solid #e5e5ea;
        }

        .brand-card {
            position: relative;
            display: flex;
            min-width: 0;
            min-height: 170px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            overflow: hidden;
            border-right: 1px solid #e5e5ea;
            border-bottom: 1px solid #e5e5ea;
            color: #292933;
            background: #fff;
            text-decoration: none !important;
            transition: background .25s ease, box-shadow .25s ease;
        }

        .brand-card::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 2px;
            content: '';
            background: var(--brands-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .25s ease;
        }

        .brand-card:hover {
            z-index: 1;
            color: #292933;
            background: #fcfcfd;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
        }

        .brand-card:hover::before { transform: scaleX(1); }

        .brand-logo-box {
            display: flex;
            width: 100%;
            height: 92px;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-box img {
            max-width: 78%;
            max-height: 72px;
            object-fit: contain;
            transition: transform .3s ease;
        }

        .brand-card:hover .brand-logo-box img { transform: scale(1.05); }

        .brand-name {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            margin-top: .65rem;
            overflow: hidden;
            font-size: .82rem;
            font-weight: 700;
            text-align: center;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .brand-name i {
            color: var(--brands-primary);
            font-size: .8rem;
            opacity: 0;
            transform: translateX(-5px);
            transition: opacity .2s ease, transform .2s ease;
        }

        .brand-card:hover .brand-name i {
            opacity: 1;
            transform: translateX(0);
        }

        .brands-js .brand-card {
            opacity: 0;
            transform: translateY(12px);
        }

        .brands-js .brand-card.is-visible {
            opacity: 1;
            transform: translateY(0);
            transition: opacity .4s ease, transform .4s ease, background .25s ease, box-shadow .25s ease;
            transition-delay: var(--reveal-delay, 0ms);
        }

        .brands-empty {
            display: none;
            padding: 3.5rem 1rem;
            border: 1px solid #e5e5ea;
            color: #777782;
            text-align: center;
        }

        .brands-empty.is-visible { display: block; }
        .brands-empty i { display: block; margin-bottom: .7rem; color: #aaaab2; font-size: 2rem; }
        .brands-empty strong { display: block; margin-bottom: .25rem; color: #292933; }

        [dir="rtl"] .brands-search i { right: .85rem; left: auto; }
        [dir="rtl"] .brands-search input { padding-right: 2.4rem; padding-left: 2.6rem; }
        [dir="rtl"] .brands-search-clear { right: auto; left: .45rem; }
        [dir="rtl"] .brand-name i { transform: rotate(180deg) translateX(-5px); }
        [dir="rtl"] .brand-card:hover .brand-name i { transform: rotate(180deg) translateX(0); }

        @media (min-width: 576px) {
            .brands-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (min-width: 768px) {
            .brands-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }

        @media (min-width: 992px) {
            .brands-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
        }

        @media (min-width: 1200px) {
            .brands-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .brands-page-head { padding: 2rem 0 1.5rem; text-align: center; }
            .brands-page-label { justify-content: center; }
            .brands-page-head .breadcrumb { justify-content: center; margin-top: 1rem; }
            .brands-tools { align-items: stretch; flex-direction: column; }
            .brands-search { width: 100%; }
        }

        @media (max-width: 400px) {
            .brand-card { min-height: 145px; padding: .8rem; }
            .brand-logo-box { height: 76px; }
            .brand-logo-box img { max-height: 58px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .brands-js .brand-card { opacity: 1; transform: none; }
            .brand-card,
            .brand-card::before,
            .brand-logo-box img,
            .brand-name i { transition: none !important; }
        }
    </style>

    <header class="brands-page-head">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <span class="brands-page-label">{{ translate('Shop by maker') }}</span>
                    <h1>{{ translate('All Brands') }}</h1>
                    <p class="brands-page-description">{{ translate('Find products from your favorite brands.') }}</p>
                </div>
                <div class="col-md-5">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ translate('All Brands') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </header>

    <main class="brands-main">
        <div class="container">
            <div class="brands-tools">
                <div class="brands-search">
                    <label class="sr-only" for="brandSearch">{{ translate('Search brands') }}</label>
                    <i class="las la-search" aria-hidden="true"></i>
                    <input id="brandSearch" type="search" placeholder="{{ translate('Search brands') }}" autocomplete="off">
                    <button id="clearBrandSearch" class="brands-search-clear" type="button"
                        aria-label="{{ translate('Clear search') }}">&times;</button>
                </div>
                <p class="brands-result-count" aria-live="polite">
                    <strong id="visibleBrandCount">{{ $sortedBrands->count() }}</strong>
                    {{ translate('brands') }}
                </p>
            </div>

            @if ($brandInitials->isNotEmpty())
                <nav class="brands-index" aria-label="{{ translate('Filter brands by letter') }}">
                    <button class="brand-index-button is-active" type="button" data-letter="all">{{ translate('All') }}</button>
                    @foreach ($brandInitials as $initial)
                        <button class="brand-index-button" type="button" data-letter="{{ $initial }}">{{ $initial }}</button>
                    @endforeach
                </nav>
            @endif

            <div class="brands-grid" id="brandsGrid">
                @foreach ($sortedBrands as $brand)
                    @php
                        $brandName = trim($brand->getTranslation('name'));
                        $brandInitial = mb_strtoupper(mb_substr($brandName, 0, 1));
                        $logo = $brand->logo != null
                            ? uploaded_asset($brand->logo)
                            : static_asset('assets/img/placeholder.jpg');
                    @endphp
                    <a class="brand-card" href="{{ route('products.brand', $brand->slug) }}"
                        data-brand-name="{{ mb_strtolower($brandName) }}"
                        data-brand-letter="{{ $brandInitial }}"
                        style="--reveal-delay: {{ min($loop->index, 8) * 35 }}ms;">
                        <span class="brand-logo-box">
                            <img class="lazyload" src="{{ $logo }}" data-src="{{ $logo }}" alt="{{ $brandName }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </span>
                        <span class="brand-name">
                            {{ $brandName }} <i class="las la-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="brands-empty" id="brandsEmpty" role="status">
                <i class="las la-search" aria-hidden="true"></i>
                <strong>{{ translate('No brands found') }}</strong>
                <span>{{ translate('Try a different search or letter.') }}</span>
            </div>
        </div>
    </main>
</div>
@endsection

@section('script')
<script>
    (function () {
        var page = document.querySelector('.minima-brands');
        var search = document.getElementById('brandSearch');
        var clear = document.getElementById('clearBrandSearch');
        var count = document.getElementById('visibleBrandCount');
        var empty = document.getElementById('brandsEmpty');
        var cards = Array.prototype.slice.call(document.querySelectorAll('.brand-card'));
        var indexButtons = Array.prototype.slice.call(document.querySelectorAll('.brand-index-button'));
        var selectedLetter = 'all';

        if (!page || !search) return;

        page.classList.add('brands-js');

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: .1 });

            cards.forEach(function (card) { observer.observe(card); });
        } else {
            cards.forEach(function (card) { card.classList.add('is-visible'); });
        }

        function filterBrands() {
            var query = search.value.trim().toLocaleLowerCase();
            var visible = 0;

            cards.forEach(function (card) {
                var nameMatches = !query || card.dataset.brandName.indexOf(query) !== -1;
                var letterMatches = selectedLetter === 'all' || card.dataset.brandLetter === selectedLetter;
                var show = nameMatches && letterMatches;

                card.style.display = show ? '' : 'none';
                if (show) {
                    visible++;
                    card.classList.add('is-visible');
                }
            });

            count.textContent = visible;
            clear.classList.toggle('is-visible', query.length > 0);
            empty.classList.toggle('is-visible', visible === 0);
        }

        search.addEventListener('input', filterBrands);

        clear.addEventListener('click', function () {
            search.value = '';
            search.focus();
            filterBrands();
        });

        indexButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                selectedLetter = button.dataset.letter;
                indexButtons.forEach(function (item) { item.classList.remove('is-active'); });
                button.classList.add('is-active');
                filterBrands();
            });
        });

        filterBrands();
    })();
</script>
@endsection
