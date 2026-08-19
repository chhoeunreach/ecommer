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
                if($key > 0 ){
                    $str .= '-'.str_replace(' ', '', $item);
                    $sku .='-'.str_replace(' ', '', $item);
                }
                else{
                    if($colors_active == 1){
                        $color_name = \App\Models\Color::where('code', $item)->first()->name;
                        $str .= $color_name;
                        $sku .='-'.$color_name;
                    }
                    else{
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

                $rowKeys = request()->input('storage_rows_'.$fieldKey, [$fieldKey]);
                if (!is_array($rowKeys) || count($rowKeys) === 0) {
                    $rowKeys = [$fieldKey];
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
                                            $val_storage = request()->input('storage_'.$rowKey, []);
                                            if (!is_array($val_storage)) $val_storage = [$val_storage];
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
                                        <input type="text" name="sku_{{ $rowKey }}" value="{{ request()->has('sku_'.$rowKey) ? request()->input('sku_'.$rowKey) : $str }}" class="form-control">
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Country') }}</td>
                                    @foreach($rowKeys as $rowKey)
                                    <td class="variant-column-{{ $rowKey }}">
                                        @php
                                            $val_country = request()->input('country_'.$rowKey, []);
                                            if (!is_array($val_country)) $val_country = [$val_country];
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
                                            $val_condition = request()->input('condition_'.$rowKey, []);
                                            if (!is_array($val_condition)) $val_condition = [$val_condition];
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
                                        <input type="text" name="code_{{ $rowKey }}" value="{{ request()->input('code_'.$rowKey) }}" class="form-control" placeholder="{{ translate('Code') }}">
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Quantity') }}</td>
                                    @foreach($rowKeys as $rowKey)
                                    <td class="variant-column-{{ $rowKey }}">
                                        <input type="number" lang="en" name="qty_{{ $rowKey }}" value="{{ request()->input('qty_'.$rowKey, 10) }}" min="0" step="1" class="form-control" required>
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Price') }}</td>
                                    @foreach($rowKeys as $rowKey)
                                    <td class="variant-column-{{ $rowKey }}">
                                        <input type="number" lang="en" name="price_{{ $rowKey }}" value="{{ request()->input('price_'.$rowKey, $unit_price) }}" min="0" step="0.01" class="form-control" required>
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="bg-light fw-600 align-middle text-center border-right" style="border-right: 1px solid #e8edf3;">{{ translate('Photo') }}</td>
                                    @foreach($rowKeys as $rowKey)
                                    <td class="variant-column-{{ $rowKey }}">
                                        <div class="input-group variant-photo-uploader" data-toggle="aizuploader" data-type="image">
                                            <div class="form-control file-amount text-truncate">
                                                <i class="las la-image mr-1"></i>{{ translate('Choose Photo') }}
                                            </div>
                                            <input type="hidden" name="img_{{ $rowKey }}" class="selected-files" value="{{ request()->input('img_'.$rowKey) }}">
                                        </div>
                                        <div class="file-preview box sm">
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
