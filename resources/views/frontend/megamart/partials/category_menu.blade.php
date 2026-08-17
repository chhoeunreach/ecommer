<div class="aiz-category-menu bg-white rounded-0 border-top" id="category-sidebar" style="width:270px;">
    <ul class="list-unstyled categories no-scrollbar mb-0 text-left">
        @foreach (get_level_zero_categories()->take(10) as $key => $category)
            @php
                $category_name = $category->getTranslation('name');
                $hasIcon = isset($category->catIcon->file_name) && !empty($category->catIcon->file_name);
            @endphp
            <li class="category-nav-element border border-top-0" data-id="{{ $category->id }}">
                <a href="{{ route('products.category', $category->slug) }}"
                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1 py-2">
                    @if ($hasIcon)
                        <img class="cat-image lazyload mr-2 opacity-80" 
                            src="{{ my_asset($category->catIcon->file_name) }}"
                            data-src="{{ my_asset($category->catIcon->file_name) }}" 
                            width="18" height="18" alt="{{ $category_name }}"
                            onerror="this.style.display='none';">
                    @else
                        <span class="cat-icon-fallback mr-2 text-muted opacity-60 d-inline-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L8.6 3.3A2 2 0 0 0 6.9 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                            </svg>
                        </span>
                    @endif
                    <span class="cat-name has-transition text-truncate">{{ $category_name }}</span>
                </a>
                
                <div class="sub-cat-menu c-scrollbar-light border p-4 shadow-none">
                    <div class="c-preloader text-center absolute-center">
                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
</div>
