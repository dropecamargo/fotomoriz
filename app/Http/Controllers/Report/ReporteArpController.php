<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\AuxiliarReporte, App\Models\Base\Unidaddecision;
use View, Excel, App, DB, Log;

class ReporteArpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
        * Request has type
        **/
		if($request->filled('type')){
			DB::beginTransaction();
            try{
				//campos auxiliar
				// cin1 : unidad decision
				// cin2 : nivel1
				// cin3 : nivel2
				// cdb1 : movimiento mes
				// cdb2 : movimiento año
				// cdb3 : presupuesto mes
				// cdb4 : presupuesto año
				// cbi1 : numero de la cuenta

				// Auxiliar mes
				$query = DB::table('asiento2n');
                $query->select('asiento2n_centrocosto', 'asiento2n_plancuentasn', DB::raw('(asiento2n_debito-asiento2n_credito) as valor'));
				$query->where('asiento2n_mes', $request->mes);
				$query->where('asiento2n_ano', $request->ano);
				$query->where('asiento2n_clase', '5');
				$query->where(function ($query){
					$query->where('asiento2n_grupo', '1');
					$query->orwhere('asiento2n_grupo', '2');
				});
				$query->orderby('asiento2n_plancuentasn');
                $gastos = $query->get();

				foreach($gastos as $item){
                    $inventario = new AuxiliarReporte;
					$inventario->cin3 = $item->asiento2n_centrocosto;
					$inventario->cdb1 = $item->valor;
					$inventario->cbi1 = $item->asiento2n_plancuentasn;
                    $inventario->save();
                }

				// Preparar datos reporte
				$title = sprintf('%s', 'Reporte Gastos ARP');
				$type = $request->type;
				$mes = $request->mes;
				$ano = $request->ano;
				$nmes=config('koi.meses')[$request->mes];

				// Generate file
				switch ($type)
				{
					case 'xls':
						Excel::create(sprintf('%s_%s_%s', 'reporte_arp', date('Y_m_d'), date('H_m_s')), function($excel) use($mes, $ano, $nmes, $title, $type){
							$unidades=Unidaddecision::getUnidaddecision();

                            foreach ($unidades as $key=>$value){
								// para generar reporte
								$query = AuxiliarReporte::query();
								$query->select('p.plancuentasn_nombre as cuenta', 'cin1 as nivel1', 'cin2 as nivel2', 'u.unidaddecision_nombre as unidad',
									DB::raw('sum(cdb1)/1000000 as mes'), DB::raw('sum(cdb2)/1000000 as ano'),
									DB::raw('sum(cdb3)/1000000 as pmes'), DB::raw('sum(cdb4)/1000000 as pano')
								);
								$query->join('plancuentasn as p', 'cbi1', '=', 'p.plancuentasn_cuenta');
								$query->join('unidaddecision as u', 'cin3', '=', 'u.unidaddecision_codigo');
								$query->where('cin3', $key);
								$query->groupBy('cuenta', 'nivel1', 'nivel2', 'unidad');
								$query->orderby('cuenta');
								$auxiliar = $query->get();

								$title = sprintf('%s', $key);
								$excel->sheet('Excel', function($sheet) use($mes, $ano, $nmes, $auxiliar, $title, $type){
									$sheet->loadView('reports.accounting.reportearp.reporte', compact('mes','ano', 'nmes', 'auxiliar', 'title', 'type'));
									$sheet->setFontSize(8);
								});
                            }
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
        return view('reports.accounting.reportearp.index');
    }
}
