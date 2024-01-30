@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('order.partials.topbar')
@endsection
@section('content')
<div class="row">
    <div class="col-md-7 cst-col-7 hitory-list">
        <ul class="scroll-y h-100 order-scroll">
            @if ($orders->count())
                @foreach ($orders as $order)
                <li>
                    <article>
                        <aside>
                            <i class="icon-ok-circled green"></i>
                            <time>
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m') }}
                                {{ \Carbon\Carbon::parse($order->created_at)->format('Y') }}
                            </time>

                            <div class="ord-id">
                                {{ $order->user->name }}<br>
                                <span>Order #{{ $order->id }}</span>
                            </div>
                        </aside>
                        <div class="ord-status">
                            @php
                                if (in_array($order->status, [\App\Models\Order::CUSTOMER_CANCELED, \App\Models\Order::DENY_ORDER]) || in_array($order->waiter_status, [\App\Models\Order::CUSTOMER_CANCELED]))
                                {
                                    $class = 'red';
                                }
                                else
                                {
                                    $class = 'green';
                                }
                            @endphp
                            <span class="{{ $class }}">{{ $order->order_status }}</span><br>
                            {{ $restaurant->country->symbol }}{{ $order->total }}
                        </div>
                    </article>
                    <a href="javascript:void(0);" class="bor-btn d-block text-center view-order" data-order_id="{{ $order->id }}">View Order</a>
                </li>
                @endforeach
            @else
                <div>No Orders Found</div>
            @endif
        </ul>

        <div class="history-pagination">
            {!! $orders->links() !!}
        </div>
    </div>

    <div class="col-md-5 view-odbox-outr blank">
        <div class="view-odbox">

            <div class="no-history-view">
                <h2 class="yellow">Click View Order</h2>
                <p>When you select an order it will appear in this box, allowing you to fully view every detail about the order and process any potential refunds required if need be.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')
<script>
    var moduleConfig = {
        viewOrderUrl: "{!! route('restaurants.orders.show', ':ID') !!}"
    };

    $(document).ready(function()
    {
        var selectors = {
            viewOrder:          jQuery('.view-order'),
            orderDetailView:    jQuery('.view-odbox'),
            historyList:        jQuery('.hitory-list'),
        };

        selectors.viewOrder.on('click', function(e)
        {
            e.preventDefault();

            var $this   = jQuery(this),
                orderId = $this.data('order_id');

            XS.Common.btnProcessingStart($this);

            selectors.historyList.find('.view-order').removeClass('act');

            $.ajax({
                url: moduleConfig.viewOrderUrl.replace(':ID', orderId),
                type: "GET",
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    selectors.orderDetailView.closest('.view-odbox-outr').removeClass('blank');
                    selectors.orderDetailView.html(response.data);
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop($this, 'View Order');
                    $this.addClass('act');
                }
            });
        });
    });
</script>
@endsection