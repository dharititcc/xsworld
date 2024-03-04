<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>giftcard email</title>
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
            text-align: center;
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

        h1 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }

        h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }

        h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }
        .boxone {
            border-radius: 12px;
            border: 0.25px solid  #805711;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%), linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(254, 243, 179, 0.12) 100%);
            box-shadow: 0px 0px 17.2px -3px rgba(204, 178, 96, 0.28);
            backdrop-filter: blur(22px);
            height: 169px;
            margin-top: 50px;
        }
        .boxone-inner {
            border-radius: 12px;
            border: 0.25px solid  #805711;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(254, 243, 179, 0.12) 100%);
            box-shadow: 0px 0px 75.7px -28px #FEF3B3;
            margin: 26px 0 24px;
        }
        .bg-img {
            position: absolute;
            transform: translateX(-50%);         
            width: 100%;
            top: 153px;
            left: 50%;
            transform: translateX(-50%);
            height: 255px;
            width: 519px;
        }
        .bg-img img {
            height: 100%;
            width: 100%;
        }
        .logo-img{
            padding-left: 20px;
        }
        .receipent-name .name-title{
            width: 46%;
        }
        .receipent-name .name-title p{
            text-align: left !important;
        }
        @media only screen and (max-width: 767px) {
            .bg-img {
            width: 100% !important;
            height: 199px !important;
        }
        .boxone, .amount-box{
            max-width: 100% !important;
        }
        .boxone{
            height: 135px !important;
            margin-top: 12px !important;
        }
        .amount-box{
            padding: 34px 20px !important;
            min-height: 106px !important;
        }
        .main{
            padding: 0px 10px;
        }
        .boxone-inner{
            height: 50px !important;
            width: 50px !important;
            margin: 10px auto 15px !important;
        }
        .table .logo-img{
            padding-left: 0px !important;
        }

            
        }
    </style>
</head>
<body class="home-wrap">
    <div class="main">
        <div class="bg-img">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('vector_bg.png'))) }}" alt="vector bg">
        </div>
        <table class="table ">
            <tbody>
                <tr>
                    <td colspan="12" style="text-align: left;"><img class="logo-img" width="119" height="28" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('XSWorld.png'))) }}" style="object-fit: contain;" alt="XS World Logo"></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="12">
                        <h2 style="font-size:16px;font-weight: 700;text-align:center;color:#000;margin: 30px 0;" >{{$name}}, Here’s an XS gift from {{$senderName}}!</h2>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="12" style="display: flex;justify-content: center;align-items: center;width: 100%;">
                        <div class="boxone" style="border-radius: 12px; max-width: 308px; padding: 9px; color: #fff; width: 100%;">
                            <div style="display: flex;justify-content: space-between;align-items: center;">
                                <div ><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('xsworld_gift.png'))) }}" alt="XS World gift"></div>
                                <div>
                                    <p style="margin-bottom: 0;color: var(#FFF);text-align: right;font-size: 13px;font-style: normal;font-weight: 400;line-height: normal;">Gift Card</p>
                                </div>
                            </div>
                            <div style="display: flex;justify-content: center;align-items: center; height: 72px;width:72px; margin: 20px auto 15px;" class="boxone-inner" >
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('gold.png'))) }}"  style="width: 42px; height:49px;" alt="vector bg">
                            </div>
                            <div style="display: flex;align-items: center;" class="receipent-name">
                                <div class="name-title">
                                    <p style="margin: 0;margin-bottom: 0;color: var(#FFF);text-align: right;font-size: 12px;font-style: normal;font-weight: 400;line-height: normal;">{{$name}}</p>
                                </div>
                                <div>
                                    <p style="margin: 0;margin-bottom: 0;color: var(#FFF);text-align: right;font-size: 12px;font-style: normal;font-weight: 400;line-height: normal;">{{$amount}}</p>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="12" style="display: flex;justify-content: center;align-items: center;width: 100%;">
                        <div class="amount-box" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%); border-radius: 12px; max-width: 500px; padding:56px 5vw;color: #fff; width: 100%; min-height:137px">
                            <p style="text-align: center;font-size:24px; font-weight: 400;margin:0; color: #fff ;">{{$amount}}</p>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="12">
                        <p style="color: #000;text-align: center;font-size: 16px;font-style: normal;font-weight: 500;line-height: normal;margin-bottom: 0;">Already got the XSWorld App?</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" style="padding-bottom: 0;"><button style="background:linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%);padding:8px 24px;color:#fff; border-radius:12px;color: #EAC36C;text-align: center;font-size: 12px;font-style: normal;font-weight: 500;line-height: normal; border:unset;height: 37px;">Redeem Gift</button></td>
                </tr>
                <tr>
                    <td colspan="12">
                        <p style="color: #000;text-align: center;font-size: 12px;font-style: normal;font-weight: 500;line-height: normal; margin-bottom:0px;">Or use the code below to redeem the gift to your account</p>
                        <p style="color: #000;text-align: center;font-size: 12px;font-style: normal;font-weight: 500;line-height: normal; margin-bottom:0px;">Settings > Redeem a gift card</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="12"><button style="background:linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%);padding:8px 24px;color:#fff; border-radius:12px;color: #EAC36C;text-align: center;font-size: 12px;font-style: normal;font-weight: 500;line-height: normal; border:unset; min-width:194px;height:37px;">{{$code}}</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    </script>
</body>

</html>