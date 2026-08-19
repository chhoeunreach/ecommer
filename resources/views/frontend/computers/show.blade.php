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
    $facetDefs = ['Storage' => 'storage', 'Display' => 'display', 'RAM' => 'ram', 'CPU' => 'cpu', 'Chip' => 'chip'];
    $facets = [];
    foreach ($facetDefs as $label => $field) {
        $values = $computer->stocks->pluck($field)->filter()->unique()->values();
        if ($values->isNotEmpty()) {
            $facets[$field] = ['label' => $label, 'values' => $values];
        }
    }
    $colorCodes = collect(json_decode($computer->colors ?? '[]', true) ?: []);
    $defaultStock = $computer->stocks->first();
@endphp
<section class="mb-4 pt-4">
    <div class="container">
        <div class="bg-white shadow-sm rounded p-4">

            <!-- Header Section (Breadcrumbs & Action Buttons) -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-0 fs-13 text-gray">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-reset">{{ translate('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('computers.index') }}" class="text-reset">{{ translate('Computers') }}</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">{{ $computer->name }}</li>
                    </ol>
                </nav>

                <!-- Action Buttons (Share) -->
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <button type="button" class="btn btn-outline-light text-secondary rounded-pill px-3 py-1 fs-12 fw-500 d-flex align-items-center border" style="border-color: #e2e8f0 !important;" data-toggle="modal" data-target="#shareModal">
                        <i class="las la-share mr-1 fs-16"></i>
                        {{ translate('Share') }}
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Image -->
                <div class="col-xl-5 col-lg-6 mb-4">
                    @php
                        $gallery_ids = [];
                        if ($computer->gallery != null) {
                            $gallery_ids = explode(',', $computer->gallery);
                            $gallery_ids = array_filter(array_map('trim', $gallery_ids));
                        }
                        $all_images = array_merge([$computer->thumbnail_img], $gallery_ids);
                        $all_images = array_filter($all_images);
                    @endphp
                    <div class="position-relative overflow-hidden rounded img-aspect-ratio-1-1">
                        @if (count($all_images) > 0)
                            <img src="{{ uploaded_asset($all_images[0]) }}" alt="{{ $computer->name }}" class="w-100 h-100 object-fit-cover" id="computer-main-img">
                        @else
                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $computer->name }}" class="w-100 h-100 object-fit-cover" id="computer-main-img">
                        @endif
                    </div>
                    @if (count($all_images) > 1)
                        <div class="d-flex flex-wrap mt-3" style="gap: 8px;">
                            @foreach ($all_images as $key => $img_id)
                                <div class="border rounded overflow-hidden {{ $key == 0 ? 'border-primary' : '' }}" style="width: 60px; height: 60px; cursor: pointer;" onclick="showComputerImage({{ $loop->index }})">
                                    <img src="{{ uploaded_asset($img_id) }}" alt="{{ $computer->name }}" class="w-100 h-100 object-fit-cover">
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="computer-all-images" value="{{ json_encode(array_map(function($id) { return uploaded_asset($id); }, array_values($all_images))) }}">
                    @endif
                </div>

                <!-- Details -->
                <div class="col-xl-7 col-lg-6">
                    <div class="text-left">
                        <h1 class="mb-2 fs-20 fw-700 text-dark">{{ $computer->name }}</h1>

                        <div class="d-flex align-items-center mb-3">
                            @if($computer->brand)
                                <span class="fs-13 fw-400 text-gray mr-2">{{ translate('Brand') }}:</span>
                                <span class="badge badge-inline badge-md bg-soft-primary text-primary">{{ $computer->brand->getTranslation('name') }}</span>
                            @endif
                        </div>

                        <form id="option-choice-form" class="product-details-page">
                            @csrf
                            <input type="hidden" name="id" value="{{ $computer->id }}">
                            <input type="hidden" name="product_type" value="computer">

                            <!-- Price -->
                            <div class="mb-4">
                                @php
                                    $price = $defaultStock->price ?? $computer->price;
                                    $discounted_price = \App\Utility\CartUtility::discount_calculation($computer, $price);
                                @endphp
                                <div class="d-flex align-items-baseline">
                                    <span class="fs-24 fw-700 text-primary" id="chosen_price">{{ single_price($discounted_price) }}</span>
                                    @if ($price != $discounted_price)
                                        <del class="fs-16 text-gray ml-2">{{ single_price($price) }}</del>
                                        @php
                                            $discount_percent = 0;
                                            if($computer->discount_type == 'percent') {
                                                $discount_percent = $computer->discount;
                                            } elseif($computer->discount_type == 'amount' && $price > 0) {
                                                $discount_percent = round(($computer->discount / $price) * 100);
                                            }
                                        @endphp
                                        <span class="badge badge-inline badge-danger ml-2">-{{ $discount_percent }}%</span>
                                    @endif
                                </div>
                            </div>

                            @if ($colorCodes->isNotEmpty() || count($facets) > 0)
                                <div class="border-dashed border-1 border-soft-light rounded-2 p-3 mb-3">
                                    <!-- Colors -->
                                    @if ($colorCodes->isNotEmpty())
                                        <div class="mb-3">
                                            <label class="fs-13 fw-600 text-dark d-block mb-2">{{ translate('Color') }}</label>
                                            <div class="d-flex flex-wrap" style="gap: 8px;">
                                                @foreach ($colorCodes as $key => $colorCode)
                                                    @php $colorName = get_single_color_name($colorCode); @endphp
                                                    <label class="aiz-megabox rounded-1 bg-white cursor-pointer mb-0">
                                                        <input type="radio" name="color" value="{{ $colorName }}" @if($key == 0) checked @endif>
                                                        <div class="d-flex align-items-center aiz-megabox-elem px-15px py-2">
                                                            <span class="w-15px h-15px rounded-circle border" style="background-color: {{ $colorCode }};"></span>
                                                            <span class="fs-13 fw-400 text-dark pl-2">{{ $colorName }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Storage / Display / RAM / CPU / Chip -->
                                    @foreach ($facets as $field => $facet)
                                        <div class="mb-3">
                                            <label class="fs-13 fw-600 text-dark d-block mb-2">{{ translate($facet['label']) }}</label>
                                            <div class="d-flex flex-wrap" style="gap: 8px;">
                                                @foreach ($facet['values'] as $key => $value)
                                                    <label class="aiz-megabox rounded-1 bg-white cursor-pointer mb-0">
                                                        <input type="radio" name="{{ $field }}" value="{{ $value }}" @if($key == 0) checked @endif>
                                                        <div class="d-flex align-items-center aiz-megabox-elem px-15px py-2">
                                                            <span class="fs-13 fw-400 text-dark">{{ $value }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="fs-12 text-gray">
                                        {{ translate('SKU') }}: <span id="variant_sku">{{ $defaultStock->sku ?? 'N/A' }}</span>
                                        &nbsp;|&nbsp;
                                        <span id="available-quantity">{{ $defaultStock->qty ?? 0 }}</span> {{ translate('in stock') }}
                                    </div>
                                </div>
                            @endif

                            <div class="border-dashed border-1 border-soft-light rounded-2 overflow-hidden mb-3 px-20px pt-15px pb-20px purchase-panel">
                                <div class="d-flex pb-10px flex-wrap align-items-center justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between justify-content-md-start w-100 w-md-auto mb-3 mb-md-0">
                                        <div class="mr-2 mr-md-4 text-dark fs-14 fw-bold">{{ translate('Quantity') }}:</div>
                                        <div class="product-quantity d-flex align-items-center bg-white rounded-1 overflow-hidden ml-auto ml-md-0 border border-gray">
                                            <button class="btn btn-icon btn-sm btn-light border-0 rounded-0" type="button" data-type="minus" data-field="quantity" disabled>
                                                <i class="las la-minus"></i>
                                            </button>
                                            <input type="text" name="quantity" class="form-control input-number border-0 text-center fs-14 fw-600 w-50px px-1 py-1 h-auto" placeholder="1" value="1" min="1" max="{{ $defaultStock->qty ?? 1 }}">
                                            <button class="btn btn-icon btn-sm btn-light border-0 rounded-0" type="button" data-type="plus" data-field="quantity">
                                                <i class="las la-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-soft-light my-20px">

                                <div class="d-flex flex-wrap product-action-buttons">
                                    @if (($defaultStock->qty ?? 0) >= 1)
                                        @include('frontend.product_details.partials.action_buttons', ['buttonPadding' => 'py-2'])
                                    @endif
                                    <button type="button" class="btn btn-secondary w-100 out-of-stock d-none" disabled>{{ translate('Out of Stock') }}</button>
                                </div>
                            </div>
                        </form>

                        <div class="border-top mt-4 mb-4"></div>

                        <!-- Description -->
                        <div class="mt-4">
                            <h3 class="fs-16 fw-700 text-dark mb-3">{{ translate('Description') }}</h3>
                            <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                {!! $computer->description !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Share this computer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Social Sharing Links -->
                <div class="d-flex justify-content-center" style="gap: 10px;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-icon btn-facebook rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: #3b5998;">
                        <i class="lab la-facebook-f fs-20"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ $computer->name }}&url={{ url()->current() }}" target="_blank" class="btn btn-icon btn-twitter rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: #1da1f2;">
                        <i class="lab la-twitter fs-20"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ $computer->name }} {{ url()->current() }}" target="_blank" class="btn btn-icon btn-whatsapp rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: #25d366;">
                        <i class="lab la-whatsapp fs-20"></i>
                    </a>
                </div>
                <div class="mt-4">
                    <input type="text" class="form-control" value="{{ url()->current() }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

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
            $('#computer-all-images').parent().find('.border').removeClass('border-primary');
            $('#computer-all-images').parent().find('.border').eq(index).addClass('border-primary');
        }
    }

    // Override the shared getVariantPrice (app.blade.php) — it posts to the
    // Product-only endpoint. Computers have their own facets (storage,
    // display, ram, cpu, chip) and their own price-lookup endpoint.
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
