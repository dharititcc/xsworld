<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>invoice email</title>
    <style type="text/css">
        /* .main {
            max-width: 1600px;
            margin: 100px auto;
        } */

        table,
        tr,
        tr td {
            width: 100%;
            border-collapse: collapse;
        }

        .td-border {
            border-bottom: 1px solid #000;
        }

        .td-top-border {
            border-top: 1px solid #000;
        }

        tr td {
            padding: 15px;
        }

        tr td:last-child {
            text-align: right;
        }

        h1 {
            font-size: 30px;
            font-weight: 700;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            line-height: 22px;
        }

        .bg-img {
            height: 265px;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
        }

        .bg-img img {
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<?php
$todayDate = date('F j, Y');
date_default_timezone_set('Asia/Dubai');
$dateTime = date('m/d/y g:iA');
$filename       = 'invoice_' . $order->id . '.pdf';
$cardBrand = '';
$lastDigit = '';

foreach ($cardDetails as $cardData) {
    if (!empty($cardData->brand)) {
        $cardBrand = $cardData->brand;
    }
    if (!empty($cardData->last4)) {
        $lastDigit = $cardData->last4;
    }
}

$taxAmount = 0;
$taxAmount = $order->amount * 0.05;
$totalAmount = $order->amount + $taxAmount + 50.59;
?>

<body class="home-wrap">
    <div class="main">
        <!-- <div class="bg-img">
            <img src="{{ asset('Vector.png') }}" />
        </div> -->
        <table class="table">
            <!-- <tbody style="background: url('{{ asset('Vector.png') }}');background-repeat: no-repeat;background-size: 100% 38%;padding: 20px 80px;display: block;"> -->
            <tbody style="background: url('{{ asset('Vector.png') }}') no-repeat center center / cover; padding: 20px 80px; display: block;">

                <tr>
                    <td colspan="5"><img width="119" height="28" src="{{ asset('XSWorld.png') }}" alt="XS World Logo"></td>
                    <td colspan="2">
                        <div>
                            <p style="white-space: nowrap;margin:0 0 8px;font-size: 14px;text-align: right;">Total <b>{{$order->restaurant->country->symbol}}{{$totalAmount}}</b></p>
                            <p style="white-space: nowrap;margin:0;font-size: 14px;text-align: right;">{{$todayDate}}</p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <h2 style="color: #000;font-size: 20px;font-style: normal;font-weight: 700;line-height: normal;">Thanks for ordering, {{$order->user->first_name}}</h2>
                    </td>
                    <td colspan="5"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <p style="font-size: 16px; font-weight: 400;margin: 0;color: #000;">Here’s your receipt for ordering with {{$order->restaurant->name}}</p>
                    </td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">
                        <div><img style="max-width: 150px;width: 150px;height: 150px;" src="{{ asset('cocktail.png') }}" alt=""></div>
                    </td>
                </tr>

                <tr>
                    <td class="td-border" colspan="6"><strong>
                            <h1 style="color: #000;font-size: 30px;font-style: normal;font-weight: 700;line-height: normal;">Total</h1>
                        </strong></td>
                    <td class="td-border"><strong>
                            <h1 style="color: #000;text-align: right;font-size: 30px;font-style: normal;font-weight: 700;line-height: normal;">{{$order->restaurant->country->symbol}}{{$totalAmount}} </h1>
                        </strong></td>
                </tr>

                <tr>
                    <td class="td-border" colspan="6">
                        <p style="font-size: 16px; font-weight: 700;margin: 0 0 16px; color:'#000';">Payments</p>
                        <div style="display: flex;gap:20px;">

                            @if($cardBrand == "Visa")
                            <img src=" {{ asset('ic-visa.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Master")
                            <img src=" {{ asset('master-card.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "American Express")
                            <img src=" {{ asset('american_express.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Discover")
                            <img src=" {{ asset('discover.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Jcb")
                            <img src=" {{ asset('jcb.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Unionpay")
                            <img src=" {{ asset('unionpay.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Cartes Bancaires")
                            <img src=" {{ asset('cb.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Interac")
                            <img src=" {{ asset('inter.png')}}" style="width: 44px; object-fit: cover;">
                            @elseif($cardBrand == "Diners")
                            <img src=" {{ asset('diner.png')}}" style="width: 44px; object-fit: cover;">
                            @endif

                            <div style="padding-left: 20px;">
                                <h3 style="color:#0F0E0E; font-size: 14px;">Paid via Credit/ Debit Card ****{{$lastDigit}}</h3>
                                <h3 style="color: #0F0E0E;font-size: 14px;">{{$dateTime}}</h3>
                            </div>
                        </div>
                    </td>
                    <td class="td-border">
                        <p style="font-size: 16px; font-weight: 700;margin: 0;text-align: right;">{{$order->restaurant->country->symbol}}{{$totalAmount}}</p>
                    </td>
                </tr>
                <tr>

                    <td colspan="7" align="center" style="text-align: center;">
                        <p style="font-size: 14px; padding: 6px 12px; font-weight: 400; background-color: #FCF5E6; margin: 0;display:inline-block; color:'#000';">
                            To view your full receipt <a target="_blank" rel="noopener noreferrer" href="{{ asset('/storage/order_pdf/' . $filename) }}" download="{{ $filename }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color:'#000'">download this PDF</a>
                        </p>
                    </td>

                </tr>
                <tr>
                    <td colspan="6">
                        <p style="font-size: 14px; font-weight: 400;margin: 0;">You ordered from {{ strtoupper($order->restaurant->name) }} </p>
                    </td>
                    <td></td>

                </tr>
                <tr>
                    <td colspan="6">
                        <p style="font-size: 14px; font-weight: 400;margin: 0;color:'#000'">Picked up from</p>
                    </td>
                    <td></td>

                </tr>
                <tr>
                    <td colspan="6">
                        <p style="font-size: 14px; font-weight: 400;margin: 0;">{{ $order->restaurant->address }}</p>
                    </td>
                    <td></td>

                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>