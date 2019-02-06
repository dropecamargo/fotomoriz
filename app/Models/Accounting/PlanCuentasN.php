<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Validator;

class PlanCuentasN extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'plancuentasn';

    // Declarar llave primaria
    protected $primaryKey = 'plancuentasn_cuenta';

    // No busque los camposr _at
    public $timestamps = false;

    // No incremente el id que no existe
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plancuentasn_concepto'];

    public function isValid($data)
    {
        $rules = [
            'plancuentasn_concepto' => 'max:25|required'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }
}
