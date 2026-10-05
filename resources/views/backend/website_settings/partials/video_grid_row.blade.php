@php
    $platformLabels = ['youtube' => 'YouTube', 'facebook' => 'Facebook', 'tiktok' => 'TikTok'];
    $platformIcons = ['youtube' => 'lab la-youtube', 'facebook' => 'lab la-facebook', 'tiktok' => 'lab la-tiktok'];
    $platform = $video['platform'] ?? null;
@endphp
<div class="vg-row">
    <span class="vg-num">{{ is_numeric($i) ? $i + 1 : '' }}</span>
    <div>
        <label class="fs-12 fw-600">{{ translate('Title (optional)') }}</label>
        <input type="text" class="form-control" name="videos[{{ $i }}][title]" maxlength="120"
            value="{{ $video['title'] ?? '' }}" placeholder="{{ translate('e.g. iPhone 17 unboxing') }}">
    </div>
    <div>
        <label class="fs-12 fw-600">{{ translate('Video Link') }} <span class="text-danger">*</span></label>
        <input type="url" class="form-control" name="videos[{{ $i }}][url]" maxlength="1000" required
            value="{{ $video['url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=… · https://www.tiktok.com/@user/video/… · https://www.facebook.com/…/videos/…">
        @if ($platform && isset($platformLabels[$platform]))
            <span class="vg-platform"><i class="{{ $platformIcons[$platform] }}"></i>{{ $platformLabels[$platform] }}</span>
        @endif
    </div>
    <div class="vg-active">
        <input type="hidden" name="videos[{{ $i }}][active]" value="0">
        <label class="aiz-switch aiz-switch-success mb-0 mr-2 align-middle">
            <input type="checkbox" name="videos[{{ $i }}][active]" value="1" @checked($video['active'] ?? true)>
            <span></span>
        </label>
        <span class="fs-12 align-middle">{{ translate('Active') }}</span>
    </div>
    <button type="button" class="btn btn-soft-danger btn-icon btn-sm vg-remove" title="{{ translate('Remove') }}">
        <i class="las la-trash"></i>
    </button>
    <div class="vg-preview" hidden
        @if ($platform && !empty($video['embed_id']))
            data-stored-src="{{ \App\Support\PromoVideo::embedUrl($video, false) }}"
            data-stored-vertical="{{ !empty($video['vertical']) ? 1 : 0 }}"
        @endif>
        <div class="vg-preview__frame"></div>
        <p class="vg-preview__msg"></p>
    </div>
</div>
