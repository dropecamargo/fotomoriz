<?php

namespace App\Models\Base;

use Zizaco\Entrust\EntrustRole;
use Validator, Cache;

class Rol extends EntrustRole
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'framework';

    public $timestamps = false;

    /**
     * The key used by cache store.
     *
     * @var static string
     */
    public static $key_cache = '_troles';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];

    public function isValid($data)
    {
        $rules = [
            'name' => 'alpha|unique:framework.rol',
            'display_name' => 'required',
        ];

        if ($this->exists){
            $rules['name'] .= ',name,' . $this->id;
        }else{
            $rules['name'] .= '|required';
        }

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }

    public static function getRoles()
    {
        if ( Cache::has(self::$key_cache)) {
            return Cache::get(self::$key_cache);
        }

        return Cache::rememberForever( self::$key_cache , function() {
            $query = Rol::query();
            $query->orderBy('display_name', 'asc');
            $collection = $query->pluck('display_name', 'rol.id');

            $collection->prepend('', '');
            return $collection;
        });
    }

    //Big block of caching functionality.
    public function cachedPermissions($module = null)
    {
        $rolePrimaryKey = $this->primaryKey;
        $cacheKey = "entrust_permissions_for_role_{$this->$rolePrimaryKey}_$module";
        return Cache::tags(config('entrust.permission_role_table'))->remember($cacheKey, config('cache.ttl'), function () use($module) {
            return $this->perms()->join('modulo1', 'modulo1.id', '=', 'permiso_rol.module_id')->where('modulo1.name', $module)->get();
        });
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(config('entrust.permission'), config('entrust.permission_role_table'), 'role_id', 'permission_id');
    }
}
