<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cartera\Factura1, App\Models\Cartera\Factura2, App\Models\Cartera\FelFactura, App\Models\Cartera\FelProducto, App\Models\Cartera\FelImpuestos;
use Log, DB;

class CarteraFel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:fel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar fels';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // $this->line('Bienvenido a la rutina de factuas.');
        // $ano = config('koi.app.ano');
        //
        // $mesi = $this->ask('Digite el mes con el cual inicia la rutina (1 a 12)');
        // $anoi = $this->ask('Digite el año con el cual inicia la rutina ('.$ano. ' a '. date('Y').')' );
        //
        // $mesf = $this->ask('Digite el mes con el cual finaliza la rutina (1 a 12)');
        // $anof = $this->ask('Digite el año con el cual finaliza la rutina ('.$ano. ' a '. date('Y').')');
        //
        // if( !($mesi > 0 && $mesi <= 12) || !($mesf > 0 && $mesf <= 12) ) {
        //     $this->error('El filtro de mes no es valido.');
        //     return;
        // }
        //
        // if( $anoi < $ano || $anoi > date('Y') || $anof < $ano || $anof > date('Y') ) {
        //     $this->error('El rango de años permitidos es de '.$ano.' hasta '.date('Y'));
        //     return;
        // }
        //
        // $fechai = $anoi."-".$mesi."-01";
        // $fechaf = $anof."-".$mesf."-01";
        //
        // if( $fechai > $fechaf ) {
        //     $this->error('La fecha final no puede ser mayor a la inicial');
        //     return;
        // }
        //
        // if( $this->confirm("La fecha inicial que ha digitado es $fechai y fecha fin $fechaf?", true) ){
            DB::beginTransaction();
            try{
                $query = Factura1::query();
                $query->select('factura1.*', 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'municipio_nombre', 'departamento_nombre', 'tercero_direccion', 'tercero_telefono');
                $query->join('tercero', 'factura1_tercero', '=', 'tercero_nit');
                $query->join('municipios', 'tercero_municipios', '=', 'municipio_codigo');
                $query->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo');
                $query->where('factura1_anulada', false);
                // $query->whereRaw("factura1_fecha_elaboro > '$fechai'");
                // $query->whereRaw("factura1_fecha_elaboro < '$fechaf'");
                // $query->limit(5);
                $query->where('factura1_numero', '10109574');
                $query->where('factura1_sucursal', '18');
                $facturas = $query->get();

                foreach ( $facturas as $factura ) {
                    $this->insertFelFactura( $factura );

                    // $query = Factura2::query();
                    // $query->select('factura2.*', 'producto_nombre', 'producto_referencia');
                    // $query->join('producto', 'factura2_producto', '=', 'producto_serie');
                    // $query->where('factura2_numero', $factura->factura1_numero);
                    // $query->where('factura2_sucursal', $factura->factura1_sucursal);
                    // $productos = $query->get();
                    //
                    // // $valorretenido = $baseimponible = $items = $iva = 0;
                    // foreach( $productos as $producto ){
                    //     $preciototal = ($producto->factura2_precio_venta + $producto->factura2_iva_pesos);
                    //
                    //     $felproducto = new FelProducto;
                    //     $felproducto->numero = $producto->factura2_numero;
                    //     $felproducto->sucursal = $producto->factura2_sucursal;
                    //     $felproducto->item = $producto->factura2_item;
                    //     $felproducto->codigoproducto = $producto->factura2_producto;
                    //     $felproducto->descripcion = $producto->producto_nombre;
                    //     $felproducto->referencia = $producto->producto_referencia;
                    //     $felproducto->cantidad = $producto->factura2_unidades_vendidas;
                    //     $felproducto->unidadmedida = 'UN';
                    //     $felproducto->valorunitario = $producto->factura2_precio_venta;
                    //     $felproducto->descuento = $producto->factura2_descuento_pesos;
                    //     $felproducto->preciosinimpuestos = $producto->factura2_precio_venta;
                    //     $felproducto->preciototal = $preciototal;
                    //     $felproducto->save();
                    //
                    //     // $this->error( $felproducto );
                    //     $this->error( 'OK' );
                    //
                    //     // $iva = $producto->factura2_iva_porcentaje;
                    //     //
                    //     // $valorretenido += $producto->factura2_iva_pesos;
                    //     // $baseimponible += $producto->factura2_precio_venta;
                    //     // $items++;
                    // }
                    //
                    // $query = FelImpuestos::query();
                    // $query->where('numero', $factura->factura1_numero);
                    // $query->where('sucursal', $factura->factura1_sucursal);
                    // $felimpuesto = $query->get();
                    //
                    // // $felimpuesto = new FelImpuestos;
                    // // $felimpuesto->numero = $factura->factura1_numero;
                    // // $felimpuesto->sucursal = $factura->factura1_sucursal;
                    // // $felimpuesto->item = $items;
                    // // $felimpuesto->codigoproducto = '';
                    // // $felimpuesto->codigoimpuesto = '01';
                    // // $felimpuesto->porcentajeimpuesto = $producto->factura2_iva_porcentaje;
                    // // $felimpuesto->valorretenido = $valorretenido;
                    // // $felimpuesto->baseimponible = $baseimponible;
                    //
                    // $this->question( $felimpuesto) ;
                }

                DB::rollback();
                $this->info('!OK ');

            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                $this->error('No se pudo ejecutar la rutina con exito.');
            }
        // }
    }

    public function insertFelFactura( $factura ){
        $fecha = "$factura->factura1_fecha_elaboro $factura->factura1_hora_elaboro";
        $baseimporte = $factura->factura1_bruto - $factura->factura1_descuento;
        $totalfactura = $baseimporte + $factura->factura1_iva;

        $felfactura = new FelFactura;
        $felfactura->numero = $factura->factura1_numero;
        $felfactura->sucursal = $factura->factura1_sucursal;
        $felfactura->tokenempresa = 'b47c0281057e51e5868494fe16a06acdf9b74335';
        $felfactura->idtablaorigen = $factura->factura1_numero;
        $felfactura->tipodocumento = '01';
        $felfactura->prefijo = $factura->factura1_prefijo;
        $felfactura->consecutivo = $factura->factura1_numero;
        $felfactura->fechafacturacion = $fecha;
        $felfactura->ordencompra = '';
        $felfactura->moneda = 'COP';
        $felfactura->totalimportebruto = $factura->factura1_bruto;
        $felfactura->totalbaseimponible = $baseimporte;
        $felfactura->totalfactura = $totalfactura;
        $felfactura->mediopago = '10';
        $felfactura->descripcion = $factura->factura1_observaciones;
        $felfactura->incoterm = '';
        $felfactura->consecutivofacturamodificada = 0;
        $felfactura->cufefacturamodificada = '';
        $felfactura->fechafacturamodificada = $fecha;
        $felfactura->tipopersona = $factura->tercero_persona == 'J' ? '1' : '2';
        $felfactura->razonsocial = $factura->tercero_razon_social;
        $felfactura->primernombre = $factura->tercero_nombre1;
        $felfactura->segundonombre = $factura->tercero_nombre2;
        $felfactura->primerapellido = $factura->tercero_apellido1;
        $felfactura->segundoapellido = $factura->tercero_apellido2;

        if( $factura->tercero_tipodocumento == 'CC'){
            $felfactura->tipoidentificacion =  13;
        }else if( $factura->tercero_tipodocumento == 'NI'){
            $felfactura->tipoidentificacion =  31;
        }

        $felfactura->numeroidentificacion = $factura->tercero_nit;
        $felfactura->regimen = $factura->tercero_regimen == '1' ? 0 : 2;
        $felfactura->email = $factura->tercero_email;
        $felfactura->pais = 'CO';
        $felfactura->departamento = $factura->municipio_nombre;
        $felfactura->ciudad = $factura->departamento_nombre;
        $felfactura->bariolocalidad = '';
        $felfactura->direccion = $factura->tercero_direccion;
        $felfactura->telefono = $factura->tercero_telefono;
        $felfactura->aplicafel = 'NO';
        $felfactura->cufe = '';
        $felfactura->estadoactual = '';
        $felfactura->fecharespuesta = $fecha;
        $felfactura->save();

        // $this->info( $felfactura );
        $this->info( 'OK' );
    }

    // public function insertFelImpuestos( $felimpuesto ){
        // $felimpuesto = new FelImpuestos;
        // $felimpuesto->numero = '';
        // $felimpuesto->sucursal = '';
        // $felimpuesto->item = '';
        // $felimpuesto->codigoproducto = '';
        // $felimpuesto->codigoimpuesto = '01';
        // $felimpuesto->porcentajeimpuesto = $producto->factura2_iva_porcentaje;
        // $felimpuesto->valorretenido = $valorretenido;
        // $felimpuesto->baseimponible = $baseimponible;
    // }
}
