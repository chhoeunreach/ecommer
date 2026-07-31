@if (get_setting('home_categories') != null)
    @php
        $homeCategoryIds = json_decode(get_setting('home_categories'));
        $homeCategories = get_category($homeCategoryIds);
    @endphp

    @foreach ($homeCategories as $category)
        @php
            $categoryName = $category->getTranslation('name');
            $categoryProducts = get_cached_products($category->id);
        @endphp
        @if (count($categoryProducts) > 0)
            <section>
                <div class="container">
                    <div class="kystore-category-feature">
                        <div class="row gutters-16">
                            <div class="col-lg-3">
                                <a href="{{ route('products.category', $category->slug) }}"
                                    class="kystore-category-feature-banner">
                                    <img class="lazyload"
                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder-rect.jpg') }}"
                                        alt="{{ $categoryName }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    <span class="kystore-hero-shade"></span>
                                    <span class="kystore-category-feature-copy">
                                        <strong>{{ $categoryName }}</strong>
                                        <span>{{ translate('Shop category') }} <i class="las la-arrow-right"></i></span>
                                    </span>
                                </a>
                            </div>
                            <div class="col-lg-9 mt-3 mt-lg-0">
                                <div class="kystore-section-heading">
                                    <div>
                                        <span class="kystore-eyebrow">{{ translate('Curated collection') }}</span>
                                        <h2>{{ $categoryName }}</h2>
                                    </div>
                                    <a href="{{ route('products.category', $category->slug) }}" class="kystore-text-link">
                                        {{ translate('View all') }} <i class="las la-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="aiz-carousel kystore-product-carousel gutters-10" data-items="4"
                                    data-xl-items="4" data-lg-items="3" data-md-items="3" data-sm-items="2"
                                    data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false">
                                    @foreach ($categoryProducts as $product)
                                        <div class="carousel-box">
                                            @include('frontend.kystor.partials.product_box_1', ['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
@endif
