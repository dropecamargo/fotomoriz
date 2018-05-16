<?php

namespace App\Models\Base;

use Zizaco\Entrust\EntrustPermission;

class Permiso extends EntrustPermission
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'framework';
    
    public $timestamps = false;

    public $incrementing = false;
}
