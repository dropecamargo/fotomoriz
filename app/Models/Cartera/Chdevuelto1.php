<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Chdevuelto1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chdevuelto1';

    public $timestamps = false;

    public static function getCheques($fechai, $fechaf, $tercero)
    {
        $query = Chdevuelto1::query();
        $query->select(DB::raw("SUM(chdevuelto1_valor) AS chdevuelto"), DB::raw("COUNT(chdevuelto1_numero) AS numerochdevueltos"));
        $query->where('chdevuelto1_tercero', $tercero->tercero_nit);
        $query->where('chdevuelto1_fecha', '>=', $fechai);
        $query->where('chdevuelto1_fecha', '<', $fechaf);
        return $query->first();
    }
}
