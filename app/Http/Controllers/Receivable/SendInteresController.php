<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Intereses1, App\Models\Base\Empresa;
use Validator, DB, Storage, Mail, Log, Session;

class SendInteresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->has('mes') && $request->has('ano') ){
            $data = $request->all();

            $validator = Validator::make($data, [
                'mes' => 'required',
                'ano' => 'required',
                'interes_inicio' => 'required|min:1|numeric',
                'interes_fin' => 'required|min:1|numeric',
            ]);

            if ($validator->fails()) {
                return redirect('/sintereses')
                    	->withErrors($validator)
                    	->withInput();
            }

            if( $request->interes_inicio > $request->interes_fin ){
                return redirect('/sintereses')
                    	->withErrors('El numero de fin no puede ser menor al inicial.')
                    	->withInput();
            }

            // Fechas filtro mes y ano
            $mes = $request->mes;
            $ano = $request->ano;

            if( intval($mes) == 12 ){
                $mesaux = 1;
                $anoaux = $ano + 1;
            }else{
                $mesaux = $mes + 1;
                $anoaux = $ano;
            }
            $fechaaux = $anoaux."-".$mesaux."-01";
            $fechacierre = date("Y-m-d", strtotime("$fechaaux -1 day"));

            $empresa = Empresa::getEmpresa();
            if( !$empresa Instanceof Empresa ){
                return redirect('/sintereses')
                    	->withErrors('No es posible recuperar empresa.')
                    	->withInput();
            }

            $query = Intereses1::query();
            $query->select('intereses1_numero', 'intereses1_fecha_cierre', 'intereses1_anulado', 'tercero_nit', 'tercero_email', DB::raw("tercero_nombre1 || ' ' || tercero_nombre2 AS tercero_nombres"), DB::raw("tercero_apellido1 || ' ' || tercero_apellido2 AS tercero_apellidos"), 'tercero_razon_social', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
            $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');
            $query->where('intereses1_fecha_cierre', $fechacierre);
            $query->where('intereses1_numero', '>=', $request->interes_inicio);
            $query->where('intereses1_numero', '<=', $request->interes_fin);
            $query->where('intereses1_anulado', false);
            $intereses = $query->get();

            if( count($intereses) <= 0 ){
                return redirect('/sintereses')
                    	->withErrors('No existen interes en el rango establecido.')
                    	->withInput();
            }

            // Recuperar carpeta
            $carpeta = sprintf('%s_%s', $ano, $mes);

            $correos = new \stdClass();
            $correos->enviados = [];
            $correos->noenviados = [];
            foreach ($intereses as $interes) {
                // Recuperar pdf storage
                $name = sprintf('%s.pdf', $interes->tercero_nit);

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
                    $enviados->ruta_archivo = $carpeta.'/'.$name;
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
            }

            // Recorrer clientes con correos validos
            foreach ($correos->enviados as $enviados) {
                // Buscar archivos storage/app
                $file = storage_path('app')."/Interes/$enviados->ruta_archivo";
                if( Storage::has("Interes/$enviados->ruta_archivo") ){

                    // Preparar datos para enviar
                    $emails = ['wnieves@fotomoriz.com', $enviados->tercero_email];
                    try{

                        $datos = ['cliente' => $enviados, 'empresa' => $empresa];
                        Mail::send('emails.intereses.enviado', $datos, function($msj) use ($file, $empresa, $emails){
                            $msj->from('wnieves@fotomoriz.com', $empresa->empresa_nombre);
                            $msj->to($emails);
                            $msj->subject('Intereses.');
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
                    Log::error('No es posible encontrar archivos pdf.');
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
                Log::error('No hay correos para enviar.');
            }

            Session::flash('message', 'Se enviaron los correos con exito.');
            Log::info('Se enviaron todos los correos con exito.');
            return redirect()->route('sintereses.index');
        }
        return view('receivable.sendinterests.main');
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
