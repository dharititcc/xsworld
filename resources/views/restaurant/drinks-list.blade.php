@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('restaurant.partials.drinktopbar')
@endsection
@section('content')
<div class="outrbox">
                            <h2 class="yellow mb-4">Category Preview Tiles</h2>
                            <div class="grid colmn-6 mb-3">
                                <a href="#" class="catg-box"><figure><img src="https://www.foodandwine.com/thmb/9oNf0Ece0Jv1PeFalXXO1A0PDzo=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Liquor-vs-Liqueur-vs-Spirit-FT-BLOG1122-88b33026a97d4554b0bf811c0ee0455b.jpg" alt=""></figure>
                                <figcaption>Spirit Feature</figcaption></a>
                                <a href="#" class="catg-box"><figure><img src="https://www.thehamperemporium.com.au/assets/webshop/cms/26/7726.jpg?1620090808" alt=""></figure>
                                <figcaption>Champagne Feature</figcaption></a>
                                <a href="#" class="catg-box"><figure><img src="https://images.immediate.co.uk/production/volatile/sites/30/2021/06/wine-tasting-hub-89ce511.jpg" alt=""></figure>
                                <figcaption>Wine Feature</figcaption></a>
                                <a href="#" class="catg-box"><figure><img src="https://i0.wp.com/post.healthline.com/wp-content/uploads/2020/08/beer-bar-1296x728-header.jpg?w=1155&h=1528" alt=""></figure>
                                <figcaption>Beer Feature</figcaption></a>
                                <a href="#" class="catg-box"><figure><img src="https://assets.gqindia.com/photos/609a8e5b2c7bfc93e2031ba2/1:1/w_1080,h_1080,c_limit/World%20Cocktail%20Day.jpeg" alt=""></figure>
                                <figcaption>Cocktail Feature</figcaption></a>
                                <a href="#" class="catg-box add"><figure><i class="icon-plus"> </i></figure>
                                <figcaption>Non-Alc Feature</figcaption>
                                <!--<input type="text" required="" autofocus=""> -->
                            </a>
                            </div>
                            <div class="sort-by d-flex mb-4">
                                <h2 class="yellow">Sort By</h2><div class="searchbox"><input type="text" class="searchbar" placeholder="Find a Drink"></div>
                            </div>
                            <div class="filter-box  mb-4">
                                <button class="bor-btn">Wine <span class="stock">(32)</span></button>
                                <button class="bor-btn">Beer <span class="stock">(14)</span></button>
                                <button class="bor-btn">Champagne <span class="stock">(10)</span></button>
                                <button class="bor-btn">Spirit <span class="stock">(43)</span></button>
                                <button class="bor-btn">Cocktail <span class="stock">(8)</span></button>
                                <button class="bor-btn">Non-Alc <span class="stock">(7)</span></button>
                            </div>
                            <div class="mb-4">
                            <button class="bor-btn">Disable Drink</button>
                                            <button class="bor-btn ms-3">Enable Drink</button>
                                            </div>
                            <div class="data-table drinks scroll-y h-600">
                                <table width="100%" class="drink_datatable">
                                    <thead>
                                    <tr valign="middle">
                                        <th><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></th>
                                        <th>
                                            Name
                                            <a href="#" class="sort-icon ms-1"><i class="icon-sort"></i></a>
                                        </th>
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
                        {{-- // 
                        // <td>&nbsp;</td>
                        // <td><div class="prd-type">Wine</div> <label> Glass</label><label> Bottle</label></td>
                        // <td><label>&nbsp;</label><label class="price">$18.50</label><label class="price"> $65.50</label></td>
                        // <td>1231 Units Sold This Month <strong style="display: block;"> Very High</strong></td>
                        // <td class="text-center"><a href="javascript:void(0)" class="favorite"></a></td>
                        // <td><div class="green"><strong>Featured Drink</strong> </div> <div class="green"><strong> In-Stock</strong></div></td>
                         --}}
                        @endsection
@section('pagescript')
@parent
<script type="text/javascript">
    $(function () {
        var table = $('.drink_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('restaurants.drinks.index') }}",
            columns: [
                   {
                     "data"           : "id", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<label class="cst-check"><input name="id" type="checkbox" value="'+row.id+'"><span class="checkmark"></span></label>'
                      }
                   },
                   {
                     "data"           : "name", // can be null or undefined ->type
                     "defaultContent" : "",
                      render:function(data, type, row){
                        return '<div class="prdname green"> '+row.name+' </div><a href="#" class="edit">Edit</a>  <div class="add-date">Added '+row.created_at+'</div>'
                      }
                   },
                   {
                     "data"           : "type", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<div class="prd-type">Wine</div> <label> Glass</label><label> Bottle</label>'
                      }
                   },
                   {
                     "data"           : "price", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<label>&nbsp;</label><label class="price">$'+row.price+'</label><label class="price"> $'+row.price+'</label>'
                      }
                   },
                   {
                     "data"           : "description", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return 'test'
                      }
                   },
                   {
                     "data"           : "favorite", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<a href="javascript:void(0)" class="favorite"></a>'
                      }
                   },
                   {
                     "data"           : "status", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<div class="green"><strong>Featured Drink</strong> </div> <div class="green"><strong> In-Stock</strong></div></td>'
                      }
                   },
            ]
        });
    });
    </script>
@endsection
