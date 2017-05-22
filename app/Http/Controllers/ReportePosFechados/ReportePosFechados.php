<?php

namespace App\Http\Controllers\ReportePosFechados;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View, Excel, App, DB, Log;
use App\Models\Base\AuxiliarReporte;

class ReportePosFechados extends Controller
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
            try
			{
				$query = DB::table('chposfechado2');				
				$query->select('chposfechado1.*', 'chposfechado2.*');
				$query->join('chposfechado1', function($join) {
					$join->on('chposfechado1_numero', '=', 'chposfechado2_numero');
					$join->on('chposfechado1_sucursal', '=', 'chposfechado2_sucursal');
				});
				$query->where('chposfechado1_saldo', '<>', '0');
				$query->where('chposfechado2_item', '=', '1');
				$query->where('chposfechado1_anulado', '<>', true);
                $cheques = $query->get();
				
				foreach ($cheques as $item) 
				{
					if($item->chposfechado2_conceptosrc=='FACTU')  
					{
						$query = DB::table('factura3');				
						$query->select('factura3_saldo as saldo');
						$query->where('factura3_numero', '=', $item->chposfechado2_numero_doc);
						$query->where('factura3_sucursal', '=', $item->chposfechado2_sucursal_doc);
						$query->where('factura3_cuota', '=', $item->chposfechado2_cuota_doc);
						$doc = $query->get();
						foreach ($doc as $item2) 
						{
							if($item2->saldo<>0)
							{
								$inventario = new AuxiliarReporte;
								$inventario->cbi1 = $item->chposfechado1_tercero;
								$inventario->cin1 = $item->chposfechado1_numero;
								$inventario->cin2 = $item->chposfechado1_sucursal;
								$inventario->cin3 = $item->chposfechado1_bancos;
								$inventario->cch1 = $item->chposfechado1_numerocheque;
								$inventario->cch2 = $item->chposfechado1_girador;
								$inventario->cch3 = $item->chposfechado1_observaciones;
								$inventario->cdt1 = $item->chposfechado1_fecha;
								$inventario->cdt2 = $item->chposfechado1_fechacheque;
								$inventario->cdb1 = $item->chposfechado1_valor;
								$inventario->cbo1 = $item->chposfechado1_centralriesgo;
								$inventario->save();
							}
						}
					}
					if($item->chposfechado2_conceptosrc=='CHDEV')
					{
						$query = DB::table('chdevuelto1');				
						$query->select('chdevuelto1_saldo as saldo');
						$query->where('chdevuelto1_numero', '=', $item->chposfechado2_numero_doc);
						$query->where('chdevuelto1_sucursal', '=', $item->chposfechado2_sucursal_doc);
						$doc = $query->get();
						foreach ($doc as $item2) 
						{
							if($item2->saldo<>0)
							{
								$inventario = new AuxiliarReporte;
								$inventario->cbi1 = $item->chposfechado1_tercero;
								$inventario->cin1 = $item->chposfechado1_numero;
								$inventario->cin2 = $item->chposfechado1_sucursal;
								$inventario->cin3 = $item->chposfechado1_bancos;
								$inventario->cch1 = $item->chposfechado1_numerocheque;
								$inventario->cch2 = $item->chposfechado1_girador;
								$inventario->cch3 = $item->chposfechado1_observaciones;
								$inventario->cdt1 = $item->chposfechado1_fecha;
								$inventario->cdt2 = $item->chposfechado1_fechacheque;
								$inventario->cdb1 = $item->chposfechado1_valor;
								$inventario->cbo1 = $item->chposfechado1_centralriesgo;
								$inventario->save();
							}
						}
					}
					if($item->chposfechado2_conceptosrc=='PAGAR')
					{
						$query = DB::table('pagare3');				
						$query->select('pagare3_saldo as saldo');
						$query->where('pagare3_numero', '=', $item->chposfechado2_numero_doc);
						$query->where('pagare3_sucursal', '=', $item->chposfechado2_sucursal_doc);
						$query->where('pagare3_item', '=', $item->chposfechado2_cuota_doc);
						$doc = $query->get();
						foreach ($doc as $item2) 
						{
							if($item2->saldo<>0)
							{
								$inventario = new AuxiliarReporte;
								$inventario->cbi1 = $item->chposfechado1_tercero;
								$inventario->cin1 = $item->chposfechado1_numero;
								$inventario->cin2 = $item->chposfechado1_sucursal;
								$inventario->cin3 = $item->chposfechado1_bancos;
								$inventario->cch1 = $item->chposfechado1_numerocheque;
								$inventario->cch2 = $item->chposfechado1_girador;
								$inventario->cch3 = $item->chposfechado1_observaciones;
								$inventario->cdt1 = $item->chposfechado1_fecha;
								$inventario->cdt2 = $item->chposfechado1_fechacheque;
								$inventario->cdb1 = $item->chposfechado1_valor;
								$inventario->cbo1 = $item->chposfechado1_centralriesgo;
								$inventario->save();
							}
						}
					}
					if($item->chposfechado2_conceptosrc=='FACTO')
					{
						$query = DB::table('factoring3');				
						$query->select('factoring3_saldo as saldo');
						$query->where('factoring3_numero', '=', $item->chposfechado2_numero_doc);
						$query->where('factoring3_sucursal', '=', $item->chposfechado2_sucursal_doc);
						$query->where('factoring3_num_doc', '=', $item->chposfechado2_cuota_doc);
						$doc = $query->get();
						foreach ($doc as $item2) 
						{
							if($item2->saldo<>0)
							{
								$inventario = new AuxiliarReporte;
								$inventario->cbi1 = $item->chposfechado1_tercero;
								$inventario->cin1 = $item->chposfechado1_numero;
								$inventario->cin2 = $item->chposfechado1_sucursal;
								$inventario->cin3 = $item->chposfechado1_bancos;
								$inventario->cch1 = $item->chposfechado1_numerocheque;
								$inventario->cch2 = $item->chposfechado1_girador;
								$inventario->cch3 = $item->chposfechado1_observaciones;
								$inventario->cdt1 = $item->chposfechado1_fecha;
								$inventario->cdt2 = $item->chposfechado1_fechacheque;
								$inventario->cdb1 = $item->chposfechado1_valor;
								$inventario->cbo1 = $item->chposfechado1_centralriesgo;
								$inventario->save();
							}
						}
					}
                }
				
				// para generar reporte
				$query = AuxiliarReporte::query();
                $query->select('cbi1 as tercero', 'cin1 as numero', 'cin2 as sucursal', 'cin3 as banco', 'cch1 as numerocheque', 'cch2 as girador', 'cch3 as observaciones',
								'cdt1 as fecha', 'cdt2 as fechacheque', 'cdb1 as valor', 'cbo1 as centralriesgo', 'bancos_nombre as nombrebanco', 'sucursal_nombre as nombresucursal',
								't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2'
								);				
                $query->join('bancos', 'cin3', '=', 'bancos_codigo');
				$query->join('sucursal', 'cin2', '=', 'sucursal_codigo');
                $query->join('tercero as t', 'cbi1', '=', 't.tercero_nit');
                $auxiliar = $query->get();
				
				// Preparar datos reporte
            
				$title = sprintf('%s', 'Cheques Posfechados');
				$type = $request->type;
		

				// Generate file
				switch ($type) 
				{
					case 'xls':
					
                    Excel::create(sprintf('%s_%s_%s', 'reporte_cheques_posfechados', date('Y_m_d'), date('H_m_s')), function($excel) use($auxiliar, $title, $type) 
					{
						$excel->sheet('Excel', function($sheet) use($auxiliar, $title, $type) 
						{
							
							$sheet->loadView('reportes.reporteposfechados.reporte', compact('auxiliar', 'title', 'type'));
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
		return view('reportes.reporteposfechados.index');
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
