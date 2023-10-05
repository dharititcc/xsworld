@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('table.partials.topbar')
@endsection
@section('content')
    <div class="container-fluid">
        <main>
            <div class="outrbox">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex flex-column sticky-sec scroll-y">
                            <div class="tbl-mng-btn">
                                <a href="javascript:void(0);" class="grey-brd-box padbox text-center lable-box table_popup_modal"><span>Add Table</span></a>
                                <a href="javascript:void(0);"
                                    class="grey-brd-box padbox text-center lable-box mt-3 btn-red remove_tables"><span>Remove Table
                                    </span></a>
                            </div>
                            <div class="key-mng">
                                <table width="100%">
                                    <tr>
                                        <th>
                                            <h2 class="yellow">Key Insights</h2>
                                        </th>
                                        <td><a href="#">Disable</a></td>
                                    </tr>
                                    <tr>
                                        <th height="20"></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total Tables</th>
                                        <td> 3</td>
                                    </tr>
                                    <tr>
                                        <th>Active Tables</th>
                                        <td> 3</td>
                                    </tr>
                                    <tr>
                                        <th>Occupied Tables</th>
                                        <td>2 </td>
                                    </tr>
                                    <tr>
                                        <th>Reserved Tables</th>
                                        <td> 0</td>
                                    </tr>
                                    <tr>
                                        <th>Staffed Tables</th>
                                        <td> 2</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="gldnline-sepr"></div>

                            <a href="{{ route('restaurants.mixers.index') }}" class="grey-brd-box padbox text-center lable-box"><span>Mixer
                                    Management</span></a>
                            <a href="{{route('restaurants.pickup.index')}}" class="grey-brd-box padbox text-center lable-box mt-3"><span>Pick-up
                                    Zones</span></a>
                            <a href="{{ route('restaurants.drinks.index') }}" class="grey-brd-box padbox text-center lable-box mt-3"><span>Drinks
                                    List</span></a>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="grid colmn-2 gap30">
                            @foreach ($res_tables as $res_table)
                                <div class="table-design grey-brd-box">
                                    <div class="head"><label class="cst-check">
                                            <input type="checkbox" class="qr_select" value="{{ $res_table->id }}"><span class="checkmark"></span></label>
                                        <h2>{{$res_table->code}}</h2>
                                    </div>
                                    <div class="cnt">
                                        <div class="qr-code"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="212" height="212" viewBox="0 0 212 212"><rect x="0" y="0" width="212" height="212" fill="#ffffff"/><g transform="scale(7.31)"><g transform="translate(0,0)"><path fill-rule="evenodd" d="M8 0L8 1L9 1L9 2L10 2L10 3L11 3L11 1L14 1L14 2L12 2L12 4L11 4L11 5L10 5L10 4L9 4L9 5L10 5L10 8L9 8L9 6L8 6L8 8L9 8L9 10L10 10L10 11L8 11L8 9L7 9L7 8L6 8L6 9L5 9L5 8L3 8L3 10L2 10L2 8L0 8L0 9L1 9L1 10L0 10L0 11L1 11L1 10L2 10L2 12L4 12L4 11L7 11L7 12L5 12L5 13L2 13L2 15L1 15L1 16L0 16L0 21L2 21L2 20L4 20L4 21L5 21L5 20L4 20L4 19L5 19L5 17L4 17L4 18L2 18L2 15L3 15L3 16L4 16L4 15L5 15L5 14L6 14L6 15L8 15L8 16L9 16L9 17L7 17L7 16L6 16L6 17L7 17L7 18L6 18L6 19L7 19L7 20L6 20L6 21L7 21L7 20L8 20L8 22L9 22L9 24L8 24L8 26L9 26L9 27L8 27L8 29L13 29L13 28L15 28L15 29L16 29L16 28L17 28L17 27L18 27L18 28L20 28L20 29L21 29L21 28L20 28L20 27L21 27L21 26L20 26L20 25L23 25L23 26L24 26L24 27L25 27L25 26L27 26L27 27L26 27L26 28L23 28L23 27L22 27L22 28L23 28L23 29L26 29L26 28L27 28L27 27L28 27L28 28L29 28L29 25L28 25L28 24L27 24L27 25L25 25L25 23L26 23L26 21L28 21L28 20L27 20L27 19L22 19L22 20L21 20L21 18L20 18L20 17L17 17L17 15L16 15L16 13L17 13L17 12L18 12L18 11L19 11L19 10L20 10L20 11L21 11L21 6L20 6L20 7L19 7L19 6L18 6L18 5L21 5L21 2L20 2L20 1L18 1L18 0L17 0L17 1L16 1L16 2L15 2L15 0L11 0L11 1L9 1L9 0ZM14 2L14 4L15 4L15 8L16 8L16 10L17 10L17 11L16 11L16 12L15 12L15 13L16 13L16 12L17 12L17 11L18 11L18 10L19 10L19 7L18 7L18 6L17 6L17 8L16 8L16 3L15 3L15 2ZM17 2L17 3L18 3L18 4L19 4L19 3L20 3L20 2L19 2L19 3L18 3L18 2ZM12 4L12 5L11 5L11 7L12 7L12 9L10 9L10 10L12 10L12 11L13 11L13 13L11 13L11 12L10 12L10 13L8 13L8 14L9 14L9 15L10 15L10 16L11 16L11 17L10 17L10 18L9 18L9 20L10 20L10 18L11 18L11 17L13 17L13 18L12 18L12 19L11 19L11 23L10 23L10 24L9 24L9 25L10 25L10 28L12 28L12 27L14 27L14 25L13 25L13 24L15 24L15 25L16 25L16 24L17 24L17 26L16 26L16 27L15 27L15 28L16 28L16 27L17 27L17 26L19 26L19 24L20 24L20 23L19 23L19 21L20 21L20 20L19 20L19 19L17 19L17 20L16 20L16 19L14 19L14 20L13 20L13 21L12 21L12 19L13 19L13 18L14 18L14 17L13 17L13 16L15 16L15 15L14 15L14 11L15 11L15 10L14 10L14 8L13 8L13 7L14 7L14 5L13 5L13 4ZM12 5L12 7L13 7L13 5ZM17 8L17 9L18 9L18 8ZM22 8L22 9L23 9L23 8ZM28 8L28 9L26 9L26 11L27 11L27 10L28 10L28 9L29 9L29 8ZM4 9L4 10L5 10L5 9ZM6 9L6 10L7 10L7 9ZM12 9L12 10L13 10L13 9ZM24 9L24 10L22 10L22 11L23 11L23 12L21 12L21 13L20 13L20 12L19 12L19 13L20 13L20 14L19 14L19 15L18 15L18 16L19 16L19 15L20 15L20 16L21 16L21 17L22 17L22 16L23 16L23 18L28 18L28 19L29 19L29 18L28 18L28 17L24 17L24 15L23 15L23 12L25 12L25 9ZM28 11L28 13L27 13L27 14L26 14L26 13L24 13L24 14L25 14L25 15L26 15L26 16L27 16L27 14L28 14L28 16L29 16L29 11ZM0 12L0 14L1 14L1 12ZM6 13L6 14L7 14L7 13ZM10 14L10 15L11 15L11 16L12 16L12 15L13 15L13 14ZM20 14L20 15L21 15L21 16L22 16L22 15L21 15L21 14ZM16 17L16 18L17 18L17 17ZM7 18L7 19L8 19L8 18ZM15 20L15 22L14 22L14 23L15 23L15 24L16 24L16 20ZM17 20L17 21L18 21L18 20ZM21 21L21 24L24 24L24 21ZM12 22L12 23L13 23L13 22ZM17 22L17 24L18 24L18 22ZM22 22L22 23L23 23L23 22ZM10 24L10 25L11 25L11 24ZM12 25L12 26L13 26L13 25ZM24 25L24 26L25 26L25 25ZM27 25L27 26L28 26L28 25ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM22 0L22 7L29 7L29 0ZM23 1L23 6L28 6L28 1ZM24 2L24 5L27 5L27 2ZM0 22L0 29L7 29L7 22ZM1 23L1 28L6 28L6 23ZM2 24L2 27L5 27L5 24Z" fill="#000000"/></g></g></svg></div>
                                        <a href="javascript:void(0);" class="export-info" data-bs-toggle="modal"
                                            data-bs-target="#export_qr_code">Export QR<br>Code</a>

                                    </div>
                                    <div class="ftr">
                                        <a href="javascript:void(0);" class="active @if($res_table->status == 1)  green @endif status" data-status="1"  data-id="{{$res_table->id}}">Active</a>
                                        <a href="javascript:void(0);" class="disable @if($res_table->status == 0)  green @endif status" data-status="0" data-id="{{$res_table->id}}">Disable</a>
                                    </div>
                                </div>
                            @endforeach
                            {{-- <div class="table-design grey-brd-box">
                                <div class="head"><label class="cst-check">
                                        <input type="checkbox" value=""><span class="checkmark"></span></label>
                                    <h2>Table #2</h2>
                                </div>
                                <div class="cnt">
                                    <div class="qr-code"><img src="img/clarity_qr-code-line.png" alt=""></div>
                                    <a href="#" class="export-info">Export QR<br>Code</a>
                                </div>
                                <div class="ftr">
                                    <a href="#" class="active">Active</a>
                                    <a href="#" class="disable red">Disable</a>
                                </div>
                            </div>
                            <div class="table-design grey-brd-box del-table">
                                <div class="head"><label class="cst-check">
                                        <input type="checkbox" value=""><span class="checkmark"></span></label>
                                    <h2>Table #3</h2>
                                </div>
                                <div class="cnt">
                                    <div class="qr-code"><img src="img/clarity_qr-code-line.png" alt=""></div>
                                    <a href="#" class="export-info">Export QR<br>Code</a>
                                </div>
                                <div class="ftr">
                                    <a href="#" class="active green">Active</a>
                                    <a href="#" class="disable">Disable</a>
                                </div>
                            </div> --}}
                            <div class="table-design grey-brd-box">
                                <a href="javascript:void(0);" class="add-table-design "><i class="icon-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Global popup -->
    <div class="modal fade" id="tableModal" tabindex="-1" aria-labelledby="tableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="table_model_title"> </span>Add New Table</h2>
                </div>
                <div class="modal-body">
                    <form name="addtableform" id="addtableform" method="post">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input type="text" name="name" class="form-control vari2" placeholder="Table Name">
                            </div>
                            <div class="form-group">
                                <input type="text" name="code" id="code" class="form-control vari2" placeholder="Table Code">
                                <span id="Errorcode"></span>
                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26" id="table_submitBtn" type="submit">Add</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- another popup-->

    <div class="modal fade qr-code-pop" id="export_qr_code" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <div style="min-height: 300px;">
                        <div class="mb-3 table-ids">
                            Table 1
                        </div>
                        <figure class="qrcode">
                            <img src="img/qr-code.jpg" alt="">
                        </figure>

                        <div class="text-center x-logo"><img src="img/x-logo.png" alt=""></div>

                    </div>

                    <div class="d-flex ftr">
                        <button class="bor-btn " type="button">Export As</button>
                        <button class="bor-btn " type="button">Print</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Global popup -->
    <!-- Bootstrap core JS-->
@endsection

@section('pagescript')
<script src="{{ asset('js/table.js') }}"></script>
<script>
    var moduleConfig = {
        tableStore: "{!! route('restaurants.table.store') !!}",
        tableUpdate: "{!! route('restaurants.table.update', ':ID') !!}",
        tableGet: "{!! route('restaurants.table.show', ':ID') !!}",
        tableStatusUpdate: "{!! route('restaurants.table-status') !!}",
    };
    $(document).ready(function()
    {
        XS.Table.init();
    });
</script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
@endsection
