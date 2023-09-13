@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('pickup.partials.topbar')
@endsection
@section('content')

 <!-- Page content-->
<div class="container-fluid">
    <main>
        <div class="outrbox">
            <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Active Waiters</h2> <div class="count-item">Total: 4</div></div>
            <div class="grid colmn-5">

                <div class="grey-brd-box waiters">
                    <div class="status-box ">#01 Amy  <div class="status"> Active</div></div>
                    <div class="wait-footer">Currently Serving Table 03</div>
                </div>
                <div class="grey-brd-box waiters">
                    <div class="status-box ">#02 Samantha   <div class="status"> Active</div></div>
                    <div class="wait-footer">Currently Idle</div>
                </div>
                <div class="grey-brd-box waiters">
                    <div class="status-box ">#03 David  <div class="status"> Active</div></div>
                    <div class="wait-footer">Currently Serving Table 06</div>
                </div>
                <div class="grey-brd-box waiters">
                    <div class="status-box ">Steven #13 <div class="status"> Active</div></div>
                    <div class="wait-footer">Currently Idle</div>
                </div>
                <div class="grey-brd-box waiters">
                    <div class="status-box ">Steven #13 <div class="status"> Active</div></div>
                    <div class="wait-footer">Currently Idle</div>
                </div>

                <!-- <a href="#" class="grey-brd-box waiters add">
                    <i class="icon-plus"> </i>
                </a> -->
            </div>
            <div class="gldnline-sepr mb-5 mt-5"></div>
            <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Food Pick-up Points</h2> <div class="count-item">Total: {{ $food_pickup_points->count()}}</div></div>
            <div class="grid colmn-5">

                @foreach ($food_pickup_points as $pickup_point)
                    <div class="catg-box overly">
                        <button><i class="icon-trash"></i></button>
                        <figure><img src="{{$pickup_point->attachment}}"><figcaption><span>{{$pickup_point->name}}</span></figcaption>
                        </figure>
                    </div>

                @endforeach

                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModal" class="catg-box add overly"><figure><i class="icon-plus"> </i></figure><!--<input type="text" required="" autofocus=""> --></a>
            </div>
            <div class="gldnline-sepr mb-5 mt-5"></div>

            <div class="d-flex mb-4 justify-content-between doubl-line"><h2 class="yellow">Drink Pick-up Points</h2> <div class="count-item">Total: {{ $drink_pickup_points->count()}}</div></div>
            <div class="grid colmn-5">

                @foreach ($drink_pickup_points as $pickup_point)
                    <div class="catg-box overly">
                        <button><i class="icon-trash"></i></button>
                        <figure><img src="{{$pickup_point->image}}" alt="">
                            <figcaption><span> {{$pickup_point->name}} </span></figcaption>
                        </figure>
                    </div>
                @endforeach


                <a href="#" class="catg-box add overly"><figure><i class="icon-plus"> </i></figure>

                <!--<input type="text" required="" autofocus=""> -->
            </a>
            </div>
        </div>
    </main>
</div>

<!-- Global popup -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header justify-content-start ">
            <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
            <h2>Add Food Pick-up</h2>
        </div>
        <div class="modal-body">
            <div style="min-height: 300px;">
                <div class="form-group mb-4">
                    <input type="text" class="form-control vari2" placeholder="Zone Name" >
                </div>
                <div class="form-group grey-brd-box custom-upload mb-5">
                    <input id="upload" type="file" class="files" name="files[]" hidden />
                    <label for="upload"><span> Add Zone Feature Image <br> (This can be changed & is mandatory).</span> <i class="icon-plus"></i></label>
                </div>
            </div>
                <button class="bor-btn w-100 font-26" type="button">Save</button>
        </div>

        </div>
    </div>
</div>
<!-- Global popup -->


@endsection

@section('pagescript')
<script>
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
</script>
@endsection
