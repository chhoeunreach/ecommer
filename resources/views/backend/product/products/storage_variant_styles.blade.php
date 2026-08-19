<style>
    /* Appended to <body> when open, so it can never be clipped by the table's scroll box. */
    .variant-attr-dropdown-menu {
        background: #fff;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .14);
        max-height: 230px;
        overflow-y: auto;
        padding: 6px;
        z-index: 10000;
    }

    .variant-attr-dropdown-menu .variant-attr-option {
        align-items: center;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        font-size: 13px;
        gap: 8px;
        margin: 0;
        padding: 7px 8px;
    }

    .variant-attr-dropdown-menu .variant-attr-option:hover {
        background: #f1f5f9;
    }

    .variant-attr-dropdown-menu .variant-attr-option input[type="checkbox"] {
        margin: 0;
    }

    #sku_combination .product-variant-table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    #sku_combination .product-variant-table thead td {
        background: #f8fafc;
        border-color: #e5eaf1;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .025em;
        padding: 14px 12px;
        text-transform: uppercase;
        vertical-align: middle;
    }

    #sku_combination .product-variant-table tbody td {
        background: #fff;
        border-color: #e8edf3;
        padding: 16px 12px;
        vertical-align: top;
    }

    #sku_combination .product-variant-table tbody tr:hover > td {
        background: #fbfdff;
    }

    #sku_combination .product-variant-table .form-control,
    #sku_combination .product-variant-table .bootstrap-select > .dropdown-toggle {
        border-color: #dbe2ea;
        border-radius: 7px;
        min-height: 42px;
    }

    #sku_combination .product-variant-table .form-control:focus,
    #sku_combination .product-variant-table .bootstrap-select.show > .dropdown-toggle {
        border-color: #80b7ff;
        box-shadow: 0 0 0 3px rgba(0, 128, 254, .10);
    }

    #sku_combination .product-variant-table .variant-name-badge {
        background: #eef6ff;
        border: 1px solid #d7eaff;
        border-radius: 999px;
        color: #1967b3;
        display: inline-flex;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        padding: 7px 10px;
    }

    #sku_combination .product-variant-table .variant-storage-help {
        color: #94a3b8;
        display: block;
        font-size: 11px;
        line-height: 1.35;
        margin-top: 7px;
    }

    #sku_combination .product-variant-table .storage-stock-field {
        background: #f8fafc;
        border: 1px solid #e6ebf1;
        border-radius: 8px;
        padding: 8px;
    }

    #sku_combination .product-variant-table .storage-stock-field:last-child {
        margin-bottom: 0 !important;
    }

    #sku_combination .product-variant-table .storage-stock-field small {
        color: #64748b !important;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .04em;
        line-height: 1;
        text-transform: uppercase;
    }

    #sku_combination .product-variant-table .storage-stock-field .form-control {
        background: #fff;
        min-height: 38px;
    }

    #sku_combination .product-variant-table .variant-photo-uploader {
        cursor: pointer;
        width: 100%;
    }

    #sku_combination .product-variant-table .variant-photo-uploader .file-amount {
        align-items: center;
        background: #f8fafc;
        border: 1px dashed #b9c6d5;
        color: #475569;
        cursor: pointer;
        display: flex;
        font-size: 13px;
        font-weight: 600;
        justify-content: center;
        min-height: 42px;
        transition: background-color .2s ease, border-color .2s ease, color .2s ease;
    }

    #sku_combination .product-variant-table .variant-photo-uploader:hover .file-amount {
        background: #eef6ff;
        border-color: #80b7ff;
        color: #0878e8;
    }

    #sku_combination .product-variant-table .variant-photo-uploader .file-amount i {
        font-size: 18px;
    }

    #sku_combination .product-variant-table .variant-action-cell .btn {
        border-radius: 7px;
        height: 38px;
        width: 38px;
    }

    #sku_combination .product-variant-table .variant-attr-toggle {
        align-items: center;
        background-color: #fff;
        cursor: pointer;
        display: flex;
        font-size: 13px;
        justify-content: space-between;
        text-align: left;
    }

    #sku_combination .product-variant-table .variant-attr-toggle:hover {
        border-color: #b9c6d5;
    }

    #sku_combination .product-variant-table .variant-attr-toggle-label {
        color: #1e293b;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #sku_combination .product-variant-table .variant-attr-toggle .variant-attr-caret {
        color: #94a3b8;
        flex-shrink: 0;
        font-size: 12px;
        margin-left: 8px;
    }

    #sku_combination .product-variant-table .add-variant-attr-value {
        text-decoration: none;
    }

    #sku_combination .product-variant-table .add-variant-attr-value:hover {
        text-decoration: underline;
    }

    #sku_combination .product-variant-table .add-variant-attr-value i {
        font-size: 11px;
    }

    @media (max-width: 991px) {
        #sku_combination .product-variant-table thead td,
        #sku_combination .product-variant-table tbody td {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>
