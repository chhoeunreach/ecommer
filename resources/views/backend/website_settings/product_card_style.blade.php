@extends('backend.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=') }}{{ filemtime(public_path('assets/css/custom-style.css')) }}">
    @if(file_exists(public_path('assets/css/product-card-styles.css')))
    <link rel="stylesheet" href="{{ static_asset('assets/css/product-card-styles.css?v=') }}{{ filemtime(public_path('assets/css/product-card-styles.css')) }}">
    @endif
    <style>
        .pcs-page .pcs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .pcs-page .pcs-option {
            position: relative;
            padding: 14px 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            background: #f8f9fb;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .pcs-page .pcs-option:hover {
            border-color: #94a3b8;
        }

        .pcs-page .pcs-option.is-selected {
            border-color: #3390f3;
            box-shadow: 0 0 0 3px rgba(51, 144, 243, .15);
            background: #f2f8ff;
        }

        .pcs-page .pcs-option input[type="radio"] {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 18px;
            height: 18px;
            accent-color: #3390f3;
        }

        .pcs-page .pcs-option-label {
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 700;
            color: #1f2430;
        }

        /* Scale the live preview card down a bit and neutralise the page
           background it expects so it reads correctly inside a small tile. */
        .pcs-page .pcs-preview-wrap {
            max-width: 200px;
            margin: 0 auto;
        }

        .pcs-page .shadcn-product-card {
            max-width: 200px !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-content pcs-page">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Product Card Style') }}</h1>
                    <p class="text-muted mb-0">{{ translate('Choose the visual design language for product cards shown across the storefront (Best Selling, Featured Products, Today\'s Deals, and similar sections).') }}</p>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem mb-4">
            <form action="{{ route('website.product_card_style.update') }}" method="POST">
                @csrf

                @php
                    $styleOptions = [
                        'default' => translate('Default'),
                        'minimalism' => translate('Minimalism'),
                        'maximalism' => translate('Maximalism'),
                        'flat' => translate('Flat Design'),
                        'neumorphism' => translate('Neumorphism'),
                        'claymorphism' => translate('Claymorphism'),
                    ];
                @endphp

                <div class="pcs-grid mb-4">
                    @foreach ($styleOptions as $styleKey => $styleLabel)
                        <label class="pcs-option {{ $activeStyle === $styleKey ? 'is-selected' : '' }}" data-pcs-option>
                            <input type="radio" name="product_card_style" value="{{ $styleKey }}"
                                @checked($activeStyle === $styleKey)>
                            <div class="pcs-option-label">{{ $styleLabel }}</div>
                            <div class="pcs-preview-wrap" data-card-style="{{ $styleKey }}">
                                @include('backend.website_settings.partials.product_card_style_preview')
                            </div>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="las la-save mr-1"></i>{{ translate('Save Changes') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('[data-pcs-option]').forEach(function (option) {
            option.addEventListener('click', function () {
                document.querySelectorAll('[data-pcs-option]').forEach(function (el) {
                    el.classList.remove('is-selected');
                });
                option.classList.add('is-selected');
            });
        });
    </script>
@endsection
