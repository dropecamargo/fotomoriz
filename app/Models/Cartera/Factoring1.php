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

    /**
    * Function getFactorings -> extractos
    **/
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

    /**
    * Function getFactoringInteres -> intereses
    **/
    public static function getFactoringInteres( $fechamora )
    {
        $query = Factoring1::query();
        $query->select('factoring1_numero as numero', 'factoring1_documentos as docu', 'factoring1_sucursal as sucursal', 'factoring1_fecha as expedicion', DB::raw("1 as cuota"), 'factoring3_vence as vencimiento',
                        DB::raw("(factoring3_vence - $fechamora ) as dias"), 'factoring3_saldo as valor', 'factoring3_num_doc as aliasnumero', 'documentos_nombre as documento');
        $query->join('documentos', 'factoring1_documentos', '=', 'documentos_codigo');
        $query->join('factoring3', function($join){
            $join->on('factoring1_numero', '=', 'factoring3_numero');
            $join->on('factoring1_sucursal', '=', 'factoring3_sucursal');
        });
        $query->whereRaw('documentos_codigo = factoring1_documentos');
        $query->where(function($query){
            $query->whereRaw('factoring3_numero = factoring1_numero');
            $query->whereRaw('factoring3_sucursal = factoring1_sucursal');
        });
        $query->whereRaw('factoring3_saldo > 0');

        return $query->get();
    }
}
