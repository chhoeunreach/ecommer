@extends('frontend.layouts.app')

@section('content')
@php
    $baseColor = get_setting('base_color', '#1b74e4');
    $baseColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $baseColor) ? $baseColor : '#1b74e4';
@endphp
<div class="all-cat-theme" style="--all-cat-accent: {{ $baseColor }};">
<style>
    .all-cat-hero {
        background: linear-gradient(135deg, var(--all-cat-accent) 0%, #111723 100%);
        color: #fff;
        padding: 3.5rem 0 5rem;
    }

    .all-cat-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: .5rem;
        letter-spacing: -0.5px;
    }

    .all-cat-hero-sub {
        opacity: .85;
        font-size: .95rem;
        margin-bottom: 0;
    }

    .all-cat-hero .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: .9rem;
        font-weight: 500;
        margin-bottom: 0;
    }

    .all-cat-hero .breadcrumb-item a {
        color: rgba(255, 255, 255, .8);
        text-decoration: none;
    }

    .all-cat-hero .breadcrumb-item a:hover {
        color: #fff;
    }

    .all-cat-hero .breadcrumb-item.active {
        color: #fff;
        font-weight: 700;
    }

    .all-cat-hero .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, .4);
    }

    .all-cat-wrapper {
        margin-top: -3rem;
        position: relative;
        z-index: 5;
        padding-bottom: 4rem;
    }

    .cat-tiles-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    @media (min-width: 576px) {
        .cat-tiles-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 768px) {
        .cat-tiles-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (min-width: 992px) {
        .cat-tiles-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .cat-tiles-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .cat-tile {
        position: relative;
        display: block;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        background: #f2f3f8;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        transition: transform .25s ease, box-shadow .25s ease;
        text-decoration: none !important;
        cursor: pointer;
    }

    .cat-tile:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, .12);
    }

    .cat-tile-media {
        position: absolute;
        inset: 0;
    }

    .cat-tile-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cat-tile-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, .75) 100%);
    }

    .cat-tile-info {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: .85rem;
        color: #fff;
    }

    .cat-tile-name {
        display: block;
        font-size: .92rem;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: .3rem;
    }

    .cat-tile-count {
        display: inline-block;
        font-size: .7rem;
        font-weight: 600;
        background: rgba(255, 255, 255, .18);
        backdrop-filter: blur(4px);
        padding: .15rem .55rem;
        border-radius: 999px;
    }

    .cat-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .cat-modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 5;
        background: rgba(255, 255, 255, .18);
        color: #fff;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 1.25rem;
        line-height: 1;
        opacity: 1;
    }

    .cat-modal-close:hover {
        background: rgba(255, 255, 255, .3);
        color: #fff;
    }

    .cat-modal-header {
        position: relative;
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        padding: 1.75rem;
        min-height: 150px;
        background: linear-gradient(135deg, var(--all-cat-accent) 0%, #111723 100%);
        color: #fff;
    }

    .cat-modal-image {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        overflow: hidden;
        flex-shrink: 0;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .3);
    }

    .cat-modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cat-modal-heading h3 {
        font-size: 1.35rem;
        font-weight: 800;
        margin: 0 0 .35rem;
    }

    .cat-modal-view-all {
        color: #fff;
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
        border-bottom: 1px dashed rgba(255, 255, 255, .6);
    }

    .cat-modal-view-all:hover {
        color: #fff;
        border-bottom-style: solid;
    }

    .cat-modal-body {
        max-height: 60vh;
        overflow-y: auto;
        padding: 1.75rem;
    }

    .cat-modal-group {
        margin-bottom: 1.5rem;
    }

    .cat-modal-group:last-child {
        margin-bottom: 0;
    }

    .cat-modal-group-title {
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: .6rem;
    }

    .cat-modal-group-title a {
        color: #1b2133;
        text-decoration: none;
    }

    .cat-modal-group-title a:hover {
        color: var(--all-cat-accent);
    }

    .cat-modal-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .cat-chip {
        font-size: .8rem;
        font-weight: 500;
        color: #4e5561;
        background: #f2f3f8;
        padding: .35rem .8rem;
        border-radius: 999px;
        text-decoration: none !important;
        transition: all .2s ease;
    }

    .cat-chip:hover {
        background: var(--all-cat-accent);
        color: #fff;
    }

    .cat-modal-empty {
        color: #8f97ab;
        font-size: .9rem;
        margin: 0;
    }

    .cat-empty {
        text-align: center;
        padding: 3rem 0;
        color: #8f97ab;
    }

    @media (max-width: 575px) {
        .all-cat-hero {
            padding: 2.5rem 0 4rem;
            text-align: center;
        }

        .all-cat-hero .breadcrumb {
            justify-content: center !important;
        }
    }
