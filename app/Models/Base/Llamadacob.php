<?php

namespace App\Models\base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Base\Llamadacob;


class Llamadacob extends Model
{
    protected $table = 'llamadacob';

    public $timestamps = false;

    public $primaryKey = ['llamadacob_tercero', 'llamadacob_fecha', 'llamadacob_hora'];

}
