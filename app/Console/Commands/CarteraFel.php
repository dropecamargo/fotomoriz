<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cartera\Factura1, App\Models\Cartera\Factura2, App\Models\Cartera\FelFactura, App\Models\Cartera\FelProducto, App\Models\Cartera\FelImpuestos, App\Models\Cartera\Devolucion2;
use Log, DB, Validator;

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
        $this->anoincial = config('koi.app.ano');
        $this->anoactual = date('Y');
        parent::__construct();
    }

    public function handle()
    {
        $this->line('Bienvenido a la rutina de facturas electronicas.');

        // Preguntas de la rutina && validar
        $mesi = intval($this->ask("Digite el mes con el cual inicia la rutina (1 a 12)"));
        if( !($mesi > 0 && $mesi <= 12) ) {
            $this->error('El filtro de mes no es valido.');
            return;
        }
        $anoi = intval($this->ask("Digite el año con el cual inicia la rutina ($this->anoincial a $this->anoactual)"));
        if( $anoi < $this->anoincial || $anoi > $this->anoactual ) {
            $this->error("El rango de años permitidos es de $this->anoincial hasta $this->anoactual");
            return;
        }
        $mesf = intval($this->ask("Digite el mes con el cual finaliza la rutina (1 a 12)") );
        if( !($mesf > 0 && $mesf <= 12) ) {
            $this->error('El filtro de mes no es valido.');
            return;
        }
        $anof = intval($this->ask("Digite el año con el cual finaliza la rutina ($this->anoincial a $this->anoactual)"));
        if( $anof < $this->anoincial || $anof > $this->anoactual ) {
            $this->error("El rango de años permitidos es de $this->anoincial hasta $this->anoactual");
            return;
        }

        // Validar que fecha inicial no sea mayor a la final
        if( ($mesi > $mesf && $anoi == $anof) || $anoi > $anof ) {
            $this->error('La fecha final no puede ser mayor a la inicial');
            return;
        }

        // fechainicial && fechafinal para el between
        $dayf = date('t',strtotime("$anof-$mesf-01"));

        $fechai = "$anoi-$mesi-01";
        $fechaf = "$anof-$mesf-$dayf";

        // Iniciar rutina
        if( $this->confirm("El mes y año que ha digitado es ".config('koi.meses')[$mesi]." $anoi y ".config('koi.meses')[$mesf]." $anof ?", true) ){
            DB::beginTransaction();
            try{
                $this->info('Generando rutina de factura electronica.');

                // Consulta para traer facturas en el rango de fechas
                $facturas = Factura1::getFacturasElectronicas($fechai, $fechaf);
                $bar = $this->output->createProgressBar(count($facturas));
                foreach ($facturas as $factura) {
                    // Validar que no exita en fel_factura
                    $validfel = FelFactura::where('tipoDocumento', '01')->where('prefijo', $factura->factura1_prefijo)->where('consecutivo', $factura->factura1_numero)->first();
                    if( !$validfel instanceof FelFactura ){
                        // Insertar felfactura -> fel_encabezadofactura
                        $this->insertFelFactura($factura, $factura->tipo);
                    }
                    $bar->advance();
                }
                $bar->finish();

                DB::commit();
                $this->info("\nSe completo la rutina de factura electronica con exito.");
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                $this->error("No se pudo ejecutar la rutina con exito.");
            }
        }
    }

    /***
    * Funcion para genrear la factura electronica los parametros (factura, type(FACT,ANUL))
    **/
    public static function insertFelFactura( $factura, $type ){
        $fecha = $factura->factura1_fecha;
        $totalfactura = $factura->baseimporte + $factura->factura1_iva;

        switch ($type) {
            case 'FACT':
                $tipoDocumento = '01';
                $motivonota = '';
                $fechafacturación = $fecha;
                $fechafacturamodificada = $fecha;
                $consecutivofacturamodificada = 0;
                break;

            case 'ANUL':
                $tipoDocumento = '04';
                $motivonota = 2;
                $fechafacturación = $factura->factura1_fecha_anulacion;
                $fechafacturamodificada = $factura->factura1_fecha;
                $consecutivofacturamodificada = $factura->factura1_numero;
                break;

            case 'DEVO':
                $tipoDocumento = '04';
                $motivonota = 1;
                $fechafacturación = $factura->devolucion1_fecha_elaboro;
                $fechafacturamodificada = $factura->factura1_fecha;
                $consecutivofacturamodificada = $factura->factura1_numero;
                break;
        }

        $felfactura = new FelFactura;
        $felfactura->tokenempresa = '238d7e9f0d8e218fd4ce83bc8d58e7a36bbdf7e9';
        $felfactura->tokenpassword = '293ec00f1fde9e58599b3edc00b7f9ddf0739b9c';
        $felfactura->tipodepersona = $factura->tercero_persona == 'J' ? '1' : '2';
        $felfactura->razonsocial = $factura->tercero_razon_social;
        $felfactura->primernombre = $factura->tercero_nombre1;
        $felfactura->segundonombre = $factura->tercero_nombre2;
        $felfactura->primerapellido = $factura->tercero_apellido1;
        $felfactura->segundoapellido = $factura->tercero_apellido2;
        $felfactura->tipoidentificacion = $factura->tercero_tipodocumento == 'CC' ? 13 : 31;
        $felfactura->numeroidentificacion = $factura->tercero_nit;
        $felfactura->email = !empty($factura->tercero_email) ? $factura->tercero_email : '';
        $felfactura->departamento = $factura->departamento_nombre;
        $felfactura->barriolocalidad = '';
        $felfactura->ciudad = $factura->municipio_nombre;
        $felfactura->direccion = $factura->tercero_direccion;
        $felfactura->pais = 'CO';
        $felfactura->telefono = $factura->tercero_telefono;
        $felfactura->regimen = $factura->tercero_regimen == '1' ? 0 : 2;
        $felfactura->aplicafel = 'NO';
        $felfactura->tipoDocumento = $tipoDocumento;
        $felfactura->prefijo = $factura->factura1_prefijo;
        $felfactura->consecutivo = $factura->factura1_numero;
        $felfactura->rango = config('koi.puntoventa')[$factura->factura1_puntoventa];
        $felfactura->fechafacturación = $fechafacturación;
        $felfactura->consecutivofacturamodificada = $consecutivofacturamodificada;
        $felfactura->cufefacturamodificada = '';
        $felfactura->fechafacturamodificada = $fechafacturamodificada;
        $felfactura->motivonota = $motivonota;
        $felfactura->incoterms = '';
        $felfactura->estatuspago = '';
        $felfactura->fechavencimiento = $fecha;
        $felfactura->moneda = 'COP';
        $felfactura->mediodepago = '10';
        $felfactura->totaldescuentos = $factura->totaldescuentos;
        $felfactura->totalsinimpuestos = $factura->factura1_bruto;
        $felfactura->totalbaseimponible = $factura->baseimporte;
        $felfactura->totalfactura = $totalfactura;
        $felfactura->decripcion = substr($factura->factura1_observaciones, 0, 240);
        $felfactura->cufe = '';
        $felfactura->estadoactual = 0;
        $felfactura->fecharespuesta = $fecha;
        $felfactura->informacionAdicional = '';
        $felfactura->save();

        if( $type == 'DEVO' ){
            $query = Devolucion2::query();
            $query->select('devolucion2.*', 'producto_nombre', DB::raw("((devolucion2_precio-devolucion2_descuento)*devolucion2_cantidad) AS baseimponible, (devolucion2_iva*devolucion2_cantidad) AS valorretenido"));
            $query->join('producto', 'devolucion2_producto', '=', 'producto_serie');
            $query->where('devolucion2_numero', $factura->devolucion1_numero);
            $query->where('devolucion2_sucursal', $factura->devolucion1_sucursal);
            $productos = $query->get();

            foreach( $productos as $producto ){
                $preciototal = ( $producto->devolucion2_precio - $producto->devolucion2_descuento + $producto->devolucion2_iva) * $producto->devolucion2_cantidad;

                $felproducto = new FelProducto;
                $felproducto->idfactura = $felfactura->Id;
                $felproducto->codigoproducto = $producto->devolucion2_producto;
                $felproducto->descripcion = $producto->producto_nombre;
                $felproducto->cantidad = $producto->devolucion2_cantidad;
                $felproducto->unidadmedida = 'UN';
                $felproducto->descuento = $producto->devolucion2_descuento;
                $felproducto->preciosinimpuestos = $producto->valorretenido;
                $felproducto->preciototal = $preciototal;
                $felproducto->codigoimpuesto = '01';
                $felproducto->porcentajeimpuesto = '19';
                $felproducto->valorimpuesto = $producto->devolucion2_iva;
                $felproducto->baseimponible = $producto->baseimponible;
                $felproducto->idotroimpuesto = 0;
                $felproducto->precioUnitario = $producto->devolucion2_precio;
                $felproducto->save();

                $felimpuesto = new FelImpuestos;
                $felimpuesto->idfactura = $felfactura->Id;
                $felimpuesto->idproducto = $felproducto->Id;
                $felimpuesto->codigoimpuesto = '01';
                $felimpuesto->porcentajeimpuesto = '19';
                $felimpuesto->baseimponible = $producto->baseimponible;
                $felimpuesto->valorretenido = $producto->valorretenido;
                $felimpuesto->save();
            }
        }else{
            $query = Factura2::query();
            $query->select('factura2.*', 'producto_nombre', DB::raw("((factura2_precio_venta-factura2_descuento_pesos)*factura2_unidades_vendidas) AS baseimponible, (factura2_iva_pesos * factura2_unidades_vendidas) AS valorretenido"));
            $query->join('producto', 'factura2_producto', '=', 'producto_serie');
            $query->where('factura2_numero', $factura->factura1_numero);
            $query->where('factura2_sucursal', $factura->factura1_sucursal);
            $productos = $query->get();

            foreach( $productos as $producto ){
                $preciototal = ( $producto->factura2_precio_venta - $producto->factura2_descuento_pesos + $producto->factura2_iva_pesos) * $producto->factura2_unidades_vendidas;

                $felproducto = new FelProducto;
                $felproducto->idfactura = $felfactura->Id;
                $felproducto->codigoproducto = $producto->factura2_producto;
                $felproducto->descripcion = $producto->producto_nombre;
                $felproducto->cantidad = $producto->factura2_unidades_vendidas;
                $felproducto->unidadmedida = 'UN';
                $felproducto->descuento = $producto->factura2_descuento_pesos;
                $felproducto->preciosinimpuestos = $producto->valorretenido;
                $felproducto->preciototal = $preciototal;
                $felproducto->codigoimpuesto = '01';
                $felproducto->porcentajeimpuesto = $producto->factura2_iva_porcentaje;
                $felproducto->valorimpuesto = $producto->factura2_iva_pesos;
                $felproducto->baseimponible = $producto->baseimponible;
                $felproducto->idotroimpuesto = 0;
                $felproducto->precioUnitario = $producto->factura2_precio_venta;
                $felproducto->save();

                $felimpuesto = new FelImpuestos;
                $felimpuesto->idfactura = $felfactura->Id;
                $felimpuesto->idproducto = $felproducto->Id;
                $felimpuesto->codigoimpuesto = '01';
                $felimpuesto->porcentajeimpuesto = $producto->factura2_iva_porcentaje;
                $felimpuesto->baseimponible = $producto->baseimponible;
                $felimpuesto->valorretenido = $producto->valorretenido;
                $felimpuesto->save();
            }
        }

        return;
    }
}
