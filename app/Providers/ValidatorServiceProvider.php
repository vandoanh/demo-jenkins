<?php

namespace App\Providers;

use App\Library\Services\CommonService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /**
         * extend validator
         */
        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $value);
        }, 'Your password is not strong enough.');

        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            $response = CommonService::getContent(config('site.captcha.verify_link'), 'post', [
                'secret' => config('site.captcha.secret_key'),
                'response' => $value
            ]);

            $response = json_decode($response);
            if (!empty($response) && $response->success) {
                return true;
            }

            return false;
        }, 'Your captcha is invalid.');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        return;
    }
}
