@extends('backend.layouts.app')

@section('content')
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 fw-700">{{ translate('Edit Accessory') }}</h1>
                </div>
            </div>
        </div>

        <div class="px-3 px-md-2rem mb-4">
            <form action="{{route('admin.accessories.update', ['accessory' => $accessory->id])}}" method="POST" enctype="multipart/form-data" id="aizSubmitForm">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="row gutters-5">
                    <div class="col-xl-8">
                        
                        <!-- Basic Information -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('Basic Information')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Accessory Name')}} <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" value="{{ $accessory->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Brand')}}</label>
                                            <select class="form-control aiz-selectpicker" name="brand_id" data-live-search="true">
                                                <option value="">{{ translate('Select Brand') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}" @if($accessory->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Tags')}}</label>
                                            <input type="text" class="form-control aiz-tag-input" name="tags" placeholder="{{ translate('Type and hit enter to add a tag') }}" value="{{ implode(',', $tags ?? []) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Description')}}</label>
                                    <textarea name="description" rows="5" class="form-control">{{ $accessory->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Price and Discount -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('Price & Discount')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Price')}} <span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="0.01" placeholder="{{translate('Price')}}" name="price" class="form-control" value="{{ $accessory->price }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount')}}</label>
                                            <input type="number" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" value="{{ $accessory->discount }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount Type')}}</label>
                                            <select class="form-control aiz-selectpicker" name="discount_type">
                                                <option value="amount" @if($accessory->discount_type == 'amount') selected @endif>{{translate('Flat')}}</option>
                                                <option value="percent" @if($accessory->discount_type == 'percent') selected @endif>{{translate('Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Discount Date Range')}}</label>
                                            @php
                                                $date_range = '';
                                                if($accessory->discount_start_date && $accessory->discount_end_date){
                                                    $date_range = date('d-m-Y H:i:s', $accessory->discount_start_date) . ' to ' . date('d-m-Y H:i:s', $accessory->discount_end_date);
                                                }
                                            @endphp
                                            <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" value="{{ $date_range }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Warranty -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Warranty')}}</h5>
                                <div class="form-group d-flex align-items-center mb-0">
                                    <label class="aiz-switch aiz-switch-success mb-0 mr-3">
                                        <input type="checkbox" name="has_warranty" id="has_warranty" value="1" onchange="toggleWarranty()" @if($accessory->has_warranty) checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400">{{translate('Enable warranty for this accessory')}}</span>
                                </div>
                                <div class="form-group mt-3" id="warranty_selection" style="display: {{ $accessory->has_warranty ? 'block' : 'none' }};">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Select Warranty')}}</label>
                                    <select class="form-control aiz-selectpicker" name="warranty_id" data-live-search="true">
                                        <option value="">{{ translate('Select Warranty') }}</option>
                                        @foreach ($warranties as $warranty)
                                            <option value="{{ $warranty->id }}" @if($accessory->warranty_id == $warranty->id) selected @endif>{{ $warranty->getTranslation('text') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Meta Tags -->
                        <div class="border border-gray-300 rounded-2 mt-4 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                    <h5 class="fs-16 fw-700">{{translate('SEO Meta Tags')}}</h5>
                                </div>
                                <div class="row gutters-5">
                                    <!-- Meta Title -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Meta Title')}}</label>
                                            <input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}" value="{{ $accessory->meta_title }}">
                                        </div>
                                    </div>
                                    <!-- Description -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Description')}}</label>
                                            <textarea name="meta_description" rows="5" class="form-control">{{ $accessory->meta_description }}</textarea>
                                        </div>
                                    </div>
                                    <!-- Meta Image -->
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label class="col-from-label fs-14 fw-500">{{translate('Meta Image')}}</label>
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="meta_img" class="selected-files" value="{{ $accessory->meta_img }}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-xl-4">
                        <!-- Product Configuration -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Product Configuration') }}</h5>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mt-3 mb-2">
                                        <div class="btn-group" role="group" style="border: 1px solid #e2e5ec; border-radius: 8px; overflow: hidden; background: #f8f9fa;">
                                            <label class="mb-0 btn btn-sm px-4 fw-600 @if($accessory->status == 1) bg-success text-white @else text-muted @endif" id="btn-active" onclick="setPublished(true)" style="border-radius: 0; box-shadow: none; cursor: pointer;">
                                                {{ translate('Active') }}
                                            </label>
                                            <label class="mb-0 btn btn-sm px-4 fw-600 @if($accessory->status != 1) bg-danger text-white @else text-muted @endif" id="btn-disabled" onclick="setPublished(false)" style="border-radius: 0; box-shadow: none; cursor: pointer;">
                                                {{ translate('Disabled') }}
                                            </label>
                                        </div>
                                        <input value="1" type="checkbox" name="published" id="real-published-checkbox" @if($accessory->status == 1) checked @endif style="display:none;">
                                        <script>
                                            function setPublished(isActive) {
                                                let checkbox = document.getElementById('real-published-checkbox');
                                                let btnActive = document.getElementById('btn-active');
                                                let btnDisabled = document.getElementById('btn-disabled');
                                                checkbox.checked = isActive;
                                                if (isActive) {
                                                    btnActive.classList.add('bg-success', 'text-white');
                                                    btnActive.classList.remove('text-muted');
                                                    btnDisabled.classList.remove('bg-danger', 'text-white');
                                                    btnDisabled.classList.add('text-muted');
                                                } else {
                                                    btnDisabled.classList.add('bg-danger', 'text-white');
                                                    btnDisabled.classList.remove('text-muted');
                                                    btnActive.classList.remove('bg-success', 'text-white');
                                                    btnActive.classList.add('text-muted');
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="border border-gray-300 rounded-2 mb-4">
                            <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                                <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Accessory Image')}}</h5>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Thumbnail Image')}}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files" value="{{ $accessory->thumbnail_img }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="col-from-label fs-14 fw-500">{{translate('Gallery Images')}}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="gallery" class="selected-files" value="{{ $accessory->gallery }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary w-200px fs-14 fw-700">{{ translate('Update Accessory') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function toggleWarranty() {
        if($('#has_warranty').is(':checked')) {
            $('#warranty_selection').show();
        } else {
            $('#warranty_selection').hide();
        }
    }

    function updateStatusLabel(el) {
        let label = $(el).closest('.d-flex').find('.status-label');
        if(el.checked) {
            label.text('{{ translate('Active') }}').removeClass('text-danger').addClass('text-success');
        } else {
            label.text('{{ translate('Disabled') }}').removeClass('text-success').addClass('text-danger');
        }
    }
</script>
@endsection
