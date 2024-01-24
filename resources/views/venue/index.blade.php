@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('venue.partials.topbar')
@endsection
@section('content')

            <div class="outrbox">
                <div class="row">
                    <div class="col-lg-6 col-xl-6 col-xxl-4 m-xxl-space act-grid-2">
                        <div class="grey-brd-box m-sm-space">
                            <div class="title">
                                <h2>Opening Times</h2> <a href="javascript:void(0);" class="edit venue_popup_modal ">EDIT</a>
                            </div>
                            <div class="padbox">
                                <table class="opening-time opening_timing_table">
                                    <form name="addtimerform" id="addtimerform" method="post">
                                        @if($res_times->count() === 0)
                                            @foreach ($days as $key => $day)
                                            <?php $key += 1;
                                                $id = session('restaurant');
                                            ?>
                                                @include('venue.partials.opening-times', ['id' => $id->id, 'key' => $key, 'name' => $day->name])
                                            @endforeach
                                        @else
                                            @foreach ($res_times as $res_time)
                                                @include('venue.partials.opening-times', ['id' => $res_time->restaurant_id, 'key' => $res_time->day->id, 'name' => $res_time->day->name, 'res_time' => $res_time])
                                            @endforeach
                                        @endif
                                    </form>
                                </table>
                                <button class="bor-btn w-100 font-26 mt-4" id="venue_submitBtn" style="display: none" type="submit">Add Open
                                    Timming</button>

                            </div>
                        </div>
                        <div class="grey-brd-box mt-lg-4 ">
                            <div class="title">
                                <h2>Venue Settings</h2> <a href="javascript:void(0);" class="edit_venue_data">EDIT</a>
                            </div>
                            <div class="padbox">
                                <form name="addvenueform" id="addvenueform" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control vari1" name="res_name" id="res_name" value="{{$restaurant->name}}" placeholder="??" disabled>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vari1" name="street1" id="street1" value="{{$restaurant->street1}}" placeholder="Street1" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vari1" name="street2" id="street2" value="{{$restaurant->street2}}" placeholder="Street2" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control vari1" name="city" id="city" value="{{$restaurant->city}}" placeholder="city" disabled>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control vari1" name="state" id="state" value="{{$restaurant->state}}" placeholder="State" disabled>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vari1" name="postcode" id="postcode" value="{{$restaurant->postcode}}" placeholder="Postcode" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control vari1" name="phone" id="phone" value="{{$restaurant->phone}}" placeholder="Phone" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control vari1" name="specialisation" id="specialisation" value="" placeholder="specialisation" disabled>{{$restaurant->specialisation}}</textarea>
                                    </div>
                                </form>
                            </div>

                            <button class="bor-btn w-100 font-30 top-bor" id="venue_data_submitBtn" style="display: none"  type="submit">Submit</button>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6 col-xxl-4 m-xxl-space review-box-outer">
                        <div class="grey-brd-box h-100">
                            <div class="title">
                                <h2>Reviews</h2>
                                <div class="reviewbox">
                                    <div class="text-star">{{ (float) $restaurant->average_rating }} Star Avg</div>
                                    <div class="stars">
                                        @php
                                            $rating = floor($restaurant->average_rating);
                                            $fraction = $restaurant->average_rating - $rating;
                                        @endphp
                                        @for ($i = 0; $i < $rating; $i++)
                                            <i class="icon-star"></i>
                                        @endfor
                                        @if ($fraction > 0)
                                            <i class="icon-star-half"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="scroll-y review-box-height">
                                @foreach ($order_reviews as $orderReview)
                                    <div class="line-gradient">
                                        <table width="100%" class="reviewer mb-2">
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($orderReview->order->created_at)->format('d/m
                                                Y') }}</td>
                                                <td>
                                                    <div class="name">{{$orderReview->order->user->first_name}}</div>
                                                    <div class="order">Order #{{$orderReview->order->id}}</div>
                                                </td>
                                                <td><i class="icon-ok-circled"></i> ${{$orderReview->order->total}}</td>
                                            </tr>
                                        </table>
                                        <div class="complete mb-2">Complete
                                            <div class="stars">
                                                @for ($i=0; $i<(float) $orderReview->rating; $i++)
                                                    <i class="icon-star"></i>
                                                @endfor
                                            </div>
                                        </div>

                                            <div class="rvnote mb-4">{{$orderReview->comment}} </div>
                                            <div class="ord-time">Order Time - {{ \Carbon\Carbon::parse($orderReview->order->created_at)->format('H:i') }}
                                            </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($order_reviews->total() > 10)
                            <div class="review-pagination">
                                {!! $order_reviews->links() !!}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-12 col-xl-12 col-xxl-4 ">
                        <div class="grey-brd-box">
                            <div class="title">
                                <h2>Venue Identity</h2> <a href="javascript:void(0);" class="edit venue_res_image">EDIT</a>
                            </div>
                            <div class="padbox">
                                <form name="addimageform" id="addimageform" method="post">
                                    <div class="form-group">
                                        <input id="img-upload" type="file" class="files" name="image"  style="display: none" accept="image/*" />
                                    </div>
                                    <figure class="venue-fig"><img src="{{ $restaurant->image }}" alt=""></figure>
                                </form>
                                <button class="bor-btn w-100 font-26 mt-4" id="venueImg_submitBtn" style="display: none" type="submit">Upload Image</button>
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
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.mixers.*') ? 'active' : '' }}"><span>Mixer Management</span></a>
                        <a href="{{ route('restaurants.pickup.index') }}"
                            class="grey-brd-box padbox text-center lable-box mt-3 {{ Route::is('restaurants.pickup.*') ? 'active' : '' }}"><span>Pick-up
                                Zones</span></a>
                    </div>

                </div>
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
            resImageUpload: "{!! route('restaurants.res-image-upload') !!}",
            venueEdit: "{!! route('restaurants.venue-edit') !!}",
        };
        $(document).ready(function() {
            XS.Venue.init();
        });
    </script>
@endsection
