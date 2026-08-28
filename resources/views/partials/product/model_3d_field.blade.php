@php
    $model3dValue = old('model_3d', isset($product) ? $product->model_3d : '');
    $model3dUploadId = old('model_3d_upload');

    if (!$model3dUploadId && $model3dValue && !filter_var($model3dValue, FILTER_VALIDATE_URL)) {
        $model3dUploadId = \App\Models\Upload::where('file_name', $model3dValue)
            ->whereIn('extension', ['glb', 'gltf'])
            ->value('id');
    }

    $model3dLinkValue = filter_var($model3dValue, FILTER_VALIDATE_URL) ? $model3dValue : '';
@endphp

<div class="form-group mb-2 mt-3">
    <label class="col-from-label fs-14 fw-500">{{ translate('3D Model') }}</label>
    <small class="d-block text-muted fs-12 fw-400 mb-2">
        {{ translate('Upload a .glb or .gltf file, or paste a Sketchfab/direct model URL. An uploaded file takes priority over the URL.') }}
    </small>

    <div class="input-group" data-toggle="aizuploader" data-type="model" data-multiple="false">
        <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">
                {{ translate('Browse 3D Model') }}
            </div>
        </div>
        <div class="form-control file-amount">{{ translate('Choose .glb or .gltf file') }}</div>
        <input type="hidden" name="model_3d_upload" class="selected-files" value="{{ $model3dUploadId }}">
    </div>
    <div class="file-preview box sm"></div>

    <div class="d-flex align-items-center my-3">
        <span class="border-top flex-grow-1"></span>
        <span class="px-3 text-muted fs-11 text-uppercase">{{ translate('or use a link') }}</span>
        <span class="border-top flex-grow-1"></span>
    </div>

    <input type="url" class="form-control" name="model_3d" value="{{ $model3dLinkValue }}"
        placeholder="{{ translate('Sketchfab embed URL or direct .glb/.gltf URL') }}">
</div>
