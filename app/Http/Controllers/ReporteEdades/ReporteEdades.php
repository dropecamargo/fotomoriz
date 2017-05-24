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

				
				// factura venta
				$query = DB::table('factura3');				
				$query->select('factura3_numero as numero', 'factura3_cuota as cuota', 'factura1_tercero as cliente', 'factura1_tercerointerno as vendedor',  
							'factura1_fecha as fecha', 'factura3_vencimiento as vencimiento', DB::raw('factura3_vencimiento - current_date as dias'), 
							'factura3_valor as valor', 'factura3_saldo as saldo', 'factura1_sucursal as sucursal');
				$query->join('factura1', function($join) {
					$join->on('factura1_numero', '=', 'factura3_numero');
					$join->on('factura1_sucursal', '=', 'factura3_sucursal');
				});
				$query->where('factura1_anulada', '<>', true);
				$query->where('factura3_saldo', '<>', '0');
				//$query->limit(20);
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
				    $inventario->cin4 = $item->dias;
                    $inventario->save();
                }	
				
				// cheques devueltos
				$query = DB::table('chdevuelto1');				
				$query->select('chdevuelto1_numero as numero',  'chdevuelto1_tercero as cliente',   'chdevuelto1_fecha as fecha',
							'chdevuelto1_fecha as vencimiento', DB::raw('chdevuelto1_fecha - current_date as dias'), 'chdevuelto1_valor as valor', 
							'chdevuelto1_saldo as saldo', 'chdevuelto1_sucursal as sucursal');
				$query->where('chdevuelto1_saldo', '<>', '0');
				//$query->limit(20);
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
				    $inventario->cin4 = $item->dias;
                    $inventario->save();
                }	
				
				
				// cheques anticipos
				$query = DB::table('anticipo1');				
				$query->select('anticipo1_numero as numero',  'anticipo1_tercero as cliente', 'anticipo1_tercerointerno as vendedor',  'anticipo1_fecha as fecha',
							'anticipo1_fecha as vencimiento', DB::raw('anticipo1_fecha - current_date as dias'), 'anticipo1_valor as valor', 
							'anticipo1_saldo as saldo', 'anticipo1_sucursal as sucursal');
				$query->where('anticipo1_saldo', '<>', '0');
				//$query->limit(20);
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
				    $inventario->cin4 = $item->dias;
                    $inventario->save();
                }	
				
				
				// cheques factoring
				$query = DB::table('factoring3');				
				$query->select('factoring3_num_doc as numero',  'factoring3_tercero_cartera as cliente',  'factoring3_fecha as fecha',
							'factoring3_vence as vencimiento', DB::raw('factoring3_vence - current_date as dias'), 'factoring3_valor as valor', 
							'factoring3_saldo as saldo', 'factoring3_sucursal as sucursal');
				$query->where('factoring3_saldo', '<>', '0');
				//$query->limit(20);
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
				    $inventario->cin4 = $item->dias;
                    $inventario->save();
                }	
				
				
				// pagare
				$query = DB::table('pagare3');				
				$query->select('pagare3_numero as numero', 'pagare3_item as cuota', 'pagare1_tercero_destino as cliente',   
							'pagare1_fecha as fecha', 'pagare3_vencimiento as vencimiento', DB::raw('pagare3_vencimiento - current_date as dias'), 
							'pagare3_valor as valor', 'pagare3_saldo as saldo', 'pagare1_sucursal as sucursal');
				$query->join('pagare1', function($join) {
					$join->on('pagare1_numero', '=', 'pagare3_numero');
					$join->on('pagare1_sucursal', '=', 'pagare3_sucursal');
				});
				$query->where('pagare3_saldo', '<>', '0');
				//$query->limit(20);
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
				    $inventario->cin4 = $item->dias;
                    $inventario->save();
                }	
				
				// para generar reporte
				$query = AuxiliarReporte::query();
                $query->select('cch1 as documento', 'cin1 as numero', 'cin2 as cuota', 'cin3 as sucural', 'cdt1 as fecha', 'cdt2 as vencimiento', 'cbi1 as tercero',
								'cbi2 as vendedor', 'cdb1 as valor', 'cdb2 as saldo', 'cin4 as dias', 'sucursal_nombre as nombresucursal',
								't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2',
								'ti.tercero_nombre1 as ti_n1', 'ti.tercero_nombre2 as ti_n2', 'ti.tercero_apellido1 as ti_ap1', 'ti.tercero_apellido2 as ti_ap2'
								);				
				$query->join('sucursal', 'cin3', '=', 'sucursal_codigo');
                $query->join('tercero as t', 'cbi1', '=', 't.tercero_nit');
				$query->join('tercero as ti', 'cbi2', '=', 'ti.tercero_nit');
				//$query->limit(10);
				
                $auxiliar = $query->get();
				
				
				
				// Preparar datos reporte
            
				$title = sprintf('%s', 'Cartera por Edades');
				$type = $request->type;
		

				// Generate file
				switch ($type) 
				{
					case 'xls':
					
                    Excel::create(sprintf('%s_%s_%s', 'Cartera por Edades', date('Y_m_d'), date('H_m_s')), function($excel) use($auxiliar, $title, $type) 
					{
						$excel->sheet('Excel', function($sheet) use($auxiliar, $title, $type) 
						{
							
							$sheet->loadView('reportes.reporteedades.reporte', compact('auxiliar', 'title', 'type'));
							$sheet->setFontSize(8);
						});
					})->download('xls');
					break;
				}
		

				
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
