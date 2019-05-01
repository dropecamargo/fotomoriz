<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\AuxiliarReporte, App\Models\Accounting\UnidadDecision, App\Models\Accounting\PresupuestoGasto, App\Models\Accounting\PlanCuentasN;
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
            ini_set('memory_limit', '-1');
            set_time_limit(0);

			DB::beginTransaction();
            try {
                // Campos auxiliar
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
                $query->where('asiento2n_nivel1', '!=', '0');
                $query->where('asiento2n_nivel2', '!=', '0');
                $query->where('asiento2n_nivel3', '0');
                $query->where('asiento2n_nivel4', '0');
                $query->where('asiento2n_nivel5', '0');
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
                $query->where('asiento2n_nivel1', '!=', '0');
                $query->where('asiento2n_nivel2', '!=', '0');
                $query->where('asiento2n_nivel3', '0');
                $query->where('asiento2n_nivel4', '0');
                $query->where('asiento2n_nivel5', '0');
                $query->union($union);
                $query->groupBy('centro', 'cuenta', 'nivel1', 'nivel2');
                $asientosn = $query->get();

                foreach($asientosn as $asienton) {
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
                    $inventario->cch1 = $asienton->cuenta;
                    $inventario->cin1 = $asienton->centro;
                    $inventario->cin2 = $asienton->nivel1;
                    $inventario->cin3 = $asienton->nivel2;
                    $inventario->save();
                }

				// Preparar datos reporte
				$title = "Reporte Gastos ARP";
				$type = $request->type;
				$mes = $request->mes;
				$ano = $request->ano;
				$nmes = config('koi.meses')[$request->mes];

                // Get unidades
                $unidades = UnidadDecision::select('unidaddecision_codigo', 'unidaddecision_nombre')->where('unidaddecision_activa', true)->orderby('unidaddecision_codigo', 'asc')->get();

				// Generate file
				switch ($type) {
					case 'xls':
						Excel::create( sprintf('%s_%s', 'reporte_arp', date('Y_m_d H_m_s') ), function ($excel) use ($mes, $ano, $nmes, $title, $type, $unidades) {
                            foreach ($unidades as $unidad) {
                                $sentencia = "
                                    SELECT cuenta, codigo, nivel1, nivel2, concepto, SUM(mes) as mes, SUM(anoacu) as anoacu, SUM(arpmes) as arpmes, SUM(arpacu) as arpacu
                                    FROM (
                                        SELECT plancuentasn_nombre AS cuenta, plancuentasn_cuenta AS codigo, plancuentasn_concepto AS concepto, cin2 AS nivel1, cin3 AS nivel2, sum(cdb1)/1000000 AS mes, sum(cdb2)/1000000 AS anoacu, sum(cdb3)/1000000 AS arpmes, sum(cdb4)/1000000 AS arpacu
                                        FROM auxiliarreporte
                                        INNER JOIN plancuentasn ON auxiliarreporte.cch1 = plancuentasn.plancuentasn_cuenta
                                        WHERE cin1 = $unidad->unidaddecision_codigo
                                        GROUP BY cuenta, codigo, nivel1, nivel2, concepto
                                        UNION
                                        SELECT plancuentasn_nombre AS cuenta, plancuentasn_cuenta AS codigo, plancuentasn_concepto AS concepto, 0 AS nivel1, 0 AS nivel2, 0 AS mes, 0 AS anoacu, 0 AS arpmes, 0 AS arpacu
                                        FROM plancuentasn
                                        WHERE 
                                        plancuentasn_clase = 5 
                                        AND plancuentasn_grupo = 1
                                        AND plancuentasn_nivel1 != 0 
                                        AND plancuentasn_nivel2 != 0 
                                        AND plancuentasn_nivel3 = 0 
                                        AND plancuentasn_nivel4 = 0 
                                        AND plancuentasn_nivel5 = 0
                                        GROUP BY cuenta, codigo, nivel1, nivel2, concepto
                                    ) x
                                    GROUP BY cuenta, codigo, nivel1, nivel2, concepto
                                    ORDER BY nivel1 ASC, concepto ASC";
                                $auxiliar = DB::select($sentencia);

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
