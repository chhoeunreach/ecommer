@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="h3">{{ translate('Add Phone Library Data') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0 h6">{{ translate('Phone Brand') }}</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('phone-library.brands.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Name') }}</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Country') }}</label>
                            <input type="text" name="country" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Website') }}</label>
                            <input type="url" name="website" class="form-control">
                        </div>
                        <button class="btn btn-primary" type="submit">{{ translate('Save Brand') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0 h6">{{ translate('Phone Model') }}</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('phone-library.models.store') }}">
                        @csrf
                        <div class="row gutters-10">
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Brand') }}</label>
                                <select name="phone_brand_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Model Name') }}</label>
                                <input type="text" name="model_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Marketing Name') }}</label>
                                <input type="text" name="marketing_name" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Year') }}</label>
                                <input type="number" name="year_released" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>{{ translate('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="active">{{ translate('Active') }}</option>
                                    <option value="discontinued">{{ translate('Discontinued') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Model Number') }}</label>
                                <input type="text" name="model_number" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ translate('Category') }}</label>
                                <input type="text" name="category" value="Smartphones" class="form-control">
                            </div>
                            <input type="hidden" name="product_type" value="mobile_phone">
                            <div class="col-12 form-group">
                                <label>{{ translate('Description') }}</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ translate('Save Model') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
