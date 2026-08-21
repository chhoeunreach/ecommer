@php
    // This partial is shared by both the admin and seller product pages, which
    // each have their own (identically named) attribute-value-store route.
    $addAttributeValueRouteName = request()->is('seller/*')
        ? 'seller.products.add-new-attribute-value'
        : 'products.add-new-attribute-value';
@endphp

// Quick-add a new value for a fixed attribute (Storage/Country/Condition) from the
// variant table, without leaving the page. Reuses the same right-offcanvas panel the
// rest of the admin uses for quick-add forms, and adds the value to every select for
// that field on success.
function add_new_fixed_attribute_value(attributeId, label, fieldPrefix) {
    var $offcanvas = $('#globalRightOffcanvas');
    var $overlay = $('#globalRightOffcanvasOverlay');

    if (!$offcanvas.length || !$overlay.length) {
        return;
    }

    // Storage values are always "<number>GB" — take just the digits and add the
    // unit ourselves, instead of asking the admin to type "GB" every time.
    var isStorage = fieldPrefix === 'storage_';
    var fieldHtml = isStorage
        ? '<div class="input-group">' +
                '<input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="{{ translate("e.g. 512") }}" id="new_fixed_attribute_value" class="form-control" required>' +
                '<div class="input-group-append">' +
                    '<span class="input-group-text">GB</span>' +
                '</div>' +
            '</div>'
        : '<input type="text" placeholder="' + label + '" id="new_fixed_attribute_value" class="form-control" required>';

    $offcanvas.addClass('active').html(
        '<div class="border-sm-bottom pb-15px px-30px">' +
            '<div class="d-flex align-items-center justify-content-between">' +
                '<h6 class="fs-16 fw-700 text-dark mb-0">{{ translate("Add New") }} ' + label + '</h6>' +
                '<button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">✕</button>' +
            '</div>' +
        '</div>' +
        '<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">' +
            '<div class="form-group mb-3">' +
                '<label class="col-from-label" for="new_fixed_attribute_value">' + label + '</label>' +
                fieldHtml +
            '</div>' +
        '</div>' +
        '<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">' +
            '<div class="d-flex justify-content-end">' +
                '<button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="save-fixed-attribute-value">' +
                    '{{ translate("Save") }}' +
                '</button>' +
            '</div>' +
        '</div>'
    );
    $overlay.addClass('active');
    $('body').addClass('body-no-scroll');
    $('#new_fixed_attribute_value').trigger('focus');

    if (isStorage) {
        $(document)
            .off('input.fixedAttributeValueDigitsOnly')
            .on('input.fixedAttributeValueDigitsOnly', '#new_fixed_attribute_value', function () {
                var digits = this.value.replace(/[^0-9]/g, '');
                if (digits !== this.value) {
                    this.value = digits;
                }
            });
    }

    $(document)
        .off('click.saveFixedAttributeValue')
        .on('click.saveFixedAttributeValue', '#save-fixed-attribute-value', function () {
            var $button = $(this);
            var rawValue = $.trim($('#new_fixed_attribute_value').val() || '');

            if (isStorage) {
                rawValue = rawValue.replace(/[^0-9]/g, '');
            }

            if (!rawValue) {
                AIZ.plugins.notify('warning', isStorage
                    ? '{{ translate("Please enter a number.") }}'
                    : '{{ translate("Please enter a value.") }}');
                return;
            }

            var value = isStorage ? (rawValue + 'GB') : rawValue;

            $button.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: '{{ route($addAttributeValueRouteName) }}',
                data: {
                    _token: AIZ.data.csrf,
                    attribute_id: attributeId,
                    value: value
                },
                success: function () {
                    var displayValue = isStorage ? value : (value.charAt(0).toUpperCase() + value.slice(1));
                    $('select[name^="' + fieldPrefix + '"]').each(function () {
                        var $select = $(this);
                        if ($select.find('option[value="' + displayValue + '"]').length === 0) {
                            $select.append($('<option>', {value: displayValue, text: displayValue}));
                            if ($select.parent().hasClass('bootstrap-select')) {
                                $select.selectpicker('refresh');
                            }
                        }
                    });
                    closeglobalRightOffcanvas();
                    AIZ.plugins.notify('success', '{{ translate("Value added") }}');
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    $button.prop('disabled', false);
                }
            });
        });
}

// Row-based variant tables (Computers create/edit) delete a whole <tr>.
function deleteProductVariant(element) {
    $(element).closest('tr.variant').remove();
}

