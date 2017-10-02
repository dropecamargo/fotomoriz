<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tercero extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tercero';

    public $timestamps = false;

    public static function getTercerosCierrecartera($año, $mes){
        $query = Tercero::query();
        $query->select('tercero_nit', 'tercero_plazo_cartera', 'tercero_telefono', 'tercero_telefono2', 'tercero_direccion', 'tercero_email', DB::raw("tercero_nombre1 || ' ' || tercero_nombre2 AS tercero_nombres"), DB::raw("tercero_apellido1 || ' ' || tercero_apellido2 AS tercero_apellidos"), 'tercero_razon_social', DB::raw("(CASE WHEN tercero_persona = 'N' THEN (tercero_nombre1 || ' ' || tercero_nombre2 || ' ' || tercero_apellido1 || ' ' || tercero_apellido2 || (CASE WHEN (tercero_razon_social IS NOT NULL AND tercero_razon_social != '') THEN (' - ' || tercero_razon_social) ELSE '' END) ) ELSE tercero_razon_social END) AS tercero_nombre"));
        $query->whereIn('tercero_nit', DB::table('cierrecartera')->select('cierrecartera_tercero')->where('cierrecartera_mes', $mes)->where('cierrecartera_ano', $año));
        return $query->get();
    }
}
