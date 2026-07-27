@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ optional($model->brand)->name }} {{ $model->marketing_name ?: $model->model_name }}</h1>
            </div>
            <div class="col text-right">
                <a href="{{ route('phone-library.index') }}" class="btn btn-soft-secondary">{{ translate('Back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    @php $primaryImage = $model->primaryImage ?: $model->images->first(); @endphp
                    @if ($primaryImage)
                        <img src="{{ Storage::disk(config('phone_library.image_disk'))->url($primaryImage->path) }}" class="img-fluid rounded mb-3" alt="{{ $model->model_name }}">
                    @else
                        <div class="bg-soft-secondary rounded d-flex align-items-center justify-content-center mb-3" style="height:260px;">
                            <i class="las la-mobile-alt la-4x text-muted"></i>
                        </div>
                    @endif
                    <p class="mb-1"><strong>{{ translate('Released') }}:</strong> {{ $model->year_released }}</p>
                    <p class="mb-1"><strong>{{ translate('Model Number') }}:</strong> {{ $model->model_number }}</p>
                    <p class="mb-0"><strong>{{ translate('Status') }}:</strong> {{ ucfirst($model->status) }}</p>
                </div>
            </div>

            @can('phone_library.import')
                <div class="card">
                    <div class="card-header"><h5 class="mb-0 h6">{{ translate('Add Image') }}</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('phone-library.images.store') }}">
                            @csrf
                            <input type="hidden" name="phone_model_id" value="{{ $model->id }}">
                            <div class="form-group">
                                <label>{{ translate('Type') }}</label>
                                <select name="type" class="form-control">
                                    <option value="main">{{ translate('Main') }}</option>
                                    <option value="front">{{ translate('Front') }}</option>
                                    <option value="back">{{ translate('Back') }}</option>
                                    <option value="side">{{ translate('Side') }}</option>
                                    <option value="lifestyle">{{ translate('Lifestyle') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Source URL') }}</label>
                                <input type="url" name="source_url" class="form-control">
                            </div>
                            <button class="btn btn-primary btn-sm">{{ translate('Queue Download') }}</button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0 h6">{{ translate('Specifications') }}</h5></div>
                <div class="card-body">
                    @php $spec = $model->specification; @endphp
                    <div class="row">
                        @foreach ([
                            'display_size' => 'Display Size',
                            'display_resolution' => 'Resolution',
                            'refresh_rate' => 'Refresh Rate',
                            'brightness' => 'Brightness',
                            'display_protection' => 'Protection',
                            'chipset' => 'Chipset',
                            'cpu' => 'CPU',
                            'gpu' => 'GPU',
                            'ram' => 'RAM',
                            'storage' => 'Storage',
                            'front_camera' => 'Front Camera',
                            'video_recording' => 'Video',
                            'battery_capacity' => 'Battery',
                            'charging_speed' => 'Charging',
                            'wifi' => 'WiFi',
                            'bluetooth' => 'Bluetooth',
                            'usb_type' => 'USB',
                            'operating_system' => 'OS',
                            'dimensions' => 'Dimensions',
                            'weight' => 'Weight',
                            'sim_type' => 'SIM',
                            'water_resistance' => 'Water Resistance',
                            'warranty' => 'Warranty',
                        ] as $field => $label)
                            <div class="col-md-6 mb-2">
                                <span class="text-muted">{{ translate($label) }}:</span>
                                <span class="fw-600">{{ optional($spec)->{$field} ?: '-' }}</span>
                            </div>
                        @endforeach
                        <div class="col-md-6 mb-2"><span class="text-muted">{{ translate('5G') }}:</span> <span class="fw-600">{{ optional($spec)->has_5g ? translate('Yes') : translate('No') }}</span></div>
                        <div class="col-md-6 mb-2"><span class="text-muted">{{ translate('NFC') }}:</span> <span class="fw-600">{{ optional($spec)->nfc ? translate('Yes') : translate('No') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0 h6">{{ translate('Available Variants') }}</h5></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Color') }}</th>
                                <th>{{ translate('Storage') }}</th>
                                <th>{{ translate('RAM') }}</th>
                                <th>{{ translate('SKU Template') }}</th>
                                <th>{{ translate('Selling Price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model->variants as $variant)
                                <tr>
                                    <td>{{ $variant->color }}</td>
                                    <td>{{ $variant->storage }}</td>
                                    <td>{{ $variant->ram }}</td>
                                    <td>{{ $variant->sku_template }}</td>
                                    <td>{{ $variant->currency }} {{ $variant->selling_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @can('phone_library.create')
                <div class="card">
                    <div class="card-header"><h5 class="mb-0 h6">{{ translate('Add Variant') }}</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('phone-library.variants.store') }}">
                            @csrf
                            <input type="hidden" name="phone_model_id" value="{{ $model->id }}">
                            <div class="row gutters-10">
                                <div class="col-md-3 form-group"><input name="color" class="form-control" placeholder="{{ translate('Color') }}" required></div>
                                <div class="col-md-3 form-group"><input name="storage" class="form-control" placeholder="{{ translate('Storage') }}" required></div>
                                <div class="col-md-2 form-group"><input name="ram" class="form-control" placeholder="{{ translate('RAM') }}"></div>
                                <div class="col-md-2 form-group"><input name="selling_price" type="number" step="0.01" class="form-control" placeholder="{{ translate('Price') }}"></div>
                                <div class="col-md-2 form-group"><button class="btn btn-primary btn-block">{{ translate('Add') }}</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
