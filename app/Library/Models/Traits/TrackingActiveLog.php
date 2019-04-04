<?php
namespace App\Library\Models\Traits;

use App\Library\Services\Jobs\WriteActiveLog;

trait TrackingActiveLog
{
    public static function bootTrackingActiveLog()
    {
        static::created(function ($model) {
            $sapi = php_sapi_name();
            if ($sapi == 'cli') {
                $module = config('constants.log.module.backend');
            } else {
                $module = !str_contains(app('request')->fullUrl(), 'backend') ? config('constants.log.module.frontend') : config('constants.log.module.backend');
            }

            $params = [
                'module' => $module,
                'type' => config('constants.log.type.insert'),
                'content' => [
                    'table' => $model->table,
                    'new' => $model->attributes,
                ],
                'ip_address' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent')
            ];
            if (auth()->check()) {
                $params['user_id'] = auth()->user()->id;
                $params['cookie_val'] = auth()->getSession()->getId();
            }

            // call log
            dispatch(new WriteActiveLog($params))->onQueue('log');
        });

        static::updated(function ($model) {
            $sapi = php_sapi_name();
            if ($sapi == 'cli') {
                $module = config('constants.log.module.backend');
            } else {
                $module = !str_contains(app('request')->fullUrl(), 'backend') ? config('constants.log.module.frontend') : config('constants.log.module.backend');
            }

            $params = [
                'module' => $module,
                'type' => config('constants.log.type.update'),
                'content' => [
                    'table' => $model->table,
                    'old' => array_diff_assoc($model->original, $model->attributes),
                    'new' => array_diff_assoc($model->attributes, $model->original),
                ],
                'ip_address' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent'),
            ];
            if (auth()->check()) {
                $params['user_id'] = auth()->user()->id;
                $params['cookie_val'] = auth()->getSession()->getId();
            }

            // call log
            dispatch(new WriteActiveLog($params))->onQueue('log');
        });

        static::deleted(function ($model) {
            $sapi = php_sapi_name();
            if ($sapi == 'cli') {
                $module = config('constants.log.module.backend');
            } else {
                $module = !str_contains(app('request')->fullUrl(), 'backend') ? config('constants.log.module.frontend') : config('constants.log.module.backend');
            }

            $params = [
                'module' => $module,
                'type' => config('constants.log.type.delete'),
                'content' => [
                    'table' => $model->table,
                    'old' => array_diff_assoc($model->original, $model->attributes),
                    'new' => array_diff_assoc($model->attributes, $model->original),
                ],
                'ip_address' => app('request')->getClientIp(),
                'user_agent' => app('request')->header('User-Agent'),
            ];
            if (auth()->check()) {
                $params['user_id'] = auth()->user()->id;
                $params['cookie_val'] = auth()->getSession()->getId();
            }

            // call log
            dispatch(new WriteActiveLog($params))->onQueue('log');
        });
    }
}
