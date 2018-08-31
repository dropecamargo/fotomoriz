<?php

namespace App\Models\Receivable;

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
        $query->select('factura1_fecha', 'factura1_iva', 'factura1_fecha_anulacion', 'factura1_numero', 'factura1_sucursal', 'factura1_prefijo', 'factura1_bruto', 'factura1_observaciones', 'tercero_persona', 'tercero_razon_social', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_tipodocumento', 'puntoventa_resolucion', 'tercero_nit', 'tercero_regimen', 'tercero_email', 'tercero_direccion', 'tercero_telefono', 'devolucion1_factura_numero', 'devolucion1_numero', 'devolucion1_sucursal', 'municipio_nombre', 'departamento_nombre', 'puntoventa_prefijo', 'puntoventa_desde', DB::raw("(factura1_descuento_0+factura1_descuento_30+factura1_descuento_60+factura1_descuento_90+factura1_descuento_120) AS totaldescuentos, (factura1_bruto-factura1_descuento) AS baseimporte, CASE WHEN(factura1_anulada=false) AND (devolucion1_factura_numero IS NULL) THEN 'FACT' WHEN (factura1_anulada = true) AND (devolucion1_factura_numero IS NULL) THEN 'ANUL' WHEN (factura1_anulada=false) AND (devolucion1_factura_numero IS NOT NULL) THEN 'DEVO' ELSE '' END as tipo"));
        $query->join('tercero', 'factura1_tercero', '=', 'tercero_nit');
        $query->join('municipios', 'tercero_municipios', '=', 'municipio_codigo');
        $query->join('departamentos', 'municipio_departamento', '=', 'departamento_codigo');
        $query->where('factura1_puntoventa', '<>', '8');
        $query->leftJoin('puntoventa', 'factura1_puntoventa', '=', 'puntoventa_numero');
        $query->leftJoin('devolucion1', function($join) {
            $join->on('factura1_numero', '=', 'devolucion1_factura_numero');
            $join->on('factura1_sucursal', '=', 'devolucion1_factura_sucursal');
        });
        $query->where(function($query) use ($fechai, $fechaf) {
            $query->whereBetween('factura1_fecha', [$fechai, $fechaf])->where('factura1_anulada', false);
        });
        $query->orWhere(function($query) use ($fechai, $fechaf) {
            $query->whereBetween('factura1_fecha_anulacion', [$fechai, $fechaf])->where('factura1_anulada', true);
        });
        $query->orderBy('factura1_fecha');
        return $query->get();
    }
}
