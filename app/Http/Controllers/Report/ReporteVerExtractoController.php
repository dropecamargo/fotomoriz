<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage, Datatables;

class ReporteVerExtractoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $path = "DOC_CARTERA/EXTRACTOS/prueba/";
            $files = Storage::allFiles($path);
            // dd($files);
            $data = [];
            foreach ($files as $file) {
                // dd(basename($file), dirname($file));
                list($a, $b, $c, $d) = explode("/", dirname($file));
                list($year, $month) = explode("_", $d);
                // dd($a, $b, $c, $d);
                $object = new \stdClass();
                $object->name = basename($file);
                $object->year = $year;
                $object->month = $month;
                $object->url = url("storage/DOC_CARTERA/EXTRACTOS/prueba/$object->name");
                $data[] = $object;
            }
            // dd($data);
            return Datatables::of(collect($data))->make(true);
        }
        return view('reports.receivable.reporteverextractos.index');
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
