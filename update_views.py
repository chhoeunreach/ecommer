import os

files_to_update = [
    'resources/views/backend/product/products/sku_combinations.blade.php',
    'resources/views/backend/product/products/sku_combinations_edit.blade.php',
    'resources/views/seller/product/products/sku_combinations.blade.php',
    'resources/views/seller/product/products/sku_combinations_edit.blade.php'
]

script_to_append = """
<script>
if (typeof add_new_fixed_attribute_value === 'undefined') {
    function add_new_fixed_attribute_value(attribute_id, attribute_name, input_prefix) {
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
                    
                    $('select[name^="' + input_prefix + '"]').each(function() {
                        var select = $(this);
                        var currentValues = select.val() || [];
                        select.html(obj);
                        currentValues.push(value.trim().charAt(0).toUpperCase() + value.trim().slice(1));
                        select.val(currentValues);
                    });
                    
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    }
}
</script>
"""

replacements = {
    "<td class=\"attribute-label\">{{ translate('Storage') }}</td>": """<td class="attribute-label">
    {{ translate('Storage') }}
    <a href="javascript:void(0)" onclick="add_new_fixed_attribute_value({{ $storageAttr->id ?? 4 }}, 'Storage', 'storage_')" class="text-blue fs-12 fw-600 has-transition d-block mt-1">
        <i class="las la-plus mr-1"></i>{{ translate('Add New') }}
    </a>
</td>""",
    "<td class=\"attribute-label\">{{ translate('Country') }}</td>": """<td class="attribute-label">
    {{ translate('Country') }}
    <a href="javascript:void(0)" onclick="add_new_fixed_attribute_value({{ $countryAttr->id ?? 8 }}, 'Country', 'country_')" class="text-blue fs-12 fw-600 has-transition d-block mt-1">
        <i class="las la-plus mr-1"></i>{{ translate('Add New') }}
    </a>
</td>""",
    "<td class=\"attribute-label\">{{ translate('Condition') }}</td>": """<td class="attribute-label">
    {{ translate('Condition') }}
    <a href="javascript:void(0)" onclick="add_new_fixed_attribute_value({{ $conditionAttr->id ?? 9 }}, 'Condition', 'condition_')" class="text-blue fs-12 fw-600 has-transition d-block mt-1">
        <i class="las la-plus mr-1"></i>{{ translate('Add New') }}
    </a>
</td>"""
}

for file_path in files_to_update:
    if os.path.exists(file_path):
        with open(file_path, 'r') as f:
            content = f.read()
            
        # Apply replacements
        for old_str, new_str in replacements.items():
            content = content.replace(old_str, new_str)
            
        # Append script if not already present
        if "add_new_fixed_attribute_value" not in content:
            content += script_to_append
            
        with open(file_path, 'w') as f:
            f.write(content)
        print(f"Updated {file_path}")
    else:
        print(f"File not found: {file_path}")

