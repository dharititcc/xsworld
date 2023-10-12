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
                                <h2>Opening Times</h2> <a href="javascript:void(0);" class="edit venue_popup_modal ">EDIT</a>
                            </div>
                            <div class="padbox">
                                <table class="opening-time opening_timing_table">
                                    <form name="addtimerform" id="addtimerform" method="post">
                                        @if($res_times->count() === 0)
                                            @foreach ($days as $key => $day)
                                            <?php $key += 1;
                                                $id = session('restaurant');;
                                            ?>
                                                <tr>
                                                    <th>{{$day->name}}</th>
                                                    <td>
                                                        <input type="hidden" name="res_id" id="res_id" data-id = "{{$id->id}}">
                                                        <input class="start_time" style="display: none" data-day_id ="{{$key}}" value="{{$key}}" name="start_time[{{$key}}]" type="time" value=""
                                                            placeholder="Start Time"><input class="close_time"
                                                            placeholder="Close TIme" style="display: none"  name="end_time[{{$key}}]" type="time" value="">
                                                        <label for="time" class="times">-</label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach ($res_times as $res_time)
                                                <tr>
                                                    <th>{{$res_time->day->name}}</th>
                                                    <td>
                                                        <input type="hidden" name="res_id" id="res_id" data-id = "{{$res_time->restaurant_id}}">
                                                        <input class="start_time" style="display: none" value="{{$res_time->start_time}}" name="start_time[{{$res_time->day->id}}]" type="time"
                                                            placeholder="Start Time"><input class="close_time"
                                                            placeholder="Close TIme" style="display: none"  name="end_time[{{$res_time->day->id}}]" type="time" value="{{$res_time->close_time}}">
                                                        <label for="time" class="times">{{($res_time->start_time) ? $res_time->start_time . ' - '. $res_time->close_time : 'Close' }} </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </form>
                                </table>
                                <button class="bor-btn w-100 font-26" id="venue_submitBtn" style="display: none" type="submit">Add Open
                                    Timming</button>

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
@section('pagescript')
    <script src="{{ asset('js/venue.js') }}"></script>
    <script>
        var moduleConfig = {
        venueStore: "{!! route('restaurants.venue.store') !!}",

    };
        $(document).ready(function() {
            XS.Venue.init();
        });
    </script>
@endsection
