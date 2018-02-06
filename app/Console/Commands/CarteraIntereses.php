<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Base\Tercero, App\Models\Cartera\CierreCartera, App\Models\Cartera\Factura1, App\Models\Cartera\Pagare1, App\Models\Cartera\Factoring1, App\Models\Cartera\Chdevuelto1, App\Models\Cartera\Intereses1, App\Models\Cartera\Intereses2,  App\Models\Cartera\Documentos, App\Models\Base\Sucursal, App\Models\Base\Empresa;
use DB, View, App, Log, Storage;

class CarteraIntereses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:intereses {intereses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar intereses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $intereses = [];
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Opciones
        if( env('APP_ENV') == 'local'){
            ini_set('memory_limit', '-1');
        }

        // Preparar datos trim(argumen) para eliminar el { inicial y el } final
        $array = trim($this->argument('intereses'), '{}');
        foreach(explode(",", $array) as $item) {
            list($key, $value) = explode(":", $item);
            $this->intereses[$key] = $value;
        }

        DB::beginTransaction();
        try {
            Log::info('Generando rutina...');

            // Fechas filtro mes y ano
            $ano = $this->intereses['ano'];
            $mes = $this->intereses['mes'];
            if( intval($mes) == 12 ){
                $mesaux = 1;
                $anoaux = $ano + 1;
            }else{
                $mesaux = $mes + 1;
                $anoaux = $ano;
            }
            $fechaaux = $anoaux."-".$mesaux."-01";
            $fechacierre = date("Y-m-d", strtotime("$fechaaux -1 day"));

            // Recuperar documento INTER
            $documentos = Documentos::where('documentos_codigo', 'INTER')->first();
            if( !$documentos Instanceof Documentos ){
                throw new \Exception('No es posible recuperar el documento.');
            }

            $empresa = Empresa::getEmpresa();
            if( !$empresa Instanceof Empresa ){
                throw new \Exception('No es posible recuperar empresa.');
            }

            $terceros = Tercero::getTercerosCierrecartera($ano, $mes);
            foreach ($terceros as $tercero) {
                // Recuperar sucursal 1 -> Bogota -> sucursal_inter
                $sucursal = Sucursal::where('sucursal_codigo', '1')->first();
                if( !$sucursal Instanceof Sucursal ){
                    throw new \Exception('No es posible recuperar sucursal.');
                }

                // Recuperar o aumentar consecutive sucursal
                $numero = $sucursal->sucursal_inter;
                $numero = !is_integer(intval($numero)) ? 1 : ($numero + 1);

                // Validar que tenga documentos en mora
                $cierrecartera = CierreCartera::getIntereses( $mes, $ano, $fechacierre, $tercero->tercero_nit, intval($this->intereses['intereses1_dias_gracia']) );
                if( count($cierrecartera) <= 0 ){
                    continue;
                }

                // array detalle
                $dias = 0;
                $detalle = [];
                foreach ($cierrecartera as $cierre) {
                    // Validar existencia de tercero, doc_origen, num_origen, suc_origen, cuo_origen
                    $validar = Intereses1::validarExiste( $tercero->tercero_nit, $cierre->docu, $cierre->numero, $cierre->sucursal, $cierre->cuota );
                    if( $validar Instanceof Intereses1 ){
                        $dias = $validar->intereses2_dias_a_cobrar;
                    }

                    // Calcular dias a cobrar (diascobrados - diasmora)
                    $acobrar = abs($cierre->dias) - abs($dias);

                    if( $acobrar <= 0 ){
                        continue;
                    }

                    $cierre->acobrar = $acobrar;
                    $detalle[] = $cierre;
                }

                // Validar detalle no este vacio
                if( !empty( $detalle ) ){
                    // El objecto contiene interes(preparado para guardar) y el detalle del interes
                    Log::info($detalle);
                    // $interes = $this->agregarInteres( $documentos, $numero, $sucursal, $fechacierre, $tercero, $detalle, $empresa );

                    // // Preparar datos para pdfs
                    // $title = sprintf('%s %s %s %s', 'INTERES DE CLIENTE A', strtoupper(config('koi.meses')[$mes]), 'DEL', $ano);
                    // $type = 'pdf';
                    //
                    // switch ($type){
                    //     case 'pdf':
                    //         $pdf = App::make('dompdf.wrapper');
                    //         $pdf->loadHTML( View::make('receivable.generarintereses.report.reporte', compact('tercero', 'interes', 'empresa', 'title', 'type'))->render());
                    //         $pdf->setPaper('letter', 'portrait')->setWarnings(false);
                    //         $carpeta = "{$ano}_{$mes}";
                    //         $name = "{$tercero->tercero_nit}.pdf";
                    //         $salida = $pdf->output();
                    //
                    //         Storage::put("DOC_CARTERA/INTERESES/$carpeta/$name", $salida);
                    //         break;
                    // }
                }
            }

            // DB::commit();
            DB::rollback();
            Log::info('Se completo la rutina con exito.');
        }catch(\Exception $e){
            DB::rollback();
            Log::info('No se pudo ejecutar la rutina con exito.');
            Log::error($e->getMessage());
        }
    }

    /**
    * Funcion para agregarInteres
    **/
    public function agregarInteres( $documentos, $numero, $sucursal, $fechacierre, $tercero, $detalle, $empresa )
    {
        // Retornar objecto
        $data = [];

        // Guardar el interes
        $newinteres = new Intereses1;
        $newinteres->intereses1_tasa = $this->intereses['intereses1_tasa'];
        $newinteres->intereses1_dias_gracia = $this->intereses['intereses1_dias_gracia'];
        $newinteres->intereses1_fecha = $this->intereses['intereses1_fecha'];
        $newinteres->intereses1_observaciones = $this->intereses['intereses1_observaciones'];
        $newinteres->intereses1_sucursal = $sucursal->sucursal_codigo;
        $newinteres->intereses1_numero = $numero;
        $newinteres->intereses1_documentos = $documentos->documentos_codigo;
        $newinteres->intereses1_anulado = false;
        $newinteres->intereses1_tercero = $tercero->tercero_nit;
        $newinteres->intereses1_usuario_elaboro = $this->intereses['username'];
        $newinteres->intereses1_fecha_elaboro = date('Y-m-d');
        $newinteres->intereses1_hora_elaboro = date('H:m:s');
        $newinteres->intereses1_fecha_cierre = $fechacierre;
        $newinteres->intereses1_iva_porcentaje = $empresa->empresa_iva;
        $newinteres->save();

        $data['interes'] = $newinteres;

        // Recorrer el detalle
        $i = 1;
        $valor_factu = $base = $subtotal = 0;
        $formula = $this->intereses['intereses1_tasa'] / 100;
        foreach( $detalle as $cierre ){
            // Calcular intereses formula (valor * tasa ) / 30 -> dias * dias_a_cobrar
            $v_interes = ( ($cierre->valor * $formula) / 30) * $cierre->acobrar;

            // Traer factura1_iva de la tabla factura1 para calculo de iva ((intereses2_saldo-factura1_iva*(intereses1_tasa/100))/30)*intereses2_dias_a_cobrar
            $factura = Factura1::select('factura1_iva')->where('factura1_numero', $cierre->numero)->where('factura1_sucursal', $cierre->sucursal)->first();
            $valor_factu += ((($cierre->valor-$factura->factura1_iva)*$formula)/30)*$cierre->acobrar;
            $subtotal += $cierre->valor;
            $base += $v_interes;

            $newinteres2 = new Intereses2;
            $newinteres2->intereses2_numero = $newinteres->intereses1_numero;
            $newinteres2->intereses2_sucursal = $newinteres->intereses1_sucursal;
            $newinteres2->intereses2_item = $i++;
            $newinteres2->intereses2_doc_origen = $cierre->docu;
            $newinteres2->intereses2_num_origen = $cierre->numero;
            $newinteres2->intereses2_suc_origen = $cierre->sucursal;
            $newinteres2->intereses2_cuo_origen = $cierre->cuota;
            $newinteres2->intereses2_expedicion = $cierre->expedicion;
            $newinteres2->intereses2_vencimiento = $cierre->vencimiento;
            $newinteres2->intereses2_saldo = $cierre->valor;
            $newinteres2->intereses2_interes = $v_interes;
            $newinteres2->intereses2_dias_a_cobrar = $cierre->acobrar;
            $newinteres2->intereses2_dias_mora = $cierre->dias;
            $newinteres2->save();

            $newinteres2->documento = $cierre->documento;
            $data['detalle'][] = $newinteres2;
        }

        $v_iva = $valor_factu * ( $empresa->empresa_iva / 100 );
        $total = $v_iva + $base;

        // Recuperar interes1, motivo: la version de pgsql no reotnra el modelo
        $rinterses1 = Intereses1::where('intereses1_numero', $newinteres->intereses1_numero)->where('intereses1_sucursal', $newinteres->intereses1_sucursal)->first();
        $rinterses1->intereses1_iva_valor = $v_iva;
        $rinterses1->save();

        $foot = new \stdClass();
        $foot->valor_factu = $valor_factu;
        $foot->v_iva = $v_iva;
        $foot->subtotal = $subtotal;
        $foot->base = $base;
        $foot->total = $total;
        $data['footer'] = $foot;

        // Actualizar consecutivo
        $sucursal->sucursal_inter = $numero;
        $sucursal->save();

        return $data;
    }
}
