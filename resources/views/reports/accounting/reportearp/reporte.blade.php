@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="11" align="center">Mes: {{ $mes}}  Año: {{ $ano }} </th>
			</tr>

			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th colspan="4" align="center">VENTAS</th>
				<th></th>
				<th colspan="4" align="center">EXISTENCIAS</th>
				<th></th>
				<th colspan="4" align="center">ROTACION</th>
				<th></th>
				<th align="center">TRANSITO</th>
			</tr>

			<tr>
		    	<th align="left">Gastos</th>
				<th align="left">ARP</th>
				<th align="left">Real</th>
				<th align="left">Var</th>
				<th align="left"></th>
				<th align="left">ARP</th>
				<th align="left">Real</th>
				<th align="left">Var</th>
				<th align="left">%</th>
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
				<tr>
				    <td align="left">{{ $item->cuenta }}</td>
					<td align="rigth">{{ $item->mes }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
