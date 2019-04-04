<?php

namespace App\Library\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use App\Library\Services\Jobs\PrecacheImage;
use App\Library\Models\NotificationSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * This is class write methods common
 *
 * @author TienDQ
 * When using need : use App\Library\Services\CommonService;
 */
class CommonService
{
    private static $_redis_instance = [];

    /**
     * Get redis connection
     * @param string $connection
     * @return object
     * @author TienDQ
     */
    public static function getRedis($connection = 'default')
    {
        //Check instance
        if (!array_key_exists($connection, static::$_redis_instance)) {
            static::$_redis_instance[$connection] = Redis::connection($connection);
        }

        return self::$_redis_instance[$connection];
    }

    /**
     * Close redis storage
     * @author TienDQ
     */
    public static function closeRedis()
    {
        //check not emtpy
        if (!empty(static::$_redis_instance)) {
            //loop redis ins
            foreach (static::$_redis_instance as $connection) {
                //close redis
                $connection->close();
            }
        }

        //reset var
        static::$_redis_instance = [];
    }

    /**
     * @param $key
     * @return $key
     */
    public static function makeCacheKey($key, $args = null)
    {
        if (empty($args)) {
            return $key;
        }

        return vsprintf($key, $args);
    }

    /**
     * make crawler
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array  $options
     * @param array  $headers
     * @return object Symfony\Component\DomCrawler\Crawler
     * @author TienDQ
     */
    public static function makeCrawler($url, $method = 'get', $params = [], $options = [], $setHeaders = [])
    {
        try {
            $cookieJar = null;
            if (isset($options['cookies']) && count($options['cookies']) > 0) {
                if ($options['cookies'] instanceof CookieJar) {
                    $cookieJar = $options['cookies'];
                }

                unset($options['cookies']);
            }

            $client = new Client([], null, $cookieJar);

            if (!empty($setHeaders)) {
                foreach ($setHeaders as $key => $setHeader) {
                    $client = $client->setHeader($key, $setHeader);
                }
            }
            $crawler = $client->request($method, trim($url), $params);

            $response = $client->getResponse();
            if ($response->getStatus() != 200) {
                $content = self::getContent($url, $method, $params, $options);

                if ($content) {
                    $crawler = new Crawler(null, $url);
                    $crawler->addContent($content);
                }
            }

            return ['client' => $client, 'crawler' => $crawler];
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return null;
        }
    }

