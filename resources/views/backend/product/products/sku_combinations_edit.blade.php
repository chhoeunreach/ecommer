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
                $stock = $product->stocks->firstWhere('variant', $str);
                $val_sku = request()->has('sku_'.$fieldKey) ? request()->input('sku_'.$fieldKey) : ($stock != null ? $stock->sku : $str);
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
                    <input type="number" lang="en" name="qty_{{ $fieldKey }}" value="{{ $val_qty }}" min="0" step="1" class="form-control" required>
                </td>
                <td>
                    <input type="number" lang="en" name="price_{{ $fieldKey }}" value="{{ $val_price }}" min="0" step="0.01" class="form-control" required>
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
