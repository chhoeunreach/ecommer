@props([
    'name',
    'value',
    'checked' => false,
    'color' => null,
])

<label class="computer-option mb-0">
    <input type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        @if($checked) checked @endif
        onchange="getVariantPrice(this)">
    <span class="computer-option__surface">
        @if($color)
            <span class="computer-option__swatch" style="--option-color: {{ $color }}" aria-hidden="true"></span>
        @endif
        <span>{{ $value }}</span>
        <i class="las la-check computer-option__check" aria-hidden="true"></i>
    </span>
</label>
