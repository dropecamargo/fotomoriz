<?php

namespace App\Models\Receivable;

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

    /**
    * Function getPagares -> extractos
    **/
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

    /**
    * Function getPagaresInteres -> intereses
    **/
    public static function getPagaresInteres( $fechamora )
    {
        $query = Pagare1::query();
        $query->select('pagare1_numero as numero', 'pagare1_documentos as docu', 'pagare1_sucursal as sucursal', 'pagare1_fecha as expedicion', 'pagare3_item as cuota', 'pagare3_vencimiento as vencimiento',
                        DB::raw("(pagare3_vencimiento - $fechamora ) as dias"), 'pagare3_saldo as valor', 'documentos_nombre as documento');
        $query->join('documentos', 'pagare1_documentos', '=', 'documentos_codigo');
        $query->join('pagare3', function($join){
            $join->on('pagare1_numero', '=', 'pagare3_numero');
            $join->on('pagare1_sucursal', '=', 'pagare3_sucursal');
        });
        $query->where(function($query){
            $query->whereRaw('pagare1_numero = pagare3_numero');
            $query->whereRaw('pagare1_sucursal = pagare3_sucursal');
        });
        $query-> whereRaw('documentos_codigo = pagare1_documentos');
        $query->whereRaw('pagare3_saldo <> 0');
        $query->orderBy('pagare3_vencimiento', 'asc');

        return $query->get();
    }
}
