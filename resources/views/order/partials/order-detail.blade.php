<div class="title">
    <h2>Order #{{$order->id}}</h2> <span class="date">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span>
</div>
<div class="item-orderd">
    <h2 class="yellow">{{ $order->user->name }}</h2>
    <div class="item-list scroll-y">
        <table>
            @if ($order->order_items->count())
            @foreach ($order->order_items as $item)
            <tr>
                <?php
                    $total = $item->total;

                    if( isset($item->mixer->id) )
                    {
                        $total += $item->mixer->total;
                    }

                    if( $item->addons->count() )
                    {
                        $total += $item->addons->sum('total');
                    }
                ?>
                <td>{{ $restaurant->country->symbol }}{{ number_format($total, 2) }}</td>
                <td><strong class="yellow">
                    {{ $item->restaurant_item->name }}
                    @if ( isset($item->variation->id) )
                        - ({{ $item->variation->name }})
                    @endif
                </strong><br>
                    @if (isset($item->mixer->id))
                        {{ $item->mixer->restaurant_item->name }} -<br>
                    @endif

                    @if ($item->addons->count())
                        @foreach ($item->addons as $addon)
                            {{ $addon->restaurant_item->name }} -<br/>
                        @endforeach
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></td>
            </tr>
            @endforeach
            @endif
            {{-- <tr>
                        <td>$25.00</td>
                        <td><strong class="yellow">Pint of Sapporo</strong> </td>
                        <td>2</td>
                        <td><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td>$18.50</td>
                        <td><strong class="yellow">Chicken Club Sandwich</strong><br>
                            Chips +</td>
                        <td>1</td>
                        <td><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td>$28.50</td>
                        <td><strong class="yellow">Chicken Parmigiana</strong><br>
                            Chips -<br>
                            Salad +</td>
                        <td>1</td>
                        <td><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td>$28.50</td>
                        <td><strong class="yellow">Chicken Parmigiana</strong><br>
                            Chips -<br>
                            Salad +</td>
                        <td>1</td>
                        <td><label class="cst-check"><input type="checkbox" value=""><span class="checkmark"></span></label></td>
                    </tr> --}}
        </table>
    </div>
</div>
<div class="payopt-box">
    <table width="100%">
        <tr>
            <td>This order has been paid for.</td>
            <td>{{ $restaurant->country->symbol }}{{ $order->total }}</td>
        </tr>
        <tr>
            <td><strong class="yellow">Paid with</strong><br> Mastercard </td>
            <td>**** - **** - **** - 2572<br>
                IAN D SMITH</td>
        </tr>
    </table>
</div>
<div class="payopt-box">
    <table width="100%">
        <tr>
            <td class="green">15% Coupon discount was applied, user used 5,300 points.</td>
            <td class="green">-$12.72</td>
        </tr>
        <tr>
            <td colspan="2"></td>

        </tr>
        <tr>
            <td>
                <h2>Final</h2>
            </td>
            <td>
                <h2 class="red">{{ $restaurant->country->symbol }}{{ $order->total }}</h2>
            </td>
        </tr>
    </table>
</div>
<div class="payopt-box b-none">
    <table width="100%" class="mb-4">
        <tr>
            <td>Order completion time: </td>
            <td>{{ $order->completion_time }}</td>
        </tr>

        <tr>
            <td>Served:</td>
            <td>{{ $order->served_time }}</td>
        </tr>
    </table>
    <a href="#" class="bor-btn d-block text-center view-order mb-3">Refund Select Items
        ($0.00)</a>
    <a href="#" class="bor-btn d-block text-center view-order">Discard Refund Request</a>
</div>