<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Detail</title>
    <style type="text/css">
        @font-face {
            font-family: 'ABC Diatype';
            src: url("{{ asset('fonts/abc-diatype-regular-webfont.woff') }}") format('woff');
            src: url("{{ asset('fonts/abc-diatype-bold-webfont.woff2') }}") format('woff');
            src: url("{{ asset('fonts/abc-diatype-regular-webfont.woff') }}") format('woff');
            src: url("{{ asset('fonts/abc-diatype-regular-webfont.woff2') }}") format('woff');
        }

        table,
        tr,
        tr td {
            width: 100%;
            border-collapse: collapse;
            /* font-family: 'ABC Diatype', sans-serif; */

        }

        .td-border {
            border-bottom: 1px solid rgba(15, 14, 14, 0.5);
        }

        .td-top-border {
            border-top: 1px solid rgba(15, 14, 14, 0.5);
        }

        tr td {
            padding: 15px;
            width: 100%;
            /* font-family: 'ABC Diatype', sans-serif; */
        }

        tr td.lf-padding {
            padding: 15px 0;
        }

        tr td:last-child {
            text-align: right;
        }

        .product-name {
            padding: 0 0 16px;
        }


        h1 {
            font-size: 26px;
            font-weight: 700;
        }

        h2 {
            font-size: 20px;
            font-weight: 500;
            color: #0F0E0E;
        }

        h2.order-name {
            font-size: 20px;
            font-weight: 400;
            color: #0F0E0E;
        }

        h3 {
            font-size: 18px;
            font-weight: 700;
        }
    </style>

</head>
<?php
$todayDate = date('F j, Y');
date_default_timezone_set('Asia/Dubai');
$dateTime = date('m/d/y g:iA');
foreach ($cardDetails as $cardData) {
    $cardBrand = $cardData->brand;
    $lastDigit = $cardData->last4;
}
?>

