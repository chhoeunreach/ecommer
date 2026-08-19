@if(count($combinations) > 0)
@php
    $storageOptions = \App\Models\AttributeValue::where('attribute_id', 4)->get();
    $countryOptions = \App\Models\AttributeValue::where('attribute_id', 8)->get();
    $conditionOptions = \App\Models\AttributeValue::where('attribute_id', 9)->get();
@endphp
@include('backend.product.products.storage_variant_styles')

<div class="variant-cards-container">
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
            $stocksForCombo = $product->stocks->where('variant', $str)->values();

            $rowKeys = request()->input('storage_rows_'.$fieldKey, null);
            if (!is_array($rowKeys) || count($rowKeys) === 0) {
                $rowKeys = null;
            }

            $rowSource = [];
            if ($rowKeys === null) {
                if ($stocksForCombo->count() > 1) {
                    $rowKeys = [];
                    foreach ($stocksForCombo as $i => $comboStock) {
                        $rk = $i === 0 ? $fieldKey : $fieldKey.'_s'.($i + 1);
                        $rowKeys[] = $rk;
                        $rowSource[$rk] = ['stock' => $comboStock, 'storage' => null];
                    }
                } elseif ($stocksForCombo->count() === 1) {
                    $comboStock = $stocksForCombo->first();
                    $storageValues = $comboStock->storage
                        ? array_values(array_filter(array_map('trim', explode(',', $comboStock->storage))))
                        : [];

                    if (count($storageValues) > 1) {
                        $rowKeys = [];
                        foreach ($storageValues as $i => $storageValue) {
                            $rk = $i === 0 ? $fieldKey : $fieldKey.'_s'.($i + 1);
                            $rowKeys[] = $rk;
                            $rowSource[$rk] = ['stock' => $comboStock, 'storage' => [$storageValue]];
                        }
                    } else {
                        $rowKeys = [$fieldKey];
                        $rowSource[$fieldKey] = ['stock' => $comboStock, 'storage' => null];
                    }
                } else {
                    $rowKeys = [$fieldKey];
                    $rowSource[$fieldKey] = ['stock' => null, 'storage' => null];
                }
            } else {
                foreach ($rowKeys as $i => $rk) {
                    $rowSource[$rk] = ['stock' => $stocksForCombo->get($i), 'storage' => null];
                }
            }

            $groupSize = count($rowKeys);
        @endphp
        
        <div class="card mb-4 variant-card border-primary" data-field-key="{{ $fieldKey }}" data-color-code="{{ $variantColorCode }}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 fw-600">{{ translate('Variant') }}: <span class="badge badge-inline badge-primary fs-14">{{ $str }}</span></h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th width="150" class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Attribute') }}</th>
                                @foreach($rowKeys as $index => $rowKey)
                                <th class="text-center align-middle bg-soft-secondary variant-column-{{ $rowKey }}" style="min-width: 200px;">
                                    {{ translate('Option') }} {{ $index + 1 }}
                                    <button type="button" class="btn btn-icon btn-xs btn-danger float-right" onclick="deleteProductVariantColumn(this)"><i class="las la-trash"></i></button>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Storage') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    <input type="hidden" name="storage_rows_{{ $fieldKey }}[]" value="{{ $rowKey }}" class="row-key-tracker">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $storageOverride = $rowSource[$rowKey]['storage'] ?? null;
                                        $val_storage = request()->has('storage_'.$rowKey)
                                            ? request()->input('storage_'.$rowKey)
                                            : ($storageOverride !== null ? $storageOverride : ($stock != null ? $stock->storage : null));
                                        
                                        if (is_string($val_storage)) $val_storage = explode(',', $val_storage);
                                        if (!is_array($val_storage)) $val_storage = [];
                                    @endphp
                                    <select name="storage_{{ $rowKey }}[]" class="form-control aiz-selectpicker" data-placeholder="{{ translate('Select') }}" multiple>
                                        @foreach($storageOptions as $opt)
                                        <option value="{{ $opt->value }}" @if(in_array($opt->value, $val_storage)) selected @endif>{{ $opt->value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('SKU') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_sku = request()->has('sku_'.$rowKey) ? request()->input('sku_'.$rowKey) : ($stock != null ? $stock->sku : $str);
                                    @endphp
                                    <input type="text" name="sku_{{ $rowKey }}" value="{{ $val_sku }}" class="form-control">
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Country') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_country = request()->has('country_'.$rowKey) ? request()->input('country_'.$rowKey) : ($stock != null ? $stock->country : null);
                                        if (is_string($val_country)) $val_country = explode(',', $val_country);
                                        if (!is_array($val_country)) $val_country = [];
                                    @endphp
                                    <select name="country_{{ $rowKey }}[]" class="form-control aiz-selectpicker" data-placeholder="{{ translate('Select') }}" multiple>
                                        @foreach($countryOptions as $opt)
                                        <option value="{{ $opt->value }}" @if(in_array($opt->value, $val_country)) selected @endif>{{ $opt->value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Condition') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_condition = request()->has('condition_'.$rowKey) ? request()->input('condition_'.$rowKey) : ($stock != null ? $stock->condition : null);
                                        if (is_string($val_condition)) $val_condition = explode(',', $val_condition);
                                        if (!is_array($val_condition)) $val_condition = [];
                                    @endphp
                                    <select name="condition_{{ $rowKey }}[]" class="form-control aiz-selectpicker" data-placeholder="{{ translate('Select') }}" multiple>
                                        @foreach($conditionOptions as $opt)
                                        <option value="{{ $opt->value }}" @if(in_array($opt->value, $val_condition)) selected @endif>{{ $opt->value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Code') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_code = request()->has('code_'.$rowKey) ? request()->input('code_'.$rowKey) : ($stock != null ? $stock->code : null);
                                    @endphp
                                    <input type="text" name="code_{{ $rowKey }}" value="{{ $val_code }}" class="form-control" placeholder="{{ translate('Code') }}">
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Quantity') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_qty = request()->has('qty_'.$rowKey) ? request()->input('qty_'.$rowKey) : ($stock != null ? $stock->qty : '10');
                                    @endphp
                                    <input type="number" lang="en" name="qty_{{ $rowKey }}" value="{{ $val_qty }}" min="0" step="1" class="form-control" required>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Price') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_price = request()->has('price_'.$rowKey)
                                            ? request()->input('price_'.$rowKey)
                                            : ($stock != null ? $stock->price : $unit_price);
                                    @endphp
                                    <input type="number" lang="en" name="price_{{ $rowKey }}" value="{{ $val_price }}" min="0" step="0.01" class="form-control" required>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Photo') }}</td>
                                @foreach($rowKeys as $rowKey)
                                <td class="variant-column-{{ $rowKey }}">
                                    @php
                                        $stock = $rowSource[$rowKey]['stock'] ?? null;
                                        $val_img = request()->has('img_'.$rowKey) ? request()->input('img_'.$rowKey) : ($stock != null ? $stock->image : null);
                                    @endphp
                                    <div class="input-group variant-photo-uploader" data-toggle="aizuploader" data-type="image">
                                        <div class="form-control file-amount text-truncate">
                                            <i class="las la-image mr-1"></i>{{ translate('Choose Photo') }}
                                        </div>
                                        <input type="hidden" name="img_{{ $rowKey }}" class="selected-files" value="{{ $val_img }}">
                                    </div>
                                    <div class="file-preview box sm">
                                        @if($stock != null && $stock->image != null)
                                        <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="{{ $stock->image }}">
                                            <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ uploaded_asset($stock->image) }}" class="img-fit">
                                            </div>
                                            <div class="col body"><h6 class="d-flex"><span class=" text-truncate ">{{ translate('Image') }}</span></h6><p></p></div>
                                            <div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif
