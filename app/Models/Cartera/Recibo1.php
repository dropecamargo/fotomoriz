<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Recibo1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recibo1';

    public $timestamps = false;

    public static function getPagos($fechai, $fechaf, $tercero)
    {
        $query = Recibo1::query();
        $query->select(DB::raw("SUM(recibo1_valor) AS recibo"), DB::raw("COUNT(recibo1_numero) AS numerorecibos"));
        $query->where('recibo1_tercero', $tercero->tercero_nit);
        $query->where('recibo1_fecha_elaboro', '>=', $fechai);
        $query->where('recibo1_fecha_elaboro', '<', $fechaf);
        return $query->first();
    }
}
