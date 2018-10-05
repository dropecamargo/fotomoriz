@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="tbtitle">
		<thead>
			<tr><th class="center">Fecha de {{ $fechai }} hasta {{ $fechaf }} </th></tr>
		</thead>
	</table>

	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr style="font-size: 6;">
				<th style="width: 10%;">FECHA</th>
				<th style="width: 8%;">PREFIJO</th>
				<th style="width: 10%;">CONSECUTIVO</th>
				<th style="width: 40%;">CLIENTE</th>
				<th style="width: 10%;">BASE</th>
				<th style="width: 10%;">IVA</th>
				<th style="width: 12%;">TOTAL</th>
			</tr>
		</thead>
		<tbody>
			@foreach($facturas as $factura)
				<tr>
					<td>{{ date('Y-m-d', strtotime($factura->fecha)) }}</td>
					<td>{{ $factura->prefijo }}</td>
					<td>{{ $factura->consecutivo }}</td>
					<td>{{ $factura->cliente }}</td>
					<td class="right">{{ number_format($factura->base, 2, ',', '.') }}</td>
					<td class="right">{{ number_format($factura->impuesto, 2, ',', '.') }}</td>
					<td class="right">{{ number_format($factura->total, 2, ',', '.') }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
