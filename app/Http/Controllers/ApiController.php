<?php

namespace App\Http\Controllers;

use App\Library\Services\Auth\AuthServiceContract;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use GuzzleHttp\Client;
use App\Library\Models\MySql\OauthClient;
use Illuminate\Support\Facades\Log;

class ApiController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $authService;
    protected $guzzleHttpClient;
    protected $model;

    public function __construct(Request $request, AuthServiceContract $authService)
    {
        $this->authService = $authService;

        $this->guzzleHttpClient = new Client();
        $this->model = OauthClient::instance()->getById(config('site.api.auth.grant_client_id'));
    }

    public function logError(Request $request, \Exception $ex)
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $bodyAsJson = json_encode($request->all());

        $message = "[Message] {$ex->getMessage()} - Method: {$method} - URI: {$uri} - IP: {$ip} - User Agent: {$user_agent} - Body: {$bodyAsJson}\r\n[Stack trace] {$ex->getTraceAsString()}";

        Log::error($message);
    }
}
