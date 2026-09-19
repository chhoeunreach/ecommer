<style>
    .up-type-option input { position: absolute; opacity: 0; pointer-events: none; }
    .up-type-card {
        display: flex; gap: 12px; align-items: flex-start; height: 100%;
        padding: 14px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px;
        cursor: pointer; transition: border-color .2s, background .2s;
    }
    .up-type-card i { font-size: 26px; color: #94a3b8; line-height: 1; }
    .up-type-card strong { display: block; font-size: 14px; color: #0f172a; }
    .up-type-card small { display: block; color: #64748b; font-size: 12px; margin-top: 2px; }
    .up-type-option input:checked + .up-type-card { border-color: #2563eb; background: #eff6ff; }
    .up-type-option input:checked + .up-type-card i { color: #2563eb; }
</style>
<script>
    function toggleUpcomingType() {
        var isPreOrder = $('input[name="type"]:checked').val() === 'pre_order';
        $('.up-preorder-only').toggle(isPreOrder);
    }

    function updateStatusLabel(el) {
        let label = $(el).closest('.d-flex').find('.status-label');
        if (el.checked) {
            label.text('{{ translate('Active') }}').removeClass('text-danger').addClass('text-success');
        } else {
            label.text('{{ translate('Disabled') }}').removeClass('text-success').addClass('text-danger');
        }
    }

    $(document).ready(toggleUpcomingType);
</script>
