<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'cors'], function () {
    //api for authentication
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('refresh-token', 'AuthController@refreshToken');
        Route::post('register', 'AuthController@register');
        Route::post('forgot-password', 'AuthController@forgotPassword');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('logout', 'UserController@logout');
            Route::get('info', 'UserController@getUserInfo');
            Route::post('update-info', 'UserController@updateUserInfo');
            Route::post('update-password', 'UserController@updatePassword');
            Route::post('upload-avatar', 'UserController@uploadAvatar');
        });
    });
});
