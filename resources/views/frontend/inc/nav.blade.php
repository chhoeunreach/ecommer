<!-- Top Bar Banner -->
@php
    $top_banner_background_color = get_setting('top_banner_background_color', get_setting('base_color'));
    $top_banner_text_color = get_setting('top_banner_text_color');
    $top_banner_image = get_setting('top_banner_image');
    $top_banner_image_for_tabs = get_setting('top_banner_image_for_tabs');
    $top_banner_image_for_mobile = get_setting('top_banner_image_for_mobile');
    $topBanners = \App\Models\TopBanner::where('status', 1)->orderBy('id','desc')->get();
@endphp 
    @if (count($topBanners) > 0 || $top_banner_image != null)
    <div class="position-relative top-banner removable-session z-1035 d-none" 
         data-key="top-banner" data-value="removed" style="background-color: {{ $top_banner_background_color }}">
        <div class="d-block text-reset h-40px h-lg-60px position-relative overflow-hidden">

            @if($top_banner_image != null)
            <!-- For Large device -->
            <img src="{{ uploaded_asset($top_banner_image)  }}"
                class="d-none d-xl-block img-fit h-100 w-100" alt="{{ translate('top_banner') }}">

            <!-- For Medium device -->
            <img src="{{ uploaded_asset($top_banner_image_for_tabs ?? $top_banner_image)  }}"
                class="d-none d-md-block d-xl-none img-fit h-100 w-100" alt="{{ translate('top_banner') }}">

            <!-- For Small device -->
            <img src="{{ uploaded_asset($top_banner_image_for_mobile ?? $top_banner_image) }}"
                class="d-md-none img-fit h-100 w-100" alt="{{ translate('top_banner') }}">
            @endif

            <!-- Scroll Text -->
            <div class="top-banner-scroll-text position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                <div class="@if (get_setting('show_full_width_top_bar') == 1) layout-container mx-auto px-3 @else container @endif">
                    <div class="overflow-hidden">
                        <div class="top-banner-scroll-inner">
                            @foreach ($topBanners as $banner)
                                <a href="{{ $banner->link ?? '#' }}" style="color: {{$top_banner_text_color}};"
                                    class="{{ $banner->link ? 'has-link' : 'no-link' }}">
                                    {{ $banner->getTranslation('text') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn text-white h-100 absolute-top-right set-session" 
            data-key="top-banner" data-value="removed"
            data-toggle="remove-parent" data-parent=".top-banner">
            <i style="color: {{$top_banner_text_color}};" class="la la-close la-2x"></i>
        </button>
    </div>
    @endif
	<div class="ky-existing-site-header @if(get_setting('homepage_select') == 'kneayerng_v1') d-none d-md-block @endif">
		@include('header.' .get_element_type_by_id(get_setting('header_element')))
	</div>

@if (get_setting('homepage_select') == 'kneayerng_v1')
    <style>
        .ky-mobile-app-header {
            background-color: #ffffff !important;
        }
    </style>
    @php
        $mobileNavCategories = get_level_zero_categories()->take(5);
        $mobileCartCount = count(get_user_cart());
        $activeMobileCategorySlug = request()->routeIs('products.category')
            ? request()->route('category_slug')
            : null;
        $isComputerCatalogRoute = request()->routeIs('computers.*');
        $isMobileHomeRoute = request()->routeIs('home');
        $isMobileAllCategoriesRoute = request()->routeIs('categories.all');
    @endphp
    <header class="ky-mobile-app-header d-md-none">
        <div class="ky-mobile-header-bar">
            <button type="button" class="ky-mobile-header-button" aria-label="{{ translate('Open menu') }}"
                data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar">
                <i class="las la-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="ky-mobile-brand" aria-label="{{ translate('Home') }}">
                @php
                    $header_logo = get_setting('header_logo');
                @endphp
                @if ($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" height="40" class="mw-100">
                @else
                    <span class="ky-mobile-brand-mark">A</span>
                    <span class="ky-mobile-brand-name">ACTIVE <strong>ECOMMERCE CMS</strong></span>
                @endif
            </a>
            <a href="{{ route('cart') }}" class="ky-mobile-header-button ky-mobile-cart-button" aria-label="{{ translate('Cart') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9.5" cy="19.5" r="1.25" fill="currentColor"/>
                    <circle cx="17.5" cy="19.5" r="1.25" fill="currentColor"/>
                </svg>
                @if ($mobileCartCount > 0)
                    <span class="ky-mobile-cart-count cart-count">{{ $mobileCartCount }}</span>
                @endif
            </a>
        </div>

        <form action="{{ route('search') }}" method="GET" class="ky-mobile-search-form">
            <i class="las la-search" aria-hidden="true"></i>
            <input type="search" name="keyword" value="{{ $query ?? '' }}"
                placeholder="{{ translate('Search products...') }}" aria-label="{{ translate('Search products') }}">
        </form>

        <nav class="ky-mobile-category-pills" aria-label="{{ translate('Product categories') }}">
            <a href="{{ route('home') }}"
                class="ky-mobile-category-pill ky-mobile-home-pill {{ $isMobileHomeRoute ? 'active' : '' }}"
                @if($isMobileHomeRoute) aria-current="page" @endif>
                <i class="las la-home" aria-hidden="true"></i>
                <span>{{ translate('Home') }}</span>
            </a>
            <a href="{{ route('categories.all') }}"
                class="ky-mobile-category-pill {{ $isMobileAllCategoriesRoute ? 'active' : '' }}"
                @if($isMobileAllCategoriesRoute) aria-current="page" @endif>{{ translate('All') }}</a>
            @foreach ($mobileNavCategories as $mobileNavCategory)
                @php
                    $isComputerMobileCategory = strtolower(trim($mobileNavCategory->getTranslation('name'))) === 'computer'
                        || str_starts_with(strtolower($mobileNavCategory->slug), 'computer');
                    $isActiveMobileCategory = $activeMobileCategorySlug === $mobileNavCategory->slug
                        || ($isComputerCatalogRoute && $isComputerMobileCategory);
                @endphp
                <a href="{{ route('products.category', $mobileNavCategory->slug) }}"
                    class="ky-mobile-category-pill {{ $isActiveMobileCategory ? 'active' : '' }}"
                    @if($isActiveMobileCategory) aria-current="page" @endif>
                    {{ $mobileNavCategory->getTranslation('name') }}
                </a>
            @endforeach
        </nav>
    </header>
@endif
<!-- Top Menu Sidebar -->
@include('frontend.inc.mobile_drawer')

<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function (data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
