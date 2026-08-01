@php
    $categoryName = $categoryItem->getTranslation('name');
    $categoryCount = (int) ($categoryItem->products_count ?? 0);
    $categoryChildren = $categoryItem->childrenCategories ?? collect();
    $categoryInputId = 'category_checkid' . $idPrefix . $categoryItem->id;
@endphp

<li class="product-category-tree__item">
    <div class="product-category-tree__row">
        @if ($categoryChildren->isNotEmpty())
            <button class="product-category-tree__toggle" type="button"
                aria-label="{{ translate('Toggle subcategories') }}" aria-expanded="true">
                <i class="las la-angle-down" aria-hidden="true"></i>
            </button>
        @else
            <span class="product-category-tree__toggle-spacer" aria-hidden="true"></span>
        @endif

        <label class="product-category-tree__label" for="{{ $categoryInputId }}">
            <input id="{{ $categoryInputId }}" type="checkbox" name="{{ $inputName }}"
                value="{{ $categoryItem->id }}" @checked(in_array($categoryItem->id, $selectedIds ?? []))
                onchange="filter(event)">
            <span class="product-category-tree__check" aria-hidden="true"></span>
            <span id="category_checkid_text{{ $idPrefix }}{{ $categoryItem->id }}"
                class="product-category-tree__name @if (in_array($categoryItem->id, $selectedIds ?? [])) fw-bold @endif">
                {{ $categoryName }}
            </span>
            @if ($categoryCount > 0)
                <span class="product-category-tree__count">{{ $categoryCount }}</span>
            @endif
        </label>
    </div>

    @if ($categoryChildren->isNotEmpty())
        <ul class="product-category-tree__children">
            @foreach ($categoryChildren as $childCategory)
                @include('frontend.partials.product_listing_category_tree_item', [
                    'categoryItem' => $childCategory,
                    'inputName' => $inputName,
                    'idPrefix' => $idPrefix,
                    'selectedIds' => $selectedIds ?? [],
                ])
            @endforeach
        </ul>
    @endif
</li>
