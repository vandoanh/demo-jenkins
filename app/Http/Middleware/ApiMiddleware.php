<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\UploadedFile;
use App\Library\Services\Jobs\WriteApiLog;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->logs($request, $response);

        return $response;
    }

    private function logs($request, $response)
    {
        $params = [
            'url_request' => $request->getPathInfo(),
            'method' => strtolower($request->getMethod()),
            'data_request' => [],
            'data_response' => null,
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->userAgent(),
        ];
        foreach ($request->except(['password', 'password_confirmation', 'password_confirm']) as $key => $item) {
            if ($item instanceof UploadedFile) {
                $params['data_request'][$key] = (array)$item;
            } else {
                $params['data_request'][$key] = $item;
            }
        }
        $params['data_request'] = json_encode($params['data_request']);
        $params['data_response'] = '';

        if (method_exists($response, 'getData')) {
            $params['data_response'] = json_encode((array)$response->getData());
        }

        dispatch(new WriteApiLog($params))->onQueue('log');
    }
}
