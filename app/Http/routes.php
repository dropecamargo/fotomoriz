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
	| Admin Routes
	|-------------------------
	*/
	Route::group(['prefix' => 'roles'], function()
	{
		Route::resource('permisos', 'Admin\PermisoRolController', ['only' => ['index', 'update', 'destroy']]);
	});
	Route::group(['prefix' => 'terceros'], function()
	{
		Route::get('search', ['as' => 'terceros.search', 'uses' => 'Admin\TerceroController@search']);
	});
	Route::resource('roles', 'Admin\RolController', ['except' => ['destroy']]);
	Route::resource('terceros', 'Admin\TerceroController', ['only' => ['index']]);
	Route::resource('permisos', 'Admin\PermisoController', ['only' => ['index']]);

	/*
	|-------------------------
	| Cartera Routes
	|-------------------------
	*/
	Route::resource('generarintereses', 'Receivable\GenerarInteresController', ['only' => ['index', 'store']]);
	Route::group(['prefix' => 'enviarintereses'], function()
	{
		Route::get('enviar', ['as' => 'enviarintereses.enviar', 'uses' => 'Receivable\EnviarInteresController@enviar']);
		Route::get('anular/{enviarinteres}', ['as' => 'enviarintereses.anular', 'uses' => 'Receivable\EnviarInteresController@anular']);
		Route::get('exportar/{enviarinteres}', ['as' => 'enviarintereses.exportar', 'uses' => 'Receivable\EnviarInteresController@exportar']);
		Route::resource('detalle', 'Receivable\DetalleEnviarInteresController', ['only' => ['index']]);
	});
	Route::resource('amortizaciones', 'Receivable\AmortizacionCreditoController', ['only' => ['index']]);
	Route::resource('enviarintereses', 'Receivable\EnviarInteresController', ['only' => ['index', 'show']]);
	Route::resource('rintereses', 'Report\ReporteInteresesGeneradosController', ['only' => ['index']]);

	Route::resource('reporteedades', 'Report\ReporteEdadesController', ['only' => ['index']]);
	Route::resource('reporteposfechados', 'Report\ReportePosFechadosController', ['only' => ['index']]);
	Route::resource('reporterecibos', 'Report\ReporteRecibosController', ['only' => ['index']]);
	Route::resource('reporteresumencobro', 'Report\ReporteResumenCobroController', ['only' => ['index']]);
	Route::resource('reporteverextractos', 'Report\ReporteVerExtractoController', ['only' => ['index', 'show']]);

	/*
	|-------------------------
	| Contabilidad Routes
	|-------------------------
	*/
	Route::resource('reportearp', 'Report\ReporteArpController', ['only' => ['index']]);

	/*
	|-------------------------
	| Inventario Routes
	|-------------------------
	*/
	Route::resource('reporteentradassalidas', 'Report\ReporteEntradasSalidasController', ['only' => ['index']]);
	Route::resource('reporteanalisisinventario', 'Report\ReporteAnalisisInventarioController', ['only' => ['index']]);
});
