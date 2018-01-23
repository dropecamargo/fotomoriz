<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Intereses1, App\Models\Base\Empresa;
use Datatables, DB, Log, Storage, Mail;

class EnviarInteresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax() ){
            $query = Intereses1::query();
            $query->select('intereses1.*', 'sucursal_nombre', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
            $query->join('sucursal', 'intereses1_sucursal', '=', 'sucursal_codigo');
            $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');

            // Persistent data filter
            if($request->has('persistent') && $request->persistent) {
                session(['searchinteres_tercero' => $request->has('tercero_nit') ? $request->tercero_nit : '']);
                session(['searchinteres_tercero_nombre' => $request->has('tercero_nombre') ? $request->tercero_nombre : '']);
                session(['searchinteres_numero' => $request->has('intereses1_numero') ? $request->intereses1_numero : '']);
            }

            return Datatables::of( $query )
                ->filter(function($query) use ($request) {
                    // Mes
                    if($request->has('intereses1_mes')){
                        $query->whereRaw("EXTRACT(MONTH FROM intereses1_fecha) = '$request->intereses1_mes'");
                    }

                    // Año
                    if($request->has('intereses1_ano')){
                        $query->whereRaw("EXTRACT(YEAR FROM intereses1_fecha) = '$request->intereses1_ano'");
                    }

                    // Numero
                    if($request->has('intereses1_numero')){
                        $query->where('intereses1_numero', $request->intereses1_numero);
                    }

                    // Documento
                    if($request->has('tercero_nit')) {
                        $query->where('intereses1_tercero', $request->tercero_nit);
                    }
                })
                ->make(true);
        }
        return view('receivable.enviarintereses.index');
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
        $query = Intereses1::query();
        $query->select('intereses1.*', 'sucursal_nombre', 'tercero_nit', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"), DB::raw("intereses1_numero || '-' || intereses1_sucursal as interes_codigo"));
        $query->join('sucursal', 'intereses1_sucursal', '=', 'sucursal_codigo');
        $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');
        $query->where('intereses1.id', $id);
        $enviarinteres = $query->first();

        if(!$enviarinteres instanceof Intereses1){
            abort(404);
        }

        return view('receivable.enviarintereses.show', ['enviarinteres' => $enviarinteres]);
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

    /**
     * Cerrar the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular(Request $request, $id)
    {
        if ($request->ajax()) {
            $interes = Intereses1::findOrFail($id);
            DB::beginTransaction();
            try {
                // Intereses
                $interes->intereses1_anulado = true;
                $interes->intereses1_fecha_anulo = date('Y-m-d');
                $interes->intereses1_hora_anulo = date('H:m:s');
                $interes->save();

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Interés anulado con exito.']);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }

    /**
     * Enviar correos the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enviar(Request $request)
    {
        if ($request->ajax()) {
            // Preparar datos para recuperar y enviar pdf's
            $correos = new \stdClass();
            $correos->enviados = [];
            $correos->noenviados = [];

            $query  = Intereses1::query();
            $query->select('intereses1.id as id', 'intereses1_numero', 'intereses1_fecha_cierre', 'intereses1_anulado', 'tercero_nit', 'tercero_email', DB::raw("tercero_nombre1 || ' ' || tercero_nombre2 AS tercero_nombres"), DB::raw("tercero_apellido1 || ' ' || tercero_apellido2 AS tercero_apellidos"), 'tercero_razon_social', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"), DB::raw("EXTRACT(YEAR FROM intereses1_fecha_cierre) as ano"), DB::raw("EXTRACT(MONTH FROM intereses1_fecha_cierre) as mes"));
            $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');
            $intereses = $query->get();

            $empresa = Empresa::getEmpresa();

            foreach ($intereses as $interes) {
                if( $request->has("id_$interes->id") ){

                    // Recuperar carpeta
                    $ruta = "{$interes->ano}_{$interes->mes}/{$interes->tercero_nit}.pdf";
                    // Validar que tercero_mail contenga @ && Validar saldos > 0
                    $validarcorreo = strpos($interes->tercero_email, '@');
                    if($validarcorreo !== false){
                        $validar = strpos($interes->tercero_email, ';');
                        $enviados = new \stdClass();
                        $enviados->tercero_nombre = $interes->tercero_nombre;
                        $enviados->tercero_nit = $interes->tercero_nit;
                        if($validar === false){
                            $enviados->tercero_email = $interes->tercero_email;
                        }else{
                            $email = explode(';', $interes->tercero_email);
                            $enviados->tercero_email = $email[0];
                        }
                        $enviados->ruta_archivo = $ruta;
                        $correos->enviados[] = $enviados;
                    }else{
                        // Crear un objeto con los clientes sin correo
                        $noenviado = new \stdClass();
                        $noenviado->tercero_nit = $interes->tercero_nit;
                        $noenviado->tercero_nombres = $interes->tercero_nombres;
                        $noenviado->tercero_apellidos = $interes->tercero_apellidos;
                        $noenviado->tercero_razon_social = $interes->tercero_razon_social;
                        $correos->noenviados[] = $noenviado;
                    }

                    DB::beginTransaction();
                    try{
                        $interes->intereses1_enviado = true;
                        $interes->save();

                        DB::commit();
                        Log::info('Se actualizo el tercero con exito!.');
                    }catch(\Exception $e){
                        DB::rollback();
                        Log::error($e->getMessage());
                        return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                    }
                }
            }

            // Recorrer clientes con correos validos
            foreach ($correos->enviados as $enviados) {
                // Buscar archivos storage/app
                $file = storage_path('app')."/DOC_CARTERA/INTERESES/$enviados->ruta_archivo";
                if( Storage::has("DOC_CARTERA/INTERESES/$enviados->ruta_archivo") ){
                    
                    // Preparar datos para enviar
                   $emails = ['wnieves@fotomoriz.com', $enviados->tercero_email];
                   try{
                       $datos = ['cliente' => $enviados, 'empresa' => $empresa];
                       Mail::send('emails.intereses.enviado', $datos, function($msj) use ($file, $empresa, $emails){
                           $msj->from('wnieves@fotomoriz.com', $empresa->empresa_nombre);
                           $msj->to($emails);
                           $msj->subject('Interés');
                           $msj->attach($file);
                       });
                   }catch(\Exception $e){
                       $fail = new \stdClass();
                       $fail->tercero_nit = $enviados->tercero_nit;
                       $fail->tercero_nombres = $enviados->tercero_nombre;
                       $fail->tercero_apellidos = '';
                       $fail->tercero_razon_social = $enviados->tercero_nombre;
                       $correos->noenviados[] = $fail;
                   }
               }else{
                   $notfound = new \stdClass();
                   $notfound->tercero_nit = $enviados->tercero_nit;
                   $notfound->tercero_nombres = $enviados->tercero_nombre;
                   $notfound->tercero_apellidos = '';
                   $notfound->tercero_razon_social = $enviados->tercero_nombre;
                   $correos->noenviados[] = $notfound;
               }
           }

           if( count($correos->noenviados) > 0 ){
               // Preparar datos para un listado de no enviados
               $datos = ['empresa' => $empresa, 'correos' => $correos];
               Mail::send('emails.intereses.noenviado', $datos, function($msj) use ($empresa){
                   $msj->from('wnieves@fotomoriz.com', $empresa->empresa_nombre);
                   $msj->to('wnieves@fotomoriz.com');
                   $msj->subject('Intereses no enviados.');
               });
           }else{
               Log::error('No hay correos para enviar(NO_ENVIADOS).');
           }

           return response()->json(['success' => true, 'msg' => "Se enviaron los intereses con exito!"]);
        }
        abort(403);
    }
}
