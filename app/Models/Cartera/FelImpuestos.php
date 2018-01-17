<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;

class FelImpuestos extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fel_impuestosgenerales';

    public $timestamps = false;

    public $incrementing = false;
}
