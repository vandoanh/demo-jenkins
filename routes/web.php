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

//route for upload
Route::group(['namespace' => 'Media'], function () {
    //route for image
    Route::get('/images/{size}/{filename}', ['as' => 'image-cache', 'uses' => 'ImageCacheController@getResponse'])->where(['filename' => '[ \w\\.\\/\\-\\@\(\)]+']);

    Route::group(['prefix' => 'upload', 'middleware' => 'cors'], function () {
        Route::post('/form/{type}', ['as' => 'upload.form', 'uses' => 'UploadController@uploadByForm']);
        Route::post('/url/{type}', ['as' => 'upload.url', 'uses' => 'UploadController@uploadByUrl']);
        Route::get('/save/{type}/{file}', ['as' => 'upload.save', 'uses' => 'UploadController@saveUpload']);
    });
});

foreach (glob(__DIR__ . '/web/*.php') as $routeFile) {
    require $routeFile;
}
