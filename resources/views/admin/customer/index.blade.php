@extends('admin.layouts.mainlayout')
@section('topbar')
@include('admin.customer.partials.topbar')
@endsection
@section('content')
<div class="outrbox">
    <div class="sort-by d-flex mb-4">
        <h2 class="yellow">Sort By</h2>
        <div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find a Drink"></div>
    </div>
    <div class="data-table drinks scroll-y h-600 table-responsive">
        <table width="100%" class="drink_datatable">
            <thead>
                <tr valign="middle">
                    <th><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span class="checkmark"></span></label></th>
                    <th>Name</th>
                    <th class="type">Type</th>
                    <th class="price">Price</th>
                    <th class="popularity">Popularity</th>
                    <th class="my-fav"></th>
                    <th>Status</th>
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
<script src="{{ asset('js/drink.js') }}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax: "{!! route('restaurants.drinks.index') !!}",
        drinkStore: "{!! route('restaurants.drinks.store') !!}",
        drinkUpdate: "{!! route('restaurants.drinks.update', ':ID') !!}",
        drinkGet: "{!! route('restaurants.drinks.show', ':ID') !!}",
        favoriteStatusUpdate: "{!! route('restaurants.favoriteStatusUpdate') !!}",
    };

    $(document).ready(function() {
        XS.Drink.init();
    });
</script>
@endsection