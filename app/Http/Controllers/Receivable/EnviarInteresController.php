<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Intereses1, App\Models\Base\Empresa;
use Datatables, DB;

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
            $query->select('intereses1.*', 'sucursal_nombre', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"), DB::raw("intereses1_numero || '-' || intereses1_sucursal as interes_codigo"));
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
        $explode = explode('-', $id);
        $numero = $explode[0];
        $sucursal = $explode[1];

        $query = Intereses1::query();
        $query->select('intereses1.*', 'sucursal_nombre', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"), DB::raw("intereses1_numero || '-' || intereses1_sucursal as interes_codigo"));
        $query->join('sucursal', 'intereses1_sucursal', '=', 'sucursal_codigo');
        $query->join('tercero', 'intereses1_tercero', '=', 'tercero_nit');
        $query->where('intereses1_numero', $numero);
        $query->where('intereses1_sucursal', $sucursal);
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
        dd('edit');
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
