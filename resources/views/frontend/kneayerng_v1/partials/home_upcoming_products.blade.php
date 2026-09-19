@php
    $upcomingGroups = [
        'pre_order' => $upcoming_products->where('type', 'pre_order')->values(),
        'coming_soon' => $upcoming_products->where('type', 'coming_soon')->values(),
    ];
    $upcomingGroups = array_filter($upcomingGroups, fn ($group) => $group->isNotEmpty());
    $upcomingActiveTab = array_key_first($upcomingGroups);
    $authUser = auth()->user();
@endphp

@if(file_exists(public_path('assets/css/kneayerng-upcoming.css')))
<link rel="stylesheet" href="{{ static_asset('assets/css/kneayerng-upcoming.css?v=') }}{{ filemtime(public_path('assets/css/kneayerng-upcoming.css')) }}">
@endif

<section class="ky-upcoming" id="ky-upcoming" aria-labelledby="ky-upcoming-title">
    <div class="layout-container mx-auto px-3">
        <div class="ky-upcoming__head">
            <div class="ky-upcoming__heading">
                <span class="ky-upcoming__kicker"><i class="las la-rocket" aria-hidden="true"></i> {{ translate('Launching soon') }}</span>
                <h2 id="ky-upcoming-title" class="ky-upcoming__title">
                    @if (count($upcomingGroups) > 1)
                        {{ translate('Pre-order & Coming Soon') }}
                    @elseif ($upcomingActiveTab === 'pre_order')
                        {{ translate('Pre-order Now') }}
                    @else
                        {{ translate('Coming Soon') }}
                    @endif
                </h2>
            </div>

            <div class="ky-upcoming__controls">
                @if (count($upcomingGroups) > 1)
                    <div class="ky-upcoming__tabs" role="tablist">
                        @foreach ($upcomingGroups as $groupType => $group)
                            <button type="button" role="tab" class="ky-upcoming__tab {{ $groupType === $upcomingActiveTab ? 'is-active' : '' }}"
                                data-upcoming-tab="{{ $groupType }}" aria-selected="{{ $groupType === $upcomingActiveTab ? 'true' : 'false' }}">
                                <i class="las {{ $groupType === 'pre_order' ? 'la-shopping-bag' : 'la-hourglass-half' }}" aria-hidden="true"></i>
                                {{ $groupType === 'pre_order' ? translate('Pre-order') : translate('Coming Soon') }}
                                <span class="ky-upcoming__tab-count">{{ $group->count() }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                <div class="ky-upcoming__arrows d-none d-md-flex">
                    <button type="button" class="ky-upcoming__arrow" data-upcoming-scroll="-1" aria-label="{{ translate('Previous') }}"><i class="las la-angle-left"></i></button>
                    <button type="button" class="ky-upcoming__arrow" data-upcoming-scroll="1" aria-label="{{ translate('Next') }}"><i class="las la-angle-right"></i></button>
                </div>
            </div>
        </div>

        @foreach ($upcomingGroups as $groupType => $group)
            <div class="ky-upcoming__track" role="tabpanel" data-upcoming-panel="{{ $groupType }}" @if ($groupType !== $upcomingActiveTab) hidden @endif>
                @foreach ($group as $item)
                    @php
                        $isOpen = $item->isPreOrderOpen();
                        $countdown = $item->countdownDate();
                        $countdownLabel = $isOpen && $item->preorder_end_date ? translate('Pre-order ends in') : translate('Releases in');
                        $modalData = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'brand' => $item->brand ? $item->brand->getTranslation('name') : null,
                            'image' => uploaded_asset($item->thumbnail_img),
                            'description' => $item->short_description ?: \Illuminate\Support\Str::limit(strip_tags($item->description), 220),
                            'price' => $item->price !== null ? single_price($item->price) : null,
                            'deposit' => $isOpen && $item->deposit_amount ? single_price($item->deposit_amount) : null,
                            'release' => $item->release_date ? $item->release_date->format('d M Y') : null,
                            'mode' => $isOpen ? 'pre_order' : 'notify',
                            'link' => $item->external_link,
                        ];
                    @endphp
                    <article class="ky-upcoming-card ky-upcoming-card--{{ $isOpen ? 'pre-order' : 'coming-soon' }}">
                        <div class="ky-upcoming-card__media">
                            <span class="ky-upcoming-card__badges">
                                <span class="ky-upcoming-card__badge ky-upcoming-card__badge--type">
                                    @if ($isOpen)
                                        {{ translate('Pre-order') }}
                                    @elseif ($item->isPreOrder())
                                        {{ translate('Pre-order closed') }}
                                    @else
                                        {{ translate('Coming Soon') }}
                                    @endif
                                </span>
                                @if ($item->badge_text)
                                    <span class="ky-upcoming-card__badge ky-upcoming-card__badge--custom">{{ $item->badge_text }}</span>
                                @endif
                            </span>
                            <img src="{{ uploaded_asset($item->thumbnail_img) }}" alt="{{ $item->name }}" loading="lazy"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>

                        <div class="ky-upcoming-card__body">
                            @if ($item->brand)
                                <span class="ky-upcoming-card__brand">{{ $item->brand->getTranslation('name') }}</span>
                            @endif
                            <h3 class="ky-upcoming-card__name" title="{{ $item->name }}">{{ $item->name }}</h3>
                            @if ($item->short_description)
                                <p class="ky-upcoming-card__desc">{{ $item->short_description }}</p>
                            @endif

                            <div class="ky-upcoming-card__meta">
                                @if ($countdown)
                                    <div class="ky-upcoming-card__countdown">
                                        <span class="ky-upcoming-card__countdown-label">{{ $countdownLabel }}</span>
                                        <span class="ky-upcoming-countdown" data-countdown="{{ $countdown->getTimestamp() * 1000 }}" data-ended="{{ translate('Available now') }}">--</span>
                                    </div>
                                @elseif ($item->release_date)
                                    <div class="ky-upcoming-card__countdown">
                                        <span class="ky-upcoming-card__countdown-label">{{ translate('Release date') }}</span>
                                        <span class="ky-upcoming-countdown is-static">{{ $item->release_date->format('d M Y') }}</span>
                                    </div>
                                @endif

                                <div class="ky-upcoming-card__price">
                                    @if ($item->price !== null)
                                        <small>{{ translate('Expected price') }}</small>
                                        <strong>{{ single_price($item->price) }}</strong>
                                    @else
                                        <small>{{ translate('Price') }}</small>
                                        <strong>{{ translate('To be announced') }}</strong>
                                    @endif
                                    @if ($isOpen && $item->deposit_amount)
                                        <span class="ky-upcoming-card__deposit">{{ translate('Deposit') }} {{ single_price($item->deposit_amount) }}</span>
                                    @endif
                                </div>
                            </div>

                            <button type="button" class="ky-upcoming-card__cta" data-upcoming-open="{{ json_encode($modalData) }}">
                                @if ($isOpen)
                                    <i class="las la-shopping-bag" aria-hidden="true"></i> {{ translate('Pre-order now') }}
                                @else
                                    <i class="las la-bell" aria-hidden="true"></i> {{ translate('Notify me') }}
                                @endif
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<!-- Pre-order / Notify modal -->
<div class="modal fade ky-upcoming-modal" id="kyUpcomingModal" tabindex="-1" role="dialog" aria-labelledby="kyUpcomingModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="ky-upcoming-modal__close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><i class="las la-times"></i></button>
            <div class="ky-upcoming-modal__grid">
                <div class="ky-upcoming-modal__media">
                    <img src="" alt="" data-field="image">
                </div>
                <div class="ky-upcoming-modal__content">
                    <span class="ky-upcoming-modal__brand" data-field="brand"></span>
                    <h3 id="kyUpcomingModalTitle" class="ky-upcoming-modal__title" data-field="name"></h3>
                    <p class="ky-upcoming-modal__desc" data-field="description"></p>

                    <ul class="ky-upcoming-modal__facts">
                        <li data-fact="price"><span>{{ translate('Expected price') }}</span><strong data-field="price"></strong></li>
                        <li data-fact="deposit"><span>{{ translate('Deposit') }}</span><strong data-field="deposit"></strong></li>
                        <li data-fact="release"><span>{{ translate('Release date') }}</span><strong data-field="release"></strong></li>
                    </ul>
                    <a href="#" target="_blank" rel="noopener" class="ky-upcoming-modal__link" data-field="link">{{ translate('Learn more') }} <i class="las la-external-link-alt"></i></a>

                    <form class="ky-upcoming-modal__form" novalidate>
                        <input type="hidden" name="upcoming_product_id">
                        <div class="ky-upcoming-modal__row">
                            <label>
                                <span>{{ translate('Full name') }} *</span>
                                <input type="text" name="name" required maxlength="255" value="{{ $authUser->name ?? '' }}" autocomplete="name">
                            </label>
                            <label>
                                <span>{{ translate('Phone') }} *</span>
                                <input type="tel" name="phone" required maxlength="50" value="{{ $authUser->phone ?? '' }}" autocomplete="tel">
                            </label>
                        </div>
                        <div class="ky-upcoming-modal__row">
                            <label>
                                <span>{{ translate('Email') }}</span>
                                <input type="email" name="email" maxlength="255" value="{{ $authUser->email ?? '' }}" autocomplete="email">
                            </label>
                            <label data-preorder-only>
                                <span>{{ translate('Quantity') }}</span>
                                <input type="number" name="quantity" min="1" max="100" value="1">
                            </label>
                        </div>
                        <label>
                            <span>{{ translate('Note') }}</span>
                            <textarea name="note" rows="2" maxlength="1000" placeholder="{{ translate('Color, storage, or anything we should know') }}"></textarea>
                        </label>
                        <p class="ky-upcoming-modal__error" hidden></p>
                        <button type="submit" class="ky-upcoming-modal__submit">
                            <span data-label-preorder>{{ translate('Confirm pre-order') }}</span>
                            <span data-label-notify>{{ translate('Notify me when available') }}</span>
                        </button>
                    </form>

                    <div class="ky-upcoming-modal__success" hidden>
                        <i class="las la-check-circle" aria-hidden="true"></i>
                        <p></p>
                        <button type="button" class="ky-upcoming-modal__submit" data-dismiss="modal">{{ translate('Done') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var section = document.getElementById('ky-upcoming');
        if (!section) return;

        // Tabs
        section.querySelectorAll('[data-upcoming-tab]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = tab.getAttribute('data-upcoming-tab');
                section.querySelectorAll('[data-upcoming-tab]').forEach(function (t) {
                    var active = t === tab;
                    t.classList.toggle('is-active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                section.querySelectorAll('[data-upcoming-panel]').forEach(function (panel) {
                    panel.hidden = panel.getAttribute('data-upcoming-panel') !== target;
                });
            });
        });

        // Arrows scroll the visible track by roughly one card
        section.querySelectorAll('[data-upcoming-scroll]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var track = section.querySelector('[data-upcoming-panel]:not([hidden])');
                if (!track) return;
                var card = track.querySelector('.ky-upcoming-card');
                var step = card ? card.getBoundingClientRect().width + 16 : track.clientWidth;
                track.scrollBy({ left: step * parseInt(btn.getAttribute('data-upcoming-scroll'), 10), behavior: 'smooth' });
            });
        });

        // Countdowns
        var timers = section.querySelectorAll('.ky-upcoming-countdown[data-countdown]');
        function pad(n) { return n < 10 ? '0' + n : n; }
        function tick() {
            var now = Date.now();
            timers.forEach(function (el) {
                var diff = parseInt(el.getAttribute('data-countdown'), 10) - now;
                if (diff <= 0) {
                    el.textContent = el.getAttribute('data-ended');
                    return;
                }
                var s = Math.floor(diff / 1000);
                var d = Math.floor(s / 86400);
                el.textContent = (d > 0 ? d + 'd ' : '') + pad(Math.floor(s % 86400 / 3600)) + ':' + pad(Math.floor(s % 3600 / 60)) + ':' + pad(s % 60);
            });
        }
        if (timers.length) {
            tick();
            setInterval(tick, 1000);
        }

        // Modal
        var modalEl = document.getElementById('kyUpcomingModal');
        document.body.appendChild(modalEl); // escape transformed parents so the backdrop stacks correctly
        var form = modalEl.querySelector('form');
        var errorEl = modalEl.querySelector('.ky-upcoming-modal__error');
        var successEl = modalEl.querySelector('.ky-upcoming-modal__success');
        var submitBtn = form.querySelector('button[type="submit"]');

        function setField(name, value) {
            var el = modalEl.querySelector('[data-field="' + name + '"]');
            if (name === 'image') { el.src = value || ''; el.alt = ''; return; }
            if (name === 'link') { el.hidden = !value; el.href = value || '#'; return; }
            el.textContent = value || '';
            el.hidden = !value;
            var fact = modalEl.querySelector('[data-fact="' + name + '"]');
            if (fact) fact.hidden = !value;
        }

        section.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-upcoming-open]');
            if (!btn) return;
            var data = JSON.parse(btn.getAttribute('data-upcoming-open'));
            ['image', 'brand', 'name', 'description', 'price', 'deposit', 'release', 'link'].forEach(function (k) { setField(k, data[k]); });
            modalEl.querySelector('[data-field="image"]').alt = data.name;

            var isPreOrder = data.mode === 'pre_order';
            modalEl.classList.toggle('is-notify', !isPreOrder);
            form.upcoming_product_id.value = data.id;
            form.hidden = false;
            successEl.hidden = true;
            errorEl.hidden = true;
            $(modalEl).modal('show');
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            errorEl.hidden = true;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            submitBtn.disabled = true;

            fetch('{{ route('pre-orders.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
                .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, status: res.status, body: body }; }); })
                .then(function (r) {
                    if (r.ok && r.body.success) {
                        form.hidden = true;
                        successEl.querySelector('p').textContent = r.body.message;
                        successEl.hidden = false;
                        form.note.value = '';
                        form.quantity.value = 1;
                        return;
                    }
                    var msg = r.status === 429
                        ? @json(translate('Too many requests. Please try again in a minute.'))
                        : (r.body.errors ? Object.values(r.body.errors)[0][0] : (r.body.message || @json(translate('Something went wrong'))));
                    errorEl.textContent = msg;
                    errorEl.hidden = false;
                })
                .catch(function () {
                    errorEl.textContent = @json(translate('Something went wrong'));
                    errorEl.hidden = false;
                })
                .finally(function () { submitBtn.disabled = false; });
        });
    })();
</script>
