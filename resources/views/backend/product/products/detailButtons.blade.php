@extends('backend.layouts.app')

@section('content')
    <style>
        .custom-button-settings-page {
            --cbs-border: #e7eaf0;
            --cbs-muted: #77809a;
            --cbs-primary: #3390f3;
        }

        .custom-button-settings-page .settings-shell {
            overflow: hidden;
            border: 1px solid var(--cbs-border);
            border-radius: 16px;
            box-shadow: 0 10px 32px rgba(30, 44, 76, .06);
        }

        .custom-button-settings-page .settings-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--cbs-border);
            background: linear-gradient(135deg, #fff 0%, #f6faff 100%);
        }

        .custom-button-settings-page .settings-header-icon,
        .custom-button-settings-page .panel-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
        }

        .custom-button-settings-page .settings-header-icon {
            width: 42px;
            height: 42px;
            background: #eaf4ff;
            color: var(--cbs-primary);
        }

        .custom-button-settings-page .settings-body {
            padding: 20px;
            background: #f7f8fb;
        }

        .custom-button-settings-page .button-settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .custom-button-settings-page .button-setting-panel {
            padding: 20px;
            border: 1px solid var(--cbs-border);
            border-radius: 13px;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .custom-button-settings-page .button-setting-panel:hover {
            border-color: #cdddf2;
            box-shadow: 0 8px 24px rgba(44, 79, 123, .07);
            transform: translateY(-1px);
        }

        .custom-button-settings-page .button-setting-panel.contact-sales-panel {
            grid-column: 1 / -1;
            border-color: rgba(34, 158, 217, .28);
            background: linear-gradient(135deg, #fff 0%, #f2fbff 100%);
        }

        .custom-button-settings-page .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--cbs-border);
        }

        .custom-button-settings-page .panel-icon {
            width: 34px;
            height: 34px;
            margin-right: 10px;
            background: #f0f3f8;
            color: #536078;
        }

        .custom-button-settings-page .contact-sales-panel .panel-icon {
            background: #e5f7ff;
            color: #168acd;
        }

        .custom-button-settings-page .panel-heading h6 {
            margin: 0;
            color: #202437;
            font-size: 14px;
            font-weight: 700;
        }

        .custom-button-settings-page .panel-description,
        .custom-button-settings-page .settings-subtitle {
            color: var(--cbs-muted);
            font-size: 12px;
        }

        .custom-button-settings-page .visibility-control {
            min-height: 36px;
            margin: 0;
            padding: 7px 10px;
            border: 1px solid var(--cbs-border);
            border-radius: 9px;
            background: #f8f9fb;
            white-space: nowrap;
        }

        .custom-button-settings-page label:not(.aiz-switch) {
            color: #4d566c;
            font-size: 12px;
            font-weight: 600;
        }

        .custom-button-settings-page .form-control {
            min-height: 43px;
            border-color: #dfe3eb;
            border-radius: 9px;
        }

        .custom-button-settings-page .form-control:focus {
            border-color: var(--cbs-primary);
            box-shadow: 0 0 0 3px rgba(51, 144, 243, .11);
        }

        .custom-button-settings-page input[type="color"] {
            padding: 5px !important;
            cursor: pointer;
        }

        .custom-button-settings-page .settings-actions {
            margin: 20px -20px -20px;
            padding: 16px 20px;
            border-top: 1px solid var(--cbs-border);
            background: #fff;
        }

        .custom-button-settings-page .settings-actions .btn {
            min-width: 132px;
            min-height: 42px;
            border-radius: 9px;
            font-weight: 600;
        }

        @media (max-width: 991px) {
            .custom-button-settings-page .button-settings-grid {
                grid-template-columns: 1fr;
            }

            .custom-button-settings-page .button-setting-panel.contact-sales-panel {
                grid-column: auto;
            }
        }

        @media (max-width: 575px) {
            .custom-button-settings-page .settings-header,
            .custom-button-settings-page .settings-body,
            .custom-button-settings-page .button-setting-panel {
                padding: 16px;
            }

            .custom-button-settings-page .panel-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .custom-button-settings-page .settings-actions {
                margin-right: -16px;
                margin-bottom: -16px;
                margin-left: -16px;
            }

            .custom-button-settings-page .settings-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="row custom-button-settings-page">
        <div class="col-xl-10 mx-auto">
            <div class="card settings-shell">
                <div class="card-header settings-header">
                    <div class="d-flex align-items-center">
                        <span class="settings-header-icon mr-3"><i class="las la-mouse-pointer fs-22"></i></span>
                        <div>
                            <h5 class="mb-1 h6">{{ translate('Product Detail Buttons') }}</h5>
                            <div class="settings-subtitle">{{ translate('Control the buttons customers see on the product detail page.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body settings-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf

                        @foreach ([
                            'product_detail_show_buy_now', 'product_detail_buy_now_text', 'product_detail_buy_now_click_message_enabled', 'product_detail_buy_now_click_message', 'product_detail_buy_now_bg_color', 'product_detail_buy_now_text_color',
                            'product_detail_show_add_to_cart', 'product_detail_add_to_cart_text', 'product_detail_add_to_cart_click_message_enabled', 'product_detail_add_to_cart_click_message', 'product_detail_add_to_cart_bg_color', 'product_detail_add_to_cart_text_color',
                            'product_detail_show_custom_button', 'product_detail_custom_button_text', 'product_detail_custom_button_url', 'product_detail_custom_button_new_tab',
                            'product_detail_custom_button_bg_color', 'product_detail_custom_button_text_color',
                            'product_detail_show_chat_button', 'product_detail_chat_button_text', 'product_detail_chat_button_url', 'product_detail_chat_button_new_tab',
                            'product_detail_chat_button_bg_color', 'product_detail_chat_button_text_color',
                            'product_detail_show_contact_sales', 'product_detail_contact_sales_text', 'product_detail_contact_sales_telegram_url',
                            'computer_detail_contact_sales_telegram_url', 'accessory_detail_contact_sales_telegram_url',
                            'product_detail_contact_sales_new_tab', 'product_detail_contact_sales_bg_color', 'product_detail_contact_sales_text_color'
                        ] as $settingType)
                            <input type="hidden" name="types[]" value="{{ $settingType }}">
                        @endforeach

                        <div class="button-settings-grid">
                        <section class="button-setting-panel">
                        <div class="panel-heading">
                            <div class="d-flex align-items-center">
                                <span class="panel-icon"><i class="las la-bolt fs-18"></i></span>
                                <div>
                                    <h6>{{ translate('Buy Now Button') }}</h6>
                                    <div class="panel-description">{{ translate('Send customers directly to checkout.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center visibility-control">
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
                            <div class="col-12 form-group">
                                <div class="d-flex align-items-center visibility-control">
                                    <input type="hidden" name="product_detail_buy_now_click_message_enabled" value="0">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="checkbox" name="product_detail_buy_now_click_message_enabled" value="1"
                                            @checked((int) get_setting('product_detail_buy_now_click_message_enabled', 0) === 1)>
                                        <span></span>
                                    </label>
                                    <span>{{ translate('Show alert message when clicked') }}</span>
                                </div>
                            </div>
                            <div class="col-12 form-group mb-0">
                                <label>{{ translate('Click Message (Optional)') }}</label>
                                <input type="text" class="form-control" name="product_detail_buy_now_click_message"
                                    value="{{ get_setting('product_detail_buy_now_click_message') }}" maxlength="255"
                                    placeholder="{{ translate('It is coming soon') }}">
                                <small class="text-muted">{{ translate('When the alert status is on, this message appears instead of continuing to checkout.') }}</small>
                            </div>
                        </div>
                        </section>

                        <section class="button-setting-panel">
                        <div class="panel-heading">
                            <div class="d-flex align-items-center">
                                <span class="panel-icon"><i class="las la-shopping-cart fs-18"></i></span>
                                <div>
                                    <h6>{{ translate('Add to Cart Button') }}</h6>
                                    <div class="panel-description">{{ translate('Let customers add the product to their cart.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center visibility-control">
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
                            <div class="col-12 form-group">
                                <div class="d-flex align-items-center visibility-control">
                                    <input type="hidden" name="product_detail_add_to_cart_click_message_enabled" value="0">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="checkbox" name="product_detail_add_to_cart_click_message_enabled" value="1"
                                            @checked((int) get_setting('product_detail_add_to_cart_click_message_enabled', 0) === 1)>
                                        <span></span>
                                    </label>
                                    <span>{{ translate('Show alert message when clicked') }}</span>
                                </div>
                            </div>
                            <div class="col-12 form-group mb-0">
                                <label>{{ translate('Click Message (Optional)') }}</label>
                                <input type="text" class="form-control" name="product_detail_add_to_cart_click_message"
                                    value="{{ get_setting('product_detail_add_to_cart_click_message') }}" maxlength="255"
                                    placeholder="{{ translate('It is coming soon') }}">
                                <small class="text-muted">{{ translate('When the alert status is on, this message appears instead of adding the product to the cart.') }}</small>
                            </div>
                        </div>
                        </section>

                        <section class="button-setting-panel contact-sales-panel">
                        <div class="panel-heading">
                            <div class="d-flex align-items-center">
                                <span class="panel-icon"><i class="lab la-telegram-plane fs-18"></i></span>
                                <div>
                                    <h6>{{ translate('Contact Sales Button') }}</h6>
                                    <div class="panel-description">{{ translate('Connect customers directly with your sales team on Telegram.') }}</div>
                                </div>
                            </div>
                            <span class="badge badge-soft-info px-3 py-2">{{ translate('Telegram') }}</span>
                        </div>
                        <div class="form-group d-flex align-items-center visibility-control">
                            <input type="hidden" name="product_detail_show_contact_sales" value="0">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="product_detail_show_contact_sales" value="1" @checked((int) get_setting('product_detail_show_contact_sales', 1) === 1)>
                                <span></span>
                            </label>
                            <span>{{ translate('Show Contact Sales Button') }}</span>
                        </div>
                        <div class="row gutters-10">
                            <div class="col-md-4 form-group">
                                <label>{{ translate('Button Text') }}</label>
                                <input type="text" class="form-control" name="product_detail_contact_sales_text"
                                    value="{{ get_setting('product_detail_contact_sales_text') ?: translate('Contact Sales') }}"
                                    placeholder="{{ translate('Contact Sales') }}">
                            </div>
                            <div class="col-md-8 form-group">
                                <label>{{ translate('Phone Products Telegram Link') }}</label>
                                <input type="url" class="form-control" name="product_detail_contact_sales_telegram_url"
                                    value="{{ get_setting('product_detail_contact_sales_telegram_url') }}"
                                    placeholder="https://t.me/your_username">
                                <small class="text-muted">{{ translate('Used on regular product pages. Also used as the fallback for computers/accessories below when left empty.') }}</small>
                            </div>
                            <div class="col-md-8 offset-md-4 form-group">
                                <label>{{ translate('Computer Products Telegram Link') }}</label>
                                <input type="url" class="form-control" name="computer_detail_contact_sales_telegram_url"
                                    value="{{ get_setting('computer_detail_contact_sales_telegram_url') }}"
                                    placeholder="https://t.me/your_computer_sales_username">
                                <small class="text-muted">{{ translate('Used on computer detail pages. Leave empty to use the Phone Products link above.') }}</small>
                            </div>
                            <div class="col-md-8 offset-md-4 form-group">
                                <label>{{ translate('Accessory Products Telegram Link') }}</label>
                                <input type="url" class="form-control" name="accessory_detail_contact_sales_telegram_url"
                                    value="{{ get_setting('accessory_detail_contact_sales_telegram_url') }}"
                                    placeholder="https://t.me/your_accessory_sales_username">
                                <small class="text-muted">{{ translate('Used on accessory detail pages. Leave empty to use the Phone Products link above.') }}</small>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Background Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_contact_sales_bg_color"
                                    value="{{ get_setting('product_detail_contact_sales_bg_color', '#ffffff') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Text Color') }}</label>
                                <input type="color" class="form-control p-1" name="product_detail_contact_sales_text_color"
                                    value="{{ get_setting('product_detail_contact_sales_text_color', '#168acd') }}">
                            </div>
                            <div class="col-md-6 form-group d-flex align-items-center pt-md-4">
                                <input type="hidden" name="product_detail_contact_sales_new_tab" value="0">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="checkbox" name="product_detail_contact_sales_new_tab" value="1" @checked((int) get_setting('product_detail_contact_sales_new_tab', 1) === 1)>
                                    <span></span>
                                </label>
                                <span>{{ translate('Open in a new tab') }}</span>
                            </div>
                        </div>
                        </section>

                        <section class="button-setting-panel">
                        <div class="panel-heading">
                            <div class="d-flex align-items-center">
                                <span class="panel-icon"><i class="las la-link fs-18"></i></span>
                                <div>
                                    <h6>{{ translate('Custom Button') }}</h6>
                                    <div class="panel-description">{{ translate('Add an extra button that links to any page.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center visibility-control">
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
                        </section>

                        <section class="button-setting-panel">
                        <div class="panel-heading">
                            <div class="d-flex align-items-center">
                                <span class="panel-icon"><i class="las la-comments fs-18"></i></span>
                                <div>
                                    <h6>{{ translate('Chat Button') }}</h6>
                                    <div class="panel-description">{{ translate('Link to WhatsApp, Messenger, or another chat service.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center visibility-control">
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
                        </section>
                        </div>

                        <div class="settings-actions d-flex align-items-center justify-content-between flex-wrap">
                            <span class="text-muted fs-12 mb-2 mb-sm-0">{{ translate('Changes apply to every product detail page.') }}</span>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="las la-save mr-1"></i>{{ translate('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
