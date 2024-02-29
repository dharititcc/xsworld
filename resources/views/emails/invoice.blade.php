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
foreach ($cardDetails as $cardData) {
    $cardBrand = $cardData->brand;
    $lastDigit = $cardData->last4;
}

?>

<body class="home-wrap">
    <div class="main">
        <div class="bg-img">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('Vector.png'))) }}" />
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="5"><img width="119" height="28" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('XSWorld.png'))) }}" alt="XS World Logo"></td>
                    <td colspan="2">
                        <div>
                            <p style="white-space: nowrap;margin:0 0 8px;font-size: 14px;">Total <b>{{$order->restaurant->country->symbol}}{{$order->amount}}</b></p>
                            <p style="white-space: nowrap;margin:0;font-size: 14px;">{{$todayDate}}</p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <h2>Thanks for ordering, {{$order->user->first_name}}</h2>
                    </td>
                    <td colspan="5"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <p style="font-size: 16px; font-weight: 400;margin: 0;">Here’s your receipt for ordering with {{$order->restaurant->name}}</p>
                    </td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2">
                        <div><img style="width: 100%;height:auto;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('cocktail.png'))) }}" alt=""></div>
                    </td>
                </tr>

                <tr>
                    <td class="td-border" colspan="6"><strong>
                            <h1>Total</h1>
                        </strong></td>
                    <td class="td-border"><strong>
                            <h1>{{$order->restaurant->country->symbol}}{{$order->amount}} </h1>
                        </strong></td>
                </tr>

                <tr>
                    <td class="td-border" colspan="6">
                        <p style="font-size: 16px; font-weight: 700;margin: 0 0 16px;">Payments</p>
                        <div style="display: flex;gap:20px;">

                            @if($cardBrand == "Visa")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('ic-visa.png'))) }}">
                            @elseif($cardBrand == "Master")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('master-card.png'))) }}">
                            @elseif($cardBrand == "American Express")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('american_express.png'))) }}">
                            @elseif($cardBrand == "Discover")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('discover.png'))) }}">
                            @elseif($cardBrand == "Jcb")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('jcb.png'))) }}">
                            @elseif($cardBrand == "Unionpay")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('unionpay.png'))) }}">
                            @elseif($cardBrand == "Cartes Bancaires")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('cb.png'))) }}">
                            @elseif($cardBrand == "Interac")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('inter.png'))) }}">
                            @elseif($cardBrand == "Diners")
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('diner.png'))) }}">
                            @endif

                            <div>
                                <h3>Paid via Credit/ Debit Card ****{{$lastDigit}}</h3>
                                <h3>{{$dateTime}}</h3>
                            </div>
                        </div>
                    </td>
                    <td class="td-border">
                        <p style="font-size: 16px; font-weight: 700;margin: 0;">{{$order->restaurant->country->symbol}}{{$order->amount}}</p>
                    </td>
                </tr>
                <tr>

                    <td colspan="7" align="center" style="text-align: center;">
                        <p style="font-size: 14px; padding: 6px 12px; font-weight: 400; background-color: #FCF5E6; margin: 0;display:inline-block; ">
                            To view your full receipt <a target="_blank" rel="noopener noreferrer" href="{{ asset('/storage/order_pdf/' . $filename) }}" download="{{ $filename }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4;">download this PDF</a>
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
                        <p style="font-size: 14px; font-weight: 400;margin: 0;">Picked up from</p>
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