</style>

<!-- Hero -->
<section class="all-cat-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-left">
                <h1>{{ translate('All Categories') }}</h1>
                <p class="all-cat-hero-sub">{{ translate('Browse everything we sell, organized by category') }}</p>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <ul class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ translate('All Categories') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Category Tiles -->
<section class="all-cat-wrapper">
    <div class="container">
        <div class="cat-tiles-grid">
            @forelse ($categories as $category)
                @php $subCount = $category->childrenCategories->count(); @endphp
                <a href="javascript:void(0)" class="cat-tile" data-toggle="modal" data-target="#categoryModal"
                    data-panel="cat-panel-{{ $category->id }}"
                    data-name="{{ $category->getTranslation('name') }}"
                    data-image="{{ uploaded_asset($category->banner) }}"
                    data-link="{{ route('products.category', $category->slug) }}">
                    <span class="cat-tile-media">
                        <img src="{{ uploaded_asset($category->banner) }}" alt="{{ $category->getTranslation('name') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </span>
                    <span class="cat-tile-overlay"></span>
                    <span class="cat-tile-info">
                        <span class="cat-tile-name">{{ $category->getTranslation('name') }}</span>
                        @if ($subCount > 0)
                            <span class="cat-tile-count">{{ $subCount }} {{ translate('Categories') }}</span>
                        @endif
                    </span>
                </a>
            @empty
                <div class="cat-empty">{{ translate('No categories found') }}</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Hidden subcategory content sources, copied into the modal on tile click -->
<div class="d-none">
    @foreach ($categories as $category)
        <div id="cat-panel-{{ $category->id }}">
            @forelse ($category->childrenCategories as $child_category)
                <div class="cat-modal-group">
                    <h4 class="cat-modal-group-title">
                        <a href="{{ route('products.category', $child_category->slug) }}">
                            {{ $child_category->getTranslation('name') }}
                        </a>
                    </h4>
                    @if ($child_category->childrenCategories->count())
                        <div class="cat-modal-chips">
                            @foreach ($child_category->childrenCategories as $second_level_category)
                                <a class="cat-chip" href="{{ route('products.category', $second_level_category->slug) }}">
                                    {{ $second_level_category->getTranslation('name') }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="cat-modal-empty">{{ translate('No subcategories available') }}</p>
            @endforelse
        </div>
    @endforeach
</div>

<!-- Shared subcategory modal -->
<div class="modal fade cat-modal" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="cat-modal-close" data-dismiss="modal" aria-label="{{ translate('Close') }}">&times;</button>
            <div class="cat-modal-header">
                <span class="cat-modal-image"><img id="categoryModalImage" src="" alt=""></span>
                <div class="cat-modal-heading">
                    <h3 id="categoryModalTitle"></h3>
                    <a id="categoryModalLink" href="#" class="cat-modal-view-all">
                        {{ translate('View All Products') }} <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="modal-body cat-modal-body" id="categoryModalBody"></div>
        </div>
    </div>
</div>
</div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.cat-tile', function () {
            var panelId = $(this).data('panel');
            $('#categoryModalTitle').text($(this).data('name'));
            $('#categoryModalImage').attr('src', $(this).data('image'));
            $('#categoryModalLink').attr('href', $(this).data('link'));
            $('#categoryModalBody').html($('#' + panelId).html());
        });
    </script>
@endsection
