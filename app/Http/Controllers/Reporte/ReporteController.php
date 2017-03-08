<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, Excel, App, DB;

use App\Models\Base\AuxiliarReporte; 

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $sucursal = DB::table('sucursal')
                    ->select('sucursal_codigo','sucursal_nombre')
                    ->where('sucursal_activa', true)
                    ->orderBy('sucursal_codigo', 'asc')
                    ->lists('sucursal_nombre', 'sucursal_codigo');

        if($request->has('type'))
        {
            DB::beginTransaction();
            try{
                $query = DB::table('inventario');
                $query->select('inventario_documentos', 'inventario_producto','inventario_unidad_entrada');
                $query->where('inventario_unidad_entrada', '>', '0');
                $query->whereIn('inventario_documentos', ['NACI','ENTRA','TRASL','FACTU','DEVOL','ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL','ABAJA','AFALT','ASOBR']);
                $query->limit(100);
                $inventario = $query->get();

                foreach ($inventario as $item) {
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->inventario_producto;

                    if (in_array($item->inventario_documentos, ['ENTRA','NACI'])){
                        $inventario->cin1 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['TRASL'])) {
                        $inventario->cin2 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['FACTU'])){
                        $inventario->cin3 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['DEVOL'])) {
                        $inventario->cin4 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['ACOMP','ADEVP','AIARR','APRES','ECANI','ENVIO','RCONS','REMRE','RGRAN','RIARR','ROBSE','RPROV','RPRUE','RRECL'])) {
                        $inventario->cin5 = $item->inventario_unidad_entrada;

                    }elseif (in_array($item->inventario_documentos, ['ABAJA','AFALT','ASOBR'])){
                        $inventario->cin6 = $item->inventario_unidad_entrada;

                    }
                
                }

                // $inventario->save();

                DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
                abort(500);
            }

            // Preparar datos reporte
            $sql = '';
            $title = sprintf('%s %s %s', 'Reportes', $request->fecha_inicio, $request->fecha_fin);
            $type = $request->type;
            $fh_inicio = $request->fecha_inicio;
            $fh_fin = $request->fecha_fin;
            $sucursal = $request->sucursal;

            // Generate file
            switch ($type) {
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'reporte', date('Y_m_d'), date('H_m_s')), function($excel) use($title, $type) {
                    $excel->sheet('Excel', function($sheet) use($title, $type) {
                        $sheet->loadView('reportes.reportegeneral.reporte', compact('title', 'type'));
                        $sheet->setFontSize(8);
                    });
                })->download('xls');
                break;
            }
        }
        return view('reportes.reportegeneral.index', ['sucursal' => $sucursal]);
    }
}