<body class="home-wrap">
    <div class="main">
        <table class="table ">
            <tbody>
                <tr>
                    <td class="td-border lf-padding" style="font-family: 'ABC Diatype', sans-serif;" colspan="6"><img width="119" height="28" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('XSWorld.png'))) }}" alt="XS World Logo"></td>
                    <td class="td-border" style="font-family: 'ABC Diatype', sans-serif;">&nbsp;</td>
                    <td class="td-border lf-padding" style="font-family: 'ABC Diatype', sans-serif;">{{ $todayDate }}</td>
                </tr>

                <tr>
                    <td colspan="8" class="lf-padding" style="padding-top: 0; padding-bottom: 0;">
                        <h2 class="order-name" style="text-align: left; font-size: 24px; font-weight: 400; color: #0F0E0E;">Thanks for Ordering, {{ $restaurant->first_name }}</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="lf-padding" style="text-align: left; padding-top: 0; padding-bottom: 0; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;"> Here's your receipt for ordering with, <span style="font-family: 'ABC Diatype', sans-serif;">{{ $pdfData->restaurant->name }}</span></div>
                    </td>
                </tr><br />
                @php
                $taxAmount = 0;
                $taxAmount = $pdfData->total * 0.05;
                $totalAmount = $pdfData->total + $taxAmount + 50.59;
                $counter = 1;
                @endphp
                <tr>
                    <td class="td-border lf-padding" style="font-family: 'ABC Diatype', sans-serif;" colspan="7"><strong style="font-size: 18px;font-weight:700; font-family: 'ABC Diatype', sans-serif;">Total</strong></td>
                    <td class="td-border lf-padding" style="font-family: 'ABC Diatype', sans-serif;"><strong style="font-size: 18px;font-weight:700; font-family: 'ABC Diatype', sans-serif;">{{ $pdfData->restaurant->country->symbol . number_format($totalAmount, 2) }}</strong></td>
                </tr>

                @foreach ($pdfData->order_items as $items)
                <tr class="product-tr">
                    <?php
                    $total = $items->total;

                    if (isset($items->mixer->id)) {
                        $total += $items->mixer->total;
                    }

                    if ($items->addons->count()) {
                        $total += $items->addons->sum('total');
                    }
                    ?>
                    <td colspan="7" style="width: 100%;padding:16px 0 0; font-family: 'ABC Diatype', sans-serif;">
                        <p style="padding:1px 4px;margin: 0 6px 16px 0;white-space: nowrap; font-size:12px; border: 1px solid #000;display: inline-block; vertical-align: middle; font-family: 'ABC Diatype', sans-serif;"> {{$counter}}</p>
                        <p style="margin: 0 0 16px  ;white-space: nowrap; font-size:14px; display: inline-block; vertical-align: middle; font-family: 'ABC Diatype', sans-serif;">{{ $items->restaurant_item->name }}
                            @if ( isset($items->variation->id) )
                            - ({{ $items->variation->name }})
                            @endif
                        </p>

                        @if (isset($items->mixer->id))

                        <p style="margin:  0 0 16px 28px;white-space: nowrap;font-size:14px; font-family: 'ABC Diatype', sans-serif;">{{ $items->mixer->restaurant_item->name }}&nbsp;{{$pdfData->restaurant->country->symbol.$items->mixer->restaurant_item->price}}</p>
                        @endif

                        @if ($items->addons->count())
                        @foreach ($items->addons as $addon)

                        <p style="margin: 0 0 16px 28px;white-space: nowrap;font-size:14px; font-family: 'ABC Diatype', sans-serif;">{{ $addon->restaurant_item->name }}&nbsp;{{$pdfData->restaurant->country->symbol.$addon->restaurant_item->price}}</p>
                        @endforeach
                        @endif
                    </td>
                    <td class="lf-padding" style="font-family: 'ABC Diatype', sans-serif; display: flex; align-items: baseline;">{{ $pdfData->restaurant->country->symbol . number_format($total, 2) }}</td>
                </tr>
                @php
                $counter++;
                @endphp
                @endforeach

                <tr>
                    <td class="td-top-border lf-padding" colspan="7" style="width: 100%; padding-bottom: 10px;font-size: 14px; font-weight: 700; font-family: 'ABC Diatype', sans-serif;"><strong>Subtotal</strong></td>
                    <td class="td-top-border lf-padding" style="padding-bottom: 10px; font-weight: 700; font-family: 'ABC Diatype', sans-serif;">{{ $pdfData->restaurant->country->symbol . number_format($pdfData->total, 2) }}</td>
                </tr>

                <tr>
                    <td class="lf-padding" colspan="7" style="width: 100%; padding-top: 0px; padding-bottom: 10px; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">Plateform Fee</div>
                    </td>
                    <td class="lf-padding" style="padding-top: 0px; padding-bottom: 10px; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">$50.59</div>
                    </td>
                </tr>

                <tr>
                    <td class="td-border lf-padding" colspan="7" style="width: 100%; padding-top: 0; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">Taxes (5%)</div>
                    </td>
                    <td class="td-border lf-padding" style="padding-top: 0; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">{{ $pdfData->restaurant->country->symbol . number_format($taxAmount, 2) }}</div>
                    </td>
                </tr>

                <tr>
                    <td class="td-border lf-padding" colspan="7" style="width: 100%; padding: 50px 0px; font-family: 'ABC Diatype', sans-serif;">
                        <div class="card_info" style="font-family: 'ABC Diatype', sans-serif;">
                            <div class="card_img" style="display: inline-block; vertical-align: middle; margin-right: 20px; font-family: 'ABC Diatype', sans-serif;">
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
                                @endif
                            </div>
                            <div class="title_wrapper" style="display: inline-block; vertical-align: middle; font-family: 'ABC Diatype', sans-serif;">
                                <h3 style="font-size: 14px; margin: 0; font-family: 'ABC Diatype', sans-serif;">Paid via Credit/ Debit Card ****{{$lastDigit}}</h3>
                                <h3 style="font-size: 14px; margin: 0; font-family: 'ABC Diatype', sans-serif;">{{$dateTime}}</h3>
                            </div>
                        </div>

                    </td>

                    <td class="td-border lf-padding" style="width: 100%;">
                        <div style="font-size: 16px;font-weight:700; font-family: 'ABC Diatype', sans-serif;"><strong style="font-family: 'ABC Diatype', sans-serif;">
                                {{ $pdfData->restaurant->country->symbol . number_format($totalAmount, 2) }}
                            </strong></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="width: 100%; text-align: left; padding-top: 30px; padding-bottom: 10px; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">Your ordered from {{ strtoupper($pdfData->restaurant->name) }}</div>
                    </td>
                    <!-- <td></td> -->

                </tr>
                <tr>
                    <td colspan="7" style="width: 100%; padding-top: 0; padding-bottom: 10px; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;"><strong>Picked up from</strong> </div>
                    </td>
                    <td style="font-family: 'ABC Diatype', sans-serif;"></td>

                </tr>
                <tr>
                    <td colspan="7" style="width: 100%; padding-top: 0; padding-bottom: 0; font-family: 'ABC Diatype', sans-serif;">
                        <div style="font-family: 'ABC Diatype', sans-serif;">{{ $pdfData->restaurant->address }}</div>
                    </td>
                    <td style="font-family: 'ABC Diatype', sans-serif;"></td>

                </tr>

            </tbody>
        </table>
    </div>

</body>

</html>