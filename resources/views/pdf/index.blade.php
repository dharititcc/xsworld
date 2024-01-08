<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
    <style>
        @media print {
            .page-break {
                display: block;
                page-break-before: always;
            }
        }

        body {
            font-family: Calibri;
        }

        table {
            font-family: Calibri;
        }

        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            width: 44mm;
            background: #FFF;
        }

        #invoice-POS ::selection {
            background: #EAC36C;
            color: #000;
        }

        #invoice-POS ::moz-selection {
            background: #EAC36C;
            color: #000;
        }

        #invoice-POS h1 {
            font-size: 1.5em;
            color: #222;
        }

        #invoice-POS h2 {
            font-size: .9em;
        }

        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        #invoice-POS p {
            font-size: .7em;
            color: #666;
            line-height: 1.2em;
        }

        #invoice-POS #top,
        #invoice-POS #mid,
        #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS #top {
            min-height: 100px;
        }

        #invoice-POS #mid {
            min-height: 80px;
        }

        #invoice-POS #bot {
            min-height: 50px;
        }

        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
            background-size: 60px 60px;
        }

        #invoice-POS .info {
            display: block;
            margin-left: 0;
        }

        #invoice-POS .title {
            float: right;
        }

        #invoice-POS .title p {
            text-align: right;
        }

        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
        }

        #invoice-POS .tabletitle {
            font-size: .5em;
            background: #EEE;
        }

        #invoice-POS .service {
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS .item {
            width: 24mm;
        }

        #invoice-POS .itemtext {
            font-size: .5em;
        }

        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        @media print {
            #printPageButton {
                display: none;
            }
        }
    </style>
</head>

<body translate="no">
    <div id="invoice-POS">
        <center id="top">
            <div class="logo"></div>
            <div class="info">
                <h2>{{ $order->restaurant->name }}</h2>
            </div><!--End Info-->
        </center><!--End InvoiceTop-->

        <div id="mid">
            <div class="info">
                <h2>Contact Info</h2>
                <p>
                    Address : {{ $order->restaurant->address }} <br>
                    Email : {{ $restaurant->email }}<br>
                    Phone : {{ $restaurant->phone }}<br>
                </p>
            </div>
        </div><!--End Invoice Mid-->

        <div id="bot">
            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Item</h2>
                        </td>
                        <td class="Hours">
                            <h2>Qty</h2>
                        </td>
                        <td class="Hours">
                            <h2>Price</h2>
                        </td>
                        <td class="Rate">
                            <h2>Sub Total</h2>
                        </td>
                    </tr>
                    @foreach ($order->items as $items)
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext"> {{ $items->restaurant_item->name }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"> {{ $items->quantity }} </p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"> {{ $order->restaurant->country->symbol . $items->price }} </p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext"> {{ $order->restaurant->country->symbol . $items->total }} </p>
                            </td>
                        </tr>
                    @endforeach

                    <tr class="tabletitle">
                        <td></td>
                        <td></td>
                        <td class="Rate">
                            <h2>Total</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ $order->restaurant->country->symbol . $order->total }}</h2>
                        </td>
                    </tr>

                </table>
            </div><!--End Table-->

            <div id="legalcopy">
                <p class="legal"><strong>Thank you for your business!</strong> Payment is expected within 31 days;
                    please
                    process this invoice within that time. There will be a 5% interest charge per month on late
                    invoices.
                </p>
            </div>
        </div><!--End InvoiceBot-->
        {{-- <button id="printPageButton" onclick="window.print()">Print</button> --}}
    </div><!--End Invoice-->
</body>
</html>