function deleteProductVariantColumn(element) {
    var $col = $(element).closest('th');
    var classes = $col.attr('class').split(' ');
    var colClass = '';
    for(var i=0; i<classes.length; i++) {
        if(classes[i].indexOf('variant-column-') === 0) {
            colClass = classes[i];
            break;
        }
    }
    
    var $card = $col.closest('.variant-card');
    var fieldKey = $card.data('field-key');
    var $siblings = $card.find('th[class*="variant-column-"]');

    if ($siblings.length > 1) {
        $card.find('.' + colClass).remove();
        return;
    }

    var colorCode = String($card.data('color-code') || '');
    var $colors = $('#colors');

    if (!colorCode || !$colors.length) {
        $card.remove();
        return;
    }

    var selectedColors = ($colors.val() || []).filter(function (value) {
        return String(value) !== colorCode;
    });

    $colors.val(selectedColors);
    $colors.selectpicker('refresh');
    $colors.trigger('change');
}

// When a Storage multi-select on a variant row ends up with more than one value
// picked, split it into one row per storage value so each can carry its own
// Country/Condition/Code/Quantity/Price instead of merging them into one row.
$(document)
    .off('change.productStorageSplit', '.variant-cards-container select[name^="storage_"]')
    .on('change.productStorageSplit', '.variant-cards-container select[name^="storage_"]', function () {
        var $select = $(this);
        var $card = $select.closest('.variant-card');
        var fieldKey = $card.data('field-key');
        var values = ($select.val() || []).slice();

        if (!fieldKey || values.length <= 1) {
            return;
        }

        var firstValue = values.shift();
        $select.val([firstValue]).trigger('change');

        var existingCount = $card.find('th[class*="variant-column-"]').length;
        var $pending = $('#pending_storage_rows').empty();

        values.forEach(function (storageValue, idx) {
            var newRowKey = fieldKey + '_s' + (existingCount + idx + 1);
            $('<input>', {type: 'hidden', name: 'storage_rows_' + fieldKey + '[]', value: newRowKey}).appendTo($pending);
            $('<input>', {type: 'hidden', name: 'storage_' + newRowKey + '[]', value: storageValue}).appendTo($pending);
        });

        update_sku();
    });

function refreshProductColorActions() {
    var isActive = $('input[name="colors_active"]').is(':checked');
    var $colorItems = $('.product-color-items').empty();

    $('#colors option:selected').each(function () {
        var $option = $(this);
        var colorId = $option.attr('data-color-id');
        var colorCode = String($option.val());
        var colorName = $('<div>').html($option.attr('data-content') || '').text().trim() || $option.val();

        if (!colorId) {
            return;
        }

        var $row = $('<div>', {
            class: 'd-flex flex-wrap align-items-center border rounded px-3 py-2 mb-2 bg-white'
        });
        var $details = $('<div>', {
            class: 'd-flex align-items-center flex-grow-1 mr-3 mb-1 mt-1'
        });

        $('<span>', {class: 'size-20px d-inline-block rounded border mr-2 flex-shrink-0'})
            .css('background', colorCode)
            .appendTo($details);
        $('<div>')
            .append($('<div>', {class: 'fs-13 fw-600 text-dark', text: colorName}))
            .append($('<small>', {class: 'text-muted', text: colorCode}))
            .appendTo($details);
        $details.appendTo($row);

        var $buttons = $('<div>', {class: 'd-flex align-items-center'});
        @can('edit_color')
            $('<button>', {
                type: 'button',
                class: 'btn btn-soft-primary btn-sm mr-1',
                title: '{{ translate("Edit color name and code") }}'
            })
                .append($('<i>', {class: 'las la-pen mr-1'}))
                .append(document.createTextNode('{{ translate("Edit") }}'))
                .on('click', function () { editProductColor(colorId); })
                .appendTo($buttons);
        @endcan
        $('<button>', {
            type: 'button',
            class: 'btn btn-soft-danger btn-sm',
            title: '{{ translate("Remove color from this product") }}'
        })
            .append($('<i>', {class: 'las la-trash mr-1'}))
            .append(document.createTextNode('{{ translate("Delete") }}'))
            .on('click', function () { removeProductColor(colorId); })
            .appendTo($buttons);

        $buttons.appendTo($row);
        $row.appendTo($colorItems);
    });

    $('.product-color-actions').toggleClass('d-none', !isActive);
}

