<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'API'], function() {
    Route::get('/{user}', 'HomeController@index');
    // đăng ký chứng thư gốc
    Route::apiResource('register-request', 'RegisterRequestController')->except(['update, destroy']);
    // download chứng thư và file lưu trữ cert + khóa bí mật
    Route::get('download-cert/{id}', 'HomeController@download')->name('download-cert');
    Route::get('download-pkcs12/{id}', 'HomeController@download')->name('download-pkcs12');
    // ktra chứng thư
    Route::get('check-cert/{user}/{serialNumber}', 'HomeController@checkCert')->name('check-cert');
    // thu hồi chứng thư
    Route::post('revoke/{user}', 'RevokeController@revoke')->name('revoke');
    // đăng ký chứng thư tạm thời
    Route::apiResource('intro-requests', 'IntroductionController')->only('store', 'index');
    // tạo chữ ký và xác thực chữ ký
    Route::post('sign', 'CertificateController@sign')->name('sign');
    Route::post('verify', 'CertificateController@verify')->name('verify');
});
