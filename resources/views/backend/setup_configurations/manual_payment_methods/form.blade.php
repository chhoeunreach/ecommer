@php
    $bank = null;
    if (isset($manual_payment_method) && $manual_payment_method->bank_info) {
        $bank_info = json_decode($manual_payment_method->bank_info);
        $bank = $bank_info[0] ?? null;
    }
@endphp

<div class="form-group">
    <label>{{ translate('Heading') }} <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="heading" maxlength="255" required
        value="{{ isset($manual_payment_method) ? $manual_payment_method->heading : old('heading') }}"
        placeholder="{{ translate('e.g. Bank Transfer') }}">
</div>

<div class="form-group">
    <label>{{ translate('Description') }}</label>
    <textarea name="description" rows="4" class="form-control">{{ isset($manual_payment_method) ? $manual_payment_method->description : old('description') }}</textarea>
    <small class="text-muted">{{ translate('Shown to the customer when this payment method is selected at checkout.') }}</small>
</div>

<div class="form-group">
    <label>{{ translate('Logo') }}</label>
    <div class="input-group" data-toggle="aizuploader" data-type="image">
        <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
        </div>
        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
        <input type="hidden" name="photo" class="selected-files" value="{{ isset($manual_payment_method) ? $manual_payment_method->photo : old('photo') }}">
    </div>
    <div class="file-preview box sm">
        @if (isset($manual_payment_method) && $manual_payment_method->photo)
            <img src="{{ uploaded_asset($manual_payment_method->photo) }}" class="h-100px">
        @endif
    </div>
</div>

<h6 class="mt-4 mb-3">{{ translate('Bank Account Info') }} <small class="text-muted">({{ translate('optional') }})</small></h6>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ translate('Bank Name') }}</label>
            <input type="text" class="form-control" name="bank_name" value="{{ $bank->bank_name ?? old('bank_name') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ translate('Account Name') }}</label>
            <input type="text" class="form-control" name="account_name" value="{{ $bank->account_name ?? old('account_name') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ translate('Account Number') }}</label>
            <input type="text" class="form-control" name="account_number" value="{{ $bank->account_number ?? old('account_number') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ translate('Routing Number') }}</label>
            <input type="text" class="form-control" name="routing_number" value="{{ $bank->routing_number ?? old('routing_number') }}">
        </div>
    </div>
</div>
