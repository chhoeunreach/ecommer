<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3 ml-0" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <div class="d-flex justify-content-around align-items-center align-items-stretch">
                <div class="aiz-topbar-item mr-3">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-topbar has-transition w-35px h-35px btn-circle btn-light p-0 hov-bg-primary hov-svg-white d-flex align-items-center justify-content-center" href="{{ route('home')}}" target="_blank" data-toggle="tooltip" data-title="{{ translate('Browse Website') }}">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="_754bac7463b8b1afad8e10a2355d1700" data-name="754bac7463b8b1afad8e10a2355d1700" d="M56,48a8,8,0,1,0,8,8A8,8,0,0,0,56,48Zm-.829,14.808a6.858,6.858,0,0,1-4.39-11.256,7.6,7.6,0,0,1,.077.93,2.966,2.966,0,0,0,.382,2.26,3.729,3.729,0,0,1,.362,1.08c.1.341.5.519.77.729.552.423,1.081.916,1.666,1.288.387.246.628.368.515.84a2.98,2.98,0,0,1-.313.951,1.927,1.927,0,0,0,.321.861c.288.288.575.553.889.813C55.938,61.706,55.4,62.229,55.171,62.808Zm5.678-1.959a6.808,6.808,0,0,1-3.56,1.888,2.844,2.844,0,0,1,.842-1.129,2.865,2.865,0,0,0,.757-.937,6.506,6.506,0,0,1,.522-.893c.272-.419-.67-1.051-.975-1.184a10.052,10.052,0,0,1-1.814-1.13c-.435-.306-1.318.16-1.808-.054A9.462,9.462,0,0,1,53,56.166c-.6-.454-.574-.984-.574-1.654.472.017,1.144-.131,1.458.249.1.12.439.655.667.465.186-.155-.138-.779-.2-.925-.193-.451.439-.626.762-.932.422-.4,1.326-1.024,1.254-1.309s-.9-1.1-1.394-.969c-.073.019-.719.7-.844.8q0-.332.01-.663c0-.14-.26-.283-.248-.373.031-.227.664-.64.821-.821-.11-.069-.487-.392-.6-.345-.276.115-.588.194-.863.309a1.756,1.756,0,0,0-.025-.274,6.792,6.792,0,0,1,1.743-.506l.542.218.382.454.382.394.334.108.53-.5L57,49.536v-.321a6.782,6.782,0,0,1,2.9,1.146c-.155.014-.326.037-.518.061a1.723,1.723,0,0,0-.268-.1c.251.54.513,1.073.779,1.606.284.569.915,1.18,1.026,1.781.131.708.04,1.352.111,2.185a3.732,3.732,0,0,0,.9,1.714,1.812,1.812,0,0,0,.707.086A6.815,6.815,0,0,1,60.849,60.849Z" transform="translate(-48 -48)" fill="#717580"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @if (addon_is_activated('pos_system'))
                <div class="d-flex justify-content-around align-items-center align-items-stretch">
                    <div class="aiz-topbar-item mr-3">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-topbar has-transition w-35px h-35px btn-circle btn-light p-0 hov-bg-primary hov-text-white d-flex align-items-center justify-content-center" href="{{ route('poin-of-sales.seller_index') }}" target="_blank" data-toggle="tooltip" data-title="{{ translate('POS') }}">
                                <i class="las la-print fs-18"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

          
            <div class="aiz-topbar-item mr-3">
                <div class="d-flex align-items-center">
                    <a class="btn btn-topbar has-transition w-35px h-35px btn-circle btn-light p-0 hov-bg-primary hov-svg-white d-flex align-items-center justify-content-center"
                        href="{{ route('seller.cache.clear') }}" data-toggle="tooltip" data-title="{{ translate('Clear Cache') }}">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16.195" height="16.195" viewBox="0 0 16.195 16.195">
                            <path id="_74846e5be5db5b666d3893933be03656" data-name="74846e5be5db5b666d3893933be03656" d="M7.777,9H8.971v1.2H7.777v1.2H6.582V10.2H5.388V9H6.582V7.8H7.777Zm-2.388,4.8H6.582v1.2H5.388v1.2H4.194v-1.2H3v-1.2H4.194v-1.2H5.388ZM19.181,11H10.225v-.6a1.2,1.2,0,0,1,1.194-1.2h2.388V2H15.6V9.2h2.388a1.2,1.2,0,0,1,1.194,1.2Zm-.6,7.2H16.2a9.372,9.372,0,0,0,.589-3,.6.6,0,1,0-1.194,0,7.784,7.784,0,0,1-.7,3H12.621a9.372,9.372,0,0,0,.589-3,.6.6,0,1,0-1.194,0,7.784,7.784,0,0,1-.7,3H9.03a23.1,23.1,0,0,0,1.177-6h8.978A19.357,19.357,0,0,1,18.584,18.2Z" transform="translate(-3 -2)" fill="#717580"/>
                        </svg>
                    </a>
                </div>
            </div>

           
            <div class="aiz-topbar-item mr-2 d-none d-xl-block">
                <div class="d-flex align-items-center h-100">
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.dashboard']) }}"
                        href="{{ route('seller.dashboard') }}">{{ translate('Dashboard') }}</a>
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.orders.index']) }}"
                        href="{{ route('seller.orders.index') }}">{{ translate('Orders') }}</a>
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.shop.index']) }}"
                        href="{{ route('seller.shop.index') }}">{{ translate('Shop Settings') }}</a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-around align-items-center align-items-stretch">
             
             <div class="aiz-topbar-item mr-3">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-topbar has-transition w-35px h-35px btn-circle btn-light p-0 hov-svg-dark d-flex align-items-center justify-content-center">
                            <span class="d-flex align-items-center position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16">
                                        <g id="Group_23884" data-name="Group 23884" transform="translate(-677 5110)">
                                        <path id="Union_38" data-name="Union 38" d="M5.5,16a.5.5,0,0,1,0-1h3a.5.5,0,1,1,0,1Zm-5-2a.5.5,0,0,1,0-1H2V7A5.008,5.008,0,0,1,6.5,2.025V.5a.5.5,0,1,1,1,0V2.025A5.007,5.007,0,0,1,12,7H11A4,4,0,1,0,3,7v6h8V7h1v6h1.5a.5.5,0,1,1,0,1Z" transform="translate(677 -5110)" fill="#9da3ae"/>
                                        </g>
                                    </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right" style="top: -9px!important; right: -7px!important;"></span>
                                @endif
                            </span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xl py-0">
                        <div class="notifications">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-dark active" data-toggle="tab" data-type="order" href="javascript:void(0);"
                                        data-target="#orders-notifications" role="tab" id="orders-tab">{{ translate('Orders') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="preorder" href="javascript:void(0);"
                                        data-target="#preorders-notifications" role="tab" id="preorders-tab">{{ translate('Preorders') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                                        data-target="#sellers-notifications" role="tab" id="sellers-tab">{{ translate('Products') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                                        data-target="#payouts-notifications" role="tab" id="sellers-tab">{{ translate('Payouts') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content c-scrollbar-light overflow-auto" style="height: 75vh; max-height: 400px; overflow-y: auto;">
                                <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                                    <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="preorders-notifications" role="tabpanel">
                                    <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PreorderNotification')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="sellers-notifications" role="tabpanel">
                                    <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'like', '%shop%')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="payouts-notifications" role="tabpanel">
                                    <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PayoutNotification')->take(20)->get()" />
                                </div>
                            </div>
                        </div>

                        <div class="text-center border-top">
                            <a href="{{ route('seller.all-notification') }}" class="text-reset d-block py-2">
                                {{ translate('View All Notifications') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @php
                if(Session::has('locale')){
                    $locale = Session::get('locale', Config::get('app.locale'));
                }
                else{
                    $locale = env('DEFAULT_LANGUAGE');
                }
            @endphp
            <div class="aiz-topbar-item">
                <div class="align-items-stretch d-flex dropdown " id="lang-change">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-topbar has-transition w-35px h-35px btn-circle btn-light p-0 d-flex align-items-center justify-content-center">
                            <img src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" height="11">
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xs">

                        @foreach (get_all_active_language() as $key => $language)
                            <li>
                                <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language->code) active @endif">
                                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-2">
                                    <span class="language">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @php $authUser = auth()->user(); @endphp
            <div class="aiz-topbar-item ml-3">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="position-relative avatar avatar-sm" style="border: 2px solid #FFAA00;">
                                <img
                                    src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';"
                                >
                                <div class="position-absolute right-0 bottom-0 bg-white rounded-circle d-flex align-items-center justify-content-center" style="right:-4px!important; bottom:-3px!important; width:20px; height:20px;">
                                    @if ($authUser->shop?->verification_status == 1)
                                        <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#75FB4C"><path d="m429-336 238-237-51-51-187 186-85-84-51 51 136 135Zm51 240q-79 0-149-30t-122.5-82.5Q156-261 126-331T96-480q0-80 30-149.5t82.5-122Q261-804 331-834t149-30q80 0 149.5 30t122 82.5Q804-699 834-629.5T864-480q0 79-30 149t-82.5 122.5Q699-156 629.5-126T480-96Zm0-72q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-312Z"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#EA3323"><path d="m768-90-72-72q-49 32-103.5 49T480-96q-79.38 0-149.19-30T208.5-208.5Q156-261 126-330.81T96-480q0-58 17-112.5T162-696l-72-72 51-51 678 678-51 51Zm-287.89-78Q524-168 565-181t79-34L476-383l-47 47-136-136 51-51 85 85-4 4-210-210q-21 38-34 79t-13 84.89q0 129.72 91.19 220.92Q350.39-168 480.11-168ZM798-264l-53-52q22-38 34.5-79t12.5-84.89q0-129.72-91.19-220.92Q609.61-792 479.89-792 436-792 395-779.5T316-745l-52-53q48-32 103-49t113.27-17q79.27 0 149 30t122.23 82.5Q804-699 834-629.27t30 149Q864-422 847-367q-17 55-49 103ZM577-484l-50-51 89-89 51 50-90 90Zm-50-51ZM420-420Z"/></svg>                                
                                    @endif
                                </div>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        
                        @if ($authUser->shop?->verification_status == 0)
                            <a href="{{ route('seller.shop.verify') }}" class="dropdown-item">
                                <i class="las la-user-check"></i>
                                <span>{{translate('Verify now')}}</span>
                            </a>
                        @endif    

                        <a href="{{ route('seller.profile.index') }}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{translate('Profile')}}</span>
                        </a>

                        <a href="{{ route('logout')}}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{translate('Logout')}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
