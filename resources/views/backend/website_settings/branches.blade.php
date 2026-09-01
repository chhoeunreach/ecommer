@extends('backend.layouts.app')

@section('content')
    @php
        if (count($branches) === 0) {
            $phone = get_setting('contact_phone') ?: '+855 (0) 12 345 678';
            $branches = [
                ['brand' => 'ANLYN POP', 'name' => 'Sen Sok Flagship', 'city' => 'Phnom Penh', 'address' => 'AEON Mall Sen Sok City, 2nd Floor, Phnom Penh', 'hours' => 'Monday – Sunday · 10:00 AM – 9:00 PM', 'phone' => $phone, 'map' => 'https://www.google.com/maps/search/?api=1&query=AEON+Mall+Sen+Sok+City+Phnom+Penh', 'facebook' => '', 'instagram' => '', 'image' => null, 'active' => true],
                ['brand' => 'ANLYN BLOOM', 'name' => 'BKK1 Boutique', 'city' => 'Phnom Penh', 'address' => 'BKK1, Chamkarmon, Phnom Penh', 'hours' => 'Monday – Sunday · 9:00 AM – 8:30 PM', 'phone' => $phone, 'map' => 'https://www.google.com/maps/search/?api=1&query=BKK1+Phnom+Penh', 'facebook' => '', 'instagram' => '', 'image' => null, 'active' => true],
                ['brand' => 'ANLYN POP', 'name' => '271 Concept Store', 'city' => 'Phnom Penh', 'address' => 'Chip Mong 271 Mega Mall, 2nd Floor, Phnom Penh', 'hours' => 'Monday – Sunday · 10:00 AM – 9:00 PM', 'phone' => $phone, 'map' => 'https://www.google.com/maps/search/?api=1&query=Chip+Mong+271+Mega+Mall+Phnom+Penh', 'facebook' => '', 'instagram' => '', 'image' => null, 'active' => true],
            ];
        }
    @endphp

    <div class="row">
        <div class="col-xl-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h5 mb-1">{{ translate('Branch Location Page') }}</h1>
                    <p class="text-muted fs-12 mb-0">{{ translate('Manage the public ANLYN POP / ANLYN BLOOM store locator.') }}</p>
                </div>
                <a href="{{ route('branches') }}" class="btn btn-soft-primary btn-sm" target="_blank" rel="noopener">
                    <i class="las la-external-link-alt mr-1"></i>{{ translate('View page') }}
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ translate('Please correct the highlighted branch information.') }}</strong>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('website.branches.update') }}" method="POST" id="branch-settings-form">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h2 class="h6 mb-0">{{ translate('Hero & Introduction') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row gutters-16">
                            <div class="col-lg-5">
                                <div class="form-group mb-lg-0">
                                    <label class="font-weight-bold">{{ translate('Hero image') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div></div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="hero_image" class="selected-files" value="{{ old('hero_image', get_setting('anlyn_branches_hero_image')) }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    <small class="text-muted">{{ translate('Recommended: landscape image, at least 1600 × 1000 px. The designed default remains until you upload one.') }}</small>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label>{{ translate('Eyebrow text') }}</label>
                                    <input type="text" name="hero_eyebrow" class="form-control" maxlength="120"
                                        value="{{ old('hero_eyebrow', get_setting('anlyn_branches_hero_eyebrow', 'Our little world, closer to you')) }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Hero title') }}</label>
                                    <input type="text" name="hero_title" class="form-control" maxlength="120"
                                        value="{{ old('hero_title', get_setting('anlyn_branches_hero_title', 'Find your happy place.')) }}">
                                    <small class="text-muted">{{ translate('The final words are styled with the ANLYN editorial typeface.') }}</small>
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Hero description') }}</label>
                                    <textarea name="hero_description" class="form-control" rows="3" maxlength="500">{{ old('hero_description', get_setting('anlyn_branches_hero_description', 'Step into a warm world of collectibles, thoughtful gifts, flowers and charming everyday finds.')) }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label>{{ translate('Branches section description') }}</label>
                                    <textarea name="section_description" class="form-control" rows="3" maxlength="500">{{ old('section_description', get_setting('anlyn_branches_section_description', 'Each ANLYN store has its own personality, with the same thoughtful service and joyful discoveries.')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h3 class="h6 mb-1">{{ translate('Fonts, Colors & Sizes') }}</h3>
                                <p class="text-muted fs-12 mb-0">{{ translate('Sizes are desktop values; the page automatically scales them for mobile screens.') }}</p>
                            </div>
                            <button type="button" class="btn btn-xs btn-soft-secondary" id="reset-branch-typography">{{ translate('Reset defaults') }}</button>
                        </div>

                        @php
                            $fontFamilies = [
                                'public-sans' => 'Public Sans',
                                'georgia' => 'Georgia',
                                'times' => 'Times New Roman',
                                'arial' => 'Arial',
                            ];
                            $fontFamilyControls = [
                                ['key' => 'hero_title_font', 'label' => translate('Hero title font'), 'setting' => 'anlyn_branches_hero_title_font', 'default' => 'public-sans'],
                                ['key' => 'hero_body_font', 'label' => translate('Hero supporting font'), 'setting' => 'anlyn_branches_hero_body_font', 'default' => 'public-sans'],
                                ['key' => 'section_title_font', 'label' => translate('Our Branches title font'), 'setting' => 'anlyn_branches_section_title_font', 'default' => 'georgia'],
                                ['key' => 'section_body_font', 'label' => translate('Section description font'), 'setting' => 'anlyn_branches_section_body_font', 'default' => 'public-sans'],
                            ];
                            $fontControls = [
                                ['key' => 'hero_eyebrow', 'label' => translate('Hero eyebrow'), 'color' => '#f4e5e8', 'size' => 11, 'min' => 8, 'max' => 30],
                                ['key' => 'hero_title', 'label' => translate('Hero title'), 'color' => '#ffffff', 'size' => 102, 'min' => 36, 'max' => 140],
                                ['key' => 'hero_title_accent', 'label' => translate('Hero accent words'), 'color' => '#f7d9df', 'size' => null, 'min' => null, 'max' => null],
                                ['key' => 'hero_description', 'label' => translate('Hero description'), 'color' => '#f2e9eb', 'size' => 16, 'min' => 10, 'max' => 30],
                                ['key' => 'section_title', 'label' => translate('Our Branches title'), 'color' => '#282124', 'size' => 72, 'min' => 30, 'max' => 100],
                                ['key' => 'section_description', 'label' => translate('Section description'), 'color' => '#746a6d', 'size' => 14, 'min' => 10, 'max' => 26],
                            ];
                        @endphp
                        <div class="row gutters-12 mb-1">
                            @foreach ($fontFamilyControls as $control)
                                @php $fontValue = old($control['key'], get_setting($control['setting'], $control['default'])); @endphp
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold fs-12">{{ $control['label'] }}</label>
                                        <select name="{{ $control['key'] }}" class="form-control branch-font-default" data-default="{{ $control['default'] }}" required>
                                            @foreach ($fontFamilies as $value => $label)
                                                <option value="{{ $value }}" @selected($fontValue === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row gutters-12">
                            @foreach ($fontControls as $control)
                                @php
                                    $colorSetting = 'anlyn_branches_' . $control['key'] . '_color';
                                    $colorValue = old($control['key'] . '_color', get_setting($colorSetting, $control['color']));
                                @endphp
                                <div class="{{ $control['size'] === null ? 'col-lg-4 col-md-6' : 'col-lg-4 col-md-6' }}">
                                    <div class="border rounded p-3 mb-3 h-100">
                                        <label class="font-weight-bold fs-12">{{ $control['label'] }}</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control aiz-color-input branch-font-default" name="{{ $control['key'] }}_color"
                                                value="{{ $colorValue }}" data-default="{{ $control['color'] }}" required pattern="#[0-9A-Fa-f]{6}">
                                            <div class="input-group-append">
                                                <span class="input-group-text p-0">
                                                    <input type="color" class="aiz-color-picker border-0 size-40px" data-target="{{ $control['key'] }}_color" value="{{ $colorValue }}">
                                                </span>
                                            </div>
                                        </div>
                                        @if ($control['size'] !== null)
                                            @php
                                                $sizeSetting = 'anlyn_branches_' . $control['key'] . '_size';
                                                $sizeValue = old($control['key'] . '_size', get_setting($sizeSetting, $control['size']));
                                            @endphp
                                            <div class="input-group">
                                                <input type="number" class="form-control branch-font-default" name="{{ $control['key'] }}_size"
                                                    min="{{ $control['min'] }}" max="{{ $control['max'] }}" value="{{ $sizeValue }}" data-default="{{ $control['size'] }}" required>
                                                <div class="input-group-append"><span class="input-group-text">px</span></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
                    <div>
                        <h2 class="h6 mb-1">{{ translate('Store Branches') }}</h2>
                        <p class="text-muted fs-12 mb-0">{{ translate('Drag-free ordering: use the arrow buttons on each card.') }}</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-branch">
                        <i class="las la-plus mr-1"></i>{{ translate('Add branch') }}
                    </button>
                </div>

                <div id="branch-repeater">
                    @foreach (old('branches', $branches) as $index => $branch)
                        @include('backend.website_settings.partials.branch_form_card', ['index' => $index, 'branch' => $branch])
                    @endforeach
                </div>

                <div class="sticky-bottom bg-white border rounded p-3 d-flex align-items-center justify-content-between shadow-sm">
                    <span class="text-muted fs-12"><i class="las la-info-circle mr-1"></i>{{ translate('Changes appear on the public branch page immediately after saving.') }}</span>
                    <button type="submit" class="btn btn-success px-4">{{ translate('Save branch page') }}</button>
                </div>
            </form>
        </div>
    </div>

    <template id="branch-card-template">
        @include('backend.website_settings.partials.branch_form_card', [
            'index' => '__INDEX__',
            'branch' => ['brand' => 'ANLYN POP', 'name' => '', 'city' => 'Phnom Penh', 'address' => '', 'hours' => 'Monday – Sunday · 10:00 AM – 9:00 PM', 'phone' => get_setting('contact_phone', ''), 'map' => '', 'facebook' => '', 'instagram' => '', 'image' => null, 'active' => true],
        ])
    </template>
@endsection

@section('script')
    <script>
        (function () {
            var repeater = document.getElementById('branch-repeater');
            var template = document.getElementById('branch-card-template');

            function reindexCards() {
                repeater.querySelectorAll('.branch-admin-card').forEach(function (card, index) {
                    card.dataset.index = index;
                    card.querySelector('.branch-card-number').textContent = index + 1;
                    card.querySelectorAll('[name]').forEach(function (field) {
                        field.name = field.name.replace(/branches\[\d+\]/, 'branches[' + index + ']');
                    });
                });
            }

            document.getElementById('add-branch').addEventListener('click', function () {
                var index = repeater.querySelectorAll('.branch-admin-card').length;
                repeater.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', index));
                reindexCards();
            });

            repeater.addEventListener('click', function (event) {
                var button = event.target.closest('[data-branch-action]');
                if (!button) return;

                var card = button.closest('.branch-admin-card');
                var action = button.dataset.branchAction;

                if (action === 'remove') {
                    if (repeater.querySelectorAll('.branch-admin-card').length === 1) {
                        AIZ.plugins.notify('warning', '{{ translate('At least one branch is required.') }}');
                        return;
                    }
                    card.remove();
                } else if (action === 'up' && card.previousElementSibling) {
                    repeater.insertBefore(card, card.previousElementSibling);
                } else if (action === 'down' && card.nextElementSibling) {
                    repeater.insertBefore(card.nextElementSibling, card);
                }

                reindexCards();
            });

            document.getElementById('reset-branch-typography').addEventListener('click', function () {
                document.querySelectorAll('.branch-font-default').forEach(function (field) {
                    field.value = field.dataset.default;
                    if (field.name.endsWith('_color')) {
                        var picker = document.querySelector('[data-target="' + field.name + '"]');
                        if (picker) picker.value = field.dataset.default;
                    }
                });
            });
        })();
    </script>
@endsection
