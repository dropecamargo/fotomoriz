<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View, Excel, App, DB, Log;

class ReporteRecibosController extends Controller
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
			// recibos efectuadas en rango de fecha
			$query = DB::table('recibo2');
            $query->select('recibo2.*' , 'recibo1.*', 'conceptosrc.*', 'sucursal.*',
							't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2'
			);

			$query->join('recibo1', function($join) {
				$join->on('recibo1_numero', '=', 'recibo2_numero');
				$join->on('recibo1_sucursal', '=', 'recibo2_sucursal');
			});

			$query->join('conceptosrc', 'recibo2_conceptosrc', '=', 'conceptosrc_codigo');
			$query->join('sucursal', 'recibo2_sucursal', '=', 'sucursal_codigo');
			$query->join('tercero as t', 'recibo1_tercero', '=', 't.tercero_nit');
            $query->whereBetween('recibo1_fecha', [$request->fecha_inicial, $request->fecha_final]);

			$recibos = $query->get();

			// Preparar datos reporte
            $title = sprintf('%s', 'Reporte Recibos de Caja');
            $type = $request->type;
            $fecha_inicio = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

			// Generate file
			switch ($type)
			{
				case 'xls':
			        Excel::create(sprintf('%s_%s_%s', 'Reporte Recibos de Caja', date('Y_m_d'), date('H_m_s')), function($excel) use($recibos, $title, $type, $fecha_inicio, $fecha_final){
						$excel->sheet('Excel', function($sheet) use($recibos, $title, $type, $fecha_inicio, $fecha_final){
							$sheet->loadView('reports.receivable.reporterecibos.reporte', compact('recibos', 'title', 'type', 'fecha_inicio', 'fecha_final'));
							$sheet->setFontSize(8);
						});
					})->download('xls');
					break;
			}
		}
        return view('reports.receivable.reporterecibos.index');
    }
}
