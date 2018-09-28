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
}
