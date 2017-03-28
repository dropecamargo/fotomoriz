<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Routes Auth
|--------------------------------------------------------------------------
*/
Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);
Route::resource('reporteentradassalidas', 'Reporte\ReporteEntradasSalidasController', ['only' => ['index']]);
Route::resource('reportearp', 'ReporteArp\ReporteArpController', ['only' => ['index']]);


// Route::group(['middleware' => ''], function(){
	/*
	|-------------------------
	| Reportes Routes
	|-------------------------
	*/
// });