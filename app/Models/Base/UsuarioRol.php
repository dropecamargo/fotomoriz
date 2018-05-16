<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Validator;

class UsuarioRol extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'framework';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuario_rol';

    public $timestamps = false;

    public $incrementing = false;

    public function isValid($data)
    {
        $rules = [
            'role_id' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }
}
