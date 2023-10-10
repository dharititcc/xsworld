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
                                        <tr>
                                            <th>Monday</th>
                                            <td><input class="start_time" style="display: none" name="start_time[]" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme" style="display: none"  name="end_time[]" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tuesday</th>
                                            <td>
                                                <input id="days_id" type="hidden" class="days_id" name="days_id" value="2" />
                                                <input class="start_time" style="display: none" name="start_time[]" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme"  style="display: none" name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Wednesday</th>
                                            <td><input class="start_time" style="display: none" name="start_time" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme"  style="display: none" name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM- 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Thursday</th>
                                            <td><input class="start_time" style="display: none" name="start_time" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme" style="display: none"  name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Friday</th>
                                            <td><input class="start_time" style="display: none" name="start_time" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme"  style="display: none" name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Saturday</th>
                                            <td><input class="start_time" style="display: none" name="start_time" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme"  style="display: none" name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sunday</th>
                                            <td><input class="start_time" style="display: none" name="start_time" type="time" value=""
                                                    placeholder="Start Time"><input class="close_time"
                                                    placeholder="Close TIme"  style="display: none" name="end_time" type="time" value="">
                                                <label for="time" class="times">12 PM - 10 PM</label>
                                            </td>
                                        </tr>
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
                                <h2>Venue Identity</h2> <a href="#" class="edit">EDIT</a>
                            </div>
                            <div class="padbox">
                                <figure class="venue-fig"><img src="{{ $restaurant->image }}" alt=""></figure>
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
        venueUpdate: "{!! route('restaurants.venue.update', ':ID') !!}",
        venueGet: "{!! route('restaurants.venue.show', ':ID') !!}",

    };
        $(document).ready(function() {
            XS.Venue.init();
        });
    </script>
@endsection
