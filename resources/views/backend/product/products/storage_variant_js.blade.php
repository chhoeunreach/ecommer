function syncStorageStockFields(element) {
    var $select = $(element);
    var $row = $select.closest('.variant');
    var fieldKey = $select.data('field-key');
    var selectedStorages = $select.val() || [];
    var defaultPrice = $select.data('default-price') || 0;

    function currentValues($container) {
        var values = {};
        $container.find('input').each(function () {
            values[$(this).data('storage') || '_default'] = $(this).val();
        });
        return values;
    }

    function renderFields($container, field, defaultValue, step) {
        var values = currentValues($container);
        $container.empty();

        if (selectedStorages.length === 0) {
            $('<input>', {
                type: 'number',
                lang: 'en',
                name: field + '_' + fieldKey,
                value: values._default !== undefined ? values._default : defaultValue,
                min: 0,
                step: step,
                class: 'form-control',
                required: true
            }).appendTo($container);
            return;
        }

        selectedStorages.forEach(function (storage) {
            var $field = $('<div>', {class: 'storage-stock-field mb-2'});
            $('<small>', {class: 'd-block text-muted mb-1', text: storage}).appendTo($field);
            $('<input>', {
                type: 'number',
                lang: 'en',
                name: field + '_' + fieldKey + '[' + storage + ']',
                value: values[storage] !== undefined
                    ? values[storage]
                    : (values._default !== undefined ? values._default : defaultValue),
                min: 0,
                step: step,
                class: 'form-control',
                required: true
            }).attr('data-storage', storage).appendTo($field);
            $field.appendTo($container);
        });
    }

    renderFields($row.find('.storage-quantity-fields'), 'qty', 10, 1);
    renderFields($row.find('.storage-price-fields'), 'price', defaultPrice, 0.01);
}

function deleteProductVariant(element) {
    var $row = $(element).closest('.variant');
    var colorCode = String($row.data('color-code') || '');
    var $colors = $('#colors');

    if (!colorCode || !$colors.length) {
        $row.remove();
        return;
    }

    var selectedColors = ($colors.val() || []).filter(function (value) {
        return String(value) !== colorCode;
    });

    $colors.val(selectedColors);
    $colors.selectpicker('refresh');
    $colors.trigger('change');
}
