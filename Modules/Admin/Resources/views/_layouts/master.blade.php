<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Module Admin</title>
        <meta name="description" content="">
        <meta name="msapplication-tap-highlight" content="no">
        <!--
        =========================================================
        * ArchitectUI HTML Theme Dashboard - v1.0.0
        =========================================================
        * Product Page: https://dashboardpack.com
        * Copyright 2019 DashboardPack (https://dashboardpack.com)
        * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
        =========================================================
        * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
        -->
        {{-- Laravel Mix - CSS File --}}
        <link rel="stylesheet" href="{{ mix('architect-ui/main.css') }}">
        <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
        <link rel="stylesheet" href="{{ mix('css/admin-styles.css') }}">
    </head>
    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">

            {{-- App Header --}}
            @include('admin::_layouts.templates.header')

            <div class="app-main">

                {{-- App Sidebar --}}
                @include('admin::_layouts.templates.sidebar')

                <div class="app-main__outer">
                    <div class="app-main__inner">

                        {{-- Page Header --}}
                        @include('admin::_layouts.templates.page-header')

                        {{-- Page Content --}}
                        @yield('content')

                    </div>

                    {{-- Page Footer Start --}}
                    @include('admin::_layouts.templates.footer')

                </div>
                {{-- <script src="http://maps.google.com/maps/api/js?sensor=false"></script> --}}
            </div>
        </div>
        {{-- Laravel Mix - JS File --}}
        <script src="{{ mix('architect-ui/assets/scripts/main.js') }}"></script>
        <script src="{{ mix('js/admin.js') }}"></script>
        <script src="{{ mix('js/admin-scripts.js') }}"></script>
        @stack('scripts')
    </body>
</html>
