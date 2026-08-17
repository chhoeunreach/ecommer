@if (get_setting('home_categories') != null)
    @php
        $home_categories = json_decode(get_setting('home_categories'));
    @endphp
    @foreach ($home_categories as $key => $value)
        @php
            $category = \App\Models\Category::find($value);
        @endphp
        @if ($category != null && count(get_cached_products($category->id)) > 0)
            <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                <div class="container">
                    <div class="row no-gutters bg-white rounded-2 overflow-hidden shadow-sm">
                        <!-- Category Banner -->
                        <div class="col-4 col-md-5 col-lg-4 col-xl-3">
                            <div class="h-100 position-relative category-banner-wrap">
                                <a href="{{ route('products.category', $category->slug) }}" class="d-block h-100">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($category->banner) }}"
                                        alt="{{ $category->getTranslation('name') }}"
                                        class="img-fit lazyload w-100 h-100">
                                </a>
                                <div class="position-absolute absolute-top-left p-3 p-md-4">
                                    <h4 class="fs-16 fs-md-22 fw-700 text-white mb-2">{{ $category->getTranslation('name') }}</h4>
                                    <a href="{{ route('products.category', $category->slug) }}" class="btn btn-primary btn-sm rounded-pill px-3 fs-12 fw-600">
                                        {{ translate('View All') }} <i class="las la-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Category Products Carousel -->
                        <div class="col-8 col-md-7 col-lg-8 col-xl-9 p-3">
                            <div class="aiz-carousel arrow-x-0 arrow-inactive-none home-category"
                                data-items="5" data-xxl-items="5" data-xl-items="4.5"
                                data-lg-items="3" data-md-items="2" data-sm-items="2"
                                data-xs-items="2" data-arrows="true" data-infinite="false">

                                @foreach (get_cached_products($category->id) as $product_key => $product)
                                <div class="carousel-box px-2 position-relative">
                                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1', ['product' => $product])
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
@endif