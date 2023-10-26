@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('accountManager.partials.topbar')
@endsection
@section('content')
    <!-- Page content-->
    <div class="container-fluid">
        <main>
            <div class="outrbox">
                <div class="d-flex mb-4 justify-content-between doubl-line">
                    <h2 class="yellow">Waiter Accounts</h2>
                    <div class="count-item">Total: {{ $waiters->count() }}</div>
                </div>
                <div class="grid colmn-5">

                    @foreach ($waiters as $waiter)
                        <div class="catg-box overly">
                            {{-- <button><i class="icon-trash"></i></button>
                         --}}
                            <form method="POST" action="{{ route('restaurants.waiter.destroy', $waiter->user->id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i
                                        class="icon-trash"></i></button>
                            </form>

                            {{-- <button onclick="return deleteConform({{ $waiter->id }});"><i
                            class="icon-trash"></i></button> --}}
                            <figure data-type="Edit Waiter"
                                data-parent_id="{{ $waiter->user->id }}" data-parant="{{ $waiter->user->first_name }}"
                                class="waiter_popup_modal">

                                <figcaption><span>{{ $waiter->user->username }}</span></figcaption>
                                {{-- <figcaption><span>{{$waiter->first_name}}</span></figcaption> --}}
                            </figure>
                        </div>
                    @endforeach

                    <a href="javascript:void(0);" class="grey-brd-box waiter_popup_modal waiters add">
                        <i class="icon-plus"> </i>
                    </a>
                </div>
                <div class="gldnline-sepr mb-5 mt-5"></div>
                <div class="d-flex mb-4 justify-content-between doubl-line">
                    <h2 class="yellow">Kitchen Accounts </h2>
                    <div class="count-item">Total: {{ $kitchens->count() }}</div>
                </div>
                <div class="grid colmn-5">

                    @foreach ($kitchens as $kitchen)
                        <div class="catg-box overly">
                            {{-- <button><i class="icon-trash"></i></button>
                            --}}
                            <form method="POST" action="{{ route('restaurants.kitchen.destroy', $kitchen->user->id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i
                                        class="icon-trash"></i></button>
                            </form>

                            {{-- <button onclick="return deleteConform({{ $kitchen->id }});"><i
                                class="icon-trash"></i></button> --}}
                            <figure data-type="Edit kitchen"
                                data-parent_id="{{ $kitchen->user->id }}" data-parant="{{ $kitchen->user->first_name }}"
                                class="kitchen_popup_modal">

                                <figcaption><span>{{ $kitchen->user->username }}</span></figcaption>
                                {{-- <figcaption><span>{{$kitchen->first_name}}</span></figcaption> --}}
                            </figure>
                        </div>
                    @endforeach


                    <a href="javascript:void(0);" class="catg-box add overly kitchen kitchen_popup_modal">
                        <figure><i class="icon-plus"> </i></figure>
                        <!--<input type="text" required="" autofocus=""> -->
                    </a>
                </div>
                <div class="gldnline-sepr mb-5 mt-5"></div>

                <div class="d-flex mb-4 justify-content-between doubl-line">
                    <h2 class="yellow">Bar Pick Zones Accounts</h2>
                    <div class="count-item">Total: {{ $barpickzones->count() }}</div>
                </div>
                <div class="grid colmn-5">

                    @foreach ($barpickzones as $barpickzone)
                        <div class="catg-box overly">
                            {{-- <button><i class="icon-trash"></i></button>
                            --}}
                            <form method="POST" action="{{ route('restaurants.barpickzone.destroy', $barpickzone->user->id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                                <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i
                                        class="icon-trash"></i></button>
                            </form>

                            {{-- <button onclick="return deleteConform({{ $barpickzone->user->id }});"><i
                                class="icon-trash"></i></button> --}}
                            <figure onclick="getBarpickzone({{ $barpickzone->user->id }})" data-type="Edit Barpickzone"
                                data-parent_id="{{ $barpickzone->user->id }}" data-parant="{{ $barpickzone->user->first_name }}"
                                class="barpickzone_popup_modal">

                                <figcaption><span>{{ $barpickzone->user->username }}</span></figcaption>
                                {{-- <figcaption><span>{{$barpickzone->user->first_name}}</span></figcaption> --}}
                            </figure>
                        </div>
                    @endforeach




                    <a href="javascript:void(0);" class="catg-box add overly barzone barpickzone_popup_modal">
                        <figure><i class="icon-plus"> </i></figure>

                        <!--<input type="text" required="" autofocus=""> -->
                    </a>
                </div>
            </div>
        </main>
    </div>
    </div>
    </div>

    <!-- Global popup -->
    <div class="modal fade" id="waiterModal" data-crudetype="1" tabindex="-1" aria-labelledby="waiterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="waiter_model_title"> </span>Waiter</h2>
                </div>
                <div class="modal-body">
                    <form name="addwaiterform" id="addwaiterform" method="post">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input id="user_id" type="hidden" class="user_id" name="user_id" />
                                <input type="text" name="waiter_id" id="waiter_id" class="form-control vari2"
                                    placeholder="Waiter ID" autocomplete="off">
                                <span id="Errorid"></span>
                            </div>
                            <div class="form-group mb-4">
                                <input type="text" name="first_name" id="waiter_name" class="form-control vari2"
                                    placeholder="Waiter Name" autocomplete="off">
                                <span id="Errorname"></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control vari2"
                                    placeholder="Password" autocomplete="off">
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
    <div class="modal fade" id="kitchenModal" data-crudetype="1" tabindex="-1" aria-labelledby="kitchenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="kitchen_model_title"> </span>Kitchen</h2>
                </div>
                <div class="modal-body">
                    <form name="addkitchenform" id="addkitchenform" method="post">
                        @csrf
                        <div style="min-height: 200px;">
                            <div class="form-group mb-4">
                                <input id="user_id" type="hidden" class="user_id" name="user_id" />
                                <input type="text" name="kitchen_id" id="kitchen_id" class="form-control vari2"
                                    placeholder="Login ID" autocomplete="off">
                                <span id="Errorid"></span>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" id="password" class="form-control vari2"
                                    placeholder="Password" autocomplete="off">
                                <span id="Errorpassword"></span>
                            </div>
                            {{-- <div class="form-group">
                                <label class="white-lable d-block text-center">Pickup Location</label>
                                <select name="kitchen_point[]" id="kitchen_point" class="form-control vari2" multiple>
                                    <option>---</option>
                                    @foreach ($kitchen_pickpoints as $pickup_point)
                                        <option value="{{ $pickup_point->id }}">{{ $pickup_point->name }}</option>
                                    @endforeach
                                </select>

                            </div> --}}
                        </div>
                        <button class="bor-btn w-100 font-26" id="kitchen_submitBtn" type="submit">Add Kitchen</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Global popup -->
    <!-- Global popup -->
    <div class="modal fade" id="addBarModal" data-crudetype="1" tabindex="-1" aria-labelledby="addBarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="barzone_model_title"> </span>Bar</h2>
                </div>
                <div class="modal-body">
                    <form name="addbarpickform" id="addbarpickform" method="post">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input id="user_id" type="hidden" class="user_id" name="user_id" />
                                <input type="text" name="barpick_id" id="barpick_id" class="form-control vari2"
                                    placeholder="Login ID" autocomplete="off">
                                <span id="Errorid"></span>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" id="password" class="form-control vari2"
                                    placeholder="Password" autocomplete="off">
                                <span id="Errorpassword"></span>
                            </div>
                            <div class="form-group">
                                <label class="white-lable d-block text-center">Pickup Location</label>
                                <select name="pickup_points" id="pickup_points" class="form-control vari2"></select>

                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26" id="barpickzone_submitBtn" type="submit">Add Bar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Global popup -->
@endsection

@section('pagescript')
<script src="{{ asset('js/account.js') }}"></script>
<script>
    var moduleConfig = {
        waiterStore: "{!! route('restaurants.waiter.store') !!}",
        waiterUpdate: "{!! route('restaurants.waiter.update', ':ID') !!}",
        waiterGet: "{!! route('restaurants.waiter.show', ':ID') !!}",

        kitchenStore: "{!! route('restaurants.kitchen.store') !!}",
        kitchenUpdate: "{!! route('restaurants.kitchen.update', ':ID') !!}",
        kitchenGet: "{!! route('restaurants.kitchen.show', ':ID') !!}",

        barpickStore: "{!! route('restaurants.barpickzone.store') !!}",
        barpickUpdate: "{!! route('restaurants.barpickzone.update', ':ID') !!}",
        barpickGet: "{!! route('restaurants.barpickzone.show', ':ID') !!}",
        availableBarPickupZones: '{!! json_encode($pickup_points) !!}'
    };

    $(document).ready(function()
    {
        XS.Account.init();
    });
</script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
@endsection
