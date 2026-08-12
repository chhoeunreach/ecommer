@forelse ($products as $product)
    <label class="d-flex align-items-center border rounded-2 p-3 mb-2 {{ $product->best_seller == 1 ? 'bg-soft-secondary' : '' }}">
        <span class="aiz-checkbox mr-3">
            <input type="checkbox" class="best-seller-product-select" value="{{ $product->id }}"
                @checked($product->best_seller == 1) @disabled($product->best_seller == 1)>
            <span class="aiz-square-check"></span>
        </span>
        <img class="size-48px img-fit rounded mr-3" src="{{ uploaded_asset($product->thumbnail_img) }}"
            alt="{{ $product->getTranslation('name') }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <span class="flex-grow-1">
            <span class="d-block fs-13 fw-600 text-dark">{{ $product->getTranslation('name') }}</span>
            <span class="d-block fs-12 text-secondary mt-1">{{ single_price($product->unit_price) }}</span>
        </span>
        @if ($product->best_seller == 1)
            <span class="badge badge-soft-success">{{ translate('Already Best Seller') }}</span>
        @endif
    </label>
@empty
    <div class="text-center py-5">
        <i class="las la-search fs-36 text-secondary"></i>
        <p class="text-secondary mt-2 mb-0">{{ translate('No products found.') }}</p>
    </div>
@endforelse
