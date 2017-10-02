@extends('receivable.extractos.layout', ['type' => $type, 'title' => $title])

@section('content')

	<table class="subtbtitle">
		<thead>
			<tr><td class="cliente">{{ $tercero->tercero_nombre }}</td></tr>
			<tr><td class="nit_cliente">NIT: {{ $tercero->tercero_nit }}</td></tr>
		</thead>
	</table>

	<table class="htable" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th width="10%" class="left">Dirección: </th>
			<td width="90%" class="left">{{ $tercero->tercero_direccion }}</td>
		</tr>
		<tr>
			<th width="10%" class="left">Telefono: </th>
			<td width="90%" class="left">{{ $tercero->tercero_telefono }} {{ empty($tercero->tercero_telefono2) ? ' ' : ' - '.$tercero->tercero_telefono2 }}</td>
		</tr>
		<tr>
			<th width="10%" class="left">Plazo: </th>
			<td width="90%" class="left">{{ $tercero->tercero_plazo_cartera ? $tercero->tercero_plazo_cartera : '0'}} Dia(s)</td>
		</tr>
	</table>

	<table class="rtable" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="2" align="center">RESUMEN DE CARTERA POR EDADES</th>
			</tr>
			<tr>
				<th colspan="2" align="left">CARTERA VENCIDA</th>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 1 A 30</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_0'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 31 A 60</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_30'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 61 A 90</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_60'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 91 A 180</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_90'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 181 A 360</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_180'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; MAS DE 360</td>
				<td class="right">$ {{ number_format($datos->resumencartera['m_360'], 2, ',', '.') }}</td>
			</tr>

			<tr>
				<th align="left"> SUBTOTAL CARTERA VENCIDA(1)</th>
				{{--*/ $sbvencida = $sbvencer = $totalcartera = 0; /*--}}
				{{--*/ $sbvencida = $datos->resumencartera['m_0'] + $datos->resumencartera['m_30'] + $datos->resumencartera['m_60'] + $datos->resumencartera['m_90'] + $datos->resumencartera['m_180'] + $datos->resumencartera['m_360']; /*--}}
				<th class="right">$ {{ number_format($sbvencida, 2, ',', '.') }}</th>
			</tr>
			<tr>
				<th colspan="2" align="left"> CARTERA POR VENCER</th>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DE 1 A 30(2)</td>
				<td class="right">$ {{ number_format($datos->resumencartera['pv_m_0'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; MAS DE 30</td>
				<td class="right">$ {{ number_format($datos->resumencartera['pv_m_30'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<th align="left"> SUBTOTAL CARTERA POR VENCER</th>
				{{--*/ $sbvencer = $datos->resumencartera['pv_m_0'] + $datos->resumencartera['pv_m_30']; /*--}}
				<th class="right">$ {{ number_format($sbvencer, 2, ',', '.') }}</th>
			</tr>
			<tr>
				<th align="left"> TOTAL CARTERA</th>
				{{--*/ $totalcartera = $sbvencida + $sbvencer; /*--}}
				<th class="right">$ {{ number_format($totalcartera, 2, ',', '.') }}</th>
			</tr>
		</thead>
	</table><br><br>

	<table class="rtablemid" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="70%" align="left">SU PAGO PARA EL MES DE {{ $fechas->nombre_mes_siguiente }}/{{ $fechas->ano_siguiente }} DEBE SER DE (1+2):</th>
				<th width="30%" class="right">$ {{ number_format($datos->resumencartera['t_1+2'], 2, ',', '.') }}</th>
			</tr>
		</thead>
	</table><br><br>

	<table class="rtable" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="2" align="center">MOVIMIENTOS A {{ $fechas->nombre_mes_escogido }} DEL {{ $fechas->ano_actual }}</th>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; COMPRAS MES</td>
				<td class="right">$ {{ number_format($datos->compras['facturacion'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; N FACTURAS REALIZADAS</td>
				<td class="right">{{ $datos->compras['numerofacturas'] }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; DEVOLUCIONES MES</td>
				<td class="right">$ {{ number_format($datos->devoluciones['devolucion'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; NOTAS MES</td>
				<td class="right">$ {{ number_format($datos->notas['nota'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; PAGOS MES</td>
				<td class="right">$ {{ number_format($datos->pagos['recibo'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; ANTICIPOS MES</td>
				<td class="right">$ {{ number_format($datos->anticipos['anticipo'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; CHEQUES DEVUELTOS MES</td>
				<td class="right">$ {{ number_format($datos->cheques['chdevuelto'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; CONSIGNACIONES M/CIA (Vr/Aprox)</td>
				<td class="right">$ {{ number_format($datos->consignaciones->consignacionmes, 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; CONSIGNACIONES M/CIA ACUMULADO (Vr/Aprox)</td>
				<td class="right">$ {{ number_format($datos->consignaciones->consignacion, 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; FACTORINGS</td>
				<td class="right">$ {{ number_format($datos->factoring['factoring'], 2, ',', '.') }}</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; PAGARES</td>
				<td class="right">$ {{ number_format($datos->pagares['pagare'], 2, ',', '.') }}</td>
			</tr>
		</thead>
	</table><br><br>

	<table class="rtable" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td colspan="2" align="left">&nbsp; Para resolver cualquier inquietud o si desea en detalle la informacin suministrada favor conectarse al Departamento de Cartera.</td>
			</tr>
		</thead>
	</table><br><br><br>

	<table class="foottable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td colspan="2" align="left">&nbsp; Atentamente,</td>
			</tr>
		</thead>
	</table><br><br><br><br>

	<table class="foottable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td colspan="2" align="left">&nbsp; Departamento de Cartera - {{ $datos->empresa->empresa_nombre }}</td>
			</tr>
			<tr>
				<td colspan="2" align="left">&nbsp; Tel 3276868 ext 205, 245</td>
			</tr>
		</thead>
	</table>
@stop
