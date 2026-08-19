@if(count($combinations) > 0)
@include('backend.product.products.storage_variant_styles')
<table class="table table-bordered aiz-table product-variant-table">
	<thead>
		<tr>
			<td class="text-center" data-breakpoints="lg" style="min-width: 120px;">
				{{translate('SKU')}}
			</td>
			<td class="text-center" style="min-width: 110px;">
				{{translate('Storage')}}
			</td>
			<td class="text-center" style="min-width: 110px;">
				{{translate('Display')}}
			</td>
			<td class="text-center" style="min-width: 100px;">
				{{translate('RAM')}}
			</td>
			<td class="text-center" style="min-width: 130px;">
				{{translate('CPU')}}
			</td>
			<td class="text-center" style="min-width: 130px;">
				{{translate('Chip')}}
			</td>
			<td class="text-center" style="min-width: 130px;">
				{{translate('Price')}}
			</td>
			<td class="text-center" style="min-width: 110px;">
				{{translate('Stock')}}
			</td>
			<td class="text-center" data-breakpoints="lg" style="min-width: 190px;">
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
			$rowValues = [];
			foreach ($combination as $idx => $item){
				$label = $optionLabels[$idx] ?? null;
				if($idx > 0 ){
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
				if ($label && $label !== 'Color') {
					$rowValues[$label] = $item;
				}
			}
		@endphp
		@if(strlen($str) > 0)
			@php
				$fieldKey = md5($str);
				$variantColorCode = $colors_active == 1 ? ($combination[0] ?? '') : '';
			@endphp
			<tr class="variant" data-color-code="{{ $variantColorCode }}">
				<td>
					<input type="text" name="sku_{{ $fieldKey }}" value="{{ request()->has('sku_'.$fieldKey) ? request()->input('sku_'.$fieldKey) : $str }}" class="form-control">
				</td>
				@foreach (['Storage' => 'storage', 'Display' => 'display', 'RAM' => 'ram', 'CPU' => 'cpu', 'Chip' => 'chip'] as $label => $field)
					@php
						$selected = request()->input($field.'_'.$fieldKey, $rowValues[$label] ?? '');
					@endphp
					<td>
						<button type="button" class="form-control variant-attr-toggle">
							<span class="variant-attr-toggle-label">{{ $selected !== '' ? $selected : '-' }}</span>
							<i class="las la-angle-down variant-attr-caret"></i>
						</button>
						<select name="{{ $field }}_{{ $fieldKey }}" class="variant-attr-select" multiple style="display:none;">
							@foreach (($attributeValueOptions[$label] ?? []) as $val)
								<option value="{{ $val }}" @selected($selected == $val)>{{ $val }}</option>
							@endforeach
						</select>
						<a href="javascript:void(0)" class="add-variant-attr-value text-blue fs-11 fw-600 d-block mt-1" data-field="{{ $field }}" data-attribute-id="{{ $attributeIdsByLabel[$label] ?? '' }}" data-attribute-name="{{ $label }}">
							<i class="las la-plus"></i> {{ translate('Add New') }}
						</a>
					</td>
				@endforeach
				<td>
					<input type="number" lang="en" name="price_{{ $fieldKey }}" value="{{ request()->input('price_'.$fieldKey, $unit_price) }}" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="number" lang="en" name="qty_{{ $fieldKey }}" value="{{ request()->input('qty_'.$fieldKey, 10) }}" min="0" step="1" class="form-control" required>
				</td>
				<td>
					<div class="input-group variant-photo-uploader" data-toggle="aizuploader" data-type="image">
						<div class="form-control file-amount text-truncate">
							<i class="las la-image mr-1"></i>{{ translate('Choose Photo') }}
						</div>
						<input type="hidden" name="img_{{ $fieldKey }}" class="selected-files" value="{{ request()->input('img_'.$fieldKey) }}">
					</div>
					<div class="file-preview box sm">
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
