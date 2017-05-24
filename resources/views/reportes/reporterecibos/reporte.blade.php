@extends('reportes.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			

			<tr>
				<th width="20px" align="left">Numero</th>
				<th width="20px" align="left">Sucursal</th>
				<th width="20px" align="center">Fecha</th>
				<th width="20px" align="left">Cliente</th>
				<th width="60px" align="left">Nombre Cliente</th>
				<th width="20px" align="center">Concepto</th>
				<th width="20px" align="center">Numero</th>
				<th width="20px" align="center">Sucursal</th>
				<th width="20px" align="center">Cuota</th>
				<th width="20px" align="center">Naturaleza</th>
				<th width="20px" align="center">Debito</th>
				<th width="20px" align="center">Credito</th>
			</tr>
		</thead>
		<tbody>
			@foreach($recibos as $item)
			<tr>
				<td align="left">{{  $item->recibo1_numero }}</td>	
				<td align="rigth">{{ $item->sucursal_nombre }}</td>
				<td align="rigth">{{ $item->recibo1_fecha }}</td>
				<td align="left">{{  $item->recibo1_tercero }}</td>				
				@if( $item->t_rz =='')
					<td align="rigth">{{ $item->t_n1 }} {{ $item->t_n2 }} {{ $item->t_ap1 }} {{ $item->t_ap2 }}</td>
				@else
					<td align="rigth">{{ $item->t_rz }}</td>
				@endif
				<td align="rigth">{{ $item->conceptosrc_nombre }}</td>
				<td align="left">{{  $item->recibo2_numero_doc }}</td>	
				<td align="left">{{  $item->recibo2_sucursal_doc }}</td>	
				<td align="left">{{  $item->recibo2_cuota_doc }}</td>	
				<td align="left">{{  $item->recibo2_naturaleza }}</td>	
				@if($item->recibo2_naturaleza=='D')
					<td align="left">{{  $item->recibo2_valor }}</td>	
					<td align="left">{{  0 }}</td>	
				@else
					<td align="left">{{  0 }}</td>	
					<td align="left">{{  $item->recibo2_valor }}</td>	
				@endif
				
			</tr>
			
			
			@endforeach
		</tbody>
	</table>
@stop	