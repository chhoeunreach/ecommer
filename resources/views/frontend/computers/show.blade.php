@extends('frontend.layouts.app')

@php
    $c_meta_title = $computer->meta_title ?: $computer->name;
    $c_meta_description = $computer->meta_description ? $computer->meta_description : \Illuminate\Support\Str::limit(strip_tags($computer->description ?? ''), 160);
    $c_meta_image = $computer->meta_img ? uploaded_asset($computer->meta_img) : uploaded_asset($computer->thumbnail_img);
@endphp

@section('meta_title'){{ $c_meta_title }}@stop
@section('meta_description'){{ $c_meta_description }}@stop
@section('meta_keywords'){{ $computer->tags }}@stop
@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $c_meta_title }}">
    <meta itemprop="description" content="{{ $c_meta_description }}">
    <meta itemprop="image" content="{{ $c_meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:title" content="{{ $c_meta_title }}">
    <meta name="twitter:description" content="{{ $c_meta_description }}">
    <meta name="twitter:image" content="{{ $c_meta_image }}">
    <meta name="twitter:data1" content="{{ single_price($computer->price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $c_meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('computers.show', $computer->id) }}" />
    <meta property="og:image" content="{{ $c_meta_image }}" />
    <meta property="og:description" content="{{ $c_meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('website_name') ?: config('app.name') }}" />
    <meta property="og:price:amount" content="{{ single_price($computer->price) }}" />
    <meta property="product:brand" content="{{ $computer->brand ? $computer->brand->name : config('app.name') }}">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($computer->price, 2) }}">
@endsection

@section('content')
@php
    $computer_variants = $computer->computer_variants;
    $facetDefs = ['Storage' => 'storage', 'Display' => 'display', 'RAM' => 'ram', 'CPU' => 'cpu', 'Chip' => 'chip'];
    $facets = [];

    if ($computer_variants->isNotEmpty()) {
        $defaultVariant = $computer_variants->first();
        $rawPrice = $defaultVariant->price > 0 ? $defaultVariant->price : $computer->price;
        $stockQty = $computer_variants->sum('stock');
        $defaultSku = $computer->sku ?? 'N/A';

        foreach ($facetDefs as $label => $field) {
            $values = $computer_variants->pluck($field)->filter()->unique()->values();
            if ($values->isNotEmpty()) {
                $facets[$field] = ['label' => $label, 'values' => $values];
            }
        }

        $colorList = $computer_variants->pluck('color')->filter()->unique()->values();
        if ($colorList->isEmpty()) {
            $colorCodes = collect(json_decode($computer->colors ?? '[]', true) ?: []);
            $colorList = $colorCodes->map(fn($code) => get_single_color_name($code));
        }
    } else {
        $defaultStock = $computer->stocks->first();
        $defaultVariant = null;
        $rawPrice = ($defaultStock && $defaultStock->price > 0) ? $defaultStock->price : $computer->price;
        $stockQty = $defaultStock->qty ?? $computer->stock;
        $defaultSku = $defaultStock->sku ?? ($computer->sku ?? 'N/A');

        foreach ($facetDefs as $label => $field) {
            $values = $computer->stocks->pluck($field)->filter()->unique()->values();
            if ($values->isNotEmpty()) {
                $facets[$field] = ['label' => $label, 'values' => $values];
            }
        }
        $colorCodes = collect(json_decode($computer->colors ?? '[]', true) ?: []);
        $colorList = $colorCodes->map(fn($code) => get_single_color_name($code));
    }

    $discounted_price = \App\Utility\CartUtility::discount_calculation($computer, $rawPrice);
@endphp

