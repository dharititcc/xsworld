@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('accountManager.partials.topbar')
@endsection
@section('content')
<!-- Page content-->
<div class="container-fluid">
    <main>
        <div class="outrbox"> 
            <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Waiter Accounts</h2> <div class="count-item">Total: {{$waiters->count()}}</div></div>
            <div class="grid colmn-5">

                @foreach ($waiters as $waiter)
                    <div class="catg-box overly">
                        {{-- <button><i class="icon-trash"></i></button>
                         --}}
                        <form method="POST" action="{{ route('restaurants.waiter.destroy', $waiter->id) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></button>
                        </form>

                        {{-- <button onclick="return deleteConform({{ $waiter->id }});"><i
                            class="icon-trash"></i></button> --}}
                        <figure onclick="getWaiter({{$waiter->id}})" data-type="Edit Waiter" data-parent_id="{{$waiter->id}}" data-parant="{{$waiter->first_name}}" class="waiter_modal">
                            
                            <figcaption><span>{{$waiter->username}}</span></figcaption>
                            {{-- <figcaption><span>{{$waiter->first_name}}</span></figcaption> --}}
                        </figure>
                    </div>
                @endforeach
               
                 <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#waiterModal" class="grey-brd-box waiters add">
                    <i class="icon-plus"> </i>
                </a>
            </div>
            <div class="gldnline-sepr mb-5 mt-5"></div>
            <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Kitchen Accounts </h2> <div class="count-item">Total: {{$kitchens->count()}}</div></div>
                <div class="grid colmn-5">
                    
                    @foreach ($kitchens as $kitchen)
                        <div class="catg-box overly">
                            {{-- <button><i class="icon-trash"></i></button>
                            --}}
                            <form method="POST" action="{{ route('restaurants.kitchen.destroy', $kitchen->id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></button>
                            </form>

                            {{-- <button onclick="return deleteConform({{ $kitchen->id }});"><i
                                class="icon-trash"></i></button> --}}
                            <figure onclick="getkitchen({{$kitchen->id}})" data-type="Edit kitchen" data-parent_id="{{$kitchen->id}}" data-parant="{{$kitchen->first_name}}" class="kitchen_modal">
                                
                                <figcaption><span>{{$kitchen->username}}</span></figcaption>
                                {{-- <figcaption><span>{{$kitchen->first_name}}</span></figcaption> --}}
                            </figure>
                        </div>
                    @endforeach
                
                    
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addkitchen" class="catg-box add overly kitchen"><figure><i class="icon-plus"> </i></figure><!--<input type="text" required="" autofocus=""> --></a>
                </div>
                <div class="gldnline-sepr mb-5 mt-5"></div>
            
                <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Bar Pick Zones Accounts</h2> <div class="count-item">Total: {{$barpickzones->count()}}</div></div>
                <div class="grid colmn-5">

                    @foreach ($barpickzones as $barpickzone)
                        <div class="catg-box overly">
                            {{-- <button><i class="icon-trash"></i></button>
                            --}}
                            <form method="POST" action="{{ route('restaurants.barpickzone.destroy', $barpickzone->id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></button>
                            </form>

                            {{-- <button onclick="return deleteConform({{ $barpickzone->id }});"><i
                                class="icon-trash"></i></button> --}}
                            <figure onclick="getBarpickzone({{$barpickzone->id}})" data-type="Edit Barpickzone" data-parent_id="{{$barpickzone->id}}" data-parant="{{$barpickzone->first_name}}" class="barpickzone_modal">
                                
                                <figcaption><span>{{$barpickzone->username}}</span></figcaption>
                                {{-- <figcaption><span>{{$barpickzone->first_name}}</span></figcaption> --}}
                            </figure>
                        </div>
                    @endforeach
                
               
                
                
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addBarzone" class="catg-box add overly"><figure><i class="icon-plus"> </i></figure>
               
                <!--<input type="text" required="" autofocus=""> -->
            </a>
            </div>
        </div>
    </main>
</div>
</div>
</div>

<!-- Global popup -->
<div class="modal fade" id="waiterModal" data-crudetype="1" tabindex="-1" aria-labelledby="waiterModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header justify-content-start ">
    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
    <h2><span class="waiter_model_title"> </span>Waiter</h2>
