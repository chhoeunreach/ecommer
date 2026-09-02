@extends('frontend.layouts.app')

@section('content')
    <!-- Header Section -->
    <section class="pt-4 pt-md-5 pb-3">
        <div class="container">
            <div class="d-flex align-items-center mb-2">
                <h1 class="fw-800 fs-20 fs-md-28 text-dark mb-0" style="letter-spacing: -0.5px;">{{$title}} {{ translate('Products') }}</h1>
            </div>
            <div class="w-100 h-1px bg-gray-200 mt-3 mb-4"></div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="pb-5 mb-4">
        <div class="container">
            <div class="row row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 gutters-10 gutters-md-16">
                @foreach ($best_selling_products as $key => $product)
                    <div class="col mb-3 mb-md-4">
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                    </div>
                @endforeach
            </div>
            
            @if(isset($has_pagination) && $has_pagination)
            <div class="aiz-pagination mt-4">
                {{ $best_selling_products->links() }}
            </div>
            @endif
        </div>
    </section>
@endsection
