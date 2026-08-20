<style>
    .variant-cards-container .variant-card {
        border: 1px solid #eef2f6;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 30px;
        background: #fff;
    }

    .variant-cards-container .card-header {
        background: #ffffff;
        border-bottom: 1px dashed #eef2f6;
        padding: 20px 24px;
    }

    .variant-cards-container .badge-custom {
        background-color: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .variant-cards-container .table-responsive {
        overflow-x: auto;
        padding: 10px 24px 24px;
    }

    .variant-cards-container table {
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
        font-family: inherit;
    }

    .variant-cards-container th {
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }

    .variant-cards-container td {
        padding: 0 12px;
        vertical-align: middle;
        border: none;
    }

    .variant-cards-container td.attribute-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 130px;
        text-align: right;
        padding-right: 24px;
    }

    .variant-cards-container .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        min-height: 46px;
        font-size: 14px;
        color: #1e293b;
        background-color: #f8fafc;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .variant-cards-container .form-control:focus,
    .variant-cards-container .form-control:hover {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    /* Style for Bootstrap Select */
    .variant-cards-container .bootstrap-select > .dropdown-toggle.btn-light {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #1e293b;
        font-size: 14px;
        padding: 12px 16px;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .variant-cards-container .bootstrap-select > .dropdown-toggle.btn-light:focus,
    .variant-cards-container .bootstrap-select > .dropdown-toggle.btn-light:hover {
        background-color: #ffffff;
        outline: none !important;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
    }

    .variant-cards-container .bootstrap-select .filter-option-inner-inner {
        color: #1e293b;
    }

    .variant-cards-container .btn-delete-col {
        background: transparent;
        color: #94a3b8;
        border: 1px solid transparent;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .variant-cards-container .btn-delete-col:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .variant-cards-container .variant-photo-uploader .file-amount {
        align-items: center;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #64748b;
        cursor: pointer;
        display: flex;
        font-size: 13px;
        font-weight: 600;
        justify-content: center;
        min-height: 46px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .variant-cards-container .variant-photo-uploader:hover .file-amount {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #3b82f6;
    }
</style>
