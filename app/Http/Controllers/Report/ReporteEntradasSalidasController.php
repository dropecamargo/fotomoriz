<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\AuxiliarReporte, App\Models\Base\Sucursal;
use View, Excel, App, DB, Log;

class ReporteEntradasSalidasController extends Controller
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
            DB::beginTransaction();
            try{
                $query = DB::table('inventario');
                $query->select('inventario_documentos', 'inventario_producto','inventario_unidad_entrada','inventario_referencia');
                $query->where('inventario_unidad_entrada', '>', '0');
                $query->whereIn('inventario_documentos', ['NACI','ENTRA','TRASL','FACTU','DEVOL','ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL','ABAJA','AFALT','ASOBR']);
                $query->where('inventario_sucursal', $request->sucursal);
                $query->whereBetween('inventario_fecha_documento', [$request->fecha_inicial, $request->fecha_final]);
                $inventario_entrada = $query->get();

                // Recorrer query inventario
                foreach ($inventario_entrada as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->inventario_producto;

                    if (in_array($item->inventario_documentos, ['ENTRA','NACI'])){
                        $inventario->cin1 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['TRASL'])){
                        $inventario->cin2 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['FACTU'])){
                        $inventario->cin3 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['DEVOL'])){
                        $inventario->cin4 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL'])) {
                        $inventario->cin5 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['ABAJA','AFALT','ASOBR'])){
                        $inventario->cin6 = $item->inventario_unidad_entrada;

                    }
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
                $query->select('cch1 as referencia','producto.producto_nombre', DB::raw('sum(cin1) as entrada_entrada'), DB::raw('sum(cin2) as traslado_entrada'),
								DB::raw('sum(cin3) as facturas_entrada'), DB::raw('sum(cin4) as devoluciones_entrada'), DB::raw('sum(cin5) as remisiones_entrada'),
								DB::raw('sum(cin6) as ajustes_entrada'),DB::raw('sum(cin7) as traslado_salida'), DB::raw('sum(cin8) as facturas_salida'),
								DB::raw('sum(cin9) as remisiones_salida'), DB::raw('sum(cin10) as ajustes_salida'));
                $query->join('producto', 'cch1', '=', 'producto_serie');
                $query->groupBy('referencia', 'producto_nombre');
                $query->orderBy('referencia');
                $auxiliar = $query->get();

                DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                abort(500);
            }

            // Preparar datos reporte
            $title = sprintf('%s', 'Reporte entradas y salidas');
            $type = $request->type;
            $fecha_inicio = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;
            $sucursal = Sucursal::find($request->sucursal);

            // Generate file
            switch ($type) {
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'reporte_entradas_salidas', date('Y_m_d'), date('H_m_s')), function($excel) use ($fecha_inicio, $fecha_final, $sucursal, $auxiliar, $title, $type) {
                    $excel->sheet('Excel', function($sheet) use($fecha_inicio, $fecha_final, $sucursal, $auxiliar, $title, $type) {
                            $sheet->loadView('reports.inventory.reporteentradassalidas.reporte', compact('fecha_inicio','fecha_final','sucursal','auxiliar', 'title', 'type'));
                            $sheet->setFontSize(8);
                        });
                    })->download('xls');
                    break;
            }
        }
        return view('reports.inventory.reporteentradassalidas.index');
    }
}
