<!-- Site Icon / Favicon -->
<div class="form-group mb-4">
    <label class="col-from-label">{{ translate('Site Icon / Favicon') }}</label>
    <div class="add-product-page-content">
        <div class="img-upload-container">
            <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                    <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                        class="w-40px h-40px w-md-64px h-md-64px" alt="{{ translate('Choose site icon') }}">
                </div>
                <input type="hidden" name="types[]" value="site_icon">
                <input type="hidden" name="site_icon" class="selected-files"
                    value="{{ get_setting('site_icon') }}">
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
    <small class="text-muted">
        {{ translate('Use a square PNG, JPG, WebP, or ICO image. 96x96 pixels or larger is recommended for Google Search.') }}
    </small>
</div>