function editProductColor(colorId) {
    var $option = $('#colors option[data-color-id="' + colorId + '"]');

    if (!colorId || !$option.length) {
        AIZ.plugins.notify('warning', '{{ translate("Unable to find this color.") }}');
        return;
    }

    var $offcanvas = $('#globalRightOffcanvas');
    var $overlay = $('#globalRightOffcanvasOverlay');

    if (!colorId || !$offcanvas.length || !$overlay.length) {
        AIZ.plugins.notify('danger', '{{ translate("Unable to edit this color.") }}');
        return;
    }

    $offcanvas.addClass('active').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
    $overlay.addClass('active');
    $('body').addClass('body-no-scroll');

    $.ajax({
        type: 'POST',
        url: '{{ route("colors.edit") }}',
        data: {
            _token: AIZ.data.csrf,
            id: colorId
        },
        success: function (html) {
            $offcanvas.html(html).attr('data-product-color-old-code', $option.val());
            $offcanvas.find('#update-color').attr('id', 'update-product-color');
            AIZ.plugins.colorPicker();
            window.closeOffcanvas = function () {
                closeglobalRightOffcanvas();
            };
        },
        error: function () {
            $offcanvas.html('<p class="text-danger p-3">{{ translate("Failed to load") }}</p>');
        }
    });
}

function removeProductColor(colorId) {
    var $colors = $('#colors');
    var $option = $colors.find('option[data-color-id="' + colorId + '"]');

    if (!colorId || !$option.length) {
        AIZ.plugins.notify('warning', '{{ translate("Unable to find this color.") }}');
        return;
    }

    if (!window.confirm('{{ translate("Remove this color and its variant from this product?") }}')) {
        return;
    }

    var colorCode = String($option.val());
    var remainingColors = ($colors.val() || []).filter(function (value) {
        return String(value) !== colorCode;
    });

    $colors.val(remainingColors).selectpicker('refresh').trigger('change');
    refreshProductColorActions();
    AIZ.plugins.notify('success', '{{ translate("Color was removed from this product.") }}');
}

$(document)
    .off('input.productColorPicker change.productColorPicker', '#globalRightOffcanvas .aiz-color-picker')
    .on('input.productColorPicker change.productColorPicker', '#globalRightOffcanvas .aiz-color-picker', function () {
        $('#globalRightOffcanvas #code').val($(this).val());
    });

$(document)
    .off('click.productColorUpdate', '#update-product-color')
    .on('click.productColorUpdate', '#update-product-color', function () {
        var $button = $(this);
        var $offcanvas = $('#globalRightOffcanvas');
        var colorId = $offcanvas.find('#edit_color_id').val();
        var colorName = $.trim($offcanvas.find('#name').val());
        var colorCode = $.trim($offcanvas.find('#code').val());
        var oldCode = String($offcanvas.attr('data-product-color-old-code') || '');

        if (!colorName || !colorCode) {
            AIZ.plugins.notify('warning', '{{ translate("Please fill all required fields.") }}');
            return;
        }

        $button.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '{{ route("colors.update") }}',
            data: {
                _token: AIZ.data.csrf,
                id: colorId,
                name: colorName,
                code: colorCode
            },
            success: function () {
                var $colors = $('#colors');
                var selectedColors = ($colors.val() || []).map(function (value) {
                    return String(value) === oldCode ? colorCode : value;
                });
                var $option = $colors.find('option[data-color-id="' + colorId + '"]');
                var $content = $('<span>');
                $('<span>', {class: 'size-15px d-inline-block mr-2 rounded border'})
                    .css('background', colorCode)
                    .appendTo($content);
                $('<span>').text(colorName).appendTo($content);

                $option
                    .val(colorCode)
                    .attr('data-content', $content.prop('outerHTML'));

                $colors.selectpicker('refresh');
                $colors.val(selectedColors).selectpicker('refresh');
                if (oldCode !== colorCode) {
                    $colors.trigger('change');
                }
                refreshProductColorActions();
                closeglobalRightOffcanvas();
                AIZ.plugins.notify('success', '{{ translate("Color has been updated successfully") }}');
            },
            error: function (xhr) {
                var firstError = Object.values(xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {})[0];
                AIZ.plugins.notify('danger', firstError ? firstError[0] : '{{ translate("Something went wrong") }}');
                $button.prop('disabled', false);
            }
        });
    });

$(document)
    .off('change.productColorActions', '#colors, input[name="colors_active"]')
    .on('change.productColorActions', '#colors, input[name="colors_active"]', refreshProductColorActions);

$(refreshProductColorActions);
