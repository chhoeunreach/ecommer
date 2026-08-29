@php
    $total_qty = 0;
    if ($product->variant_product) {
        foreach ($product->stocks as $stock) {
            $total_qty += $stock->qty;
        }
    } else {
        $total_qty = optional($product->stocks->first())->qty ?? 0;
    }
    $currency_sym = currency_symbol();
    $updateUrl = auth()->check() && auth()->user()->user_type == 'seller' 
        ? route('seller.bulk-product-stock-update') 
        : route('bulk-product-stock-update');
@endphp

<div class="d-flex flex-column h-100 bg-white shadow-lg rounded-left">
    <!-- Top Header -->
    <div class="border-bottom py-3 px-4 bg-light position-relative">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center overflow-hidden">
                <div class="w-50px h-50px rounded-2 border overflow-hidden mr-3 bg-white flex-shrink-0 d-flex align-items-center justify-content-center shadow-sm">
                    @if($product->thumbnail_img)
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" class="img-fit h-100 w-100">
                    @else
                        <i class="las la-image fs-24 text-secondary"></i>
                    @endif
                </div>
                <div class="overflow-hidden">
                    <h6 class="fs-15 fw-700 text-dark mb-1 text-truncate" title="{{ $product->getTranslation('name') }}">
                        {{ $product->getTranslation('name') }}
                    </h6>
                    <div class="d-flex align-items-center flex-wrap gap-2 fs-12">
                        @if($product->variant_product)
                            <span class="badge badge-inline badge-soft-info fw-600">{{ count($product->stocks) }} {{ translate('Variants') }}</span>
                        @else
                            <span class="badge badge-inline badge-soft-secondary fw-600">{{ translate('Single Product') }}</span>
                        @endif

                        @if($total_qty <= 0)
                            <span class="badge badge-inline badge-soft-danger fw-600" id="stock-status-badge">{{ translate('Out of Stock') }}</span>
                        @elseif($total_qty <= $product->low_stock_quantity)
                            <span class="badge badge-inline badge-soft-warning fw-600" id="stock-status-badge">{{ translate('Low Stock') }} (≤ {{ $product->low_stock_quantity }})</span>
                        @else
                            <span class="badge badge-inline badge-soft-success fw-600" id="stock-status-badge">{{ translate('In Stock') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeOffcanvas()" class="border-0 bg-transparent text-secondary p-2 hov-text-dark rounded-circle flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>

    <!-- Banner Info -->
    <div class="bg-soft-primary px-4 py-2 border-bottom d-flex align-items-center justify-content-between">
        <span class="fs-12 text-primary fw-600">
            <i class="las la-edit mr-1"></i> {{ translate('Adjust stock quantities and prices below') }}
        </span>
        <div class="fs-12 text-dark">
            <span class="fw-400 text-secondary">{{ translate('Total Stock') }}:</span> 
            <strong class="text-primary fs-14 ml-1" id="modal-live-total-stock">{{ $total_qty }}</strong>
        </div>
    </div>

    <!-- Body / Content -->
    <div class="flex-grow-1 overflow-auto px-4 py-3">
        <form id="adjust-stock-price-form">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="bg-light text-secondary fs-12 fw-700 text-uppercase">
                        <tr>
                            @if($product->variant_product)
                                <th class="py-2 pl-3 rounded-left" style="min-width: 130px;">{{ translate('Variant') }}</th>
                            @else
                                <th class="py-2 pl-3 rounded-left" style="min-width: 130px;">{{ translate('Item') }}</th>
                            @endif
                            <th class="py-2 text-center" style="min-width: 140px;">{{ translate('Stock Quantity') }}</th>
                            <th class="py-2 text-right pr-3 rounded-right" style="min-width: 130px;">{{ translate('Unit Price') }} ({{ $currency_sym }})</th>
                        </tr>
                    </thead>
                    <tbody class="fs-13">
                        @if($product->variant_product && count($product->stocks) > 0)
                            @foreach ($product->stocks as $stock)
                                <tr class="stock-row border-bottom" data-stock-id="{{ $stock->id }}">
                                    <td class="py-3 pl-3 align-middle">
                                        <span class="fw-700 text-dark d-block">{{ $stock->variant }}</span>
                                        @if($stock->sku)
                                            <small class="text-muted fs-11">SKU: {{ $stock->sku }}</small>
                                        @endif
                                    </td>
                                    <td class="py-3 align-middle text-center">
                                        <div class="input-group input-group-sm mx-auto" style="max-width: 130px;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm px-2" type="button" onclick="adjustQty(this, -1)">-</button>
                                            </div>
                                            <input type="number" 
                                                   class="form-control text-center stock-qty-input fw-600" 
                                                   value="{{ $stock->qty }}" 
                                                   min="0" 
                                                   step="1"
                                                   oninput="recalculateTotalStock()">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm px-2" type="button" onclick="adjustQty(this, 1)">+</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 align-middle text-right">
                                        <div class="input-group input-group-sm ml-auto" style="max-width: 130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light text-secondary fs-12 font-weight-bold">{{ $currency_sym }}</span>
                                            </div>
                                            <input type="number" 
                                                   class="form-control text-right stock-price-input fw-600" 
                                                   value="{{ $stock->price }}" 
                                                   min="0" 
                                                   step="0.01">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @php
                                $first_stock = $product->stocks->first();
                                $stock_id = $first_stock ? $first_stock->id : 0;
                                $stock_qty = $first_stock ? $first_stock->qty : $total_qty;
                                $stock_price = $first_stock ? $first_stock->price : $product->unit_price;
                            @endphp
                            <tr class="stock-row border-bottom" data-stock-id="{{ $stock_id }}">
                                <td class="py-3 pl-3 align-middle">
                                    <span class="fw-700 text-dark d-block">{{ translate('Base Product') }}</span>
                                    @if($product->sku)
                                        <small class="text-muted fs-11">SKU: {{ $product->sku }}</small>
                                    @endif
                                </td>
                                <td class="py-3 align-middle text-center">
                                    <div class="input-group input-group-sm mx-auto" style="max-width: 130px;">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary btn-sm px-2" type="button" onclick="adjustQty(this, -1)">-</button>
                                        </div>
                                        <input type="number" 
                                               class="form-control text-center stock-qty-input fw-600" 
                                               value="{{ $stock_qty }}" 
                                               min="0" 
                                               step="1"
                                               oninput="recalculateTotalStock()">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-sm px-2" type="button" onclick="adjustQty(this, 1)">+</button>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 pr-3 align-middle text-right">
                                    <div class="input-group input-group-sm ml-auto" style="max-width: 130px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light text-secondary fs-12 font-weight-bold">{{ $currency_sym }}</span>
                                        </div>
                                        <input type="number" 
                                               id="main_product_unit_price"
                                               class="form-control text-right stock-price-input fw-600" 
                                               value="{{ $stock_price }}" 
                                               min="0" 
                                               step="0.01">
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <div class="border-top p-3 bg-light d-flex align-items-center justify-content-between">
        <button type="button" class="btn btn-outline-secondary font-weight-bold px-3 py-2" onclick="closeOffcanvas()">
            {{ translate('Cancel') }}
        </button>
        <button type="button" id="save-stock-price-btn" class="btn btn-primary px-4 py-2 font-weight-bold rounded-pill shadow-sm" onclick="saveStockAndPrice({{ $product->id }}, '{{ $updateUrl }}')">
            <i class="las la-check-circle mr-1 fs-16 align-middle"></i> {{ translate('Save Changes') }}
        </button>
    </div>
</div>

<script type="text/javascript">
    window.adjustQty = function(btn, change) {
        var input = $(btn).closest('.input-group').find('.stock-qty-input');
        var val = parseInt(input.val()) || 0;
        val = Math.max(0, val + change);
        input.val(val).trigger('input');
    };

    window.recalculateTotalStock = function() {
        var total = 0;
        $('.stock-qty-input').each(function() {
            var val = parseInt($(this).val()) || 0;
            total += Math.max(0, val);
        });
        $('#modal-live-total-stock').text(total);

        var lowStockQty = {{ $product->low_stock_quantity ?? 0 }};
        var badge = $('#stock-status-badge');
        if(total <= 0) {
            badge.attr('class', 'badge badge-inline badge-soft-danger fw-600').text('{{ translate("Out of Stock") }}');
        } else if(total <= lowStockQty) {
            badge.attr('class', 'badge badge-inline badge-soft-warning fw-600').text('{{ translate("Low Stock") }} (≤ ' + lowStockQty + ')');
        } else {
            badge.attr('class', 'badge badge-inline badge-soft-success fw-600').text('{{ translate("In Stock") }}');
        }
    };

    window.saveStockAndPrice = function(productId, updateRouteUrl) {
        var formData = {};
        $('.stock-row').each(function() {
            var stockId = $(this).data('stock-id');
            var qty = $(this).find('.stock-qty-input').val();
            var price = $(this).find('.stock-price-input').val();
            
            formData[stockId] = {
                qty: qty !== '' ? qty : 0,
                price: price !== '' ? price : 0
            };
        });

        var mainUnitPrice = $('#main_product_unit_price').val();
        var saveBtn = $('#save-stock-price-btn');
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> {{ translate("Saving...") }}');

        $.ajax({
            url: updateRouteUrl,
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                stocks: formData,
                unit_price: mainUnitPrice,
                product_id: productId
            },
            success: function(response) {
                saveBtn.prop('disabled', false).html('<i class="las la-check-circle mr-1 fs-16 align-middle"></i> {{ translate("Save Changes") }}');
                if(response == 1) {
                    if(typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Stock and Price updated successfully") }}');
                    }
                    if(typeof closeOffcanvas === 'function') {
                        closeOffcanvas();
                    } else if(typeof closeRightcanvas === 'function') {
                        closeRightcanvas();
                    }
                    if(typeof getProducts === 'function' && typeof currentTab !== 'undefined') {
                        getProducts(currentTab);
                    }
                } else {
                    if(typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                }
            },
            error: function() {
                saveBtn.prop('disabled', false).html('<i class="las la-check-circle mr-1 fs-16 align-middle"></i> {{ translate("Save Changes") }}');
                if(typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            }
        });
    };

    function adjustQty(btn, change) { window.adjustQty(btn, change); }
    function recalculateTotalStock() { window.recalculateTotalStock(); }
    function saveStockAndPrice(productId, updateRouteUrl) { window.saveStockAndPrice(productId, updateRouteUrl); }
</script>