<section class="py-2 py-sm-3 py-lg-4" style="background: #f8fafc; min-height: 80vh;">
    <div class="container px-2 px-sm-3 px-md-4">
        
        <!-- Breadcrumb Bar -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0 fs-12 fs-md-13 fw-600 align-items-center flex-nowrap overflow-hidden">
                <li class="breadcrumb-item flex-shrink-0"><a href="{{ route('home') }}" class="text-muted text-hover-primary"><i class="las la-home mr-1"></i>{{ translate('Home') }}</a></li>
                <li class="breadcrumb-item flex-shrink-0"><a href="{{ route('computers.index') }}" class="text-muted text-hover-primary">{{ translate('Computers') }}</a></li>
                <li class="breadcrumb-item active text-dark fw-700 text-truncate" aria-current="page">{{ $computer->name }}</li>
            </ol>
        </nav>

        <!-- Main Product Card -->
        <div class="card border-0 shadow-sm rounded-3 rounded-md-4 overflow-hidden mb-4" style="border: 1px solid #e2e8f0;">
            <div class="card-body p-3 p-sm-4 p-lg-5 bg-white">
                <div class="row gutters-15 gutters-md-25">
                    
                    <!-- Left: Gallery & Main Image -->
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        @php
                            $gallery_ids = [];
                            if ($computer->gallery != null) {
                                $gallery_ids = explode(',', $computer->gallery);
                                $gallery_ids = array_filter(array_map('trim', $gallery_ids));
                            }
                            $all_images = array_merge([$computer->thumbnail_img], $gallery_ids);
                            $all_images = array_filter($all_images);
                        @endphp

                        <div class="position-relative overflow-hidden rounded-3 border bg-white p-2 p-sm-3 mb-3 d-flex align-items-center justify-content-center" style="border-color: #f1f5f9 !important; min-height: 240px; border-radius: 14px;">
                            @if($rawPrice != $discounted_price)
                                <div class="position-absolute top-0 left-0 m-2 z-1">
                                    <span class="badge badge-danger px-2.5 py-1 fs-11 fw-700 rounded-pill shadow-sm">
                                        <i class="las la-tag mr-1"></i>{{ translate('SALE') }}
                                    </span>
                                </div>
                            @endif

                            <div class="position-absolute top-0 right-0 m-2 z-1">
                                <button type="button" class="btn btn-icon btn-light rounded-circle shadow-sm border" style="width: 36px; height: 36px;" data-toggle="modal" data-target="#shareModal" title="{{ translate('Share') }}">
                                    <i class="las la-share-alt fs-16 text-dark"></i>
                                </button>
                            </div>

                            @if (count($all_images) > 0)
                                <img src="{{ uploaded_asset($all_images[0]) }}" alt="{{ $computer->name }}" class="img-fluid object-fit-contain transition-all duration-300" id="computer-main-img" style="max-height: 240px; width: auto;">
                            @else
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $computer->name }}" class="img-fluid object-fit-contain" id="computer-main-img" style="max-height: 240px; width: auto;">
                            @endif
                        </div>

                        @if (count($all_images) > 1)
                            <div class="d-flex flex-nowrap overflow-auto pb-1" style="gap: 8px; -webkit-overflow-scrolling: touch;">
                                @foreach ($all_images as $key => $img_id)
                                    <div class="computer-thumb-box flex-shrink-0 {{ $key == 0 ? 'active' : '' }}" onclick="showComputerImage({{ $loop->index }})" style="width: 54px; height: 54px; border-radius: 10px; cursor: pointer; border: 2px solid {{ $key == 0 ? '#4f46e5' : '#e2e8f0' }}; overflow: hidden; transition: all 0.2s ease;">
                                        <img src="{{ uploaded_asset($img_id) }}" alt="{{ $computer->name }}" class="w-100 h-100 object-fit-cover">
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="computer-all-images" value="{{ json_encode(array_map(function($id) { return uploaded_asset($id); }, array_values($all_images))) }}">
                        @endif

                        <!-- Key Spec Quick Cards -->
                   

                    </div>

                    <!-- Right: Product Details & Options -->
                    <div class="col-lg-6">
                        <div class="pl-lg-3">
                            
                            <!-- Brand & Status Badges -->
                            <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 6px;">
                                @if($computer->brand)
                                    <span class="badge badge-inline bg-soft-primary text-primary px-2.5 py-1 fs-11 fw-700 rounded-pill">
                                        <i class="las la-laptop mr-1"></i>{{ $computer->brand->getTranslation('name') }}
                                    </span>
                                @endif

                                @if($stockQty > 0)
                                    <span class="badge badge-inline bg-soft-success text-success px-2.5 py-1 fs-11 fw-700 rounded-pill d-inline-flex align-items-center">
                                        <span class="size-6px rounded-circle bg-success mr-1" style="width:6px; height:6px; display:inline-block;"></span>
                                        {{ translate('In stock') }} ({{ $stockQty }})
                                    </span>
                                @else
                                    <span class="badge badge-inline bg-soft-danger text-danger px-2.5 py-1 fs-11 fw-700 rounded-pill">
                                        {{ translate('Out of Stock') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h1 class="fs-18 fs-sm-22 fs-md-26 fw-800 text-dark mb-2.5" style="color: #0f172a; line-height: 1.3;">{{ $computer->name }}</h1>

                            <!-- Price Card Banner -->
                            <div class="p-2.5 p-sm-3 bg-light rounded-3 mb-3 border" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-color: #e2e8f0 !important; border-left: 4px solid #4f46e5 !important;">
                                <span class="fs-10 text-uppercase fw-700 text-muted d-block mb-0.5 tracking-wider">{{ translate('Price') }}</span>
                                <div class="d-flex align-items-baseline justify-content-between flex-wrap" style="gap: 8px;">
                                    <div class="d-flex align-items-baseline" style="gap: 8px;">
                                        <span class="fs-22 fs-sm-26 fs-md-28 fw-900 text-primary" id="chosen_price">{{ single_price($discounted_price) }}</span>
                                        @if ($rawPrice != $discounted_price)
                                            <del class="fs-14 fs-sm-16 text-muted fw-600" id="original_price">{{ single_price($rawPrice) }}</del>
                                        @endif
                                    </div>

                                    @if ($rawPrice != $discounted_price)
                                        @php
                                            $discount_percent = 0;
                                            if($computer->discount_type == 'percent') {
                                                $discount_percent = $computer->discount;
                                            } elseif($computer->discount_type == 'amount' && $rawPrice > 0) {
                                                $discount_percent = round(($computer->discount / $rawPrice) * 100);
                                            }
                                        @endphp
                                        <span class="badge bg-danger text-white px-2 py-1 fs-11 fw-800 rounded-pill">
                                            <i class="las la-bolt mr-0.5"></i>SAVE {{ $discount_percent }}%
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <form id="option-choice-form" class="product-details-page">
                                @csrf
                                <input type="hidden" name="id" value="{{ $computer->id }}">
                                <input type="hidden" name="product_type" value="computer">

                                <!-- Configurator Options Section -->
                                @if ($colorList->isNotEmpty() || count($facets) > 0)
                                    <div class="computer-configurator-box p-2.5 p-sm-3 rounded-3 mb-3 border" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 12px;">
                                        
                                        <!-- Colors -->
                                        @if ($colorList->isNotEmpty())
                                            <div class="mb-2.5">
                                                <label class="fs-11 fw-700 text-uppercase tracking-wider text-dark d-block mb-1.5">
                                                    {{ translate('Select Color') }}:
                                                </label>
                                                <div class="d-flex flex-wrap" style="gap: 6px;">
                                                    @foreach ($colorList as $key => $colorName)
                                                        @php
                                                            $colorModel = \App\Models\Color::where('name', $colorName)->first();
                                                            $colorHex = $colorModel ? $colorModel->code : '#3b82f6';
                                                        @endphp
                                                        <label class="variant-option-pill cursor-pointer mb-0">
                                                            <input type="radio" name="color" value="{{ $colorName }}" @if($key == 0) checked @endif onchange="getVariantPrice(this)" class="d-none">
                                                            <div class="option-pill-content d-flex align-items-center">
                                                                <span class="size-12px rounded-circle border mr-1.5 flex-shrink-0" style="background-color: {{ $colorHex }}; width: 12px; height: 12px;"></span>
                                                                <span>{{ $colorName }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Facet Options (Storage, Display, RAM, CPU, Chip) -->
                                        @foreach ($facets as $field => $facet)
                                            <div class="mb-2.5">
                                                <label class="fs-11 fw-700 text-uppercase tracking-wider text-dark d-block mb-1.5">
                                                    {{ translate($facet['label']) }}:
                                                </label>
                                                <div class="d-flex flex-wrap" style="gap: 6px;">
                                                    @foreach ($facet['values'] as $key => $value)
                                                        <label class="variant-option-pill cursor-pointer mb-0">
                                                            <input type="radio" name="{{ $field }}" value="{{ $value }}" @if($key == 0) checked @endif onchange="getVariantPrice(this)" class="d-none">
                                                            <div class="option-pill-content">
                                                                <span>{{ $value }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- SKU & Live Availability Info -->
                                        <div class="fs-11 text-muted fw-600 border-top pt-2 mt-2 d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
                                            <div class="d-flex align-items-center">
                                                <i class="las la-barcode mr-1 fs-14 text-primary"></i>
                                                <span>{{ translate('SKU') }}: </span>
                                                <strong id="variant_sku" class="text-dark ml-1">{{ $defaultSku }}</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="las la-check-circle mr-1 fs-14 text-success"></i>
                                                <strong id="available-quantity" class="text-dark mr-1">{{ $stockQty }}</strong>
                                                <span>{{ translate('items available') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                @endif

                                <!-- Quantity & Action Buttons Box -->
                                <div class="p-3 rounded-3 border bg-light-50 mb-3" style="background: #f8fafc; border-color: #e2e8f0 !important; border-radius: 12px;">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <label class="fs-13 fw-700 text-dark mb-0">{{ translate('Quantity') }}:</label>
                                        <div class="product-quantity d-flex align-items-center bg-white rounded-3 border shadow-xs overflow-hidden" style="width: 110px; height: 36px; border-color: #cbd5e1 !important;">
                                            <button class="btn btn-sm btn-link text-dark font-weight-bold px-2 py-0 h-100" type="button" data-type="minus" data-field="quantity" disabled style="width: 32px;">
                                                <i class="las la-minus fs-11"></i>
                                            </button>
                                            <input type="text" name="quantity" class="form-control input-number border-0 text-center fs-13 fw-700 text-dark px-1 h-100" placeholder="1" value="1" min="1" max="{{ $stockQty > 0 ? $stockQty : 1 }}">
                                            <button class="btn btn-sm btn-link text-dark font-weight-bold px-2 py-0 h-100" type="button" data-type="plus" data-field="quantity" style="width: 32px;">
                                                <i class="las la-plus fs-11"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if ($stockQty >= 1)
                                        <div class="product-action-buttons d-flex flex-column" style="gap: 8px;">
                                            <!-- Buy Now Button -->
                                            <button type="button" onclick="buyNow()" class="btn btn-primary btn-block py-2.5 fs-14 fw-700 shadow-primary rounded-3 buy-now d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none; border-radius: 10px; height: 44px;">
                                                <i class="las la-bolt fs-18 mr-2"></i>
                                                <span>{{ translate('Buy Now') }}</span>
                                            </button>

                                            <!-- Add to Cart Button -->
                                            <button type="button" onclick="addToCart()" class="btn btn-soft-primary btn-block py-2.5 fs-14 fw-700 rounded-3 add-to-cart d-flex align-items-center justify-content-center" style="background: #e0e7ff; color: #4f46e5; border: 1px solid #c7d2fe; border-radius: 10px; height: 44px;">
                                                <i class="las la-shopping-bag fs-18 mr-2"></i>
                                                <span>{{ translate('Add to Cart') }}</span>
                                            </button>
                                            
                                            <!-- Contact Sales Button -->
                                            <a href="https://t.me/" target="_blank" class="btn btn-outline-secondary btn-block py-2.5 fs-14 fw-700 rounded-3 d-flex align-items-center justify-content-center" style="border: 1.5px solid #cbd5e1; color: #334155; background: #ffffff; border-radius: 10px; height: 44px;">
                                                <i class="lab la-telegram-plane fs-18 mr-2 text-info"></i>
                                                <span>{{ translate('Contact Sales') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    <button type="button" class="btn btn-secondary w-100 py-2.5 font-weight-bold rounded-3 out-of-stock {{ $stockQty >= 1 ? 'd-none' : '' }}" disabled style="border-radius: 10px;">{{ translate('Out of Stock') }}</button>
                                </div>

                            </form>

                            <!-- Warranty & Guarantee Banner -->
                            @if($computer->has_warranty && $computer->warranty)
                                <div class="p-3 rounded-3 border d-flex align-items-center mt-3" style="background: #f0f9ff; border-color: #bae6fd !important; border-radius: 12px;">
                                    <div class="d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 38px; height: 38px; background: #0284c7; color: #ffffff; border-radius: 10px;">
                                        <i class="las la-shield-alt fs-20"></i>
                                    </div>
                                    <div>
                                        <h6 class="fs-12 fw-700 text-dark mb-0">{{ translate('Official Warranty Included') }}</h6>
                                        <span class="fs-11 text-muted fw-600">{{ $computer->warranty->getTranslation('text') }}</span>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Specifications & Description Tabs Card -->
        <div class="card border-0 shadow-sm rounded-3 rounded-md-4 overflow-hidden mb-5" style="border-radius: 16px; border: 1px solid #e2e8f0;">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs border-0 px-2 px-sm-4 pt-2 computer-nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-700 fs-12 fs-sm-14 py-2.5 py-sm-3 px-3 px-sm-4 border-0" id="description-tab" data-toggle="tab" href="#tab-description" role="tab" aria-selected="true">
                            <i class="las la-file-alt mr-1"></i>{{ translate('Overview') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-700 fs-12 fs-sm-14 py-2.5 py-sm-3 px-3 px-sm-4 border-0" id="specs-tab" data-toggle="tab" href="#tab-specs" role="tab" aria-selected="false">
                            <i class="las la-list-alt mr-1"></i>{{ translate('Specifications') }}
                        </a>
                    </li>
                    @if($computer->has_warranty && $computer->warranty)
                        <li class="nav-item">
                            <a class="nav-link fw-700 fs-12 fs-sm-14 py-2.5 py-sm-3 px-3 px-sm-4 border-0" id="warranty-tab" data-toggle="tab" href="#tab-warranty" role="tab" aria-selected="false">
                                <i class="las la-shield-alt mr-1"></i>{{ translate('Warranty Policy') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="card-body p-3 p-sm-4 p-lg-5 bg-white">
                <div class="tab-content">
                    
                    <!-- Tab 1: Description -->
                    <div class="tab-pane fade show active" id="tab-description" role="tabpanel">
                        @if($computer->description)
                            <div class="mw-100 overflow-hidden text-left aiz-editor-data fs-13 fs-sm-14 lh-1-7 text-secondary">
                                {!! $computer->description !!}
                            </div>
                        @else
                            <div class="py-2">
                                <h6 class="fs-14 fw-700 text-dark mb-2">{{ translate('Product Summary') }}</h6>
                                <p class="fs-13 text-secondary lh-1-7 mb-0">
                                    {{ translate('Experience high performance with') }} <strong>{{ $computer->name }}</strong>. 
                                    @if($defaultVariant)
                                        {{ translate('Powered by') }} <strong>{{ $defaultVariant->chip }} ({{ $defaultVariant->cpu }})</strong>, 
                                        {{ translate('equipped with') }} <strong>{{ $defaultVariant->ram }}</strong> {{ translate('RAM and') }} 
                                        <strong>{{ $defaultVariant->storage }}</strong> {{ translate('SSD storage.') }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Tab 2: Specifications -->
                    <div class="tab-pane fade" id="tab-specs" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped rounded-3 overflow-hidden mb-0">
                                <tbody>
                                    <tr>
                                        <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('Computer Model') }}</th>
                                        <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ $computer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('SKU Code') }}</th>
                                        <td class="fs-12 fs-sm-13 fw-700 text-primary py-2.5">{{ $computer->sku ?? 'N/A' }}</td>
                                    </tr>
                                    @if($computer->brand)
                                        <tr>
                                            <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('Brand Manufacturer') }}</th>
                                            <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ $computer->brand->getTranslation('name') }}</td>
                                        </tr>
                                    @endif
                                    @if($defaultVariant)
                                        <tr>
                                            <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('Processor / Chip') }}</th>
                                            <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ $defaultVariant->chip }} ({{ $defaultVariant->cpu }})</td>
                                        </tr>
                                        <tr>
                                            <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('Storage Options') }}</th>
                                            <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ isset($facets['storage']) ? $facets['storage']['values']->implode(', ') : $defaultVariant->storage }}</td>
                                        </tr>
                                        <tr>
                                            <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('RAM Memory') }}</th>
                                            <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ isset($facets['ram']) ? $facets['ram']['values']->implode(', ') : $defaultVariant->ram }}</td>
                                        </tr>
                                        <tr>
                                            <th class="w-35 bg-light fs-12 fs-sm-13 fw-700 text-dark py-2.5">{{ translate('Display Display') }}</th>
                                            <td class="fs-12 fs-sm-13 fw-600 text-dark py-2.5">{{ $defaultVariant->display }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Warranty Policy -->
                    @if($computer->has_warranty && $computer->warranty)
                        <div class="tab-pane fade" id="tab-warranty" role="tabpanel">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="las la-shield-alt fs-20 text-success mr-2"></i>
                                    <h5 class="fs-14 fw-700 text-dark mb-0">{{ translate('Warranty Protection Plan') }}</h5>
                                </div>
                                <p class="fs-13 fw-600 text-dark mb-0">{{ $computer->warranty->getTranslation('text') }}</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</section>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-dashed py-3 px-4">
                <h5 class="modal-title fs-16 fw-700 text-dark">{{ translate('Share this Computer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center mb-4" style="gap: 12px;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-icon rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; background: #1877f2;">
                        <i class="lab la-facebook-f fs-20"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ $computer->name }}&url={{ url()->current() }}" target="_blank" class="btn btn-icon rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; background: #1da1f2;">
                        <i class="lab la-twitter fs-20"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ $computer->name }} {{ url()->current() }}" target="_blank" class="btn btn-icon rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; background: #25d366;">
                        <i class="lab la-whatsapp fs-20"></i>
                    </a>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control fw-600 text-dark" value="{{ url()->current() }}" readonly id="shareLinkInput">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary fw-700 px-3" onclick="copyShareLink()">
                            <i class="las la-copy mr-1"></i>{{ translate('Copy') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Mobile responsive option pills */
    .variant-option-pill {
        cursor: pointer;
        margin-bottom: 0;
        user-select: none;
    }
    .variant-option-pill input[type="radio"] {
        display: none !important;
    }
    .option-pill-content {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        transition: all 0.2s ease;
    }
    @media (min-width: 576px) {
        .option-pill-content {
            padding: 7px 16px;
            font-size: 13px;
        }
    }
    .variant-option-pill:hover .option-pill-content {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .variant-option-pill input[type="radio"]:checked + .option-pill-content {
        border-color: #4f46e5 !important;
        background-color: #4f46e5 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    .variant-option-pill input[type="radio"]:checked + .option-pill-content span {
        color: #ffffff !important;
    }
    
    /* Navigation Tabs Styling */
    .computer-nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    .computer-nav-tabs::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    .computer-nav-tabs .nav-item {
        flex-shrink: 0;
    }
    .computer-nav-tabs .nav-link {
        color: #64748b;
        border-bottom: 3px solid transparent !important;
        border-radius: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .computer-nav-tabs .nav-link:hover {
        color: #4f46e5;
    }
    .computer-nav-tabs .nav-link.active {
        color: #4f46e5 !important;
        border-bottom: 3px solid #4f46e5 !important;
        background: transparent !important;
    }
    
    /* Thumbnail Active State */
    .computer-thumb-box:hover {
        border-color: #818cf8 !important;
    }
    .computer-thumb-box.active {
        border-color: #4f46e5 !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }
</style>
@endsection

@section('script')
<script>
    var computerImages = [];

    function showComputerImage(index) {
        if (computerImages.length === 0 && $('#computer-all-images').length) {
            computerImages = JSON.parse($('#computer-all-images').val());
        }
        if (computerImages.length > index) {
            $('#computer-main-img').attr('src', computerImages[index]);
            $('.computer-thumb-box').removeClass('active').css('border-color', '#e2e8f0');
            $('.computer-thumb-box').eq(index).addClass('active').css('border-color', '#4f46e5');
        }
    }

    function copyShareLink() {
        var copyText = document.getElementById("shareLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        AIZ.plugins.notify('success', '{{ translate("Link copied to clipboard!") }}');
    }

    window.getVariantPrice = function (triggerEl) {
        var $form = $('#option-choice-form');
        var data = $form.serializeArray();
        if (triggerEl) {
            data.push({ name: 'changed_field', value: triggerEl.name });
        }

        $.ajax({
            type: 'POST',
            url: '{{ route('computers.variant_price') }}',
            data: data,
            success: function (res) {
                $('#chosen_price').html(res.price);
                $('#variant_sku').html(res.sku);
                $('#available-quantity').html(res.quantity);

                if (res.image) {
                    $('#computer-main-img').attr('src', res.image);
                }

                ['color', 'storage', 'display', 'ram', 'cpu', 'chip'].forEach(function (field) {
                    if (res[field]) {
                        $form.find('input[name="' + field + '"][value="' + res[field] + '"]').prop('checked', true);
                    }
                });

                var $qty = $form.find('input[name="quantity"]');
                $qty.attr('max', res.max_limit);
                if (parseInt($qty.val()) > res.max_limit && res.max_limit > 0) {
                    $qty.val(res.max_limit);
                }

                if (res.in_stock == 0) {
                    $form.find('.buy-now, .add-to-cart').addClass('d-none');
                    $form.find('.out-of-stock').removeClass('d-none');
                } else {
                    $form.find('.buy-now, .add-to-cart').removeClass('d-none');
                    $form.find('.out-of-stock').addClass('d-none');
                }
            }
        });
    };
</script>
@endsection
