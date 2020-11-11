<div class="app-sidebar sidebar-shadow bg-slick-carbon sidebar-text-light">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box"><span class="hamburger-inner"></span></span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper"><i class="fa fa-ellipsis-v fa-w-6"></i></span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                
                {{-- Dashboard Menu Section --}}
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="{{ route('admin::home') }}" class="mm-active">
                        <i class="metismenu-icon pe-7s-rocket"></i> Dashboard
                    </a>
                </li>
                
                {{-- General Menu Section --}}
                <li class="app-sidebar__heading">Menu</li>
                <li class="mm-active">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-menu"></i>Dropdown Expanded<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 1
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 2
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 3
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-menu"></i>Dropdown Collapsed<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 1
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 2
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i> Item 3
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-display2"></i> List Item
                    </a>
                </li>
                
                {{-- Auth Menu Section --}}
                <li class="app-sidebar__heading">Auth</li>
                <li>
                    <a href="{{ route('admin::users.index') }}">
                        <i class="metismenu-icon pe-7s-users"></i> Users
                    </a>
                </li>
                
                {{--
                    <li>
                        <a href="#">
                            <i class="metismenu-icon pe-7s-look"></i> Administrator
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="metismenu-icon pe-7s-headphones"></i> Mortals
                        </a>
                    </li>
                --}}
            </ul>
        </div>
    </div>
</div>