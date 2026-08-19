@extends('backend.layouts.app')

@section('content')
    @php
        $computerAttributeIdsByLabel = \App\Models\Attribute::whereIn('name', ['Storage', 'Display', 'RAM', 'CPU', 'Chip'])->pluck('id', 'name');
        $computerAttributeValueOptions = [];
        foreach ($computerAttributeIdsByLabel as $label => $attrId) {
            $computerAttributeValueOptions[$label] = \App\Models\AttributeValue::where('attribute_id', $attrId)->pluck('value');
        }
    @endphp
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Add New Computer') }}</h1>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem mb-4">
            <form action="{{route('admin.computers.store')}}" method="POST" enctype="multipart/form-data" id="aizSubmitForm">
                @csrf
                <div class="row gutters-5">
                    <div class="col-xl-8">

                        <!-- Basic Information -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('Basic Information')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Computer Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Brand')}}</label>
                                            <select class="form-control aiz-selectpicker" name="brand_id" data-live-search="true">
                                                <option value="">{{ translate('Select Brand') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Tags')}}</label>
                                            <input type="text" class="form-control aiz-tag-input" name="tags" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Description')}}</label>
                                    <textarea name="description" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Price and Discount -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('Price & Discount')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Price')}} <span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="0.01" placeholder="{{translate('Price')}}" name="price" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount')}}</label>
                                            <input type="number" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount Type')}}</label>
                                            <select class="form-control aiz-selectpicker" name="discount_type">
                                                <option value="amount">{{translate('Flat')}}</option>
                                                <option value="percent">{{translate('Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount Date Range')}}</label>
                                            <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variation & Stock -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('Variation & Stock')}}</h5>
                                </div>

                                <!-- Colors -->
                                <div class="form-group row gutters-5 mb-0">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>
                                            @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}" data-color-id="{{ $color->id }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 align-content-center">
                                        <label class="aiz-switch aiz-switch-blue mb-0">
                                            <input value="1" type="checkbox" name="colors_active">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                @can('add_color')
                                    <div class="mt-1 d-none" id="add_color">
                                        <a href="#" class="text-blue fs-12 fw-600 hov-opacity-80 has-transition d-flex align-items-center" style="margin-left: -2px;">
                                            <svg xmlns="http://www.w3.org/2000/svg"  height="20px" viewBox="0 -960 960 960" width="20px" fill="#3390f3"><path d="M444-444H240v-72h204v-204h72v204h204v72H516v204h-72v-204Z"/></svg>
                                            <span> {{translate('New Color') }}</span>
                                        </a>
                                    </div>
                                @endcan
                                <div class="product-color-actions d-none mt-2">
                                    <div class="product-color-items"></div>
                                    <small class="d-block text-muted mt-1">{{ translate('Edit a color name or code, or remove that color from this computer.') }}</small>
                                </div>

                                <!-- Choose Attributes (kept functional for backward compatibility, hidden from view: variant rows are now added one by one below) -->
                                <div class="d-none" aria-hidden="true">
                                    <div class="form-group row gutters-5 mb-2 mt-3">
                                        <label class="col-md-3 col-from-label fs-14 fw-500">{{ translate('Choose Attributes') }}</label>
                                        <div class="col-md-9">
                                            <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                                @endforeach
                                            </select>
                                            @can('add_product_attribute')
                                                <a href="#" id="add_attribute" class="text-blue fs-12 fw-600 hov-opacity-80 has-transition d-flex align-items-center mt-1">
                                                    <i class="las la-plus fs-16 mr-1"></i><span>{{ translate('New Attribute') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div id="chose_options_text" class="d-none" aria-hidden="true">
                                        <p class="fs-12 text-muted mb-2">{{ translate('Choose the attributes and select the values used to build variants (e.g. Storage, Display, RAM, CPU, Chip).') }}</p>
                                    </div>
                                    <div class="customer_choice_options mb-3 d-none" id="customer_choice_options" aria-hidden="true"></div>
                                </div>

                                <!-- Flat stock/sku (used only while there are no variant combinations) -->
                                <div id="show-hide-div">
                                    <div class="row gutters-5 mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-2 mb-lg-3">
                                                <label class="col-from-label fs-14 fw-500">{{ translate('Stock') }}</label>
                                                <input type="number" lang="en" value="0" step="1" integer-only name="current_stock" class="form-control" placeholder="10">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2 mb-lg-3">
                                                <label class="col-from-label fs-14 fw-500">{{ translate('SKU') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="{{ translate('Computer SKU') }}">
                                                    <div class="input-group-prepend">
                                                        <button type="button" id="generateSKUBtn" class="bg-gray text-white fs-14 fw-400 border-0 rounded-right px-3 w-100px" onclick="generateSKU()">{{ translate('Generate') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SKU Combination (variant table) -->
                                <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
                                    <h6 class="fs-14 fw-700 mb-0">{{ translate('Variants') }}</h6>
                                    <button type="button" class="btn btn-sm btn-soft-primary" id="add-variant-row-btn">
                                        <i class="las la-plus"></i> {{ translate('Add Variant Row') }}
                                    </button>
                                </div>
                                <div class="sku_combination table-responsive" id="sku_combination" style="overflow-x: auto; width: 100%;"></div>
                            </div>
                        </div>

                        <!-- Warranty -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Warranty')}}</h5>
                                <div class="form-group d-flex align-items-center mb-0">
                                    <label class="aiz-switch aiz-switch-success mb-0 mr-3">
                                        <input type="checkbox" name="has_warranty" id="has_warranty" value="1" onchange="toggleWarranty()">
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400">{{translate('Enable warranty for this computer')}}</span>
                                </div>
                                <div class="form-group mt-3" id="warranty_selection" style="display: none;">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Select Warranty')}}</label>
                                    <select class="form-control aiz-selectpicker" name="warranty_id" data-live-search="true">
                                        <option value="">{{ translate('Select Warranty') }}</option>
                                        @foreach ($warranties as $warranty)
                                            <option value="{{ $warranty->id }}">{{ $warranty->getTranslation('text') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Meta Tags -->
                        <div class="border border-gray-300 rounded-2 mt-4 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('SEO Meta Tags')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <!-- Meta Title -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Meta Title')}}</label>
                                            <input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}">
                                        </div>
                                    </div>
                                    <!-- Description -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Description')}}</label>
                                            <textarea name="meta_description" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <!-- Meta Image -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Meta Image')}}</label>
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="meta_img" class="selected-files">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-4">
                        <!-- Product Configuration -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Product Configuration') }}</h5>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mt-3 mb-2">
                                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                            <input value="1" type="checkbox" name="published" checked onchange="updateStatusLabel(this)">
                                            <span></span>
                                        </label>
                                        <span class="fs-14 fw-600 d-block status-label text-success" style="margin-top: -6px">{{ translate('Active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Computer Image')}}</h5>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Thumbnail Image')}}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Gallery Images')}}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="gallery" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary w-200px fs-14 fw-700">{{ translate('Save Computer') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function toggleWarranty() {
        if($('#has_warranty').is(':checked')) {
            $('#warranty_selection').show();
        } else {
            $('#warranty_selection').hide();
        }
    }

    function updateStatusLabel(el) {
        let label = $(el).closest('.d-flex').find('.status-label');
        if(el.checked) {
            label.text('{{ translate('Active') }}').removeClass('text-danger').addClass('text-success');
        } else {
            label.text('{{ translate('Disabled') }}').removeClass('text-success').addClass('text-danger');
        }
    }

    function generateSKU() {
        const btn = document.getElementById('generateSKUBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i>';
        setTimeout(() => {
            const now = Date.now();
            const randomSuffix = Math.floor(Math.random() * 100);
            const barcode = Number(now.toString() + randomSuffix.toString().padStart(2, '0'));

            document.getElementById('sku').value = barcode;
            btn.innerHTML = '<i class="las la-check-circle text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = "{{ translate('Regenerate') }}";
                btn.disabled = false;
            }, 1200);
        }, 300);
    }

    function add_more_customer_choice_option(i, name){
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                $('#chose_options_text').removeClass('d-none');
                var obj = JSON.parse(data);
                $('#customer_choice_options').removeClass('d-none').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-9">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" data-selected-text-format="count" multiple required>\
                            '+obj+'\
                        </select>\
                        <div class="mt-1">\
                            <a href="javascript:void(0)" onclick="add_new_attribute_value('+i+', \''+name+'\')" class="text-blue fs-12 fw-600 hov-opacity-80 has-transition d-flex align-items-center" style="margin-left: -2px;">\
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#3390f3"><path d="M444-444H240v-72h204v-204h72v204h204v72H516v204h-72v-204Z"/></svg>\
                                <span> {{translate("New ") }} ' + name + '</span>\
                            </a>\
                        </div>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });
    }

    function add_new_attribute_value(attribute_id, attribute_name) {
        var value = prompt("{{ translate('Enter new ') }}" + attribute_name + " {{ translate('value:') }}");
        if(value != null && value.trim() != "") {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route("products.add-new-attribute-value") }}',
                data: {
                    attribute_id: attribute_id,
                    value: value
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    var select = $('select[name="choice_options_' + attribute_id + '[]"]');
                    var currentValues = select.val() || [];
                    select.html(obj);
                    currentValues.push(value.trim().charAt(0).toUpperCase() + value.trim().slice(1));
                    select.val(currentValues);
                    AIZ.plugins.bootstrapSelect('refresh');
                    update_sku();
                }
            });
        }
    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            $('#add_color').addClass('d-none');
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else {
            $('#colors').prop('disabled', false);
            $('#add_color').removeClass('d-none');
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice", function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    @include('backend.product.products.storage_variant_js')

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('admin.computers.sku_combination') }}',
           data:$('#aizSubmitForm').serialize(),
           success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.sectionFooTable('#sku_combination');
                AIZ.plugins.bootstrapSelect('refresh');
                if (data.trim().length > 1) {
                   $('#show-hide-div').hide();
                }
                else {
                    $('#show-hide-div').show();
                }
           }
       });
    }

    var $openVariantAttrMenu = null;

    // Removes the popover DOM only, without acting on its selection. Use closeVariantAttrMenu()
    // instead unless you're about to process the selection yourself right after.
    function removeVariantAttrMenu() {
        if ($openVariantAttrMenu) {
            $openVariantAttrMenu.remove();
            $openVariantAttrMenu = null;
        }
    }

    // Closing the popover is the single point where a multi-checked cell actually splits its
    // row. Splitting on every checkbox click instead (checked once, react immediately) let a
    // still-open popover's checkboxes drift out of sync with the row it had already split off,
    // so a 3rd click would re-read stale checked boxes and split the same value again.
    function closeVariantAttrMenu() {
        if (!$openVariantAttrMenu) {
            return;
        }
        var $select = $($openVariantAttrMenu.data('for'));
        removeVariantAttrMenu();
        maybeSplitRow($select);
    }

    function refreshVariantAttrToggleLabel($select) {
        var values = $select.val() || [];
        var label = '-';
        if (values.length === 1) {
            label = values[0];
        } else if (values.length > 1) {
            label = values.length + ' {{ translate("selected") }}';
        }
        $select.siblings('.variant-attr-toggle').find('.variant-attr-toggle-label').text(label);
    }

    // Clicking a Storage/Display/RAM/CPU/Chip cell opens a small checkbox popover
    // (appended to <body> so it's never clipped by the table's scroll box).
    $(document).on('click', '.variant-attr-toggle', function(e) {
        e.stopPropagation();
        var $toggle = $(this);
        var $select = $toggle.siblings('select.variant-attr-select');

        var reopening = $openVariantAttrMenu && $openVariantAttrMenu.data('for') === $select.get(0);
        closeVariantAttrMenu();
        if (reopening) {
            return;
        }

        var $menu = $('<div class="variant-attr-dropdown-menu"></div>').data('for', $select.get(0));
        var currentVals = $select.val() || [];

        $select.find('option').each(function() {
            var val = $(this).val();
            var $opt = $('<label class="variant-attr-option"></label>');
            $('<input>', { type: 'checkbox', value: val })
                .prop('checked', currentVals.indexOf(val) !== -1)
                .appendTo($opt);
            $('<span>').text(val).appendTo($opt);
            $menu.append($opt);
        });

        $('body').append($menu);
        var rect = $toggle.get(0).getBoundingClientRect();
        $menu.css({
            position: 'absolute',
            top: (rect.bottom + window.scrollY + 4) + 'px',
            left: (rect.left + window.scrollX) + 'px',
            minWidth: rect.width + 'px'
        });

        $openVariantAttrMenu = $menu;
    });

    $(document).on('click', '.variant-attr-dropdown-menu', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', function() {
        closeVariantAttrMenu();
    });

    // Checking/unchecking only updates the select + the live label; it does NOT split yet.
    // The actual split happens once, when the popover closes (see closeVariantAttrMenu above).
    $(document).on('change', '.variant-attr-dropdown-menu input[type="checkbox"]', function() {
        var $menu = $(this).closest('.variant-attr-dropdown-menu');
        var $select = $($menu.data('for'));
        var checkedVals = $menu.find('input[type="checkbox"]:checked').map(function() {
            return $(this).val();
        }).get();
        $select.val(checkedVals);
        refreshVariantAttrToggleLabel($select);
    });

    $(document).on('click', '.add-variant-attr-value', function() {
        var $link = $(this);
        var attributeId = $link.data('attribute-id');
        var attributeName = $link.data('attribute-name');
        var $select = $link.siblings('select.variant-attr-select');

        if (!attributeId) {
            return;
        }

        var value = prompt("{{ translate('Enter new ') }}" + attributeName + " {{ translate('value:') }}");
        if (value == null || value.trim() == "") {
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{ route("products.add-new-attribute-value") }}',
            data: {
                attribute_id: attributeId,
                value: value
            },
            success: function() {
                var newVal = value.trim().charAt(0).toUpperCase() + value.trim().slice(1);
                if ($select.find('option[value="' + newVal + '"]').length === 0) {
                    $select.append($('<option>', { value: newVal, text: newVal }));
                }
                var current = $select.val() || [];
                if (current.indexOf(newVal) === -1) {
                    current.push(newVal);
                }
                $select.val(current);
                if ($openVariantAttrMenu && $openVariantAttrMenu.data('for') === $select.get(0)) {
                    removeVariantAttrMenu();
                }
                maybeSplitRow($select);
            }
        });
    });

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);

        var pendingRequests = $.map($("#choice_attributes option:selected"), function(option){
            return add_more_customer_choice_option($(option).val(), $(option).text());
        });

        $.when.apply($, pendingRequests).always(function() {
            update_sku();
        });
    });

    var computerAttributeIdsByLabel = @json($computerAttributeIdsByLabel ?? []);
    var computerAttributeValueOptions = @json($computerAttributeValueOptions ?? []);
    var variantFields = { Storage: 'storage', Display: 'display', RAM: 'ram', CPU: 'cpu', Chip: 'chip' };

    function buildVariantAttrCellHtml(label, field, key, selectedValue) {
        var attributeId = computerAttributeIdsByLabel[label] || '';
        var options = computerAttributeValueOptions[label] || [];
        var html = '<button type="button" class="form-control variant-attr-toggle">' +
            '<span class="variant-attr-toggle-label">' + (selectedValue || '-') + '</span>' +
            '<i class="las la-angle-down variant-attr-caret"></i>' +
        '</button>';
        html += '<select name="' + field + '_' + key + '" class="variant-attr-select" multiple style="display:none;">';
        options.forEach(function(val) {
            html += '<option value="' + val + '"' + (val === selectedValue ? ' selected' : '') + '>' + val + '</option>';
        });
        html += '</select>';
        html += '<a href="javascript:void(0)" class="add-variant-attr-value text-blue fs-11 fw-600 d-block mt-1" data-field="' + field + '" data-attribute-id="' + attributeId + '" data-attribute-name="' + label + '"><i class="las la-plus"></i> {{ translate("Add New") }}</a>';
        return html;
    }

    function ensureVariantTableSkeleton() {
        if ($('#sku_combination table').length > 0) {
            return;
        }

        var head = '<tr>' +
            '<td class="text-center" style="min-width:120px;">{{ translate("SKU") }}</td>' +
            '<td class="text-center" style="min-width:110px;">{{ translate("Storage") }}</td>' +
            '<td class="text-center" style="min-width:110px;">{{ translate("Display") }}</td>' +
            '<td class="text-center" style="min-width:100px;">{{ translate("RAM") }}</td>' +
            '<td class="text-center" style="min-width:130px;">{{ translate("CPU") }}</td>' +
            '<td class="text-center" style="min-width:130px;">{{ translate("Chip") }}</td>' +
            '<td class="text-center" style="min-width:130px;">{{ translate("Price") }}</td>' +
            '<td class="text-center" style="min-width:110px;">{{ translate("Stock") }}</td>' +
            '<td class="text-center" style="min-width:190px;">{{ translate("Photo") }}</td>' +
            '<td class="text-center" style="min-width:80px;">{{ translate("Action") }}</td>' +
        '</tr>';

        $('#sku_combination').html(
            '<table class="table table-bordered aiz-table product-variant-table">' +
                '<thead>' + head + '</thead>' +
                '<tbody></tbody>' +
            '</table>'
        );
    }

    // prefill: { sku, price, qty, storage, display, ram, cpu, chip }
    function buildVariantRowElement(key, prefill) {
        prefill = prefill || {};
        var $row = $('<tr class="variant"></tr>');

        $row.append(
            '<td>' +
                '<input type="hidden" name="manual_variant_keys[]" value="' + key + '">' +
                '<input type="text" name="sku_' + key + '" class="form-control" value="' + (prefill.sku || '') + '">' +
            '</td>'
        );

        Object.keys(variantFields).forEach(function(label) {
            var field = variantFields[label];
            $row.append('<td>' + buildVariantAttrCellHtml(label, field, key, prefill[field] || '') + '</td>');
        });

        $row.append('<td><input type="number" lang="en" name="price_' + key + '" value="' + (prefill.price || '') + '" min="0" step="0.01" class="form-control" required></td>');
        $row.append('<td><input type="number" lang="en" name="qty_' + key + '" value="' + (prefill.qty || 0) + '" min="0" step="1" class="form-control" required></td>');
        $row.append(
            '<td>' +
                '<div class="input-group variant-photo-uploader" data-toggle="aizuploader" data-type="image">' +
                    '<div class="form-control file-amount text-truncate"><i class="las la-image mr-1"></i>{{ translate("Choose Photo") }}</div>' +
                    '<input type="hidden" name="img_' + key + '" class="selected-files">' +
                '</div>' +
                '<div class="file-preview box sm"></div>' +
            '</td>'
        );
        $row.append('<td class="text-center variant-action-cell"><button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteProductVariant(this)"><i class="las la-trash"></i></button></td>');

        return $row;
    }

    function addVariantRow() {
        ensureVariantTableSkeleton();
        $('#sku_combination table tbody').append(buildVariantRowElement(nextVariantRowKey()));
        $('#show-hide-div').hide();
    }

    $('#add-variant-row-btn').on('click', function() {
        addVariantRow();
    });

    // Picking more than one value for a Storage/Display/RAM/CPU/Chip cell splits the row:
    // the first picked value stays on this row, each additional value gets its own new row
    // (copying everything else from this row) right below it. Called once the popover closes
    // (or right after "+ Add New" resolves), never mid-selection.
    var variantRowKeyCounter = 0;

    function nextVariantRowKey() {
        variantRowKeyCounter += 1;
        return 'manual' + Date.now() + '_' + variantRowKeyCounter;
    }

    function maybeSplitRow($select) {
        var values = $select.val() || [];
        if (values.length <= 1) {
            return;
        }

        var name = $select.attr('name');
        var sepIndex = name.indexOf('_');
        var field = name.substring(0, sepIndex);
        var key = name.substring(sepIndex + 1);
        var $row = $select.closest('tr.variant');
        if ($row.length === 0) {
            return;
        }

        var keepValue = values[0];
        var extraValues = values.slice(1);
        $select.val([keepValue]);
        refreshVariantAttrToggleLabel($select);

        var prefill = {
            sku: $row.find('input[name="sku_' + key + '"]').val(),
            price: $row.find('input[name="price_' + key + '"]').val(),
            qty: $row.find('input[name="qty_' + key + '"]').val()
        };
        Object.keys(variantFields).forEach(function(label) {
            var f = variantFields[label];
            var val = $row.find('select[name="' + f + '_' + key + '"]').val();
            prefill[f] = (val && val.length) ? val[0] : '';
        });

        var $insertAfter = $row;
        extraValues.forEach(function(val) {
            var newPrefill = $.extend({}, prefill);
            newPrefill[field] = val;
            var $newRow = buildVariantRowElement(nextVariantRowKey(), newPrefill);
            $insertAfter.after($newRow);
            $insertAfter = $newRow;
        });

        $('#show-hide-div').hide();
    }
</script>
@endsection
