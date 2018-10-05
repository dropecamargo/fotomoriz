<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Receivable\Factura1, App\Models\Receivable\Factura2, App\Models\Receivable\FelFactura, App\Models\Receivable\FelProducto, App\Models\Receivable\FelImpuestos, App\Models\Receivable\FelCamposAdicionales, App\Models\Receivable\Devolucion1, App\Models\Receivable\Devolucion2;
use Log, DB;

class CarteraFel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:facturas';

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
        // Filtro fechas inicial y final(-5 dias al inicial)
        $initaldate = date('Y-m-d', strtotime("-5 day"));
        $endate = date('Y-m-d');

        // Iniciando rutina
        DB::beginTransaction();
        try{
            // Msg de bienvenida
            Log::info('Iniciando rutina de facturas electronicas.');

            // Facturas
            $facturas = Factura1::query()
                ->select('factura1_numero AS numero', 'factura1_sucursal AS sucursal', 'factura1_fecha AS fecha', 'factura1_iva AS iva', 'factura1_bruto AS totalsinimpuestos', 'factura1_observaciones AS observaciones', 'factura1_fecha_anulacion AS anulacion', DB::raw("(factura1_descuento_0+factura1_descuento_30+factura1_descuento_60+factura1_descuento_90+factura1_descuento_120) AS totaldescuentos, (factura1_bruto-factura1_descuento) AS totalbaseimponible"), 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'tercero_email2', 'tercero_direccion', 'tercero_telefono', 'municipio_nombre', 'departamento_nombre', 'puntoventa_resolucion AS pv_resolucion', 'puntoventa_prefijo AS prefijo', 'puntoventa_desde AS pv_desde', DB::raw("puntoventa_prefijo AS f_prefijo, NULL AS f_numero, NULL AS f_fecha, 'FACT' AS tipo"))
                ->join('tercero', 'factura1_tercero', '=', 'tercero_nit')
                ->join('municipios', 'tercero_municipios', '=', 'municipio_codigo')
                ->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo')
                ->join('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero')
                ->where('factura1_puntoventa', '<>', '8')
                ->whereBetween('factura1_fecha', [$initaldate, $endate]);

            // Facturas Anuladas
            $anuladas = Factura1::query()
                ->select('factura1_numero AS numero', 'factura1_sucursal AS sucursal', 'factura1_fecha AS fecha', 'factura1_iva AS iva', 'factura1_bruto AS totalsinimpuestos', 'factura1_observaciones AS observaciones', 'factura1_fecha_anulacion AS anulacion', DB::raw("(factura1_descuento_0+factura1_descuento_30+factura1_descuento_60+factura1_descuento_90+factura1_descuento_120) AS totaldescuentos, (factura1_bruto-factura1_descuento) AS totalbaseimponible"), 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'tercero_email2', 'tercero_direccion', 'tercero_telefono', 'municipio_nombre', 'departamento_nombre', 'puntoventa_resolucion AS pv_resolucion', DB::raw("'NC' || puntoventa_prefijo AS prefijo, 1 AS pv_desde"), 'puntoventa_prefijo AS f_prefijo', 'factura1_numero AS f_numero', 'factura1_fecha AS f_fecha', DB::raw("'ANUL' AS tipo"))
                ->join('tercero', 'factura1_tercero', '=', 'tercero_nit')
                ->join('municipios', 'tercero_municipios', '=', 'municipio_codigo')
                ->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo')
                ->join('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero')
                ->where('factura1_puntoventa', '<>', '8')
                ->whereBetween('factura1_fecha_anulacion', [$initaldate, $endate])
                ->where('factura1_anulada', true)
                ->unionAll($facturas);

            // Devoluciones
            $devoluciones = Devolucion1::query()
                ->select('devolucion1_numero AS numero', 'devolucion1_sucursal AS sucursal', 'devolucion1_fecha_elaboro AS fecha', 'devolucion1_iva AS iva', 'devolucion1_bruto AS totalsinimpuestos', 'devolucion1_observaciones AS observaciones', DB::raw("NULL AS anulacion"), 'devolucion1_descuento AS totaldescuentos', DB::raw("(devolucion1_bruto-devolucion1_descuento) AS totalbaseimponible"), 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'tercero_email2', 'tercero_direccion', 'tercero_telefono', 'municipio_nombre', 'departamento_nombre', 'puntoventa_resolucion AS pv_resolucion', DB::raw("'NC' || puntoventa_prefijo AS prefijo"), DB::raw("1 AS pv_desde"), 'puntoventa_prefijo AS f_prefijo', 'factura1_numero AS f_numero', 'factura1_fecha AS f_fecha', DB::raw("'DEVO' AS tipo"))
                ->whereBetween('devolucion1_fecha_elaboro', [$initaldate, $endate])
                ->join('factura1', function($join){
                    $join->on('factura1_numero', '=', 'devolucion1_factura_numero')
                        ->on('factura1_sucursal', '=', 'devolucion1_factura_sucursal');
                })
                ->join('tercero', 'devolucion1_tercero', '=', 'tercero.tercero_nit')
                ->join('municipios', 'tercero_municipios', '=', 'municipio_codigo')
                ->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo')
                ->join('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero')
                ->where('factura1_puntoventa', '<>', '8')
                ->unionAll($anuladas)
                ->orderBy('fecha', 'asc')
                ->get();

            // Variable con los datos
            $datos = $devoluciones;

            foreach ($datos as $dato) {
                // Validar que no se repitan fels
                $validarfactura = FelFactura::where('prefijo', $dato->prefijo)->where('consecutivo', $dato->numero)->first();

                if( !$validarfactura instanceof FelFactura ){
                    // Insertar items en fel factura
                    $insertitem = $this->insertFelFactura($dato, $dato->tipo);
                    if($insertitem != 'OK'){
                        DB::rollback();
                        return $this->error($insertitem);
                    }
                }
            }

            DB::commit();
            Log::info("Se completo la rutina de factura electronica con exito.");
        }catch(\Exception $e){
            DB::rollback();
            Log::error($e->getMessage());
        }
    }

    /***
    * Funcion para genrear la factura electronica los parametros (items, type(FACT,ANUL,DEVO))
    **/
    public static function insertFelFactura($dato, $type) {
        // Preparar datos fecha->factura1_fecha/devolucion1_fecha_elaboro
        $fecha = $dato->fecha;
        $totalfactura = $dato->totalbaseimponible + $dato->iva;
        $estadoactual = 0;
        // Validar que tenga email2, email1 o por defecto wnieves@fotomoriz.com
        if ( !empty($dato->tercero_email2) ){
            $email = $dato->tercero_email2;
        }else if ( !empty($dato->tercero_email) ){
            $email = $dato->tercero_email;
        }else {
            $email = 'wnieves@fotomoriz.com';
        }

        // Validar que tercero_mail contenga @ && que no contengan varios email con ;
        $validarcorreo = strpos($email, '@');
        if($validarcorreo !== false){
            $validar = strpos($email, ';');
            if($validar !== false){
                $email = explode(';', $email);
                $email = $email[0];
            }
        }

        switch ( $type ) {
            case 'FACT':
                $tipoDocumento = '01';
                $motivonota = '';
                $fechafacturación = $fecha;
                $fechafacturamodificada = $fecha;
                $consecutivofacturamodificada = 0;
                $cufefacturamodificada = '';
                break;

            case 'ANUL':
                $tipoDocumento = '04';
                $motivonota = 2;
                $fechafacturación = $dato->anulacion;
                $cufefacturamodificada = '';
                $consecutivofacturamodificada = 0;
                break;

            case 'DEVO':
                $tipoDocumento = '04';
                $motivonota = 1;
                $fechafacturación = $fecha;
                $consecutivofacturamodificada = $dato->f_numero;
                break;
        }

        if( $type != 'FACT' ){
            // Recuperar fel_factura anterior en caso de ser anul o devo
            $prevfactura = FelFactura::where('prefijo', $dato->f_prefijo)->where('consecutivo', $dato->f_numero)->first();
            if( !$prevfactura instanceof FelFactura ){
                return "OK";
            }

            if( $type == 'DEVO' ){
                if( date('Y-m-d', strtotime($prevfactura->fechafacturación)) <= date('Y-m-d', strtotime("2018-09-01"))){
                    $estadoactual = 5;
                }
            }

            $cufefacturamodificada = $prevfactura->cufe;
            $fechafacturamodificada = $prevfactura->fechafacturación;
        }

        // Preparando los datos de fel_factura
        $felfactura = new FelFactura;
        $felfactura->tokenempresa = '238d7e9f0d8e218fd4ce83bc8d58e7a36bbdf7e9';
        $felfactura->tokenpassword = '293ec00f1fde9e58599b3edc00b7f9ddf0739b9c';
        $felfactura->tipodepersona = $dato->tercero_persona == 'J' ? '1' : '2';
        $felfactura->razonsocial = $dato->tercero_razon_social;
        $felfactura->primernombre = $dato->tercero_nombre1;
        $felfactura->segundonombre = $dato->tercero_nombre2;
        $felfactura->primerapellido = $dato->tercero_apellido1;
        $felfactura->segundoapellido = $dato->tercero_apellido2;
        $felfactura->tipoidentificacion = $dato->tercero_tipodocumento == 'CC' ? 13 : 31;
        $felfactura->numeroidentificacion = $dato->tercero_nit;
        $felfactura->email = $email;
        $felfactura->departamento = $dato->departamento_nombre;
        $felfactura->barriolocalidad = '';
        $felfactura->ciudad = $dato->municipio_nombre;
        $felfactura->direccion = $dato->tercero_direccion;
        $felfactura->pais = 'CO';
        $felfactura->telefono = $dato->tercero_telefono;
        $felfactura->regimen = $dato->tercero_regimen == '1' ? 0 : 2;
        $felfactura->aplicafel = 'SI';
        $felfactura->tipoDocumento = $tipoDocumento;
        $felfactura->prefijo = $dato->prefijo;
        $felfactura->consecutivo = $dato->numero;
        $felfactura->rango = ($dato->prefijo) ? "$dato->prefijo-$dato->pv_desde" : "$dato->pv_desde";
        $felfactura->fechafacturación = $fechafacturación;
        $felfactura->consecutivofacturamodificada = $consecutivofacturamodificada;
        $felfactura->cufefacturamodificada = $cufefacturamodificada;
        $felfactura->fechafacturamodificada = $fechafacturamodificada;
        $felfactura->motivonota = $motivonota;
        $felfactura->incoterms = '';
        $felfactura->estatuspago = '';
        $felfactura->fechavencimiento = $fecha;
        $felfactura->moneda = 'COP';
        $felfactura->mediodepago = '10';
        $felfactura->totaldescuentos = $dato->totaldescuentos;
        $felfactura->totalsinimpuestos = $dato->totalsinimpuestos;
        $felfactura->totalbaseimponible = $dato->totalbaseimponible;
        $felfactura->totalfactura = $totalfactura;
        $felfactura->decripcion = substr($dato->observaciones, 0, 240);
        $felfactura->cufe = '';
        $felfactura->estadoactual = $estadoactual;
        $felfactura->fecharespuesta = $fecha;
        $felfactura->informacionAdicional = '';
        $felfactura->save();

        // No crear registro en campos adicionales cuando es devolucion
        if( $type != 'DEVO'){
            // Insertar en la table fel_camposadicionales
            $felcamposadicionales = new FelCamposAdicionales;
            $felcamposadicionales->idfactura = $felfactura->Id;
            $felcamposadicionales->nombrecampo = '10027';
            $felcamposadicionales->valorcampo = $dato->pv_resolucion;
            $felcamposadicionales->controlinterno1 = '';
            $felcamposadicionales->controlinterno2 = '';
            $felcamposadicionales->campopdf = 1;
            $felcamposadicionales->campoxml = 1;
            $felcamposadicionales->save();
        }

        if( $type == 'DEVO' ){
            $query = Devolucion2::query();
            $query->select('devolucion2.*', 'producto_nombre', DB::raw("((devolucion2_precio-devolucion2_descuento)*devolucion2_cantidad) AS baseimponible, (devolucion2_iva*devolucion2_cantidad) AS valorretenido"));
            $query->join('producto', 'devolucion2_producto', '=', 'producto_serie');
            $query->where('devolucion2_numero', $dato->numero);
            $query->where('devolucion2_sucursal', $dato->sucursal);
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
            $query->where('factura2_numero', $dato->numero);
            $query->where('factura2_sucursal', $dato->sucursal);
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

        return 'OK';
    }
}
