<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pagare1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pagare1';

    public $timestamps = false;

    public static function getPagares($fechai, $fechaf, $tercero)
    {
        $query = Pagare1::query();
        $query->select(DB::raw("SUM(pagare3_saldo) as pagare"), DB::raw("COUNT(pagare1_numero) AS numeropagares"));
        $query->join('pagare3', function($join){
            $join->on('pagare3_numero', '=', 'pagare1_numero');
            $join->on('pagare3_sucursal', '=', 'pagare1_sucursal');
        });
        $query->whereRaw("pagare3_numero = pagare1_numero");
        $query->whereRaw("pagare3_sucursal = pagare1_sucursal");
        $query->where('pagare1_tercero_destino', $tercero->tercero_nit);
        $query->where('pagare1_fecha', '>=', $fechai);
        $query->where('pagare1_fecha', '<', $fechaf);
        return $query->first();
    }
}
