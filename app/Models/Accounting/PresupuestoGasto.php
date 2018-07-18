<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class PresupuestoGasto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'presupuestog';

    public $timestamps = false;

    public $incrementing = false;
}
