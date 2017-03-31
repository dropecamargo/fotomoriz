@extends('reportes.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="13" align="center">Mes: {{ $mes}}  Año: {{ $ano }} </th>
			</tr>

			<tr>
				<th width="20px" align="left">Referencia</th>
				<th width="80px" align="left">Producto</th>
				<th width="20px" align="center">mes1</th>
				<th width="20px" align="center">mes2</th>
				<th width="20px" align="center">mes3</th>
				<th width="20px" align="center">MES4</th>
				<th width="20px" align="center">Remisiones</th>
				<th width="20px" align="center">Ajustes</th>
				<th></th>
				<th width="20px" align="center">Traslados</th>
				<th width="20px" align="center">Facturas</th>
				<th width="20px" align="center">Remisiones</th>
				<th width="20px" align="center">Ajustes</th>
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
			<tr>
				<td align="left">{{ $item->referencia }}</td>
				<td align="left">{{ $item->producto_nombre }}</td>
				<td align="rigth">{{ $item->entrada_entrada }}</td>
				<td align="rigth">{{ $item->traslado_entrada }}</td>
				<td align="rigth">{{ $item->facturas_entrada }}</td>
				<td align="rigth">{{ $item->devoluciones_entrada }}</td>
				<td align="rigth">{{ $item->remisiones_entrada }}</td>
				<td align="rigth">{{ $item->ajustes_entrada }}</td>
				<td></td>
				<td align="rigth">{{ $item->traslado_salida }}</td>
				<td align="rigth">{{ $item->facturas_salida }}</td>
				<td align="rigth">{{ $item->remisiones_salida }}</td>
				<td align="rigth">{{ $item->ajustes_salida }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@stop