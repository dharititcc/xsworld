@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('venue.partials.topbar')
@endsection
@section('content')

    </nav>
    <!-- Page content-->
    <div class="container-fluid">
        <main>
            <div class="outrbox">
                <div class="row">
                    <div class="col-md-4">
                        <div class="grey-brd-box">
                            <div class="title">
                                <h2>Opening Times</h2> <a href="javascript:void(0);" class="edit venue_popup_modal ">EDIT</a>
                            </div>
                            <div class="padbox">
                                <table class="opening-time">
                                    <form name="addtimerform" id="addtimerform" method="post">
                                        @if($res_times->count() === 0)
                                            @foreach ($days as $key => $day)
                                            <?php $key += 1; 
                                                $id = session('restaurant');;
                                            ?>
                                                <tr>
                                                    <th>{{$day->name}}</th>
                                                    <td>
                                                        <input type="hidden" name="res_id" id="res_id" data-id = "{{$id->id}}">
                                                        <input class="start_time" style="display: none" data-day_id ="{{$key}}" value="{{$key}}" name="start_time[{{$key}}]" type="time" value=""
                                                            placeholder="Start Time"><input class="close_time"
                                                            placeholder="Close TIme" style="display: none"  name="end_time[{{$key}}]" type="time" value="">
                                                        <label for="time" class="times">-</label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach ($res_times as $res_time)
                                                <tr>
                                                    <th>{{$res_time->day->name}}</th>
                                                    <td>
                                                        <input type="hidden" name="res_id" id="res_id" data-id = "{{$res_time->restaurant_id}}">
                                                        <input class="start_time" style="display: none" value="{{$res_time->start_time}}" name="start_time[{{$res_time->day->id}}]" type="time"
                                                            placeholder="Start Time"><input class="close_time"
                                                            placeholder="Close TIme" style="display: none"  name="end_time[{{$res_time->day->id}}]" type="time" value="{{$res_time->close_time}}">
                                                        <label for="time" class="times">{{($res_time->start_time) ? $res_time->start_time . ' - '. $res_time->close_time : 'Close' }} </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </form>
                                </table>
                                <button class="bor-btn w-100 font-26" id="venue_submitBtn" style="display: none" type="submit">Add Open
                                    Timming</button>

                            </div>
                        </div>
                        <div class="grey-brd-box mt-4">
                            <div class="title">
                                <h2>Venue Settings</h2> <a href="#" class="edit">EDIT</a>
                            </div>
                            <div class="padbox">
                                <div class="form-group">
                                    <input type="text" class="form-control vari1" placeholder="??">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control vari1" placeholder="??">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control vari1" placeholder="??">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control vari1" placeholder="??">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control vari1" placeholder="??">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control vari1" placeholder="??">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control vari1" placeholder="??">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control vari1" placeholder="??">
                                </div>
                                =
                            </div>

                            <button class="bor-btn w-100 font-30 top-bor" type="button">Support</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="grey-brd-box">
                            <div class="title">
                                <h2>Reviews</h2>
                                <div class="reviewbox">
                                    <div class="text-star">5 Star Avg</div>
                                    <div class="stars"><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll-y h-800">
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">User written review can go here of it is an extended review the
                                            box can drop down and the venue can see it. </div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">User written review can go here of it is an extended review the
                                            box can drop down and the venue can see it. </div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                                <div class="line-gradient">
                                    <table width="100%" class="reviewer mb-2">
                                        <tr>
                                            <td>12/09
                                                2023</td>
                                            <td>
                                                <div class="name">A. Smithson </div>
                                                <div class="order">Order #227721</div>
                                            </td>
                                            <td><i class="icon-ok-circled"></i> $81.00</td>
                                        </tr>
                                    </table>
                                    <div class="complete mb-2">Complete <i class="icon-star"></i><i
                                            class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                                            class="icon-star"></i></div>

                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="rvnote">This user left no review.</div>
                                        <div class="ord-time">Order Time - 09:43</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="grey-brd-box">
                            <div class="title">
                                <h2>Venue Identity</h2> <a href="javascript:void(0);" class="edit venue_res_image">EDIT</a>
                            </div>
                            <div class="padbox">
                                <form name="addimageform" id="addimageform" method="post">
                                    <input id="img-upload" type="file" class="files" name="image"  style="display: none" accept="image/*" />
                                    <figure class="venue-fig"><img src="{{ $restaurant->image }}" alt=""></figure>
                                </form>
                                <button class="bor-btn w-100 font-26" id="venueImg_submitBtn" style="display: none" type="submit">Upload Image</button>
                            </div>
                        </div>
                        <a href="#"
                            class="grey-brd-box padbox text-center lable-box mt-3"><span>Analytics</span></a>
                        <a href="{{ route('restaurants.waiter.index') }}"
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.waiter.*') ? 'active' : '' }}"><span>Account
                                Management</span></a>
                        <a href="{{ route('restaurants.drinks.index') }}"
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.drinks.*') ? 'active' : '' }}"><span>Drinks
                                Management</span></a>
                        <a href="{{ route('restaurants.mixers.index') }}"
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.mixers.*') ? 'active' : '' }}"
                            data-bs-toggle="modal" data-bs-target="#exampleModal"><span>Mixer Management</span></a>
                        <a href="{{ route('restaurants.pickup.index') }}"
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.pickup.*') ? 'active' : '' }}"><span>Pick-up
                                Zones</span></a>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- Global popup -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2>Manually Add Mixer</h2>
                </div>
                <div class="modal-body">
                    <div style="min-height: 300px;">
                        <div class="form-group mb-4">
                            <input type="text" class="form-control vari2" placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control vari2" placeholder="Enter Price">
                        </div>
                    </div>
                    <button class="bor-btn w-100 font-26" type="button">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('pagescript')
    <script src="{{ asset('js/venue.js') }}"></script>
    <script>
        var moduleConfig = {
            venueStore: "{!! route('restaurants.venue.store') !!}",
            resImageUpload: "{!! route('restaurants.res-image-upload') !!}"
        };
        $(document).ready(function() {
            XS.Venue.init();
        });
    </script>
@endsection
