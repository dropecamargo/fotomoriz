<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\UsuarioRol, App\Models\Base\TerceroInterno, App\Models\Base\Rol, App\Models\Base\Usuario;
use DB, Log,Cache;

class UsuarioRolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax() )
        {
            $data = [];
            if($request->has('tercerointerno_id')) {
                $tercerointerno = TerceroInterno::where('tercerointerno_codigo', $request->tercerointerno_id)->first();
                $usuario = Usuario::where('usuario_id', $tercerointerno->tercerointerno_usuario)->first();

                $query = UsuarioRol::query();
                $query->select('usuario_rol.*','rol.display_name', 'rol.name', 'rol.id as id');
                $query->join('rol', 'usuario_rol.role_id', '=', 'rol.id');
                $query->where('user_id', $usuario->id);
                $query->orderBy('user_id', 'asc');
                $data = $query->get();
            }
            return response()->json( $data );
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
        if ($request->ajax()) {
            $data = $request->all();
            DB::beginTransaction();
            try {
                // Validar rol
                $rol = Rol::find($request->role_id);
                if(!$rol instanceof Rol) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar rol, por favor verifique la información o consulte al administrador.']);
                }

                // Validar tercerointerno
                $tercerointerno = TerceroInterno::where('tercerointerno_codigo', $request->user_id)->first();
                if(!$tercerointerno instanceof TerceroInterno) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar tercero interno, por favor verifique la información o consulte al administrador.']);
                }

                // Validar que contenga usuario valido
                $usuario = Usuario::where('usuario_id', $tercerointerno->tercerointerno_usuario)->first();
                if(!$usuario instanceof Usuario) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el usuario del tercero, por favor verifique la información o consulte al administrador.']);
                }

                // Validar unique
                $usuariounique = UsuarioRol::where('user_id', $usuario->id)->where('role_id', $rol->id)->first();
                if($usuariounique instanceof UsuarioRol) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => "El rol {$rol->display_name} ya se encuentra asociada a este tercero."]);
                }

                $usuario->attachRole($rol);

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'id' => $rol->id, 'display_name' =>$rol->display_name, 'name' => $rol->name]);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
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
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validar rol
                $rol = Rol::find($id);
                if(!$rol instanceof Rol) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar rol, por favor verifique la información o consulte al administrador.']);
                }

                // Validar tercerointerno
                $tercerointerno = TerceroInterno::where('tercerointerno_codigo', $request->user_id)->first();
                if(!$tercerointerno instanceof TerceroInterno) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar tercero interno, por favor verifique la información o consulte al administrador.']);
                }

                // Validar que contenga usuario valido
                $usuario = Usuario::where('usuario_id', $tercerointerno->tercerointerno_usuario)->first();
                if(!$usuario instanceof Usuario) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el usuario del tercero, por favor verifique la información o consulte al administrador.']);
                }

                // Eliminar item rol
                $usuario->detachRole($rol);

                DB::commit();
                return response()->json(['success' => true]);
            }catch(\Exception $e){
                DB::rollback();
                Log::error(sprintf('%s -> %s: %s', 'UsuarioRolController', 'destroy', $e->getMessage()));
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }
}
