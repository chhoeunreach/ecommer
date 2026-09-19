@extends('backend.layouts.app')

@section('content')
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Add Pre-order / Coming Soon Product') }}</h1>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem mb-4">
            <form action="{{ route('admin.upcoming-products.store') }}" method="POST" id="aizSubmitForm">
                @csrf
                @include('backend.upcoming_products._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('backend.upcoming_products._form_assets')
@endsection
