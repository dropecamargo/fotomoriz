<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\AuxiliarReporte, App\Models\Accounting\UnidadDecision, App\Models\Accounting\PresupuestoGasto;
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
                // cdb2 : arp mes
                // cdb3 : arp año
                // cdb4 : movimiento año
                // cbi1 : numero de la cuenta

                // Asientos
                $query = DB::table('asiento2n');
                $query->select('asiento2n_centrocosto as centro', 'asiento2n_plancuentasn as cuenta', 'asiento2n_nivel1 as nivel1', 'asiento2n_nivel2 as nivel2', DB::raw('(asiento2n_debito - asiento2n_credito) as valor_mensual'), DB::raw('0 as valor_anual'));
                $query->where('asiento2n_mes', $request->mes);
                $query->where('asiento2n_ano', $request->ano);
                $query->where('asiento2n_clase', '5');
                $query->where(function ($query){
                    $query->where('asiento2n_grupo', '1');
                    $query->orWhere('asiento2n_grupo', '2');
                });
                $query->join('plancuentasn', 'asiento2n_plancuentasn', '=', 'plancuentasn_cuenta');
                $query->whereRaw('asiento2n_nivel1 = plancuentasn_nivel1');
                $query->whereRaw('asiento2n_nivel2 = plancuentasn_nivel2');
                $union = $query;

                // Acumulado(REAL) de asienton
                $query = DB::table('asiento2n');
                $query->select('asiento2n_centrocosto as centro', 'asiento2n_plancuentasn as cuenta', 'asiento2n_nivel1 as nivel1', 'asiento2n_nivel2 as nivel2', DB::raw('0 as valor_mensual'), DB::raw('SUM(asiento2n_debito - asiento2n_credito) as valor_anual'));
                $query->whereBetween('asiento2n_mes', ['1', $request->mes]);
                $query->where('asiento2n_ano', $request->ano);
                $query->where('asiento2n_clase', '5');
                $query->where(function ($query){
                    $query->where('asiento2n_grupo', '1');
                    $query->orWhere('asiento2n_grupo', '2');
                });
                $query->groupBy('centro', 'cuenta', 'nivel1', 'nivel2');
                $query->unionAll($union);
                $asientosn = $query->get();

                foreach($asientosn as $asienton){
                    // Mes(ARP) de presupuestog
                    $query = PresupuestoGasto::query();
                    $query->select('presupuestog_valor');
                    $query->where('presupuestog_mes', $request->mes);
                    $query->where('presupuestog_ano', $request->ano);
                    $query->where('presupuestog_unidaddecision', $asienton->centro);
                    $query->where('presupuestog_nivel1', $asienton->nivel1);
                    $query->where('presupuestog_nivel2', $asienton->nivel2);
                    $presupuestogm = $query->first();

                    // Acumulado(ARP) de presupuestog
                    $query = PresupuestoGasto::query();
                    $query->selectRaw('SUM(presupuestog_valor) as presupuestog_valor');
                    $query->whereBetween('presupuestog_mes', ['1', $request->mes]);
                    $query->where('presupuestog_ano', $request->ano);
                    $query->where('presupuestog_unidaddecision', $asienton->centro);
                    $query->where('presupuestog_nivel1', $asienton->nivel1);
                    $query->where('presupuestog_nivel2', $asienton->nivel2);
                    $presupuestoga = $query->first();

                    $inventario = new AuxiliarReporte;
                    $inventario->cdb1 = $asienton->valor_mensual;
                    $inventario->cdb2 = $asienton->valor_anual;
                    $inventario->cdb3 = isset( $presupuestogm->presupuestog_valor ) ? $presupuestogm->presupuestog_valor : 0;
                    $inventario->cdb4 = isset( $presupuestoga->presupuestog_valor ) ? $presupuestoga->presupuestog_valor : 0;
                    $inventario->cbi1 = $asienton->cuenta;
                    $inventario->cin1 = $asienton->centro;
                    $inventario->save();
                }

				// Preparar datos reporte
				$title = "Reporte Gastos ARP";
				$type = $request->type;
				$mes = $request->mes;
				$ano = $request->ano;
				$nmes = config('koi.meses')[$request->mes];

				// Generate file
				switch ($type)
				{
					case 'xls':
						Excel::create( sprintf('%s_%s_%s', 'reporte_arp', date('Y_m_d'), date('H_m_s') ), function($excel) use($mes, $ano, $nmes, $title, $type){

                                $unidades = UnidadDecision::select('unidaddecision_codigo', 'unidaddecision_nombre')->where('unidaddecision_activa', true)->orderby('unidaddecision_nombre', 'asc')->get();
                                foreach ($unidades as $unidad){
                                    // para generar reporte
    								$query = AuxiliarReporte::query();
    								$query->select('plancuentasn_nombre as cuenta', 'plancuentasn_cuenta as codigo', 'cin2 as nivel1', 'cin3 as nivel2',
                                        DB::raw('
                                            sum(cdb1)/1000000 as mes,
                                            sum(cdb2)/1000000 as anoacu,
                                            sum(cdb3)/1000000 as arpmes,
                                            sum(cdb4)/1000000 as arpacu'
                                        )
                                    );
                                    $query->where('cin1', $unidad->unidaddecision_codigo);
                                    $query->join('plancuentasn', 'cbi1', '=', 'plancuentasn_cuenta');
                                    $query->groupBy('cuenta', 'codigo', 'nivel1', 'nivel2');
                                    $query->orderby('codigo');
                                    $auxiliar = $query->get();

                                    if( count($auxiliar) <= 0 ){
                                        continue;
                                    }

                                    $expression = array( "[","]","*","?",":","/",'"',"\\");
                                    $name = str_replace($expression, '', $unidad->unidaddecision_nombre);
    								$title = "$name";
    								$excel->sheet('Excel', function($sheet) use ($mes, $ano, $nmes, $auxiliar, $title, $type, $unidad){
    									$sheet->loadView('reports.accounting.reportearp.reporte', compact('mes','ano', 'nmes', 'auxiliar', 'title', 'type', 'unidad'));
                                        $sheet->setWidth(array('A' => 20, 'B' => 70, 'C' => 2, 'G' => 2, 'L' => 2));
                                        $sheet->setHeight(array(1 => 15, 2 => 15));
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
