<style>
    .variant-cards-container .variant-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .variant-cards-container .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }

    .variant-cards-container .table-responsive {
        overflow-x: auto;
    }

    .variant-cards-container table {
        border-collapse: collapse;
        width: 100%;
        font-family: inherit;
    }

    .variant-cards-container th, .variant-cards-container td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
    }

    .variant-cards-container td.border-right,
    .variant-cards-container th.border-right {
        border-right: 1px solid #e2e8f0;
    }

    .variant-cards-container th.bg-soft-secondary,
    .variant-cards-container td.bg-light {
        background-color: #f8fafc;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .variant-cards-container td.variant-column {
        background-color: #ffffff;
    }

    .variant-cards-container .form-control {
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        min-height: 42px;
        font-size: 14px;
        color: #334155;
        box-shadow: 0 1px 2px rgba(0,0,0,0.01);
    }

    .variant-cards-container .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* Style for Bootstrap Select */
    .variant-cards-container .bootstrap-select > .dropdown-toggle.btn-light {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #334155;
        font-size: 14px;
        padding: 10px 14px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.01);
    }

    .variant-cards-container .bootstrap-select > .dropdown-toggle.btn-light:focus {
        outline: none !important;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }

    .variant-cards-container .bootstrap-select .filter-option-inner-inner {
        color: #334155;
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
        min-height: 42px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .variant-cards-container .variant-photo-uploader:hover .file-amount {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #3b82f6;
    }
</style>
