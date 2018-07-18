<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Cache;

class UnidadDecision extends Model
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

    public static function getUnidadesDecision()
    {
        if (Cache::has( self::$key_cache )) {
            return Cache::get( self::$key_cache );
        }

        return Cache::rememberForever( self::$key_cache , function() {
            $query = UnidadDecision::query();
            $query->select('unidaddecision_codigo', 'unidaddecision_nombre');
            $query->orderby('unidaddecision_nombre', 'asc');

            $collection = $query->pluck('unidaddecision_nombre', 'unidaddecision_codigo');
            $collection->prepend('', '');
            return $collection;
        });
    }
}
