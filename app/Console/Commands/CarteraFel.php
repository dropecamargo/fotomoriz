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

        $fechai = $anoi."-".$mesi."-01";
        $fechaf = $anof."-".$mesf."-01";

        if( $fechai > $fechaf ) {
            $this->error('La fecha final no puede ser mayor a la inicial');
            return;
        }

        if( $this->confirm("La fecha inicial que ha digitado es $fechai y fecha fin $fechaf?", true) ){
            DB::beginTransaction();
            try{
                $query = Factura1::query();
                $query->select('factura1.*', 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'municipio_nombre', 'departamento_nombre', 'tercero_direccion', 'tercero_telefono', DB::raw("(factura1_descuento_0 + factura1_descuento_30 + factura1_descuento_60 + factura1_descuento_90 + factura1_descuento_120) AS totaldescuentos"), DB::raw("(factura1_bruto - factura1_descuento) AS baseimporte"));
                $query->join('tercero', 'factura1_tercero', '=', 'tercero_nit');
                $query->join('municipios', 'tercero_municipios', '=', 'municipio_codigo');
                $query->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo');
                $query->where('factura1_anulada', false);
                $query->whereRaw("factura1_fecha_elaboro > '$fechai'");
                $query->whereRaw("factura1_fecha_elaboro < '$fechaf'");
                $facturas = $query->get();

                foreach ( $facturas as $factura ) {
                    // Insertar felfactura -> fel_encabezadofactura
                    $felfactura = FelFactura::insertFelFactura( $factura );

                    $query = Factura2::query();
                    $query->select('factura2.*', 'producto_nombre', 'producto_referencia', DB::raw("(factura2_precio_venta - factura2_descuento_pesos) AS baseimponible"));
                    $query->join('producto', 'factura2_producto', '=', 'producto_serie');
                    $query->where('factura2_numero', $factura->factura1_numero);
                    $query->where('factura2_sucursal', $factura->factura1_sucursal);
                    $productos = $query->get();

                    foreach( $productos as $producto ){
                        $preciototal = ($producto->factura2_precio_venta + $producto->factura2_iva_pesos);

                        $felproducto = new FelProducto;
                        $felproducto->idfactura = $felfactura;
                        $felproducto->codigoproducto = $producto->factura2_producto;
                        $felproducto->descripcion = $producto->producto_nombre;
                        $felproducto->referencia = $producto->producto_referencia;
                        $felproducto->cantidad = $producto->factura2_unidades_vendidas;
                        $felproducto->unidadmedida = 'UN';
                        $felproducto->valorunitario = $producto->factura2_precio_venta;
                        $felproducto->descuento = $producto->factura2_descuento_pesos;
                        $felproducto->preciosinimpuestos = $producto->factura2_precio_venta;
                        $felproducto->preciototal = $preciototal;
                        $felproducto->codigoimpuesto = '01';
                        $felproducto->porcentajeimpuesto = $producto->factura2_iva_porcentaje;
                        $felproducto->valorretenido = $producto->factura2_iva_pesos;
                        $felproducto->baseimponible = $producto->baseimponible;
                        $felproducto->save();
                    }

                    $query = Factura2::query();
                    $query->select('factura2_iva_porcentaje', DB::raw("SUM(factura2_iva_pesos * factura2_unidades_vendidas) AS valorretenido"), DB::raw("SUM( (factura2_precio_venta - factura2_descuento_pesos) * factura2_unidades_vendidas ) AS baseimponible") );
                    $query->where('factura2_numero', $factura->factura1_numero);
                    $query->where('factura2_sucursal', $factura->factura1_sucursal);
                    $query->groupBy('factura2_iva_porcentaje');
                    $impuestos = $query->get();

                    foreach ($impuestos as $impuesto) {
                        $felimpuesto = new FelImpuestos;
                        $felimpuesto->idfactura = $felfactura;
                        $felimpuesto->codigoimpuesto = '01';
                        $felimpuesto->porcentajeimpuesto = $impuesto->factura2_iva_porcentaje;
                        $felimpuesto->valorretenido = $impuesto->valorretenido;
                        $felimpuesto->baseimponible = $impuesto->baseimponible;
                        $felimpuesto->save();
                    }
                }

                DB::rollback();
                $this->info('!OK Bitches');

                // DB::commit();
                // $this->question('!OK Bitches');
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                $this->error('No se pudo ejecutar la rutina con exito.');
            }
        }
    }
}
