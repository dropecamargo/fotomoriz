<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounting\PresupuestoGasto, App\Models\Accounting\UnidadDecision;
use DB, Excel, Log, Datatables, Validator;

class PresupuestoGastoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax() ) {
            $query = PresupuestoGasto::query();
            $query->select('presupuestog.*', 'unidaddecision_nombre');
            $query->join('unidaddecision', 'presupuestog_unidaddecision', '=', 'unidaddecision_codigo');
            $query->orderBy('presupuestog_mes', 'presupuestog_ano');

            // Persistent data filter
            if($request->has('persistent') && $request->persistent) {
                session(['search_mes' => $request->filled('search_mes') ? $request->search_mes : '']);
                session(['search_ano' => $request->filled('search_ano') ? $request->search_ano : '']);
                session(['search_unidad' => $request->filled('search_unidad') ? $request->search_unidad : '']);
            }

            return Datatables::of($query)
                ->filter(function($query) use ($request){
                    // Si existe Mes
                    if( $request->filled('search_mes') ){
                        $query->where('presupuestog_mes', $request->search_mes);
                    }

                    // Si existe Ano
                    if( $request->filled('search_ano') ){
                        $query->where('presupuestog_ano', $request->search_ano);
                    }

                    // Si existe Unidad
                    if( $request->filled('search_unidad') ){
                        $query->where('presupuestog_unidaddecision', $request->search_unidad);
                    }

                })->make(true);
        }
        return view('accounting.presupuestosg.index');
    }

    /**
     * Import Excel the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // Opciones
        if( isset($request->file) ){
            // Begin validator type file && tercero required
            if ( !in_array($request->file->guessClientExtension(), ['xls', 'xml', 'xlsx', 'csv']) ){
                return response()->json(['success' => false, 'errors' => "Por favor, seleccione un archivo excel valido, los formatos valido son: xls, xml, csv, xlsx."]);
            }

            DB::beginTransaction();
            try {
                $file = $request->file;

                // Uploaded file
                $data = [];
                Excel::filter('chunk')->selectSheetsByIndex(0)->load($file->getRealPath())->chunk(100, function($results) use ( &$data ){
                    $errors = new \stdClass();
                    $errors->success = true;

                    $headers = $results->getHeading();
                    $defaultHeaders = array_combine($headers, $headers);
                    $rules = [
                        'mes' => 'required',
                        'ano' => 'required',
                        'unidad' => 'required',
                        'nivel1' => 'required',
                        'nivel2' => 'required',
                        'valor' => 'required'
                    ];

                    $validator = Validator::make($defaultHeaders, $rules);
                    if( $validator->fails() ) {
                        $validator->errors()->add('comment', 'Estos campos mencionados no estan presentes en el encabezado del archivo');

                        $errors->success = false;
                        $errors->errors = $validator->errors();
                        $data[] = $errors;
                        return false;
                    }

                    foreach($results as $row){
                        $row->mes = intval($row->mes);
                        $row->ano = intval($row->ano);
                        $row->unidad = intval($row->unidad);
                        $row->nivel1 = intval($row->nivel1);
                        $row->nivel2 = intval($row->nivel2);

                        // Validar unidad de decision
                        $unidad = UnidadDecision::where('unidaddecision_codigo', $row->unidad)->first();
                        if( !$unidad instanceof UnidadDecision ){
                            $errors->success = false;
                            $errors->errors = "No es posible recuperar la unidad de decision, por favor verifique la información.";
                            $data[] = $errors;
                            return false;
                        }

                        // Validar presupuestog
                        $presupuestog = PresupuestoGasto::where('presupuestog_mes', $row->mes)
                            ->where('presupuestog_ano', $row->ano)
                            ->where('presupuestog_unidaddecision', $unidad->unidaddecision_codigo)
                            ->where('presupuestog_nivel1', $row->nivel1)
                            ->where('presupuestog_nivel2', $row->nivel2)
                            ->first();

                        if( $presupuestog instanceof PresupuestoGasto ){
                            $presupuestog->presupuestog_valor = $row->valor;
                            $presupuestog->save();

                        }else{
                            $presupuestog = new PresupuestoGasto;
                            $presupuestog->presupuestog_mes = $row->mes;
                            $presupuestog->presupuestog_ano = $row->ano;
                            $presupuestog->presupuestog_unidaddecision = $unidad->unidaddecision_codigo;
                            $presupuestog->presupuestog_nivel1 = $row->nivel1;
                            $presupuestog->presupuestog_nivel2 = $row->nivel2;
                            $presupuestog->presupuestog_valor = $row->valor;
                            $presupuestog->save();
                        }
                    }

                }, false);

                if( isset( $data[0] ) && !$data[0]->success ){
                    DB::rollback();
                    return response()->json($data[0]);
                }

                DB::commit();
                return response()->json(['success'=> true, 'msg'=> "Se importo con exito el archivo."]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        return response()->json(['success' => false, 'errors' => "Por favor, seleccione un archivo."]);
    }

    /**
     * Export Excel the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportar()
    {
        Excel::create(sprintf('%s_%s', "formato_presupuesto", date('Y_m_d H_m_s')), function ($excel) {
            $title = 'Presupuesto';
            $excel->sheet('Excel', function($sheet) use ($title) {
                $sheet->loadView('accounting.presupuestosg.export', compact('title'));
            });
        })->download('xls');
    }
}
