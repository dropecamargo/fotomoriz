<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Receivable\FelFactura;
use DB, Validator, App, View;

class ReporteFelFacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->filled('type') ){
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            // Validate fields
            $validator = Validator::make($request->all(), [
                'filtertype' => 'required',
                'filterinitialdate' => 'required',
                'filterenddate' => 'required|after_or_equal:filterinitialdate'
            ]);

            if ($validator->fails()) {
                return redirect('/reportefacturaselectronicas')
                        ->withErrors($validator)
                        ->withInput();
            }

            $filtertype = ($request->filtertype == '0') ? '' : $request->filtertype;

            // Prepara consulta para los datos
            $query = FelFactura::query();
            $query->select('fechafacturación AS fecha', 'prefijo', 'consecutivo', 'totalbaseimponible AS base', DB::raw("(totalfactura-totalbaseimponible) AS impuesto"), 'totalfactura AS total', DB::raw("CASE WHEN tipodepersona = '2' THEN primernombre || ' ' || segundonombre || ' ' || primerapellido || ' ' || segundoapellido ELSE razonsocial END AS cliente"));
            $query->whereBetween('fechafacturación', [$request->filterinitialdate, $request->filterenddate]);
            ($filtertype != '_') ? $query->where('motivonota', $filtertype) : '';
            $query->orderBy('fecha', 'asc');
            $facturas = $query->get();

            // Preparar datos reporte
            $title = "Reporte facturas electronicas";
            $type = $request->type;
            $fechai = $request->filterinitialdate;
            $fechaf = $request->filterenddate;

            switch ($type) {
                case 'pdf':
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->loadHTML(View::make('reports.receivable.reportefacturaselectronicas.reporte', compact('facturas', 'title', 'type', 'fechai', 'fechaf'))->render());
                    $pdf->setPaper('A4', 'portair')->setWarnings(false);
                    return $pdf->stream(sprintf('%s.pdf', 'facturaselectronicas', date('Y_m_d')));
                break;
            }
        }
        return view('reports.receivable.reportefacturaselectronicas.index');
    }
}
