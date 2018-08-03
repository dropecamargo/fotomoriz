<?php

namespace App\Models\Receivable;

use Illuminate\Database\Eloquent\Model;

class FelCamposAdicionales extends Model
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
    protected $table = 'fel_camposadicionales';

    protected $primaryKey = 'Id';

    public $timestamps = false;
}
