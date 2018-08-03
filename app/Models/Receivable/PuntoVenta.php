<?php

namespace App\Models\Receivable;

use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'puntoventa';

    public $timestamps = false;

    public $incrementing = false;
}
