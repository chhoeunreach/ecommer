@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left pb-2">
        <h1 class="h3 fw-bold mb-0">{{ translate('All Best Seller Products') }}</h1>
    </div>

    <div class="card">
        <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom px-25px">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link px-0 mr-4 py-3 {{ request('status') === null ? 'active' : '' }}"
                        href="{{ route('best_seller_products.index', array_filter(['search' => request('search')])) }}">
                        {{ translate('All Best Seller Products') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-0 mr-4 py-3 {{ request('status') === 'active' ? 'active' : '' }}"
                        href="{{ route('best_seller_products.index', ['status' => 'active', 'search' => request('search')]) }}">
                        {{ translate('Active') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-0 py-3 {{ request('status') === 'inactive' ? 'active' : '' }}"
                        href="{{ route('best_seller_products.index', ['status' => 'inactive', 'search' => request('search')]) }}">
                        {{ translate('Inactive') }}
                    </a>
                </li>
            </ul>

            <button type="button" class="btn btn-link d-flex align-items-center fs-14 fw-600 px-0"
                onclick="openBestSellerProductsCanvas()">
                <span>{{ translate('Add New Best Seller Product') }}</span>
                <span class="btn btn-primary btn-circle ml-3"><i class="las la-plus"></i></span>
            </button>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('best_seller_products.index') }}" class="row gutters-10 mb-4">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="col">
                    <div class="input-group bg-light rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-transparent"><i class="las la-search"></i></span>
                        </div>
                        <input type="text" class="form-control border-0 bg-transparent" name="search"
                            value="{{ request('search') }}" placeholder="{{ translate('Search Best Seller Products ...') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-light px-4" onclick="removeSelectedBestSellerProducts()">
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
                                    <input type="checkbox" id="best-seller-products-check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </th>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Price') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Best Seller') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="align-middle">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" class="best-seller-row-check" value="{{ $product->id }}">
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
                                        <input type="checkbox" checked onchange="removeBestSellerProduct({{ $product->id }}, this)">
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-right align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm action-toggle dropdown-toggle" type="button"
                                            data-toggle="dropdown" data-boundary="viewport" aria-expanded="false">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('product_edit')
                                                <a class="dropdown-item" href="{{ route('products.admin.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}">
                                                    <i class="las la-edit mr-2"></i>{{ translate('Edit') }}
                                                </a>
                                            @endcan
                                            @if (!$product->draft)
                                                <a class="dropdown-item" href="{{ route('product', $product->slug) }}" target="_blank">
                                                    <i class="las la-eye mr-2"></i>{{ translate('View Product') }}
                                                </a>
                                            @endif
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="removeBestSellerProduct({{ $product->id }})">
                                                <i class="las la-times-circle mr-2"></i>{{ translate('Remove from Best Seller') }}
                                            </a>
                                            @can('product_delete')
                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                    onclick="deleteBestSellerProduct({{ $product->id }})">
                                                    <i class="las la-trash mr-2"></i>{{ translate('Delete Product') }}
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="las la-box-open fs-40 text-secondary"></i>
                                    <p class="text-secondary mt-2 mb-0">{{ translate('No best seller products found.') }}</p>
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
    <div id="bestSellerRightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white py-20px z-1045">
        <div class="border-bottom pb-15px px-30px d-flex align-items-center justify-content-between">
            <h5 class="fs-16 fw-700 m-0">{{ translate('Add Best Seller Products') }}</h5>
            <button type="button" class="border-0 bg-transparent" onclick="closeBestSellerProductsCanvas()">
                <i class="las la-times fs-24"></i>
            </button>
        </div>

        <div class="right-offcanvas-body position-absolute h-100 px-30px">
            <div class="row gutters-5 mt-3">
                <div class="col-md-6">
                    <select class="form-control aiz-selectpicker" id="best-seller-product-category"
                        data-live-search="true" onchange="searchBestSellerProductCandidates()">
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
                    <input type="text" class="form-control" id="best-seller-product-keyword"
                        placeholder="{{ translate('Search by Product Name') }}">
                </div>
            </div>
            <div class="mt-3" id="best-seller-product-candidates"></div>
        </div>

        <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer py-20px">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-light mr-2" onclick="closeBestSellerProductsCanvas()">
                    {{ translate('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="addBestSellerProducts()">
                    {{ translate('Add') }}
                </button>
            </div>
        </div>
    </div>
    <div id="bestSellerRightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
    <script>
        var bestSellerProductSearchTimer;
        var bestSellerProductsCanvas = document.getElementById('bestSellerRightOffcanvas');
        var bestSellerProductsOverlay = document.getElementById('bestSellerRightOffcanvasOverlay');

        function notifyBestSellerProductError(xhr) {
            var message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : '{{ translate('Something went wrong') }}';
            AIZ.plugins.notify('danger', message);
        }

        function openBestSellerProductsCanvas() {
            bestSellerProductsCanvas.classList.add('active');
            bestSellerProductsOverlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            searchBestSellerProductCandidates();
        }

        function closeBestSellerProductsCanvas() {
            bestSellerProductsCanvas.classList.remove('active');
            bestSellerProductsOverlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }

        function searchBestSellerProductCandidates() {
            clearTimeout(bestSellerProductSearchTimer);
            bestSellerProductSearchTimer = setTimeout(function () {
                $('#best-seller-product-candidates').html(
                    '<div class="text-center py-5"><i class="las la-spinner la-spin la-2x"></i></div>'
                );
                $.post('{{ route('best_seller_products.search') }}', {
                    _token: AIZ.data.csrf,
                    category: $('#best-seller-product-category').val(),
                    search_key: $('#best-seller-product-keyword').val()
                }).done(function (html) {
                    $('#best-seller-product-candidates').html(html);
                }).fail(notifyBestSellerProductError);
            }, 300);
        }

        function addBestSellerProducts() {
            var productIds = $('.best-seller-product-select:checked:not(:disabled)').map(function () {
                return $(this).val();
            }).get();

            if (productIds.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one product.') }}');
                return;
            }

            $.post('{{ route('best_seller_products.store') }}', {
                _token: AIZ.data.csrf,
                product_ids: productIds
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(notifyBestSellerProductError);
        }

        function removeBestSellerProduct(id, checkbox) {
            $.post('{{ route('best_seller_products.remove') }}', {
                _token: AIZ.data.csrf,
                id: id
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(function (xhr) {
                if (checkbox) checkbox.checked = true;
                notifyBestSellerProductError(xhr);
            });
        }

        function deleteBestSellerProduct(id) {
            if (!confirm('{{ translate('Are you sure you want to delete this product? This action cannot be undone.') }}')) {
                return;
            }

            $.ajax({
                url: "{{ route('products.destroy', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Product deleted successfully') }}');
                        window.location.reload();
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function removeSelectedBestSellerProducts() {
            var ids = $('.best-seller-row-check:checked').map(function () {
                return $(this).val();
            }).get();

            if (ids.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one product.') }}');
                return;
            }

            if (!confirm('{{ translate('Remove the selected products from Best Seller Products?') }}')) {
                return;
            }

            $.post('{{ route('best_seller_products.bulk-remove') }}', {
                _token: AIZ.data.csrf,
                ids: ids
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message);
                window.location.reload();
            }).fail(notifyBestSellerProductError);
        }

        $('#best-seller-product-keyword').on('input', searchBestSellerProductCandidates);
        $('#best-seller-products-check-all').on('change', function () {
            $('.best-seller-row-check').prop('checked', this.checked);
        });
        bestSellerProductsOverlay.addEventListener('click', closeBestSellerProductsCanvas);
    </script>
@endsection
