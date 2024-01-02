<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
</head>
<body>
    <label>Order No</label>             :    {{ $order->id}} </br>
    <label>Order Date</label>           :    {{ $order->created_at}} </br>
    <label>Customer Name </label>       :    {{ $order->user->first_name }} {{  $order->user->last_name }} </br>
    {{-- <label>Customer Name </label>  :    {{ $order->created_at}} </br> --}}
    <label>Restaurant Name</label>      :    {{ $order->restaurant->name}} </br>

    <hr>
    Order items
    <hr>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>Items</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $items)
                <tr>
                    <td>{{ $items->restaurant_item->name }}</td>
                    <td>{{ $items->quantity }}</td>
                    <td>{{ $order->restaurant->country->symbol.$items->price }}</td>
                    <td>{{ $order->restaurant->country->symbol.$items->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <label style="text-align: left"> Grand Total</label> : {{ $order->restaurant->country->symbol.$order->total }}
</body>
</html>
