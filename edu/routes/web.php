<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
//login
Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@index']);
Route::post('/postlogin', ['as' => 'post_login', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
// middleware

Route::group(['middleware' => 'authapi'], function(){
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/data', ['as' => 'load_data', 'uses' => 'HomeController@LoadData']);
    Route::post('/query', ['as' => 'query_data', 'uses' => 'HomeController@QueryData']);
    Route::post('/excel', ['as' => 'excel', 'uses' => 'HomeController@ExcelData']);
    Route::post('/zalo', ['as' => 'zalo', 'uses' => 'HomeController@Zalohook']);
    Route::get('/sync_zl', ['as' => 'sync', 'uses' => 'HomeController@SyncZl']);
});



