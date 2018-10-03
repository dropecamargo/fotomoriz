<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\Sucursal, App\Models\Receivable\Factura1, App\Models\Receivable\Devolucion1, App\Models\Receivable\Nota1, App\Models\Base\AuxiliarReporte, App\Models\Commercial\ConfiguraSabana;
use DB, Validator, Log, App, View, Excel;

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
            ini_set('memory_limit', '-1');
            set_time_limit(0);

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
                // Sucursales
                $query = Sucursal::query();
                $query->select('sucursal_codigo', 'sucursal_nombre');
                $query->join('puntoventa', 'sucursal_codigo', '=', 'puntoventa.puntoventa_sucursal');
                $query->whereColumn('puntoventa_nombre', 'sucursal_nombre');
                !$validatesucursales ? $query->whereIn('sucursal_codigo', $request->filtersucursales) : '';
                $query->orderBy('sucursal_codigo', 'asc');
                $sucursales = $query->get();

                foreach ($sucursales as $sucursal) {
                    // Facturas
                    $query = Factura1::query();
                    $query->select('configurasabana_agrupacion as agrupacion', 'configurasabana_grupo as grupo', 'configurasabana_unificacion as unificacion', DB::raw("SUM(factura2_precio_venta*factura2_unidades_vendidas) as ventas, SUM(factura2_descuento_pesos*factura2_unidades_vendidas) as descuentos, SUM(factura2_costo*factura2_unidades_vendidas) AS costos, SUM(0) AS devoluciones, 'F' as tipo"), 'producto_lineanegocio as linea', DB::raw("0 as valornota, 0 as brutofactura, 0 as descuentofactura, FALSE as anulada, 0 as ivafactura"));
                    $query->whereBetween('factura1_fecha', [$fechai, $fechaf]);
                    $query->join('factura2', function($join){
                        $join->on('factura1.factura1_numero', '=', 'factura2.factura2_numero')
                             ->on('factura1.factura1_sucursal', '=', 'factura2.factura2_sucursal');
                    });
                    $query->join('producto', 'factura2.factura2_producto', '=', 'producto.producto_serie');
                    $query->join('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                    $query->join('puntoventa', function($join) use($sucursal){
                        $join->on('factura1.factura1_puntoventa', '=', 'puntoventa.puntoventa_numero')
                            ->where('puntoventa.puntoventa_sucursal', $sucursal->sucursal_codigo)
                            ->where('puntoventa.puntoventa_numero', '<>', 8);
                    });
                    $query->where('factura1_anulada', false);
                    if($sucursal->sucursal_codigo == 1){
                        $query->whereIn('factura1_sucursal', [$sucursal->sucursal_codigo, 18]);
                    }
                    $query->groupBy('agrupacion', 'grupo', 'unificacion', 'linea');
                    $facturaunion = $query;

                    // Devoluciones
                    $query = Devolucion1::query();
                    $query->select('configurasabana_agrupacion as agrupacion', 'configurasabana_grupo as grupo', 'configurasabana_unificacion as unificacion', DB::raw("SUM(0) AS ventas, SUM(0) AS descuentos, SUM(devolucion2_costo*devolucion2_cantidad) AS costos, SUM((devolucion2_precio*devolucion2_cantidad)-(devolucion2_descuento*devolucion2_cantidad)) AS devoluciones, 'D' as tipo"), 'producto_lineanegocio as linea', DB::raw("0 as valornota, 0 as brutofactura, 0 as descuentofactura, FALSE as anulada, 0 as ivafactura"));
                    $query->join('devolucion2', function($join){
                        $join->on('devolucion1.devolucion1_numero', '=', 'devolucion2.devolucion2_numero')
                            ->on('devolucion1.devolucion1_sucursal', '=', 'devolucion2.devolucion2_sucursal');
                    });
                    $query->join('producto', 'devolucion2.devolucion2_producto', '=', 'producto.producto_serie');
                    $query->join('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                    $query->join('factura1', 'devolucion1.devolucion1_factura_numero', '=', 'factura1.factura1_numero');
                    $query->join('puntoventa', function($join) use($sucursal){
                        $join->on('factura1.factura1_puntoventa', '=', 'puntoventa.puntoventa_numero')
                            ->where('puntoventa.puntoventa_sucursal', $sucursal->sucursal_codigo)
                            ->where('puntoventa.puntoventa_numero', '<>', 8);
                    });
                    $query->whereBetween('devolucion1_fecha_elaboro', [$fechai, $fechaf]);
                    if($sucursal->sucursal_codigo == 1){
                        $query->whereIn('factura1_sucursal', [$sucursal->sucursal_codigo, 18]);
                    }
                    $query->groupBy('agrupacion', 'grupo', 'unificacion', 'linea');
                    $query->unionAll($facturaunion);
                    $devolucionunion = $query;

                    // Notas
                    $query = Nota1::query();
                    $query->select('configurasabana_agrupacion as agrupacion', 'configurasabana_grupo as grupo', 'configurasabana_unificacion as unificacion', DB::raw("SUM(factura2_precio_venta*factura2_unidades_vendidas) as ventas, SUM(factura2_descuento_pesos*factura2_unidades_vendidas) as descuentos, SUM(factura2_costo*factura2_unidades_vendidas) as costos, SUM(0) as devoluciones, 'N' as tipo"), 'producto_lineanegocio as linea', 'nota2_valor as valornota', 'factura1_bruto as brutofactura', 'factura1_descuento as descuentofactura', 'nota1_anulada as anulada', 'factura1_iva as ivafactura');
                    $query->join('nota2', function($join){
                        $join->on('nota1.nota1_numero', '=', 'nota2.nota2_numero')
                            ->on('nota1.nota1_sucursal', '=', 'nota2.nota2_sucursal');
                    });
                    $query->join('factura1', function($join){
                        $join->on('nota2.nota2_numero_doc', '=', 'factura1.factura1_numero')
                            ->on('nota2.nota2_sucursal_doc', '=', 'factura1.factura1_sucursal');
                    });
                    $query->join('factura2', function($join){
                        $join->on('factura1.factura1_numero', '=', 'factura2.factura2_numero')
                            ->on('factura1.factura1_sucursal', '=', 'factura2.factura2_sucursal');
                    });
                    $query->join('producto', 'factura2.factura2_producto', '=', 'producto.producto_serie');
                    $query->join('configurasabana', 'producto.producto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                    $query->join('puntoventa', function($join) use($sucursal){
                        $join->on('factura1.factura1_puntoventa', '=', 'puntoventa.puntoventa_numero')
                            ->where('puntoventa.puntoventa_sucursal', $sucursal->sucursal_codigo)
                            ->where('puntoventa.puntoventa_numero', '<>', 8);
                    });
                    $query->where('nota1_anulada', false);
                    $query->where('nota1_conceptonota', '<>', 3);
                    $query->where('nota1_conceptonota', '<>', 4);
                    $query->where('nota1_numero', '<>', 1279);
                    $query->where(function($query){
                        $query->whereRaw("NOT(nota1_anulada='true' AND nota1_fecha_elaboro >= nota1_fecha_anulo AND nota1_fecha_anulo <= nota1_fecha_elaboro)");
                    });
                    if($sucursal->sucursal_codigo == 1){
                        $query->whereIn('factura1_sucursal', [$sucursal->sucursal_codigo, 18]);
                    }
                    $query->whereBetween('nota1_fecha_elaboro', [$fechai, $fechaf]);
                    $query->orWhereBetween('nota1_fecha_anulo', [$fechai, $fechaf]);
                    $query->groupBy('agrupacion', 'grupo', 'unificacion', 'linea', 'tipo', 'valornota', 'brutofactura', 'descuentofactura', 'ivafactura', 'anulada');
                    $query->unionAll($devolucionunion);
                    $query->orderByRaw("agrupacion asc, grupo asc, unificacion asc");
                    $detalles = $query->get();

                    // Recorrer detalle del reporte
                    foreach ($detalles as $item) {
                            if( $item->tipo == 'N' ){
                                $valorfactura = $item->brutofactura-$item->descuentofactura;
                                $porcentajeiva = ($item->ivafactura*100/$valorfactura)/100;
                                $valornota = $item->valornota/(1+$porcentajeiva);
                                $porcentajeitem = (($item->ventas-$item->descuentos)*100/$valorfactura)/100;
                                $valordescuento = $valornota*$porcentajeitem;

                                if( $item->anulada )
                                    $valordescuento= -1*$valordescuento;

                                $item->ventas = 0;
                                $item->descuentos = $valordescuento;
                                $item->devoluciones = 0;
                                $item->valor = 0;
                                $item->costos = 0;
                            }

                            if( $item->tipo == 'D' )
                                $item->costos = -1*$item->costos;

                            /**
                            * cdb1 -> ventas
                            * cdb2 -> descuentos
                            * cdb3 -> devoluciones
                            * cdb4 -> costos
                            * cin1 -> sucursal_codigo
                            * cin2 -> cod_agrupacion
                            * cin3 -> cod_grupo
                            * cin4 -> cod_unificacion
                            */
                            $auxiliar = new AuxiliarReporte;
                            $auxiliar->cdb1 = $item->ventas;
                            $auxiliar->cdb2 = $item->descuentos;
                            $auxiliar->cdb3 = $item->devoluciones;
                            $auxiliar->cdb4 = $item->costos;
                            $auxiliar->cin1 = $sucursal->sucursal_codigo;
                            $auxiliar->cin2 = $item->agrupacion;
                            $auxiliar->cin3 = $item->grupo;
                            $auxiliar->cin4 = $item->unificacion;
                            $auxiliar->save();
                    }
                }

                $data = [];
                $agrupaciones = ConfiguraSabana::getAgrupaciones();
                foreach($agrupaciones as $agrupacion) {
                    $detalle = new \stdClass();
                    $detalle->agrupacion = $agrupacion->agrupacion;
                    $detalle->nombre = $agrupacion->nom_agrupacion;

                    $grupos = ConfiguraSabana::getGrupos($agrupacion->agrupacion);
                    foreach($grupos as $grupo) {
                        $itemsgrupo = new \stdClass();
                        $itemsgrupo->grupo = $grupo->grupo;
                        $itemsgrupo->nombre = $grupo->nom_grupo;

                        $unificaciones = ConfiguraSabana::getUnificaciones($agrupacion->agrupacion, $grupo->grupo);
                        foreach($unificaciones as $unificacion){
                            $itemunificacion = new \stdClass();
                            $itemunificacion->unificacion = $unificacion->unificacion;
                            $itemunificacion->nombre = $unificacion->nom_unificacion;

                            foreach ($sucursales as $sucursal) {
                                $query = AuxiliarReporte::query();
                                $query->select('cin1 as sucursal', DB::raw("SUM(cdb1) as ventas, SUM(cdb2) as descuentos, SUM(cdb3) as devoluciones, SUM(cdb1-cdb2-cdb3) as total, SUM(cdb4) as costos"));
                                $query->where('auxiliarreporte.cin1', $sucursal->sucursal_codigo);
                                $query->where('auxiliarreporte.cin2', $agrupacion->agrupacion);
                                $query->where('auxiliarreporte.cin3', $grupo->grupo);
                                $query->where('auxiliarreporte.cin4', $unificacion->unificacion);
                                $query->groupBy('sucursal');
                                $auxiliar = $query->first();

                                // Traer presupuestos
                                $query = DB::table('presupuesto');
                                $query->select('configurasabana_agrupacion as agrupacion', 'configurasabana_grupo as grupo', 'configurasabana_unificacion as unificacion', DB::raw("SUM(presupuesto_valor) as p_valor"));
                                $query->join('configurasabana', 'presupuesto.presupuesto_lineanegocio', '=', 'configurasabana.configurasabana_lineanegocio');
                                $query->where('presupuesto_sucursal', $sucursal->sucursal_codigo);
                                $query->where('configurasabana_agrupacion', $agrupacion->agrupacion);
                                $query->where('configurasabana_grupo', $grupo->grupo);
                                $query->where('configurasabana_unificacion', $unificacion->unificacion);
                                if( $anoi != $anof ){
                                    $query->where(function ($query) use ($mesi, $anoi, $mesf, $anof){
                                        $query->where(function ($query) use ($mesi, $anoi){
                                            $query->whereBetween('presupuesto_mes', [$mesi, 12]);
                                            $query->where('presupuesto_ano', $anoi);
                                        });
                                        $query->orWhere(function ($query) use ($mesf, $anof){
                                            $query->whereBetween('presupuesto_mes', [1, $mesf]);
                                            $query->where('presupuesto_ano', $anof);
                                        });
                                    });
                                } else {
                                    $query->whereBetween('presupuesto_mes', [$mesi, $mesf]);
                                    $query->whereBetween('presupuesto_ano', [$anoi, $anof]);
                                }
                                $query->groupBy('agrupacion', 'grupo', 'unificacion');
                                $presupuestos = $query->first();

                                // Validar que tenga valor && calcular dats restantes
                                $presupuesto = !empty($presupuestos) ? $presupuestos->p_valor : 0;

                                if( $auxiliar instanceof AuxiliarReporte ){
                                    $auxiliar->sucursal = $sucursal->sucursal_nombre;
                                    $auxiliar->porcentaje = ($presupuesto != 0) ? ($auxiliar->total*100)/$presupuesto : 0;
                                    $auxiliar->presupuesto = $presupuesto;
                                    $auxiliar->margen = $auxiliar->total-$auxiliar->costos;
                                    $auxiliar->p_margen = ($auxiliar->total != 0) ? (100*($auxiliar->total-$auxiliar->costos))/$auxiliar->total : 0;
                                    $auxiliar = $auxiliar->toArray();

                                }else{
                                    $auxiliar = array(
                                        'sucursal' => $sucursal->sucursal_nombre,
                                        'ventas' => 0,
                                        'descuentos' => 0,
                                        'devoluciones' => 0,
                                        'total' => 0,
                                        'presupuesto' => $presupuesto,
                                        'porcentaje' => 0,
                                        'costos' => 0,
                                        'margen' => 0,
                                        'p_margen' => 0
                                    );
                                }
                                $itemunificacion->lineas[] = $auxiliar;
                            }
                            $itemsgrupo->unificaciones[] = $itemunificacion;
                        }

                        // Calcular valores totales por grupo
                        $totalgrupos = [];
                        foreach ($sucursales as $key => $sucursal) {
                            $suc = new \stdClass();
                            $suc->sucursal = $sucursal->sucursal_nombre;

                            // Variables para calcular total x grupo
                            $s_ventas = $s_descuentos = $s_devoluciones = $s_totales = $s_presupuestos = $s_costos = 0;
                            foreach ($itemsgrupo->unificaciones as $lineas) {

                                $filtered = array_where($lineas->lineas, function ($item) use($sucursal){
                                    return $item['sucursal'] == $sucursal->sucursal_nombre;
                                });

                                $s_ventas += $filtered[$key]['ventas'];
                                $s_descuentos += $filtered[$key]['descuentos'];
                                $s_devoluciones += $filtered[$key]['devoluciones'];
                                $s_totales += $filtered[$key]['total'];
                                $s_presupuestos += $filtered[$key]['presupuesto'];
                                $s_costos += $filtered[$key]['costos'];
                            }

                            $suc->ventas = $s_ventas;
                            $suc->descuentos = $s_descuentos;
                            $suc->devoluciones = $s_devoluciones;
                            $suc->totales = $s_totales;
                            $suc->presupuestos = $s_presupuestos;
                            $suc->porcentajes = ($s_presupuestos != 0) ? ($s_totales*100)/$s_presupuestos : 0;
                            $suc->costos = $s_costos;
                            $suc->margenes = $s_totales-$s_costos;
                            $suc->p_margenes = ($s_totales != 0) ? (100*($s_totales-$s_costos))/$s_totales : 0;
                            $totalgrupos[] = $suc;
                        }

                        $itemsgrupo->totales = $totalgrupos;
                        $detalle->grupos[] = $itemsgrupo;
                    }

                    // Calcular valores totales por agrupacion
                    $totalagrupaciones = [];
                    foreach ($sucursales as $key => $sucursal) {
                        $suc = new \stdClass();
                        $suc->sucursal = $sucursal->sucursal_nombre;

                        // Variables para calcular total x agrupacion
                        $a_ventas = $a_descuentos = $a_devoluciones = $a_totales = $a_presupuestos = $a_costos = 0;
                        foreach ($detalle->grupos as $totales) {
                            $filtered = array_where($totales->totales, function ($item) use($sucursal){
                                return $item->sucursal == $sucursal->sucursal_nombre;
                            });

                            $a_ventas += $filtered[$key]->ventas;
                            $a_descuentos += $filtered[$key]->descuentos;
                            $a_devoluciones += $filtered[$key]->devoluciones;
                            $a_totales += $filtered[$key]->totales;
                            $a_presupuestos += $filtered[$key]->presupuestos;
                            $a_costos += $filtered[$key]->costos;
                        }

                        $suc->ventas = $a_ventas;
                        $suc->descuentos = $a_descuentos;
                        $suc->devoluciones = $a_devoluciones;
                        $suc->totales = $a_totales;
                        $suc->presupuestos = $a_presupuestos;
                        $suc->porcentajes = ($a_presupuestos != 0) ? ($a_totales*100)/$a_presupuestos : 0;
                        $suc->costos = $a_costos;
                        $suc->margenes = $a_totales-$a_costos;
                        $suc->p_margenes = ($a_totales != 0) ? (100*($a_totales-$a_costos))/$a_totales : 0;
                        $totalagrupaciones[] = $suc;
                    }

                    $detalle->totales = $totalagrupaciones;
                    $data[] = $detalle;
                }

                // Preparar datos reporte
                $title = "Reporte Sabana de ventas costos";
                $type = $request->type;
                switch ($type) {
                    case 'pdf':
                        $pdf = App::make('dompdf.wrapper');
                        $pdf->loadHTML(View::make('reports.commercial.sabanaventas.reporte', compact('data', 'title', 'type', 'fechai', 'fechaf'))->render());
                        $pdf->setPaper('A4', 'landscape')->setWarnings(false);
                        return $pdf->stream(sprintf('%s.pdf', 'sabanaventas', date('Y_m_d')));
                    break;
                    case 'xls':
                        Excel::create(sprintf('%s_%s_%s', 'reporte_sabana_de_ventas', date('Y_m_d'), date('H_m_s')), function($excel) use ($fechai, $fechaf, $data, $title, $type) {
                        $excel->sheet('Excel', function($sheet) use($fechai, $fechaf, $data, $title, $type) {
                                $sheet->loadView('reports.commercial.sabanaventas.reporte', compact('fechai','fechaf', 'data', 'title', 'type'));
                                $sheet->setFontSize(8);
                            });
                        })->download('xls');
                    break;
                }

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
