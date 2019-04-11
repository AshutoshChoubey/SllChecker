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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::middleware('auth')->group(function() {
	Route::get('/sslchecker','SllCheckerController@index')->name('index');
	Route::post('/sslchecker','SllCheckerController@store');
	Route::post('/sslchecker/refresh','SllCheckerController@sslChecker');
	Route::post('/sslchecker/refreshAfterFail','SllCheckerController@refreshAfterFail');
	Route::post('/sslchecker/delete','SllCheckerController@destroy');
	
});

