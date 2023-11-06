<div id="sidebar-wrapper">
        <div class="sidebar-title xs-admin-ttl"><button type="button" id="sidebarToggle1"><i class="icon-left"></i></button> <img src="{{ asset('img/xsworld.png') }}" alt="" class="logo"></div>
    <figure class="xs-admin-logo"><img src="{{ asset('img/logo.svg') }}" alt="xs-logo"></figure>
    <div class="list-group">
        <a href="{{ route('admin.home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Dashboard</a>
        {{-- <a href="{{ route('restaurants.categories.index') }}" class="{{ Route::is('restaurants.categories.*') ? 'active' : '' }}">Categories Management</a> --}}
        {{-- <a href="add-drinks.html">Add Drinks</a> --}}
        <a href="{{ route('admin.restaurant.index') }}" class="{{ Route::is('admin.restaurant.*') ? 'active' : '' }}">Restaurants</a>
        <a href="{{ route('admin.event.index') }}" class="{{ Route::is('admin.event.*') ? 'active' : '' }}">Events</a>
        <a href="{{ route('admin.customer.index') }}" class="{{ Route::is('admin.customer.*') ? 'active' : '' }}">Customers</a>
        <a class="" href="{{ route('admin.auth.logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
  </div>
    <div class="sidebar-footer"><label> what will it be today?</label> <span class="year">2023</span></div>
</div>
