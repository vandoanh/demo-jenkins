<?php

namespace App\Library\Models\Traits;

use App\Library\Services\Jobs\PrecacheCategory;
use App\Library\Services\Jobs\PrecacheComment;
use App\Library\Services\Jobs\PrecacheNotice;
use App\Library\Services\Jobs\PrecachePost;
use App\Library\Services\Jobs\PrecacheTag;
use App\Library\Services\Jobs\PrecacheUser;

trait PreCache
{
    public static function bootPreCache()
    {
        static::created(function ($model) {
            $params = [
                'type' => config('constants.precache.type.create'),
                'id' => $model->attributes[$model->primaryKey],
            ];

            // call job update cache
            switch ($model->table) {
                case 'posts':
                    dispatch(new PrecachePost($params));
                    break;
                case 'categories':
                    dispatch(new PrecacheCategory($params));
                    break;
                case 'tags':
                    dispatch(new PrecacheTag($params));
                    break;
                case 'comments':
                    dispatch(new PrecacheComment($params));
                    break;
                case 'users':
                    dispatch(new PrecacheUser($params));
                    break;
                case 'notices':
                    dispatch(new PrecacheNotice($params));
                    break;
            }
        });

        static::updated(function ($model) {
            $params = [
                'type' => config('constants.precache.type.update'),
                'id' => $model->attributes[$model->primaryKey],
                'data' => $model->original,
            ];

            // call job update cache
            switch ($model->table) {
                case 'posts':
                    dispatch(new PrecachePost($params));
                    break;
                case 'categories':
                    dispatch(new PrecacheCategory($params));
                    break;
                case 'tags':
                    dispatch(new PrecacheTag($params));
                    break;
                case 'comments':
                    dispatch(new PrecacheComment($params));
                    break;
                case 'users':
                    dispatch(new PrecacheUser($params));
                    break;
                case 'notices':
                    dispatch(new PrecacheNotice($params));
                    break;
            }
        });

        static::deleted(function ($model) {
            $params = [
                'type' => config('constants.precache.type.delete'),
                'id' => $model->attributes[$model->primaryKey],
                'data' => $model->original,
            ];

            // call job update cache
            switch ($model->table) {
                case 'posts':
                    dispatch(new PrecachePost($params));

                    if (env('USED_ELASTICSEARCH_FLAG', false) == true) {
                        $model->removeFromIndex();
                    }
                    break;
                case 'categories':
                    dispatch(new PrecacheCategory($params));
                    break;
                case 'tags':
                    dispatch(newPrecacheTag($params));
                    break;
                case 'comments':
                    dispatch(new PrecacheComment($params));
                    break;
                case 'users':
                    dispatch(new PrecacheUser($params));
                    break;
                case 'notices':
                    dispatch(new PrecacheNotice($params));
                    break;
            }
        });
    }
}
