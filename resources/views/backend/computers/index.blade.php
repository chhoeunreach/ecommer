@extends('backend.layouts.app')

@section('content')
<style>
    .shadcn-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .shadcn-header {
        font-weight: 600;
        font-size: 1.125rem;
        color: #0f172a;
    }
    .shadcn-btn {
        background: #0f172a;
        color: #fff;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background 0.2s;
    }
    .shadcn-btn:hover {
        background: #334155;
        color: #fff;
    }
    .shadcn-table th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }
    .shadcn-table td {
        vertical-align: middle;
        color: #334155;
        font-size: 0.875rem;
        border-bottom: 1px solid #e2e8f0;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 shadcn-header">{{translate('All Computers')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.computers.create') }}" class="btn shadcn-btn">
                <span>{{translate('Add New Computer')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card shadcn-card">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0 h6">{{translate('Computers')}}</h5>
        <div class="pull-right clearfix">
            <form class="" id="sort_computers" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table aiz-table mb-0 shadcn-table">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Thumbnail')}}</th>
                        <th>{{translate('Price')}}</th>
                        <th data-breakpoints="lg">{{translate('Status')}}</th>
                        <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($computers as $key => $computer)
                        <tr>
                            <td>{{ ($key+1) + ($computers->currentPage() - 1)*$computers->perPage() }}</td>
                            <td>{{ $computer->name }}</td>
                            <td>
                                @if ($computer->thumbnail_img != null)
                                    <img src="{{ uploaded_asset($computer->thumbnail_img) }}" alt="Image" class="h-50px w-50px rounded">
                                @else
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="Image" class="h-50px w-50px rounded">
                                @endif
                            </td>
                            <td>{{ single_price($computer->price) }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{ $computer->id }}" type="checkbox" <?php if($computer->status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('admin.computers.edit', ['computer'=>$computer->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('admin.computers.destroy', $computer->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $computers->appends(request()->input())->links() }}
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
        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.computers.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Computer status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
