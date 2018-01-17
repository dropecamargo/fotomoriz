<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;

class FelFactura extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fel_factura';

    public $incrementing = false;

    public $timestamps = false;

    public static function insertFelFactura( $factura ){
        $fecha = "$factura->factura1_fecha_elaboro $factura->factura1_hora_elaboro";
        $totalfactura = $factura->baseimporte + $factura->factura1_iva;

        $felfactura = new FelFactura;
        $felfactura->tokenempresa = 'b47c0281057e51';
        $felfactura->idtablaorigen = $factura->factura1_numero;
        $felfactura->tipodocumento = '01';
        $felfactura->prefijo = $factura->factura1_prefijo;
        $felfactura->consecutivo = $factura->factura1_numero;
        $felfactura->fechafacturacion = $fecha;
        $felfactura->ordencompra = '';
        $felfactura->moneda = 'COP';
        $felfactura->totalimportebruto = $factura->factura1_bruto;
        $felfactura->totalbaseimponible = $factura->baseimporte;
        $felfactura->totalfactura = $totalfactura;
        $felfactura->mediopago = '10';
        $felfactura->descripcion = substr($factura->factura1_observaciones, 0, 240);
        $felfactura->incoterm = '';
        $felfactura->consecutivofacturamodificada = 0;
        $felfactura->cufefacturamodificada = '';
        $felfactura->fechafacturamodificada = $fecha;
        $felfactura->tipopersona = $factura->tercero_persona == 'J' ? '1' : '2';
        $felfactura->razonsocial = $factura->tercero_razon_social;
        $felfactura->primernombre = $factura->tercero_nombre1;
        $felfactura->segundonombre = $factura->tercero_nombre2;
        $felfactura->primerapellido = $factura->tercero_apellido1;
        $felfactura->segundoapellido = $factura->tercero_apellido2;
        if( $factura->tercero_tipodocumento == 'CC'){
            $felfactura->tipoidentificacion =  13;
        }else if( $factura->tercero_tipodocumento == 'NI'){
            $felfactura->tipoidentificacion =  31;
        }
        $felfactura->numeroidentificacion = $factura->tercero_nit;
        $felfactura->regimen = $factura->tercero_regimen == '1' ? 0 : 2;
        $felfactura->email = empty($factura->tercero_email) ? '' : $factura->tercero_email;
        $felfactura->pais = 'CO';
        $felfactura->departamento = $factura->municipio_nombre;
        $felfactura->ciudad = $factura->departamento_nombre;
        $felfactura->bariolocalidad = '';
        $felfactura->direccion = $factura->tercero_direccion;
        $felfactura->telefono = $factura->tercero_telefono;
        $felfactura->aplicafel = 'NO';
        $felfactura->cufe = '';
        $felfactura->estadoactual = '';
        $felfactura->fecharespuesta = $fecha;
        $felfactura->tokenpassword = 'b47c0281057e51';
        $felfactura->totaldescuentos = $factura->totaldescuentos;
        $felfactura->save();

        $id = $felfactura->getConnection()->getPdo()->lastInsertId();
        return $id;
    }
}
