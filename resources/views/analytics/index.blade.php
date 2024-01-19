@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('analytics.partials.topbar')
@endsection
@section('content')
<div class="row">
    <div class="col-md-3 no-pd">
        <h2 class="yellow pd-20 gold-border">General Overview</h2>

        <div class="pd-20">
            <div class="item-list overview scroll-y">
                <ul>
                    <li><a href="#" class="active">General Overview</a></li>
                    @if ($categories->count())
                    @foreach ($categories as $category)
                    {{-- {{ dump ($category)}} --}}
                    <li><a href="#">{{ $category->children_parent->name }} - {{ $category->name }}</a></li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <div class="title">
            <h2 class="yellow pd-20 gold-border">Venue Insights</h2>
        </div>
        <div class="padbox">
            <table width="100%" class="opening-time">
                <tbody>
                    @include('restaurant.key_insights')
                </tbody>
            </table>

        </div>

    </div>
    <div class="col-md-9 no-pd gold-border-left">
        <div class="d-flex mb-4 justify-content-between doubl-line gold-border align-items-center">
            <h2 class="yellow pd-20">24th July - 24th Aug 2023</h2>
            <div class="display-range pd-20">
                <input type="text" name="dates" class="bor-btn w-300 text-center">
                {{-- <button id="dateRangeBtn">Select Date Range</button> --}}
                {{-- <a href="#" class="bor-btn" id="dateRangeBtn"><span>24/07/23 - 24/08/23</span></a> --}}
                {{-- <a href="#" class="bor-btn ms-3"><span>Display By</span></a> --}}
            </div>
        </div>
        <div class="graph">
            <!-- <img src="img/demo-graph.jpg" alt=""> -->
            <div id="mygraph">
            </div>
        </div>
        <div class="data-table drinks table-responsive pt-4">
            <table width="100%" class="drink_datatable">
                <thead>
                    <tr valign="middle">
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>No. Of Unit Sold</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('pagescript')
@parent
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="{{asset('js/analytics.js')}}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax: "{!! route('restaurants.drinks.index') !!}",
        currency: "{!! $restaurant->country->symbol !!}",
        graphUrl: "{!! route('filter.analytics') !!}"
    };

    $(document).ready(function() {
        XS.Analytic.init();
    });
</script>
@endsection