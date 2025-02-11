@extends('admin.layouts.mainlayout')
@section('topbar')
@include('admin.customer.partials.topbar')
@endsection
@section('content')
<div class="outrbox">
    <div class="sort-by d-flex mb-4">
        <h2 class="yellow">Search</h2>
        <div class="searchbox"><input type="text" name="search" id="search" class="searchbar" placeholder="Find a Customer"></div>
    </div>
    <div class="data-table drinks table-responsive">
        <table width="100%" class="customer_datatable drink_datatable no_check">
            <thead>
                <tr valign="middle">
                    {{-- <th class="dt-left"><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span class="checkmark"></span></label></th> --}}
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
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
<script src="{{ asset('js/admin/customer.js') }}"></script>
<script type="text/javascript">
    var moduleConfig = {
        tableAjax:          "{!! route('admin.customer.table') !!}",
        deleteCustomer:     "{!! route('admin.customer.destroy',':ID') !!}",
    };

    $(document).ready(function() {
        XS_Admin.Customer.init();
    });
</script>
@endsection