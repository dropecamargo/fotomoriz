<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\AuxiliarReporte;
use View, Excel, App, DB, Log;

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

			    if($request->mes==1){
					$xmes0=9;  $xano0=$request->ano-1;
					$xmes1=10; $xano1=$request->ano-1;
					$xmes2=11; $xano2=$request->ano-1;
					$xmes3=12; $xano3=$request->ano-1;
				}
				if($request->mes==2){
					$xmes0=10; $xano0=$request->ano-1;
					$xmes1=11; $xano1=$request->ano-1;
					$xmes2=12; $xano2=$request->ano-1;
					$xmes3=1;  $xano3=$request->ano;
				}
				if($request->mes==3){
					$xmes0=11; $xano0=$request->ano-1;
					$xmes1=12; $xano1=$request->ano-1;
					$xmes2=1;  $xano2=$request->ano;
					$xmes3=2;  $xano3=$request->ano;
				}
				if($request->mes==4){
					$xmes0=12; $xano0=$request->ano-1;
					$xmes1=1;  $xano1=$request->ano;
					$xmes2=2;  $xano2=$request->ano;
					$xmes3=3;  $xano3=$request->ano;
				}
				if($request->mes>4){
					$xmes0=$request->mes-4; $xano0=$request->ano;
					$xmes1=$request->mes-3; $xano1=$request->ano;
					$xmes2=$request->mes-2; $xano2=$request->ano;
					$xmes3=$request->mes-1; $xano3=$request->ano;
				}
				$xmes4=$request->mes; $xano4=$request->ano;

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
				// cdb9 : costo pedidos nacionales
			    // cin9 : unidades pedidos nacionales

				// ventas
				$xmeses='4';

                // si es el mes en curso, deben tomar los 4 meses anteriores
				if(date('n')==$xmes4  && date('o')==$xano4){
					$xm1=$xmes0; $xa1=$xano0;
					$xm2=$xmes1; $xa2=$xano1;
					$xm3=$xmes2; $xa3=$xano2;
					$xm4=$xmes3; $xa4=$xano3;
					$xmeses='5';
				}else{
					$xm1=$xmes1; $xa1=$xano1;
					$xm2=$xmes2; $xa2=$xano2;
					$xm3=$xmes3; $xa3=$xano3;
					$xm4=$xmes4; $xa4=$xano4;
					$xmeses='4';
				}

				$query = DB::table('factura2');
                $query->select('factura2_producto', DB::raw('sum(factura2_unidades_vendidas) as unidades'), DB::raw('sum((factura2_unidades_vendidas * factura2_costo)) as costo'), DB::raw('EXTRACT(MONTH from factura1_fecha) as mes'));
                $query->join('factura1', function($join){
                	$join->on('factura1_numero', '=', 'factura2_numero');
                	$join->on('factura1_sucursal', '=', 'factura2_sucursal');
                });
                $query->where('factura1_anulada', False);
                $query->where('factura2_tipoinventario', '1');
                $query->where(function ($query) use ($xa1, $xm1, $xa2, $xm2, $xa3, $xm3, $xa4, $xm4){
					$query->where(function ($query) use ($xa1, $xm1){
						$query->whereRaw("EXTRACT(YEAR from factura1_fecha) = $xa1");
						$query->whereRaw("EXTRACT(MONTH from factura1_fecha) = $xm1");
					});
					$query->orWhere(function ($query) use ($xa2, $xm2){
						$query->whereRaw("EXTRACT(YEAR from factura1_fecha) = $xa2");
						$query->whereRaw("EXTRACT(MONTH from factura1_fecha) = $xm2");
					});
					$query->orwhere(function ($query) use ($xa3, $xm3){
						$query->whereRaw("EXTRACT(YEAR from factura1_fecha) = $xa3");
						$query->whereRaw("EXTRACT(MONTH from factura1_fecha) = $xm3");
					});
					$query->orWhere(function ($query) use ($xa4, $xm4){
						$query->whereRaw("EXTRACT(YEAR from factura1_fecha) = $xa4");
						$query->whereRaw("EXTRACT(MONTH from factura1_fecha) = $xm4");
					});
				});
				$query->groupBy('factura2_producto', 'mes');
                $ventas = $query->get();

				foreach ($ventas as $item){
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->factura2_producto;
					if($item->mes==$xm1){
						$inventario->cin1 = $item->unidades;
						$inventario->cdb1 = $item->costo;
					}
					if($item->mes==$xm2){
						$inventario->cin2 = $item->unidades;
						$inventario->cdb2 = $item->costo;
					}
					if($item->mes==$xm3){
						$inventario->cin3 = $item->unidades;
						$inventario->cdb3 = $item->costo;
					}
					if($item->mes==$xm4){
						$inventario->cin4 = $item->unidades;
						$inventario->cdb4 = $item->costo;
					}
                    $inventario->save();
                }

				// devoluciones
				$query = DB::table('devolucion2');
				$query->select('devolucion2_producto', DB::raw('sum(devolucion2_cantidad) as unidades'), DB::raw('sum((devolucion2_cantidad * devolucion2_costo)) as costo'), DB::raw('EXTRACT(MONTH from devolucion1_fecha_elaboro) as mes'));
				$query->join('devolucion1', function($join) {
					$join->on('devolucion1_numero', '=', 'devolucion2_numero');
					$join->on('devolucion1_sucursal', '=', 'devolucion2_sucursal');
				});
				$query->where(function ($query) use ($xa1, $xm1, $xa2, $xm2, $xa3, $xm3, $xa4, $xm4){
					$query->where(function ($query) use ($xa1, $xm1){
						$query->whereRaw("EXTRACT(YEAR from devolucion1_fecha_elaboro) = $xa1");
						$query->whereRaw("EXTRACT(MONTH from devolucion1_fecha_elaboro) = $xm1");
					});
					$query->orWhere(function ($query) use ($xa2, $xm2){
						$query->whereRaw("EXTRACT(YEAR from devolucion1_fecha_elaboro) = $xa2");
						$query->whereRaw("EXTRACT(MONTH from devolucion1_fecha_elaboro) = $xm2");
					});
					$query->orwhere(function ($query) use ($xa3, $xm3){
						$query->whereRaw("EXTRACT(YEAR from devolucion1_fecha_elaboro) = $xa3");
						$query->whereRaw("EXTRACT(MONTH from devolucion1_fecha_elaboro) = $xm3");
					});
					$query->orWhere(function ($query) use ($xa4, $xm4){
						$query->whereRaw("EXTRACT(YEAR from devolucion1_fecha_elaboro) = $xa4");
						$query->whereRaw("EXTRACT(MONTH from devolucion1_fecha_elaboro) = $xm4");
					});
				});
				$query->where('devolucion2_tipoinventario', '1');
				$query->groupBy('devolucion2_producto', 'mes');
				$devoluciones = $query->get();

				foreach ($devoluciones as $item){
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->devolucion2_producto;
					if($item->mes==$xm1){
						$inventario->cin1 = $item->unidades*(-1);
						$inventario->cdb1 = $item->costo*(-1);
					}
					if($item->mes==$xm2){
						$inventario->cin2 = $item->unidades*(-1);
						$inventario->cdb2 = $item->costo*(-1);
					}
					if($item->mes==$xm3){
						$inventario->cin3 = $item->unidades*(-1);
						$inventario->cdb3 = $item->costo*(-1);
					}
					if($item->mes==$xm4){
						$inventario->cin4 = $item->unidades*(-1);
						$inventario->cdb4 = $item->costo*(-1);
					}
                    $inventario->save();
                }

				//  Existencias a cierre
				$query = DB::table('cierreinventario');
				$query->select('cierreinventario_producto',	DB::raw('sum(cierreinventario_cantidad) as unidades'), DB::raw('sum(cierreinventario_cantidad*cierreinventario_costo_pesos) as costo'),	'cierreinventario_mes as mes');
				$query->where('cierreinventario_tipoinventario', '1');
				$query->where(function ($query) use ($xano1, $xmes1, $xano2, $xmes2, $xano3, $xmes3, $xano4, $xmes4){
					$query->where(function ($query) use ($xano1, $xmes1){
						$query->where('cierreinventario_mes','=', $xmes1);
						$query->where('cierreinventario_ano','=', $xano1);
					});
					$query->orWhere(function ($query) use ($xano2, $xmes2){
						$query->where('cierreinventario_mes','=', $xmes2);
						$query->where('cierreinventario_ano','=', $xano2);
					});
					$query->orwhere(function ($query) use ($xano3, $xmes3){
						$query->where('cierreinventario_mes','=', $xmes3);
						$query->where('cierreinventario_ano','=', $xano3);
					});
					$query->orWhere(function ($query) use ($xano4, $xmes4){
						$query->where('cierreinventario_mes','=', $xmes4);
						$query->where('cierreinventario_mes','=', $xmes4);
						$query->where('cierreinventario_ano','=', $xano4);
					});
				});
				$query->where('cierreinventario_cantidad','>', '0');
				$query->whereNotIn('cierreinventario_sucursal', [6, 7, 8, 10, 15]);
				$query->groupBy('cierreinventario_producto', 'mes');
                $existencias = $query->get();

				foreach ($existencias as $item){
                    $inventario = new AuxiliarReporte;
                    $inventario->cch1 = $item->cierreinventario_producto;
					if($item->mes==$xmes1){
						$inventario->cin5 = $item->unidades;
						$inventario->cdb5 = $item->costo;
					}
					if($item->mes==$xmes2){
						$inventario->cin6 = $item->unidades;
						$inventario->cdb6 = $item->costo;
					}
					if($item->mes==$xmes3){
						$inventario->cin7 = $item->unidades;
						$inventario->cdb7 = $item->costo;
					}
					if($item->mes==$xmes4){
						$inventario->cin8 = $item->unidades;
						$inventario->cdb8 = $item->costo;
					}
                    $inventario->save();
                }

                // si es mes en curso, se mantiene inventario actual
				if(date('n')==$xmes4  && date('o')==$xano4){
					$query = DB::table('prodbode');
					$query->select('prodbode_producto as referencia', DB::raw('sum(prodbode_unidades) as unidades'), DB::raw('sum(prodbode_unidades*producto_costo_pesos) as costo'));
					$query->join('producto', 'prodbode_producto', '=', 'producto_serie');
					$query->where('producto_tipoinventario', '1');
					$query->where('prodbode_unidades','>', '0');
					$query->whereNotIn('prodbode_sucursal', [6, 7, 8, 10, 15]);
					$query->groupBy('prodbode_producto');
					$existencias = $query->get();

					foreach ($existencias as $item){
						$inventario = new AuxiliarReporte;
						$inventario->cch1 = $item->referencia;
						$inventario->cin8 = $item->unidades;
						$inventario->cdb8 = $item->costo;
						$inventario->save();
					}
				}

				// pedidos de importacion pendientes
				$query = DB::table('pedidoimportacion2');
				$query->select('pedidoimportacion2_producto as referencia', DB::raw('sum(pedidoimportacion2_saldo) as unidades'), DB::raw('sum(pedidoimportacion2_saldo*pedidoimportacion2_costo_dolares) as costo'));
				$query->join('producto', 'pedidoimportacion2_producto', '=', 'producto_serie');
				$query->where('pedidoimportacion2_saldo','>', '0');
				$query->groupBy('pedidoimportacion2_producto');

				$existencias = $query->get();
				foreach ($existencias as $item){
					$inventario = new AuxiliarReporte;
					$inventario->cch1 = $item->referencia;
					$inventario->cin9 = $item->unidades;
					$inventario->cdb9 = $item->costo;
					$inventario->save();
				}

				// pedidos nacionales pendientes
				$query = DB::table('pedidocompra2');
				$query->select('pedidocompra2_producto as referencia', DB::raw('sum(pedidocompra2_saldo) as unidades'), DB::raw('sum(pedidocompra2_saldo*pedidocompra2_costo_dolares) as costo'));
				$query->join('producto', 'pedidocompra2_producto', '=', 'producto_serie');
				$query->where('pedidocompra2_saldo','>', '0');
				$query->groupBy('pedidocompra2_producto');

				$existencias = $query->get();
				foreach ($existencias as $item){
					$inventario = new AuxiliarReporte;
					$inventario->cch1 = $item->referencia;
					$inventario->cin10 = $item->unidades;
					$inventario->cdb10 = $item->costo;
					$inventario->save();
				}

				// para generar reporte
				$query = AuxiliarReporte::query();
                $query->select('p.producto_referencia as referencia','p.producto_nombre as nombre', 'l.lineanegocio_nombre as linea',
								DB::raw('sum(cin1) as unidad1'), DB::raw('sum(cdb1) as costo1'),
								DB::raw('sum(cin2) as unidad2'), DB::raw('sum(cdb2) as costo2'),
								DB::raw('sum(cin3) as unidad3'), DB::raw('sum(cdb3) as costo3'),
								DB::raw('sum(cin4) as unidad4'), DB::raw('sum(cdb4) as costo4'),
								DB::raw('sum(cin5) as unidad5'), DB::raw('sum(cdb5) as costo5'),
								DB::raw('sum(cin6) as unidad6'), DB::raw('sum(cdb6) as costo6'),
								DB::raw('sum(cin7) as unidad7'), DB::raw('sum(cdb7) as costo7'),
								DB::raw('sum(cin8) as unidad8'), DB::raw('sum(cdb8) as costo8'),
								DB::raw('sum(cin9) as unidad9'), DB::raw('sum(cdb9) as costo9'),
								DB::raw('sum(cin10) as unidad10'), DB::raw('sum(cdb10) as costo10')
                            );
                $query->join('producto as p', 'cch1', '=', 'p.producto_serie');
				$query->join('lineanegocio as l', 'p.producto_lineanegocio', '=', 'l.lineanegocio_codigo');
                $query->groupBy('referencia', 'nombre', 'linea');
                $query->orderBy('referencia', 'nombre', 'linea');
                $auxiliar = $query->get();

                DB::rollback();
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                abort(500);
            }

            // Preparar datos reporte
			$title = sprintf('%s', 'Reporte Analisis Inventario  Unidades');
            $type = $request->type;
            $mes = $request->mes;
            $ano = $request->ano;
			$nmes0=config('koi.meses')[$xmes0];
			$nmes1=config('koi.meses')[$xmes1];
			$nmes2=config('koi.meses')[$xmes2];
			$nmes3=config('koi.meses')[$xmes3];
			$nmes4=config('koi.meses')[$xmes4];

            // Generate file
            switch ($type)
			{
                case 'xls':
                    Excel::create(sprintf('%s_%s_%s', 'reporte_analisis_inventario', date('Y_m_d'), date('H_m_s')), function($excel) use($mes, $ano, $xmeses, $nmes0, $nmes1, $nmes2, $nmes3, $nmes4, $auxiliar, $title, $type){
						$title = sprintf('%s', 'Analisis Inventario  Costos');
						$excel->sheet('Excel', function($sheet) use($mes, $ano, $xmeses, $nmes0, $nmes1, $nmes2, $nmes3, $nmes4, $auxiliar, $title, $type){
							$sheet->loadView('reports.inventory.reporteanalisisinventario.reporte', compact('mes', 'ano', 'xmeses', 'nmes0', 'nmes1', 'nmes2', 'nmes3', 'nmes4',  'auxiliar', 'title', 'type'));
							$sheet->setFontSize(8);
						});
                        
						$title = sprintf('%s', 'Analisis Inventario  Unidades');
						$excel->sheet('Excel', function($sheet) use($mes, $ano, $xmeses, $nmes0, $nmes1, $nmes2, $nmes3, $nmes4, $auxiliar, $title, $type){
							$sheet->loadView('reports.inventory.reporteanalisisinventario.reporte2', compact('mes', 'ano', 'xmeses', 'nmes0', 'nmes1', 'nmes2', 'nmes3', 'nmes4',  'auxiliar', 'title', 'type'));
							$sheet->setFontSize(8);
						});
					})->download('xls');
					break;
            }
        }
		return view('reports.inventory.reporteanalisisinventario.index');
    }
}
