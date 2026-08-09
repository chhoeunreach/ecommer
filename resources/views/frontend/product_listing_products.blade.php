@forelse ($products as $key => $product)
    <div class="col listing-product-card">
        @if (isset($product_type) && $product_type == 'preorder_product')
            @include('preorder.frontend.product_box3', [
                'product' => $product,
            ])
        @else
            @include(
                'frontend.product_box_for_listing_page',
                ['product' => $product]
            )
        @endif
    </div>
@empty
    <div class="col-12 listing-empty-state">
        <i class="las la-search la-3x mb-3"></i>
        <h3 class="fs-18 fw-700 mb-1">{{ translate('No products found') }}</h3>
        <p class="fs-13 text-muted mb-0">{{ translate('Try changing or clearing some filters.') }}</p>
    </div>
@endforelse
