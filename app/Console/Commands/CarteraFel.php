<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Receivable\Factura1, App\Models\Receivable\Factura2, App\Models\Receivable\FelFactura, App\Models\Receivable\FelProducto, App\Models\Receivable\FelImpuestos, App\Models\Receivable\FelCamposAdicionales, App\Models\Receivable\PuntoVenta, App\Models\Receivable\Devolucion2;
use Log, DB, Validator;

class CarteraFel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:facturas {fechai} {fechaf}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar facturas electronicas.';

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
        $this->line('Bienvenido a la rutina de facturas electronicas.');

        if( strlen($this->argument('fechai')) != 10 || strlen($this->argument('fechaf')) != 10 ){
            $this->error("Las fechas son incorrectas formato (YYYY.MM.DD)");
            return;
        }

        // Convertir en fecha
        list($anoi,$mesi,$dayi) = explode('.', $this->argument('fechai'));
        list($anof,$mesf,$dayf) = explode('.', $this->argument('fechaf'));

        // Validar que fecha inicial no sea mayor a la final
        $fechai = date('Y-m-d', strtotime("$anoi-$mesi-$dayi"));
        $fechaf = date('Y-m-d', strtotime("$anof-$mesf-$dayf"));
        if( $fechaf < $fechai ) {
            $this->error('La fecha final no puede ser menor a la inicial');
            return;
        }

        // Confirmar rutina
        DB::beginTransaction();
        try{
            $this->info('Generando rutina de factura electronica.');

            // Consulta para traer facturas en el rango de fechas
            $facturas = Factura1::getFacturasElectronicas($fechai, $fechaf);
            $bar = $this->output->createProgressBar(count($facturas));
            foreach ($facturas as $factura) {
                // Validar que no exita en fel_factura
                $existsfel = FelFactura::where('tipoDocumento', '01')->where('prefijo', $factura->factura1_prefijo)->where('consecutivo', $factura->factura1_numero)->first();
                if( !$existsfel instanceof FelFactura ){
                    // Validar factura
                    $validfel = $this->validateFelFactura($factura, $factura->tipo);
                    if($validfel != 'OK'){
                        DB::rollback();
                        return $this->error($validfel);
                    }
                }
                $bar->advance();
            }
            $bar->finish();

            DB::commit();
            $this->info("\nSe completo la rutina de factura electronica con exito.");
        }catch(\Exception $e){
            DB::rollback();
            $this->error("No se pudo ejecutar la rutina con exito.");
        }
    }

    /***
    * Funcion para genrear la factura electronica los parametros (factura, type(FACT,ANUL))
    **/
    public static function validateFelFactura($factura, $type){
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
        $felfactura->email = !empty($factura->tercero_email) ? $factura->tercero_email : 'sistemas@fotomoriz.com';
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
        $felfactura->rango = ($factura->puntoventa_prefijo) ? "$factura->puntoventa_prefijo-$factura->puntoventa_desde" : "$factura->puntoventa_desde";
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

        // Insertar en la table fel_camposadicionales
        $felcamposadicionales = new FelCamposAdicionales;
        $felcamposadicionales->idfactura = $felfactura->Id;
        $felcamposadicionales->nombrecampo = '10027';
        $felcamposadicionales->valorcampo = $factura->puntoventa_resolucion;
        $felcamposadicionales->controlinterno1 = '';
        $felcamposadicionales->controlinterno2 = '';
        $felcamposadicionales->campopdf = 1;
        $felcamposadicionales->campoxml = 1;
        $felcamposadicionales->save();

        // Cuando la factura es anulada, cree un registro con valores normales
        if( $type == 'ANUL'){
            $anulfelfactura = $felfactura->replicate();
            $anulfelfactura->tipoDocumento = '01';
            $anulfelfactura->motivonota = '';
            $anulfelfactura->fechafacturación = $fecha;
            $anulfelfactura->fechafacturamodificada = $fecha;
            $anulfelfactura->consecutivofacturamodificada = 0;
            $anulfelfactura->save();

            $anulfelcamposadicionales = $felcamposadicionales->replicate();
            $anulfelcamposadicionales->idfactura = $anulfelfactura->Id;
            $anulfelcamposadicionales->save();
        }

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

                if( $type == 'ANUL'){
                    $anulfelproducto = $felproducto->replicate();
                    $anulfelproducto->idfactura = $anulfelfactura->Id;
                    $anulfelproducto->save();

                    $anulfelimpuesto = $felimpuesto->replicate();
                    $anulfelimpuesto->idfactura = $anulfelfactura->Id;
                    $anulfelimpuesto->save();
                }
            }
        }

        return 'OK';
    }
}
