<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\Sucursal, App\Models\Receivable\Factura2, App\Models\Base\AuxiliarReporte;
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

            DB::beginTransaction();
            try{
                // Recuperar sucursales
                $query = Sucursal::query();
                $query->select('sucursal_codigo', 'sucursal_nombre');
                $query->where('sucursal_activa', true);
                !in_array('all', $request->filtersucursales) ? $query->whereIn('sucursal_codigo', $request->filtersucursales) : '';
                $query->orderBy('sucursal_nombre');
                $sucursales = $query->get();

                foreach ($sucursales as $sucursal) {
                    // Recuperar factura2
                    $query = Factura2::query();
                    $query->select('factura2_producto as producto', 'producto_lineanegocio as linea', 'factura1.factura1_anulada as estado', DB::raw("SUM(factura2_precio_venta*factura2_unidades_vendidas) as ventas, SUM(factura2_descuento_pesos) as descuentos, SUM(factura2_costo) as costos"));
                    $query->join('factura1', function($join) {
                        $join->on('factura2_numero', '=', 'factura1_numero');
                        $join->on('factura2_sucursal', '=', 'factura1_sucursal');
                    });
                    $query->join('producto', 'factura2.factura2_producto', '=', 'producto.producto_serie');
                    $query->join('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                    $query->where('factura1_sucursal', $sucursal->sucursal_codigo);
                    $query->whereBetween('factura1_fecha', [$fechai, $fechaf]);
                    $query->groupBy('producto', 'linea', 'estado');
                    $facturas = $query->get();

                    foreach ($facturas as $factura) {
                        /**
                        * cdb1 -> ventas
                        * cdb2 -> descuentos
                        * cdb3 -> costos
                        * cdb4 -> aventas
                        * cdb5 -> adescuentos
                        * cdb6 -> acostos
                        * cin1 -> sucursal_codigo
                        * cin2 -> linea_codigo
                        */
                        $auxiliar = new AuxiliarReporte;
                        if( !$factura->estado ){
                            $auxiliar->cdb1 = $factura->ventas;
                            $auxiliar->cdb2 = $factura->descuentos;
                            $auxiliar->cdb3 = $factura->costos;
                        }else{
                            $auxiliar->cdb4 = $factura->ventas;
                            $auxiliar->cdb5 = $factura->descuentos;
                            $auxiliar->cdb6 = $factura->costos;
                        }
                        $auxiliar->cin1 = $sucursal->sucursal_codigo;
                        $auxiliar->cin2 = $factura->linea;
                        $auxiliar->save();
                    }
                }

                $array = [];
                $agrupaciones = DB::table('configurasabana')->select('configurasabana_agrupacion as agrupacion')->groupBy('agrupacion')->orderBy('agrupacion', 'asc')->get();
                foreach ($agrupaciones as $agrupacion) {
                    $object = new \stdClass();
                    $object->agrupacion = $agrupacion->agrupacion;

                    $grupos = DB::table('configurasabana')->select('configurasabana_grupo as grupo')->where('configurasabana_agrupacion', $agrupacion->agrupacion)->groupBy('grupo')->orderBy('grupo', 'asc')->get();
                    foreach ($grupos as $grupo) {
                        $subObject = new \stdClass();
                        $subObject->grupo = $grupo->grupo;

                            // $query = AuxiliarReporte::query();
                            // $query->select('cin1 as sucursal', 'cin2 as linea', DB::raw("SUM(cdb1-cdb4) as ventas, SUM(cdb2-cdb5) as descuentos, SUM(cdb3-cdb6) as costos"));
                            // $query->join('configurasabana', 'cin2', '=', 'configurasabana.configurasabana_lineanegocio');
                            // $query->where('configurasabana_agrupacion', $agrupacion->agrupacion);
                            // $query->where('configurasabana_grupo', $grupo->grupo);
                            // $query->groupBy('sucursal', 'linea');
                            // $query->orderBy('sucursal', 'asc');
                            // $aux = $query->get();

                        $subObject->hoal[] = $aux;

                        // $unificaciones = DB::table('configurasabana')->select('configurasabana_unificacion as unificacion')->where('configurasabana_agrupacion', $agrupacion->agrupacion)->where('configurasabana_grupo', $grupo->grupo)->groupBy('unificacion')->orderBy('unificacion', 'asc')->get();
                        $object->grupos[] = $subObject;
                    }
                    $array[] = $object;
                }
                dd($array);

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
