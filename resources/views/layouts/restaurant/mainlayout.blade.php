<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.restaurant.head')
</head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            @include('layouts.restaurant.sidebar')
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                @yield('topbar')
                <!-- Page content-->
                <div class="container-fluid">
                    <main>
                       @yield('content')
                    </main>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        @include('layouts.restaurant.footer')
        @yield('pagescript')
    </body>
</html>
