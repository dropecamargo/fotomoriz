<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;

use Validator, Session, Auth;

class InteresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->has('ano') && $request->has('mes') ){
            $data = $request->all();

            $validator = Validator::make($data, [
                'intereses1_tasa' => 'required|max:4',
                'intereses1_dias_gracia' => 'required|numeric',
                'intereses1_fecha' => 'required|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return redirect('/intereses')
                    	->withErrors($validator)
                    	->withInput();
            }

            // Llamando funcion de Symfony parameters command cartera:interes Tasa, Dias_gracia, Fecha, Año, Mes, Observacion, usuario elaboro
            $user = Auth::user()->usuario_id;
            $command = "php ".base_path()."/artisan cartera:intereses --data=$data[intereses1_tasa] --data=$data[intereses1_dias_gracia] --data=$data[intereses1_fecha] --data=$data[ano] --data=$data[mes] --data=$data[intereses1_observaciones] --data=$user >> /dev/null 2>&1";
            $process = new Process($command);
            $process->start();

            //  Enviar mensaje y redireccionar!
            Session::flash('message', 'Se esta generando la rutina! puede continuar.');
            return redirect()->route('intereses.index');

        }
        return view('receivable.interests.main');
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
