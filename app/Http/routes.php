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
Route::group(['prefix' => 'auth'], function(){
	Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@postLogin']);
	Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);
	Route::get('integrate', ['as' => 'auth.integrate', 'uses' => 'Auth\AuthController@integrate']);
});
Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);

/*
|--------------------------------------------------------------------------
| Secure Routes Application
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth'], function(){
	Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

	/*
	|-------------------------
	| Reportes Routes
	|-------------------------
	*/
	Route::resource('reporteentradassalidas', 'Report\ReporteEntradasSalidasController', ['only' => ['index']]);
	Route::resource('reporteanalisisinventario', 'Report\ReporteAnalisisInventarioController', ['only' => ['index']]);
	Route::resource('reportearp', 'Report\ReporteArpController', ['only' => ['index']]);
	Route::resource('reporteresumencobro', 'Report\ReporteResumenCobroController', ['only' => ['index']]);
	Route::resource('reporteedades', 'Report\ReporteEdadesController', ['only' => ['index']]);
	Route::resource('reporteposfechados', 'Report\ReportePosFechadosController', ['only' => ['index']]);
	Route::resource('reporterecibos', 'Report\ReporteRecibosController', ['only' => ['index']]);
});
