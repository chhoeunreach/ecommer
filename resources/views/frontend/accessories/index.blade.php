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
    .shadcn-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .shadcn-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }
    .shadcn-card-img-wrapper {
        width: 100%;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        position: relative;
        background-color: #f1f5f9;
    }
    .shadcn-card-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .shadcn-card-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .shadcn-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .shadcn-card-desc {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .shadcn-card-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    .shadcn-btn-outline {
        display: inline-block;
        width: 100%;
        text-align: center;
        background: transparent;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        margin-top: 1rem;
    }
    .shadcn-btn-outline:hover {
        background: #f1f5f9;
        text-decoration: none;
        color: #0f172a;
    }
</style>

<section class="shadcn-section">
    <div class="container">
        <div class="text-center">
            <h1 class="shadcn-header-title">{{ translate('Our Accessories') }}</h1>
            <p class="shadcn-header-subtitle">{{ translate('Explore our premium collection of accessories designed just for you.') }}</p>
        </div>

        <div class="row gutters-16">
            @forelse ($accessories as $accessory)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="shadcn-card">
                        <div class="shadcn-card-img-wrapper">
                            @if ($accessory->thumbnail_img != null)
                                <img src="{{ uploaded_asset($accessory->thumbnail_img) }}" alt="{{ $accessory->name }}" class="shadcn-card-img">
                            @else
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $accessory->name }}" class="shadcn-card-img">
                            @endif
                        </div>
                        <div class="shadcn-card-content">
                            <h3 class="shadcn-card-title">{{ $accessory->name }}</h3>
                            <div class="shadcn-card-desc">
                                {{ strip_tags($accessory->description) }}
                            </div>
                            <div class="d-flex align-items-center mt-auto">
                                @php
                                    $price = $accessory->price;
                                    $discounted_price = $price;
                                    if($accessory->discount > 0) {
                                        if($accessory->discount_type == 'percent') {
                                            $discounted_price -= ($price * $accessory->discount) / 100;
                                        } elseif($accessory->discount_type == 'amount') {
                                            $discounted_price -= $accessory->discount;
                                        }
                                    }
                                @endphp
                                <span class="shadcn-card-price">{{ single_price($discounted_price) }}</span>
                                @if ($price != $discounted_price)
                                    <del class="fs-13 text-gray fw-400 ml-2">{{ single_price($price) }}</del>
                                @endif
                            </div>
                            <a href="{{ route('accessories.show', $accessory->id) }}" class="shadcn-btn-outline">{{ translate('View Details') }}</a>
                        </div>
                    </div>
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
