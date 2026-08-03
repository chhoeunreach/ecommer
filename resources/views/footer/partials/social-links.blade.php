@foreach (footer_social_links() as $socialLink)
    @php
        $platform = $socialLink['platform'];
        $socialClass = $platform === 'twitter' ? 'x-twitter' : $platform;
        $iconClasses = [
            'facebook' => 'lab la-facebook-f',
            'instagram' => 'lab la-instagram',
            'youtube' => 'lab la-youtube',
            'linkedin' => 'lab la-linkedin-in',
            'telegram' => 'lab la-telegram-plane',
            'whatsapp' => 'lab la-whatsapp',
            'pinterest' => 'lab la-pinterest-p',
            'website' => 'las la-link',
        ];
        $iconClass = $iconClasses[$platform] ?? $iconClasses['website'];
        $backgroundColors = [
            'tiktok' => '#000000',
            'telegram' => '#229ed9',
            'whatsapp' => '#25d366',
            'pinterest' => '#e60023',
            'website' => '#5d6161',
        ];
    @endphp
    <li class="list-inline-item {{ $itemClass ?? 'mr-2' }}">
        <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer"
            class="{{ $socialClass }}" aria-label="{{ ucfirst($platform) }}"
            style="overflow: hidden; @if (isset($backgroundColors[$platform])) background-color: {{ $backgroundColors[$platform] }}; @endif">
            @if (!empty($socialLink['icon']))
                <img src="{{ uploaded_asset($socialLink['icon']) }}" alt="" width="36" height="36"
                    style="display: block; width: 36px; height: 36px; object-fit: cover;">
            @elseif ($platform === 'twitter')
                <svg class="aiz-custom-x-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                </svg>
            @elseif ($platform === 'tiktok')
                <img src="{{ static_asset('assets/img/social/tiktok.png') }}" alt="" width="44" height="44"
                    style="display: block; width: 44px; height: 44px; max-width: none; margin: -4px;">
            @else
                <i class="{{ $iconClass }}"></i>
            @endif
        </a>
    </li>
@endforeach
