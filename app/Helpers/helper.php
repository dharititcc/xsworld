<?php

use Bavix\Wallet\Interfaces\Mathable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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

if (! function_exists('includeRouteFiles')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        $directory = $folder;
        $handle = opendir($directory);
        $directory_list = [$directory];

        while (false !== ($filename = readdir($handle))) {
            if ($filename != '.' && $filename != '..' && is_dir($directory.$filename)) {
                array_push($directory_list, $directory.$filename.'/');
            }
        }

        foreach ($directory_list as $directory) {
            foreach (glob($directory.'*.php') as $filename) {
                require $filename;
            }
        }
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

if (! function_exists('getRouteUrl')) {
    /**
     * Converts querystring params to array and use it as route params and returns URL.
     */
    function getRouteUrl($url, $url_type = 'route', $separator = '?')
    {
        $routeUrl = '';

        if (! empty($url)) {
            if ($url_type == 'route') {
                if (strpos($url, $separator) !== false) {
                    $urlArray = explode($separator, $url);
                    $url = $urlArray[0];
                    parse_str($urlArray[1], $params);
                    $routeUrl = route($url, $params);
                } else {
                    $routeUrl = route($url);
                }
            } else {
                $routeUrl = $url;
            }
        }

        return $routeUrl;
    }
}

if (! function_exists('renderMenuItems')) {
    /**
     * render sidebar menu items after permission check.
     */
    function renderMenuItems($items, $viewName = 'backend.includes.partials.sidebar-item')
    {
        foreach ($items as $item) {
            // if(!empty($item->url) && !Route::has($item->url)) {
            //     return;
            // }
            if (! empty($item->view_permission_id)) {
                if (access()->allow($item->view_permission_id)) {
                    echo view($viewName, compact('item'));
                }
            } else {
                echo view($viewName, compact('item'));
            }
        }
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

if (! function_exists('insert_into_array')) {
    function insert_into_array(&$array, array $keys, $value)
    {
        $last = array_pop($keys);
        foreach ($keys as $key) {
            if (! array_key_exists($key, $array) ||
                  array_key_exists($key, $array) && ! is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        if (is_array($array[$last])) {
            $array[$last] = array_merge($array[$last], $value);
        } else {
            $array[$last] = $value;
        }
    }
}

if (! function_exists('add_key_value_in_file')) {
    function add_key_value_in_file($file_name, $new_key_value, $parent_keys = null)
    {
        $file_array = eval(str_replace('<?php', '', str_replace('?>', '', @file_get_contents($file_name))));

        if (! empty($parent_keys)) {
            $parents = explode('.', $parent_keys);
            insert_into_array($file_array, $parents, $new_key_value);
        } else {
            foreach ($new_key_value as $key => $value) {
                $file_array[$key] = $value;
            }
        }

        $file_contents_new = "<?php\nreturn [\n";
        $file_contents_new .= get_array_contents($file_array);
        $file_contents_new .= '];';
        file_put_contents($file_name, $file_contents_new);
    }
}

if (! function_exists('get_array_contents')) {
    function get_array_contents($arr)
    {
        $contents = '';
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $contents .= "\t\"$key\" => [\n";
                $contents .= get_array_contents($value);
                $contents .= "\t],\n";
            } else {
                $contents .= "\t\"$key\" => \"$value\",\n";
            }
        }

        return $contents;
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

if (! function_exists('formatNumber')) {
    function formatNumber($number, int $decimalPlaces = 2)
    {
        return app(Mathable::class)->round($number, $decimalPlaces);
    }
}

/**
 * Ref - https://stackoverflow.com/questions/4116499/php-count-round-thousand-to-a-k-style-count-like-facebook-share-twitter-bu
 * Ref - https://code.recuweb.com/2018/php-format-numbers-to-nearest-thousands/
 */
if (! function_exists('formatVote')) {
    function formatVote($num) {

        if( $num > 999 ) {
    
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
            
            return $x_display;
        }
    
        return $num;
    }
}

if (! function_exists('ftxlog')) {
    /***
     * @param string $description
     * @param array $options {
     *     Optional. Array of Option parameters.
     *
     *     @type string       tag           Tag with which log should be made, default: config('activitylog.default_log_name')
     *     @type Object       model         Model instance/Class name on which changes are being performed, default: null
     *     @type Object       user          Model instance of user which performed/caused this activity, default: Current user
     *     @type Object       created_at    Carbon instance of time on which happened, default: now
     *     @type Array        data          Array of key values which should be stored with log, default: []
     * }
     */
    function ftxlog($description='',array $options = [])
    {
        $defaults = [
            'tag' =>  config('activitylog.default_log_name'),
            'model' => null,
            'user'  => (auth()->user()) ?? null,
            'created_at' => now(),
            'data' => [],
        ];
        $value = array_merge($defaults, $options);

        try{

            $activity = activity($value['tag']);
            if(!empty($value['model'])){
                $class = $value['model'];
                if(is_string($value['model'])){
                    $class = new $value['model'];
                }
                $activity->performedOn($class);
            }
            if(!empty($value['user'])){
                $activity->causedBy($value['user']);
            }
            if(!empty($value['created_at'])){
                $date = Carbon::createFromFormat('Y-m-d H:i:s',$value['created_at']->format('Y-m-d H:i:s'));
                $activity->createdAt($date);
            }
            if(!empty($value['data']) && is_array($value['data']) && count($value['data'])){
                $activity->withProperties($value['data']);
            }
            $activity->log($description);

            return true;
        }catch(Exception $ex){
            // Unable to encode attribute [properties] for model [Spatie\\Activitylog\\Models\\Activity] to JSON: Malformed UTF-8 characters, possibly incorrectly encoded.
            /* Log::critical('activity logger', [
                'error' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'values' => $value
            ]); */
        }
        return false;
    }
}

if (! function_exists('convertArrayToTableRecursive')) {
    function convertArrayToTableRecursive($value,$html = '')
    {
        if(is_array($value)){
            $html .= '<table class="table">
                <tbody>';
                foreach ($value as $key => $v){
                $html .= '<tr>
                        <th>'.$key.'</th>
                        <td>'.convertArrayToTableRecursive($v).'</td>
                    </tr>';
                }
                $html .= '</tbody>
            </table>';
            return $html;
        }else{
            return $value;
        }
    }
}

if (! function_exists('addPaymentLog')) {
    function addPaymentLog($message, array $context = [])
    {
        \Log::channel('paymentLog')->debug($message, $context);
    }
}

if (! function_exists('encryptEloquent')) {
    function encryptEloquent($value, $serialize = true)
    {
        return app('encryptEloquent')->encrypt($value, $serialize);
    }
}

if (! function_exists('decryptEloquent')) {
    function decryptEloquent($value, $unserialize = true)
    {
        return app('encryptEloquent')->decrypt($value, $unserialize);
    }
}

if (! function_exists('getPaymentGatewayChargesFromResponse')) {
    function getPaymentGatewayChargesFromResponse($paymentGatewayResponse)
    {
        $charge = null;

        if (
            isset($paymentGatewayResponse['balance_transaction'])
            && isset($paymentGatewayResponse['balance_transaction']['fee'])
        ) {
            // For Stripe
            $charge = $paymentGatewayResponse['balance_transaction']['fee'] / 100;
        } else if (
            !empty($paymentGatewayResponse['paypal_charge'])
        ) {
            // For PayPal
            $charge = $paymentGatewayResponse['paypal_charge'];
        }

        return $charge;
    }
}