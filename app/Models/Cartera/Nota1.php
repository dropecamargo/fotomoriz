<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class Nota1 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nota1';

    public $timestamps = false;

    public static function getNotas($fechai, $fechaf, $tercero)
    {
        $query = Nota1::query();
        $query->select(DB::raw("SUM(CASE WHEN (nota1_anulada=false) THEN (nota1_valor) ELSE (-1*nota1_valor) END) AS nota"), DB::raw("COUNT(nota1_numero) AS numeronotas"));
        $query->where('nota1_tercero', $tercero->tercero_nit);
        $query->where(function ($query) use ($fechai, $fechaf) {
           $query->where(function ($query) use ($fechai, $fechaf){
               $query->where('nota1_fecha_elaboro', '>=', $fechai);
               $query->where('nota1_fecha_elaboro', '<', $fechaf);
           });
           $query->orWhere(function ($query) use ($fechai, $fechaf){
               $query->where('nota1_fecha_anulo', '>=', $fechai);
               $query->where('nota1_fecha_anulo', '<', $fechaf);
           });
        });
        return $query->first();
    }
}
