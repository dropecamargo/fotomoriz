<?php

namespace App\Http\Controllers\ReporteResumenCobro;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\Lamadacob;
use App\Models\Base\Conceptocob;
use App\Models\Base\Tercero;

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
		if($request->has('type'))
        {
			
			$query = DB::table('llamadacob');
            $query->select('llamadacob.*',   'conceptocob.*',
							't.tercero_razon_social as t_rz', 't.tercero_nombre1 as t_n1', 't.tercero_nombre2 as t_n2', 't.tercero_apellido1 as t_ap1', 't.tercero_apellido2 as t_ap2',
							'ti.tercero_nombre1 as ti_n1', 'ti.tercero_nombre2 as ti_n2', 'ti.tercero_apellido1 as ti_ap1', 'ti.tercero_apellido2 as ti_ap2'
			);
							//DB::raw("CONCAT(t.tercero_razon_social, ' ',  t.tercero_nombre1, ' ', t.tercero_nombre2, ' ',t.tercero_apellido1, ' ',t.tercero_apellido2) as tercero_nombre"), 
			                //DB::raw("CONCAT	(ti.tercero_nombre1::text, ' ', ti.tercero_nombre2::text, ' ',ti.tercero_apellido1::text, ' ',ti.tercero_apellido2::text) as terceroi_nombre"));
							//DB::raw("(t.tercero_razon_nombre || ' ' || t.tercero_nombre1 || ' ' || t.tercero_nombre2 || ' ' || t.tercero_apellido1 || ' ' || t.tercero_apellido2) as tercero_nombre"));
			$query->join('conceptocob', 'llamadacob_conceptocob', '=', 'conceptocob_codigo');
			$query->join('tercero as t', 'llamadacob_tercero', '=', 't.tercero_nit');
			$query->join('tercero as ti', 'llamadacob_tercerointerno', '=', 'ti.tercero_nit');
            $query->whereBetween('llamadacob_fecha', [$request->fecha_inicial, $request->fecha_final]);
            $llamadas = $query->get();
			
			
			
			
			// Preparar datos reporte
            $title = sprintf('%s', 'Reporte Resumen Cobro');
            $type = $request->type;
            $fecha_inicio = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;
            

            // Generate file
            switch ($type) {
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'reporte_resumen_cobro', date('Y_m_d'), date('H_m_s')), function($excel) use($fecha_inicio, $fecha_final, $llamadas, $title, $type) {
                    $excel->sheet('Excel', function($sheet) use($fecha_inicio, $fecha_final, $llamadas, $title, $type) {
                        $sheet->loadView('reportes.reporteresumencobro.reporte', compact('fecha_inicio','fecha_final','llamadas', 'title', 'type'));
                        $sheet->setFontSize(8);
                    });
                })->download('xls');
                break;
            }

                
		}
        return view('reportes.reporteresumencobro.index');
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
