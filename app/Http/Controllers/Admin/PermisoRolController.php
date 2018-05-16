<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Base\Modulo, App\Models\Base\Rol, App\Models\Base\PermisoRol, App\Models\Base\Permiso;
use DB, Log;

class PermisoRolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $query = Modulo::query();
            $query->select('modulo1.id', 'display_name', 'nivel1', 'nivel2', 'nivel3', 'nivel4');
            $query->where('nivel1', '=', $request->nivel1);
            $query->where('nivel2', '=', $request->nivel2);
            $query->where('nivel3', '!=', '0');
            $query->where('nivel4', '=', '0');
            $query->orderBy('nivel3', 'asc');
            $modules = $query->get();

            $data = [];
            foreach ($modules as $module)
            {
                $object = new \stdClass();
                $object->id = $module->id;
                $object->display_name = $module->display_name;
                $object->nivel1 = $module->nivel1;
                $object->nivel2 = $module->nivel2;
                $object->nivel3 = $module->nivel3;
                $object->nivel4 = $module->nivel4;

                $query = PermisoRol::query();
                $query->where('role_id', $request->role_id);
                $query->where('module_id', $module->id);
                $query->orderBy('permission_id', 'asc');
                $object->mpermissions = $query->pluck('permission_id');

                $data[] = $object;
            }
            return response()->json($data);
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
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $module = Modulo::find($id);
                if(!$module instanceof Modulo) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }

                $role = Rol::find($request->role_id);
                if(!$role instanceof Rol) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }

                $permissions = Permiso::get();
                foreach ($permissions as $permission) {
                    $query = PermisoRol::query();
                    $query->where('role_id', $role->id);
                    $query->where('module_id', $module->id);
                    $query->where('permission_id', $permission->id);
                    $permissionrole = $query->first();

                    if($request->has("permiso_$permission->id")) {
                        if(!$permissionrole instanceof PermisoRol){
                            $permissionrole = new PermisoRol;
                            $permissionrole->role_id = $role->id;
                            $permissionrole->module_id = $module->id;
                            $permissionrole->permission_id = $permission->id;
                            $permissionrole->save();
                        }
                    }else{
                        if($permissionrole instanceof PermisoRol){
                            PermisoRol::where('role_id', $role->id)->where('module_id', $module->id)->where('permission_id', $permission->id)->delete();
                        }
                    }
                }

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true]);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
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
