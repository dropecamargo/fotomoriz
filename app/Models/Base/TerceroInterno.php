<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use DB;

class TerceroInterno extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tercerointerno';

    public $timestamps = false;

    public static function getTerceroInterno($id)
    {
        $query = TerceroInterno::query();
        $query->select('tercerointerno.*', 'unidaddecision_nombre', DB::raw("(CASE WHEN t.tercero_persona = 'N' THEN (t.tercero_nombre1 || ' ' || t.tercero_nombre2 || ' ' || t.tercero_apellido1 || ' ' || t.tercero_apellido2 ) END ) AS tercero_nombre"), 'te.tercero_razon_social', 'te.tercero_nit');
        $query->join('tercero as t', 'tercerointerno_codigo', '=', 't.tercero_nit');
        $query->join('tercero as te', 'tercerointerno_tercero', '=', 'te.tercero_nit');
        $query->join('unidaddecision', 'tercerointerno_unidadecision', '=', 'unidaddecision_codigo');
        $query->where('tercerointerno_codigo', $id);
        return $query->first();
    }
}
