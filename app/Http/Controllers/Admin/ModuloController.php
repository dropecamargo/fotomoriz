<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base\Modulo;
use Datatables, DB;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Modulo::query();
            $query->whereNotNull('name');
            return Datatables::of($query)->make(true);
        }
        return view('admin.modulos.index');
    }
}
