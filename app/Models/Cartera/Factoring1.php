<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Factoring1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'factoring1';

    public $timestamps = false;

    public static function getFactorings($fechai, $fechaf, $tercero)
    {
        $query = Factoring1::query();
        $query->select(DB::raw("SUM(factoring3_saldo) AS factoring"), DB::raw("COUNT(factoring1_numero) AS numerofactoring"));
        $query->join('factoring3', function($join){
            $join->on('factoring3_numero', '=', 'factoring1_numero');
            $join->on('factoring3_sucursal', '=', 'factoring1_sucursal');
        });
        $query->whereRaw("factoring3_numero = factoring1_numero");
        $query->whereRaw("factoring3_numero = factoring1_numero");
        $query->where('factoring3_tercero_cartera', $tercero->tercero_nit)->orWhere('factoring3_tercero_endoso', $tercero->tercero_nit);
        $query->where('factoring1_fecha', '>=', $fechai);
        $query->where('factoring1_fecha', '<', $fechaf);
        return $query->first();
    }
}
