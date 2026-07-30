@if (count($newest_products) > 0)
    @if (!request()->has('page'))
        <div class="container">
            <div class="kystore-product-grid" id="newest-products-list">
    @endif
                @foreach ($newest_products as $newProduct)
                    @include('frontend.kystor.partials.home_product_box', ['product' => $newProduct])
                @endforeach
    @if (!request()->has('page'))
            </div>
        </div>
    @endif
@endif
