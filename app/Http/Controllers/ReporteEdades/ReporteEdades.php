<?php

namespace App\Http\Controllers\ReporteEdades;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, Excel, App, DB, Log;
use App\Models\Base\AuxiliarReporte;

class ReporteEdades extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->has('type'))
        {
			DB::beginTransaction();
            try{

				/*
				//campos auxiliar
				cch1 : documento
				
				cin1 : numero
				cin2 : cuota
				cin3 : sucursal
				cdt1 : fecha expedicion
				cdt2 : fecha vencimiento
				cbi1 : nit cliente
				cch3 : nombre cliente
				cbi2 : nit tercero interno
				cch4 : nombre tercero interno
				
				cdb1 : valor cuota
				cdb2 : valor saldo
				cdb3 : valor mora >360
				cdb4 : valor mora >180 	- <=360
				cdb5 : valor mora >90 	- <=180
				cdb6 : valor mora >60 	- <=90
				cdb7 : valor mora >30 	- <=60
				cdb8 : valor mora >0 	- <=30
				
				cdb9  : valor cartera de  0  a 30
				cdb10 : valor cartera de  31 a 60
				cdb11 : valor cartera de  61 a 90
				cdb12 : valor cartera de  91 a 180
				cdb13 : valor cartera de  181 a 360
				cdb14 : valor cartera de  > 360
				
				*/
				
				// factura venta
				$query = DB::table('factura3');				
				$query->select('factura3_numero as numero', 'factura3_cuota as cuota', 'factura1_tercero as cliente', 'factura1_tercerointerno as vendedor',  
							'factura1_fecha as fecha', 'factura3_vencimiento as vencimiento', DB::raw('now() - factura3_vencimiento as dias'), 
							'factura3_valor as valor', 'factura3_saldo as saldo', 'factura1_sucursal as sucursal');
				$query->join('factura1', function($join) {
					$join->on('factura1_numero', '=', 'factura3_numero');
					$join->on('factura1_sucursal', '=', 'factura3_sucursal');
				});
				$query->where('factura1_anulada', '=', False);
				$query->where('factura3_saldo', '<>', '0');
				//var_dump($query->toSql());
                $facturas = $query->get();
				foreach ($facturas as $item) 
				{
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = 'FACTURA DE VENTA';					
					$inventario->cin1 = $item->numero;
					$inventario->cin2 = $item->cuota;
					$inventario->cin3 = $item->sucursal;
					$inventario->cdt1 = $item->fecha;
					$inventario->cdt2 = $item->vencimiento;
					$inventario->cbi1 = $item->cliente;
					$inventario->cbi2 = $item->vendedor;
					$inventario->cdb1 = $item->valor;
					$inventario->cdb2 = $item->saldo;
				    $dias=$item->dias;
                    $inventario->save();
                }	
				
				// cheques devueltos
				$query = DB::table('chdevuelto1');				
				$query->select('chdevuelto1_numero as numero',  'chdevuelto1_tercero as cliente',   'chdevuelto1_fecha as fecha',
							'chdevuelto1_fecha as vencimiento', DB::raw('now() - chdevuelto1_fecha as dias'), 'chdevuelto1_valor as valor', 
							'chdevuelto1_saldo as saldo', 'chdevuelto1_sucursal as sucursal');
				$query->where('chdevuelto1_saldo', '<>', '0');
				$facturas = $query->get();
				foreach ($facturas as $item) 
				{
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = 'CHEQUES DEVUELTOS';					
					$inventario->cin1 = $item->numero;
					$inventario->cin2 = 0;
					$inventario->cin3 = $item->sucursal;
					$inventario->cdt1 = $item->fecha;
					$inventario->cdt2 = $item->vencimiento;
					$inventario->cbi1 = $item->cliente;
					$inventario->cbi2 = 0;
					$inventario->cdb1 = $item->valor;
					$inventario->cdb2 = $item->saldo;
				    $dias=$item->dias;
                    $inventario->save();
                }	
				
				
				// cheques anticipos
				$query = DB::table('anticipo1');				
				$query->select('anticipo1_numero as numero',  'anticipo1_tercero as cliente', 'anticipo1_tercerointerno as vendedor',  'anticipo1_fecha as fecha',
							'anticipo1_fecha as vencimiento', DB::raw('now() - anticipo1_fecha as dias'), 'anticipo1_valor as valor', 
							'anticipo1_saldo as saldo', 'anticipo1_sucursal as sucursal');
				$query->where('anticipo1_saldo', '<>', '0');
				$facturas = $query->get();
				foreach ($facturas as $item) 
				{
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = 'ANTICIPOS';					
					$inventario->cin1 = $item->numero;
					$inventario->cin2 = 0;
					$inventario->cin3 = $item->sucursal;
					$inventario->cdt1 = $item->fecha;
					$inventario->cdt2 = $item->vencimiento;
					$inventario->cbi1 = $item->cliente;
					$inventario->cbi2 = 0;
					$inventario->cdb1 = $item->valor;
					$inventario->cdb2 = $item->saldo;
				    $dias=$item->dias;
                    $inventario->save();
                }	
				
				
				// cheques factoring
				$query = DB::table('factoring3');				
				$query->select('factoring3_num_doc as numero',  'factoring3_tercero_cartera as cliente',  'factoring3_fecha as fecha',
							'factoring3_vence as vencimiento', DB::raw('now() - factoring3_vence as dias'), 'factoring3_valor as valor', 
							'factoring3_saldo as saldo', 'factoring3_sucursal as sucursal');
				$query->where('factoring3_saldo', '<>', '0');
				$facturas = $query->get();
				foreach ($facturas as $item) 
				{
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = 'FACTORING';					
					$inventario->cin1 = $item->numero;
					$inventario->cin2 = 1;
					$inventario->cin3 = $item->sucursal;
					$inventario->cdt1 = $item->fecha;
					$inventario->cdt2 = $item->vencimiento;
					$inventario->cbi1 = $item->cliente;
					$inventario->cbi2 = 0;
					$inventario->cdb1 = $item->valor;
					$inventario->cdb2 = $item->saldo;
				    $dias=$item->dias;
                    $inventario->save();
                }	
				
				
				// pagare
				$query = DB::table('pagare3');				
				$query->select('pagare3_numero as numero', 'pagare3_item as cuota', 'pagare1_tercero_destino as cliente',   
							'pagare1_fecha as fecha', 'pagare3_vencimiento as vencimiento', DB::raw('now() - pagare3_vencimiento as dias'), 
							'pagare3_valor as valor', 'pagare3_saldo as saldo', 'pagare1_sucursal as sucursal');
				$query->join('pagare1', function($join) {
					$join->on('pagare1_numero', '=', 'pagare3_numero');
					$join->on('pagare1_sucursal', '=', 'pagare3_sucursal');
				});
				$query->where('pagare3_saldo', '<>', '0');
				//var_dump($query->toSql());
                $facturas = $query->get();
				foreach ($facturas as $item) 
				{
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = 'PAGARE';					
					$inventario->cin1 = $item->numero;
					$inventario->cin2 = $item->cuota;
					$inventario->cin3 = $item->sucursal;
					$inventario->cdt1 = $item->fecha;
					$inventario->cdt2 = $item->vencimiento;
					$inventario->cbi1 = $item->cliente;
					$inventario->cbi2 = 0;
					$inventario->cdb1 = $item->valor;
					$inventario->cdb2 = $item->saldo;
				    $dias=$item->dias;
                    $inventario->save();
                }	
				
				
				dd('fin');
				
			    DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
				dd($e->getMessage());
                Log::error($e->getMessage());
                abort(500);
            }	
			
		}
		return view('reportes.reporteedades.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
