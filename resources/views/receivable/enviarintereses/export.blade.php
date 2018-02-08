@extends('reports.layout', ['type' => 'pdf', 'title' => $title])

@section('content')
	<table class="tbtitle">
		<thead>
			<tr><td class="size-8 center bold">{{ $interes->tercero_nombre }}</td></tr>
			<tr><td class="size-8 center bold">NIT: {{ $interes->tercero_nit }}</td></tr>
		</thead>
	</table>

	<table border="0" width="100%">
		<thead>
			<tr>
				<th class="left" width="15%">N° Interes:</th>
				<td width="85%">{{ $interes->intereses1_numero }}</td>
			</tr>
			<tr>
				<th class="left" width="15%">Fecha:</th>
				<td width="85%">{{ $interes->intereses1_fecha }}</td>
			</tr>
			<tr>
				<th class="left" width="15%">Tasa:</th>
				<th class="left" width="85%">Por concepto de intereses de mora al {{ $interes->intereses1_tasa }}% mensual con corte al {{ $interes->intereses1_fecha_cierre }}</th>
			</tr>
		</thead>
	</table>

	<table class="itable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th align="center" width="15%">DOCUMENTO</th>
				<th align="center">NUMERO</th>
				<th align="center">CUOTA</th>
				<th align="center">EXPEDICION</th>
				<th align="center">VENCIMIENTO</th>
				<th align="center">D/MORA</th>
				<th align="center">COBRADOS</th>
				<th align="center">A COBRAR</th>
				<th align="center" width="15%">SALDO</th>
				<th align="center" width="15%">INTERESES</th>
			</tr>
		</thead>
		<tbody>
			{{--*/ $cobrados = $subtotal = $base = $v_factura = $v_iva = $total = 0; /*--}}
			@foreach( $detalle as $interes2 )
				{{--*/
                    $cobrados = abs($interes2->intereses2_dias_a_cobrar) - abs($interes2->intereses2_dias_mora);
                    $subtotal += $interes2->intereses2_saldo;
                    $base += $interes2->intereses2_interes;

                    if($interes2->intereses2_doc_origen == 'FACTU'){
                        $v_factura += ((($interes2->intereses2_saldo - $interes2->factura1_iva) * ($interes->intereses1_tasa/100) )/30) * $interes2->intereses2_dias_a_cobrar;
                    }else{
                        $v_factura += $interes2->intereses2_interes;
                    }
                /*--}}

				<tr>
					<td class="left size-7">{{ $interes2->documentos_nombre }}</td>
					<td class="right size-7">{{ $interes2->intereses2_num_origen }}</td>
					<td class="center size-7">{{ $interes2->intereses2_cuo_origen }}</td>
					<td class="center size-7">{{ $interes2->intereses2_expedicion }}</td>
					<td class="center size-7">{{ $interes2->intereses2_vencimiento }}</td>
					<td class="center size-7">{{ $interes2->intereses2_dias_mora }}</td>
					<td class="center size-7">{{ abs($cobrados) }}</td>
					<td class="center size-7">{{ $interes2->intereses2_dias_a_cobrar }}</td>
					<td class="right size-7">{{ number_format($interes2->intereses2_saldo, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($interes2->intereses2_interes, 2, ',', '.') }}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
            {{--*/
                $v_iva = $v_factura * ( $empresa->empresa_iva / 100 );
                $total = $v_iva + $base;
            /*--}}
			<tr>
				<td class="bold right" colspan="8">SUBTOTAL</td>
				<td class="bold right">{{ number_format($subtotal, 2, ',', '.')}}</td>
				<td class="bold right">{{ number_format($base, 2, ',', '.')}}</td>
			</tr>
			<tr>
				<td class="bold right" colspan="8">IVA</td>
				<td class="bold right"><small>(Base Iva {{ number_format($v_factura, 2, ',', '.') }})</small></td>
				<td class="bold right">{{ number_format($v_iva, 2, ',', '.')}}</td>
			</tr>
			<tr>
				<td class="bold right" colspan="8">TOTAL</td>
				<td colspan="2" class="bold right">{{ number_format($total, 2, ',', '.') }}</td>
			</tr>
		</tfoot>
	</table>
	<br>
	<div>
		<p><b>SON: {{ \NumeroALetras::convertir( intval($total), 'PESOS' ) }}</b></p>
	</div>

	<div class="footer">
		<p><b>Atentamente, </b></p><br><br><br>
		<p><b>WILLIAM NIEVES CLAVIJO <br> JEFE NACIONAL DE CARTERA	</b></p>
	</div>
@stop
