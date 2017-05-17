@extends('reportes.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			

			<tr>
				<th width="20px" align="left">Nit</th>
				<th width="60px" align="left">Nombre Tercero</th>
				<th width="20px" align="left">Cheque</th>
				<th width="20px" align="left">Banco</th>
				<th width="20px" align="left">Fecha Registrado</th>
				<th width="20px" align="left">Fecha Cheque</th>
				<th width="20px" align="center">Girador</th>
				<th width="20px" align="center">Valor</th>
				<th width="25px" align="center">Central de Riesgo</th>
				<th width="20px" align="center">Numero Sistema</th>
				<th width="20px" align="center">Sucursal</th>
				<th width="60px" align="left">Observaciones</th> 
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