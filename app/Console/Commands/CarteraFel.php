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
        $this->line('Bienvenido a la rutina de facturas electronicas.');
        $ano = config('koi.app.ano');

        $preguntamesi = $this->ask('Digite el mes con el cual inicia la rutina (1 a 12)');
        $preguntaanoi = $this->ask('Digite el año con el cual inicia la rutina ('.$ano. ' a '. date('Y').')' );

        $preguntamesf = $this->ask('Digite el mes con el cual finaliza la rutina (1 a 12)');
        $preguntaanof = $this->ask('Digite el año con el cual finaliza la rutina ('.$ano. ' a '. date('Y').')');

        $mesi = intval($preguntamesi);
        $anoi = intval($preguntaanoi);
        $mesf = intval($preguntamesf);
        $anof = intval($preguntaanof);

        if( !($mesi > 0 && $mesi <= 12) || !($mesf > 0 && $mesf <= 12) ) {
            $this->error('El filtro de mes no es valido.');
            return;
        }

        if( $anoi < $ano || $anoi > date('Y') || $anof < $ano || $anof > date('Y') ) {
            $this->error('El rango de años permitidos es de '.$ano.' hasta '.date('Y'));
            return;
        }

        if( ($mesi > $mesf && $anoi == $anof) || $anoi > $anof ) {
            $this->error('La fecha final no puede ser mayor a la inicial');
            return;
        }

        if( $this->confirm("El mes y año que ha digitado es ".config('koi.meses')[$mesi]." ".$anoi." y ".config('koi.meses')[$mesf]." ".$anof."?", true) ){
            DB::beginTransaction();
            try{
                $this->info('Generando rutina de factura electronica.');

                $query = Factura1::query();
                $query->select('factura1.*', 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'municipio_nombre', 'departamento_nombre', 'tercero_direccion', 'tercero_telefono', DB::raw("(factura1_descuento_0 + factura1_descuento_30 + factura1_descuento_60 + factura1_descuento_90 + factura1_descuento_120) AS totaldescuentos"), DB::raw("(factura1_bruto - factura1_descuento) AS baseimporte"));
                $query->join('tercero', 'factura1_tercero', '=', 'tercero_nit');
                $query->join('municipios', 'tercero_municipios', '=', 'municipio_codigo');
                $query->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo');
                $query->join('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero');
                $query->where('factura1_anulada', false);
                $query->where(function($query) use ($mesi, $anoi){
                    $query->whereMonth('factura1_fecha_elaboro', '>=', $mesi);
                    $query->whereYear('factura1_fecha_elaboro', '>=', $anoi);
                });
                $query->where(function($query)  use ($mesf, $anof){
                    $query->whereMonth('factura1_fecha_elaboro', '<=', $mesf);
                    $query->whereYear('factura1_fecha_elaboro', '<=', $anof);
                });
                $query->where('factura1_puntoventa', '<>', '8');
                $query->orderBy('factura1_fecha_elaboro');
                $facturas = $query->get();

                foreach ( $facturas as $factura ) {

                    // Validar que no exita en fel_factura
                    $validfel = FelFactura::where('tipoDocumento', '01')->where('prefijo', $factura->factura1_prefijo)->where('consecutivo', $factura->factura1_numero)->first();
                    if( !$validfel instanceof FelFactura ){

                        // Insertar felfactura -> fel_encabezadofactura
                        $felfactura = $this->insertFelFactura( $factura );

                        $query = Factura2::query();
                        $query->select('factura2.*', 'producto_nombre', 'producto_referencia', DB::raw("((factura2_precio_venta-factura2_descuento_pesos)*factura2_unidades_vendidas) AS baseimponible, (factura2_iva_pesos * factura2_unidades_vendidas) AS valorretenido"));
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
                }

                DB::commit();
                Log::info('Se completo la rutina de factura electronica con exito.');
                $this->info('Se completo la rutina de factura electronica con exito.');
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                $this->error('No se pudo ejecutar la rutina con exito.');
            }
        }
    }

    public static function insertFelFactura( $factura ){
        $fecha = "$factura->factura1_fecha_elaboro $factura->factura1_hora_elaboro";
        $totalfactura = $factura->baseimporte + $factura->factura1_iva;

        $felfactura = new FelFactura;
        $felfactura->tokenempresa = '238d7e9f0d8e218fd4ce83bc8d58e7a36bbdf7e9';
        $felfactura->tokenpassword = '293ec00f1fde9e58599b3edc00b7f9ddf0739b9c';
        $felfactura->tipodepersona = $factura->tercero_persona == 'J' ? '1' : '2';
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
        $felfactura->email = !empty($factura->tercero_email) ? $factura->tercero_email : '';
        $felfactura->departamento = $factura->departamento_nombre;
        $felfactura->barriolocalidad = '';
        $felfactura->ciudad = $factura->municipio_nombre;
        $felfactura->direccion = $factura->tercero_direccion;
        $felfactura->pais = 'CO';
        $felfactura->telefono = $factura->tercero_telefono;
        $felfactura->regimen = $factura->tercero_regimen == '1' ? 0 : 2;
        $felfactura->aplicafel = 'NO';
        $felfactura->tipoDocumento = '01';
        $felfactura->prefijo = $factura->factura1_prefijo;
        $felfactura->consecutivo = $factura->factura1_numero;
        $felfactura->rango = config('koi.puntoventa')[$factura->factura1_puntoventa];
        $felfactura->fechafacturación = $fecha;
        $felfactura->consecutivofacturamodificada = 0;
        $felfactura->cufefacturamodificada = '';
        $felfactura->fechafacturamodificada = $fecha;
        $felfactura->motivonota = '';
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

        return $felfactura;
    }
}
