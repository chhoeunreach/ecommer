@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('WhatsApp Chat Setting')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('whatsapp_chat.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Chat')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="whatsapp_chat" type="checkbox" @if (get_setting('whatsapp_chat') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Order')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0 ">
                                    <input value="1" id="whatsapp_order_switch" name="whatsapp_order" type="checkbox" @if (get_setting('whatsapp_order') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row message-area">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('For Seller Products')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0 ">
                                    <input value="1" id="whatsapp_order_seller_prods" name="whatsapp_order_seller_prods" type="checkbox" @if (get_setting('whatsapp_order_seller_prods') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                                <br>
                                <small class="text-info">{{translate('Get WhatsApp Order for Seller products')}}</small>
                            </div>
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('Order Message')}}</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" rows="5" placeholder="{{translate('Order Message')}}" name="order_messege_template">{{get_setting('order_messege_template')}}</textarea>
                            </div>

                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="WHATSAPP_NUMBER">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Number')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="WHATSAPP_NUMBER" value="{{  env('WHATSAPP_NUMBER') }}" placeholder="{{ translate('WhatsApp Number') }}" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-600 mb-3">{{ translate('Floating Chat Button') }}</h6>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{ translate('Show Chat Now Button') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" id="floating_chat_button" name="floating_chat_button" type="checkbox"
                                        @if (get_setting('floating_chat_button') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                                <small class="d-block mt-2 text-muted">
                                    {{ translate('Adds Chat Now to the storefront floating action buttons.') }}
                                </small>
                            </div>
                        </div>

                        <div class="floating-chat-fields">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="col-from-label">{{ translate('Button Label') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="floating_chat_label"
                                        value="{{ get_setting('floating_chat_label', 'Chat Now') }}"
                                        placeholder="{{ translate('Chat Now') }}" maxlength="40">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="col-from-label">{{ translate('Chat URL') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="url" class="form-control" name="floating_chat_url"
                                        value="{{ get_setting('floating_chat_url') }}"
                                        placeholder="https://wa.me/855...">
                                    <small class="text-muted">
                                        {{ translate('Leave blank to use the WhatsApp number above. You can also use a Messenger or Telegram link.') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        function toggleMessageArea() {
            if ($('#whatsapp_order_switch').is(':checked')) {
                $('.message-area').removeClass('d-none');
            } else {
                $('.message-area').addClass('d-none');
            }
        }

        toggleMessageArea();

        $('#whatsapp_order_switch').on('change', function () {
            toggleMessageArea();
        });

        function toggleFloatingChatFields() {
            $('.floating-chat-fields').toggleClass('d-none', !$('#floating_chat_button').is(':checked'));
        }

        toggleFloatingChatFields();
        $('#floating_chat_button').on('change', toggleFloatingChatFields);
    });
</script>

@endsection
