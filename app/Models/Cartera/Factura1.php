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

    public static function getFacturas($fechai, $fechaf, $tercero)
    {
        $query = Factura1::query();
        $query->select(DB::raw("SUM(factura1_bruto - factura1_descuento + factura1_iva - factura1_retencion) AS facturacion"), DB::raw("COUNT(factura1_numero) AS numerofacturas"));
        $query->where('factura1_tercero', $tercero->tercero_nit);
        $query->where('factura1_fecha', '>=', $fechai);
        $query->where('factura1_fecha', '<', $fechaf);
        return $query->first();
    }
}
