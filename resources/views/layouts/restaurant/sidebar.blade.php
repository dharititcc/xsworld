<div id="sidebar-wrapper">
    <div class="sidebar-header"><label> {{ session('restaurant')->name }}</label> <a href="javascript:void(0);" class="setting"> <i class="icon-settings-thin"></i></a></div>
    <div class="sidebar-title"><button type="button" id="sidebarToggle1"><i class="icon-left"></i></button> <img src="{{ asset('img/xsworld.png') }}" alt="" class="logo"></div>
    <figure><img src="{{ session('restaurant')->image }}" alt="{{ session('restaurant')->name }}"></figure>
    <div class="list-group">
        <a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('restaurants.categories.index') }}" class="{{ Route::is('restaurants.categories.*') ? 'active' : '' }}">Categories Management</a>
        {{-- <a href="add-drinks.html">Add Drinks</a> --}}
        <a href="{{ route('restaurants.drinks.index') }}" class="{{ Route::is('restaurants.drinks.*') ? 'active' : '' }}">Drinks List</a>
        <a href="{{ route('restaurants.foods.index') }}" class="{{ Route::is('restaurants.foods.*') ? 'active' : '' }}">Foods List</a>
        <a href="{{ route('restaurants.mixers.index') }}" class="{{ Route::is('restaurants.mixers.*') ? 'active' : '' }}">Mixer Management</a>
        <a href="{{ route('restaurants.addons.index') }}" class="{{ Route::is('restaurants.addons.*') ? 'active' : '' }}">Addons Management</a>
        <a href="{{ route('restaurants.waiter.index') }}" class="{{ Route::is('restaurants.waiter.*') ? 'active' : '' }}">Account Manager</a>
        <a href="#">Analytics</a>
        <a href="{{route('restaurants.pickup.index')}}" class="{{ Route::is('restaurants.pickup.*') ? 'active' : '' }}">Pick-up Zones</a>
        <a href="{{ route('restaurants.table.index') }}" class="{{ Route::is('restaurants.table.*') ? 'active' : '' }}">Table Management</a>
        <a href="{{ route('restaurants.venue.index') }}" class="{{ Route::is('restaurants.venue.*') ? 'active' : '' }}">Bar Management</a>
        <a class="" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
        </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
  </div>
    <div class="sidebar-footer"><label> what will it be today?</label> <span class="year">2023</span></div>
</div>
