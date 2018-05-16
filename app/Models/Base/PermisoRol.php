<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class PermisoRol extends Model
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
    protected $table = 'permiso_rol';

	public $incrementing = false;

    public $timestamps = false;
}
