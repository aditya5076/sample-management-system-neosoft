<!-- BEGIN: Header -->
<header class="m-grid__item m-header " data-minimize-offset="200" data-minimize-mobile-offset="200" >
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
        <!-- BEGIN: Brand -->
        <div class="m-stack__item m-brand  m-brand--skin-dark ">
            <div class="m-stack m-stack--ver m-stack--general">
                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                    <a href="{{ Route('showScreen') }}" class="m-brand__logo-wrapper">
                    <h4 class="text-white">NESTERRA</h4>
                    </a>
                </div>
                <div class="m-stack__item m-stack__item--middle m-brand__tools">
                    <!-- BEGIN: Left Aside Minimize Toggle -->
                    <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                    <span></span>
                    </a>
                    <!-- END -->
                    <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                    <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                    <span></span>
                    </a>
                    <!-- END -->
                    <!-- BEGIN: Responsive Header Menu Toggler -->
                    <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                    <span></span>
                    </a>
                    <!-- END -->
                    <!-- BEGIN: Topbar Toggler -->
                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                    <i class="flaticon-more"></i>
                    </a>
                    <!-- BEGIN: Topbar Toggler -->
                </div>
            </div>
        </div>
        <!-- END: Brand -->
        <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
            <!-- BEGIN: Horizontal Menu -->
            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                <i class="la la-close"></i>
            </button>
            <!-- END: Horizontal Menu -->
            <div class="m-dashboard__head-text"><h4>Sample Management System</h4></div>
            <!-- BEGIN: Topbar -->
            <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                <div class="m-stack__item m-topbar__nav-wrapper">
                    <ul class="m-topbar__nav m-nav m-nav--inline">
                    
                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                        <a href="#" class="m-nav__link m-dropdown__toggle">
                            <span class="m-topbar__userpic pic-text" style="color: #43425D">
                                {{ Str::upper(Auth::user()->name) }} &nbsp; <i class="la la-angle-down"></i>
                            </span>
                        </a>
                        <div class="m-dropdown__wrapper">
                            <div class="m-dropdown__inner">
                                <div class="m-dropdown__header m--align-center" style="background-size: cover;">
                                <div class="m-card-user m-card-user--skin-dark">
                                    <div class="m-card-user__details">
                                        <span class="m-card-user__name m--font-weight-500">
                                            {{Str::upper(Auth::user()->roles->role_name)}}
                                        </span>
                                    </div>
                                </div>
                                </div>
                                <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav m-nav--skin-light">
                                        <li class="m-nav__item">
                                            <a href="{{ route('logout') }}"
                                            class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder lead-advance-search">
                                            {{ __('Logout') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
    </div>
</header>
<!-- END: Header -->