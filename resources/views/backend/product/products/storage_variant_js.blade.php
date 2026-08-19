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
