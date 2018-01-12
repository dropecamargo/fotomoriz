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
    protected $table = 'fel_encabezadofactura';

    public $timestamps = false;

    public $incrementing = false;

    public static function insertFelFactura( $factura ){
        $fecha = "$factura->factura1_fecha_elaboro $factura->factura1_hora_elaboro";
        $baseimporte = $factura->factura1_bruto - $factura->factura1_descuento;
        $totalfactura = $baseimporte + $factura->factura1_iva;

        $felfactura = new FelFactura;
        $felfactura->numero = $factura->factura1_numero;
        $felfactura->sucursal = $factura->factura1_sucursal;
        $felfactura->tokenempresa = 'b47c0281057e51e5868494fe16a06acdf9b74335';
        $felfactura->idtablaorigen = $factura->factura1_numero;
        $felfactura->tipodocumento = '01';
        $felfactura->prefijo = $factura->factura1_prefijo;
        $felfactura->consecutivo = $factura->factura1_numero;
        $felfactura->fechafacturacion = $fecha;
        $felfactura->ordencompra = '';
        $felfactura->moneda = 'COP';
        $felfactura->totalimportebruto = $factura->factura1_bruto;
        $felfactura->totalbaseimponible = $baseimporte;
        $felfactura->totalfactura = $totalfactura;
        $felfactura->mediopago = '10';
        $felfactura->descripcion = $factura->factura1_observaciones;
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
        $felfactura->email = $factura->tercero_email;
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
        $felfactura->save();
    }
}
