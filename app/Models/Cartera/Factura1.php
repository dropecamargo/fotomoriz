<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Factura1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'factura1';

    public $timestamps = false;

    /**
    *  Function getFacturas -> extractos
    **/
    public static function getFacturas($fechai, $fechaf, $tercero)
    {
        $query = Factura1::query();
        $query->select(DB::raw("SUM(factura1_bruto - factura1_descuento + factura1_iva - factura1_retencion) AS facturacion"), DB::raw("COUNT(factura1_numero) AS numerofacturas"));
        $query->where('factura1_tercero', $tercero->tercero_nit);
        $query->where('factura1_fecha', '>=', $fechai);
        $query->where('factura1_fecha', '<', $fechaf);
        return $query->first();
    }

    /**
    *  Function getFacturaInteres -> intereses
    **/
    public static function getFacturaInteres($fechamora)
    {
        $query = Factura1::query();
        $query->select('factura1_numero as numero', 'factura1_documentos as docu', 'factura1_sucursal as sucursal', 'factura1_fecha as expedicion', 'factura3_cuota as cuota', 'factura3_vencimiento as vencimiento', DB::raw("DATE(factura3_vencimiento - $fechamora ) as dias"), 'factura3_saldo as valor', 'documentos_nombre as documento');
        $query->join('documentos', 'factura1_documentos', '=', 'documentos_codigo');
        $query->join('factura3', function($join){
            $join->on('factura1_numero', '=', 'factura3_numero');
            $join->on('factura1_sucursal', '=', 'factura3_sucursal');
        });
        $query->where(function($query){
            $query->whereRaw('factura3_numero = factura1_numero');
            $query->whereRaw('factura3_sucursal = factura1_sucursal');
        });
        $query->whereRaw('documentos_codigo = factura1_documentos');
        $query->whereRaw('factura3_saldo > 0');
        $query->whereNotNull('factura1_anulada');
        $query->orderBy('factura3_vencimiento', 'asc');

        return $query->get();
    }

    /**
    *  consula para traer las facturas para generar la rutina de fels
    **/
    public static function getFacturasElectronicas($fechai, $fechaf)
    {
        $query = Factura1::query();
        $query->select('factura1.*', 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'municipio_nombre', 'departamento_nombre', 'tercero_direccion', 'tercero_telefono', DB::raw("(factura1_descuento_0 + factura1_descuento_30 + factura1_descuento_60 + factura1_descuento_90 + factura1_descuento_120) AS totaldescuentos"), DB::raw("(factura1_bruto - factura1_descuento) AS baseimporte"));
        $query->join('tercero', 'factura1_tercero', '=', 'tercero_nit');
        $query->join('municipios', 'tercero_municipios', '=', 'municipio_codigo');
        $query->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo');
        $query->join('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero');
        $query->where('tercero_tipodocumento', '<>', 'XX');
        $query->where('factura1_puntoventa', '<>', '8');
        $query->orderBy('factura1_fecha');

        return $query;
    }
}
