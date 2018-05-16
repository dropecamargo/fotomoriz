@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th align="left">Nit</th>
				<th align="left">Nombre Tercero</th>
				<th align="left">Cheque</th>
				<th align="left">Banco</th>
				<th align="left">Fecha Registrado</th>
				<th align="left">Fecha Cheque</th>
				<th align="center">Girador</th>
				<th align="center">Valor</th>
				<th align="center">Central de Riesgo</th>
				<th align="center">Numero Sistema</th>
				<th align="center">Sucursal</th>
				<th align="left">Observaciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
				<tr>
					<td align="left">{{  $item->tercero }}</td>
					@if( $item->t_rz =='')
						<td align="rigth">{{ $item->t_n1 }} {{ $item->t_n2 }} {{ $item->t_ap1 }} {{ $item->t_ap2 }}</td>
					@else
						<td align="rigth">{{ $item->t_rz }}</td>
					@endif
					<td align="left">{{  $item->numerocheque }}</td>
					<td align="rigth">{{ $item->nombrebanco }}</td>
					<td align="rigth">{{ $item->fecha }}</td>
					<td align="rigth">{{ $item->fechacheque }}</td>
					<td align="rigth">{{ $item->girador }}</td>
					<td align="rigth">{{ $item->valor }}</td>
					<td align="rigth">{{ $item->centralriesgo }}</td>
					<td align="rigth">{{ $item->numero }}</td>
					<td align="rigth">{{ $item->nombresucursal }}</td>
					<td align="rigth">{{ $item->observaciones }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
