<?php

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

Route::group(['namespace' => 'Backend', 'prefix' => 'backend'], function () {
    Route::group(['namespace' =>'Auth', 'prefix' => 'auth'], function () {
        Route::get('/login', ['as' => 'backend.auth.login', 'uses' => 'LoginController@showLoginForm']);
        Route::post('/login', ['as' => 'backend.auth.login.post', 'uses' => 'LoginController@processLogin']);
        Route::get('/logout', ['as' => 'backend.auth.logout', 'middleware' => ['web'], 'uses' => 'LoginController@logout']);
        Route::get('/forgot-password', ['as' => 'backend.auth.forgot-password', 'uses' => 'ForgotPasswordController@forgotPass']);
        Route::post('/forgot-password', ['as' => 'backend.auth.forgot-password.post', 'uses' => 'ForgotPasswordController@processForgotPass']);
        Route::get('/forgot-password-complete', ['as' => 'backend.auth.forgot-password-complete', 'uses' => 'ForgotPasswordController@forgotPassComplete']);

        Route::get('/reset-password/{token}', ['as' => 'backend.auth.reset-password', 'uses' => 'ResetPasswordController@resetPass']);
        Route::post('/reset-password', ['as' => 'backend.auth.reset-password.post', 'uses' => 'ResetPasswordController@processResetPass']);
    });

    Route::group(['middleware' => ['auth:backend', 'role']], function () {
        Route::get('/', ['as' => 'backend.dashboard', 'uses' => 'IndexController@dashboard']);
        Route::get('/create-code', ['as' => 'backend.create-code', 'uses' => 'IndexController@createCode']);

        //Category
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', ['as' => 'backend.category.index', 'uses' => 'CategoryController@index']);
            Route::get('/create', ['as' => 'backend.category.create', 'uses' => 'CategoryController@create']);
            Route::post('/store', ['as' => 'backend.category.store', 'uses' => 'CategoryController@store']);
            Route::get('/{id}', ['as' => 'backend.category.edit', 'uses' => 'CategoryController@edit']);
            Route::post('/{id}', ['as' => 'backend.category.update', 'uses' => 'CategoryController@update']);
            Route::delete('/delete', ['as' => 'backend.category.delete', 'uses' => 'CategoryController@delete']);
        });

        //Post
        Route::group(['prefix' => 'post'], function () {
            Route::get('/', ['as' => 'backend.post.index', 'uses' => 'PostController@index']);
            Route::get('/create', ['as' => 'backend.post.create', 'uses' => 'PostController@create']);
            Route::post('/store', ['as' => 'backend.post.store', 'uses' => 'PostController@store']);
            Route::post('/change-status', ['as' => 'backend.post.status', 'uses' => 'PostController@changeStatus']);
            Route::get('/{id}', ['as' => 'backend.post.edit', 'uses' => 'PostController@edit']);
            Route::post('/{id}', ['as' => 'backend.post.update', 'uses' => 'PostController@update']);
            Route::delete('/delete', ['as' => 'backend.post.delete', 'uses' => 'PostController@delete']);
        });

        //Comment
        Route::group(['prefix' => 'comment'], function () {
            Route::get('/', ['as' => 'backend.comment.index', 'uses' => 'CommentController@index']);
            Route::get('/{id}', ['as' => 'backend.comment.edit', 'uses' => 'CommentController@edit']);
            Route::post('/update/{id}', ['as' => 'backend.comment.update', 'uses' => 'CommentController@update']);
            Route::post('/changeStatus', ['as' => 'backend.comment.change_status', 'uses' => 'CommentController@changeStatus']);
            Route::delete('/delete', ['as' => 'backend.comment.delete', 'uses' => 'CommentController@delete']);
        });

        //User
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', ['as' => 'backend.user.index', 'uses' => 'UserController@index']);
            Route::get('/create', ['as' => 'backend.user.create', 'uses' => 'UserController@create']);
            Route::post('/store', ['as' => 'backend.user.store', 'uses' => 'UserController@store']);
            Route::get('/profile', ['as' => 'backend.user.profile', 'uses' => 'UserController@profile']);
            Route::get('/edit-profile', ['as' => 'backend.user.edit.profile', 'uses' => 'UserController@editProfile']);
            Route::post('/update-profile', ['as' => 'backend.user.update.profile', 'uses' => 'UserController@updateProfile']);
            Route::get('/change-password', ['as' => 'backend.user.change.password', 'uses' => 'UserController@changePassword']);
            Route::post('/update-password', ['as' => 'backend.user.update.password', 'uses' => 'UserController@updatePassword']);
            Route::get('/{id}', ['as' => 'backend.user.edit', 'uses' => 'UserController@edit']);
            Route::post('/update/{id}', ['as' => 'backend.user.update', 'uses' => 'UserController@update']);
            Route::get('/detail/{id}', ['as' => 'backend.user.detail', 'uses' => 'UserController@detail']);
        });

        //Notice
        Route::group(['prefix' => 'notice'], function () {
            Route::get('/', ['as' => 'backend.notice.index', 'uses' => 'NoticeController@index']);
            Route::get('/create', ['as' => 'backend.notice.create', 'uses' => 'NoticeController@create']);
            Route::post('/store', ['as' => 'backend.notice.store', 'uses' => 'NoticeController@store']);
            Route::post('/change-status', ['as' => 'backend.notice.status', 'uses' => 'NoticeController@changeStatus']);
            Route::get('/{id}', ['as' => 'backend.notice.edit', 'uses' => 'NoticeController@edit']);
            Route::post('/{id}', ['as' => 'backend.notice.update', 'uses' => 'NoticeController@update']);
            Route::delete('/delete', ['as' => 'backend.notice.delete', 'uses' => 'NoticeController@delete']);
        });
    });
});
