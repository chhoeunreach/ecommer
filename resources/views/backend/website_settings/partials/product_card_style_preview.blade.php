{{-- Static sample card used only to preview product-card style variants; no real product data needed. --}}
<div class="aiz-card-box shadcn-product-card">
    <div class="shadcn-card-img-wrap">
        <a href="javascript:void(0)" class="d-block h-100 position-relative">
            <img class="mx-auto product-main-image" src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ translate('Sample product') }}">
        </a>

        <div class="shadcn-badges-wrap">
            <span class="shadcn-badge shadcn-badge-discount">-15%</span>
        </div>

        <div class="shadcn-actions-wrap">
            <a href="javascript:void(0)" class="shadcn-action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
            </a>
            <a href="javascript:void(0)" class="shadcn-action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3 14 10"/><path d="M3 21l7-7"/>
                </svg>
            </a>
        </div>
    </div>

    <div class="shadcn-card-body">
        <div class="shadcn-card-content">
            <h3 class="shadcn-product-title">
                <a href="javascript:void(0)">{{ translate('Sample Product Name') }}</a>
            </h3>
            <div class="ky-product-variant">{{ translate('New') }}</div>

            <div class="shadcn-price-container">
                <span class="shadcn-price-current">$249.00</span>
                <span class="shadcn-price-old">$299.00</span>
            </div>
        </div>

        <div class="shadcn-card-action">
            <a class="shadcn-cta-btn" href="javascript:void(0)">
                <i class="las la-shopping-cart"></i>
                <span>{{ translate('Select Options') }}</span>
            </a>
        </div>
    </div>
</div>
