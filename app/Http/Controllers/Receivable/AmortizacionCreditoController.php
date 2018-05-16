<?php

namespace App\Http\Controllers\Receivable;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, App;

class AmortizacionCreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->filled('type')) {

            // Reference attributes
            $interes = $request->amortizacion_interes / 100;
            $valor = $request->amortizacion_valor;
            $cuota = $request->amortizacion_cuota;
            $gracia = $request->amortizacion_gracia;
            $seguro = $request->amortizacion_seguro;

            // Formula anualidad
            $anualidad = (pow((1+$interes),($cuota - $gracia )) * $interes);
            $anualidad = $anualidad / (pow((1+$interes),($cuota - $gracia )) - 1);
            $anualidad *= $valor;

            // Prepare object data
            $data = [];
            for ($i = 0; $i < $cuota ; $i++) {
                $object = new \stdClass();
                $object->cuota = $i + 1;
                $object->tasa = $interes;
                $object->seguro = $seguro;
                $object->date = date('Y-m-d', strtotime("+$i months",strtotime(date('Y-m-d'))));
                if ($gracia > $i) {
                    $object->financiacion = $valor * $interes;
                    $object->amortizacion = 0;
                    $object->saldo = $valor - $object->amortizacion;
                    $object->total = $seguro + $object->financiacion;
                }else{
                    $object->financiacion = $interes * $data[$i - 1]->saldo;
                    $object->amortizacion = $anualidad - $object->financiacion - $seguro;
                    $object->saldo = $data[$i - 1]->saldo - $object->amortizacion;
                    $object->total = $anualidad;
                }
                $data[] = $object;
            }

            // Prepare dates for report
            $title = "Amortización";
            $type = $request->type;

            // Generate file
            switch ($type) {
                case 'pdf':
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->loadHTML(View::make('receivable.amortizaciones.report', compact('data', 'title', 'type'))->render());
                    $pdf->setPaper('A4', 'portairt')->setWarnings(false);
                    return $pdf->stream(sprintf('%s_%s.pdf', 'amortizacion', date('Y_m_d')));
                break;
            }
        }
        return view('receivable.amortizaciones.index');
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
