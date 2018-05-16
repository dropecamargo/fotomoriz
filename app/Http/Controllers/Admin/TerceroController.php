<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\Tercero;
use DB, Datatables;

class TerceroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Tercero::query();
            $query->select('tercero_nit', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_direccion', 'tercero_municipios', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
            $query->whereRaw("tercero_tipodocumento <> 'XX'");

            // Persistent data filter
            if($request->has('persistent') && $request->persistent) {
                session(['search_tercero_nit' => $request->has('tercero_nit') ? $request->tercero_nit : '']);
                session(['search_tercero_nombre' => $request->has('tercero_nombre') ? $request->tercero_nombre : '']);
            }

            return Datatables::of($query)
                ->filter(function($query) use($request) {
                    // Documento
                    if($request->filled('tercero_nit')) {
                        $query->where('tercero_nit', $request->tercero_nit);
                    }

                    // Nombre
                    if($request->filled('tercero_nombre')) {
                        $query->where(function ($query) use($request) {
                            $query->whereRaw("tercero_nombre1 LIKE '%{$request->tercero_nombre}%'");
                            $query->orWhereRaw("tercero_nombre2 LIKE '%{$request->tercero_nombre}%'");
                            $query->orWhereRaw("tercero_apellido1 LIKE '%{$request->tercero_nombre}%'");
                            $query->orWhereRaw("tercero_apellido2 LIKE '%{$request->tercero_nombre}%'");
                            $query->orWhereRaw("tercero_razon_social LIKE '%{$request->tercero_nombre}%'");
                            $query->orWhereRaw("(tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2) LIKE '%{$request->tercero_nombre}%'");
                        });
                    }
                })
                ->make(true);
        }
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

    /**
     * Search tercero.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if($request->has('tercero_nit')) {
            $query = Tercero::query();
            $query->select('tercero_nit', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
            $query->where('tercero_nit', $request->tercero_nit);
            $query->whereRaw("tercero_tipodocumento <> 'XX'");
            $tercero = $query->first();

            if($tercero instanceof Tercero) {
                return response()->json(['success' => true, 'tercero_nit' => $tercero->tercero_nit, 'tercero_nombre' => $tercero->tercero_nombre]);
            }
        }
        return response()->json(['success' => false]);
    }
}
