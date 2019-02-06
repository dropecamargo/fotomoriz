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

/*
|--------------------------------------------------------------------------
| Routes Auth
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth'], function(){
	Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@postLogin']);
	Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);
	Route::get('integrate', ['as' => 'auth.integrate', 'uses' => 'Auth\LoginController@integrate']);
});
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);

Route::middleware('auth')->group(function () {
	Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

	/*
	|-------------------------
	| Admin Routes
	|-------------------------
	*/
	Route::prefix('terceros')->name('terceros.')->group(function()	{
		Route::get('search', ['as' => 'search', 'uses' => 'Admin\TerceroController@search']);
	});
	Route::resource('terceros', 'Admin\TerceroController', ['only' => ['index']]);

	Route::prefix('tercerosinterno')->name('tercerosinterno.')->group(function()	{
		Route::resource('roles', 'Admin\UsuarioRolController', ['only' => ['index', 'store', 'destroy']]);
	});
	Route::resource('tercerosinterno', 'Admin\TerceroInternoController', ['only' => ['index', 'show']]);

	Route::prefix('roles')->name('roles.')->group(function() {
		Route::resource('permisos', 'Admin\PermisoRolController', ['only' => ['index', 'update', 'destroy']]);
	});
	Route::resource('roles', 'Admin\RolController', ['except' => ['destroy']]);
	Route::resource('permisos', 'Admin\PermisoController', ['only' => ['index']]);
	Route::resource('modulos', 'Admin\ModuloController', ['only' => ['index']]);

	/*
	|-------------------------
	| Cartera Routes
	|-------------------------
	*/
	Route::resource('generarintereses', 'Receivable\GenerarInteresController', ['only' => ['index', 'store']]);
	Route::prefix('enviarintereses')->name('enviarintereses.')->group(function () {
		Route::get('enviar', ['as' => 'enviar', 'uses' => 'Receivable\EnviarInteresController@enviar']);
		Route::get('anular/{enviarinteres}', ['as' => 'anular', 'uses' => 'Receivable\EnviarInteresController@anular']);
		Route::get('exportar/{enviarinteres}', ['as' => 'exportar', 'uses' => 'Receivable\EnviarInteresController@exportar']);
		Route::resource('detalle', 'Receivable\DetalleEnviarInteresController', ['only' => ['index']]);
	});
	Route::resource('amortizaciones', 'Receivable\AmortizacionCreditoController', ['only' => ['index']]);
	Route::resource('enviarintereses', 'Receivable\EnviarInteresController', ['only' => ['index', 'show']]);
	Route::resource('rintereses', 'Report\ReporteInteresesGeneradosController', ['only' => ['index']]);

	Route::resource('reportefacturaselectronicas', 'Report\ReporteFelFacturasController', ['only' => ['index']]);
	Route::resource('reporteedades', 'Report\ReporteEdadesController', ['only' => ['index']]);
	Route::resource('reporteposfechados', 'Report\ReportePosFechadosController', ['only' => ['index']]);
	Route::resource('reporterecibos', 'Report\ReporteRecibosController', ['only' => ['index']]);
	Route::resource('reporteresumencobro', 'Report\ReporteResumenCobroController', ['only' => ['index']]);
	Route::resource('reporteverextractos', 'Report\ReporteVerExtractoController', ['only' => ['index', 'show']]);

	/*
	|-------------------------
	| Comercial Routes
	|-------------------------
	*/
	Route::resource('reportesabanacobros', 'Report\ReporteSabanaCostoController', ['only' => ['index']]);

	/*
	|-------------------------
	| Contabilidad Routes
	|-------------------------
	*/
	Route::prefix('presupuestosg')->name('presupuestosg.')->group(function() {
		Route::get('exportar', ['as' => 'exportar', 'uses' => 'Accounting\PresupuestoGastoController@exportar']);
	});
	Route::resource('presupuestosg', 'Accounting\PresupuestoGastoController', ['only' => ['index']]);
	Route::resource('reportearp', 'Report\ReporteArpController', ['only' => ['index']]);
	Route::resource('plancuentasn', 'Accounting\PlanCuentaNController', ['only' => ['index', 'show', 'edit', 'update']]);

	/*
	|-------------------------
	| Inventario Routes
	|-------------------------
	*/
	Route::resource('reporteentradassalidas', 'Report\ReporteEntradasSalidasController', ['only' => ['index']]);
	Route::resource('reporteanalisisinventario', 'Report\ReporteAnalisisInventarioController', ['only' => ['index']]);

	/*
	|-------------------------
	| Imports Routes
	|-------------------------
	*/
	Route::prefix('import')->name('import.')->group(function() {
		Route::post('presupuestog' ,['as' =>'presupuestosg','uses'=>'Accounting\PresupuestoGastoController@import'] );
	});
});
