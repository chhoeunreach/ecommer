@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Ultimate POS Connector') }}</h5>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Connection Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pos-connector.update') }}">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('POS Base URL') }}</label>
                            <input type="url" name="pos_base_url" class="form-control" value="{{ old('pos_base_url', $setting->pos_base_url) }}" placeholder="http://localhost" required>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('API Token') }}</label>
                            <input type="password" name="api_token" class="form-control" value="{{ old('api_token', $setting->api_token) }}" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Shop Domain') }}</label>
                            <input type="text" name="shop_domain" class="form-control" value="{{ old('shop_domain', $setting->shop_domain) }}" placeholder="127.0.0.1:8001">
                        </div>
                        <label class="aiz-switch aiz-switch-success mb-3">
                            <input type="checkbox" name="is_active" value="1" @if(old('is_active', $setting->is_active)) checked @endif>
                            <span></span>
                            <span class="ml-2">{{ translate('Active') }}</span>
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">{{ translate('Save Settings') }}</button>
                            <button type="button" id="test-pos-connection" class="btn btn-soft-info">{{ translate('Test Connection') }}</button>
                        </div>
                    </form>
                    @if($setting->last_sync_at)
                        <div class="alert alert-light mt-3 mb-0">
                            {{ translate('Last sync') }}: {{ $setting->last_sync_at }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Manual Sync') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <form method="POST" action="{{ route('pos-connector.sync', 'categories') }}">@csrf<button class="btn btn-soft-primary">{{ translate('Sync Categories') }}</button></form>
                        <form method="POST" action="{{ route('pos-connector.sync', 'brands') }}">@csrf<button class="btn btn-soft-primary">{{ translate('Sync Brands') }}</button></form>
                        <form method="POST" action="{{ route('pos-connector.sync', 'products') }}">@csrf<button class="btn btn-soft-primary">{{ translate('Sync Products') }}</button></form>
                        <form method="POST" action="{{ route('pos-connector.sync', 'all') }}">@csrf<button class="btn btn-primary">{{ translate('Sync All') }}</button></form>
                    </div>
                    <form method="POST" action="{{ route('pos-connector.orders.pending') }}">
                        @csrf
                        <button class="btn btn-soft-success">{{ translate('Send Pending Orders to POS') }}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 h6">{{ translate('POS Product Manager') }}</h5>
                    @if($productManager)
                        <span class="badge badge-inline badge-soft-info">
                            {{ translate('Imported') }}: {{ $productManager['imported_total'] }}
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    @if($productManager)
                        <form method="POST" action="{{ route('pos-connector.products.action') }}" id="pos-products-action-form">
                            @csrf
                            <input type="hidden" name="action" id="pos-products-action">

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-soft-primary pos-products-action" data-action="sync_selected">
                                    {{ translate('Sync Selected') }}
                                </button>
                                <button type="button" class="btn btn-soft-danger pos-products-action" data-action="remove_selected">
                                    {{ translate('Remove Selected Imported') }}
                                </button>
                                <button type="button" class="btn btn-danger pos-products-action" data-action="remove_all">
                                    {{ translate('Remove All Imported') }}
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label class="aiz-checkbox mb-0">
                                                    <input type="checkbox" id="pos-products-check-all">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </th>
                                            <th>{{ translate('POS ID') }}</th>
                                            <th>{{ translate('Product') }}</th>
                                            <th>{{ translate('SKU') }}</th>
                                            <th>{{ translate('Category') }}</th>
                                            <th>{{ translate('Brand') }}</th>
                                            <th>{{ translate('Price') }}</th>
                                            <th>{{ translate('Stock') }}</th>
                                            <th>{{ translate('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productManager['rows'] as $row)
                                            <tr>
                                                <td>
                                                    <label class="aiz-checkbox mb-0">
                                                        <input type="checkbox" name="pos_ids[]" value="{{ $row['pos_id'] }}" class="pos-product-check">
                                                        <span class="aiz-square-check"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $row['pos_id'] }}</td>
                                                <td>
                                                    <div class="fw-600">{{ $row['name'] }}</div>
                                                    @if($row['ecommerce_id'])
                                                        <small class="text-muted">#{{ $row['ecommerce_id'] }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $row['sku'] ?: '-' }}</td>
                                                <td>{{ $row['category'] ?: '-' }}</td>
                                                <td>{{ $row['brand'] ?: '-' }}</td>
                                                <td>{{ single_price($row['price']) }}</td>
                                                <td>{{ $row['qty'] }}</td>
                                                <td>
                                                    @if($row['imported'])
                                                        <span class="badge badge-inline badge-success">{{ translate('Imported') }}</span>
                                                    @else
                                                        <span class="badge badge-inline badge-secondary">{{ translate('Not Imported') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">{{ translate('No POS products found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-muted">
                                {{ translate('Page') }} {{ $productManager['page'] }} / {{ $productManager['last_page'] }}
                            </span>
                            <div>
                                @if($productManager['page'] > 1)
                                    <a class="btn btn-sm btn-soft-secondary" href="{{ request()->fullUrlWithQuery(['page' => $productManager['page'] - 1]) }}">
                                        {{ translate('Previous') }}
                                    </a>
                                @endif
                                @if($productManager['page'] < $productManager['last_page'])
                                    <a class="btn btn-sm btn-soft-secondary" href="{{ request()->fullUrlWithQuery(['page' => $productManager['page'] + 1]) }}">
                                        {{ translate('Next') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light mb-0">
                            {{ translate('Save active POS settings with an API token to load POS products.') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Recent Order Exports') }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Order') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('POS Transaction') }}</th>
                                <th>{{ translate('Message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExports as $export)
                                <tr>
                                    <td>#{{ $export->order_id }}</td>
                                    <td>{{ ucfirst($export->status) }}</td>
                                    <td>{{ $export->pos_transaction_id }}</td>
                                    <td>{{ $export->message }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">{{ translate('No exports yet') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#test-pos-connection').on('click', function () {
            $.post('{{ route('pos-connector.test') }}', {
                _token: '{{ csrf_token() }}'
            }).done(function (response) {
                AIZ.plugins.notify('success', response.message || '{{ translate('Connection successful') }}');
            }).fail(function (xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ translate('Connection failed') }}';
                AIZ.plugins.notify('danger', message);
            });
        });

        $('#pos-products-check-all').on('change', function () {
            $('.pos-product-check').prop('checked', $(this).is(':checked'));
        });

        $('.pos-products-action').on('click', function () {
            var action = $(this).data('action');
            var selected = $('.pos-product-check:checked').length;

            if (action !== 'remove_all' && selected === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one POS product') }}');
                return;
            }

            if (action === 'remove_all' && !confirm('{{ translate('Remove all imported POS products from Active eCommerce?') }}')) {
                return;
            }

            if (action === 'remove_selected' && !confirm('{{ translate('Remove selected imported POS products from Active eCommerce?') }}')) {
                return;
            }

            $('#pos-products-action').val(action);
            $('#pos-products-action-form').submit();
        });
    </script>
@endsection
