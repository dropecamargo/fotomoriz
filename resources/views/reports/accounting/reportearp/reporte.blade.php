@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="11" align="center">Mes: {{ $mes}}  Año: {{ $ano }} </th>
			</tr>

			<tr>
				<th colspan="1" align="center"></th>
				<th colspan="1" align="center"></th>
				<th colspan="1" align="center"></th>
				<th colspan="4" align="center">VENTAS</th>
				<th colspan="1" align="center"></th>
				<th colspan="4" align="center">EXISTENCIAS</th>
				<th colspan="1" align="center"></th>
				<th colspan="4" align="center">ROTACION</th>
				<th colspan="1" align="center"></th>
				<th colspan="1" align="center">TRANSITO</th>
			</tr>

			<tr>
		    	<th width="60px" align="left">Gastos</th>
				<th width="15px" align="left">ARP</th>
				<th width="15px" align="left">Real</th>
				<th width="15px" align="left">Var</th>
				<th width="15px" align="left"></th>
				<th width="15px" align="left">ARP</th>
				<th width="15px" align="left">Real</th>
				<th width="15px" align="left">Var</th>
				<th width="15px" align="left">%</th>
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