</div>
<div class="modal-body">
    <form name="addwaiterform" id="addwaiterform" method="post" action="javascript:void(0)">
        @csrf
        <div style="min-height: 300px;">
            <div class="form-group mb-4">
                <input id="user_id" type="hidden" class="user_id" name="user_id" />
                <input type="text" name="waiter_id" id="waiter_id" class="form-control vari2" placeholder="Waiter ID">
                <span id="Errorid"></span>
            </div>
            <div class="form-group mb-4">
                <input type="text" name="first_name" id="waiter_name" class="form-control vari2" placeholder="Waiter Name">
                <span id="Errorname"></span>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control vari2" placeholder="Password">
                <span id="Errorpassword"></span>
            </div>
        </div>
      <button class="bor-btn w-100 font-26" id="waiter_submitBtn" type="submit">Add Waiter</button>
    </form>
</div>

</div>
</div>
</div>
<!-- Global popup --> 
<!-- Global popup -->
<div class="modal fade" id="addkitchen" data-crudetype="1" tabindex="-1" aria-labelledby="addkitchenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-start ">
                <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
                <h2><span class="kitchen_model_title"> </span>Kitchen</h2>
            </div>
            <div class="modal-body">
                <form name="addkitchenform" id="addkitchenform" method="post" action="javascript:void(0)">
                    @csrf
                    <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input id="user_id" type="hidden" class="user_id" name="user_id" />
                                <input type="text" name="kitchen_id" id="kitchen_id" class="form-control vari2" placeholder="Login ID">
                                <span id="Errorid"></span>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" id="password" class="form-control vari2" placeholder="Password">
                                <span id="Errorpassword"></span>
                            </div>
                            <div class="form-group">
                                <label class="white-lable d-block text-center">Pickup Location</label>
                                <select name="kitchen_point[]" id="kitchen_point" class="form-control vari2" multiple>
                                    <option>---</option>
                                    @foreach ($pickup_points as $pickup_point)
                                        <option value="{{$pickup_point->id}}">{{$pickup_point->name}}</option>
                                    @endforeach
                                    {{-- <option>Geelong</option>
                                    <option>Clayton</option>
                                    <option>Hamptorn</option>
                                    <option>Mentone</option> --}}
                                </select>
                                
                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26" id="kitchen_submitBtn" type="submit">Add Kitchen</button>
                    </div>
                </form>
            </div>
        </div>
</div>
<!-- Global popup --> 
<!-- Global popup -->
<div class="modal fade" id="addBarzone" data-crudetype="1" tabindex="-1" aria-labelledby="addBarzoneLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-start ">
                <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
                <h2><span class="barzone_model_title"> </span>Bar</h2>
            </div>
            <div class="modal-body">
                <form name="addbarpickform" id="addbarpickform" method="post" action="javascript:void(0)">
                    @csrf
                    <div style="min-height: 300px;">
                        <div class="form-group mb-4">
                            <input id="user_id" type="hidden" class="user_id" name="user_id" />
                            <input type="text" name="barpick_id" id="barpick_id" class="form-control vari2" placeholder="Login ID">
                            <span id="Errorid"></span>
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" name="password" id="password" class="form-control vari2" placeholder="Password">
                            <span id="Errorpassword"></span>
                        </div>
                        <div class="form-group">
                            <label class="white-lable d-block text-center">Pickup Location</label>
                            <select name="pickup_points" id="pickup_points" class="form-control vari2">
                                @foreach ($pickup_points as $pickup_point)
                                    <option value="{{$pickup_point->id}}">{{$pickup_point->name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                        <button class="bor-btn w-100 font-26" id="barpickzone_submitBtn"  type="submit">Add Bar</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Global popup -->
@endsection

@section('pagescript')
<script src="{{asset('js/accountmanager/waiter.js')}}"></script>
<script src="{{asset('js/accountmanager/addBarzone.js')}}"></script>
<script src="{{asset('js/accountmanager/addkitchen.js')}}"></script>
<script src="{{asset('js/sweetalert.js')}}"></script>
<script>
    var routeStore = '{{ route("restaurants.waiter.store") }}';
    var routeUpdate = "{{ route('restaurants.waiter.update',':ID') }}";
    var routeGet = "{{ route('restaurants.waiter.show',':ID') }}";

    var barpickStore = '{{route("restaurants.barpickzone.store")}}';
    var barpickUpdate = "{{route('restaurants.barpickzone.update',':ID')}}";
    var barpickGet = "{{route('restaurants.barpickzone.show',':ID')}}";

    var kitchenStore = '{{route("restaurants.kitchen.store")}}';
    var kitchenUpdate = "{{route('restaurants.kitchen.update',':ID')}}";
    var kitchenGet = "{{route('restaurants.kitchen.show',':ID')}}";
</script>
@endsection