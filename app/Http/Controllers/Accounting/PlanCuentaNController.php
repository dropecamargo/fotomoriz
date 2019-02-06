<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounting\PlanCuentasN;
use DB, Log, Datatables;

class PlanCuentaNController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax() ) {
            return Datatables::of( PlanCuentasN::query() )->make(true);
        }
        return view('accounting.plancuentasn.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $plancuentasn = PlanCuentasN::findOrFail($id);
        if ($request->ajax()) {
            return response()->json($plancuentasn);
        }
        return view('accounting.plancuentasn.show', ['plancuentasn' => $plancuentasn]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plancuentasn = PlanCuentasN::findOrFail($id);
        return view('accounting.plancuentasn.edit', ['plancuentasn' => $plancuentasn]);
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
            $data = $request->all();
            $plancuentasn = PlanCuentasN::findOrFail($id);
            if ($plancuentasn->isValid($data)) {
                DB::beginTransaction();
                try {
                    // PlanCuentasN
                    $plancuentasn->fill($data);
                    $plancuentasn->save();

                    // Commit Transaction
                    DB::commit();
                    return response()->json(['success' => true]);
                }catch(\Exception $e){
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $plancuentasn->errors]);
        }
        abort(403);
    }
}
