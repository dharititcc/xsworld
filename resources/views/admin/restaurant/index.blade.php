@extends('admin.layouts.mainlayout')
@section('topbar')
@include('admin.restaurant.partials.topbar')
@endsection
@section('content')
<div class="outrbox">
    <div class="sort-by d-flex mb-4">
        <h2 class="yellow">Sort By</h2>
        <div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find a Drink"></div>
    </div>
    <div class="data-table drinks scroll-y h-600 table-responsive">
        <table width="100%" class="restaurant_datatable">
            <thead>
                <tr valign="middle">
                    <th><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span class="checkmark"></span></label></th>
                    <th>Name</th>
                    <th class="type">Address</th>
                    <th class="price">Phone</th>
                    <th class="popularity">Country</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection
@section('pagescript')
@parent
<script src="{{ asset('js/admin/restaurant.js') }}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax: "{!! route('admin.restaurant.table') !!}"
    };

    $(document).ready(function() {
        XS_Admin.Restaurant.init();
    });
</script>
@endsection