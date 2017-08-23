@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="13" align="center">Fecha inicio: {{ $fecha_inicio }}  Fecha final: {{ $fecha_final }} </th>
			</tr>

			<tr>
				<th width="20px" align="left">Nit</th>
				<th width="60px" align="left">Nombre Tercero</th>
				<th width="20px" align="left">Fecha</th>
				<th width="20px" align="center">Hora</th>
				<th width="20px" align="center">Concepto</th>
				<th width="20px" align="center">Nombre Concepto</th>
				<th width="20px" align="left">Proxima Llamada</th>
				<th width="20px" align="center">T. Interno</th>
				<th width="60px" align="left">Nombre</th>
			</tr>
		</thead>
		<tbody>
			@foreach($llamadas_p as $item)
			<tr>
				<td align="left">{{  $item->llamadacob_tercero }}</td>
				@if( $item->t_rz =='')
					<td align="rigth">{{ $item->t_n1 }} {{ $item->t_n2 }} {{ $item->t_ap1 }} {{ $item->t_ap2 }}</td>
				@else
					<td align="rigth">{{ $item->t_rz }}</td>
				@endif
				<td align="left">{{  $item->llamadacob_fecha }}</td>
				<td align="rigth">{{ $item->llamadacob_hora }}</td>
				<td align="rigth">{{ $item->llamadacob_conceptocob }}</td>
				<td align="rigth">{{ $item->conceptocob_nombre }}</td>
				<td align="rigth">{{ $item->llamadacob_prox_fecha  }} {{ $item->llamadacob_prox_hora }}</td>
				<td align="rigth">{{ $item->llamadacob_tercerointerno }}</td>
				<td align="rigth">{{ $item->ti_n1 }} {{ $item->ti_n2 }} {{ $item->ti_ap1 }} {{ $item->ti_ap2 }}</td>
			</tr>


			@endforeach
		</tbody>
	</table>
@stop
