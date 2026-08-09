@if(count($combinations) > 0)
<table class="table table-bordered aiz-table">
    <thead>
        <tr>
            <td class="text-center" data-breakpoints="lg" style="min-width: 120px;">
                {{translate('SKU')}}
            </td>
            <td class="text-center" style="min-width: 120px;">
                {{translate('Variant')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 100px;">
                {{translate('Storage')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 100px;">
                {{translate('Code')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 100px;">
                {{translate('Country')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 100px;">
                {{translate('Condition')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 100px;">
                {{translate('Quantity')}}
            </td>
            <td class="text-center" style="min-width: 120px;">
                {{translate('Variant Price')}}
            </td>
            <td class="text-center" data-breakpoints="lg" style="min-width: 200px;">
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
                    $stock = $product->stocks->where('variant', $str)->first();
                    // if($stock != null) {
                    //     $variation_available = true;
                    // }
                }
            @endphp
            @if(strlen($str) > 0)
            @php
                $fieldKey = md5($str);
                
                $val_sku = request()->has('sku_'.$fieldKey) ? request()->input('sku_'.$fieldKey) : ($stock != null ? $stock->sku : $str);
                $val_storage = request()->has('storage_'.$fieldKey) ? request()->input('storage_'.$fieldKey) : ($stock != null ? $stock->storage : '');
                $val_code = request()->has('code_'.$fieldKey) ? request()->input('code_'.$fieldKey) : ($stock != null ? $stock->code : '');
                $val_country = request()->has('country_'.$fieldKey) ? request()->input('country_'.$fieldKey) : ($stock != null ? $stock->country : '');
                $val_condition = request()->has('condition_'.$fieldKey) ? request()->input('condition_'.$fieldKey) : ($stock != null ? $stock->condition : '');
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
            <tr class="variant">
                <td>
                    <input type="text" name="sku_{{ $fieldKey }}" value="{{ $val_sku }}" class="form-control">
                </td>
                <td>
                    <label for="" class="control-label">{{ $str }}</label>
                </td>
                <td>
                    <input type="text" name="storage_{{ $fieldKey }}" value="{{ $val_storage }}" class="form-control">
                </td>
                <td>
                    <input type="text" name="code_{{ $fieldKey }}" value="{{ $val_code }}" class="form-control">
                </td>
                <td>
                    <input type="text" name="country_{{ $fieldKey }}" value="{{ $val_country }}" class="form-control">
                </td>
                <td>
                    <input type="text" name="condition_{{ $fieldKey }}" value="{{ $val_condition }}" class="form-control">
                </td>
                <td>
                    <input type="number" lang="en" name="qty_{{ $fieldKey }}" value="{{ $val_qty }}" min="0" step="1" class="form-control" required>
                </td>
                <td>
                    <input type="number" lang="en" name="price_{{ $fieldKey }}" value="{{ $val_price }}" min="0" step="0.01" class="form-control" required>
                </td>
                <td>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount text-truncate">{{ translate('Choose File') }}</div>
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
                <td class="text-center">
                    <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="delete_variant(this)"><i class="las la-trash"></i></button>
                </td>
            </tr>
            @endif
        @endforeach

    </tbody>
</table>
@endif
