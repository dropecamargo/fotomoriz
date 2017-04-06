<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Unidaddecision extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unidaddecision';

    public $timestamps = false;

    public $primaryKey = 'unidaddecision_codigo';

    /**
     * The key used by cache store.
     *
     * @var static string
     */
    public static $key_cache = '_unidaddecision';

 	public static function getUnidaddecision()
    {
        if (Cache::has( self::$key_cache )) {
            return Cache::get( self::$key_cache );
        }

        return Cache::rememberForever( self::$key_cache , function() {
            $query = Unidaddecision::query();
            $query->select('unidaddecision_codigo','unidaddecision_nombre');
            $query->orderby('unidaddecision_nombre', 'asc');
            $collection = $query->lists('unidaddecision_nombre', 'unidaddecision_codigo');
            return $collection;
        });
    }
}
