<?php

namespace App\Models\Receivable;

use Illuminate\Database\Eloquent\Model;
use DB;

class Devolucion1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devolucion1';

    public $timestamps = false;

    public static function getDevoluciones($fechai, $fechaf, $tercero)
    {
        $query = Devolucion1::query();
        $query->select(DB::raw("SUM(devolucion1_bruto - devolucion1_descuento + devolucion1_iva - devolucion1_retencion) AS devolucion"), DB::raw("COUNT(devolucion1_numero) AS numerodevoluciones"));
        $query->where('devolucion1_tercero', $tercero->tercero_nit);
        $query->where('devolucion1_fecha_elaboro', '>=', $fechai);
        $query->where('devolucion1_fecha_elaboro', '<', $fechaf);
        return $query->first();
    }
}
