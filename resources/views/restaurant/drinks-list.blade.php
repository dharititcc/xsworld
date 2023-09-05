@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('restaurant.partials.drinktopbar')
@endsection
@section('content')
<style>
  table.dataTable tbody tr{
    background-color: #0f0e0e !important;
  }
</style>
<div class="outrbox">
                            <h2 class="yellow mb-4">Category Preview Tiles</h2>
                            <div class="grid colmn-6 mb-3">
                            @foreach ($categories as $category)
                              <a href="#" class="catg-box"><figure><img src="{{$category->image}}" alt=""></figure>
                                <figcaption>{{$category->name}} </figcaption></a>
                            @endforeach
                            </a>
                            </div>
                            <div class="sort-by d-flex mb-4">
                                <h2 class="yellow">Sort By</h2><div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find a Drink"></div>
                            </div>
                            <div class="filter-box  mb-4">
                              <button class="bor-btn category" onclick="getCategory(null)">All <span class="stock"></span></button>
                              @foreach ($categories as $category)
                                <button class="bor-btn category" onclick="getCategory({{$category->id}})">{{$category->name}} <span class="stock">({{$category->items->count()}})</span></button>
                                @endforeach
                            </div>
                            <div class="mb-4">
                              <button class="bor-btn" id="disable" >Disable Drink</button>
                              <button class="bor-btn ms-3" id="enable" >Enable Drink</button>
                            </div>
                            <div class="data-table drinks scroll-y h-600">
                                <table width="100%" class="drink_datatable">
                                    <thead>
                                    <tr valign="middle">
                                        <th><label class="cst-check"><input type="checkbox" id="allcheck"  value=""><span class="checkmark"></span></label></th>
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
              url :"{{ route('restaurants.drinks.index') }}",
              data :data,
            },
            columns: [
                   {
                     "data"           : "id", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        return '<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="'+row.id+'"><span class="checkmark"></span></label>'
                      }
                   },
                   {
                     "data"           : "name", // can be null or undefined ->type
                     "defaultContent" : "",
                      render:function(data, type, row){
                        var color = (row.is_available == 1) ? "green":"red";
                        return '<div class="prdname '+color+'"> '+row.name+' </div><a href="#" class="edit">Edit</a>  <div class="add-date">Added '+formatDate(row.created_at)+'</div>'
                      }
                   },
                   {
                     "data"           : "type", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        var text="";
                        if(row.variations.length > 0){
                          for (let i = 0; i < row.variations.length; i++)
                          {
                                  text += '<label class="">'+row.variations[i]['name'] + "</label><br>";
                          }
                          return text
                        }
                        return ""
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
                     "data"           : "favorite", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        if(row.is_featured == 1){
                          return '<a href="javascript:void(0)" class="favorite"></a>'
                        }
                        return '<a href="javascript:void(0)" class="favorite null"></a>'
                      }
                   },
                   {
                     "data"           : "status", // can be null or undefined
                     "defaultContent" : "",
                     "bSortable"      : false,
                      render:function(data, type, row){
                        var html='';
                        if(row.is_featured == 1)
                        {
                          html +='<div class="green"><strong>Featured Drink</strong> </div>'
                        }
                        if(row.is_available == 1)
                        {
                          html +='<div class="green"><strong> In-Stock</strong></div>'
                        }else{
                          html +='<div class="red"><strong>  Out Of Stock</strong></div>'
                        }
                      return html
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
    $(function(){
      $('#enable').click(function(e) {
          e.preventDefault();
          $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
            var data = [];
            var i= 0;
            data['enable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
            $('.drink_datatable').DataTable().destroy();
            load_data(data);
              //console.log(data);
          });
        });
    });
    $(function(){
      $('#disable').click(function(e) {
          e.preventDefault();
          $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
            var data = [];
            var i= 0;
            data['disable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
            $('.drink_datatable').DataTable().destroy();
            load_data(data);
              //console.log(data);
          });
        });
    });

    $(document).ready(function(){
      $('.checkboxitem').click(function() {
        alert(1);
          if ($(this).is(':checked')) {
              $('#disable').removeAttr('disabled');
              $('#enable').removeAttr('disabled');
          } else {
              $('#id_of_your_button').attr('disabled');
          }
      });

      $('#allcheck').click(function(e) {
          e.preventDefault();
        alert();
          $('input[name="id"]').attr('checked','checked');
        // $(this).val('uncheck all');
      },function(){
          $('input[name="id"]').removeAttr('checked');
          //$(this).val('check all');
      })
  });
 

  </script>
@endsection
