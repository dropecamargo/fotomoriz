@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="5%" align="left">No.</th>
				<th width="15%" align="left">Nit</th>
				<th width="50%" align="left">Nombre Tercero</th>
				<th width="15%" align="left">Valor</th>
				<th width="15%" align="left">Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach( $intereses as $interes )
				@php
					$iva = $interes->valoriva * ($empresa->empresa_iva/100);
					$total = $iva + $interes->intereses;
				@endphp
				<tr class="{{ $interes->intereses1_anulado ? 'line-red' : '' }}">
					<td>{{ $interes->intereses1_numero }}</td>
					<td>{{ $interes->intereses1_tercero }}</td>
					<td>{{ $interes->tercero_nombre }}</td>
					<td class="right">{{ number_format($interes->intereses, 2, ',', '.') }}</td>
					<td class="right">{{ number_format($total, 2, ',', '.') }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
