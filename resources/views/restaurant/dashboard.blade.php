@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('restaurant.partials.topbar')
@endsection
@section('content')
            <div class="hero">
                <img src="{{ session('restaurant')->image }}" alt="{{ session('restaurant')->name }}">
            </div>
            <div class="outrbox">
                <div class="row">
                    <div class="col-lg-5 col-xl-4 act-grid-2">
                        <div class="grey-brd-box mb-4">
                            <div class="title">
                                <h2>Opening Times</h2> <a href="#" class="edit">EDIT</a>
                            </div>
                            <div class="padbox">
                                <table class="opening-time">
                                    <tbody><tr><th>Monday</th><td>12 PM - 10 PM</td></tr>
                                    <tr><th>Tuesday</th><td>12 PM - 10 PM</td></tr>
                                    <tr><th>Wednesday</th><td>12 PM - 10 PM</td></tr>
                                    <tr><th>Thursday</th><td>12 PM - 11:30 PM</td></tr>
                                    <tr><th>Friday</th><td>12PM - 2AM</td></tr>
                                    <tr><th>Saturday</th><td>12PM - 2AM</td></tr>
                                    <tr><th>Sunday</th><td>12PM - 2AM</td></tr>
                                </tbody></table>
                            </div>
                        </div>
                        <div class="grey-brd-box m-lg-space">
                            <div class="title">
                                <h2>Key Insights</h2> <a href="#" class="edit">Disable</a>
                            </div>
                            <div class="padbox">
                                <table width="100%" class="opening-time">
                                    <tbody>
                                    <tr><th>Total Tables</th><td> {{$res_tables->count()}}</td></tr>
                                    <tr><th>Active Tables</th><td> {{$active_tbl}}</td></tr>
                                    <tr><th>Occupied Tables</th><td>2 </td></tr>
                                    <tr><th>Reserved Tables</th><td> 0</td></tr>
                                    <tr><th>Staffed Tables</th><td> 2</td></tr>
                                </tbody></table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-xl-8">
                        <div class="grid colmn-3 h-100 m-h-100">
                            <a href="{{ route('restaurants.venue.index') }}" class="{{ Route::is('restaurants.venue.*') ? 'active' : '' }} grey-brd-box padbox text-center lable-box"><span>Venue Management</span></a>
                            <a href="#" class="grey-brd-box padbox text-center lable-box"><span>Analytics</span></a>
                            <a href="{{ route('restaurants.table.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Table Management</span></a>
                            <a href="{{ route('restaurants.foods.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Food List</span></a>
                            <a href="{{ route('restaurants.drinks.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Drink List</span></a>
                            <a href="{{ route('restaurants.mixers.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Mixer Management</span></a>
                            <a href="{{ route('restaurants.categories.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Category Management</span></a>
                            <a href="{{route('restaurants.pickup.index')}}" class="grey-brd-box padbox text-center lable-box"><span>Pick-up Zone Management</span></a>
                            <a href="{{route('restaurants.addons.index')}}" class="grey-brd-box padbox text-center lable-box"><span>Addon Management</span></a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
