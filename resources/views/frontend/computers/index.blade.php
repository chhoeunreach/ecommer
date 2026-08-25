@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Computers') }}@stop
@section('meta_description'){{ translate('Explore our collection of computers and choose the configuration that fits you.') }}@stop

@section('style')
    <link rel="stylesheet" href="{{ static_asset('assets/css/computer-details.css?v=') }}{{ filemtime(public_path('assets/css/computer-details.css')) }}">
@endsection

@section('content')
<main class="computer-catalog-page">
    <div class="computer-catalog-shell">
        <header class="computer-catalog-hero">
            <div class="computer-catalog-hero__copy">
                <span class="computer-catalog-eyebrow"><i class="las la-laptop" aria-hidden="true"></i>{{ translate('Computer collection') }}</span>
                <h1>{{ translate('Find your next computer') }}</h1>
                <p>{{ translate('Compare available models, prices, and configurations in one clean collection.') }}</p>
            </div>
            <span class="computer-catalog-count">
                <strong>{{ $computers->total() }}</strong>
                {{ $computers->total() === 1 ? translate('model') : translate('models') }}
            </span>
        </header>

        @if($computers->isNotEmpty())
            <section class="computer-catalog-grid" aria-label="{{ translate('Available computers') }}">
                @foreach($computers as $computer)
                    @php
                        $rawPrice = $computer->price;
                        $discountedPrice = \App\Utility\CartUtility::discount_calculation($computer, $rawPrice);
                        $stockQuantity = $computer->computer_variants->isNotEmpty()
                            ? (int) $computer->computer_variants->sum('stock')
                            : ($computer->stocks->isNotEmpty() ? (int) $computer->stocks->sum('qty') : (int) $computer->stock);
                        $defaultVariant = $computer->computer_variants->first();
                        $computerImage = $computer->thumbnail_img
                            ? uploaded_asset($computer->thumbnail_img)
                            : static_asset('assets/img/placeholder.jpg');
                    @endphp

                    <article class="computer-catalog-card">
                        <a href="{{ route('computers.show', $computer->id) }}" class="computer-catalog-card__image" aria-label="{{ $computer->name }}">
                            @if($rawPrice != $discountedPrice)
                                <span class="computer-catalog-card__sale">{{ translate('Sale') }}</span>
                            @endif
                            <img src="{{ $computerImage }}" alt="{{ $computer->name }}" loading="lazy">
                        </a>

                        <div class="computer-catalog-card__body">
                            <div class="computer-catalog-card__meta">
                                @if($computer->brand)
                                    <span>{{ $computer->brand->getTranslation('name') }}</span>
                                @endif
                                <span class="{{ $stockQuantity > 0 ? 'is-available' : 'is-unavailable' }}">
                                    <i class="las {{ $stockQuantity > 0 ? 'la-check-circle' : 'la-times-circle' }}" aria-hidden="true"></i>
                                    {{ $stockQuantity > 0 ? translate('In stock') : translate('Out of Stock') }}
                                </span>
                            </div>

                            <h2><a href="{{ route('computers.show', $computer->id) }}">{{ $computer->name }}</a></h2>

                            @if($defaultVariant)
                                <div class="computer-catalog-specs" aria-label="{{ translate('Key specifications') }}">
                                    @if($defaultVariant->chip)<span>{{ $defaultVariant->chip }}</span>@endif
                                    @if($defaultVariant->ram)<span>{{ $defaultVariant->ram }}</span>@endif
                                    @if($defaultVariant->storage)<span>{{ $defaultVariant->storage }}</span>@endif
                                </div>
                            @endif

                            @if($computer->description)
                                <p class="computer-catalog-card__description">{{ \Illuminate\Support\Str::limit(strip_tags($computer->description), 95) }}</p>
                            @endif

                            <div class="computer-catalog-card__footer">
                                <div class="computer-catalog-card__price">
                                    <span>{{ single_price($discountedPrice) }}</span>
                                    @if($rawPrice != $discountedPrice)<del>{{ single_price($rawPrice) }}</del>@endif
                                </div>
                                <a href="{{ route('computers.show', $computer->id) }}" class="computer-catalog-card__action">
                                    <span>{{ translate('View details') }}</span><i class="las la-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            @if($computers->hasPages())
                <nav class="computer-catalog-pagination aiz-pagination" aria-label="{{ translate('Computer pages') }}">
                    {{ $computers->links() }}
                </nav>
            @endif
        @else
            <section class="computer-catalog-empty">
                <span><i class="las la-laptop" aria-hidden="true"></i></span>
                <h2>{{ translate('No computers available yet') }}</h2>
                <p>{{ translate('Please check again soon for new models.') }}</p>
                <a href="{{ route('home') }}">{{ translate('Return home') }}</a>
            </section>
        @endif
    </div>
</main>
@endsection
