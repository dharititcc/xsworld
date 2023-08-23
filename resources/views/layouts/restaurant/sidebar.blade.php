<!-- Sidebar-->
<div id="sidebar-wrapper">
    <div class="sidebar-header"><label> {{ session('restaurant')->name }}</label> <a href="javascript:void(0);" class="setting"> <i class="icon-settings-thin"></i></a></div>
    <div class="sidebar-title"><button type="button" id="sidebarToggle1"><i class="icon-left"></i></button> <img src="img/xsworld.png" alt="" class="logo"></div>
    <figure><img src="{{ session('restaurant')->image }}" alt="{{ session('restaurant')->name }}"></figure>
    <div class="list-group">
        <a href="{{ route('home') }}">Dashboard</a>
        <a href="{{ route('restaurants.categories.index') }}">Categories Management</a>
        <a href="add-drinks.html">Add Drinks</a>
        <a href="drinks-list.html">Drinks List</a>
        <a href="#">Mixer Management</a>
        <a href="#">Analytics</a>
        <a href="#">Pick-up Zones</a>
        <a href="#">Bar Management</a>
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