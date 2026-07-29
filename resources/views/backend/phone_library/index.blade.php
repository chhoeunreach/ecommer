@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Phone Library') }}</h1>
            </div>
            <div class="col text-right">
                @can('phone_library.create')
                    <a href="{{ route('phone-library.create') }}" class="btn btn-primary">
                        <i class="las la-plus"></i> {{ translate('Add Phone') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('phone-library.index') }}">
                <div class="row gutters-10">
                    <div class="col-md-2 mb-2">
                        <input type="text" name="brand" value="{{ request('brand') }}" class="form-control" placeholder="{{ translate('Brand') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="model" value="{{ request('model') }}" class="form-control" placeholder="{{ translate('Model') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="storage" value="{{ request('storage') }}" class="form-control" placeholder="{{ translate('Storage') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="color" value="{{ request('color') }}" class="form-control" placeholder="{{ translate('Color') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="year" value="{{ request('year') }}" class="form-control" placeholder="{{ translate('Year') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="las la-search"></i> {{ translate('Search') }}
                        </button>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="has_5g" class="form-control aiz-selectpicker">
                            <option value="">{{ translate('5G') }}</option>
                            <option value="1" @selected(request('has_5g') === '1')>{{ translate('Yes') }}</option>
                            <option value="0" @selected(request('has_5g') === '0')>{{ translate('No') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="chipset" value="{{ request('chipset') }}" class="form-control" placeholder="{{ translate('Chipset') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="display_size" value="{{ request('display_size') }}" class="form-control" placeholder="{{ translate('Display Size') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control aiz-selectpicker">
                            <option value="">{{ translate('Status') }}</option>
                            <option value="active" @selected(request('status') === 'active')>{{ translate('Active') }}</option>
                            <option value="discontinued" @selected(request('status') === 'discontinued')>{{ translate('Discontinued') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('phone-library.index') }}" class="btn btn-soft-secondary btn-block">{{ translate('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Phone') }}</th>
                            <th>{{ translate('Year') }}</th>
                            <th>{{ translate('Display') }}</th>
                            <th>{{ translate('Chipset') }}</th>
                            <th>{{ translate('5G') }}</th>
                            <th>{{ translate('Variants') }}</th>
                            <th class="text-right">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($models as $phone)
                            <tr>
                                <td>
                                    <div class="fw-700">{{ optional($phone->brand)->name }} {{ $phone->marketing_name ?: $phone->model_name }}</div>
                                    <div class="text-muted">{{ $phone->model_number }} · {{ ucfirst($phone->status) }}</div>
                                </td>
                                <td>{{ $phone->year_released }}</td>
                                <td>{{ optional($phone->specification)->display_size }}</td>
                                <td>{{ optional($phone->specification)->chipset }}</td>
                                <td>{{ optional($phone->specification)->has_5g ? translate('Yes') : translate('No') }}</td>
                                <td>{{ $phone->variants_count }}</td>
                                <td class="text-right">
                                    <a href="{{ route('phone-library.show', $phone->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Preview') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                    @can('phone_library.delete')
                                        <form method="POST" action="{{ route('phone-library.models.destroy', $phone) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-alert" data-href="{{ route('phone-library.models.destroy', $phone) }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">{{ translate('No phone models found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $models->links() }}
        </div>
    </div>
@endsection
