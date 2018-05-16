<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
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
    protected $table = 'modulo1';

    public $timestamps = false;

    public $incrementing = false;

    public static function getModules()
    {
        $query = Modulo::query();
        $query->select('modulo1.id', 'display_name', 'nivel1');
        $query->where('nivel1', '!=', '0');
        $query->where('nivel2', '=', '0');
        $query->where('nivel3', '=', '0');
        $query->where('nivel4', '=', '0');
        $query->orderBy('nivel1', 'asc');
        $fathers = $query->get();

        $data = [];
        foreach ($fathers as $fathers) {
            $object = new \stdClass();
            $object->id = $fathers->id;
            $object->display_name = $fathers->display_name;

            $query = Modulo::query();
            $query->select('modulo1.id', 'display_name', 'nivel1', 'nivel2', 'nivel3');
            $query->where('nivel1', '=', $fathers->nivel1);
            $query->where('nivel2', '!=', '0');
            $query->where('nivel3', '=', '0');
            $query->where('nivel4', '=', '0');
            $query->orderBy('nivel2', 'asc');
            $object->childrens = $query->get();

            $data[] = $object;
        }
        return $data;
    }
}
