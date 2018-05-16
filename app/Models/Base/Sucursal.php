<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Sucursal extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sucursal';

    public $timestamps = false;

    public $incrementing = false;

    public $primaryKey = 'sucursal_codigo';

    /**
     * The key used by cache store.
     *
     * @var static string
     */
    public static $key_cache = '_sucursales';

 	public static function getSucursales()
    {
        if (Cache::has( self::$key_cache )) {
            return Cache::get( self::$key_cache );
        }

        return Cache::rememberForever( self::$key_cache , function() {
            $query = Sucursal::query();
            $query->select('sucursal_codigo','sucursal_nombre');
            $query->where('sucursal_activa', true);
            $query->orderby('sucursal_nombre', 'asc');
            $collection = $query->pluck('sucursal_nombre', 'sucursal_codigo');

            return $collection;
        });
    }
}
