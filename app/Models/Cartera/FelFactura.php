<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;

class FelFactura extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'felpgsql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fel_factura';

    protected $primaryKey = 'Id';

    public $timestamps = false;
}
