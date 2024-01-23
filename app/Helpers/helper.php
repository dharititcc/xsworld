<?php

use App\Exceptions\GeneralException;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

/**
 * Henerate UUID.
 *
 * @return uuid
 */
function generateUuid()
{
    return Str::uuid();
}

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        return 'dashboard';
    }
}

// Global helpers file with misc functions.
if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('common')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function common()
    {
        return app('common');
    }
}

if (! function_exists('history')) {
    /**
     * Access the history facade anywhere.
     */
    function history()
    {
        return app('history');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('getRtlCss')) {
    /**
     * The path being passed is generated by Laravel Mix manifest file
     * The webpack plugin takes the css filenames and appends rtl before the .css extension
     * So we take the original and place that in and send back the path.
     *
     * @param $path
     *
     * @return string
     */
    function getRtlCss($path)
    {
        $path = explode('/', $path);
        $filename = end($path);
        array_pop($path);
        $filename = rtrim($filename, '.css');

        return implode('/', $path).'/'.$filename.'.rtl.css';
    }
}

if (! function_exists('escapeSlashes')) {
    /**
     * Access the escapeSlashes helper.
     */
    function escapeSlashes($path)
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('//', DIRECTORY_SEPARATOR, $path);
        $path = trim($path, DIRECTORY_SEPARATOR);

        return $path;
    }
}

