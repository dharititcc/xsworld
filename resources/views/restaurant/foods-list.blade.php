@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('restaurant.partials.foodtopbar')
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
                                <button class="bor-btn category" onclick="getCategory({{$category->id}})">{{$category->name}} <span class="stock">({{count($category->items)}})</span></button>
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
                        @endsection
@section('pagescript')
<script src="{{asset('js/enableSweetalert.js')}}"></script>
<script src="{{asset('js/disableSweetalert.js')}}"></script>
@parent
    <!-- Global popup -->
    <div class="modal fade" id="wd930" tabindex="0" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <form name="adddrink" id="drinkpopup" method="post" action="javascript:void(0)">
          <div class="modal-content">
              <div class="modal-header dri-heder">
                  <div class="head-left">
                      <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                              class="icon-left"></i></button>
                      <h2>Manually Add Food</h2>
                  </div>
                  <div class="head-right">
                      <a href="javascript:void(0)" data-is_favorite="0" class="favorite is_favorite null"></a>

                      <button class="bor-btn" id="submitBtn" type="submit">Save</button>
                  </div>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-4">
                          <div class="tab-btn">
                              <a href="#" class="bor-btn product_type active" data-product_type="0">Simple</a>
                              <a href="#" class="bor-btn product_type" data-product_type="1">Variable</a>
                          </div>
                          {{-- <div class="grey-brd-box d-flex featured-img">
                              <a href="#" class="add-edit"><i class="icon-plus"></i></a>
                              <span class="img-text">Product Image</span>
                          </div> --}}
                          <div class="form-group grey-brd-box d-flex featured-img">
                              <input id="upload" type="file" class="files" name="image" hidden/>
                              <label for="upload"><span> Product Image</span> <i
                                      class="icon-plus"></i></label>
                          </div>
                          <input type="text" name="price" id="price" class="form-control vari2 mb-3" placeholder="Enter Price">

                      </div>
                      <div class="col-md-8">
                          <div class="form-group mb-4">
                              <input type="text" name="name" class="form-control vari3" placeholder="Product Name">
                          </div>
                          <div class="grid colmn-5 cstm-catgory">
                              @foreach ($categories as $category)
                              <label>
                                  <input type="checkbox" name="category_id" value="{{$category->id}}">
                                  <div class="category">
                                      <div class="name">{{$category->name}}
                                          <span>{{$category->items->count()}} Total</span>
                                      </div>
                                  </div>
                              </label>
                              @endforeach
                          </div>
                          <div class="extr-info">
                              <div class="head">
                                  <h2 class="yellow">Additional Information</h2> <span class="optional-info"></span>
                              </div>
                              <div class="form-group">
                                  <input type="text" name="ingredients" class="form-control vari1" placeholder="Ingredients">
                              </div>
                              <div class="form-group full-w-form">
                                  <div class="row">
                                      <div class="col-md-3"><input type="text" name="country_of_origin" class="form-control vari1"
                                              placeholder="Country of Origin"></div>
                                      <div class="col-md-4"><input type="text" name="year_of_production" class="form-control vari1"
                                              placeholder="Year of Production"></div>
                                      <div class="col-md-5"><input type="text" name="type_of_drink" class="form-control vari1"
                                              placeholder="Type of Drink (Spirit/Wines)"></div>
                                  </div>
                              </div>

                              <textarea id="description" name="description"
                                  placeholder="Product descriptor goes into this box it can be brief or it can be long, this is to be displayed when the user clicks on the specific beverage."
                                  class="prd-desc"></textarea>


                          </div>
                      </div>
                  </div>

                  <div class="prd-variation" style="display: none">
                      <div class="head">
                          <h2 class="yellow">Drink Variations</h2>
                          <div class="add-remove"><a href="#" class="bor-btn plus" type="button"><i
                                      class="icon-plus"></i></a> <a href="#" class="bor-btn minus"
                                  type="button"><i class="icon-minus"></i></a></div>
                      </div>
                      <div class="variety grid colmn-7">
                          {{-- <div class="grey-brd-box item-box">
                              <button><i class="icon-minus"></i></button>
                              <aside> Glass
                                  <span>($12.50)</span>
                              </aside>
                          </div> --}}
                          <a href="#" class="grey-brd-box item-box add" data-bs-toggle="modal"
                              data-bs-target="#addDrink">
                              <aside>+ Add Variation </aside>
                          </a>
                      </div>
                  </div>
              </div>

          </div>
        </form>
      </div>
  </div>
  <!-- Global popup -->
  <div class="modal fade" id="addDrink" tabindex="0" data-crudetype="1" aria-labelledby="exampleModalLabel" aria-hidden="false">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header justify-content-start ">
                  <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                          class="icon-left"></i></button>
                  <h2>Add Drink Variation</h2>
              </div>
              <div class="modal-body">
                  <div style="min-height: 300px;">
                      <div class="form-group mb-4">
                          <input type="text" class="form-control vari2" placeholder="Variation Name">
                      </div>
                      <div class="form-group mb-4">
                          <input type="text" class="form-control vari2" placeholder="Variation Price">
                      </div>
                      <div class="form-group">
                          <input type="text" class="form-control vari2" placeholder="Condition?">
                      </div>
                  </div>
                  <button class="bor-btn w-100 font-26" type="button">Save</button>
              </div>
          </div>
      </div>
  </div>
