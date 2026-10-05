@extends('backend.layouts.app')

@section('content')
    <style>
        .vg-page .vg-row {
            display: grid;
            grid-template-columns: 34px minmax(0, 2fr) minmax(0, 3fr) auto auto;
            gap: 12px;
            align-items: start;
            padding: 14px;
            border: 1px solid #e5e7ed;
            border-radius: 12px;
            background: #fff;
        }

        .vg-page .vg-row + .vg-row { margin-top: 10px; }

        .vg-page .vg-num {
            display: inline-flex;
            width: 30px;
            height: 30px;
            margin-top: 6px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1edff;
            color: #6d3df1;
            font-size: 12px;
            font-weight: 800;
        }

        .vg-page .vg-platform {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            padding: 2px 9px;
            border-radius: 999px;
            background: #f3f4f8;
            color: #536078;
            font-size: 11px;
            font-weight: 700;
        }

        .vg-page .vg-active { padding-top: 30px; white-space: nowrap; }
        .vg-page .vg-remove { margin-top: 27px; }

        .vg-page .vg-preview { grid-column: 2 / -1; }
        .vg-page .vg-preview[hidden] { display: none; }

        .vg-page .vg-preview__frame {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: #111827;
            box-shadow: 0 6px 18px rgba(25, 28, 40, .14);
        }

        .vg-page .vg-preview__frame.is-wide { width: min(100%, 340px); aspect-ratio: 16 / 9; }
        .vg-page .vg-preview__frame.is-vertical { width: 170px; aspect-ratio: 9 / 16; }
        .vg-page .vg-preview__frame:empty { display: none; }

        .vg-page .vg-preview__frame iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .vg-page .vg-preview__msg { margin: 6px 0 0; font-size: 12px; color: #77809a; }
        .vg-page .vg-preview__msg.is-error { color: #d9314b; }
        .vg-page .vg-preview__msg:empty { display: none; }

        @media (max-width: 991px) {
            .vg-page .vg-row { grid-template-columns: 30px 1fr; }
            .vg-page .vg-row > .vg-num { grid-row: span 4; }
            .vg-page .vg-active, .vg-page .vg-remove { padding-top: 0; margin-top: 0; }
        }
    </style>

    <div class="page-content vg-page">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
            <h1 class="h3 fw-700">{{ translate('Video Grid') }}</h1>
            <p class="text-muted mb-0">{{ translate('Shown on the home page right below the main home slider. Videos play automatically (muted) when a visitor scrolls to them.') }}</p>
        </div>

        <div class="px-3 px-md-2rem mb-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('website.video_grid.update') }}" method="POST">
                @csrf

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group d-flex align-items-center">
                            <input type="hidden" name="enabled" value="0">
                            <label class="aiz-switch aiz-switch-success mb-0 mr-3">
                                <input type="checkbox" name="enabled" value="1" @checked(old('enabled', $enabled ? 1 : 0))>
                                <span></span>
                            </label>
                            <span class="fw-600">{{ translate('Show the video grid on the home page') }}</span>
                        </div>
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <label>{{ translate('Section Title') }}</label>
                                <input type="text" class="form-control" name="title" maxlength="120"
                                    value="{{ old('title', $title) }}" placeholder="{{ translate('Watch & Discover') }}">
                            </div>
                            <div class="col-md-7 form-group">
                                <label>{{ translate('Subtitle (optional)') }}</label>
                                <input type="text" class="form-control" name="subtitle" maxlength="200"
                                    value="{{ old('subtitle', $subtitle) }}" placeholder="{{ translate('Short videos from our shop') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Videos') }} <span class="text-muted fs-12">({{ translate('max 12') }})</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted fs-13">{{ translate('Copy the share link of a video from Facebook, YouTube or TikTok and paste it below.') }}</p>

                        <div id="vg-rows">
                            @php $rows = old('videos', $videos); @endphp
                            @foreach ($rows as $i => $video)
                                @include('backend.website_settings.partials.video_grid_row', ['i' => $i, 'video' => $video])
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-soft-primary mt-3" id="vg-add">
                            <i class="las la-plus mr-1"></i>{{ translate('Add Video') }}
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="las la-save mr-1"></i>{{ translate('Save Changes') }}
                </button>
            </form>
        </div>
    </div>

    <template id="vg-row-template">
        @include('backend.website_settings.partials.video_grid_row', ['i' => '__INDEX__', 'video' => ['active' => true]])
    </template>
@endsection

@section('script')
    <script>
        (function () {
            var rows = document.getElementById('vg-rows');
            var tpl = document.getElementById('vg-row-template');
            var addBtn = document.getElementById('vg-add');
            var next = rows.children.length + 100;

            function renumber() {
                Array.prototype.forEach.call(rows.children, function (row, idx) {
                    row.querySelector('.vg-num').textContent = idx + 1;
                });
                addBtn.disabled = rows.children.length >= 12;
            }

            addBtn.addEventListener('click', function () {
                if (rows.children.length >= 12) return;
                var html = tpl.innerHTML.replace(/__INDEX__/g, next++);
                rows.insertAdjacentHTML('beforeend', html);
                renumber();
            });

            rows.addEventListener('click', function (e) {
                var btn = e.target.closest('.vg-remove');
                if (!btn) return;
                btn.closest('.vg-row').remove();
                renumber();
            });

            var previewUrl = @json(route('website.video_grid.preview'));
            var csrf = @json(csrf_token());
            var timers = new WeakMap();

            function showPreview(row, src, vertical, message, isError) {
                var box = row.querySelector('.vg-preview');
                var frame = box.querySelector('.vg-preview__frame');
                var msg = box.querySelector('.vg-preview__msg');
                frame.innerHTML = '';
                msg.textContent = message || '';
                msg.classList.toggle('is-error', !!isError);
                if (src) {
                    frame.className = 'vg-preview__frame ' + (vertical ? 'is-vertical' : 'is-wide');
                    var iframe = document.createElement('iframe');
                    iframe.src = src;
                    iframe.allow = 'encrypted-media; picture-in-picture; fullscreen';
                    iframe.setAttribute('loading', 'lazy');
                    frame.appendChild(iframe);
                }
                box.hidden = !(src || message);
            }

            var platformNames = { youtube: 'YouTube', facebook: 'Facebook', tiktok: 'TikTok' };
            var platformIcons = { youtube: 'lab la-youtube', facebook: 'lab la-facebook', tiktok: 'lab la-tiktok' };

            function setBadge(row, platform) {
                var old = row.querySelector('.vg-platform');
                if (old) old.remove();
                if (!platform) return;
                var badge = document.createElement('span');
                badge.className = 'vg-platform';
                badge.innerHTML = '<i class="' + platformIcons[platform] + '"></i>' + platformNames[platform];
                row.querySelector('input[type="url"]').parentNode.appendChild(badge);
            }

            function requestPreview(row) {
                var url = row.querySelector('input[type="url"]').value.trim();
                if (!url) { setBadge(row, null); showPreview(row, null, false, ''); return; }
                showPreview(row, null, false, @json(translate('Loading preview…')), false);

                var body = new FormData();
                body.append('url', url);
                body.append('_token', csrf);
                fetch(previewUrl, { method: 'POST', body: body, headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
                    .then(function (r) {
                        if (r.ok && r.body.ok) {
                            setBadge(row, r.body.platform);
                            showPreview(row, r.body.embed_url, r.body.vertical, '', false);
                        } else {
                            setBadge(row, null);
                            var m = (r.body && (r.body.message || (r.body.errors && Object.values(r.body.errors)[0][0]))) || @json(translate('Could not preview this link.'));
                            showPreview(row, null, false, m, true);
                        }
                    })
                    .catch(function () { showPreview(row, null, false, @json(translate('Could not preview this link.')), true); });
            }

            function schedule(row) {
                clearTimeout(timers.get(row));
                timers.set(row, setTimeout(function () { requestPreview(row); }, 450));
            }

            rows.addEventListener('input', function (e) {
                if (e.target.matches('input[type="url"]')) schedule(e.target.closest('.vg-row'));
            });
            rows.addEventListener('paste', function (e) {
                if (e.target.matches('input[type="url"]')) schedule(e.target.closest('.vg-row'));
            });

            Array.prototype.forEach.call(rows.querySelectorAll('.vg-preview[data-stored-src]'), function (box) {
                showPreview(box.closest('.vg-row'), box.getAttribute('data-stored-src'), box.getAttribute('data-stored-vertical') === '1', '', false);
            });

            renumber();
        })();
    </script>
@endsection
