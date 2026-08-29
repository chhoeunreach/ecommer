@extends('backend.layouts.app')

@section('content')
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Create Computer') }}</h1>
                </div>
                <div class="col text-right">
                    <a href="{{ route('admin.computers.index') }}" class="btn btn-link text-muted fw-600">
                        <i class="las la-angle-left"></i> {{ translate('Back to list') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem mb-5">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-xs mb-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="las la-exclamation-circle fs-20 mr-2"></i>
                        <strong class="fs-15">{{ translate('Please correct the errors below:') }}</strong>
                    </div>
                    <ul class="mb-0 pl-4 fs-13">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.computers.store') }}" method="POST" enctype="multipart/form-data" id="computerCreateForm">
                @csrf
                <div class="row gutters-15">
                    <div class="col-xl-8">

                        <!-- Basic Information Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4 d-flex align-items-center">
                                <div class="size-36px bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:36px; height:36px; background:#e0e7ff; color:#4f46e5;">
                                    <i class="las la-laptop fs-20"></i>
                                </div>
                                <div>
                                    <h5 class="fs-16 fw-700 mb-0 text-dark">{{ translate('Basic Information') }}</h5>
                                    <span class="fs-12 text-muted">{{ translate('Enter computer name, SKU, brand, and available color choices.') }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row gutters-10">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Computer Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="{{ translate('e.g. MacBook Air M4 (2026)') }}" name="name" class="form-control form-control-lg fw-600 text-dark" value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('SKU Code') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-700 text-primary" id="sku" name="sku" placeholder="{{ translate('e.g. MBA-M4-2026') }}" value="{{ old('sku') }}" required>
                                                <div class="input-group-append">
                                                    <button type="button" id="generateSKUBtn" class="btn btn-soft-primary fs-13 fw-700 px-3" onclick="generateSKU()">
                                                        <i class="las la-magic mr-1"></i> {{ translate('Generate SKU') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Brand') }}</label>
                                            <select class="form-control aiz-selectpicker" name="brand_id" data-live-search="true">
                                                <option value="">{{ translate('Select Brand') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->getTranslation('name') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Colors (Pick available colors)') }} <span class="text-danger">*</span></label>
                                            <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple onchange="onColorsChanged()">
                                                @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                                    <option value="{{ $color->code }}" data-color-name="{{ $color->name }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>">{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Tags') }}</label>
                                            <input type="text" class="form-control aiz-tag-input" name="tags" value="{{ old('tags') }}" placeholder="{{ translate('Type tag and hit enter') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="variant-input-label d-block">{{ translate('Description') }}</label>
                                    <textarea name="description" rows="4" class="form-control" placeholder="{{ translate('Enter computer details, features, and description...') }}">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Storage & Color Variants Section Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4 d-flex align-items-center justify-content-between flex-wrap" style="gap: 15px;">
                                <div class="d-flex align-items-center">
                                    <div class="size-36px bg-soft-info text-info rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:36px; height:36px; background:#e0f2fe; color:#0284c7;">
                                        <i class="las la-hdd fs-20"></i>
                                    </div>
                                    <div>
                                        <h5 class="fs-16 fw-700 mb-0 text-dark">{{ translate('Storage Variants') }}</h5>
                                        <span class="fs-12 text-muted">{{ translate('Configure storage variants per chosen color (RAM, CPU, Chip, Price, Stock).') }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <label class="variant-input-label mb-0 whitespace-nowrap">{{ translate('Variants Per Color') }}:</label>
                                        <select id="variants_per_color_select" class="form-control custom-select fw-700 text-info bg-light border-info" style="width: 120px;" onchange="onVariantsPerColorChange()">
                                            <option value="1" selected>1 Variant</option>
                                            <option value="2">2 Variants</option>
                                            <option value="3">3 Variants</option>
                                            <option value="4">4 Variants</option>
                                        </select>
                                    </div>

                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <label class="variant-input-label mb-0 whitespace-nowrap">{{ translate('Total') }}:</label>
                                        <select id="variant_count_select" class="form-control custom-select fw-700 text-primary bg-light border-primary" style="width: 80px;" onchange="onVariantCountSelectChange()">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('variants') ? (count(old('variants')) == $i ? 'selected' : '') : ($i == 1 ? 'selected' : '') }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <button type="button" class="btn btn-soft-primary btn-sm fs-13 fw-700 px-3" onclick="addStorageVariant()">
                                        <i class="las la-plus-circle"></i> {{ translate('Add Variant') }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-soft-primary border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center" style="background:#eff6ff; color:#1d4ed8;">
                                    <i class="las la-lightbulb fs-20 mr-2 text-primary"></i>
                                    <span class="fs-13 fw-600"><strong>{{ translate('Step 1:') }}</strong> {{ translate('Select 1 or more Colors in Basic Information.') }} &nbsp;|&nbsp; <strong>{{ translate('Step 2:') }}</strong> {{ translate('Storage Variants generate automatically for each color below.') }}</span>
                                </div>

                                <!-- Dynamic Variant Boxes Container -->
                                <div id="storage_variants_container"></div>
                            </div>
                        </div>

                        <!-- Price, Discount & Warranty Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4 d-flex align-items-center">
                                <div class="size-36px bg-soft-success text-success rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:36px; height:36px; background:#dcfce7; color:#16a34a;">
                                    <i class="las la-tags fs-20"></i>
                                </div>
                                <div>
                                    <h5 class="fs-16 fw-700 mb-0 text-dark">{{ translate('Discount & Warranty') }}</h5>
                                    <span class="fs-12 text-muted">{{ translate('Configure overall promotion discounts and product warranty terms.') }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row gutters-10">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Discount Value') }}</label>
                                            <input type="number" min="0" step="0.01" placeholder="{{ translate('Discount') }}" name="discount" class="form-control fw-600" value="{{ old('discount') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Discount Type') }}</label>
                                            <select class="form-control aiz-selectpicker" name="discount_type">
                                                <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>{{ translate('Flat Amount ($)') }}</option>
                                                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>{{ translate('Percent (%)') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="variant-input-label d-block">{{ translate('Discount Date Range') }}</label>
                                            <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{ translate('Select Date Range') }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" value="{{ old('date_range') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group d-flex align-items-center mb-0 mt-2 p-3 bg-light rounded-3 border">
                                    <label class="aiz-switch aiz-switch-success mb-0 mr-3">
                                        <input type="checkbox" name="has_warranty" id="has_warranty" value="1" onchange="toggleWarranty()" {{ old('has_warranty') ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                    <div>
                                        <span class="fs-14 fw-700 text-dark d-block">{{ translate('Enable Warranty Coverage') }}</span>
                                        <span class="fs-12 text-muted">{{ translate('Check this to attach a warranty plan to this computer.') }}</span>
                                    </div>
                                </div>
                                <div class="form-group mt-3" id="warranty_selection" style="display: {{ old('has_warranty') ? 'block' : 'none' }};">
                                    <label class="variant-input-label d-block">{{ translate('Select Warranty Plan') }}</label>
                                    <select class="form-control aiz-selectpicker" name="warranty_id" data-live-search="true">
                                        <option value="">{{ translate('Select Warranty') }}</option>
                                        @foreach ($warranties as $warranty)
                                            <option value="{{ $warranty->id }}" {{ old('warranty_id') == $warranty->id ? 'selected' : '' }}>{{ $warranty->getTranslation('text') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Sidebar -->
                    <div class="col-xl-4">
                        <!-- Publish Status Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4">
                                <h5 class="fs-15 fw-700 mb-0 text-dark">{{ translate('Publish Status') }}</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border">
                                    <span class="fs-14 fw-600 text-dark">{{ translate('Publish Product') }}</span>
                                    <div class="d-flex align-items-center">
                                        <label class="aiz-switch aiz-switch-blue mb-0 mr-2">
                                            <input value="1" type="checkbox" name="published" checked onchange="updateStatusLabel(this)">
                                            <span></span>
                                        </label>
                                        <span class="fs-13 fw-700 status-label text-success">{{ translate('Active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Photos Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4">
                                <h5 class="fs-15 fw-700 mb-0 text-dark">{{ translate('Computer Images') }}</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="form-group mb-3">
                                    <label class="variant-input-label d-block">{{ translate('Thumbnail Image') }} <span class="text-danger">*</span></label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files" value="{{ old('thumbnail_img') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="variant-input-label d-block">{{ translate('Gallery Images') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="gallery" class="selected-files" value="{{ old('gallery') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Meta Tags Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white border-bottom-dashed py-3 px-4">
                                <h5 class="fs-15 fw-700 mb-0 text-dark">{{ translate('SEO Meta Tags') }}</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="form-group mb-3">
                                    <label class="variant-input-label d-block">{{ translate('Meta Title') }}</label>
                                    <input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}" value="{{ old('meta_title') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="variant-input-label d-block">{{ translate('Meta Description') }}</label>
                                    <textarea name="meta_description" rows="3" class="form-control" placeholder="{{ translate('Meta Description') }}">{{ old('meta_description') }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="variant-input-label d-block">{{ translate('Meta Image') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" class="selected-files" value="{{ old('meta_img') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sticky Form Action Bar -->
                <div class="card p-3 border-0 shadow-lg rounded-3 mt-4 position-sticky bottom-0 bg-white" style="z-index: 99; bottom: 15px; border: 1px solid #cbd5e1 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('admin.computers.index') }}" class="btn btn-light px-4 fs-14 fw-600 text-muted">{{ translate('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-5 fs-14 fw-700 shadow-primary py-2 rounded-2">
                            <i class="las la-check-circle fs-18 mr-1"></i> {{ translate('Save & Create Computer') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Add Custom Value Modal Dialog -->
    <div class="modal fade" id="customValueModal" tabindex="-1" role="dialog" aria-labelledby="customModalTitle" aria-hidden="true" style="z-index: 1090;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title fs-16 fw-700 text-dark" id="customModalTitle">{{ translate('Add Custom Value') }}</h5>
                        <p class="text-muted fs-13 mb-0 mt-1" id="customModalDescription">{{ translate('Enter a custom value for this option.') }}</p>
                    </div>
                    <button type="button" class="close text-secondary p-0 m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fs-20">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mb-0">
                        <label class="variant-input-label text-uppercase text-secondary fw-700 fs-11" id="customModalInputLabel">{{ translate('Custom Value') }}</label>
                        <input type="text" id="customModalInput" class="form-control form-control-lg fs-14 fw-600 text-dark" placeholder="{{ translate('Type new custom value...') }}">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-end" style="gap: 10px;">
                    <button type="button" class="btn btn-light px-4 fs-13 fw-600 rounded-pill" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn btn-primary px-4 fs-13 fw-700 rounded-pill shadow-sm" onclick="applyCustomValueModal()">
                        <i class="las la-check-circle mr-1"></i> {{ translate('Save & Apply') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-group-section {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
            margin-bottom: 24px;
            transition: all 0.25s ease;
            overflow: visible !important;
        }
        .color-group-section:hover {
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
        }
        .color-group-header {
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .variant-card-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
            margin-bottom: 16px;
            overflow: visible !important;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }
        .variant-card-box:hover,
        .variant-card-box:focus-within,
        .variant-card-box.show {
            border-color: #cbd5e1;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
            z-index: 50 !important;
        }
        .variant-card-header {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 10px 16px;
            border-bottom: 1px dashed #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .variant-card-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .variant-card-body {
            padding: 16px;
            overflow: visible !important;
        }
        .variant-badge {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.25);
        }
        .variant-input-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 4px;
        }
        .variant-storage-input {
            color: #4f46e5 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
        }
        .variant-price-input {
            color: #059669 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
        }
        .variant-stock-input {
            color: #2563eb !important;
            font-weight: 700 !important;
            font-size: 14px !important;
        }
        /* Custom Input Group Dropdowns for Storage, Display, RAM, CPU, Chip */
        .variant-card-box .input-group {
            position: relative !important;
        }
        .variant-card-box .input-group-append {
            position: static !important;
        }
        .variant-card-box .btn-dropdown-preset::after,
        .variant-card-box .dropdown-toggle::after {
            display: none !important;
        }
        .variant-card-box .btn-dropdown-preset {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: none;
            color: #64748b;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
        }
        .variant-card-box .btn-dropdown-preset:hover,
        .variant-card-box .btn-dropdown-preset:focus,
        .variant-card-box .show > .btn-dropdown-preset {
            background: #e2e8f0;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .variant-card-box .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: auto !important;
            transform: none !important;
            width: 100% !important;
            min-width: 240px !important;
            max-height: 220px !important;
            overflow-y: auto !important;
            margin-top: 4px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.18) !important;
            padding: 6px !important;
            z-index: 1080 !important;
            background: #ffffff !important;
        }
        .variant-card-box .dropdown-item {
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            transition: all 0.15s ease;
        }
        .variant-card-box .dropdown-item:hover,
        .variant-card-box .dropdown-item:focus {
            background: #eff6ff !important;
            color: #2563eb !important;
            font-weight: 700 !important;
        }
    </style>
@endsection

@section('script')
<script>
    // Preserved form state across dropdown count changes
    var currentVariantsState = [];

    // Pre-fill from old() input if validation error occurs
    @if(old('variants'))
        currentVariantsState = @json(old('variants'));
    @else
        currentVariantsState = [
            { storage: '256GB', display: '13.6-inch Liquid Retina', ram: '16GB', cpu: '10-core', chip: 'Apple M4', color: 'Space Gray', price: '999', stock: '10' }
        ];
    @endif

    var defaultPresets = [
        { storage: '256GB', display: '13.6-inch Liquid Retina', ram: '16GB', cpu: '10-core', chip: 'Apple M4', color: 'Space Gray', price: '999', stock: '10' },
        { storage: '512GB', display: '13.6-inch Liquid Retina', ram: '24GB', cpu: '10-core', chip: 'Apple M4', color: 'Silver', price: '1199', stock: '10' },
        { storage: '1TB', display: '13.6-inch Liquid Retina', ram: '24GB', cpu: '10-core', chip: 'Apple M4', color: 'Midnight', price: '1499', stock: '10' },
        { storage: '2TB', display: '13.6-inch Liquid Retina', ram: '32GB', cpu: '12-core', chip: 'Apple M4 Pro', color: 'Space Black', price: '1999', stock: '10' },
    ];

    function captureCurrentInputs() {
        $('#storage_variants_container .variant-card-box').each(function(index) {
            var $box = $(this);
            currentVariantsState[index] = {
                storage: $box.find('.variant-storage-input').val() || '',
                display: $box.find('.variant-display-input').val() || '',
                ram: $box.find('.variant-ram-input').val() || '',
                cpu: $box.find('.variant-cpu-input').val() || '',
                chip: $box.find('.variant-chip-input').val() || '',
                color: $box.find('.variant-color-select').val() || $box.find('.variant-color-input').val() || '',
                price: $box.find('.variant-price-input').val() || '',
                stock: $box.find('.variant-stock-input').val() || ''
            };
        });
    }

    function getSelectedColorNames() {
        var names = [];
        $('#colors option:selected').each(function() {
            var name = $(this).attr('data-color-name') || $(this).text().trim();
            if (name && names.indexOf(name) === -1) {
                names.push(name);
            }
        });
        return names;
    }

    function onColorsChanged() {
        onVariantsPerColorChange();
    }

    function onVariantsPerColorChange() {
        var selectedColors = getSelectedColorNames();
        var perColor = parseInt($('#variants_per_color_select').val()) || 1;

        captureCurrentInputs();
        var storagePresets = ['256GB', '512GB', '1TB', '2TB'];

        currentVariantsState = [];

        if (selectedColors.length > 0) {
            selectedColors.forEach(function(color) {
                for (var k = 0; k < perColor; k++) {
                    var storage = storagePresets[k % storagePresets.length];
                    var price = 999 + (k * 200);
                    currentVariantsState.push({
                        storage: storage,
                        display: '13.6-inch Liquid Retina',
                        ram: k > 1 ? '32GB' : (k > 0 ? '24GB' : '16GB'),
                        cpu: '10-core',
                        chip: 'Apple M4',
                        color: color,
                        price: price.toString(),
                        stock: '10'
                    });
                }
            });
            var totalCount = currentVariantsState.length;
            $('#variant_count_select').val(totalCount);
            renderStorageVariants(totalCount);
        } else {
            $('#variant_count_select').val(0);
            renderStorageVariants(0);
        }
    }

    function onVariantCountSelectChange() {
        var count = parseInt($('#variant_count_select').val()) || 1;
        captureCurrentInputs();

        var selectedColors = getSelectedColorNames();
        var storagePresets = ['256GB', '512GB', '1TB', '2TB'];

        while (currentVariantsState.length < count) {
            var k = currentVariantsState.length;
            var fallbackColor = selectedColors.length > 0 ? selectedColors[k % selectedColors.length] : 'Space Gray';
            currentVariantsState.push({
                storage: storagePresets[k % storagePresets.length],
                display: '13.6-inch Liquid Retina',
                ram: k > 1 ? '32GB' : (k > 0 ? '24GB' : '16GB'),
                cpu: '10-core',
                chip: 'Apple M4',
                color: fallbackColor,
                price: (999 + k * 200).toString(),
                stock: '10'
            });
        }

        if (currentVariantsState.length > count) {
            currentVariantsState = currentVariantsState.slice(0, count);
        }

        renderStorageVariants(count);
    }

    function changeColorVariantCount(targetColor, delta) {
        captureCurrentInputs();
        var storagePresets = ['256GB', '512GB', '1TB', '2TB'];

        if (delta > 0) {
            var colorItems = currentVariantsState.filter(function(v) { return v.color === targetColor; });
            var k = colorItems.length;
            var storage = storagePresets[k % storagePresets.length];
            var price = (999 + k * 200).toString();

            currentVariantsState.push({
                storage: storage,
                display: '13.6-inch Liquid Retina',
                ram: k > 1 ? '32GB' : (k > 0 ? '24GB' : '16GB'),
                cpu: '10-core',
                chip: 'Apple M4',
                color: targetColor,
                price: price,
                stock: '10'
            });
        } else if (delta < 0) {
            var colorItems = currentVariantsState.filter(function(v) { return v.color === targetColor; });
            var targetIndex = -1;
            for (var idx = currentVariantsState.length - 1; idx >= 0; idx--) {
                if (currentVariantsState[idx].color === targetColor) {
                    targetIndex = idx;
                    break;
                }
            }

            if (targetIndex !== -1 && colorItems.length > 1) {
                currentVariantsState.splice(targetIndex, 1);
            }
        }

        var newTotal = currentVariantsState.length;
        $('#variant_count_select').val(newTotal);
        renderStorageVariants(newTotal);
    }

    function addStorageVariant() {
        var selectedColors = getSelectedColorNames();
        if (selectedColors.length === 0) {
            alert("{{ translate('Please select at least 1 Color first.') }}");
            return;
        }

        captureCurrentInputs();
        var newCount = currentVariantsState.length + 1;
        var fallbackColor = selectedColors[(newCount - 1) % selectedColors.length];

        currentVariantsState.push({
            storage: '256GB',
            display: '13.6-inch Liquid Retina',
            ram: '16GB',
            cpu: '10-core',
            chip: 'Apple M4',
            color: fallbackColor,
            price: '999',
            stock: '10'
        });

        $('#variant_count_select').val(newCount);
        renderStorageVariants(newCount);
    }

    function removeVariantCard(btn) {
        captureCurrentInputs();
        var $box = $(btn).closest('.variant-card-box');
        var index = parseInt($box.attr('data-index'));

        if (currentVariantsState.length <= 1) {
            return;
        }

        currentVariantsState.splice(index, 1);
        var newCount = currentVariantsState.length;
        $('#variant_count_select').val(newCount);
        renderStorageVariants(newCount);
    }

    var colorCodeMap = {};
    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
        colorCodeMap[@json($color->name)] = @json($color->code);
    @endforeach

    function updateVariantHeaderColor(selectEl) {
        captureCurrentInputs();
        var currentCount = currentVariantsState.length;
        renderStorageVariants(currentCount);
    }

    function updateVariantTitleText(inputEl) {
        var $box = $(inputEl).closest('.variant-card-box');
        var storageVal = $(inputEl).val();
        var colorVal = $box.find('.variant-color-select').val();
        $box.find('.variant-title-text').text((colorVal ? colorVal + ' — ' : '') + (storageVal ? storageVal + ' Storage Option' : 'Storage Variant'));
    }

    function renderStorageVariants(count) {
        captureCurrentInputs();
        var $container = $('#storage_variants_container');
        $container.empty();

        var selectedColors = getSelectedColorNames();

        if (selectedColors.length === 0 || count === 0) {
            var noColorHtml = `
                <div class="text-center py-5 border border-dashed rounded-3 bg-light my-2 px-3">
                    <div class="size-60px bg-soft-primary text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:54px; height:54px; background:#e0e7ff; color:#4f46e5;">
                        <i class="las la-palette fs-28"></i>
                    </div>
                    <h6 class="fs-16 fw-700 text-dark mb-1">{{ translate("No Color Selected") }}</h6>
                    <p class="fs-13 text-muted mb-0 mx-auto" style="max-width:420px;">{{ translate("Please select 1 or more Colors in the Colors section above. Storage Variants will automatically generate and group for each chosen color.") }}</p>
                </div>
            `;
            $container.html(noColorHtml);
            return;
        }

        // Group variants by color
        var groupedVariants = {};
        selectedColors.forEach(function(c) {
            groupedVariants[c] = [];
        });

        for (var i = 0; i < count; i++) {
            var fallbackColor = selectedColors[i % selectedColors.length];

            var data = currentVariantsState[i] || defaultPresets[i] || {
                storage: (128 * Math.pow(2, i)) + 'GB',
                display: '13.6-inch',
                ram: '16GB',
                cpu: '10-core',
                chip: 'Apple M4',
                color: fallbackColor,
                price: '999',
                stock: '10'
            };

            if (!data.color || selectedColors.indexOf(data.color) === -1) {
                data.color = fallbackColor;
            }

            if (!groupedVariants[data.color]) {
                groupedVariants[data.color] = [];
            }

            data.originalIndex = i;
            groupedVariants[data.color].push(data);
        }

        // Render each color group section
        selectedColors.forEach(function(colorName) {
            var variantList = groupedVariants[colorName] || [];
            if (variantList.length === 0) return;

            var hexCode = colorCodeMap[colorName] || '#3b82f6';
            var groupHtml = `
                <div class="color-group-section border rounded-3 p-3 p-md-4 mb-4 bg-light-50 shadow-xs" style="background: #f8fafc; border: 1px solid #cbd5e1; border-left: 5px solid ${hexCode};">
                    <div class="color-group-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap" style="border-color: #cbd5e1 !important; gap: 10px;">
                        <h6 class="fs-15 fw-700 text-dark mb-0 d-flex align-items-center">
                            <span class="size-18px d-inline-block mr-2 rounded-circle border shadow-xs" style="background: ${hexCode}; width: 18px; height: 18px; box-shadow: 0 0 0 2px #fff, 0 0 0 3px ${hexCode};"></span>
                            <span>${colorName} Storage Variants</span>
                            <span class="badge badge-inline badge-soft-primary ml-2 px-3 py-1 fs-12 fw-700 rounded-pill">${variantList.length} Variants</span>
                        </h6>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="fs-12 text-muted fw-700 uppercase mr-1">{{ translate("Variants") }}:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary px-2 font-weight-bold" onclick="changeColorVariantCount('${colorName}', -1)" title="Decrease variant for ${colorName}" ${variantList.length <= 1 ? 'disabled' : ''}>
                                    <i class="las la-minus"></i>
                                </button>
                                <span class="btn btn-light disabled px-3 fw-700 text-dark">${variantList.length}</span>
                                <button type="button" class="btn btn-outline-secondary px-2 font-weight-bold" onclick="changeColorVariantCount('${colorName}', 1)" title="Increase variant for ${colorName}">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-soft-primary btn-sm fs-12 fw-700 px-3 ml-1" onclick="changeColorVariantCount('${colorName}', 1)">
                                <i class="las la-plus-circle"></i> Add Variant
                            </button>
                        </div>
                    </div>
                    <div class="color-group-body">
            `;

            variantList.forEach(function(data) {
                var i = data.originalIndex;
                var titleText = (data.color ? data.color + ' — ' : '') + (data.storage ? data.storage + ' Storage Option' : 'Storage Variant #' + (i + 1));

                var boxHtml = `
                    <div class="variant-card-box mb-3" data-index="${i}">
                        <div class="variant-card-header">
                            <h6 class="variant-card-title mb-0">
                                <span class="variant-badge">Variant #${i + 1}</span>
                                <span class="variant-title-text fw-700 text-dark ml-2">${titleText}</span>
                                <span class="badge badge-inline badge-soft-info ml-2 variant-color-badge">${data.color || ''}</span>
                            </h6>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="text-muted fs-12 mr-2">${i === 0 ? '{{ translate("Default Base Variant") }}' : ''}</span>
                                ${count > 1 ? `<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" onclick="removeVariantCard(this)" title="{{ translate('Delete Variant') }}"><i class="las la-trash fs-14"></i></button>` : ''}
                            </div>
                        </div>
                        <div class="variant-card-body">
                            <div class="row gutters-10">
                                <!-- Storage -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="variant-input-label d-block">{{ translate("Storage") }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="variants[${i}][storage]" class="form-control variant-storage-input" placeholder="e.g. 256GB" value="${data.storage || ''}" oninput="updateVariantTitleText(this)" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dropdown-preset dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-angle-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '128GB')">128GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '256GB')">256GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '512GB')">512GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '1TB')">1TB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '2TB')">2TB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '4TB')">4TB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'storage', '8TB')">8TB</a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item text-primary font-weight-bold" href="javascript:void(0)" onclick="setVariantField(this, 'storage', 'CUSTOM_PROMPT')"><i class="las la-plus-circle mr-1"></i> {{ translate('+ Add Custom Storage...') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Display -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="variant-input-label d-block">{{ translate("Display") }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="variants[${i}][display]" class="form-control variant-display-input" placeholder="e.g. 13.6-inch" value="${data.display || ''}" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dropdown-preset dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-angle-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '13.6-inch Liquid Retina')">13.6-inch Liquid Retina</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '14.2-inch Liquid Retina XDR')">14.2-inch Liquid Retina XDR</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '15.3-inch Liquid Retina')">15.3-inch Liquid Retina</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '16.2-inch Liquid Retina XDR')">16.2-inch Liquid Retina XDR</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '24-inch 4.5K Retina')">24-inch 4.5K Retina</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'display', '27-inch 5K Retina')">27-inch 5K Retina</a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item text-primary font-weight-bold" href="javascript:void(0)" onclick="setVariantField(this, 'display', 'CUSTOM_PROMPT')"><i class="las la-plus-circle mr-1"></i> {{ translate('+ Add Custom Display...') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- RAM -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="variant-input-label d-block">{{ translate("RAM") }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="variants[${i}][ram]" class="form-control variant-ram-input" placeholder="e.g. 16GB" value="${data.ram || ''}" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dropdown-preset dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-angle-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '8GB')">8GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '16GB')">16GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '24GB')">24GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '32GB')">32GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '64GB')">64GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '96GB')">96GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '128GB')">128GB</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'ram', '192GB')">192GB</a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item text-primary font-weight-bold" href="javascript:void(0)" onclick="setVariantField(this, 'ram', 'CUSTOM_PROMPT')"><i class="las la-plus-circle mr-1"></i> {{ translate('+ Add Custom RAM...') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="variant-input-label d-block">{{ translate("Color") }}</label>
                                        <select name="variants[${i}][color]" class="form-control variant-color-select" onchange="updateVariantHeaderColor(this)">
                                            ${selectedColors.map(c => `<option value="${c}" ${c === data.color ? 'selected' : ''}>${c}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>

                                <!-- CPU -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="variant-input-label d-block">{{ translate("CPU") }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="variants[${i}][cpu]" class="form-control variant-cpu-input" placeholder="e.g. 10-core" value="${data.cpu || ''}" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dropdown-preset dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-angle-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '8-core')">8-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '10-core')">10-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '12-core')">12-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '14-core')">14-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '16-core')">16-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '24-core')">24-core</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', '32-core')">32-core</a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item text-primary font-weight-bold" href="javascript:void(0)" onclick="setVariantField(this, 'cpu', 'CUSTOM_PROMPT')"><i class="las la-plus-circle mr-1"></i> {{ translate('+ Add Custom CPU...') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chip -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="variant-input-label d-block">{{ translate("Chip") }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="variants[${i}][chip]" class="form-control variant-chip-input" placeholder="e.g. Apple M4" value="${data.chip || ''}" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dropdown-preset dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="las la-angle-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M1')">Apple M1</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M2')">Apple M2</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M3')">Apple M3</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M4')">Apple M4</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M4 Pro')">Apple M4 Pro</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Apple M4 Max')">Apple M4 Max</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Intel Core i7')">Intel Core i7</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'Intel Core i9')">Intel Core i9</a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item text-primary font-weight-bold" href="javascript:void(0)" onclick="setVariantField(this, 'chip', 'CUSTOM_PROMPT')"><i class="las la-plus-circle mr-1"></i> {{ translate('+ Add Custom Chip...') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-3">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="variant-input-label d-block">{{ translate("Price ($)") }} <span class="text-danger">*</span></label>
                                        <input type="number" lang="en" min="0" step="0.01" name="variants[${i}][price]" class="form-control variant-price-input" placeholder="e.g. 999" value="${data.price || ''}" required>
                                    </div>
                                </div>

                                <!-- Stock per variant -->
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="variant-input-label d-block">{{ translate("Stock") }} <span class="text-danger">*</span></label>
                                        <input type="number" lang="en" min="0" step="1" name="variants[${i}][stock]" class="form-control variant-stock-input" placeholder="e.g. 10" value="${data.stock !== undefined ? data.stock : '10'}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                groupHtml += boxHtml;
            });

            groupHtml += `
                    </div>
                </div>
            `;

            $container.append(groupHtml);
        });
    }

    var activeTargetInput = null;
    var activeFieldName = '';

    function setVariantField(btn, fieldName, val) {
        var $box = $(btn).closest('.form-group');
        var $input = $box.find('input');

        if (val === 'CUSTOM_PROMPT') {
            activeTargetInput = $input;
            activeFieldName = fieldName;

            var prettyName = fieldName ? (fieldName.charAt(0).toUpperCase() + fieldName.slice(1)) : 'Option';
            $('#customModalTitle').text('{{ translate("Add Custom") }} ' + prettyName);
            $('#customModalDescription').text('{{ translate("Enter a new custom") }} ' + fieldName + ' {{ translate("option for this variant.") }}');
            $('#customModalInputLabel').text(prettyName.toUpperCase() + ' {{ translate("VALUE") }}');
            $('#customModalInput').val('').attr('placeholder', '{{ translate("e.g. Custom") }} ' + prettyName);

            $('#customValueModal').modal('show');

            setTimeout(function() {
                $('#customModalInput').focus();
            }, 300);
        } else {
            $input.val(val).trigger('input').trigger('change');
            if (fieldName === 'storage') {
                updateVariantTitleText($input[0]);
            }
        }
    }

    function formatCustomValue(fieldName, rawVal) {
        if (!rawVal) return '';
        var trimmed = rawVal.trim();
        if (!trimmed) return '';

        if (fieldName === 'storage') {
            if (/gb|tb|mb/i.test(trimmed)) {
                return trimmed.replace(/gb/i, 'GB').replace(/tb/i, 'TB').replace(/mb/i, 'MB');
            }

            var pureNum = trimmed.replace(/[^0-9]/g, '');
            if (pureNum.length > 0) {
                if (pureNum.length === 1) {
                    return pureNum + 'TB';
                } else {
                    return pureNum + 'GB';
                }
            }
        } else if (fieldName === 'ram') {
            if (/gb|mb/i.test(trimmed)) {
                return trimmed.replace(/gb/i, 'GB').replace(/mb/i, 'MB');
            }
            var pureNum = trimmed.replace(/[^0-9]/g, '');
            if (pureNum.length > 0) {
                return pureNum + 'GB';
            }
        } else if (fieldName === 'cpu') {
            if (/core/i.test(trimmed)) {
                return trimmed;
            }
            var pureNum = trimmed.replace(/[^0-9]/g, '');
            if (pureNum.length > 0) {
                return pureNum + '-core';
            }
        }

        return trimmed;
    }

    function applyCustomValueModal() {
        var rawVal = $('#customModalInput').val();
        if (rawVal && rawVal.trim() !== '') {
            var formattedVal = formatCustomValue(activeFieldName, rawVal);
            if (activeTargetInput) {
                activeTargetInput.val(formattedVal).trigger('input').trigger('change');
                if (activeFieldName === 'storage') {
                    updateVariantTitleText(activeTargetInput[0]);
                }
            }
            $('#customValueModal').modal('hide');
        } else {
            $('#customModalInput').focus();
        }
    }

    $(document).on('keypress', '#customModalInput', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            applyCustomValueModal();
        }
    });

    $(document).on('change blur', '.variant-storage-input', function() {
        var val = $(this).val();
        if (val && /^[0-9]+$/.test(val.trim())) {
            var formatted = formatCustomValue('storage', val);
            $(this).val(formatted);
            updateVariantTitleText(this);
        }
    });

    $(document).on('change blur', '.variant-ram-input', function() {
        var val = $(this).val();
        if (val && /^[0-9]+$/.test(val.trim())) {
            var formatted = formatCustomValue('ram', val);
            $(this).val(formatted);
        }
    });

    $(document).on('change blur', '.variant-cpu-input', function() {
        var val = $(this).val();
        if (val && /^[0-9]+$/.test(val.trim())) {
            var formatted = formatCustomValue('cpu', val);
            $(this).val(formatted);
        }
    });

    function generateSKU() {
        const btn = document.getElementById('generateSKUBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i>';
        setTimeout(() => {
            const prefix = 'COMP-';
            const randomCode = Math.floor(100000 + Math.random() * 900000);
            document.getElementById('sku').value = prefix + randomCode;
            btn.innerHTML = '<i class="las la-check-circle text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = "{{ translate('Generate') }}";
                btn.disabled = false;
            }, 1000);
        }, 300);
    }

    function toggleWarranty() {
        if ($('#has_warranty').is(':checked')) {
            $('#warranty_selection').show();
        } else {
            $('#warranty_selection').hide();
        }
    }

    function updateStatusLabel(el) {
        let label = $(el).closest('.d-flex').find('.status-label');
        if (el.checked) {
            label.text('{{ translate("Active") }}').removeClass('text-danger').addClass('text-success');
        } else {
            label.text('{{ translate("Disabled") }}').removeClass('text-success').addClass('text-danger');
        }
    }

    $(document).ready(function() {
        var initialCount = parseInt($('#variant_count_select').val()) || 2;
        renderStorageVariants(initialCount);

        $('#variant_count_select').on('change', function() {
            var count = parseInt($(this).val()) || 1;
            renderStorageVariants(count);
        });

        $(document).on('change change.bs.select', '#colors', function() {
            onColorsChanged();
        });
    });
</script>
@endsection