<script type="text/javascript">

var moduleConfig = {
            'addFood'   : "{!! route('restaurants.foods.store') !!}",
            'getFood'   : "{!! route('restaurants.foods.show', ':ID') !!}",
            'updateFood': "{!! route('restaurants.foods.update', ':ID') !!}",
        };
        if (window.File && window.FileList && window.FileReader) {
                $(".files").on("change", function(e) {
                    var clickedButton = this,
                        files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i],
                            fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target,
                                thumbnail = `
                                    <div class="pip">
                                        <img class="imageThumb" src="${e.target.result}" title="${file.name}" />
                                        <i class="icon-trash remove"></i>
                                    </div>
                                `;
                            $(thumbnail).insertAfter(clickedButton);
                            $(".remove").click(function() {
                                $(this).parent(".pip").remove();
                            });
                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            } else {
                alert("Your browser doesn't support to File API")
            }
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
              url :"{{ route('restaurants.foods.index') }}",
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

    // $(function(){
    //   $('#enable').click(function(e) {
    //       e.preventDefault();
    //       $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
    //         var data = [];
    //         var i= 0;
    //         data['enable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
    //         $('.drink_datatable').DataTable().destroy();
    //         load_data(data);
    //           //console.log(data);
    //       });
    //     });
    // });
    // $(function(){
    //   $('#disable').click(function(e) {
    //       e.preventDefault();
    //       $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
    //         var data = [];
    //         var i= 0;
    //         data['disable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
    //         $('.drink_datatable').DataTable().destroy();
    //         load_data(data);
    //           //console.log(data);
    //       });
    //     });
    // });

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
  $('.product_type').click(function(e)
        {
            var product_type = $(this).data('product_type');
            $('.product_type').removeClass('active');
            if(product_type === 1){
                document.getElementById("price").style.visibility='hidden';
                $('.prd-variation').removeAttr("style");
            }else{
                document.getElementById("price").style.visibility='visible';
                $(".prd-variation").css("display", "none");
            }
            $(this).addClass('active');
        });
        $('.is_favorite').click(function(e)
        {
            var is_favorite = $(this).data('is_favorite');
            if(is_favorite === 0){
                $('.is_favorite').removeClass('null');
                $(this).data('is_favorite',1);
            }else{
                $(this).data('is_favorite',0);
                $('.is_favorite').addClass('null');
            }
        });
    $('#submitBtn').click(function(e)
    { //alert(1);
        $("#drinkpopup").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    category_id : {
                        required: true,
                    },
                    price: {
                        required: true,
                    },
                    image: {
                        required: true,
                    },
                    ingredients: {
                        required: true,
                    },
                    country_of_origin: {
                        required: true,
                    },
                    type_of_drink: {
                        required: true,
                    },
                    year_of_production: {
                        required: true,
                    },
                    message: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    image: {
                        required: "Please enter files", //accept: 'Not an image!'
                    }
                },
                submitHandler: function(form) {
                   // console.log('new');
                    formsubmit(form);
                }
            });

    });
    function formsubmit(from){
        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#submitBtn').html('Please Wait...');
                $("#submitBtn").attr("disabled", true);
                var route = "";
                var crudetype = $('#addDrink').data('crudetype'); //getter
                var data                = new FormData(),
                    name                = $("input[name=name]").val(),
                    description         = $("#description").val(),
                    price               = $("input[name=price]").val(),
                    ingredients         = $("input[name=ingredients]").val(),
                    country_of_origin   = $("input[name=country_of_origin]").val(),
                    type_of_drink       = $("input[name=type_of_drink]").val(),
                    year_of_production  = $("input[name=year_of_production]").val(),
                    is_variable         = $(".product_type.active").data('product_type'),
                    is_featured         = $(".is_favorite").data('is_favorite'),
                    photo               = $('#upload').prop('files')[0];
                    var category = [];
                    $.each($("input[name='category_id']:checked"), function(i) {
                        category[i] = $(this).val();
                    });
                    //console.log(is_variable);return false;

                data.append('name', name);
                data.append('category_id', category);
                data.append('description', description);
                data.append('price', price);
                data.append('ingredients', ingredients);
                data.append('country_of_origin', country_of_origin);
                data.append('type_of_drink', type_of_drink);
                data.append('year_of_production', year_of_production);
                data.append('is_variable', is_variable);
                data.append('is_featured', is_featured);
                data.append('photo', photo);
                //console.log(crudetype);
                if (crudetype === 1) {
                    route = moduleConfig.addFood;
                } else {
                    route = moduleConfig.updateFood.replace(':ID', category_id),
                        data.append('_method', 'PUT');
                }
                console.log(route);
                $.ajax({
                    url: route,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                        alert('Ajax form has been submitted successfully');
                        document.getElementById("categorypopup").reset();
                        location.reload(true);
                    }
                });
    }
  </script>
@endsection
