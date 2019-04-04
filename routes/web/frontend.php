<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['namespace' => 'Frontend', 'prefix' => LaravelLocalization::setLocale()], function () {
    Route::patterns([
        'code' => '[A-Za-z0-9\-]+',
        'id' => '[0-9]+',
    ]);

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::group(['prefix' => 'login'], function () {
            Route::get('/', ['as' => 'auth.login', 'uses' => 'LoginController@showLoginForm']);
            Route::post('/', ['as' => 'auth.login.post', 'uses' => 'LoginController@processLogin']);
        });
        Route::get('/logout', ['as' => 'auth.logout', 'middleware' => ['web'], 'uses' => 'LoginController@logout']);
        Route::get('/message', ['as' => 'auth.message', 'uses' => 'LoginController@message']);

        Route::group(['prefix' => 'register'], function () {
            Route::get('/', ['as' => 'auth.register', 'uses' => 'RegisterController@showRegistrationForm']);
            Route::post('/', ['as' => 'auth.register.post', 'uses' => 'RegisterController@processRegister']);
        });

        Route::group(['prefix' => 'forgot-password'], function () {
            Route::get('/', ['as' => 'auth.forgot-password', 'uses' => 'ForgotPasswordController@forgotPass']);
            Route::post('/', ['as' => 'auth.forgot-password.post', 'uses' => 'ForgotPasswordController@processForgotPass']);
        });

        Route::group(['prefix' => 'reset-password'], function () {
            Route::get('/{token}', ['as' => 'auth.reset-password', 'uses' => 'ResetPasswordController@showResetForm']);
            Route::post('/{token}', ['as' => 'auth.reset-password.post', 'uses' => 'ResetPasswordController@processResetPass']);
        });

        Route::group(['prefix' => 'verify'], function () {
            Route::get('/{token}', ['as' => 'auth.verify', 'uses' => 'VerificationController@verify']);
            Route::get('/', ['as' => 'auth.verify.resend', 'uses' => 'VerificationController@resend']);
        });

        Route::group(['prefix' => 'social'], function () {
            Route::get('/login/{provider}', ['as' => 'auth.social.login', 'uses' => 'SocialController@login']);
            Route::get('/handle/{provider}', ['as' => 'auth.social.handle', 'uses' => 'SocialController@handle']);
        });
    });

    Route::get('/', ['as' => 'home', 'uses' => 'IndexController@dashboard']);

    Route::group(['prefix' => 'interaction'], function () {
        Route::get('/get-widget', ['as' => 'interaction.widget', 'uses' => 'InteractionController@getWidget']);
        Route::get('/get-comment', ['as' => 'interaction.comment', 'uses' => 'InteractionController@getComment']);
        Route::post('/post-comment', ['as' => 'interaction.comment.post', 'uses' => 'InteractionController@postComment']);
        Route::post('/update-view', ['as' => 'interaction.post.view', 'uses' => 'InteractionController@updateView']);
        Route::post('/update-comment-like', ['as' => 'interaction.comment.like', 'uses' => 'InteractionController@updateLikeComment']);
    });

    //chat
    Route::group(['prefix' => 'chat'], function () {
        Route::get('/', ['as' => 'chat.index', 'uses' => 'ChatController@index']);
        Route::post('/send', ['as' => 'chat.send', 'uses' => 'ChatController@send']);
    });

    //notification
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/subscribe', ['as' => 'notification.subscribe', 'uses' => 'NotificationController@subscribe']);
        Route::get('/unsubscribe', ['as' => 'notification.unsubscribe', 'uses' => 'NotificationController@unsubscribe']);
    });
    Route::group(['prefix' => 'notice'], function () {
        Route::get('/', ['as' => 'notice.index', 'uses' => 'NoticeController@index']);
        Route::get('/{id}', ['as' => 'notice.detail', 'uses' => 'NoticeController@detail']);
    });

    Route::get('/search', ['as' => 'post.search', 'uses' => 'PostController@search']);
    Route::get('/tag/{code}-{id}', ['as' => 'post.tag', 'uses' => 'PostController@tag']);
    Route::get('/{code}', ['as' => 'post.category', 'uses' => 'PostController@category']);
    Route::get('/{code}-{id}.html', ['as' => 'post.detail', 'uses' => 'PostController@detail']);

    Route::group(['middleware' => ['auth:web'], 'prefix' => 'user'], function () {
        Route::get('/profile', ['as' => 'user.profile', 'uses' => 'UserController@profile']);
        Route::get('/edit-profile', ['as' => 'user.edit.profile', 'uses' => 'UserController@editProfile']);
        Route::post('/update-profile', ['as' => 'user.update.profile', 'uses' => 'UserController@updateProfile']);
        Route::get('/change-password', ['as' => 'user.change.password', 'uses' => 'UserController@changePassword']);
        Route::post('/update-password', ['as' => 'user.update.password', 'uses' => 'UserController@updatePassword']);

        Route::group(['prefix' => 'post'], function () {
            Route::get('/', ['as' => 'user.post', 'uses' => 'UserController@listPost']);
            Route::get('/create', ['as' => 'user.post.create', 'uses' => 'UserController@createPost']);
            Route::post('/store', ['as' => 'user.post.store', 'uses' => 'UserController@storePost']);
            Route::get('/{id}', ['as' => 'user.post.edit', 'uses' => 'UserController@editPost']);
            Route::post('/{id}', ['as' => 'user.post.update', 'uses' => 'UserController@updatePost']);
        });
    });
});
