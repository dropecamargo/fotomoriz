<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Base\Tercero, App\Models\Base\Empresa, App\Models\Cartera\CierreCartera, App\Models\Cartera\Factura1, App\Models\Cartera\Devolucion1, App\Models\Cartera\Nota1, App\Models\Cartera\Recibo1, App\Models\Cartera\Anticipo1, App\Models\Cartera\Chdevuelto1, App\Models\Cartera\Factoring1, App\Models\Cartera\Pagare1;
use DB, View, App, Log, Storage, Mail;

class CarteraExtractos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:extractos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for generate emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if( env('APP_ENV') == 'local'){
            ini_set('memory_limit', '-1');
        }

        $this->line('Bienvenido a la rutina de extractos.');
        $pregunta = $this->ask('Digite el mes con el cual generar extractos (1 a 12)');
        $mes = intval($pregunta);

        if( !($mes > 0 && $mes <= 12) ) {
            $this->info('El mes no es valido.');
            Log::error('El mes no es valido.');
            return;
        }

        try{
            // Fechas inicio y fin filtros 1° parte
            $anocierre = date('Y');
            $mescierre = $mes;
            if(intval($mescierre)==12){
                $mesaux=1;
                $anoaux=$anocierre+1;
            }else{
                $mesaux=$mescierre+1;
                $anoaux=$anocierre;
            }
            $fechai=$anocierre."-".$mescierre."-01";
            $fechaf=$anoaux."-".$mesaux."-01";

            // Terceros
            $terceros = Tercero::getTercerosCierrecartera($anocierre, $mescierre);

            // Empresa
            $empresa = Empresa::getEmpresa();

            // Correos
            $correos = new \stdClass();
            $correos->enviados = [];
            $correos->noenviados = [];

            // Fechas
            $fechas = new \stdClass();
            $fechas->ano_actual = date('Y');
            $fechas->nombre_mes_escogido = strtoupper(config('koi.meses')[$mescierre]);
            $fechas->nombre_mes_siguiente = ($mescierre >= 12 ? strtoupper(config('koi.meses')[1]) : strtoupper(config('koi.meses')[$mescierre+1]));
            $fechas->ano_siguiente = ($mescierre >= 12 ? date('Y')+1 : date('Y'));

            $existente = ['ventas@jvpublicidadymark.com', 'magicolorflorencia@yahoo.es', 'etrianaq@hotmail.com', 'info@artymana.com.co', 'promographics1@gmail.com', 'zona.publicitaria@hotmail.com', 'nicolas.pena@on2desing.com', 'claudiavega_gvp@yahoo.es', 'aldemar@hotmail.com', 'andres.llano@imageprinting.com.co', 'iincolors@gmail.com', 'ltdiseno@gmail.com', 'mgr@proempaques.com', 'tesoreria@promadeco.com.co', 'gerencia@signlinepublicidad.com', 'delosrios@visual-box.com', 'compras@advisioncv.com', 'amgoimp@gmail.com', 'viewdigitalltda@gmail.com', 'administrativo@imprestudiografico.com', 'abarteimpresioneseu@gmail.com', 'empresa@editorageminis.com', 'mercadeo@xkanner.com', 'alvarocontreras56@yahoo.com', 'gerencia@digiprintweb.net', 'pladicon2012acacias@gmail.com', 'compugonzalez@gmail.com', 'gladys77osorio@yahoo.com', 'contabilidad@arde.com.co', 'dir.general@gyamarketingeimpresion.com', 'ddsimpresion@gmail.com', 'sistemas@fotomoriz.com', 'carropartes@gmail.com', 'p.rodriguez@matepublicidad.com', 'j.carrero@grafiq.com.co', 'princon@desarrollo-visual.com', 'jaime-g@jagodigital.com.co', 'compras@dislumbra.com', 'l.rodriguez@artepop.co', 'gerencia@creativapublicidad.com.co', 'gerprod@alangraph.com', 'deeperdesign.info@gmail.com', 'agho_publicidad@hotmail.com', 'imagendigitalprint@hotmail.com', 'jecatruju@gmail.com', 'paolagalvis@creativegroup.com.co', 'katherine.gomez@aviancataca.com', 'ideas@printpointsas.com', 'diegoduke1@hotmail.com', 'humberto@servigrafic.com', 'ideasap@hotmail.es', 'daniel.camacho.vargas@gmail.com', 'creaydisena@gmail.com', 'esmeraldameneses2009@hotmail.com', 'jhonhen888@hotmail.com', 'imagen.digital12@hotmail.com', 'tallerdigitalsas@gmail.com', 'bpfajardo.artgraphics14@gmail.com', 'carmengallego@rgbpreprensa.net', 'administrativo@imagecolors.com', 'gerencia@imprecomercial.com', 'fotoadmin@etb.net.co', 'jcontreras@swantex.com', 'gyjpublicidaddigital@hotmail.com', 'administracion@comunicacionwow.com', 'ventas@filmtex.com', 'david20perez@gmail.com', 'sergio@duplad.com', 'businessglobal35@yahoo.es', 'magifoto-digital@hotmail.com', 'gerencia@impresosmariel.com', 'publicidad2@marcel-france.com', 'jcasas@apglass.com', 'visuality.exterior@gmail.com', 'avilumpub@yahoo.es', 'harveycruz0522@hotmail.co', 'dusdigital@gmail.com', 'fernandomedina@vallasamerica.com', 'avilapublicidad@mac.com', 'gerencia@opcionesgraficas.com', 'dgsbucaramanga@gmail.com', 'senalgrafica@hotmail.com', 'compras@midta.com', 'lilit1967@yahoo.com', 'perfectprint2009@hotmail.com', 'c_florez@solutions.com.co', 'almacen@fgsa.co', 'devilsltda@gmail.com', 'publicidadproo8@gmail.com', 'publicidaddigitalbarrancabermeja@hotmail.com', 'seyspro@gmail.com', 'gillovilla@hotmail.com', 'publikfactory@hotmail.com', 'marioaraujo1180@hotmail.com', 'masdigitalimpresion@gmail.com', 'conceptgraphic.sas@hotmail.com', 'ventasrapiprint@yahoo.es', 'mabelaparicio@crearimpresion.com', 'yaneth@logograma.com.co', 'jormaji@hotmail.com', 'publicidadruiz@hotmail.com', 'impresos@epm.net.co'];

            // Recorrer clientes
            foreach ($terceros as $tercero) {
                // Iniciando datos 2° parte
                $datos = new \stdClass();
                $datos->empresa = $empresa;

                // Resumen cartera
                $resumencartera = CierreCartera::getResumenCarterta($mescierre, $anocierre, $tercero);
                if( $resumencartera['t_1+2'] <= 0 ){
                    continue;
                }

                // Compras del mes
                $compras = Factura1::getFacturas($fechai, $fechaf, $tercero);

                // Devoluciones del mes
                $devoluciones = Devolucion1::getDevoluciones($fechai, $fechaf, $tercero);

                // Notas del mes
                $notas = Nota1::getNotas($fechai, $fechaf, $tercero);

                // Pagos del mes
                $pagos = Recibo1::getPagos($fechai, $fechaf, $tercero);

                // Anticipos del mes
                $anticipos = Anticipo1::getAnticipos($fechai, $fechaf, $tercero);

                // Cheques devueltos del mes
                $cheques = Chdevuelto1::getCheques($fechai, $fechaf, $tercero);

                // Factoring1 && Factoring3
                $factoring = Factoring1::getFactorings($fechai, $fechaf, $tercero);

                // Pagare1 && Pagare3
                $pagares = Pagare1::getPagares($fechai, $fechaf, $tercero);

                // Consignaciones
                $sqlconsig="
                    SELECT
                    SUM(CASE WHEN (r.rconsignacion1_fecha >= $fechai AND r.rconsignacion1_fecha <= $fechaf) THEN (p.producto_precio_venta_pesos*(r2.rconsignacion2_cantidad - r2.rconsignacion2_unidades_dev)) ELSE 0 END) AS consignacionmes,
                    SUM(p.producto_precio_venta_pesos) AS consignacion
                    FROM
                    rconsignacion1 AS r, rconsignacion2 AS r2, producto AS p
                    WHERE
                    r.rconsignacion1_numero = r2.rconsignacion2_numero AND
                    r.rconsignacion1_sucursal_fuente = r2.rconsignacion2_sucursal AND
                    r2.rconsignacion2_producto = p.producto_serie AND
                    r2.rconsignacion2_cantidad != r2.rconsignacion2_unidades_dev AND
                    r.rconsignacion1_tipo = 'E' AND
                    r.rconsignacion1_tercero = $tercero->tercero_nit";
                $consignaciones = DB::select($sqlconsig)[0];

                // Preparar titulo & tipo para los archivos
                $title = sprintf('%s %s %s %s', 'EXTRACTO DE CLIENTE A', $fechas->nombre_mes_escogido, 'DEL', $fechas->ano_actual);
                $type = 'pdf';

                // Datos 2° parte
                $datos->resumencartera = $resumencartera;
                $datos->compras = $compras;
                $datos->devoluciones = $devoluciones;
                $datos->notas = $notas;
                $datos->pagos = $pagos;
                $datos->anticipos = $anticipos;
                $datos->cheques = $cheques;
                $datos->factoring = $factoring;
                $datos->pagares = $pagares;
                $datos->consignaciones = $consignaciones;

                // Generate file
                switch ($type){
                    case 'pdf':
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->loadHTML(View::make('receivable.extractos.reports.reporte',  compact('tercero', 'datos', 'fechas', 'title', 'type')));
                    $carpeta = sprintf('%s_%s', $fechas->ano_actual, $mescierre);
                    $name = sprintf('%s_%s_%s.pdf', $empresa->empresa_nombre,'extractocliente',$tercero->tercero_nit);
                    $salida = $pdf->output();

                    Storage::put("DOC_CARTERA/EXTRACTOS/prueba/$carpeta/$name", $salida);

                    // Validar que tercero_mail contenga @ && Validar saldos > 0
                    $validarcorreo = strpos($tercero->tercero_email, '@');
                    if($validarcorreo !== false){
                        $validar = strpos($tercero->tercero_email, ';');
                        $enviados = new \stdClass();
                        $enviados->tercero_nombre = $tercero->tercero_nombre;
                        $enviados->tercero_nit = $tercero->tercero_nit;
                        if($validar === false){
                            $enviados->tercero_email = $tercero->tercero_email;
                        }else{
                            $email = explode(';', $tercero->tercero_email);
                            $enviados->tercero_email = $email[0];
                        }
                        $enviados->ruta_archivo = $carpeta.'/'.$name;
                        $correos->enviados[] = $enviados;
                    }else{
                        // Crear un objeto con los clientes sin correo
                        $noenviado = new \stdClass();
                        $noenviado->tercero_nit = $tercero->tercero_nit;
                        $noenviado->tercero_nombres = $tercero->tercero_nombres;
                        $noenviado->tercero_apellidos = $tercero->tercero_apellidos;
                        $noenviado->tercero_razon_social = $tercero->tercero_razon_social;
                        $correos->noenviados[] = $noenviado;
                    }
                    break;
                }
            }

            // Recorrer clientes con correos validos
            foreach ($correos->enviados as $enviados) {

                // Buscar archivos storage/app
                $file = storage_path('app')."/DOC_CARTERA/EXTRACTOS/prueba/$enviados->ruta_archivo";
                if( Storage::has("DOC_CARTERA/EXTRACTOS/prueba/$enviados->ruta_archivo") ){

                    if( in_array( trim($enviados->tercero_email), $existente) ){
                        continue;
                    }

                    // Preparar datos para enviar
                    $emails = ['wnieves@fotomoriz.com', $enviados->tercero_email];

                    try{

                        $datos = ['cliente' => $enviados, 'empresa' => $empresa];
                        Mail::send('emails.extractos.enviado', $datos, function($msj) use ($file, $empresa, $emails){
                            $msj->to($emails);
                            $msj->subject('Estado de cuentas.');
                            $msj->attach($file);
                        });

                    }catch(\Exception $e){
                        $fail = new \stdClass();
                        $fail->tercero_nit = $enviados->tercero_nit;
                        $fail->tercero_nombres = $enviados->tercero_nombre;
                        $fail->tercero_apellidos = '';
                        $fail->tercero_razon_social = $enviados->tercero_nombre;
                        $correos->noenviados[] = $fail;
                    }

                }else{
                    Log::error('No es posible encontrar archivos pdf.');
                }
            }

            if( count($correos->noenviados) > 0 ){

                // Preparar datos para un listado de no enviados
                $datos = ['empresa' => $empresa, 'correos' => $correos];
                Mail::send('emails.extractos.noenviado', $datos, function($msj) use ($empresa){
                    $msj->to('wnieves@fotomoriz.com');
                    $msj->subject('Estados de cuenta no enviados.');
                });

            }else{
                Log::error('No hay correos para enviar.');
            }

            $this->info('Se genero la rutina con exito.');
            Log::info('Se genero la rutina con exito.');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            $this->info('Se ha producido algun error en la rutina.');
        }
    }
}