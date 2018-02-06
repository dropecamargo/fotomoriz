<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use DB, Validator;

class Intereses1 extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'intereses1';

    public $timestamps = false;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['intereses1_tasa', 'intereses1_dias_gracia', 'intereses1_fecha', 'intereses1_observaciones', 'intereses1_fecha_corte', 'intereses1_iva_porcentaje', 'intereses1_iva_valor'];

    /**
     * The attributes that are mass nullable fields to null.
     *
     * @var array
     */
    protected $nullable = ['intereses1_fecha_corte', 'intereses1_usuario_anulo', 'intereses1_fecha_anulo', 'intereses1_hora_anulo', 'intereses1_fecha_cierre'];

    public function isValid($data)
    {
        $rules = [
            'intereses1_tasa' => 'required|max:4',
            'intereses1_dias_gracia' => 'required|numeric',
            'intereses1_fecha' => 'required|date_format:Y-m-d'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }

    public static function validarExiste( $tercero, $doc, $num, $suc, $cuo ){
        $query = Intereses1::query();
        $query->select(DB::raw("SUM(intereses2_dias_a_cobrar) as intereses2_dias_a_cobrar"), 'intereses2_doc_origen', 'intereses2_num_origen', 'intereses2_suc_origen', 'intereses2_cuo_origen', DB::raw("MAX('intereses1_fecha_corte') as intereses1_fecha_corte") ,DB::raw("MAX(intereses1_fecha_cierre) as intereses1_fecha_cierre"));
        $query->join('intereses2', function ($join){
            $join->on('intereses1_numero', '=', 'intereses2_numero');
            $join->on('intereses1_sucursal', '=', 'intereses2_sucursal');
        });
        $query->where('intereses1_tercero', $tercero);
        $query->where('intereses1_anulado', false);
        $query->where('intereses2_doc_origen', $doc);
        $query->where('intereses2_num_origen', $num);
        $query->where('intereses2_suc_origen', $suc);
        $query->where('intereses2_cuo_origen', $cuo);
        $query->groupBy('intereses2_doc_origen', 'intereses2_num_origen', 'intereses2_suc_origen', 'intereses2_cuo_origen');
        return $query->first();
    }
}
