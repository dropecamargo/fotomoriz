<?php

namespace App\Models\Commercial;

use Illuminate\Database\Eloquent\Model;

class ConfiguraSabana extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configurasabana';

    public $timestamps = false;

    public static function getAgrupaciones(){
        return self::query()
                    ->select('configurasabana_nombre_agrupacion AS nom_agrupacion', 'configurasabana_agrupacion AS agrupacion')
                    ->groupBy('nom_agrupacion', 'agrupacion')
                    ->orderBy('agrupacion', 'asc')
                    ->get();
    }

    public static function getGrupos($agrupacion){
        return self::query()
                    ->select('configurasabana_grupo_nombre AS nom_grupo', 'configurasabana_grupo AS grupo')
                    ->where('configurasabana_agrupacion', $agrupacion)
                    ->groupBy('nom_grupo', 'grupo')
                    ->orderBy('grupo', 'asc')
                    ->get();
    }

    public static function getUnificaciones($agrupacion, $grupo){
        return self::query()
                    ->select('configurasabana_nombre_unificacion AS nom_unificacion', 'configurasabana_unificacion AS unificacion')
                    ->where('configurasabana_agrupacion', $agrupacion)
                    ->where('configurasabana_grupo', $grupo)
                    ->groupBy('nom_unificacion', 'unificacion')
                    ->orderBy('unificacion', 'asc')
                    ->get();
    }
}
