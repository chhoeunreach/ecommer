@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left pb-2">
        <h1 class="h3 fw-bold mb-0">{{ translate('All Featured Products') }}</h1>
    </div>

    <div class="card">
        <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom px-25px">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link px-0 mr-4 py-3 {{ request('status') === null ? 'active' : '' }}"
                        href="{{ route('featured_products.index', array_filter(['search' => request('search')])) }}">
                        {{ translate('All Featured Products') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-0 mr-4 py-3 {{ request('status') === 'active' ? 'active' : '' }}"
                        href="{{ route('featured_products.index', ['status' => 'active', 'search' => request('search')]) }}">
                        {{ translate('Active') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-0 py-3 {{ request('status') === 'inactive' ? 'active' : '' }}"
                        href="{{ route('featured_products.index', ['status' => 'inactive', 'search' => request('search')]) }}">
                        {{ translate('Inactive') }}
                    </a>
                </li>
            </ul>

            <button type="button" class="btn btn-link d-flex align-items-center fs-14 fw-600 px-0"
                onclick="openFeaturedProductsCanvas()">
                <span>{{ translate('Add New Featured Product') }}</span>
                <span class="btn btn-primary btn-circle ml-3"><i class="las la-plus"></i></span>
            </button>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('featured_products.index') }}" class="row gutters-10 mb-4">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="col">
                    <div class="input-group bg-light rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-transparent"><i class="las la-search"></i></span>
                        </div>
                        <input type="text" class="form-control border-0 bg-transparent" name="search"
                            value="{{ request('search') }}" placeholder="{{ translate('Search Featured Products ...') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-light px-4" onclick="removeSelectedFeaturedProducts()">
                        {{ translate('Bulk Action') }} <i class="las la-angle-down ml-1"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th class="w-40px">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" id="featured-products-check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </th>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Price') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Featured') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="align-middle">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" class="featured-row-check" value="{{ $product->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <img class="size-60px img-fit rounded border mr-3"
                                            src="{{ uploaded_asset($product->thumbnail_img) }}"
                                            alt="{{ $product->getTranslation('name') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        <span class="fs-13 fw-600 text-dark">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">{{ $product->main_category->name ?? translate('Uncategorized') }}</td>
                                <td class="align-middle">{{ single_price($product->unit_price) }}</td>
                                <td class="align-middle">
                                    <span class="badge {{ $product->published == 1 ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                        {{ $product->published == 1 ? translate('Active') : translate('Inactive') }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" checked onchange="removeFeaturedProduct({{ $product->id }}, this)">
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-right align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm action-toggle dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-expanded="false">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                onclick="removeFeaturedProduct({{ $product->id }})">
                                                <i class="las la-trash mr-2"></i>{{ translate('Remove') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="las la-box-open fs-40 text-secondary"></i>
                                    <p class="text-secondary mt-2 mb-0">{{ translate('No featured products found.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white py-20px z-1045">
        <div class="border-bottom pb-15px px-30px d-flex align-items-center justify-content-between">
            <h5 class="fs-16 fw-700 m-0">{{ translate('Add Featured Products') }}</h5>
            <button type="button" class="border-0 bg-transparent" onclick="closeFeaturedProductsCanvas()">
                <i class="las la-times fs-24"></i>
            </button>
        </div>

        <div class="right-offcanvas-body position-absolute h-100 px-30px">
            <div class="row gutters-5 mt-3">
                <div class="col-md-6">
                    <select class="form-control aiz-selectpicker" id="featured-product-category"
                        data-live-search="true" onchange="searchFeaturedProductCandidates()">
                        <option value="">{{ translate('Choose Category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                            @foreach ($category->childrenCategories as $childCategory)
                                @include('categories.child_category', ['child_category' => $childCategory])
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="featured-product-keyword"
                        placeholder="{{ translate('Search by Product Name') }}">
                </div>
            </div>
            <div class="mt-3" id="featured-product-candidates"></div>
        </div>

        <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer py-20px">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-light mr-2" onclick="closeFeaturedProductsCanvas()">
                    {{ translate('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="addFeaturedProducts()">
                    {{ translate('Add') }}
                </button>
            </div>
        </div>
    </div>
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
    <script>
        var featuredProductSearchTimer;
        var featuredProductsCanvas = document.getElementById('rightOffcanvas');
        var featuredProductsOverlay = document.getElementById('rightOffcanvasOverlay');

        function notifyFeaturedProductError(xhr) {
            var message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : '{{ translate('Something went wrong') }}';
            AIZ.plugins.notify('danger', message);
        }

        function openFeaturedProductsCanvas() {
            featuredProductsCanvas.classList.add('active');
            featuredProductsOverlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            searchFeaturedProductCandidates();
        }

        function closeFeaturedProductsCanvas() {
            featuredProductsCanvas.classList.remove('active');
            featuredProductsOverlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }

        function searchFeaturedProductCandidates() {
            clearTimeout(featuredProductSearchTimer);
            featuredProductSearchTimer = setTimeout(function () {
                $('#featured-product-candidates').html(
                    '<div class="text-center py-5"><i class="las la-spinner la-spin la-2x"></i></div>'
                );
                $.post('{{ route('featured_products.search') }}', {
                    _token: AIZ.data.csrf,
                    category: $('#featured-product-category').val(),
                    search_key: $('#featured-product-keyword').val()
                }).done(function (html) {
                    $('#featured-product-candidates').html(html);
                }).fail(notifyFeaturedProductError);
            }, 300);
        }

        function addFeaturedProducts() {
            var productIds = $('.featured-product-select:checked:not(:disabled)').map(function () {
                return $(this).val();
            }).get();

            if (productIds.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one product.') }}');
                return;
            }

            $.post('{{ route('featured_products.store') }}', {
                _token: AIZ.data.csrf,
                product_ids: productIds
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(notifyFeaturedProductError);
        }

        function removeFeaturedProduct(id, checkbox) {
            $.post('{{ route('featured_products.remove') }}', {
                _token: AIZ.data.csrf,
                id: id
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(function (xhr) {
                if (checkbox) checkbox.checked = true;
                notifyFeaturedProductError(xhr);
            });
        }

        function removeSelectedFeaturedProducts() {
            var ids = $('.featured-row-check:checked').map(function () {
                return $(this).val();
            }).get();

            if (ids.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one product.') }}');
                return;
            }

            if (!confirm('{{ translate('Remove the selected products from Featured Products?') }}')) {
                return;
            }

            $.post('{{ route('featured_products.bulk-remove') }}', {
                _token: AIZ.data.csrf,
                ids: ids
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(notifyFeaturedProductError);
        }

        $('#featured-product-keyword').on('input', searchFeaturedProductCandidates);
        $('#featured-products-check-all').on('change', function () {
            $('.featured-row-check').prop('checked', this.checked);
        });
        featuredProductsOverlay.addEventListener('click', closeFeaturedProductsCanvas);
    </script>
@endsection
