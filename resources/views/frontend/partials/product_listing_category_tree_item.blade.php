@php
    $categoryName = $categoryItem->getTranslation('name');
    $categoryCount = (int) ($categoryItem->products_count ?? 0);
    $categoryChildren = $categoryItem->childrenCategories ?? collect();
    $categoryInputId = 'category_checkid' . $idPrefix . $categoryItem->id;

    $containsSelected = function ($item) use (&$containsSelected, $selectedIds) {
        if (in_array($item->id, $selectedIds ?? [])) {
            return true;
        }
        foreach ($item->childrenCategories ?? collect() as $child) {
            if ($containsSelected($child)) {
                return true;
            }
        }
        return false;
    };

    $branchExpanded = $containsSelected($categoryItem);
@endphp

<li class="product-category-tree__item">
    <div class="product-category-tree__row">
        @if ($categoryChildren->isNotEmpty())
            <button class="product-category-tree__toggle" type="button"
                aria-label="{{ translate('Toggle subcategories') }}"
                aria-expanded="{{ $branchExpanded ? 'true' : 'false' }}">
                <i class="las la-angle-down" aria-hidden="true"></i>
            </button>
        @else
            <span class="product-category-tree__toggle-spacer" aria-hidden="true"></span>
        @endif

        <label class="product-category-tree__label mb-0"
            for="{{ $categoryInputId }}">
            <input id="{{ $categoryInputId }}" type="checkbox" name="{{ $inputName }}"
                value="{{ $categoryItem->id }}" @checked(in_array($categoryItem->id, $selectedIds ?? []))
                onchange="filter(event)">
            <span class="product-category-tree__check" aria-hidden="true"></span>
            <span id="category_checkid_text{{ $idPrefix }}{{ $categoryItem->id }}"
                class="product-category-tree__name @if (in_array($categoryItem->id, $selectedIds ?? [])) fw-bold text-primary @endif">
                {{ $categoryName }}
            </span>
            @if ($categoryCount > 0)
                <span class="product-category-tree__count">{{ $categoryCount }}</span>
            @endif
        </label>
    </div>

    @if ($categoryChildren->isNotEmpty())
        <ul class="product-category-tree__children" @if (!$branchExpanded) hidden @endif>
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
