@php
    $field = function ($key, $default = '') use ($branch) {
        return is_array($branch) ? ($branch[$key] ?? $default) : data_get($branch, $key, $default);
    };
@endphp

<div class="card branch-admin-card" data-index="{{ $index }}">
    <div class="card-header bg-soft-light d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <span class="badge badge-inline badge-soft-primary mr-2">#<span class="branch-card-number">{{ is_numeric($index) ? $index + 1 : 1 }}</span></span>
            <h3 class="h6 mb-0">{{ translate('Branch') }}</h3>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-light" data-branch-action="up" title="{{ translate('Move up') }}"><i class="las la-arrow-up"></i></button>
            <button type="button" class="btn btn-xs btn-light" data-branch-action="down" title="{{ translate('Move down') }}"><i class="las la-arrow-down"></i></button>
            <button type="button" class="btn btn-xs btn-soft-danger" data-branch-action="remove" title="{{ translate('Remove') }}"><i class="las la-trash"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="row gutters-16">
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ translate('Store photo') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                        <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div></div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="branches[{{ $index }}][image]" class="selected-files" value="{{ $field('image') }}">
                    </div>
                    <div class="file-preview box sm"></div>
                    <small class="text-muted">{{ translate('Recommended: 1200 × 900 px landscape photo.') }}</small>
                </div>

                <div class="form-group">
                    <label>{{ translate('Brand') }}</label>
                    <select name="branches[{{ $index }}][brand]" class="form-control aiz-selectpicker" required>
                        <option value="ANLYN POP" @selected($field('brand') === 'ANLYN POP')>ANLYN POP</option>
                        <option value="ANLYN BLOOM" @selected($field('brand') === 'ANLYN BLOOM')>ANLYN BLOOM</option>
                    </select>
                </div>

                <div class="form-group mb-lg-0">
                    <input type="hidden" name="branches[{{ $index }}][active]" value="0">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" name="branches[{{ $index }}][active]" value="1" @checked((bool) $field('active', true))>
                        <span></span>
                    </label>
                    <span class="ml-2 fs-12 font-weight-bold">{{ translate('Show this branch') }}</span>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row gutters-12">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>{{ translate('Branch name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="branches[{{ $index }}][name]" class="form-control" maxlength="120" value="{{ $field('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ translate('City label') }}</label>
                            <input type="text" name="branches[{{ $index }}][city]" class="form-control" maxlength="100" value="{{ $field('city', 'Phnom Penh') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ translate('Full address') }} <span class="text-danger">*</span></label>
                    <textarea name="branches[{{ $index }}][address]" class="form-control" rows="2" maxlength="500" required>{{ $field('address') }}</textarea>
                </div>

                <div class="row gutters-12">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>{{ translate('Opening hours') }} <span class="text-danger">*</span></label>
                            <input type="text" name="branches[{{ $index }}][hours]" class="form-control" maxlength="250" value="{{ $field('hours') }}" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>{{ translate('Contact number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="branches[{{ $index }}][phone]" class="form-control" maxlength="80" value="{{ $field('phone') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ translate('Google Maps URL') }}</label>
                    <input type="url" name="branches[{{ $index }}][map]" class="form-control" maxlength="1000" value="{{ $field('map') }}" placeholder="https://maps.google.com/…">
                </div>

                <div class="row gutters-12">
                    <div class="col-md-6">
                        <div class="form-group mb-md-0">
                            <label><i class="lab la-facebook-f mr-1"></i>{{ translate('Facebook URL') }}</label>
                            <input type="url" name="branches[{{ $index }}][facebook]" class="form-control" maxlength="1000" value="{{ $field('facebook') }}" placeholder="https://facebook.com/…">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label><i class="lab la-instagram mr-1"></i>{{ translate('Instagram URL') }}</label>
                            <input type="url" name="branches[{{ $index }}][instagram]" class="form-control" maxlength="1000" value="{{ $field('instagram') }}" placeholder="https://instagram.com/…">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
