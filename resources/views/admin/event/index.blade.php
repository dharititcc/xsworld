@extends('admin.layouts.mainlayout')
@section('topbar')
@include('admin.event.partials.topbar')
@endsection
@section('content')
<div class="outrbox">
    <div class="sort-by d-flex mb-4">
        <h2 class="yellow">Sort By</h2>
        <div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find an event"></div>
    </div>
    <div class="data-table drinks table-responsive">
        <table width="100%" class="restaurant_event_datatable drink_datatable no_check">
            <thead>
                <tr valign="middle">
                    {{-- <th class="dt-left"><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span class="checkmark"></span></label></th> --}}
                    <th>Name</th>
                    <th class="type">Address</th>
                    <th class="price">Phone</th>
                    <th class="popularity">Country</th>
                    <th class="popularity">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('admin.restaurant.partials.create-restaurant')

@endsection
@section('pagescript')
@parent
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzaUUaqbCwcmb_TMSSnEQ5q0Qr5Sib7i4&libraries=places&callback=Function.prototype" defer></script>
<script src="{{ asset('js/admin/event.js') }}"></script>
<script src="{{ asset('js/admin/restaurant.js') }}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax: "{!! route('admin.event.table') !!}",
        storeRestaurant:    "{!! route('admin.restaurant.store') !!}",
        deleteRestaurant:   "{!! route('admin.restaurant.destroy',':ID') !!}",
        getRestaurant:      "{!! route('admin.restaurant.show',':ID') !!}",
        updateRestaurant:    "{!! route('admin.restaurant.update',':ID') !!}",
    };

    $(document).ready(function() {
        XS_Admin.Event.makeDatatable();
        XS_Admin.Event.searchFilter();
        XS_Admin.Restaurant.init();
    });
</script>
@endsection