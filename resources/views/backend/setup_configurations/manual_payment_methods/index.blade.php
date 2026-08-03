@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Manual Payment Methods') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                @can('add_manual_payment_method')
                    <a href="{{ route('manual_payment_methods.create') }}" class="btn btn-circle btn-info">
                        <span>{{ translate('Add New Method') }}</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Manual Payment Methods') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Logo') }}</th>
                                <th>{{ translate('Heading') }}</th>
                                <th style="text-align: right;">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manual_payment_methods as $key => $method)
                                <tr>
                                    <td>
                                        {{ $manual_payment_methods->firstItem() + $key }}
                                    </td>
                                    <td>
                                        <img src="{{ uploaded_asset($method->photo) }}" alt="{{ $method->heading }}" class="h-50px">
                                    </td>
                                    <td>{{ $method->heading }}</td>
                                    <td style="text-align: right;">
                                        @can('edit_manual_payment_method')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('manual_payment_methods.edit', $method->id) }}" title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_manual_payment_method')
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('manual_payment_methods.destroy', $method->id) }}" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $manual_payment_methods->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
