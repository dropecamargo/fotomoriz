<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View, Excel, App, DB, Log;

class ReporteResumenCobroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->filled('type'))
        {
			// llamadas efectuadas en rango de fecha
			$query = DB::table('llamadacob');
            $query->select('llamadacob.*',   'conceptocob.*',
							't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2',
							'ti.tercero_nombre1 as ti_n1', 'ti.tercero_nombre2 as ti_n2', 'ti.tercero_apellido1 as ti_ap1', 'ti.tercero_apellido2 as ti_ap2'
			);
			$query->join('conceptocob', 'llamadacob_conceptocob', '=', 'conceptocob_codigo');
			$query->join('tercero as t', 'llamadacob_tercero', '=', 't.tercero_nit');
			$query->join('tercero as ti', 'llamadacob_tercerointerno', '=', 'ti.tercero_nit');
            $query->whereBetween('llamadacob_fecha', [$request->fecha_inicial, $request->fecha_final]);
            $llamadas = $query->get();


			// llamadas programadas en rango de fecha
			$query = DB::table('llamadacob');
            $query->select('llamadacob.*',   'conceptocob.*',
							't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2',
							'ti.tercero_nombre1 as ti_n1', 'ti.tercero_nombre2 as ti_n2', 'ti.tercero_apellido1 as ti_ap1', 'ti.tercero_apellido2 as ti_ap2'
			);
			$query->join('conceptocob', 'llamadacob_conceptocob', '=', 'conceptocob_codigo');
			$query->join('tercero as t', 'llamadacob_tercero', '=', 't.tercero_nit');
			$query->join('tercero as ti', 'llamadacob_tercerointerno', '=', 'ti.tercero_nit');
            $query->whereBetween('llamadacob_prox_fecha', [$request->fecha_inicial, $request->fecha_final]);
            $llamadas_p = $query->get();

			//var_dump($llamadas_p);

			// Preparar datos reporte
            $title = sprintf('%s', 'Reporte Resumen Cobro');
            $type = $request->type;
            $fecha_inicio = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

            // Generate file
            switch ($type) {
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'Reporte Resumen Cobro', date('Y_m_d'), date('H_m_s')), function($excel) use($fecha_inicio, $fecha_final, $llamadas, $llamadas_p, $title, $type){
    					$title = sprintf('%s', 'Cobros Realizados');
                        $excel->sheet('Excel', function($sheet) use($fecha_inicio, $fecha_final, $llamadas, $title, $type) {
                            $sheet->loadView('reports.receivable.reporteresumencobro.reporte', compact('fecha_inicio','fecha_final','llamadas', 'title', 'type'));
                            $sheet->setFontSize(8);
                        });

                    	$title = sprintf('%s', 'Cobros Programados');
                        $excel->sheet('Excel', function($sheet) use($fecha_inicio, $fecha_final, $llamadas_p, $title, $type) {
                            $sheet->loadView('reports.receivable.reporteresumencobro.reporte2', compact('fecha_inicio','fecha_final','llamadas_p', 'title', 'type'));
                            $sheet->setFontSize(8);
                        });
                    })->download('xls');
                    break;
            }
		}
        return view('reports.receivable.reporteresumencobro.index');
    }
}
