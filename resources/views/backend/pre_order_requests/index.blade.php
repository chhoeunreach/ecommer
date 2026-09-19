@extends('backend.layouts.app')

@section('content')
<style>
    .shadcn-card { background: #fff; border: 1px solid #e2e8f0; border-radius: .5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,.05); }
    .shadcn-header { font-weight: 600; font-size: 1.125rem; color: #0f172a; }
    .shadcn-table th { background-color: #f8fafc; color: #64748b; font-weight: 500; text-transform: uppercase; font-size: .75rem; letter-spacing: .05em; border-bottom: 1px solid #e2e8f0; }
    .shadcn-table td { vertical-align: middle; color: #334155; font-size: .875rem; border-bottom: 1px solid #e2e8f0; }
    .por-status { min-width: 130px; font-size: 13px; font-weight: 600; border-radius: 6px; }
    .por-status[data-status="pending"] { color: #b45309; background: #fffbeb; border-color: #fde68a; }
    .por-status[data-status="confirmed"] { color: #1d4ed8; background: #eff6ff; border-color: #bfdbfe; }
    .por-status[data-status="completed"] { color: #15803d; background: #f0fdf4; border-color: #bbf7d0; }
    .por-status[data-status="cancelled"] { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 shadcn-header">{{ translate('Pre-order Requests') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.upcoming-products.index') }}" class="btn btn-soft-secondary">
                <i class="las la-box"></i> {{ translate('Manage Products') }}
            </a>
        </div>
    </div>
</div>

<div class="card shadcn-card">
    <form class="card-header border-bottom-0 row gutters-5 mx-0" action="" method="GET">
        <div class="col-md-3 mb-2 mb-md-0">
            <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true" onchange="this.form.submit()">
                <option value="">{{ translate('All Products') }}</option>
                @foreach ($upcoming_products as $up)
                    <option value="{{ $up->id }}" @selected($product_id == $up->id)>{{ $up->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
            <select name="type" class="form-control aiz-selectpicker" onchange="this.form.submit()">
                <option value="">{{ translate('All Types') }}</option>
                <option value="pre_order" @selected($type == 'pre_order')>{{ translate('Pre-order') }}</option>
                <option value="notify" @selected($type == 'notify')>{{ translate('Notify Me') }}</option>
            </select>
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
            <select name="status" class="form-control aiz-selectpicker" onchange="this.form.submit()">
                <option value="">{{ translate('All Status') }}</option>
                @foreach (\App\Models\PreOrderRequest::STATUSES as $s)
                    <option value="{{ $s }}" @selected($status == $s)>{{ translate(ucfirst($s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="search" value="{{ $sort_search }}" placeholder="{{ translate('Search name, phone or email & Enter') }}">
        </div>
    </form>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table aiz-table mb-0 shadcn-table">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{ translate('Customer') }}</th>
                        <th>{{ translate('Product') }}</th>
                        <th data-breakpoints="md">{{ translate('Type') }}</th>
                        <th data-breakpoints="md">{{ translate('Qty') }}</th>
                        <th data-breakpoints="lg">{{ translate('Note') }}</th>
                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pre_order_requests as $key => $por)
                        <tr>
                            <td>{{ ($key + 1) + ($pre_order_requests->currentPage() - 1) * $pre_order_requests->perPage() }}</td>
                            <td>
                                <div class="fw-600">{{ $por->name }}</div>
                                <a href="tel:{{ $por->phone }}" class="fs-12 d-block">{{ $por->phone }}</a>
                                @if ($por->email)
                                    <a href="mailto:{{ $por->email }}" class="fs-12 text-muted">{{ $por->email }}</a>
                                @endif
                            </td>
                            <td>{{ $por->upcomingProduct->name ?? translate('Deleted product') }}</td>
                            <td>
                                @if ($por->type == 'pre_order')
                                    <span class="badge badge-inline badge-primary">{{ translate('Pre-order') }}</span>
                                @else
                                    <span class="badge badge-inline badge-secondary">{{ translate('Notify Me') }}</span>
                                @endif
                            </td>
                            <td>{{ $por->quantity }}</td>
                            <td style="max-width: 240px; white-space: normal;">{{ $por->note ?: '—' }}</td>
                            <td class="text-nowrap">{{ $por->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <select class="form-control form-control-sm por-status" data-id="{{ $por->id }}" data-status="{{ $por->status }}" onchange="update_request_status(this)">
                                    @foreach (\App\Models\PreOrderRequest::STATUSES as $s)
                                        <option value="{{ $s }}" @selected($por->status == $s)>{{ translate(ucfirst($s)) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-right">
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.pre-order-requests.destroy', $por->id) }}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">{{ translate('No requests found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="aiz-pagination px-3">
                {{ $pre_order_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_request_status(el) {
            $.post('{{ route('admin.pre-order-requests.update_status') }}', {_token: '{{ csrf_token() }}', id: $(el).data('id'), status: el.value})
                .done(function () {
                    el.setAttribute('data-status', el.value);
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                })
                .fail(function () {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                });
        }
    </script>
@endsection
