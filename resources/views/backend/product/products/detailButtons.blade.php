@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Detail Buttons') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf

                        @foreach ([
                            'product_detail_show_buy_now', 'product_detail_buy_now_text', 'product_detail_buy_now_bg_color', 'product_detail_buy_now_text_color',
                            'product_detail_show_add_to_cart', 'product_detail_add_to_cart_text', 'product_detail_add_to_cart_bg_color', 'product_detail_add_to_cart_text_color',
                            'product_detail_show_custom_button', 'product_detail_custom_button_text', 'product_detail_custom_button_url', 'product_detail_custom_button_new_tab',
                            'product_detail_custom_button_bg_color', 'product_detail_custom_button_text_color',
                            'product_detail_show_chat_button', 'product_detail_chat_button_text', 'product_detail_chat_button_url', 'product_detail_chat_button_new_tab',
                            'product_detail_chat_button_bg_color', 'product_detail_chat_button_text_color'
                        ] as $settingType)
                            <input type="hidden" name="types[]" value="{{ $settingType }}">
                        @endforeach

                        <h6 class="fs-14 fw-700 border-bottom pb-3 mb-3">{{ translate('Buy Now Button') }}</h6>
                        <div class="form-group d-flex align-items-center">
                            <input type="hidden" name="product_detail_show_buy_now" value="0">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="product_detail_show_buy_now" value="1" @checked((int) get_setting('product_detail_show_buy_now', 1) === 1)>
                                <span></span>
                            </label>
                            <span>{{ translate('Show Buy Now Button') }}</span>
                        </div>
                        <div class="row gutters-10">
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Button Text') }}</label>
                                <input type="text" class="form-control" name="product_detail_buy_now_text"
                                    value="{{ get_setting('product_detail_buy_now_text') }}" placeholder="{{ translate('Buy Now') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Background Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_buy_now_bg_color"
                                    value="{{ get_setting('product_detail_buy_now_bg_color', '#17171f') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Text Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_buy_now_text_color"
                                    value="{{ get_setting('product_detail_buy_now_text_color', '#ffffff') }}">
                            </div>
                        </div>

                        <h6 class="fs-14 fw-700 border-bottom pb-3 mb-3 mt-3">{{ translate('Add to Cart Button') }}</h6>
                        <div class="form-group d-flex align-items-center">
                            <input type="hidden" name="product_detail_show_add_to_cart" value="0">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="product_detail_show_add_to_cart" value="1" @checked((int) get_setting('product_detail_show_add_to_cart', 1) === 1)>
                                <span></span>
                            </label>
                            <span>{{ translate('Show Add to Cart Button') }}</span>
                        </div>
                        <div class="row gutters-10">
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Button Text') }}</label>
                                <input type="text" class="form-control" name="product_detail_add_to_cart_text"
                                    value="{{ get_setting('product_detail_add_to_cart_text') }}" placeholder="{{ translate('Add to Cart') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Background Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_add_to_cart_bg_color"
                                    value="{{ get_setting('product_detail_add_to_cart_bg_color', '#dcebff') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Text Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_add_to_cart_text_color"
                                    value="{{ get_setting('product_detail_add_to_cart_text_color', '#3390f3') }}">
                            </div>
                        </div>

                        <h6 class="fs-14 fw-700 border-bottom pb-3 mb-3 mt-3">{{ translate('Custom Button') }}</h6>
                        <div class="form-group d-flex align-items-center">
                            <input type="hidden" name="product_detail_show_custom_button" value="0">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="product_detail_show_custom_button" value="1" @checked((int) get_setting('product_detail_show_custom_button', 0) === 1)>
                                <span></span>
                            </label>
                            <span>{{ translate('Show Custom Button') }}</span>
                        </div>
                        <div class="row gutters-10">
                            <div class="col-md-4 form-group">
                                <label>{{ translate('Button Text') }}</label>
                                <input type="text" class="form-control" name="product_detail_custom_button_text"
                                    value="{{ get_setting('product_detail_custom_button_text') ?: translate('Contact Us') }}" placeholder="{{ translate('Contact Us') }}">
                            </div>
                            <div class="col-md-8 form-group">
                                <label>{{ translate('Button URL') }}</label>
                                <input type="url" class="form-control" name="product_detail_custom_button_url"
                                    value="{{ get_setting('product_detail_custom_button_url') ?: route('custom-pages.show_custom_page', 'contact-us') }}" placeholder="https://">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Background Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_custom_button_bg_color"
                                    value="{{ get_setting('product_detail_custom_button_bg_color', '#19c553') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Text Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_custom_button_text_color"
                                    value="{{ get_setting('product_detail_custom_button_text_color', '#ffffff') }}">
                            </div>
                            <div class="col-md-6 form-group d-flex align-items-center pt-md-4">
                                <input type="hidden" name="product_detail_custom_button_new_tab" value="0">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="checkbox" name="product_detail_custom_button_new_tab" value="1" @checked((int) get_setting('product_detail_custom_button_new_tab', 1) === 1)>
                                    <span></span>
                                </label>
                                <span>{{ translate('Open in a new tab') }}</span>
                            </div>
                        </div>

                        <h6 class="fs-14 fw-700 border-bottom pb-3 mb-3 mt-3">{{ translate('Chat Button') }}</h6>
                        <div class="form-group d-flex align-items-center">
                            <input type="hidden" name="product_detail_show_chat_button" value="0">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="product_detail_show_chat_button" value="1" @checked((int) get_setting('product_detail_show_chat_button', 0) === 1)>
                                <span></span>
                            </label>
                            <span>{{ translate('Show Chat Button') }}</span>
                        </div>
                        <div class="row gutters-10">
                            <div class="col-md-4 form-group">
                                <label>{{ translate('Button Text') }}</label>
                                <input type="text" class="form-control" name="product_detail_chat_button_text"
                                    value="{{ get_setting('product_detail_chat_button_text') ?: translate('Chat With Us') }}"
                                    placeholder="{{ translate('Chat With Us') }}">
                            </div>
                            <div class="col-md-8 form-group">
                                <label>{{ translate('Chat Link') }}</label>
                                <input type="url" class="form-control" name="product_detail_chat_button_url"
                                    value="{{ get_setting('product_detail_chat_button_url') }}" placeholder="https://wa.me/855...">
                                <small class="text-muted">
                                    {{ translate('Add a full HTTPS link for WhatsApp, Messenger, Telegram, or another chat service.') }}
                                </small>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Background Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_chat_button_bg_color"
                                    value="{{ get_setting('product_detail_chat_button_bg_color', '#1677ff') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Text Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_chat_button_text_color"
                                    value="{{ get_setting('product_detail_chat_button_text_color', '#ffffff') }}">
                            </div>
                            <div class="col-md-6 form-group d-flex align-items-center pt-md-4">
                                <input type="hidden" name="product_detail_chat_button_new_tab" value="0">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="checkbox" name="product_detail_chat_button_new_tab" value="1" @checked((int) get_setting('product_detail_chat_button_new_tab', 1) === 1)>
                                    <span></span>
                                </label>
                                <span>{{ translate('Open in a new tab') }}</span>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
