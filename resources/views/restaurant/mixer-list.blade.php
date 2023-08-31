@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('restaurant.partials.mixertopbar')
@endsection
@section('content')
<style>
  table.dataTable tbody tr{
    background-color: #0f0e0e !important;
  }
</style>
        <div class="outrbox">
                            <div class="sort-by d-flex mb-4">
                                <h2 class="yellow">Sort By</h2><div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find a Drink"></div>
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
                                        <th class="price">Price</th>
                                        <th class="popularity">Popularity</th>
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
<script type="text/javascript">
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [day, month, year].join('-');
}
  load_data();
function load_data(data = null) {
 // console.log(data);
        var table = $('.drink_datatable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax:{
              url :"{{ route('restaurants.mixers.index') }}",
              data :data,
            },
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
                        return '<div class="prdname green"> '+row.name+' </div><a href="#" class="edit">Edit</a>  <div class="add-date">Added '+formatDate(row.created_at)+'</div>'
                      }
                   },
                   {
                     "data"           : "price", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        var text="";
                        if(row.variations.length > 0){
                          for (let i = 0; i < row.variations.length; i++)
                          {
                                  text += '<label class="price">$'+row.variations[i]['price'] + "</label><br>";
                          }
                          return text
                        }
                        return row.price
                      }
                   },
                   {
                     "data"           : "description", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return row.description
                      }
                   },
                   {
                     "data"           : "status", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        var html;
                        if(row.is_featured == 1){
                          html ='<div class="green"><strong>Featured Drink</strong> </div><div class="green"><strong> In-Stock</strong></div></td>'
                         return html
                      }
                      return '<div class="green"><strong> In-Stock</strong></div></td>'
                    }
                   },
            ]
        });
    }
    function getCategory(id)
    {
      var data = [];
          data['category'] = id;
        if( !data ) {
            $('.drink_datatable').DataTable().destroy();
            load_data();
        }
        else {
            $('.drink_datatable').DataTable().destroy();
            load_data(data);
        }
    }
    $("#search").keyup(function()
    {
      $('.drink_datatable').DataTable().destroy();
      var data = [];
          data['search_main'] = this.value;
      load_data(data);
    });
  </script>
@endsection
