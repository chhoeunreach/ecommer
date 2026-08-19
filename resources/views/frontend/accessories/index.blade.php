@extends('frontend.layouts.app')

@section('content')
<style>
    /* Shadcn UI Inspired Styles for Frontend */
    .shadcn-section {
        background-color: #f8fafc;
        min-height: calc(100vh - 200px);
        padding: 4rem 0;
    }
    .shadcn-header-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 0.5rem;
    }
    .shadcn-header-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        margin-bottom: 3rem;
    }
</style>

<section class="shadcn-section">
    <div class="container">
        <div class="text-center">
            <h1 class="shadcn-header-title">{{ translate('Our Accessories') }}</h1>
            <p class="shadcn-header-subtitle">{{ translate('Explore our premium collection of accessories designed just for you.') }}</p>
        </div>

        <div class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">
            @forelse ($accessories as $accessory)
                <div class="col listing-product-card">
                    @include('frontend.' . get_setting('homepage_select') . '.partials.home_accessory_box', ['accessory' => $accessory])
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">{{ translate('No accessories found.') }}</h5>
                </div>
            @endforelse
        </div>

        <div class="aiz-pagination mt-4">
            {{ $accessories->links() }}
        </div>
    </div>
</section>

@endsection
