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

    public static function getChequesInteres( $fechamora )
    {
        $query = Chdevuelto1::query();
        $query->join('documentos', 'chdevuelto1_documentos', '=', 'documentos_codigo');
        $query->select('chdevuelto1_numero as numero', 'chdevuelto1_documentos as docu', 'chdevuelto1_sucursal as sucursal', 'chdevuelto1_fecha as expedicion', 'chdevuelto1_fecha as vencimiento',
                        DB::raw("(chdevuelto1_fecha - $fechamora ) as dias"), 'chdevuelto1_saldo as valor', 'documentos_nombre as documento');
        $query->whereRaw('documentos_codigo = chdevuelto1_documentos');
        $query->whereRaw('chdevuelto1_saldo > 0');
        $query->orderBy('chdevuelto1_fecha', 'asc');
        return $query->get();
    }
}
