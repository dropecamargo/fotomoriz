<?php

namespace App\Models\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Validator, DB;

class Tercero extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'koi_tercero';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tercero_nit', 'tercero_digito', 'tercero_tipo', 'tercero_regimen', 'tercero_persona', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_razonsocial', 'tercero_direccion', 'tercero_municipio', 'tercero_direccion', 'tercero_email', 'tercero_representante', 'tercero_cc_representante', 'tercero_telefono1', 'tercero_telefono2', 'tercero_fax', 'tercero_celular', 'tercero_actividad', 'tercero_cual', 'username', 'password'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function isValid($data)
    {
        $rules = [
            'tercero_nit' => 'required|max:15|min:1|unique:koi_tercero',
            'tercero_digito' => 'required',
            'tercero_tipo' => 'required',
            'tercero_regimen' => 'required',
            'tercero_persona' => 'required',
            'tercero_direccion' => 'required',
            'tercero_municipio' => 'required',
            'tercero_actividad' => 'required'
        ];

        if ($this->exists){
            $rules['tercero_nit'] .= ',tercero_nit,' . $this->id;
        }else{
            $rules['tercero_nit'] .= '|required';
        }

        $validator = Validator::make($data, $rules);
        if ($validator->passes())
        {
            if($data['tercero_persona'] == 'N') {
                if(empty($data['tercero_nombre1'])) {
                    $this->errors = trans('validation.required', ['attribute' => '1er. Nombre']);
                    return false;
                }
                if(empty($data['tercero_apellido1'])) {
                    $this->errors = trans('validation.required', ['attribute' => '1er. Apellido']);
                    return false;
                }
            }else{
                if(empty($data['tercero_razonsocial'])) {
                    $this->errors = trans('validation.required', ['attribute' => 'Razón Social, Comercial o Establecimiento']);
                    return false;
                }
            }
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }

    public static function getTercero($id)
    {
        $query = Tercero::query();
        $query->select('koi_tercero.*', 'actividad_nombre', 'actividad_tarifa', DB::raw("CONCAT(municipio_nombre, ' - ', departamento_nombre) as municipio_nombre"));
        $query->leftJoin('koi_actividad', 'tercero_actividad', '=', 'koi_actividad.id');
        $query->leftJoin('koi_municipio', 'tercero_municipio', '=', 'koi_municipio.id');
        $query->leftJoin('koi_departamento', 'koi_municipio.departamento_codigo', '=', 'koi_departamento.departamento_codigo');
        $query->where('koi_tercero.id', $id);
        return $query->first();
    }

    public function getName()
    {
        return $this->attributes['tercero_razonsocial'] ? $this->attributes['tercero_razonsocial'] : sprintf('%s %s %s', $this->attributes['tercero_nombre1'], $this->attributes['tercero_apellido1'], $this->attributes['tercero_apellido2']);
    }

    public function setTerceroNombre1Attribute($name)
    {
        $this->attributes['tercero_nombre1'] = strtoupper($name);
    }

    public function setTerceroNombre2Attribute($name)
    {
        $this->attributes['tercero_nombre2'] = strtoupper($name);
    }

    public function setTerceroApellido1Attribute($lastname)
    {
        $this->attributes['tercero_apellido1'] = strtoupper($lastname);
    }

    public function setTerceroApellido2Attribute($lastname)
    {
        $this->attributes['tercero_apellido2'] = strtoupper($lastname);
    }

    public function setTerceroRazonsocialAttribute($name)
    {
        $this->attributes['tercero_razonsocial'] = strtoupper($name);
    }

    public function setTerceroDireccionAttribute($name)
    {
        $this->attributes['tercero_direccion'] = strtoupper($name);
    }
}