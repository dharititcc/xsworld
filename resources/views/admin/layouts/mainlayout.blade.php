<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.common.head')
</head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            @include('layouts.common.sidebar')
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
        @include('layouts.common.footer')
        <script>
            var pathname = window.location.pathname,
                body     = document.getElementsByTagName('body')[0];

            if( pathname == 'admin/home' )
            {
                body.classList.add('home');
            }
            else
            {
                body.classList.remove('home')
            }
        </script>
        @yield('pagescript')
    </body>
</html>