<?php

namespace App\Models\Receivable;

use Illuminate\Database\Eloquent\Model;

class FelProducto extends Model
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
    protected $table = 'fel_productos';

    protected $primaryKey = 'Id';

    public $timestamps = false;
}
