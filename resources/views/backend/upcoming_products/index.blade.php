@extends('backend.layouts.app')

@section('content')
<style>
    .shadcn-card { background: #fff; border: 1px solid #e2e8f0; border-radius: .5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,.05); }
    .shadcn-header { font-weight: 600; font-size: 1.125rem; color: #0f172a; }
    .shadcn-btn { background: #0f172a; color: #fff; border-radius: .375rem; padding: .5rem 1rem; font-size: .875rem; font-weight: 500; transition: background .2s; }
    .shadcn-btn:hover { background: #334155; color: #fff; }
    .shadcn-table th { background-color: #f8fafc; color: #64748b; font-weight: 500; text-transform: uppercase; font-size: .75rem; letter-spacing: .05em; border-bottom: 1px solid #e2e8f0; }
    .shadcn-table td { vertical-align: middle; color: #334155; font-size: .875rem; border-bottom: 1px solid #e2e8f0; }
    .up-type-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .up-type-pill--pre_order { background: #dbeafe; color: #1d4ed8; }
    .up-type-pill--coming_soon { background: #fef3c7; color: #b45309; }
    .up-filter-tabs a { display: inline-block; padding: 6px 14px; border-radius: 999px; font-size: 13px; color: #475569; margin-right: 4px; }
    .up-filter-tabs a.active { background: #0f172a; color: #fff; }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 shadcn-header">{{ translate('Pre-order & Coming Soon') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.pre-order-requests.index') }}" class="btn btn-soft-secondary mr-2">
                <i class="las la-inbox"></i> {{ translate('View Requests') }}
            </a>
            <a href="{{ route('admin.upcoming-products.create') }}" class="btn shadcn-btn">
                <span>{{ translate('Add New Product') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card shadcn-card">
    <div class="card-header border-bottom-0 flex-wrap">
        <div class="up-filter-tabs mb-2 mb-md-0">
            <a href="{{ route('admin.upcoming-products.index', ['search' => $sort_search]) }}" class="{{ !$type ? 'active' : '' }}">{{ translate('All') }}</a>
            <a href="{{ route('admin.upcoming-products.index', ['type' => 'pre_order', 'search' => $sort_search]) }}" class="{{ $type == 'pre_order' ? 'active' : '' }}">{{ translate('Pre-order') }}</a>
            <a href="{{ route('admin.upcoming-products.index', ['type' => 'coming_soon', 'search' => $sort_search]) }}" class="{{ $type == 'coming_soon' ? 'active' : '' }}">{{ translate('Coming Soon') }}</a>
        </div>
        <form id="sort_upcoming_products" action="" method="GET">
            @if ($type)
                <input type="hidden" name="type" value="{{ $type }}">
            @endif
            <div style="min-width: 200px;">
                <input type="text" class="form-control" name="search" value="{{ $sort_search }}" placeholder="{{ translate('Type name & Enter') }}">
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table aiz-table mb-0 shadcn-table">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{ translate('Product') }}</th>
                        <th>{{ translate('Type') }}</th>
                        <th data-breakpoints="md">{{ translate('Price') }}</th>
                        <th data-breakpoints="md">{{ translate('Release Date') }}</th>
                        <th data-breakpoints="lg">{{ translate('Requests') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($upcoming_products as $key => $upcoming_product)
                        <tr>
                            <td>{{ ($key + 1) + ($upcoming_products->currentPage() - 1) * $upcoming_products->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ uploaded_asset($upcoming_product->thumbnail_img) }}" alt="" class="h-50px w-50px rounded mr-2" style="object-fit: contain; background: #f8fafc;"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <span>{{ $upcoming_product->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="up-type-pill up-type-pill--{{ $upcoming_product->type }}">
                                    {{ $upcoming_product->isPreOrder() ? translate('Pre-order') : translate('Coming Soon') }}
                                </span>
                            </td>
                            <td>
                                {{ $upcoming_product->price !== null ? single_price($upcoming_product->price) : '—' }}
                                @if ($upcoming_product->deposit_amount)
                                    <div class="fs-12 text-muted">{{ translate('Deposit') }}: {{ single_price($upcoming_product->deposit_amount) }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $upcoming_product->release_date ? $upcoming_product->release_date->format('d M Y H:i') : '—' }}
                                @if ($upcoming_product->isPreOrder() && $upcoming_product->preorder_end_date)
                                    <div class="fs-12 {{ $upcoming_product->isPreOrderOpen() ? 'text-muted' : 'text-danger' }}">
                                        {{ $upcoming_product->isPreOrderOpen() ? translate('Closes') : translate('Closed') }}: {{ $upcoming_product->preorder_end_date->format('d M Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pre-order-requests.index', ['product_id' => $upcoming_product->id]) }}" class="fw-600">
                                    {{ $upcoming_product->requests_count }}
                                </a>
                                @if ($upcoming_product->pending_requests_count > 0)
                                    <span class="badge badge-inline badge-warning">{{ $upcoming_product->pending_requests_count }} {{ translate('pending') }}</span>
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{ $upcoming_product->id }}" type="checkbox" @checked($upcoming_product->status == 1)>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('admin.upcoming-products.edit', $upcoming_product->id) }}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.upcoming-products.destroy', $upcoming_product->id) }}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">{{ translate('No products yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="aiz-pagination px-3">
                {{ $upcoming_products->appends(request()->input())->links() }}
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
        function update_status(el) {
            var status = el.checked ? 1 : 0;
            $.post('{{ route('admin.upcoming-products.update_status') }}', {_token: '{{ csrf_token() }}', id: el.value, status: status}, function (data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
