@php
    $vgEnabled = (int) get_setting('home_video_grid_enabled', 1) === 1;
    $vgVideos = collect(json_decode(get_setting('home_video_grid_videos', '[]'), true) ?: [])
        ->filter(fn ($v) => !empty($v['active']) && \App\Support\PromoVideo::embedUrl($v))
        ->values();
    $vgTitle = get_setting('home_video_grid_title') ?: translate('Watch & Discover');
    $vgSubtitle = get_setting('home_video_grid_subtitle');
    $vgIcons = ['youtube' => 'lab la-youtube', 'facebook' => 'lab la-facebook-f', 'tiktok' => 'lab la-tiktok'];
    $vgLabels = ['youtube' => 'YouTube', 'facebook' => 'Facebook', 'tiktok' => 'TikTok'];
@endphp

@if ($vgEnabled && $vgVideos->isNotEmpty())
    <section class="ky-vgrid" id="ky-vgrid" aria-labelledby="ky-vgrid-title">
        <div class="layout-container mx-auto px-3">
            <div class="ky-vgrid__head">
                <span class="ky-vgrid__kicker"><i class="las la-play-circle" aria-hidden="true"></i> {{ translate('Videos') }}</span>
                <h2 id="ky-vgrid-title" class="ky-vgrid__title">{{ $vgTitle }}</h2>
                @if ($vgSubtitle)
                    <p class="ky-vgrid__subtitle">{{ $vgSubtitle }}</p>
                @endif
            </div>

            <div class="ky-vgrid__grid">
                @foreach ($vgVideos as $video)
                    @php
                        $platform = $video['platform'];
                        $thumb = \App\Support\PromoVideo::thumbnailUrl($video);
                    @endphp
                    <article class="ky-vgrid__item ky-vgrid__item--{{ !empty($video['vertical']) ? 'vertical' : 'wide' }}">
                        <div class="ky-vgrid__frame ky-vgrid__frame--{{ $platform }}"
                            data-embed="{{ \App\Support\PromoVideo::embedUrl($video) }}"
                            data-title="{{ $video['title'] ?: $vgLabels[$platform] }}"
                            @if ($thumb) style="--ky-poster: url('{{ $thumb }}')" @endif>
                            <span class="ky-vgrid__platform"><i class="{{ $vgIcons[$platform] ?? 'las la-video' }}" aria-hidden="true"></i>{{ $vgLabels[$platform] ?? '' }}</span>
                            <button type="button" class="ky-vgrid__play" aria-label="{{ translate('Play video') }}">
                                <i class="las la-play" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        (function () {
            var frames = document.querySelectorAll('#ky-vgrid .ky-vgrid__frame');
            if (!frames.length) return;

            var MAX_PLAYING = 4;
            var playing = [];
            var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var saveData = navigator.connection && navigator.connection.saveData;
            var autoplay = 'IntersectionObserver' in window && !reduced && !saveData;

            function mount(frame) {
                if (frame.querySelector('iframe')) return;
                var iframe = document.createElement('iframe');
                iframe.src = frame.getAttribute('data-embed');
                iframe.title = frame.getAttribute('data-title') || 'Video';
                iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
                iframe.setAttribute('loading', 'lazy');
                iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
                frame.appendChild(iframe);
                frame.classList.add('is-playing');
                playing.push(frame);
                while (playing.length > MAX_PLAYING) unmount(playing[0]);
            }

            function unmount(frame) {
                var iframe = frame.querySelector('iframe');
                if (iframe) iframe.remove();
                frame.classList.remove('is-playing');
                playing = playing.filter(function (f) { return f !== frame; });
            }

            frames.forEach(function (frame) {
                frame.querySelector('.ky-vgrid__play').addEventListener('click', function () { mount(frame); });
            });

            if (!autoplay) return;

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.intersectionRatio >= 0.6) {
                        mount(entry.target);
                    } else if (!entry.isIntersecting || entry.intersectionRatio < 0.15) {
                        unmount(entry.target);
                    }
                });
            }, { threshold: [0, 0.15, 0.6] });

            frames.forEach(function (frame) { observer.observe(frame); });
        })();
    </script>
@endif
