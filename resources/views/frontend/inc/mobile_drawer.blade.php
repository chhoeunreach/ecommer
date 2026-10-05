@php
    $drawerLabels = json_decode(get_setting('header_menu_labels') ?? '[]', true) ?: [];
    $drawerLinks = json_decode(get_setting('header_menu_links') ?? '[]', true) ?: [];
    $drawerIcons = ['home' => 'la-home', 'flash sale' => 'la-bolt', 'blogs' => 'la-newspaper', 'all brands' => 'la-tags', 'all categories' => 'la-th-large'];
@endphp
<style>
    .aiz-top-menu-sidebar.ky-drawer { z-index: 1050 !important; }
    .ky-drawer .overlay { background: rgba(35, 5, 21, .42); backdrop-filter: blur(3px); }
    .aiz-top-menu-sidebar.ky-drawer .collapse-sidebar {
        width: 340px; max-width: calc(100vw - 44px); height: 100%; height: 100dvh;
        display: flex; flex-direction: column; padding: max(24px, env(safe-area-inset-top)) 20px max(24px, env(safe-area-inset-bottom));
        background: #fff; border-radius: 0 24px 24px 0; box-shadow: 12px 0 48px rgba(53, 5, 30, .16);
        overscroll-behavior: contain; color: #30212a;
    }
    .ky-drawer-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 26px; }
    .ky-drawer-heading h2 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -.6px; }
    .ky-drawer-close { width: 40px; height: 40px; flex-shrink: 0; border: 1px solid #eee7e9; border-radius: 50%; background: #fcfaf8; color: #796873; font-size: 22px; display: grid; place-items: center; cursor: pointer; }
    .ky-drawer-profile { padding: 18px; border: 1px solid #efdfb2; background: linear-gradient(120deg, #fff5d8, #fffdf6); border-radius: 20px; margin-bottom: 26px; }
    .ky-drawer-user { display: flex; gap: 12px; align-items: center; min-width: 0; }
    .ky-drawer-avatar { width: 46px; height: 46px; flex-shrink: 0; display: grid; place-items: center; border-radius: 15px; background: #fff; color: #35051e; font-size: 26px; overflow: hidden; }
    .ky-drawer-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .ky-drawer-user strong { display: block; font-size: 15px; line-height: 1.5; overflow-wrap: anywhere; }
    .ky-drawer-user p { margin: 3px 0 0; color: #85715c; font-size: 12px; line-height: 1.5; }
    .ky-drawer-auth { display: flex; gap: 8px; margin-top: 16px; }
    .ky-drawer-auth a { flex: 1; padding: 10px 5px; border-radius: 10px; text-align: center; font-size: 12px; font-weight: 600; background: #fff; color: #35051e; border: 1px solid #e9d69c; }
    .ky-drawer-auth a:first-child { background: #35051e; border-color: #35051e; color: #f4cd42; }
    .ky-drawer-label { margin: 0 12px 10px; color: #9b8790; font-size: 10px; letter-spacing: 1.6px; text-transform: uppercase; font-weight: 700; }
    .ky-drawer-list { padding: 0; margin: 0; list-style: none; }
    .ky-drawer-list li + li { margin-top: 5px; }
    .ky-drawer-link { display: flex; align-items: center; gap: 13px; min-height: 52px; padding: 12px; border-radius: 13px; color: #51424a; font-size: 14px; font-weight: 600; transition: background .2s, color .2s; }
    .ky-drawer-link > i:first-child { font-size: 23px; color: #9a858f; width: 25px; text-align: center; flex-shrink: 0; }
    .ky-drawer-link span { flex: 1; }
    .ky-drawer-link .la-angle-right { font-size: 12px; opacity: .45; }
    .ky-drawer-link:hover, .ky-drawer-link.active { background: #35051e; color: #fff; }
    .ky-drawer-link:hover > i:first-child, .ky-drawer-link.active > i:first-child { color: #f4cd42; }
    .ky-drawer-link.active .la-angle-right { color: #f4cd42; opacity: 1; }
    .ky-drawer-account { margin-top: 22px; padding-top: 22px; border-top: 1px solid #f0e9e5; }
    .ky-drawer-footer { margin-top: auto; padding: 24px 12px 0; }
    .ky-drawer-footer a { display: inline-flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; color: #88747f; }
    .ky-drawer a:focus-visible, .ky-drawer button:focus-visible { outline: 2px solid #35051e; outline-offset: 3px; }
    @media (prefers-reduced-motion: reduce) { .aiz-top-menu-sidebar.ky-drawer, .aiz-top-menu-sidebar.ky-drawer .collapse-sidebar, .ky-drawer .overlay, .ky-drawer-link { transition: none; } }
</style>
<div class="aiz-top-menu-sidebar ky-drawer collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar" data-same=".hide-top-menu-bar" aria-hidden="true"></div>
    <aside class="collapse-sidebar c-scrollbar-light text-left" aria-label="{{ translate('Main menu') }}">
        <div class="ky-drawer-heading">
            <h2>{{ translate('Menu') }}</h2>
            <button type="button" class="ky-drawer-close hide-top-menu-bar" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar" aria-label="{{ translate('Close menu') }}"><i class="las la-times" aria-hidden="true"></i></button>
        </div>
        <div class="ky-drawer-profile">
            <div class="ky-drawer-user">
                <span class="ky-drawer-avatar">
                    @auth
                        <img src="{{ $user_avatar }}" alt="{{ translate('Avatar') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <i class="las la-user" aria-hidden="true"></i>
                    @endauth
                </span>
                <div>
                    <strong>@auth {{ $user->name }} @else {{ translate('Welcome!') }} @endauth</strong>
                    <p>@auth {{ translate('Good to see you again') }} @else {{ translate('Your next favorite is here') }} @endauth</p>
                </div>
            </div>
            @guest
                <div class="ky-drawer-auth">
                    <a href="{{ route('user.login') }}">{{ translate('Login') }}</a>
                    <a href="{{ route('user.registration') }}">{{ translate('Registration') }}</a>
                </div>
            @endguest
        </div>
        <nav aria-label="{{ translate('Store navigation') }}">
            <p class="ky-drawer-label">{{ translate('Explore') }}</p>
            <ul class="ky-drawer-list">
                @foreach ($drawerLabels as $key => $value)
                    @php
                        $drawerLink = $drawerLinks[$key] ?? '/';
                        $drawerActive = is_active_header_menu($drawerLink);
                        $drawerIcon = $drawerIcons[strtolower(trim($value))] ?? 'la-compass';
                    @endphp
                    <li><a href="{{ header_menu_url($drawerLink) }}" class="ky-drawer-link {{ $drawerActive ? 'active' : '' }}" @if($drawerActive) aria-current="page" @endif>
                        <i class="las {{ $drawerIcon }}" aria-hidden="true"></i><span>{{ translate($value) }}</span><i class="las la-angle-right" aria-hidden="true"></i>
                    </a></li>
                @endforeach
            </ul>
            @auth
                <div class="ky-drawer-account">
                    <p class="ky-drawer-label">{{ translate('Your account') }}</p>
                    @php
                        $drawerAccountLinks = [[isAdmin() ? 'admin.dashboard' : 'dashboard', 'My Account', 'la-user-circle']];
                        if (isCustomer()) {
                            $drawerAccountLinks = array_merge($drawerAccountLinks, [['customer.all-notifications', 'Notifications', 'la-bell'], ['wishlists.index', 'Wishlist', 'la-heart'], ['compare', 'Compare', 'la-exchange-alt']]);
                        }
                    @endphp
                    <ul class="ky-drawer-list">
                        @foreach ($drawerAccountLinks as [$drawerRoute, $drawerLabel, $drawerIcon])
                            <li><a href="{{ route($drawerRoute) }}" class="ky-drawer-link {{ request()->routeIs($drawerRoute) ? 'active' : '' }}" @if(request()->routeIs($drawerRoute)) aria-current="page" @endif><i class="las {{ $drawerIcon }}" aria-hidden="true"></i><span>{{ translate($drawerLabel) }}</span><i class="las la-angle-right" aria-hidden="true"></i></a></li>
                        @endforeach
                    </ul>
                </div>
            @endauth
        </nav>
        <div class="ky-drawer-footer">
            @auth
                <a href="{{ route('logout') }}"><i class="las la-sign-out-alt" aria-hidden="true"></i>{{ translate('Logout') }}</a>
            @else
                <a href="{{ route('home') }}"><i class="las la-shopping-bag" aria-hidden="true"></i>{{ translate('Discover something you love') }}</a>
            @endauth
        </div>
    </aside>
</div>
