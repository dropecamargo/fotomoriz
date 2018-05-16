<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\TerceroInterno;
use DB, Datatables;

class TerceroInternoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TerceroInterno::query();
            $query->select('tercerointerno.*', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_razon_social', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
            $query->join('tercero', 'tercerointerno_codigo', '=', 'tercero_nit');

            // Persistent data filter
            if($request->has('persistent') && $request->persistent) {
                session(['search_tercerointerno_codigo' => $request->has('tercerointerno_codigo') ? $request->tercerointerno_codigo : '']);
                session(['search_tercerointerno_nombre' => $request->has('tercerointerno_nombre') ? $request->tercerointerno_nombre : '']);
            }

            // Joins

            return Datatables::of($query)
                ->filter(function($query) use($request) {
                    // Documento
                    if($request->filled('tercerointerno_codigo')) {
                        $query->where('tercerointerno_codigo', $request->tercerointerno_codigo);
                    }

                    // Nombre
                    if($request->filled('tercerointerno_nombre')) {
                        $query->where(function ($query) use($request) {
                            $query->whereRaw("tercero_nombre1 LIKE '%{$request->tercerointerno_nombre}%'");
                            $query->orWhereRaw("tercero_nombre2 LIKE '%{$request->tercerointerno_nombre}%'");
                            $query->orWhereRaw("tercero_apellido1 LIKE '%{$request->tercerointerno_nombre}%'");
                            $query->orWhereRaw("tercero_apellido2 LIKE '%{$request->tercerointerno_nombre}%'");
                            $query->orWhereRaw("tercero_razon_social LIKE '%{$request->tercerointerno_nombre}%'");
                            $query->orWhereRaw("(tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2) LIKE '%{$request->tercerointerno_nombre}%'");
                        });
                    }
                })
                ->make(true);
        }
        return view('admin.tercerosinterno.index');
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
    public function show(Request $request, $id)
    {
        $tercerointerno = TerceroInterno::getTerceroInterno($id);
        if ($request->ajax()) {
            return response()->json($tercerointerno);
        }
        return view('admin.tercerosinterno.show', ['tercerointerno' => $tercerointerno]);
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
