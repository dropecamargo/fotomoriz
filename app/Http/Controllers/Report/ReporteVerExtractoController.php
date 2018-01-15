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
            $path = "DOC_CARTERA/EXTRACTOS";
            $directories = Storage::allDirectories($path);
            $data = [];
            foreach ($directories as $directory) {
                $object = new \stdClass();
                $object->name = basename($directory);
                $data[] = $object;
            }
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
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $path = "DOC_CARTERA/EXTRACTOS/$id";
            $files = Storage::files($path);
            $data = [];
            foreach ($files as $file) {
                $object = new \stdClass();
                $object->name = basename($file);
                $object->url = url("storage/$path/$object->name");
                $data[] = $object;
            }
            return Datatables::of(collect($data))->make(true);
        }
        return view('reports.receivable.reporteverextractos.show', ['id' => $id]);
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
