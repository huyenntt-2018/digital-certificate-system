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

Auth::routes();

Route::get('admin/login', 'AdminController@getLogin')->name('admin.login');
Route::post('admin/login', 'AdminController@postLogin');
Route::get('admin/logout', 'AdminController@logout')->name('admin.logout');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    Route::resource('users', 'UserController');
    Route::resource('certificates', 'CertificateController')->only(['index', 'show']);
    Route::get('update-status', 'CertificateController@status')->name('update.status');
});

// Logout
Route::get('logout', 'HomeController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@index')->name('home');
});
