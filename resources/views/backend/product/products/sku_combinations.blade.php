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
				$storageOptions = ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB', '2TB'];
				$selectedStorages = request()->input('storage_'.$fieldKey, []);
				$selectedStorages = is_array($selectedStorages) ? $selectedStorages : preg_split('/[,\r\n]+/', $selectedStorages);
				$storageOptions = array_values(array_unique(array_merge($storageOptions, array_filter(array_map('trim', $selectedStorages)))));
			@endphp
			<tr class="variant" data-color-code="{{ $variantColorCode }}">
				<td>
					<input type="text" name="sku_{{ $fieldKey }}" value="{{ request()->has('sku_'.$fieldKey) ? request()->input('sku_'.$fieldKey) : $str }}" class="form-control">
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
								<div class="storage-stock-field mb-2">
									<small class="d-block text-muted mb-1">{{ $storageOption }}</small>
									<input type="number" lang="en" name="qty_{{ $fieldKey }}[{{ $storageOption }}]" data-storage="{{ $storageOption }}" value="{{ request()->input('qty_'.$fieldKey.'.'.$storageOption, 10) }}" min="0" step="1" class="form-control" required>
								</div>
							@endforeach
						@else
							<input type="number" lang="en" name="qty_{{ $fieldKey }}" value="{{ request()->input('qty_'.$fieldKey, 10) }}" min="0" step="1" class="form-control" required>
						@endif
					</div>
				</td>
				<td>
					<div class="storage-price-fields">
						@if (count($selectedStorages) > 0)
							@foreach ($selectedStorages as $storageOption)
								<div class="storage-stock-field mb-2">
									<small class="d-block text-muted mb-1">{{ $storageOption }}</small>
									<input type="number" lang="en" name="price_{{ $fieldKey }}[{{ $storageOption }}]" data-storage="{{ $storageOption }}" value="{{ request()->input('price_'.$fieldKey.'.'.$storageOption, $unit_price) }}" min="0" step="0.01" class="form-control" required>
								</div>
							@endforeach
						@else
							<input type="number" lang="en" name="price_{{ $fieldKey }}" value="{{ request()->input('price_'.$fieldKey, $unit_price) }}" min="0" step="0.01" class="form-control" required>
						@endif
					</div>
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
