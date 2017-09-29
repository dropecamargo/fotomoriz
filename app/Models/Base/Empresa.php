<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'empresa';

    public static function getEmpresa()
    {
    	$query = Empresa::query();
        $query->select('empresa_nit', 'empresa_nombre');
    	return $query->first();
    }
}
