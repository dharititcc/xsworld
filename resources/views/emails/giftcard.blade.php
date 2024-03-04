<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>giftcard email</title>
</head>

<body class="home-wrap" style="margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <div class="main" style="max-width: 1600px; margin: 40px auto; position:relative;">
        <img src="{{ asset('vector_bg.png')}}" alt="vector bg" style="height: 100%; width: 100%;position: absolute;top: 13rem;left: 50%;transform: translateX(-50%);height: 255px;width: 519px;z-index: 0; ">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: center;z-index: 1;
    position: relative;">
            <tbody>
                <tr>
                    <td colspan="12" style="text-align: left;"><img class="logo-img" width="119" height="28" src="{{ asset('XSWorld.png') }}" style="object-fit: contain; padding-left: 20px;" alt="XS World Logo"></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="12">
                        <h2 style="font-size: 16px; font-weight: 700; text-align: center; color: #000; margin: 30px 0;">{{$name}}, Here’s an XS gift from {{$senderName}}!</h2>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="12" style="display: flex; justify-content: center; align-items: center; width: 100%;">
                        <div class="boxone" style="border-radius: 12px; border: 0.25px solid  #805711; background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%), linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(254, 243, 179, 0.12) 100%); box-shadow: 0px 0px 17.2px -3px rgba(204, 178, 96, 0.28); backdrop-filter: blur(22px); height: 169px; margin: 50px auto 0; max-width: 308px; padding: 9px; color: #fff; width: 100%;">
                            <div style="display: flex;justify-content: space-between;align-items: center;">
                                <div style="width: 50%;text-align: left;"><img src="{{ asset('xsworld_gift.png') }}" alt="XS World gift"></div>
                                <div style="width: 50%;text-align: left;">
                                    <p style="margin-bottom: 0; color: #FFF; text-align: right; font-size: 13px; font-style: normal; font-weight: 400; line-height: normal;">Gift Card</p>
                                </div>
                            </div>
                            <div style="position: relative;border-radius: 12px;border: 0.25px solid #805711;background: linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(254, 243, 179, 0.12) 100%);box-shadow: 0px 0px 75.7px -28px #FEF3B3;display: flex;justify-content: center;align-items: center;height: 72px;width: 72px;margin: 20px auto 15px;" class="boxone-inner">
                                <img src="{{ asset('gold.png') }}" style="width: 42px; height: 49px;margin: auto;" alt="vector bg">
                            </div>
                            <div style="display: flex; align-items: center;gap:75px" class="receipent-name">
                                <div class="name-title" style="width: 46%;">
                                    <p style="margin: 0; margin-bottom: 0; color: #FFF; text-align: left; font-size: 12px; font-style: normal; font-weight: 400; line-height: normal;">{{$name}}</p>
                                </div>
                                <div>
                                    <p style="margin: 0; margin-bottom: 0; color: #FFF; text-align: right; font-size: 12px; font-style: normal; font-weight: 400; line-height: normal;">{{$amount}}</p>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="12" style="display: flex; justify-content: center; align-items: center; width: 100%; justify-content: center;">
                        <div class="amount-box" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%); border-radius: 12px; max-width: 500px; padding: 56px 5vw; color: #fff; width: 100%; min-height: 137px;margin: auto;">
                            <p style="text-align: center; font-size: 24px; font-weight: 400; margin: 0; color: #fff;">{{$amount}}</p>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="12">
                        <p style="color: #000; text-align: center; font-size: 16px; font-style: normal; font-weight: 500; line-height: normal; margin-bottom: 0;">Already got the XSWorld App?</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" style="padding-bottom: 0;"><button style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%); padding: 8px 24px; color: #fff; border-radius: 12px; color: #EAC36C; text-align: center; font-size: 12px; font-style: normal; font-weight: 500; line-height: normal; border: unset; height: 37px;">Redeem Gift</button></td>
                </tr>
                <tr>
                    <td colspan="12">
                        <p style="color: #000; text-align: center; font-size: 12px; font-style: normal; font-weight: 500; line-height: normal; margin-bottom: 0px;">Or use the code below to redeem the gift to your account</p>
                        <p style="color: #000; text-align: center; font-size: 12px; font-style: normal; font-weight: 500; line-height: normal; margin-bottom: 0px;">Settings > Redeem a gift card</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="12"><button style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.80) 100%); padding: 8px 24px; color: #fff; border-radius: 12px; color: #EAC36C; text-align: center; font-size: 12px; font-style: normal; font-weight: 500; line-height: normal; border: unset; min-width: 194px; height: 37px;">{{$code}}</button></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
