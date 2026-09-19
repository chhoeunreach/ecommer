@extends('frontend.layouts.app')

@section('meta_title', 'Our Store Locations & Service Centers | Kneayerng Phone Shop')
@section('meta_description', 'Find your nearest Kneayerng Phone Shop in Phnom Penh. Explore store locations, opening hours, contact details, device repair services and directions.')

@section('meta')
    <meta property="og:title" content="Our Store Locations & Service Centers | Kneayerng Phone Shop">
    <meta property="og:description" content="Visit Kneayerng Phone Shop for genuine smartphones, laptops, MacBooks, tech accessories and expert repair services.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('branches') }}">
    <meta property="og:image" content="{{ get_setting('anlyn_branches_hero_image') ? uploaded_asset(get_setting('anlyn_branches_hero_image')) : static_asset('assets/img/branches/anlyn-branches-hero.jpg') }}">
@endsection

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <link rel="stylesheet" href="{{ static_asset('assets/css/branch-locations.css?v=') }}{{ filemtime(public_path('assets/css/branch-locations.css')) }}">
@endsection

@section('content')
    @php
        $contactPhone = get_setting('contact_phone') ?: '+855 (0) 12 345 678';
        $globalSocialLinks = collect(footer_social_links())->take(3)->values();
        $socialIcons = [
            'facebook' => 'lab la-facebook-f',
            'instagram' => 'lab la-instagram',
            'youtube' => 'lab la-youtube',
            'twitter' => 'lab la-twitter',
            'linkedin' => 'lab la-linkedin-in',
            'tiktok' => 'lab la-tiktok',
        ];

        $defaultBranches = [
            [
                'brand' => 'KNEAYERNG STORE',
                'name' => 'Sen Sok Flagship & Service Center',
                'image' => 'assets/img/branches/anlyn-pop-branch.jpg',
                'address' => 'AEON Mall Sen Sok City (2nd Floor), St. 1003, Phnom Penh',
                'hours' => 'Monday – Sunday · 9:00 AM – 9:00 PM',
                'phone' => $contactPhone,
                'map' => 'https://www.google.com/maps/search/?api=1&query=AEON+Mall+Sen+Sok+City+Phnom+Penh',
                'city' => 'Phnom Penh',
                'services' => ['Smartphones', 'Laptops & Mac', 'Repair Center', 'Click & Collect'],
                'lat' => 11.5744,
                'lng' => 104.8722,
                'active' => true,
            ],
            [
                'brand' => 'KNEAYERNG STORE',
                'name' => 'BKK1 Apple & Tech Boutique',
                'image' => 'assets/img/branches/anlyn-bloom-branch.jpg',
                'address' => 'St. 51 Corner St. 306, BKK1, Chamkarmon, Phnom Penh',
                'hours' => 'Monday – Sunday · 8:30 AM – 8:30 PM',
                'phone' => $contactPhone,
                'map' => 'https://www.google.com/maps/search/?api=1&query=BKK1+Phnom+Penh',
                'city' => 'Phnom Penh',
                'services' => ['Apple Products', 'Accessories', 'Fast Trade-in', 'Consultation'],
                'lat' => 11.5474,
                'lng' => 104.9160,
                'active' => true,
            ],
            [
                'brand' => 'KNEAYERNG STORE',
                'name' => 'Chip Mong 271 Concept Store',
                'image' => 'assets/img/branches/anlyn-concept-branch.jpg',
                'address' => 'Chip Mong 271 Mega Mall (1st Floor), Yothapol Khemarak Phoumin Blvd, Phnom Penh',
                'hours' => 'Monday – Sunday · 9:00 AM – 9:00 PM',
                'phone' => $contactPhone,
                'map' => 'https://www.google.com/maps/search/?api=1&query=Chip+Mong+271+Mega+Mall+Phnom+Penh',
                'city' => 'Phnom Penh',
                'services' => ['Phones & Tablets', 'Gaming Laptops', 'Audio & Gadgets', 'Express Service'],
                'lat' => 11.5588,
                'lng' => 104.9337,
                'active' => true,
            ],
        ];

        $fallbackImages = [
            'assets/img/branches/anlyn-pop-branch.jpg',
            'assets/img/branches/anlyn-bloom-branch.jpg',
            'assets/img/branches/anlyn-concept-branch.jpg',
        ];

        $storedBranches = json_decode(get_setting('anlyn_branches', '[]'), true);
        $branches = is_array($storedBranches) && count($storedBranches) > 0 ? $storedBranches : $defaultBranches;
        $branches = collect($branches)
            ->filter(fn ($branch) => (bool) ($branch['active'] ?? true))
            ->values()
            ->map(function ($branch, $index) use ($fallbackImages, $contactPhone) {
                $branch['city'] = !empty($branch['city']) ? $branch['city'] : 'Phnom Penh';
                $branch['map'] = !empty($branch['map']) ? $branch['map'] : ('https://www.google.com/maps/search/?api=1&query=' . urlencode($branch['address'] ?? 'Phnom Penh'));
                $branch['phone'] = !empty($branch['phone']) ? $branch['phone'] : $contactPhone;
                $branch['image_url'] = !empty($branch['image'])
                    ? uploaded_asset($branch['image'])
                    : static_asset($fallbackImages[$index % count($fallbackImages)]);
                
                if (empty($branch['services'])) {
                    $servicesPool = [
                        ['Smartphones', 'Laptops & Mac', 'Repair Center', 'Click & Collect'],
                        ['Apple Products', 'Accessories', 'Fast Trade-in', 'Consultation'],
                        ['Phones & Tablets', 'Gaming Laptops', 'Audio & Gadgets', 'Express Service'],
                    ];
                    $branch['services'] = $servicesPool[$index % count($servicesPool)];
                }
                return $branch;
            });

        $heroImage = get_setting('anlyn_branches_hero_image')
            ? uploaded_asset(get_setting('anlyn_branches_hero_image'))
            : static_asset('assets/img/branches/anlyn-branches-hero.jpg');

        $heroEyebrow = get_setting('anlyn_branches_hero_eyebrow');
        if (empty($heroEyebrow) || str_contains(strtolower($heroEyebrow), 'little world')) {
            $heroEyebrow = 'Official Retail & Service Locations';
        }

        $heroTitle = get_setting('anlyn_branches_hero_title');
        if (empty($heroTitle) || str_contains(strtolower($heroTitle), 'happy place')) {
            $heroTitle = 'Find a Kneayerng Store Near You';
        }

        $heroDescription = get_setting('anlyn_branches_hero_description');
        if (empty($heroDescription) || str_contains(strtolower($heroDescription), 'collectibles') || str_contains(strtolower($heroDescription), 'flowers')) {
            $heroDescription = 'Visit our retail showrooms to experience the latest smartphones, laptops, MacBooks and genuine accessories with certified warranty and repair service.';
        }

        $sectionDescription = get_setting('anlyn_branches_section_description');
        if (empty($sectionDescription) || str_contains(strtolower($sectionDescription), 'anlyn')) {
            $sectionDescription = 'Locate our stores across Phnom Penh with direct maps, business hours, and expert in-store customer support.';
        }

        // Get unique cities for filter buttons
        $cities = $branches->pluck('city')->unique()->values();

        // Lightweight branch data for the store-locator map / geolocation script
        $branchesGeoData = $branches->values()->map(function ($branch, $i) {
            return [
                'index' => $i,
                'name' => $branch['name'],
                'address' => $branch['address'],
                'hours' => $branch['hours'],
                'phone' => $branch['phone'],
                'image' => $branch['image_url'],
                'lat' => (isset($branch['lat']) && is_numeric($branch['lat'])) ? (float) $branch['lat'] : null,
                'lng' => (isset($branch['lng']) && is_numeric($branch['lng'])) ? (float) $branch['lng'] : null,
            ];
        })->values();
    @endphp

    <main class="ky-branches-page">
        <!-- Hero Section -->
        <section class="ky-branches-hero">
            <img src="{{ $heroImage }}" alt="Kneayerng Phone Shop Retail Store" class="ky-branches-hero__bg-media">
            <div class="ky-branches-hero__glow"></div>
            
            <div class="ky-branches-hero__container">
                <div class="ky-branches-badge">
                    <span class="ky-pulse-dot"></span>
                    <span>{{ $heroEyebrow }}</span>
                </div>

                <h1 class="ky-branches-title">
                    @if(str_contains(strtolower($heroTitle), 'kneayerng'))
                        Find a <span class="ky-gradient-text">Kneayerng</span> Store Near You
                    @else
                        {{ $heroTitle }}
                    @endif
                </h1>

                <p class="ky-branches-desc">{{ $heroDescription }}</p>

                <!-- Search & Filters Container -->
                <div class="ky-search-filter-box">
                    <div class="ky-search-input-wrap">
                        <i class="las la-search" aria-hidden="true"></i>
                        <input type="text" id="kyBranchSearch" class="ky-search-input"
                            placeholder="{{ translate('Search by store name, mall, or street...') }}"
                            autocomplete="off" aria-label="{{ translate('Search stores') }}">
                        <button type="button" id="kySearchClear" class="ky-search-clear" aria-label="{{ translate('Clear search') }}">
                            <i class="las la-times"></i>
                        </button>
                    </div>

                    <div class="ky-filter-pills" role="group" aria-label="Filter stores by city">
                        <button type="button" class="ky-filter-btn active" data-filter="all">
                            <i class="las la-store"></i> {{ translate('All Stores') }} ({{ $branches->count() }})
                        </button>
                        @foreach ($cities as $city)
                            <button type="button" class="ky-filter-btn" data-filter="{{ strtolower($city) }}">
                                <i class="las la-map-marker-alt"></i> {{ $city }}
                            </button>
                        @endforeach
                        <button type="button" class="ky-filter-btn ky-find-my-store-btn" id="kyFindMyStoreBtn">
                            <i class="las la-location-arrow"></i> <span id="kyFindMyStoreLabel">{{ translate('Find My Store') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Store Locator Map -->
        <section class="ky-store-map-section">
            <div class="ky-store-map-container">
                <div id="kyStoreMap" class="ky-store-map"></div>

                <button type="button" class="ky-map-locate-btn" id="kyMapLocateBtn" aria-label="{{ translate('Locate me') }}">
                    <i class="las la-location-arrow"></i>
                </button>

                <div class="ky-map-nearest-card d-none" id="kyNearestCard">
                    <button type="button" class="ky-map-nearest-close" id="kyNearestCardClose" aria-label="{{ translate('Close') }}">
                        <i class="las la-times"></i>
                    </button>
                    <img src="" alt="" id="kyNearestCardImg" class="ky-map-nearest-img">
                    <div class="ky-map-nearest-info">
                        <div class="ky-map-nearest-top">
                            <h4 id="kyNearestCardName"></h4>
                            <span class="ky-map-nearest-distance" id="kyNearestCardDistance"></span>
                        </div>
                        <p class="ky-map-nearest-address" id="kyNearestCardAddress"></p>
                        <div class="ky-map-nearest-meta">
                            <span id="kyNearestCardHours"><i class="las la-clock"></i> <span></span></span>
                            <a href="tel:" id="kyNearestCardPhone"><i class="las la-phone"></i> <span></span></a>
                        </div>
                    </div>
                    <a href="#" class="ky-map-nearest-view" id="kyNearestCardView">{{ translate('View') }} <i class="las la-angle-right"></i></a>
                </div>
            </div>
        </section>

        <!-- Highlights Strip -->
        <div class="ky-highlights-strip">
            <div class="ky-highlight-item">
                <div class="ky-highlight-icon">
                    <i class="las la-tools"></i>
                </div>
                <div class="ky-highlight-text">
                    <h4>{{ translate('Certified Tech Repairs') }}</h4>
                    <p>{{ translate('Fast screen, battery & logic board service') }}</p>
                </div>
            </div>
            <div class="ky-highlight-item">
                <div class="ky-highlight-icon">
                    <i class="las la-shield-alt"></i>
                </div>
                <div class="ky-highlight-text">
                    <h4>{{ translate('100% Genuine Devices') }}</h4>
                    <p>{{ translate('Official brand warranty on all products') }}</p>
                </div>
            </div>
            <div class="ky-highlight-item">
                <div class="ky-highlight-icon">
                    <i class="las la-truck-loading"></i>
                </div>
                <div class="ky-highlight-text">
                    <h4>{{ translate('In-Store Click & Collect') }}</h4>
                    <p>{{ translate('Order online, pick up in 1 hour') }}</p>
                </div>
            </div>
            <div class="ky-highlight-item">
                <div class="ky-highlight-icon">
                    <i class="las la-sync-alt"></i>
                </div>
                <div class="ky-highlight-text">
                    <h4>{{ translate('Instant Trade-In') }}</h4>
                    <p>{{ translate('Upgrade your old phone or laptop easily') }}</p>
                </div>
            </div>
        </div>

        <!-- Branch List Section -->
        <section class="ky-branches-section" id="stores-list">
            <div class="ky-section-header">
                <div>
                    <h2>{{ translate('Store Locations') }}</h2>
                    <p>{{ $sectionDescription }}</p>
                </div>
                <div class="ky-branches-count">
                    <i class="las la-map-pin text-primary"></i>
                    <span id="kyStoreCountText">{{ $branches->count() }} {{ translate('Locations Available') }}</span>
                </div>
            </div>

            <!-- Stores Grid -->
            <div class="ky-branches-grid" id="kyBranchesGrid">
                @foreach ($branches as $index => $branch)
                    <article class="ky-branch-card" id="branch-card-{{ $index }}"
                        data-city="{{ strtolower($branch['city']) }}"
                        data-search="{{ strtolower($branch['name'] . ' ' . $branch['address'] . ' ' . $branch['city']) }}"
                        data-lat="{{ $branch['lat'] ?? '' }}"
                        data-lng="{{ $branch['lng'] ?? '' }}"
                        data-map="{{ $branch['map'] }}">
                        
                        <!-- Media Header -->
                        <div class="ky-branch-media">
                            <img src="{{ $branch['image_url'] }}" alt="{{ $branch['name'] }}" loading="lazy">
                            <span class="ky-branch-overlay-badge">
                                <i class="las la-hashtag"></i> Store 0{{ $index + 1 }}
                            </span>
                            <span class="ky-branch-status-badge">
                                <span class="ky-status-dot"></span> {{ translate('Open Daily') }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="ky-branch-body">
                            <div class="ky-branch-city-tag">
                                <i class="las la-map-marker-alt"></i> {{ $branch['city'] }}
                            </div>
                            <h3 class="ky-branch-name">{{ $branch['name'] }}</h3>

                            <!-- Services / Features tags -->
                            @if (!empty($branch['services']))
                                <div class="ky-branch-services">
                                    @foreach ($branch['services'] as $service)
                                        <span class="ky-service-chip">
                                            <i class="las la-check-circle"></i> {{ $service }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Details List -->
                            <div class="ky-branch-details">
                                <div class="ky-detail-row">
                                    <div class="ky-detail-icon">
                                        <i class="las la-map-marker"></i>
                                    </div>
                                    <div class="ky-detail-content">
                                        <strong>{{ translate('Address') }}</strong>
                                        <span>{{ $branch['address'] }}</span>
                                    </div>
                                </div>

                                <div class="ky-detail-row">
                                    <div class="ky-detail-icon">
                                        <i class="las la-clock"></i>
                                    </div>
                                    <div class="ky-detail-content">
                                        <strong>{{ translate('Hours') }}</strong>
                                        <span>{{ $branch['hours'] }}</span>
                                    </div>
                                </div>

                                <div class="ky-detail-row">
                                    <div class="ky-detail-icon">
                                        <i class="las la-phone"></i>
                                    </div>
                                    <div class="ky-detail-content">
                                        <strong>{{ translate('Phone / Support') }}</strong>
                                        <a href="tel:{{ preg_replace('/[^+0-9]/', '', $branch['phone']) }}">
                                            {{ $branch['phone'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="ky-branch-actions">
                                <a href="{{ $branch['map'] }}" target="_blank" rel="noopener" class="ky-btn-directions">
                                    <i class="las la-directions"></i> {{ translate('Directions') }}
                                </a>
                                <a href="tel:{{ preg_replace('/[^+0-9]/', '', $branch['phone']) }}" class="ky-btn-call" aria-label="Call {{ $branch['name'] }}">
                                    <i class="las la-phone-volume"></i> {{ translate('Call') }}
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach

                <!-- No Results State -->
                <div class="ky-no-results d-none" id="kyNoResults">
                    <i class="las la-search-location"></i>
                    <h3>{{ translate('No stores found') }}</h3>
                    <p>{{ translate('Try adjusting your search query or select another city.') }}</p>
                    <button type="button" class="ky-btn-directions" id="kyResetFilters">
                        {{ translate('View All Stores') }}
                    </button>
                </div>
            </div>
        </section>

        <!-- Customer Tech Support & Promise Banner -->
        <section class="ky-visit-promise-section">
            <div class="ky-visit-promise-card">
                <div class="ky-visit-promise-card__glow"></div>
                <div class="ky-promise-header">
                    <h3>{{ translate('Why Visit Kneayerng Stores?') }}</h3>
                    <p>{{ translate('Enjoy a world-class retail experience tailored for tech enthusiasts, professionals, and students.') }}</p>
                </div>

                <div class="ky-promise-grid">
                    <div class="ky-promise-item">
                        <div class="ky-promise-icon">
                            <i class="las la-mobile"></i>
                        </div>
                        <h4>{{ translate('Live Hands-on Demos') }}</h4>
                        <p>{{ translate('Test latest smartphones, iPads, MacBooks & audio before buying.') }}</p>
                    </div>

                    <div class="ky-promise-item">
                        <div class="ky-promise-icon">
                            <i class="las la-user-cog"></i>
                        </div>
                        <h4>{{ translate('Expert Technicians') }}</h4>
                        <p>{{ translate('Same-day screen, battery, and diagnostic repair support.') }}</p>
                    </div>

                    <div class="ky-promise-item">
                        <div class="ky-promise-icon">
                            <i class="las la-exchange-alt"></i>
                        </div>
                        <h4>{{ translate('Instant Trade-In') }}</h4>
                        <p>{{ translate('Get instant cash or store credit for your older devices.') }}</p>
                    </div>

                    <div class="ky-promise-item">
                        <div class="ky-promise-icon">
                            <i class="las la-database"></i>
                        </div>
                        <h4>{{ translate('Free Data Migration') }}</h4>
                        <p>{{ translate('We safely transfer your photos, chats & apps to your new phone.') }}</p>
                    </div>
                </div>

                <div class="ky-promise-cta">
                    <a href="{{ url('/contact-us') }}" class="ky-btn-contact-support">
                        <i class="las la-headset"></i> {{ translate('Contact Support & Inquiries') }}
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Copy Toast -->
    <div class="ky-copy-toast" id="kyCopyToast">
        <i class="las la-check-circle text-success"></i>
        <span>{{ translate('Address copied to clipboard') }}</span>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('kyBranchSearch');
            var searchClear = document.getElementById('kySearchClear');
            var filterBtns = document.querySelectorAll('.ky-filter-btn');
            var cards = document.querySelectorAll('.ky-branch-card');
            var noResults = document.getElementById('kyNoResults');
            var countText = document.getElementById('kyStoreCountText');
            var resetBtn = document.getElementById('kyResetFilters');
            var currentFilter = 'all';

            function filterStores() {
                var query = searchInput ? searchInput.value.trim().toLowerCase() : '';
                var visibleCount = 0;

                if (searchClear) {
                    searchClear.classList.toggle('active', query.length > 0);
                }

                cards.forEach(function (card) {
                    var cardCity = card.getAttribute('data-city') || '';
                    var searchData = card.getAttribute('data-search') || '';

                    var matchesFilter = (currentFilter === 'all') || (cardCity === currentFilter);
                    var matchesQuery = (query === '') || (searchData.indexOf(query) !== -1);

                    if (matchesFilter && matchesQuery) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (noResults) {
                    noResults.classList.toggle('d-none', visibleCount > 0);
                }

                if (countText) {
                    countText.textContent = visibleCount + ' {{ translate("Locations Available") }}';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterStores);
            }

            if (searchClear) {
                searchClear.addEventListener('click', function () {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                    }
                    filterStores();
                });
            }

            filterBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    currentFilter = btn.getAttribute('data-filter');
                    filterStores();
                });
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    if (searchInput) searchInput.value = '';
                    currentFilter = 'all';
                    filterBtns.forEach(function (b) {
                        b.classList.toggle('active', b.getAttribute('data-filter') === 'all');
                    });
                    filterStores();
                });
            }

            // ---------------------------------------------------------------
            // Store locator map + "Find My Store" (nearest branch via geolocation)
            // ---------------------------------------------------------------
            var branchesGeo = @json($branchesGeoData);

            function toRad(value) {
                return (value * Math.PI) / 180;
            }

            function distanceKm(lat1, lng1, lat2, lng2) {
                var R = 6371;
                var dLat = toRad(lat2 - lat1);
                var dLng = toRad(lng2 - lng1);
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // ---- Leaflet map ----
            var kyMap = null;
            var kyUserMarker = null;
            var mapEl = document.getElementById('kyStoreMap');

            var userIcon = window.L ? L.divIcon({
                className: 'ky-map-user-wrap',
                html: '<span class="ky-map-user-dot"></span>',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            }) : null;

            if (mapEl && window.L) {
                kyMap = L.map(mapEl, { scrollWheelZoom: false }).setView([11.5564, 104.9282], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19,
                }).addTo(kyMap);

                var storeIcon = L.divIcon({
                    className: 'ky-map-pin-wrap',
                    html: '<span class="ky-map-pin"><i class="las la-store"></i></span>',
                    iconSize: [34, 34],
                    iconAnchor: [17, 34],
                    popupAnchor: [0, -30],
                });

                var boundsPoints = [];
                branchesGeo.forEach(function (b) {
                    if (b.lat === null || b.lng === null) return;
                    var marker = L.marker([b.lat, b.lng], { icon: storeIcon }).addTo(kyMap);
                    marker.bindPopup('<strong>' + b.name + '</strong><br>' + b.address);
                    marker.on('click', function () {
                        showNearestCard(b, null);
                    });
                    b.marker = marker;
                    boundsPoints.push([b.lat, b.lng]);
                });

                if (boundsPoints.length > 1) {
                    kyMap.fitBounds(boundsPoints, { padding: [40, 40] });
                } else if (boundsPoints.length === 1) {
                    kyMap.setView(boundsPoints[0], 15);
                }
            }

            // ---- Nearest-store bottom card ----
            var nearestCardEl = document.getElementById('kyNearestCard');
            var nearestCardClose = document.getElementById('kyNearestCardClose');
            var nearestCardView = document.getElementById('kyNearestCardView');

            function showNearestCard(branch, distance) {
                if (!nearestCardEl) return;

                document.getElementById('kyNearestCardImg').src = branch.image || '';
                document.getElementById('kyNearestCardName').textContent = branch.name || '';
                document.getElementById('kyNearestCardDistance').textContent =
                    (typeof distance === 'number') ? distance.toFixed(1) + ' km' : '';
                document.getElementById('kyNearestCardAddress').textContent = branch.address || '';

                var hoursSpan = document.querySelector('#kyNearestCardHours span');
                if (hoursSpan) hoursSpan.textContent = branch.hours || '';

                var phoneLink = document.getElementById('kyNearestCardPhone');
                var phoneSpan = phoneLink ? phoneLink.querySelector('span') : null;
                if (phoneSpan) phoneSpan.textContent = branch.phone || '';
                if (phoneLink) phoneLink.setAttribute('href', 'tel:' + String(branch.phone || '').replace(/[^+0-9]/g, ''));

                if (nearestCardView) {
                    if (typeof branch.index === 'number') {
                        nearestCardView.classList.remove('d-none');
                        nearestCardView.setAttribute('href', '#branch-card-' + branch.index);
                    } else {
                        nearestCardView.classList.add('d-none');
                    }
                }

                nearestCardEl.classList.remove('d-none');
            }

            if (nearestCardClose) {
                nearestCardClose.addEventListener('click', function () {
                    nearestCardEl.classList.add('d-none');
                });
            }

            if (nearestCardView) {
                nearestCardView.addEventListener('click', function (e) {
                    var targetId = this.getAttribute('href');
                    var targetEl = targetId ? document.querySelector(targetId) : null;
                    if (targetEl) {
                        e.preventDefault();
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetEl.classList.add('ky-branch-nearest');
                        setTimeout(function () {
                            targetEl.classList.remove('ky-branch-nearest');
                        }, 4000);
                    }
                });
            }

            // ---- Locate the visitor & find their nearest store ----
            function locateNearestStore(onDone) {
                if (!navigator.geolocation) {
                    alert('{{ translate('Geolocation is not supported by your browser.') }}');
                    if (onDone) onDone();
                    return;
                }

                navigator.geolocation.getCurrentPosition(function (position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;

                    if (kyMap && userIcon) {
                        if (kyUserMarker) kyMap.removeLayer(kyUserMarker);
                        kyUserMarker = L.marker([userLat, userLng], { icon: userIcon }).addTo(kyMap);
                    }

                    // Always clear any leftover search/filter so a match is guaranteed to be visible
                    currentFilter = 'all';
                    if (searchInput) searchInput.value = '';
                    filterBtns.forEach(function (b) {
                        b.classList.toggle('active', b.getAttribute('data-filter') === 'all');
                    });
                    filterStores();

                    var nearestBranch = null;
                    var nearestDistance = Infinity;

                    branchesGeo.forEach(function (b) {
                        if (b.lat === null || b.lng === null) return;
                        var d = distanceKm(userLat, userLng, b.lat, b.lng);
                        if (d < nearestDistance) {
                            nearestDistance = d;
                            nearestBranch = b;
                        }
                    });

                    if (!nearestBranch) {
                        showNearestCard({
                            name: '{{ translate('Store locations coming soon') }}',
                            address: '{{ translate('We could not match a mapped store to your location yet. Please browse the list below.') }}',
                            hours: '',
                            phone: '',
                            image: '',
                        }, null);
                        if (onDone) onDone();
                        return;
                    }

                    if (kyMap) {
                        kyMap.fitBounds([[userLat, userLng], [nearestBranch.lat, nearestBranch.lng]], { padding: [60, 60] });
                        if (nearestBranch.marker) nearestBranch.marker.openPopup();
                    }

                    showNearestCard(nearestBranch, nearestDistance);

                    var listCard = document.getElementById('branch-card-' + nearestBranch.index);
                    if (listCard) {
                        cards.forEach(function (c) { c.classList.remove('ky-branch-nearest'); });
                        listCard.classList.add('ky-branch-nearest');
                        setTimeout(function () {
                            listCard.classList.remove('ky-branch-nearest');
                        }, 4000);
                    }

                    if (onDone) onDone();
                }, function () {
                    alert('{{ translate('Location access was denied. Please enable location permissions and try again.') }}');
                    if (onDone) onDone();
                });
            }

            var findMyStoreBtn = document.getElementById('kyFindMyStoreBtn');
            var findMyStoreLabel = document.getElementById('kyFindMyStoreLabel');
            var mapLocateBtn = document.getElementById('kyMapLocateBtn');
            var mapSection = mapEl ? mapEl.closest('.ky-store-map-section') : null;

            function setFindMyStoreState(text, disabled) {
                if (findMyStoreLabel) findMyStoreLabel.textContent = text;
                if (findMyStoreBtn) findMyStoreBtn.disabled = !!disabled;
            }

            if (findMyStoreBtn) {
                findMyStoreBtn.addEventListener('click', function () {
                    if (mapSection) mapSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    setFindMyStoreState('{{ translate('Locating…') }}', true);
                    locateNearestStore(function () {
                        setFindMyStoreState('{{ translate('Find My Store') }}', false);
                    });
                });
            }

            if (mapLocateBtn) {
                mapLocateBtn.addEventListener('click', function () {
                    mapLocateBtn.classList.add('ky-locating');
                    locateNearestStore(function () {
                        mapLocateBtn.classList.remove('ky-locating');
                    });
                });
            }
        });
    </script>
@endsection
