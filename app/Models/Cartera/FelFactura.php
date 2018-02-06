<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;

class FelFactura extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fel_factura';

    public $incrementing = false;

    public $timestamps = false;
}
