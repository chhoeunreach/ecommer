import os

files_to_update = [
    'resources/views/backend/product/products/sku_combinations.blade.php',
    'resources/views/backend/product/products/sku_combinations_edit.blade.php',
    'resources/views/seller/product/products/sku_combinations.blade.php',
    'resources/views/seller/product/products/sku_combinations_edit.blade.php'
]

old_script = """<script>
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
</script>"""

new_script = """<script>
if (typeof window.add_new_fixed_attribute_value === 'undefined') {
    window.add_new_fixed_attribute_value = function(attribute_id, attribute_name, input_prefix) {
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
</script>"""

for file_path in files_to_update:
    if os.path.exists(file_path):
        with open(file_path, 'r') as f:
            content = f.read()
            
        content = content.replace(old_script, new_script)
            
        with open(file_path, 'w') as f:
            f.write(content)
        print(f"Updated {file_path}")
    else:
        print(f"File not found: {file_path}")

