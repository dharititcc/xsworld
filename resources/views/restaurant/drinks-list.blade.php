@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('restaurant.partials.drinktopbar')
@endsection
@section('content')
    <div class="outrbox">
        <h2 class="yellow mb-4">Category Preview Tiles</h2>
        <div class="grid colmn-6 mb-3">
            @foreach ($categories as $category)
                <a href="javascript:void(0);" class="catg-box">
                    <figure><img src="{{ $category->image }}" alt=""></figure>
                    <figcaption>{{ $category->name }} </figcaption>
                </a>
            @endforeach
            </a>
        </div>
        <div class="sort-by d-flex mb-4">
            <h2 class="yellow">Sort By</h2>
            <div class="searchbox"><input type="text" name="search" id="search" class="searchbar"
                    placeholder="Find a Drink"></div>
        </div>
        <div class="filter-box  mb-4">
            <button class="bor-btn category drink_cat active" data-category_id="">All <span class="stock"></span></button>
            @foreach ($categories as $category)
                <button class="bor-btn category drink_cat" data-category_id="{{ $category->id }}">{{ $category->name }} <span
                        class="stock">({{ $category->items->count() }})</span></button>
            @endforeach
        </div>
        <div class="mb-4 table-en-ds">
            <button class="bor-btn" id="disable">Disable Drink</button>
            <button class="bor-btn ms-3" id="enable">Enable Drink</button>
        </div>
        <div class="data-table drinks scroll-y h-600">
            <table width="100%" class="drink_datatable">
                <thead>
                    <tr valign="middle">
                        <th><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span
                                    class="checkmark"></span></label></th>
                        <th>
                            Name
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
<script src="{{asset('js/enableSweetalert.js')}}"></script>
<script src="{{asset('js/disableSweetalert.js')}}"></script>
    <!-- Global popup -->
    <div class="modal fade" id="wd930" tabindex="0" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <form name="adddrink" id="drinkpopup" method="post" >
            @csrf
            <div class="modal-content">
                <div class="modal-header dri-heder">
                    <div class="head-left">
                        <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                                class="icon-left"></i></button>
                        <h2><span class="model_title">  </span> Drink</h2>
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
                                <a href="javascript:void(0);" class="bor-btn product_type active" data-product_type="0">Simple</a>
                                <a href="javascript:void(0);" class="bor-btn product_type" data-product_type="1">Variable</a>
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
                                <input type="text" name="name" id="name" class="form-control vari3" placeholder="Product Name">
                                <input id="product_type" type="hidden" class="product_type" name="is_variable" />
                                <input id="is_featured" type="hidden" class="is_featured" name="is_featured" />
                            </div>
                            <div class="grid colmn-5 cstm-catgory">
                                @foreach ($categories as $category)
                                <label>
                                    <input type="checkbox" name="category_id[]" id="category_id" value="{{$category->id}}">
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
                                    <input type="text" name="ingredients" id="ingredients" class="form-control vari1" placeholder="Ingredients">
                                </div>
                                <div class="form-group full-w-form">
                                    <div class="row">
                                        <div class="col-md-3"><input type="text" name="country_of_origin" id="country_of_origin" class="form-control vari1"
                                                placeholder="Country of Origin"></div>
                                        <div class="col-md-4"><input type="text" name="year_of_production" id="year_of_production" class="form-control vari1"
                                                placeholder="Year of Production"></div>
                                        <div class="col-md-5"><input type="text" name="type_of_drink" id="type_of_drink" class="form-control vari1"
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
                            <div class="add-remove"><a href="javascript:void(0);" class="bor-btn plus remove_variation" type="button"><i
                                        class="icon-plus"></i></a> <a href="javascript:void(0);" class="bor-btn minus"
                                    type="button"><i class="icon-minus"></i></a></div>
                        </div>
                        <div class="variety grid colmn-7">
                            {{-- <div class="grey-brd-box item-box">
                                <button><i class="icon-minus"></i></button>
                                <aside> Glass
                                    <span>($12.50)</span>
                                </aside>
                            </div> --}}
                            <a href="#" class="grey-brd-box item-box add add_variations">
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
                            <input type="text" name="variation_name" class="form-control vari2 variation_field" placeholder="Variation Name">
                        </div>
                        <div class="form-group mb-4">
                            <input type="text" class="form-control vari2 variation_field" name="variation_price" placeholder="Variation Price">
                        </div>
                        {{-- <div class="form-group">
                            <input type="text" class="form-control vari2" placeholder="Condition?">
                        </div> --}}
                    </div>
                    <button class="bor-btn w-100 font-26" type="button" id="add_variation_btn">Save</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('js/drink.js') }}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax: "{!! route('restaurants.drinks.index') !!}",
        drinkStore: "{!! route('restaurants.drinks.store') !!}",
        drinkUpdate: "{!! route('restaurants.drinks.update', ':ID') !!}",
        drinkGet: "{!! route('restaurants.drinks.show', ':ID') !!}",
    };

    $(document).ready(function()
    {
        XS.Drink.init();
    });
</script>
@endsection
