@if(count($combinations) > 0)
@include('backend.product.products.storage_variant_styles')
<table class="table table-bordered aiz-table product-variant-table">
    <thead>
        <tr>
            <td class="text-center" data-breakpoints="lg" style="min-width: 120px;">
                {{translate('SKU')}}
            </td>
            <td class="text-center" style="min-width: 120px;">
                {{translate('Variant')}}
            </td>
            <td class="text-center" style="min-width: 190px;">
                {{translate('Storage')}}
            </td>
            <td class="text-center" style="min-width: 140px;">
                {{translate('Quantity')}}
            </td>
            <td class="text-center" style="min-width: 140px;">
                {{translate('Variant Price')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 210px;">
                {{translate('Photo')}}
            </td>
            <td class="text-center" style="min-width: 80px;">
                {{translate('Action')}}
            </td>
        </tr>
    </thead>
    <tbody>

        @foreach ($combinations as $key => $combination)
            @php
                $sku = '';
                foreach (explode(' ', $product_name) as $key => $value) {
                    $sku .= substr($value, 0, 1);
                }

                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ) {
                        $str .= '-'.str_replace(' ', '', $item);
                        $sku .='-'.str_replace(' ', '', $item);
                    }
                    else {
                        if($colors_active == 1) {
                            $color_name = \App\Models\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                            $sku .='-'.$color_name;
                        }
                        else {
                            $str .= str_replace(' ', '', $item);
                            $sku .='-'.str_replace(' ', '', $item);
                        }
                    }
                }
            @endphp
            @if(strlen($str) > 0)
            @php
                $fieldKey = md5($str);
                $variantColorCode = $colors_active == 1 ? ($combination[0] ?? '') : '';
                $matchingStocks = $product->stocks->filter(function ($candidate) use ($str) {
                    if ($candidate->variant === $str) {
                        return true;
                    }

                    $storageKey = $candidate->storage ? str_replace(' ', '', $candidate->storage) : null;
                    return $storageKey && $candidate->variant === $str.'-'.$storageKey;
                });
                $stock = $matchingStocks->first();
                $stockSku = $stock != null ? $stock->sku : $str;
                $stockStorageKey = $stock != null && $stock->storage ? '-'.str_replace(' ', '', $stock->storage) : null;
                if ($stockStorageKey && str_ends_with($stockSku, $stockStorageKey)) {
                    $stockSku = substr($stockSku, 0, -strlen($stockStorageKey));
                }
                
                $val_sku = request()->has('sku_'.$fieldKey) ? request()->input('sku_'.$fieldKey) : $stockSku;
                $selectedStorages = request()->has('storage_'.$fieldKey)
                    ? request()->input('storage_'.$fieldKey)
                    : $matchingStocks->pluck('storage')->filter()->unique()->values()->all();
                $selectedStorages = is_array($selectedStorages) ? $selectedStorages : preg_split('/[,\r\n]+/', $selectedStorages);
                $selectedStorages = array_values(array_filter(array_map('trim', $selectedStorages)));
                $storageOptions = array_values(array_unique(array_merge(
                    ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB', '2TB'],
                    $selectedStorages
                )));
                $val_qty = request()->has('qty_'.$fieldKey) ? request()->input('qty_'.$fieldKey) : ($stock != null ? $stock->qty : '10');
                
                if (request()->has('price_'.$fieldKey)) {
                    $val_price = request()->input('price_'.$fieldKey);
                } elseif ($product->unit_price == $unit_price) {
                    $val_price = $stock != null ? $stock->price : $unit_price;
                } else {
                    $val_price = $unit_price;
                }
                
                $val_img = request()->has('img_'.$fieldKey) ? request()->input('img_'.$fieldKey) : ($stock != null ? $stock->image : null);
            @endphp
            <tr class="variant" data-color-code="{{ $variantColorCode }}">
                <td>
                    <input type="text" name="sku_{{ $fieldKey }}" value="{{ $val_sku }}" class="form-control">
                </td>
                <td>
                    <span class="variant-name-badge">{{ $str }}</span>
                </td>
                <td>
                    <select name="storage_{{ $fieldKey }}[]" class="form-control aiz-selectpicker show-tick variant-storage-select" data-field-key="{{ $fieldKey }}" data-default-price="{{ $unit_price }}" onchange="syncStorageStockFields(this)" multiple data-live-search="true" data-actions-box="true" data-container="body" data-width="100%" data-selected-text-format="count > 2" title="{{ translate('Choose Storage') }}">
                        @foreach ($storageOptions as $storageOption)
                            <option value="{{ $storageOption }}" @selected(in_array($storageOption, $selectedStorages))>{{ $storageOption }}</option>
                        @endforeach
                    </select>
                    <small class="variant-storage-help">{{ translate('Select storage to configure its quantity and price') }}</small>
                </td>
                <td>
                    <div class="storage-quantity-fields">
                        @if (count($selectedStorages) > 0)
                            @foreach ($selectedStorages as $storageOption)
                                @php($storageStock = $matchingStocks->firstWhere('storage', $storageOption))
                                <div class="storage-stock-field mb-2">
                                    <small class="d-block text-muted mb-1">{{ $storageOption }}</small>
                                    <input type="number" lang="en" name="qty_{{ $fieldKey }}[{{ $storageOption }}]" data-storage="{{ $storageOption }}" value="{{ request()->input('qty_'.$fieldKey.'.'.$storageOption, $storageStock != null ? $storageStock->qty : 10) }}" min="0" step="1" class="form-control" required>
                                </div>
                            @endforeach
                        @else
                            <input type="number" lang="en" name="qty_{{ $fieldKey }}" value="{{ $val_qty }}" min="0" step="1" class="form-control" required>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="storage-price-fields">
                        @if (count($selectedStorages) > 0)
                            @foreach ($selectedStorages as $storageOption)
                                @php($storageStock = $matchingStocks->firstWhere('storage', $storageOption))
                                <div class="storage-stock-field mb-2">
                                    <small class="d-block text-muted mb-1">{{ $storageOption }}</small>
                                    <input type="number" lang="en" name="price_{{ $fieldKey }}[{{ $storageOption }}]" data-storage="{{ $storageOption }}" value="{{ request()->input('price_'.$fieldKey.'.'.$storageOption, $storageStock != null ? $storageStock->price : $unit_price) }}" min="0" step="0.01" class="form-control" required>
                                </div>
                            @endforeach
                        @else
                            <input type="number" lang="en" name="price_{{ $fieldKey }}" value="{{ $val_price }}" min="0" step="0.01" class="form-control" required>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="input-group variant-photo-uploader" data-toggle="aizuploader" data-type="image">
                        <div class="form-control file-amount text-truncate">
                            <i class="las la-image mr-1"></i>{{ translate('Choose Photo') }}
                        </div>
                        <input type="hidden" name="img_{{ $fieldKey }}" class="selected-files" value="{{ $val_img }}">
                    </div>
                    <div class="file-preview box sm">
                        @if($stock != null && $stock->image != null)
                        <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="2049" title="Group 39990.webp">
                            <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                <img src="{{ uploaded_asset($stock->image) }}" class="img-fit">
                            </div>
                            <div class="col body"><h6 class="d-flex"><span class=" text-truncate ">Group 39990</span><span class="flex-shrink-0 ext">.webp</span></h6><p>27 KB</p></div>
                            <div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button>
                            </div>
                        </div>
                        @endif
                    </div>
                </td>
                <td class="text-center variant-action-cell">
                    <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteProductVariant(this)"><i class="las la-trash"></i></button>
                </td>
            </tr>
            @endif
        @endforeach

    </tbody>
</table>
@endif
