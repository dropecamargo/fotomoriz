<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Intereses1, App\Models\Base\Empresa;
use View, App, DB, Log;

class ReporteInteresesGeneradosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( env('APP_ENV') == 'local'){
            ini_set('memory_limit', '-1');
        }

        if( $request->has('type') ){
            // Fechas filtro mes y ano
            $mes = $request->mes;
            $ano = $request->ano;

            if( intval($mes) == 12 ){
                $mesaux = 1;
                $anoaux = $ano + 1;
            }else{
                $mesaux = $mes + 1;
                $anoaux = $ano;
            }
            $fechaaux = $anoaux."-".$mesaux."-01";
            $fechacierre = date("Y-m-d", strtotime("$fechaaux -1 day"));

            $empresa = Empresa::getEmpresa();

            $query = Intereses1::query();
            $query->select('intereses1_numero', 'intereses1_sucursal', 'intereses1_tercero', DB::raw("SUM(intereses2_interes) as intereses"), DB::raw("(CASE WHEN tercero_persona = 'N'
                        THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 ||
                                (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END)
                            )
                        ELSE tercero_razon_social END)
                    AS tercero_nombre"));
            $query->where('intereses1_fecha_cierre', $fechacierre);
            $query->where('intereses1_sucursal', '=', '1');
            $query->where('intereses1_anulado', '=', '0');
            $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');
            $query->join('intereses2', function ($join){
                $join->on('intereses1_numero', '=', 'intereses2_numero');
                $join->on('intereses1_sucursal', '=', 'intereses2_sucursal');
            });
            $query->groupBy('intereses1_numero', 'intereses1_sucursal', 'intereses1_tercero', 'tercero_persona', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_razon_social');
            $query->orderBy('intereses1_numero');
            $intereses = $query->get();

            if( count($intereses) <= 0){
                return redirect('/rintereses')
                    	->withErrors('No existen intereses a la fecha seleccionada, por favor verifique la información o consulte al administrador.')
                    	->withInput();
            }

            // Preparar datos reporte
            $title = sprintf('%s %s %s %s', 'Reporte de intereses generados a', config('koi.meses')[$mes], ' de ', $ano );
            $type = $request->type;

            // Generate file
            switch ($type) {
                case 'pdf':
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    $pdf->loadHTML(View::make('reports.receivable.reporteintereses.reporte', compact('intereses', 'empresa', 'title', 'type'))->render());
                    $pdf->setPaper('A4', 'portairt')->setWarnings(false);
                    return $pdf->stream(sprintf('%s_%s.pdf', 'intereses_generados', date('Y_m_d')));
                break;
            }
        }
        return view('reports.receivable.reporteintereses.index');
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
