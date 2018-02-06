<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Intereses2;
use DB;

class DetalleEnviarInteresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax() ){
            $detalle = [];
            if( $request->has('interes') ){
                $query = Intereses2::query();
                $query->select('intereses2.*', 'intereses1_tasa', 'documentos_nombre', 'factura1_iva', DB::raw("intereses2_dias_mora - intereses2_dias_a_cobrar AS cobrados"));
                $query->join('documentos', 'intereses2_doc_origen', '=', 'documentos_codigo');
                $query->join('intereses1', function($join){
                    $join->on('intereses1_numero', '=', 'intereses2_numero');
                    $join->on('intereses1_sucursal', '=', 'intereses2_sucursal');
                });
                $query->join('factura1', function($join){
                    $join->on('factura1_numero', '=', 'intereses2_num_origen');
                    $join->on('factura1_sucursal', '=', 'intereses2_suc_origen');
                });
                $query->where('intereses1.id', $request->interes);
                $query->orderBy('intereses2_dias_mora', 'desc');
                $detalle = $query->get();
            }

            return response()->json($detalle);
        }
        abort(404);
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
