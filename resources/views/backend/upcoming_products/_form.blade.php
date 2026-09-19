@php
    $item = $upcoming_product ?? null;
    $currentType = old('type', $item->type ?? 'pre_order');
@endphp
<div class="row gutters-5">
    <div class="col-xl-8">

        <!-- Type -->
        <div class="border border-gray-300 rounded-2 mb-4">
            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Product Type') }} <span class="text-danger">*</span></h5>
                <div class="row gutters-10 up-type-picker">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <label class="up-type-option mb-0 w-100">
                            <input type="radio" name="type" value="pre_order" @checked($currentType == 'pre_order') onchange="toggleUpcomingType()">
                            <span class="up-type-card">
                                <i class="las la-shopping-bag"></i>
                                <span>
                                    <strong>{{ translate('Pre-order') }}</strong>
                                    <small>{{ translate('Customers can reserve the product now, with an optional deposit.') }}</small>
                                </span>
                            </span>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="up-type-option mb-0 w-100">
                            <input type="radio" name="type" value="coming_soon" @checked($currentType == 'coming_soon') onchange="toggleUpcomingType()">
                            <span class="up-type-card">
                                <i class="las la-hourglass-half"></i>
                                <span>
                                    <strong>{{ translate('Coming Soon') }}</strong>
                                    <small>{{ translate('Teaser with countdown. Customers can ask to be notified.') }}</small>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="border border-gray-300 rounded-2 mb-4">
            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                    <h5 class="fs-16 fw-700">{{ translate('Basic Information') }}</h5>
                </div>
                <div class="row gutters-5">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Product Name') }} <span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ translate('Name') }}" name="name" value="{{ $item->name ?? '' }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Brand') }}</label>
                            <select class="form-control aiz-selectpicker" name="brand_id" data-live-search="true">
                                <option value="">{{ translate('Select Brand') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected(($item->brand_id ?? null) == $brand->id)>{{ $brand->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Badge Text') }}</label>
                            <input type="text" maxlength="50" placeholder="{{ translate('e.g. New 2026, Limited') }}" name="badge_text" value="{{ $item->badge_text ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Short Description') }}</label>
                            <input type="text" maxlength="500" placeholder="{{ translate('One line shown on the home card') }}" name="short_description" value="{{ $item->short_description ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="col-from-label fs-14 fw-500">{{ translate('Description') }}</label>
                    <textarea name="description" rows="5" class="form-control">{{ $item->description ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Price & Schedule -->
        <div class="border border-gray-300 rounded-2 mb-4">
            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                    <h5 class="fs-16 fw-700">{{ translate('Price & Schedule') }}</h5>
                </div>
                <div class="row gutters-5">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Expected Price') }}</label>
                            <input type="number" min="0" step="0.01" placeholder="{{ translate('Leave empty to hide price') }}" name="price" value="{{ $item->price ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 up-preorder-only">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Deposit Amount') }}</label>
                            <input type="number" min="0" step="0.01" placeholder="{{ translate('Optional') }}" name="deposit_amount" value="{{ $item->deposit_amount ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Release Date') }}</label>
                            <input type="datetime-local" name="release_date" value="{{ optional($item->release_date ?? null)->format('Y-m-d\TH:i') }}" class="form-control">
                            <small class="text-muted">{{ translate('Coming Soon products count down to this date.') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6 up-preorder-only">
                        <div class="form-group mb-3">
                            <label class="col-from-label fs-14 fw-500">{{ translate('Pre-order Closes On') }}</label>
                            <input type="datetime-local" name="preorder_end_date" value="{{ optional($item->preorder_end_date ?? null)->format('Y-m-d\TH:i') }}" class="form-control">
                            <small class="text-muted">{{ translate('Empty = pre-order stays open.') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xl-4">
        <!-- Configuration -->
        <div class="border border-gray-300 rounded-2 mb-4">
            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Configuration') }}</h5>
                <div class="d-flex align-items-center mt-3 mb-3">
                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                        <input value="1" type="checkbox" name="published" @checked(!$item || $item->status == 1) onchange="updateStatusLabel(this)">
                        <span></span>
                    </label>
                    <span class="fs-14 fw-600 d-block status-label {{ !$item || $item->status == 1 ? 'text-success' : 'text-danger' }}" style="margin-top: -6px">{{ !$item || $item->status == 1 ? translate('Active') : translate('Disabled') }}</span>
                </div>
                <div class="form-group mb-3">
                    <label class="col-from-label fs-14 fw-500">{{ translate('Sort Order') }}</label>
                    <input type="number" name="sort_order" value="{{ $item->sort_order ?? 0 }}" class="form-control">
                    <small class="text-muted">{{ translate('Lower numbers show first on the home page.') }}</small>
                </div>
                <div class="form-group mb-0">
                    <label class="col-from-label fs-14 fw-500">{{ translate('Learn More Link') }}</label>
                    <input type="url" name="external_link" value="{{ $item->external_link ?? '' }}" placeholder="https://" class="form-control">
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="border border-gray-300 rounded-2 mb-4">
            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Product Image') }}</h5>
                <div class="form-group mb-3">
                    <label class="col-from-label fs-14 fw-500">{{ translate('Thumbnail Image') }} <span class="text-danger">*</span></label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="thumbnail_img" value="{{ $item->thumbnail_img ?? '' }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm"></div>
                </div>
                <div class="form-group mb-0">
                    <label class="col-from-label fs-14 fw-500">{{ translate('Gallery Images') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="gallery" value="{{ $item->gallery ?? '' }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row align-items-center mb-4">
    <div class="col-12 text-right">
        <button type="submit" class="btn btn-primary w-200px fs-14 fw-700">{{ translate('Save Product') }}</button>
    </div>
</div>
