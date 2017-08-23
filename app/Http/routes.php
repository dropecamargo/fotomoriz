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
	Route::resource('reporteanalisisinventario', 'ReporteAnalisisInventario\ReporteAnalisisInventarioController', ['only' => ['index']]);
	Route::resource('reporteentradassalidas', 'Reporte\ReporteEntradasSalidasController', ['only' => ['index']]);
	Route::resource('reportearp', 'ReporteArp\ReporteArpController', ['only' => ['index']]);
	Route::resource('reporteresumencobro', 'ReporteResumenCobro\ReporteResumenCobroController', ['only' => ['index']]);
	Route::resource('reporteedades', 'ReporteEdades\ReporteEdades', ['only' => ['index']]);
	Route::resource('reporteposfechados', 'ReportePosFechados\ReportePosFechados', ['only' => ['index']]);
	Route::resource('reporterecibos', 'ReporteRecibos\ReporteRecibos', ['only' => ['index']]);
});
