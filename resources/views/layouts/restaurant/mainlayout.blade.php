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
        <script>
            var pathname = window.location.pathname,
                body     = document.getElementsByTagName('body')[0];

            if( pathname == '/home' )
            {
                body.classList.add('home');
            }
            else
            {
                body.classList.remove('home')
            }

            if( pathname == '/restaurants/orders' || pathname == '/analytics' )
            {
                body.classList.add('orders');
            }
            else
            {
                body.classList.remove('orders')
            }

            $('#sidebarToggle1').on('click', function(e) {
                e.preventDefault();

                $('body').removeClass('sb-sidenav-toggled');
            });
        </script>
        @yield('pagescript')
    </body>
</html>
