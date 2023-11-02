@extends('admin.layouts.mainlayout')
@section('topbar')
    @include('admin.common.nav')
@endsection
@section('content')
    <div class="outrbox">
        <div class="row">
            {{-- <div class="col-lg-5 col-xl-4 act-grid-2">
                <div class="grey-brd-box mb-4">
                    <div class="title">
                        <h2>Opening Times</h2> <a href="javascript:void(0);" class="edit venue_popup_modal ">EDIT</a>
                    </div>
                    <div class="padbox">
                        <table class="opening-time opening_timing_table">
                            <form name="addtimerform" id="addtimerform" method="post">
                                @if ($res_times->count() === 0)
                                    @foreach ($days as $key => $day)
                                        <?php $key += 1;
                                        $id = session('restaurant');
                                        ?>
                                        @include('venue.partials.opening-times', [
                                            'id' => $id->id,
                                            'key' => $key,
                                            'name' => $day->name,
                                        ])
                                    @endforeach
                                @else
                                    @foreach ($res_times as $res_time)
                                        @include('venue.partials.opening-times', [
                                            'id' => $res_time->restaurant_id,
                                            'key' => $res_time->day->id,
                                            'name' => $res_time->day->name,
                                            'res_time' => $res_time,
                                        ])
                                    @endforeach
                                @endif
                            </form>
                        </table>
                        <button class="bor-btn w-100 font-26" id="venue_submitBtn" style="display: none" type="submit">Add
                            Open
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
                                <tr>
                                    <th>Total Tables</th>
                                    <td> {{ $res_tables->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Active Tables</th>
                                    <td> {{ $active_tbl }}</td>
                                </tr>
                                <tr>
                                    <th>Occupied Tables</th>
                                    <td>2 </td>
                                </tr>
                                <tr>
                                    <th>Reserved Tables</th>
                                    <td> 0</td>
                                </tr>
                                <tr>
                                    <th>Staffed Tables</th>
                                    <td> 2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-12 h-230">
                <div class="grid colmn-3 h-100 m-h-100">
                    <a href="{{ route('admin.restaurant.index') }}"
                        class="grey-brd-box padbox text-center lable-box"><span>Restaurants</span></a>
                    <a href="{{ route('admin.event.index') }}"
                        class="grey-brd-box padbox text-center lable-box"><span>Events</span></a>
                    <a href="{{ route('admin.customer.index') }}"
                        class="grey-brd-box padbox text-center lable-box"><span>Customers</span></a>
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
