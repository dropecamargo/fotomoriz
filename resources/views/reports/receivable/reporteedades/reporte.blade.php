@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th align="left">Documento</th>
				<th align="left">Sucursal</th>
				<th align="center">Fecha</th>
				<th align="center">Vencimiento</th>
				<th align="left">Numero</th>
				<th align="left">Cuota</th>
				<th align="left">Cliente</th>
				<th align="left">Nombre Cliente</th>
				<th align="left">Vendedor</th>
				<th align="left">Nombre Vendedor</th>
				<th align="center">Valor</th>
				<th align="center">Saldo</th>
				<th align="center">Dias Cartera</th>
				<th align="center">Mora> 360</th>
				<th align="center">Mora> 180 A 360</th>
				<th align="center">Mora> 90 A 180</th>
				<th align="center">Mora> 60 A 90</th>
				<th align="center">Mora> 30 A 60</th>
				<th align="center">Mora> 0  A 30</th>
				<th align="center">De 0 A 30</th>
				<th align="center">De 31 A 60</th>
				<th align="center">De 61 A 90</th>
				<th align="center">De 91 A 180</th>
				<th align="center">De 181 A 360</th>
				<th align="center">Mayor A 360</th>
				<th align="center">Total Mora</th>
				<th align="center">Total Por Vencer</th>
				<th align="center">Vencer Mes</th>
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
				<tr>
					<td align="left">{{  $item->documento }}</td>
					<td align="rigth">{{ $item->nombresucursal }}</td>
					<td align="rigth">{{ $item->fecha }}</td>
					<td align="rigth">{{ $item->vencimiento }}</td>
					<td align="left">{{  $item->numero }}</td>
					<td align="rigth">{{ $item->cuota }}</td>
					<td align="left">{{  $item->tercero }}</td>
					@if( $item->t_rz =='')
						<td align="rigth">{{ $item->t_n1 }} {{ $item->t_n2 }} {{ $item->t_ap1 }} {{ $item->t_ap2 }}</td>
					@else
						<td align="rigth">{{ $item->t_rz }}</td>
					@endif
					<td align="left">{{  $item->vendedor }}</td>
					<td align="rigth">{{ $item->ti_n1 }} {{ $item->ti_n2 }} {{ $item->ti_ap1 }} {{ $item->ti_ap2 }}</td>
					<td align="rigth">{{ $item->valor }}</td>
					<td align="rigth">{{ $item->saldo }}</td>
					<td align="rigth">{{ $item->dias }}</td>

					@php
						$col1 = $col2 = $col3 = $col4 = $col5 = $col6 = $col7 = $col8 = $col9 = $col10 = $col11 = $col12 = $Tmora = $Tvencer = 0;

						if( $item->dias < 0){
							$item->dias = $item->dias*(-1);
							$Tmora = $item->saldo;

							if( $item->dias > 360)
								$col1=$item->saldo;

							if( $item->dias > 180 && $item->dias <= 360)
								$col2=$item->saldo;

							if( $item->dias > 90 && $item->dias <=180)
								$col3=$item->saldo;

							if( $item->dias > 60 && $item->dias <=90)
								$col4=$item->saldo;

							if( $item->dias > 30 && $item->dias <=60)
								$col5=$item->saldo;

							if( $item->dias > 0 && $item->dias <=30)
								$col6=$item->saldo;
						}else{
							$Tvencer = $item->saldo;
							if( $item->dias >= 0 && $item->dias <=30)
								$col7=$item->saldo;

							if( $item->dias > 30 && $item->dias <=60)
								$col8=$item->saldo;

							if( $item->dias > 60 && $item->dias <=90)
								$col9=$item->saldo;

							if( $item->dias > 90 && $item->dias <=180)
								$col10=$item->saldo;

							if( $item->dias > 180 && $item->dias <= 360)
								$col11=$item->saldo;

							if( $item->dias > 360)
								$col12=$item->saldo;
						}
					@endphp

					<td align="rigth">{{ $col1 }}</td>
					<td align="rigth">{{ $col2 }}</td>
					<td align="rigth">{{ $col3 }}</td>
					<td align="rigth">{{ $col4 }}</td>
					<td align="rigth">{{ $col5 }}</td>
					<td align="rigth">{{ $col6 }}</td>
					<td align="rigth">{{ $col7 }}</td>
					<td align="rigth">{{ $col8 }}</td>
					<td align="rigth">{{ $col9 }}</td>
					<td align="rigth">{{ $col10 }}</td>
					<td align="rigth">{{ $col11 }}</td>
					<td align="rigth">{{ $col12 }}</td>
					<td align="rigth">{{ $Tmora }}</td>
					<td align="rigth">{{ $Tvencer }}</td>
					<td align="rigth">{{ $item->mes }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
