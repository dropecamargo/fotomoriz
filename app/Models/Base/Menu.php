<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Menu extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'framework';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The key used by cache store.
     *
     * @var static string
     */
    public static $key_cache = '_menu';

    public static function getMenu()
    {
        if (Cache::has( self::$key_cache )) {
            return Cache::get( self::$key_cache );
        }

        return Cache::rememberForever( self::$key_cache , function() {
            $query = Menu::query();
            $query->where('menu_activo', true);
            return $query->get();
        });
    }
}