if (! function_exists('checkDatabaseConnection')) {
    /**
     * @return bool
     */
    function checkDatabaseConnection()
    {
        try {
            DB::connection()->reconnect();

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (! function_exists('getShortDescription')) {
    // Don't change the default value
    function getShortDescription(?string $text, bool $lineBreak = true, int $length = 500): ?string
    {
        if (empty($text)) {
            return $text;
        }

        $text = mb_strlen($text) > $length
            ? mb_substr($text, 0, $length - 3) . '...'
            : $text;

        return $lineBreak ? getLineBreakOnly($text) : $text;
    }
}

if (! function_exists('getLineBreakOnly')) {
    function getLineBreakOnly(?string $text): ?string
    {
        if (empty($text)) {
            return $text;
        }

        return nl2br(e($text));
    }
}

if (! function_exists('displayViewMore')) {
    // Don't change the default value
    function displayViewMore(?string $text, int $length = 500): bool
    {
        if (empty($text)) {
            return false;
        }

        return mb_strlen($text) > $length;
    }
}

if (! function_exists('urlWithScheme')) {
    function urlWithScheme(?string $url, bool $https = false): ?string
    {
        if (empty($url)) {
            return null;
        }

        $urlInfo = parse_url($url);

        if (!isset($urlInfo['scheme'])) {
            if ($https) {
                $url = 'https://' . $url;
            } else {
                $url = 'http://' . $url;
            }
        }

        return $url;
    }
}

if (! function_exists('generateUniqueModelSlug')) {
    /**
     * Generate Unique Model Slug
     *
     * @param Model $model
     * @param string $slugAttribute
     * @param string $slugField
     * @param int $counter
     * @return string
     */
    function generateUniqueModelSlug(
        Model $model, // Model instance
        string $slugAttribute = 'title', // Accessor or attribute which will be used to identify value
        string $slugField = 'slug', // DB field used to check unique value in the database
        int $counter = 1 // Suffix number
    ): string
    {
        $nameSlug = Str::slug($model->{$slugAttribute});

        // Apply counter initially
        $slug = $nameSlug . '-' . $counter;

        // Fetch all slugs
        $allSlugs = $model->select($slugField)->withTrashed()->pluck($slugField)->toArray();

        // Check in array instead of checking in database to improve the performance
        while (in_array($slug, $allSlugs)) {
            $slug = $nameSlug . '-' . $counter;

            $counter++;
        }

        // Do final check in the DB
        $exists = $model->withTrashed()->where($slugField, $slug)->exists();

        if (!$exists) {
            return $slug;
        }

        // Recursive call to the function unless a unique counter is generated
        return generateUniqueModelSlug($model, $slugAttribute, $slugField, $counter);
    }
}

if (! function_exists('sendTwilioCustomerSms')) {
    /**
     * Method sendTwilioCustomerSms
     *
     * @param $mobile_no $mobile_no [explicite description]
     * @param $otp $otp [explicite description]
     *
     * @return void
     */
    function sendTwilioCustomerSms($mobile_no,$otp)
    {
        try {
            $token          = env("TWILIO_AUTH_TOKEN");
            $twilio_sid     = env("TWILIO_ACCOUNT_SID");
            $twilio_number  = env("TWILIO_PHONE_NUMBER");
            $send_sms       = new Client($twilio_sid, $token);

            $data = $send_sms->messages->create(
                // Where to send a text message (your cell phone?)
                $mobile_no,
                array(
                    'from' => $twilio_number,
                    'body' => 'Your One Time Password for verify account '.$otp
                ),
                );
            return 'send sms successfully';
        } catch (Exception $e) {
            // return $this->respondWithError($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }
}

if (! function_exists('generateNumericOTP')) {
    // Function to generate OTP
    /**
     * Method generateNumericOTP
     *
     * @param $n $n [explicite description]
     *
     * @return mixed
     */
    function generateNumericOTP($n)
    {
        // Taking a generator string that consists of
        // all the numeric digits
        $generator = "1357902468";

        // Iterating for n-times and pick a single character
        // from generator and append it to $result

        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, rand() % strlen($generator), 1);
        }

        // Returning the result
        return $result;
    }
}

if (! function_exists('sendNotification')) {
    /**
     * Method sendNotification
     *
     * @param String $title [explicite description]
     * @param String $message [explicite description]
     * @param array $tokens [explicite description]
     * @param int $orderid [explicite description]
     *
     * @return mixed
     */
    function sendNotification(String $title, String $message, array $tokens, int $orderid)
    {
        if( !empty( $tokens ) )
        {
            try {
                $accesstoken = getenv("FCM_TOKEN");
                $URL = 'https://fcm.googleapis.com/fcm/send';

                $notification = [
                    'title'                 =>  $title,
                    'body'                  =>  $message,
                    'message'               =>  $message,
                    'icon'                  =>  'myIcon',
                    'sound'                 => 'mySound',
                    // 'notification_type'  => $type,
                    'image'                 =>'',
                    'order_id'              => $orderid,
                    // "click_action"          => (string)$orderid
                ];

                $newArray = array_merge($notification, ["click_action" => (string)$orderid]);

                $post_data = [
                    'registration_ids'    => $tokens, //multple token array
                    // 'to'                    => $tokens, //single token
                    'notification'          => $notification,
                    'data'                  => $newArray
                ];

                $crl = curl_init();

                $headr = array();
                $headr[] = 'Content-type: application/json';
                $headr[] = 'Authorization: Bearer ' . $accesstoken;
                curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($crl, CURLOPT_URL, $URL);
                curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

                curl_setopt($crl, CURLOPT_POST, true);
                curl_setopt($crl, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

                $rest = curl_exec($crl);
                // dd($rest);
                Log::debug("Notification Testing Helper Log:  - {$rest}");
                return true;
            } catch (Exception $e) {
                throw new GeneralException($e->getMessage());
            }
        }

    }
}

if (! function_exists('waiterNotification')) {
    /**
     * Method waiterNotification
     *
     * @param String $title [explicite description]
     * @param String $message [explicite description]
     * @param array $tokens [explicite description]
     * @param int $orderid [explicite description]
     * @param int $orderid [explicite description]
     *
     * @return mixed
     */
    function waiterNotification(String $title, String $message, array $tokens, int $code, int $orderid)
    {
        if( !empty( $tokens ) )
        {
            Log::debug("Waiter Notification Testing:  - {$message}");
            try {
                $accesstoken = getenv("FCM_TOKEN");
                $URL = 'https://fcm.googleapis.com/fcm/send';

                $notification = [
                    'title'                 =>  $title,
                    'body'                  =>  $message,
                    'message'               =>  $message,
                    'icon'                  =>  'myIcon',
                    'sound'                 => 'mySound',
                    'image'                 =>'',
                    'order_id'              => $orderid,
                ];

                $newArray = array_merge($notification, ["click_action" => $code]);

                $post_data = [
                    'registration_ids'    => $tokens, //multple token array
                    // 'to'                    => $tokens, //single token
                    'notification'          => $notification,
                    'data'                  => $newArray
                ];

                $crl = curl_init();

                $headr = array();
                $headr[] = 'Content-type: application/json';
                $headr[] = 'Authorization: Bearer ' . $accesstoken;
                curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($crl, CURLOPT_URL, $URL);
                curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

                curl_setopt($crl, CURLOPT_POST, true);
                curl_setopt($crl, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

                $rest = curl_exec($crl);
                // dd($rest);
                Log::debug("Waiter Notification Log Helper:  - {$rest}");

                return true;
            } catch (Exception $e) {
                throw new GeneralException($e->getMessage());
            }
        }
    }
}

if (! function_exists('cate_name')) {
    function cate_name(int $cate_id)
    {
        $category       = Category::find($cate_id);
        $category_name  = '';
        if(isset($category->id))
        {
            $category_name = $category->name;
        }
        return $category_name;
    }
}

if (! function_exists('get_previous_quarter')) {
    function get_previous_quarter()
    {
        $current = Carbon::now();

        // check month and year in quarter
        $getPreviousQuarter = $current->month($current->month-3);

        return [
            'start_date'=> $getPreviousQuarter->firstOfQuarter()->format('Y-m-d'),
            'end_date'  => $getPreviousQuarter->lastOfQuarter()->format('Y-m-d'),
        ];
    }
}

if( ! function_exists('get_dates_period') )
{
    /**
     * Method get_dates_period
     *
     * @param string $startDate [explicite description]
     * @param string $endDate [explicite description]
     *
     * @return array
     */
    function get_dates_period($startDate, $endDate): array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        return $period->toArray();
    }
}

if (! function_exists('get_current_quarter')) {
    function get_current_quarter()
    {
        $current = Carbon::now();

        return [
            'start_date'=> $current->firstOfQuarter()->format('Y-m-d'),
            'end_date'  => $current->lastOfQuarter()->format('Y-m-d'),
        ];
    }
}

if (! function_exists('referralCode')) {
    function referralCode($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('addressLatLong')) {
    function addressLatLong($address,$isLatLong = 0,$formatted_latlng=0) {
        $GOOGLE_API_KEY = env('GOOGLE_API_KEY');
        $key = 0;

        if($isLatLong === 1) {
            // Get geo data from Google Maps API by lat lng
            $geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$formatted_latlng}&key={$GOOGLE_API_KEY}");
            $key = 7;
            // Decode JSON data returned by API
            $apiResponse = json_decode($geocodeFromAddr);
            if(empty($apiResponse->results))
            {
                throw new GeneralException('Please Enter Proper Address');
            } else {
                // Retrieve latitude and longitude from API data
                $latitude  = $apiResponse->results[0]->geometry->location->lat;
                $longitude = $apiResponse->results[0]->geometry->location->lng;
                // $country   = $apiResponse->results[0]->address_components[$key]->long_name;

                foreach($apiResponse->results[0]->address_components as $countryName)
                {
                    // dd($apiResponse->results[0]->address_components);
                    if(strlen($countryName->short_name) == 2)
                    {
                        foreach($countryName->types as $type)
                        {
                            if($type == "country")
                            {
                                $country   = $countryName->long_name;
                                break;
                            }
                        }
                    }
                }
                $latlong = [
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                    'country'   => $country
                ];
                return $latlong;
            }
        } else {
            // Formatted address
            $formatted_address = str_replace(' ', '+', $address);
            // Get geo data from Google Maps API by address
            $geocodeFromAddress = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$formatted_address}&key={$GOOGLE_API_KEY}");
            // Decode JSON data returned by API
            $apiResponse = json_decode($geocodeFromAddress);

            if(empty($apiResponse->results))
            {
                throw new GeneralException('Please Enter Proper Address');
            } else {
                // Retrieve latitude and longitude from API data
                $latitude  = $apiResponse->results[0]->geometry->location->lat;
                $longitude = $apiResponse->results[0]->geometry->location->lng;
                foreach($apiResponse->results[0]->address_components as $countryName)
                {
                    // dd($apiResponse->results[0]->address_components);
                    if(strlen($countryName->short_name) == 2)
                    {
                        foreach($countryName->types as $type)
                        {
                            if($type == "country")
                            {
                                $country   = $countryName->long_name;
                                break;
                            }
                        }
                    }
                }
                $latlong = [
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                    'country'   => $country
                ];
                return $latlong;
            }
        }
    }
}