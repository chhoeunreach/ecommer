@extends('backend.layouts.app')

@section('content')
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Category Attribute Settings') }}</h1>
                    <p class="fs-13 text-muted mb-0">{{ translate('Assign attributes to a category. They are auto-loaded as product variant options when a product is created under that category.') }}</p>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem">
            <div class="row gutters-5">
                <div class="col-xl-8">
                    <div class="border border-gray-300 rounded-2 mb-4">
                        <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                            <form action="{{ route('products.category-attribute-settings.update') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{ translate('Select Category') }}</label>
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" onchange="loadCategoryAttributes(this.value)">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if($category->id == $selectedCategoryId) selected @endif>{{ $category->getTranslation('name') }}</option>
                                            @if ($category->childrenCategories)
                                                @foreach ($category->childrenCategories as $child)
                                                    <option value="{{ $child->id }}" @if($child->id == $selectedCategoryId) selected @endif>&nbsp;&nbsp;&nbsp;{{ $child->getTranslation('name') }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500 d-block">{{ translate('Attributes') }}</label>
                                    <div id="attribute-checkboxes">
                                        @foreach ($attributes as $attribute)
                                            <label class="aiz-checkbox aiz-checkbox-list mr-4 mb-2">
                                                <input type="checkbox" name="filtering_attributes[]" value="{{ $attribute->id }}" @if(in_array($attribute->id, $selectedAttributeIds)) checked @endif>
                                                <span></span>
                                                <span class="fs-14 fw-400">{{ $attribute->getTranslation('name') }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function loadCategoryAttributes(categoryId) {
        if (!categoryId) {
            return;
        }
        $.post('{{ route('products.get-category-attributes') }}', {
            _token: AIZ.data.csrf,
            category_id: categoryId
        }, function(response) {
            var ids = (response.attributes || []).map(function(attribute) {
                return String(attribute.id);
            });
            $('#attribute-checkboxes input[type="checkbox"]').each(function() {
                $(this).prop('checked', ids.indexOf(String($(this).val())) !== -1);
            });
        });
    }
</script>
@endsection