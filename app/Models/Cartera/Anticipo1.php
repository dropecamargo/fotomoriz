<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Anticipo1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'anticipo1';

    public $timestamps = false;

    public static function getAnticipos($fechai, $fechaf, $tercero)
    {
        $query = Anticipo1::query();
        $query->select(DB::raw("SUM(anticipo1_valor) AS anticipo"), DB::raw("COUNT(anticipo1_numero) AS numeroanticipos"));
        $query->where('anticipo1_tercero', $tercero->tercero_nit);
        $query->where('anticipo1_fecha_elaboro', '>=', $fechai);
        $query->where('anticipo1_fecha_elaboro', '<', $fechaf);
        return $query->first();
    }
}