    /**
     * get content cross domain
     *
     * @param  <string> $url
     * @param  <string> $method
     * @param  <array> $params
     * @param  <array> $options
     * @return <string>
     * @author TienDQ
     */
    public static function getContent($url, $method = 'get', $params = [], $options = [])
    {
        $content = '';
        $url = trim($url);
        $method = strtolower($method);

        $options = $options + [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 60,
        ];

        try {
            if (function_exists('curl_init')) {
                $ch = curl_init();

                if ($method == 'post') {
                    $options[CURLOPT_POST] = 1;
                    if (!empty($params)) {
                        $options[CURLOPT_POSTFIELDS] = http_build_query($params);
                    }
                }

                if (starts_with($url, 'https')) {
                    $options[CURLOPT_SSL_VERIFYPEER] = 0;
                    $options[CURLOPT_SSL_VERIFYHOST] = 0;
                }

                curl_setopt_array($ch, array_wrap($options));
                $content = curl_exec($ch);
                if (curl_error($ch)) {
                    Log::error(curl_error($ch));
                }
                curl_close($ch);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        return $content;
    }

    public static function downloadFileCurl($url, $pathStorage, $newFile)
    {
        $fileTemp = $pathStorage . $newFile;
        $ch = curl_init(trim($url));
        $fp = fopen($fileTemp, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        if (file_exists($fileTemp)) {
            return $fileTemp;
        }

        return false;
    }

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     *
     * @param array $data
     *
     * @return string
     */
    public static function encrypt($data)
    {
        $encrypt_method = config('site.general.encrypt.method');
        $secret_key = config('site.general.encrypt.secret_key');
        $secret_iv = config('site.general.encrypt.secret_iv');

        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $string = json_encode($data);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     *
     * @param string $data
     *
     * @return string
     */
    public static function decrypt($data)
    {
        $encrypt_method = config('site.general.encrypt.method');
        $secret_key = config('site.general.encrypt.secret_key');
        $secret_iv = config('site.general.encrypt.secret_iv');

        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);
        $output = json_decode($output);

        return $output;
    }

    public static function saveImageFromUrl($url, $type, $save = false, $returnUrl = false)
    {
        $response = static::getContent(config('site.media.url.upload.url') . '/' . $type, 'post', ['url' => $url]);

        if ($response) {
            $response = json_decode($response, true);

            if ($response['error'] == 0) {
                if ($save) {
                    return static::saveImageFromTmp($response['filename'], $type);
                }

                return $returnUrl ? image_url($response['filename']) : $response['filename'];
            }
        }

        return '';
    }

    public static function saveImageFromTmp($fileName, $type, $returnUrl = false)
    {
        $response = static::getContent(config('site.media.url.upload.save') . '/' . $type . '/' . $fileName, 'get');

        if ($response) {
            $response = json_decode($response, true);

            if ($response['error'] == 0) {
                return $returnUrl ? image_url($response['filename']) : $response['filename'];
            }
        }

        return '';
    }

    public static function saveImageFromFile($file, $fileName, $type, $returnUrl = true)
    {
        $disk = Storage::disk(config('site.media.storage'));
        $type = strtolower($type);

        if ($type === 'image') {
            $dateYear = date('Y');
            $dateMonth = date('m');
            $dateDay = date('d');
            $filePath = '/' . $dateYear . '/' . $dateMonth . '/' . $dateDay . '/';
        } else {
            $filePath = '/';
        }

        $disk->putFileAs(config('site.media.path') . '/' . $type . $filePath, $file, $fileName);

        if ($returnUrl) {
            return image_url($filePath . $fileName);
        }

        return $filePath . $fileName;
    }

    public static function randomInt()
    {
        return str_replace('.', '', microtime(true));
    }

    public static function makeBeautyIp($arrListIp)
    {
        $arrReturn = [];

        foreach ($arrListIp as $ip) {
            $start = ip2long(preg_replace('/\*/', '1', $ip));
            $end = ip2long(preg_replace('/\*/', '255', $ip));

            $arrReturn = array_merge($arrReturn, array_map('long2ip', range($start, $end)));
        }

        return array_unique($arrReturn);
    }

    public static function logError($request, $exception)
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $bodyAsJson = json_encode($request->all());

        $message = "[Message] {$exception->getMessage()} - Method: {$method} - URI: {$uri} - IP: {$ip} - User Agent: {$user_agent} - Body: {$bodyAsJson}\r\n[Stack trace] {$exception->getTraceAsString()}";

        Log::error($message);
    }

    /**
     * paginate custom
     *
     * @param mixed $items
     * @param int $perPage
     * @param int $intPage
     * @return Paginator | Collection | Array
     */
    public static function doPaginate($items, $perPage, $intPage = null)
    {
        if ($perPage == 0) {
            $perPage = 1000000000000;
        }

        $intPage = $intPage ? $intPage : (LengthAwarePaginator::resolveCurrentPage() ?: 1);

        return new LengthAwarePaginator($items->forPage($intPage, $perPage), $items->count(), $perPage, $intPage);
    }



    public static function setErrorApi($message = null)
    {
        $arrErrors = [
            'status' => 0,
            'message' => !empty($message) ? $message : 'Internal Error.',
            'data' => [],
        ];

        return response()->json($arrErrors);
    }

    public static function setSuccessApi($data = [])
    {
        $return = [
            'status' => 1,
            'message' => 'Success',
            'data' => $data,
        ];

        return response()->json($return);
    }

    public static function processImageContent($content)
    {
        $arrValidExt = config('site.media.type.image');

        // Get disk storage
        $disk = Storage::disk(config('site.media.storage'));
        $typeName = 'image';

        //get current date to make directory
        $date_year = date('Y');
        $date_month = date('m');
        $date_day = date('d');

        preg_match_all('/<img(.[^>]*)src="(.[^>]*)"(.[^>]*)(\/)?>/', $content, $matches);
        if (empty($matches)) {
            return $content;
        }

        $arrSrcImage = $matches[2];
        $arrPrecacheImage = [];

        foreach ($arrSrcImage as $src) {
            if (!starts_with($src, config('site.media.url.image'))) {
                //save file to new path
                $image = self::saveImageFromUrl($src, config('constants.image.default.folder'), true, true);

                //save file to new path
                if ($image) {
                    $arrPrecacheImage[] = $image;
                    $image = image_url($image);

                    if (strpos($src, '?')) {
                        $src = str_replace('&', '&amp;', $src);
                    } else {
                        $src = urldecode($src);
                    }

                    //update new url to content of article
                    $content = str_replace($src, $image, $content);

                    $srcEnCode = str_replace(array('(', ')', ' '), array('%28', '%29', '%20'), $src);
                    $content = str_replace($srcEnCode, $image, $content);

                    $srcEnCode = str_replace(array('[', ']'), array('%5B', '%5D'), $srcEnCode);
                    $content = str_replace($srcEnCode, $image, $content);

                    $srcEnCode = str_replace(array('@'), array('%40'), $srcEnCode);
                    $content = str_replace($srcEnCode, $image, $content);
                }
            }
        }

        //run job pre-cache image
        if (!empty($arrPrecacheImage)) {
            dispatch(new PrecacheImage([
                'images' => $arrPrecacheImage
            ]))->onQueue('image');
        }

        return $content;
    }

    public static function sendNotification($params)
    {
        try {
            $params = array_merge([
                'user_id' => null,
                'data' => [
                    'title' => 'Test send notification!',
                    'message' => 'You have received a push message.',
                    'url' => config('site.notification.vapid.subject'),
                ],
            ], $params);

            $auth = [
                'VAPID' => [
                    'subject' => config('site.notification.vapid.subject'),
                    'publicKey' => config('site.notification.vapid.public_key'),
                    'privateKey' => config('site.notification.vapid.private_key'),
                ],
            ];

            // get all subscription
            $arrSubscription = NotificationSubscription::instance()->getSubscriptions($params['user_id']);
            $webPush = new WebPush($auth);

            foreach ($arrSubscription as $subscription) {
                $webPush->sendNotification(
                    Subscription::create([
                        'endpoint' => $subscription->endpoint,
                        'publicKey' => $subscription->public_key,
                        'authToken' => $subscription->auth_token,
                        'contentEncoding' => $subscription->content_encoding,
                    ]),
                    json_encode($params['data'])
                );
            }

            $webPush->flush();

            return true;
        } catch (\Exception $ex) {
            static::logError(app('request'), $ex);

            return false;
        }
    }

    public static function sendChatwork($params = [])
    {
    }
}
