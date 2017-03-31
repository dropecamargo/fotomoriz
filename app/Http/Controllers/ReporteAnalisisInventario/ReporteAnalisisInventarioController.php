<?php

namespace App\Http\Controllers\ReporteAnalisisInventario;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, Excel, App, DB, Log;

use App\Models\Base\AuxiliarReporte;

class ReporteAnalisisInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		if($request->has('type'))
        {
			DB::beginTransaction();
            try{

				//campos auxiliar
				// ventas
				// cch1 : referencia producto
				// cdb1, cdb2, cdb3, cdb4 : costo ventas / devoluciones
			    // cin1, cin2, cin3, cin4 : unidades vendidas / devueltas
				// existencias
				// cch1 : referencia producto
				// cdb5, cdb6, cdb7, cdb8 : costo al cierre
			    // cin6, cin6, cin7, cin8 : unidades al cierre
				// transito
				// cch1 : referencia producto
				// cdb9 : costo pedidos de importacion
			    // cin9 : unidades pedidos de importacion
				
				// ventas
				$query = DB::table('factura2');
                $query->select('factura2_producto', 'factura2_unidades_vendidas', DB::raw('(factura2_unidades_vendidas * factura2_costo) as costo'));		
				$query->join('factura1', function($join) {
					$join->on('factura1_numero', '=', 'factura2_numero');
					$join->on('factura1_sucursal', '=', 'factura2_sucursal');
				});
				$query->where('factura1_anulada', '=', False);
				$query->where('factura2_tipoinventario', '=', '1');
				$query->whereRaw("EXTRACT(YEAR from factura1_fecha) = $request->ano");
				$query->whereRaw("EXTRACT(MONTH from factura1_fecha) = $request->mes");
                $ventas = $query->get();
				
				foreach ($ventas as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->factura2_producto;
                    $inventario->cin1 = $item->factura2_unidades_vendidas;
					$inventario->cdb1 = $item->costo;
                    $inventario->save();
                }	
				
				// devoluciones
				$query = DB::table('devolucion2');
				$query->select('devolucion2_producto', 'devolucion2_cantidad', DB::raw('(devolucion2_cantidad * devolucion2_costo) as costo'));		
				$query->join('devolucion1', function($join) {
					$join->on('devolucion1_numero', '=', 'devolucion2_numero');
					$join->on('devolucion1_sucursal', '=', 'devolucion2_sucursal');
				});
				$query->whereRaw("EXTRACT(YEAR from devolucion1_fecha_elaboro) = $request->ano");
				$query->whereRaw("EXTRACT(MONTH from devolucion1_fecha_elaboro) = $request->mes");
				$query->where('devolucion2_tipoinventario','=', '1');
                $devoluciones = $query->get();
				
							
				foreach ($devoluciones as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->devolucion2_producto;
                    $inventario->cin1 = $item->devolucion2_cantidad;
					$inventario->cdb1 = $item->costo;
                    $inventario->save();
                }

				/*
				foreach ($ventas as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->factura2_producto;
                    $inventario->cin2 = $item->factura2_unidades_vendidas;
					$inventario->cdb2 = $item->costo;
                    $inventario->save();
                }
				
				foreach ($devoluciones as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->devolucion2_producto;
                    $inventario->cin2 = $item->devolucion2_cantidad;
					$inventario->cdb2 = $item->costo;
                    $inventario->save();
                }

				
				foreach ($ventas as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->factura2_producto;
                    $inventario->cin3 = $item->factura2_unidades_vendidas;
					$inventario->cdb3 = $item->costo;
                    $inventario->save();
                }
				
				foreach ($devoluciones as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->devolucion2_producto;
                    $inventario->cin3 = $item->devolucion2_cantidad;
					$inventario->cdb3 = $item->costo;
                    $inventario->save();
                }
				
				foreach ($ventas as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->factura2_producto;
                    $inventario->cin4 = $item->factura2_unidades_vendidas;
					$inventario->cdb4 = $item->costo;
                    $inventario->save();
                }
				
				foreach ($devoluciones as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->devolucion2_producto;
                    $inventario->cin4 = $item->devolucion2_cantidad;
					$inventario->cdb4 = $item->costo;
                    $inventario->save();
                }
				
                //Salidas
                $query = DB::table('inventario');
                $query->select('inventario_documentos', 'inventario_producto','inventario_unidad_salida','inventario_referencia');
                $query->where('inventario_unidad_salida', '>', '0');
                $query->whereIn('inventario_documentos', ['TRASL','FACTU','ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL','ABAJA','AFALT','ASOBR']);
                $query->where('inventario_sucursal', $request->sucursal);
                $query->whereBetween('inventario_fecha_documento', [$request->fecha_inicial, $request->fecha_final]);
                $inventario_salida = $query->get();

                // Recorrer query inventario
                foreach ($inventario_salida as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->inventario_producto;

                    if (in_array($item->inventario_documentos, ['TRASL'])){
                        $inventario->cin7 = $item->inventario_unidad_salida;

                    }elseif (in_array($item->inventario_documentos, ['FACTU'])){
                        $inventario->cin8 = $item->inventario_unidad_salida;

                    }elseif (in_array($item->inventario_documentos, ['ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL'])) {
                        $inventario->cin9 = $item->inventario_unidad_salida;

                    }elseif (in_array($item->inventario_documentos, ['ABAJA','AFALT','ASOBR'])){
                        $inventario->cin10 = $item->inventario_unidad_salida;

                    }
                    $inventario->save();
                }

                $query = AuxiliarReporte::query();
                $query->select('cch1 as referencia','producto.producto_nombre', DB::raw('sum(cin1) as entrada_entrada'), DB::raw('sum(cin2) as traslado_entrada'), DB::raw('sum(cin3) as facturas_entrada'), DB::raw('sum(cin4) as devoluciones_entrada'), DB::raw('sum(cin5) as remisiones_entrada'), DB::raw('sum(cin6) as ajustes_entrada'),DB::raw('sum(cin7) as traslado_salida'), DB::raw('sum(cin8) as facturas_salida'), DB::raw('sum(cin9) as remisiones_salida'), DB::raw('sum(cin10) as ajustes_salida'));
                $query->join('producto', 'cch1', '=', 'producto_serie');
                $query->groupBy('referencia', 'producto_nombre');
                $query->orderBy('referencia');
                $auxiliar = $query->get();

				*/
				
                DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
				dd($e->getMessage());
                Log::error($e->getMessage());
                abort(500);
            }

            // Preparar datos reporte
            $title = sprintf('%s', 'Reporte Analisis Inventario');
            $type = $request->type;
            $mes = $request->mes;
            $ano = $request->ano;

            // Generate file
            switch ($type) {
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'reporte_analisis_inventario', date('Y_m_d'), date('H_m_s')), function($excel) use($mes, $ano,  $auxiliar, $title, $type) {
                    $excel->sheet('Excel', function($sheet) use($mes, $ano, $auxiliar, $title, $type) {
                        $sheet->loadView('reportes.reporteanalisisinventario.reporte', compact('mes','ano','auxiliar', 'title', 'type'));
                        $sheet->setFontSize(8);
                    });
                })->download('xls');
                break;
            }
        }
		return view('reportes.reporteanalisisinventario.index');
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
