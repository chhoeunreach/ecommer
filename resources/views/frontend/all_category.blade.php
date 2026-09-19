@extends('frontend.layouts.app')

@section('content')
@php
    $baseColor = get_setting('base_color', '#1b74e4');
    $baseColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $baseColor) ? $baseColor : '#1b74e4';
@endphp
<div class="all-cat-theme" style="--all-cat-accent: {{ $baseColor }}; background-color: #f5f7fb; min-height: 100vh;">
<style>
    .all-cat-hero {
        background: transparent;
        color: #0f172a;
        padding: 3.5rem 0 2.5rem;
    }

    .all-cat-hero h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: .5rem;
        letter-spacing: -1px;
    }

    .all-cat-hero-sub {
        color: #64748b;
        font-size: 1.05rem;
        margin-bottom: 0;
    }

    .all-cat-hero .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: .9rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    .all-cat-hero .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .all-cat-hero .breadcrumb-item a:hover {
        color: var(--all-cat-accent);
    }

    .all-cat-hero .breadcrumb-item.active {
        color: #0f172a;
    }

    .all-cat-hero .breadcrumb-item+.breadcrumb-item::before {
        color: #cbd5e1;
    }

    .all-cat-wrapper {
        padding-bottom: 5rem;
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
            gap: 1.5rem;
        }
    }

    .cat-tile {
        display: flex;
        flex-direction: column;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.035);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none !important;
        cursor: pointer;
        overflow: hidden;
    }

    .cat-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        border-color: rgba(51, 144, 243, 0.2);
    }

    .cat-tile-media {
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid rgba(0,0,0,0.02);
    }

    .cat-tile-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .cat-tile:hover .cat-tile-media img {
        transform: scale(1.08);
    }

    .cat-tile-info {
        padding: 1.25rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex-grow: 1;
        justify-content: center;
        background: #ffffff;
    }

    .cat-tile-name {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 0.3rem;
        transition: color 0.2s ease;
    }

    .cat-tile:hover .cat-tile-name {
        color: var(--all-cat-accent);
    }

    .cat-tile-count {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .cat-modal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .cat-modal-close {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 5;
        background: #f1f5f9;
        color: #475569;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 1.4rem;
        line-height: 1;
        opacity: 1;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cat-modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: scale(1.05);
    }

    .cat-modal-header {
        position: relative;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 2rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a;
    }

    .cat-modal-image {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        overflow: hidden;
        flex-shrink: 0;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 0.5rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .cat-modal-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .cat-modal-heading h3 {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0 0 .25rem;
        letter-spacing: -0.5px;
    }

    .cat-modal-view-all {
        color: var(--all-cat-accent);
        font-weight: 700;
        font-size: .9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: opacity 0.2s ease;
    }

    .cat-modal-view-all:hover {
        opacity: 0.8;
        color: var(--all-cat-accent);
    }

    .cat-modal-body {
        max-height: 60vh;
        overflow-y: auto;
        padding: 2rem;
        background: #ffffff;
    }

    .cat-modal-group {
        margin-bottom: 1.75rem;
    }

    .cat-modal-group:last-child {
        margin-bottom: 0;
    }

    .cat-modal-group-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: .8rem;
    }

    .cat-modal-group-title a {
        color: #0f172a;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .cat-modal-group-title a:hover {
        color: var(--all-cat-accent);
    }

    .cat-modal-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
    }

    .cat-chip {
        font-size: .85rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid transparent;
        padding: .4rem .9rem;
        border-radius: 99px;
        text-decoration: none !important;
        transition: all .2s ease;
    }

    .cat-chip:hover {
        background: #ffffff;
        color: var(--all-cat-accent);
        border-color: rgba(51, 144, 243, 0.3);
        box-shadow: 0 4px 10px rgba(52, 144, 243, 0.1);
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
        grid-column: 1 / -1;
    }

    @media (max-width: 767px) {
        .all-cat-hero {
            padding: 2.5rem 0 2rem;
            text-align: center;
        }

        .all-cat-hero .breadcrumb {
            justify-content: center !important;
        }
        
        .cat-tiles-grid {
            gap: 1rem;
        }
        
        .cat-tile-media {
            padding: 1rem;
        }
        
        .cat-tile-info {
            padding: 1rem 0.5rem;
        }
        
        .cat-tile-name {
            font-size: 0.95rem;
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
                    <div class="cat-tile-media">
                        <img src="{{ uploaded_asset($category->banner) }}" alt="{{ $category->getTranslation('name') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </div>
                    <div class="cat-tile-info">
                        <div class="cat-tile-name">{{ $category->getTranslation('name') }}</div>
                        @if ($subCount > 0)
                            <div class="cat-tile-count">{{ $subCount }} {{ translate('Categories') }}</div>
                        @endif
                    </div>
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
                <div class="cat-modal-image"><img id="categoryModalImage" src="" alt=""></div>
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
