<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use DB;

class CierreCartera extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cierrecartera';

    public $timestamps = false;

    public static function getResumenCarterta($mes, $ano, $tercero)
    {
        // Cierrecartera_s
        $query = CierreCartera::select(DB::raw("
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 360) THEN (cierrecartera_saldo) ELSE 0 END) AS valor_m360,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 180 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 360) THEN (cierrecartera_saldo) else 0 END) AS valor_m180,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 90 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 180) THEN (cierrecartera_saldo) else 0 END) AS valor_m90,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 60 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 90) THEN (cierrecartera_saldo) else 0 END) AS valor_m60,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 30 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 60) THEN (cierrecartera_saldo) else 0 END) AS valor_m30,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 0 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 30) THEN (cierrecartera_saldo) else 0 END) AS valor_m0,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) <= 0 AND (cierrecartera_fecha_cierre - cierrecartera_vence) >= -30) THEN (cierrecartera_saldo) else 0 END) AS valor_pv_m0,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) < -30) THEN (cierrecartera_saldo) else 0 END) AS valor_pv_m30
        "));
        $query->where('cierrecartera_documentos', '!=', 'ANTIC');
        $query->where('cierrecartera_mes', $mes);
        $query->where('cierrecartera_ano', $ano);
        $query->where('cierrecartera_tercero', $tercero->tercero_nit);
        $cierrecartera_s = $query->get();

        // Cierrecartera_r
        $query = CierreCartera::select(DB::raw("
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 360) THEN (cierrecartera_saldo) ELSE 0 END) AS valor_m360,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 180 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 360) THEN (cierrecartera_saldo) else 0 END) AS valor_m180,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 90 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 180) THEN (cierrecartera_saldo) else 0 END) AS valor_m90,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 60 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 90) THEN (cierrecartera_saldo) else 0 END) AS valor_m60,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 30 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 60) THEN (cierrecartera_saldo) else 0 END) AS valor_m30,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) > 0 AND (cierrecartera_fecha_cierre - cierrecartera_vence) <= 30) THEN (cierrecartera_saldo) else 0 END) AS valor_m0,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) <= 0 AND (cierrecartera_fecha_cierre - cierrecartera_vence) >= -30) THEN (cierrecartera_saldo) else 0 END) AS valor_pv_m0,
            SUM(CASE WHEN ((cierrecartera_fecha_cierre - cierrecartera_vence) < -30) THEN (cierrecartera_saldo) else 0 END) AS valor_pv_m30
        "));
        $query->where('cierrecartera_documentos', 'ANTIC');
        $query->where('cierrecartera_mes', $mes);
        $query->where('cierrecartera_ano', $ano);
        $query->where('cierrecartera_tercero', $tercero->tercero_nit);
        $cierrecartera_r = $query->get();

        $m_360 = ($cierrecartera_s[0]['valor_m360'] - $cierrecartera_r[0]['valor_m360']);
        $m_180 = ($cierrecartera_s[0]['valor_m180'] - $cierrecartera_r[0]['valor_m180']);
        $m_90 = ($cierrecartera_s[0]['valor_m90'] - $cierrecartera_r[0]['valor_m90']);
        $m_60 = ($cierrecartera_s[0]['valor_m60'] - $cierrecartera_r[0]['valor_m60']);
        $m_30 = ($cierrecartera_s[0]['valor_m30'] - $cierrecartera_r[0]['valor_m30']);
        $m_0 = ($cierrecartera_s[0]['valor_m0'] - $cierrecartera_r[0]['valor_m0']);
        $pv_m_0 = ($cierrecartera_s[0]['valor_pv_m0'] - $cierrecartera_r[0]['valor_pv_m0']);
        $pv_m_30 = ($cierrecartera_s[0]['valor_pv_m30'] - $cierrecartera_r[0]['valor_pv_m30']);

        // Validar saldos > 0, Sumar fechas vencidas(1) + porvencer(2) pv_m_0 = (1+2)
        $saldos = $m_0 + $m_30 + $m_60 + $m_90 + $m_180 + $m_360 + $pv_m_0;
        $resumencartera = array('m_360' => $m_360, 'm_180' => $m_180, 'm_90' => $m_90, 'm_60' => $m_60, 'm_30' => $m_30, 'm_0' => $m_0, 'pv_m_0' => $pv_m_0, 'pv_m_30' => $pv_m_30, 't_1+2' => $saldos);

        return $resumencartera;
    }
}
