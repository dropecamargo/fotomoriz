<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;

class FelProducto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fel_productos';

    public $timestamps = false;

    public $incrementing = false;
}
