<?php

namespace App\Library\Services\Crawler;

use App\Library\Services\CommonService;
use Carbon\Carbon;
use Verdant\XML2Array;

/**
 * Crawler service class
 *
 * @author TienDQ
 */
class CrawlerService
{
    protected $crawlerLogService;

    public $params = [];
    public $headers = [];
    public $curlOptions = [];
    public $cookies = [];

    public function __construct()
    {
        $this->crawlerLogService = new CrawlerLogService();
    }

    public function getData($url, $method = 'get', $params = [], $type = 'html')
    {
        $method = strtolower($method);
        $type = strtolower($type);

        //call api
        if ($method === 'get') {
            $url = $url . '?' . http_build_query($params);
        }

        $this->crawlerLogService->writeLog(['URL' => $url, 'start' => Carbon::now()->toDateTimeString(), 'function' => __FUNCTION__, 'request_data' => $params], 'Start get data crawler');

        if ($type !== 'html') {
            $response = CommonService::getContent($url, $method, $params, $this->curlOptions);
        } else {
            $options = array_merge($this->curlOptions, ['cookies' => $this->cookies]);
            $response = CommonService::makeCrawler($url, $method, $params, $options, $this->headers);
        }

        $this->crawlerLogService->writeLog(['URL' => $url, 'end' => Carbon::now()->toDateTimeString(), 'function' => __FUNCTION__, 'response_data' => $response], 'End get data crawler');

        if ($response !== false && !empty($response)) {
            switch ($type) {
                case 'xml':
                    $response = XML2Array::createArray($response, [
                        'attributesKey' => 'attributes',
                        'cdataKey' => 'cdata',
                        'valueKey' => 'value',
                    ]);
                    break;
                case 'json':
                    if (0 === strpos(bin2hex($response), 'efbbbf')) {
                        $response = substr($response, 3);
                    }
                    $response = json_decode($response, true);
                    break;
                case 'html':
                default:
                    break;
            }
        } else {
            $response = [];
        }

        return $response;
    }
}
