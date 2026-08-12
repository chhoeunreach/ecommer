@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Auction Products') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            @can('add_auction_product')
                <a href="{{ route('products.create', ['auction' => 1]) }}" class="btn btn-circle btn-info mr-2">
                    <span>{{ translate('Add New Auction Product') }}</span>
                </a>
            @endcan
            <a href="{{ route('products.all') }}" class="btn btn-circle btn-outline-info">
                <span>{{ translate('Convert an Existing Product') }}</span>
            </a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6">{{ translate('All Auction Products') }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>{{ translate('Product') }}</th>
                    <th>{{ translate('Starting Bid') }}</th>
                    <th>{{ translate('Highest Bid') }}</th>
                    <th>{{ translate('Auction Period') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th width="10%" class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $key => $product)
                    <tr>
                        <td>{{ ($key + 1) + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" class="w-40px h-40px rounded mr-2" style="object-fit: cover;" alt="{{ $product->getTranslation('name') }}">
                                <span>{{ $product->getTranslation('name') }}</span>
                            </div>
                        </td>
                        <td>{{ single_price($product->starting_bid) }}</td>
                        <td>{{ $product->bids_max_amount ? single_price($product->bids_max_amount) : '--' }}</td>
                        <td>
                            @if ($product->auction_start_date && $product->auction_end_date)
                                {{ date('d M Y, h:i A', $product->auction_start_date) }} &mdash; {{ date('d M Y, h:i A', $product->auction_end_date) }}
                            @else
                                <span class="text-danger">{{ translate('Not set') }}</span>
                            @endif
                        </td>
                        <td>
                            @if (!$product->auction_start_date || !$product->auction_end_date)
                                <span class="badge badge-inline badge-soft-secondary">{{ translate('Not scheduled') }}</span>
                            @elseif ($product->auction_start_date > strtotime('now'))
                                <span class="badge badge-inline badge-soft-info">{{ translate('Upcoming') }}</span>
                            @elseif ($product->auction_end_date >= strtotime('now'))
                                <span class="badge badge-inline badge-soft-success">{{ translate('Running') }}</span>
                            @else
                                <span class="badge badge-inline badge-soft-danger">{{ translate('Ended') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @can('edit_auction_product')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('products.admin.edit', $product->id) }}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            {{ translate('No auction products yet. Edit any product and enable Auction to get started.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection
