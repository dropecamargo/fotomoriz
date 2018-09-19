<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\Sucursal, App\Models\Receivable\Factura1, App\Models\Receivable\Factura2, App\Models\Base\AuxiliarReporte;
use DB, Validator, Log, App, View;

class ReporteSabanaCostoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ( $request->filled('type') ){
            // Validate fields
            $validator = Validator::make($request->all(), [
                'filtersucursales' => 'required',
                'filtermesi' => 'required',
                'filteranoi' => 'required',
                'filtermesf' => 'required',
                'filteranof' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('/reportesabanacobros')
                        ->withErrors($validator)
                        ->withInput();
            }

            // Validar fechas
            $mesi = $request->filtermesi;
            $anoi = $request->filteranoi;
            $mesf = $request->filtermesf;
            $anof = $request->filteranof;

            $fechai = date("Y-m-d", strtotime("$anoi-$mesi-01"));
            $fechaf = date("Y-m-t", strtotime("$anof-$mesf-01"));

            if( $fechai > $fechaf ){
                return redirect('/reportesabanacobros')
                        ->withErrors("La fecha inicial no puede ser mayor a la final, for favor verifique la información.")
                        ->withInput();
            }

            // Validar array
            $validatesucursales = in_array('0', $request->filtersucursales);
            $filtersucursales = in_array('1', $request->filtersucursales) ? array_merge($request->filtersucursales, ['5', '18']) : $request->filtersucursales;

            DB::beginTransaction();
            try{
                // // Recuperar sucursales
                // $query = Sucursal::query();
                // $query->select('sucursal_codigo', 'sucursal_nombre');
                // $query->join('puntoventa', 'sucursal_codigo', '=', 'puntoventa.puntoventa_sucursal');
                // $query->where('puntoventa_prefijo', '<>', '8');
                // !$validatesucursales ? $query->whereIn('sucursal_codigo', $request->filtersucursales) : '';
                // $sucursales = $query->get();


                $query = Factura1::query();
                $query->select('factura1_anulada as estado', 'factura1_sucursal as sucursal', 'factura1_numero as numero', DB::raw("SUM(factura2_precio_venta*factura2_unidades_vendidas) as ventas, SUM(factura2_descuento_pesos*factura2_unidades_vendidas) as descuentos, SUM(factura2_costo*factura2_unidades_vendidas) as costos"));
                $query->join('factura2', function($join){
                    $join->on('factura1_numero', '=', 'factura2_numero');
                    $join->on('factura1_sucursal', '=', 'factura2_sucursal');
                });
                $query->join('producto', 'factura2.factura2_producto', '=', 'producto.producto_serie');
                $query->join('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                !$validatesucursales ? $query->whereIn('factura1_sucursal', $filtersucursales) : '';
                $query->where('configurasabana_agrupacion', 1);
                $query->where('configurasabana_grupo', 1);
                $query->where('configurasabana_unificacion', 1);
                $query->whereBetween('factura1_fecha', [$fechai, $fechaf]);
                $query->groupBy('estado', 'numero', 'sucursal');
                $facturas = $query->get();

                dd($facturas);

                // foreach ($facturas as $factura) {
                //     // Si la sucursal es 5 o 18 convertir a 1(BOGOTA)
                //     $sucu = ($factura->sucursal == 5 || $factura->sucursal == 18 ) ? 1 : $factura->sucursal;
                //
                //     /**
                //     * cdb1 -> ventas
                //     * cdb2 -> descuentos
                //     * cdb3 -> costos
                //     * cdb4 -> aventas
                //     * cdb5 -> adescuentos
                //     * cdb6 -> acostos
                //     * cin1 -> sucursal_codigo
                //     * cin2 -> cod_agrupacion
                //     * cin3 -> cod_grupo
                //     * cin4 -> cod_unificacion
                //     */
                //     $auxiliar = new AuxiliarReporte;
                //     if( !$factura->estado ){
                //         $auxiliar->cdb1 = $factura->ventas;
                //         $auxiliar->cdb2 = $factura->descuentos;
                //         $auxiliar->cdb3 = $factura->costos;
                //     }else{
                //         $auxiliar->cdb4 = $factura->ventas;
                //         $auxiliar->cdb5 = $factura->descuentos;
                //         $auxiliar->cdb6 = $factura->costos;
                //     }
                //     $auxiliar->cin1 = $sucu;
                //     $auxiliar->cin2 = $factura->agrupacion;
                //     $auxiliar->cin3 = $factura->grupo;
                //     $auxiliar->cin4 = $factura->unificacion;
                //     $auxiliar->save();
                // }
                //
                // $query = AuxiliarReporte::query();
                // $query->select('cin1 as sucursal', 'cin2 as agrupacion', 'cin3 as grupo', 'cin4 as unificacion', DB::raw("SUM(cdb1-cdb4) as ventas, SUM(cdb2-cdb5) as descuentos, SUM(cdb3-cdb6) as costos"));
                // $query->groupBy('sucursal', 'agrupacion', 'grupo', 'unificacion');
                // $query->orderByRaw("agrupacion asc, grupo asc, unificacion asc");
                // dd($query->get());

                // foreach ($sucursales as $sucursal) {
                //     // Recuperar factura2
                //     $query = Factura2::query();
                //     $query->select('factura1_sucursal as sucursal', 'producto_lineanegocio as linea', 'configurasabana_agrupacion as agrupacion', 'configurasabana_grupo as grupo', 'configurasabana_unificacion as unificacion', DB::raw("SUM(factura2_precio_venta*factura2_unidades_vendidas) as ventas, SUM(factura2_descuento_pesos) as descuentos, SUM(factura2_costo) as costos"));
                //     // $query->select('producto_lineanegocio as linea', 'factura1_anulada as estado', DB::raw("(factura2_precio_venta*factura2_unidades_vendidas) as ventas, (factura2_descuento_pesos) as descuentos, (factura2_costo) as costos"));
                //     $query->join('factura1', function($join){
                //         $join->on('factura1_numero', 'factura2_numero');
                //         $join->on('factura1_sucursal', 'factura2_sucursal');
                //     });
                //     $query->join('producto', 'factura2_producto', '=', 'producto.producto_serie');
                //     $query->join('configurasabana', 'producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                //     $query->where('factura1_puntoventa', $sucursal->sucursal_codigo);
                //     $query->whereBetween('factura1_fecha', [$fechai, $fechaf]);
                //     $query->groupBy('sucursal', 'agrupacion', 'grupo', 'unificacion', 'linea');
                //     $facturas = $query->get();
                //
                //     dd($facturas);
                //
                //     // $query->join('producto', 'factura2.factura2_producto', '=', 'producto.producto_serie');
                //     // $query->leftjoin('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                //
                //     foreach ($facturas as $factura) {
                //         /**
                //         * cdb1 -> ventas
                //         * cdb2 -> descuentos
                //         * cdb3 -> costos
                //         * cdb4 -> aventas
                //         * cdb5 -> adescuentos
                //         * cdb6 -> acostos
                //         * cin1 -> sucursal_codigo
                //         * cin2 -> linea_codigo
                //         */
                //         $auxiliar = new AuxiliarReporte;
                //         if( !$factura->estado ){
                //             $auxiliar->cdb1 = $factura->ventas;
                //             $auxiliar->cdb2 = $factura->descuentos;
                //             $auxiliar->cdb3 = $factura->costos;
                //         }else{
                //             $auxiliar->cdb4 = $factura->ventas;
                //             $auxiliar->cdb5 = $factura->descuentos;
                //             $auxiliar->cdb6 = $factura->costos;
                //         }
                //         $auxiliar->cin1 = $sucursal->sucursal_codigo;
                //         $auxiliar->cin2 = $factura->linea;
                //         $auxiliar->save();
                //     }
                // }
                //
                // $query = AuxiliarReporte::query();
                // $query->select('cin1 as sucursal', 'cin2 as linea', DB::raw("SUM(cdb1-cdb4) as ventas, SUM(cdb2-cdb5) as descuentos, SUM(cdb3-cdb6) as costos"));
                // $query->groupBy('sucursal', 'linea');
                // dd($query->get());

                // $data = [];
                // $agrupaciones = DB::table('configurasabana')
                //                         ->select('configurasabana_nombre_agrupacion AS nom_agrupacion', 'configurasabana_agrupacion AS agrupacion')
                //                         ->groupBy('nom_agrupacion', 'agrupacion')
                //                         ->orderBy('agrupacion', 'asc')
                //                         ->get();
                // foreach ($agrupaciones as $agrupacion) {
                //     $object = new \stdClass();
                //     $object->agrupacion = $agrupacion->nom_agrupacion;
                //
                //     $grupos = DB::table('configurasabana')
                //                         ->select('configurasabana_grupo_nombre AS nom_grupo', 'configurasabana_grupo AS grupo')
                //                         ->where('configurasabana_agrupacion', $agrupacion->agrupacion)
                //                         ->groupBy('nom_grupo', 'grupo')
                //                         ->orderBy('grupo', 'asc')
                //                         ->get();
                //     foreach ($grupos as $grupo) {
                //         $subObject = new \stdClass();
                //         $subObject->grupo = $grupo->nom_grupo;
                //
                //         $unificaciones = DB::table('configurasabana')
                //                                     ->select('configurasabana_nombre_unificacion AS nom_unificacion', 'configurasabana_unificacion AS unificacion')
                //                                     ->where('configurasabana_agrupacion', $agrupacion->agrupacion)
                //                                     ->where('configurasabana_grupo', $grupo->grupo)
                //                                     ->groupBy('nom_unificacion', 'unificacion')
                //                                     ->orderBy('unificacion', 'asc')
                //                                     ->get();
                //         foreach ($unificaciones as $unificacion) {
                //             $subsubObject = new \stdClass();
                //             $subsubObject->unificacion = $unificacion->nom_unificacion;
                //
                //             $aux = [];
                //             foreach ($sucursales as $sucursal) {
                //                 $query = AuxiliarReporte::query();
                //                 $query->select('cin1 as sucursal', DB::raw("SUM(cdb1-cdb4) as ventas, SUM(cdb2-cdb5) as descuentos, SUM(cdb3-cdb6) as costos"));
                //                 $query->join('configurasabana', 'cin2', '=', 'configurasabana.configurasabana_lineanegocio');
                //                 $query->where('auxiliarreporte.cin1', $sucursal->sucursal_codigo);
                //                 $query->where('configurasabana_agrupacion', $agrupacion->agrupacion);
                //                 $query->where('configurasabana_grupo', $grupo->grupo);
                //                 $query->where('configurasabana_unificacion', $unificacion->unificacion);
                //                 $query->groupBy('sucursal');
                //                 $auxiliar = $query->first();
                //
                //                 if( $auxiliar instanceof AuxiliarReporte ){
                //                     $auxiliar->sucursal = $sucursal->sucursal_nombre;
                //                     $auxiliar = $auxiliar->toArray();
                //
                //                 }else{
                //                     $auxiliar = array(
                //                         'sucursal' => $sucursal->sucursal_nombre,
                //                         'ventas' => 0,
                //                         'descuentos' => 0,
                //                         'costos' => 0
                //                     );
                //                 }
                //
                //                 // // list($sucursales, $ventas, $descuentos, $costos) = array_divide($auxiliar['sucursal'], $auxiliar['ventas'], $auxiliar['descuentos'], $auxiliar['costos']);
                //                 // $sucursales = array_pluck($auxiliar, 'sucursal');
                //                 // $ventas = array_pluck($auxiliar, 'ventas');
                //                 // $descuentos = array_pluck($auxiliar, 'descuentos');
                //                 // $costos = array_pluck($auxiliar, 'costos');
                //                 // dd($sucursales, $ventas, $descuentos, $costos);
                //                 $aux[] = $auxiliar;
                //             }
                //
                //             $subsubObject->detalle = $aux;
                //             $subObject->unificaciones[] = $subsubObject;
                //
                //             // $query = AuxiliarReporte::query();
                //             // $query->select('cin1 as sucursal', 'cin2 as linea', DB::raw("SUM(cdb1-cdb4) as ventas, SUM(cdb2-cdb5) as descuentos, SUM(cdb3-cdb6) as costos"));
                //             // $query->join('configurasabana', 'cin2', '=', 'configurasabana.configurasabana_lineanegocio');
                //             // $query->where('configurasabana_agrupacion', $agrupacion->agrupacion);
                //             // $query->where('configurasabana_grupo', $grupo->grupo);
                //             // $query->where('configurasabana_unificacion', $unificacion->unificacion);
                //             // $query->groupBy('sucursal', 'linea');
                //             // $query->orderBy('sucursal', 'asc');
                //             // $aux = $query->get();
                //             //
                //             // if(count($aux) <= 0){
                //             //     continue;
                //             // }
                //         }
                //
                //         if( !isset($subObject->unificaciones) ){
                //             continue;
                //         }
                //         $object->grupos[] = $subObject;
                //     }
                //
                //     if( !isset($object->grupos) ){
                //         continue;
                //     }
                //
                //     $data[] = $object;
                // }
                //
                // // Preparar datos reporte
                // $title = "Reporte Sabana de ventas costos";
                // $type = $request->type;
                // switch ($type)
                // {
                //     case 'pdf':
                //         $pdf = App::make('dompdf.wrapper');
                //         $pdf->loadHTML(View::make('reports.commercial.sabanaventas.reporte', compact('data', 'title', 'type', 'fechai', 'fechaf'))->render());
                //         $pdf->setPaper('A4', 'landscape')->setWarnings(false);
                //         return $pdf->stream(sprintf('%s.pdf', 'sabanaventas', date('Y_m_d')));
                //     break;
                // }

                DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                abort(500);
            }
        }
        return view('reports.commercial.sabanaventas.index');
    }
}
