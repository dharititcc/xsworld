@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('table.partials.topbar')
@endsection
@section('content')
<div class="outrbox">
    <div class="row">
        <div class="col-md-4 cst-col-4">
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
                            <td> {{$res_tables->count()}}</td>
                        </tr>
                        <tr>
                            <th>Active Tables</th>
                            <td> {{$active_tbl}}</td>
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
        <div class="col-md-8 cst-col-8">
            <div class="grid colmn-2 gap30 crt-table">
                @foreach ($res_tables as $res_table)
                    <div class="table-design grey-brd-box">
                        <div class="head"><label class="cst-check">
                                <input type="checkbox" class="qr_select" value="{{ $res_table->id }}"><span class="checkmark"></span></label>
                            <h3>{{ $res_table->name }} - {{$res_table->code}}</h3>
                        </div>
                        <div class="cnt">
                            <?php //dd(asset("images/".$res_table->qr_image)); ?>
                            <div class="qr-code"><img src="{{asset("images/".$res_table->qr_image)}}"/> </div>
                            <a href="javascript:void(0);" class="export-info" data-id="{{$res_table->id}}" data-bs-toggle="modal"
                                data-bs-target="#export_qr_code">Export QR<br>Code</a>

                        </div>
                        <div class="ftr">
                            <a href="javascript:void(0);" class="active @if($res_table->status == 1)  green @endif status" data-status="1"  data-id="{{$res_table->id}}">Active</a>
                            <a href="javascript:void(0);" class="disable @if($res_table->status == 0)  green @endif status" data-status="0" data-id="{{$res_table->id}}">Disable</a>
                        </div>
                    </div>
                @endforeach
                <div class="table-design grey-brd-box">
                    <a href="javascript:void(0);" class="add-table-design table_popup_modal"><i class="icon-plus"></i></a>
                </div>
            </div>
        </div>
    </div>

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

            <div class="modal-body qr-code-hide">
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
                    <button class="bor-btn export_pdf" type="button">Export As</button>
                    <button class="bor-btn print" type="button">Print</button>
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
        tableDelete: "{!! route('restaurants.table-destroy') !!}",
        exportpdf: "{!! route('restaurants.table-export_pdf') !!}",
    };
    $(document).ready(function()
    {
        XS.Table.init();
    });
</script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
@endsection
