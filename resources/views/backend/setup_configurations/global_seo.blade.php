@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Shop & SEO Settings -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Shop & SEO Settings') }}</h6>
                    <p class="mb-0 text-muted fs-13">{{ translate('This controls how your shop appears in Google search results and in link previews on Facebook, Messenger, WhatsApp, Telegram, etc.') }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Shop Name -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Shop Name') }}</label>
                            <input type="hidden" name="types[]" value="website_name">
                            <input type="text" class="form-control" placeholder="{{ translate('Shop Name') }}"
                                name="website_name" value="{{ get_setting('website_name') }}">
                            <small class="text-muted">{{ translate('Shown as your site name in browser tabs, search results, and link previews.') }}</small>
                        </div>

                        <!-- Logo -->
                        <div class="form-group mb-4">
                            <label class="col-from-label d-block">{{ translate('Logo') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="header_logo" class="selected-files"
                                            value="{{ get_setting('header_logo') }}">
                                        <input type="hidden" name="types[]" value="header_logo">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small class="text-muted">{{ translate('Shown in the site header. Minimum dimensions: 244px width X 40px height.') }}</small>
                        </div>

                        <!-- Meta Title -->
                        <div class="form-group mb-4">
                            <label class="ol-from-label">{{ translate('Meta Title') }}</label>
                            <input type="hidden" name="types[]" value="meta_title">
                            <input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title"
                                value="{{ get_setting('meta_title') }}">

                        </div>
                        <!-- Meta description -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Meta description') }}</label>
                            <input type="hidden" name="types[]" value="meta_description">
                            <textarea class="resize-off form-control" placeholder="{{translate('Description')}}"
                                name="meta_description">{{ get_setting('meta_description') }}</textarea>

                        </div>
                        <!-- Keywords -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Keywords') }}</label>
                            <input type="hidden" name="types[]" value="meta_keywords">
                            <textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}"
                                name="meta_keywords">{{ get_setting('meta_keywords') }}</textarea>
                            <small class="text-muted">{{ translate('Separate with coma') }}</small>

                        </div>
                        <!-- Cover / Social Share Image -->
                        <div class="form-group mb-4">
                            <label class="col-from-label d-block">{{ translate('Cover Image (Social Share)') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="meta_image" class="selected-files"
                                            value="{{ get_setting('meta_image') }}">
                                        <input type="hidden" name="types[]" value="meta_image">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small class="text-muted">{{ translate('This image shows up when your homepage link is shared on Facebook, WhatsApp, Messenger, etc. Recommended size: 1200x630px. Falls back to the Logo if left empty.') }}</small>
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