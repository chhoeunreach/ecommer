@extends('backend.layouts.app')

@section('content')


    <div class="row">
        <div class="col-lg-8 mx-auto">

            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('General Settings') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <!-- Website Base Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Website Base Color')}}</label>

                            <input type="hidden" name="types[]" value="base_color">
                            <input type="text" name="base_color" class="form-control" placeholder="#377dff"
                                value="{{ get_setting('base_color') }}">
                            <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <!-- Website Base Hover Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Website Base Hover Color')}}</label>

                            <input type="hidden" name="types[]" value="base_hov_color">
                            <input type="text" name="base_hov_color" class="form-control" placeholder="#377dff"
                                value="{{  get_setting('base_hov_color') }}">
                            <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <!-- Website Secondary Base Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Website Secondary Base Color')}}</label>

                            <input type="hidden" name="types[]" value="secondary_base_color">
                            <input type="text" name="secondary_base_color" class="form-control" placeholder="#ffc519"
                                value="{{ get_setting('secondary_base_color') }}">
                            <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <!-- Website Secondary Base Hover Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Website Secondary Base Hover Color')}}</label>

                            <input type="hidden" name="types[]" value="secondary_base_hov_color">
                            <input type="text" name="secondary_base_hov_color" class="form-control" placeholder="#dbaa17"
                                value="{{  get_setting('secondary_base_hov_color') }}">
                            <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Details Layout -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h6 class="fw-600 mb-1">{{ translate('Product Details Layout') }}</h6>
                        <p class="fs-12 text-muted mb-0">{{ translate('Choose the storefront design used on every product details page.') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $productDetailsLayout = get_setting('product_details_layout', 'v1');
                        $productDetailsLayout = in_array($productDetailsLayout, ['v1', 'v2'], true) ? $productDetailsLayout : 'v1';
                    @endphp
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="types[]" value="product_details_layout">
                        <div class="row gutters-12">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="d-block h-100 mb-0 cursor-pointer">
                                    <input type="radio" name="product_details_layout" value="v1" class="d-none"
                                        {{ $productDetailsLayout === 'v1' ? 'checked' : '' }}>
                                    <span class="d-block h-100 border rounded-2 p-4 has-transition product-layout-option">
                                        <span class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="badge badge-inline badge-soft-secondary">{{ translate('Version 1') }}</span>
                                            <i class="las la-check-circle fs-24 layout-selected-icon"></i>
                                        </span>
                                        <strong class="d-block fs-16 text-dark mb-1">{{ translate('Classic Product Details') }}</strong>
                                        <span class="d-block fs-12 text-muted">{{ translate('The current product page design and layout.') }}</span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="d-block h-100 mb-0 cursor-pointer">
                                    <input type="radio" name="product_details_layout" value="v2" class="d-none"
                                        {{ $productDetailsLayout === 'v2' ? 'checked' : '' }}>
                                    <span class="d-block h-100 border rounded-2 p-4 has-transition product-layout-option">
                                        <span class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="badge badge-inline badge-soft-primary">{{ translate('Version 2') }}</span>
                                            <i class="las la-check-circle fs-24 layout-selected-icon"></i>
                                        </span>
                                        <strong class="d-block fs-16 text-dark mb-1">{{ translate('Modern Product Details') }}</strong>
                                        <span class="d-block fs-12 text-muted">{{ translate('A spacious card layout optimized for desktop and mobile shopping.') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">
                                {{ translate('Save Layout') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image Watermark -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Image Watermark') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Use Image Watermark (During Upload) -->
                        <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="hidden" name="types[]" value="use_image_watermark">
                                    <input type="checkbox" name="use_image_watermark"
                                         @if(get_setting('use_image_watermark') == 'on') checked @endif>
                                    <span></span>
                                </label>
                                <span class="d-block" style="margin-top: -6px">{{translate('Use Image Watermark (During Upload)')}}</span>
                            </div>
                        </div>
                        <!-- Watermark Type -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Watermark Type') }}</label>

                            <input type="hidden" name="types[]" value="image_watermark_type">
                            <select name="image_watermark_type" class="form-control aiz-selectpicker">
                                <option value="image" @if (get_setting('image_watermark_type') == "image") selected @endif>
                                    {{ translate('Image') }}</option>
                                <option value="text" @if (get_setting('image_watermark_type') == "text") selected @endif>
                                    {{ translate('Text') }}</option>
                            </select>
                        </div>
                        <!-- Watermark Image -->
                        <div class="form-group mb-4 @if (get_setting('image_watermark_type') == " text") d-none @endif"
                            id="watermark_image">
                            <label class="col-from-label">{{ translate('Watermark Image') }}</label>

                            <div class="input-group " data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="watermark_image">
                                <input type="hidden" name="watermark_image" value="{{ get_setting('watermark_image') }}"
                                    class="selected-files">
                            </div>
                            <div class="file-preview box"></div>
                            <small class="text-muted">{{ translate('Do not use "svg" image.') }}</small>
                        </div>
                        <div class="@if (in_array(get_setting('image_watermark_type'), [" image", null])) d-none @endif"
                            id="watermark_text">
                            <!-- Watermark Text -->
                            <div class="form-group mb-4">
                                <label class="col-from-label">{{translate('Watermark Text')}}</label>

                                <input type="hidden" name="types[]" value="watermark_text">
                                <input type="text" name="watermark_text" class="form-control" placeholder="Watermark Text"
                                    value="{{  get_setting('watermark_text') }}">
                            </div>
                            <!-- Watermark Text Size -->
                            <div class="form-group mb-4">
                                <label class="col-from-label">{{translate('Watermark Text Size')}}</label>

                                <input type="hidden" name="types[]" value="watermark_text_size">
                                <input type="number" name="watermark_text_size" class="form-control" placeholder="Ex: 20"
                                    value="{{  get_setting('watermark_text_size') }}">
                            </div>
                            <!-- Watermark Text Color -->
                            <div class="form-group mb-4">
                                <label class="col-from-label">{{translate('Watermark Text Color')}}</label>

                                <div class="input-group">
                                    <input type="hidden" name="types[]" value="watermark_text_color">
                                    <input type="text" class="form-control aiz-color-input" placeholder="Ex: #e1e1e1"
                                        name="watermark_text_color" value="{{ get_setting('watermark_text_color') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text p-0">
                                            <input class="aiz-color-picker border-0 size-40px" type="color"
                                                value="{{ get_setting('watermark_text_color') }}">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Watermark Position -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Watermark Position')}}</label>

                            <input type="hidden" name="types[]" value="watermark_position">
                            <select name="watermark_position" class="form-control aiz-selectpicker"
                                data-selected="{{ get_setting('watermark_position') }}">
                                <option value="top-left">{{ translate('Top-Left') }}</option>
                                <option value="top-right">{{ translate('Top-Right') }}</option>
                                <option value="bottom-left">{{ translate('Bottom-Left') }}</option>
                                <option value="bottom-right">{{ translate('Bottom-Right') }}</option>
                                <option value="center">{{ translate('Center') }}</option>
                            </select>
                        </div>
                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <style>
        .product-layout-option { border-width: 2px !important; }
        .product-layout-option .layout-selected-icon { color: #c5c8d0; }
        input[name="product_details_layout"]:checked + .product-layout-option {
            border-color: var(--primary) !important;
            background: var(--soft-primary);
            box-shadow: 0 8px 22px rgba(0, 0, 0, .06);
        }
        input[name="product_details_layout"]:checked + .product-layout-option .layout-selected-icon {
            color: var(--primary);
        }
    </style>
    <script type="text/javascript">
        $('select[name="image_watermark_type"]').on('change', function () {
            let val = $(this).val();
            if (val == 'image') {
                $('#watermark_image').removeClass('d-none');
                $('#watermark_text').addClass('d-none');
            } else {
                $('#watermark_text').removeClass('d-none');
                $('#watermark_image').addClass('d-none');
            }
        });
    </script>
@endsection
