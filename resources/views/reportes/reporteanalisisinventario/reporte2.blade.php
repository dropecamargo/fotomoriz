@extends('reportes.layout', ['type' => $type, 'title' => $title])

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
				<th colspan="2" align="center">TRANSITO</th>
			</tr>
			
			<tr>
				
				<th width="60px" align="left">Linea</th>
				<th width="20px" align="left">Referencia</th>
				<th width="80px" align="left">Producto</th>
				@if($xmeses==4)
					<th width="20px" align="left">{{ $nmes1 }}</th>
					<th width="20px" align="left">{{ $nmes2 }}</th>
					<th width="20px" align="left">{{ $nmes3 }}</th>
					<th width="20px" align="left">{{ $nmes4 }}</th>
				@else
					<th width="20px" align="left">{{ $nmes0 }}</th>
					<th width="20px" align="left">{{ $nmes1 }}</th>
					<th width="20px" align="left">{{ $nmes2 }}</th>
					<th width="20px" align="left">{{ $nmes3 }}</th>
				@endif
				<th width="20px" align="left">Promedio</th>
				<th width="20px" align="left">{{ $nmes1 }}</th>
				<th width="20px" align="left">{{ $nmes2 }}</th>
				<th width="20px" align="left">{{ $nmes3 }}</th>
				<th width="20px" align="left">{{ $nmes4 }}</th>
				<th width="20px" align="left"></th>
				<th width="20px" align="left">{{ $nmes1 }}</th>
				<th width="20px" align="left">{{ $nmes2 }}</th>
				<th width="20px" align="left">{{ $nmes3 }}</th>
				<th width="20px" align="left">{{ $nmes4 }}</th>
				<th width="20px" align="left"></th>
				<th width="20px" align="left">Importaciones</th>
				<th width="20px" align="left">Nacionales</th>
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
			<tr>
			    <td align="rigth">{{ $item->linea }}</td>
				<td align="left">{{ $item->referencia }}</td>
				<td align="left">{{ $item->nombre }}</td>
				
				<td align="rigth">{{ $item->unidad1 }}</td>
				<td align="rigth">{{ $item->unidad2 }}</td>
				<td align="rigth">{{ $item->unidad3 }}</td>
				<td align="rigth">{{ $item->unidad4 }}</td>
				{{--*/  $promedio=($item->unidad1+$item->unidad2+$item->unidad3+$item->unidad4)/4 ;  /*--}}
				<th align="rigth">{{ $promedio }}</th>
				<td align="rigth">{{ $item->unidad5 }}</td>
				<td align="rigth">{{ $item->unidad6 }}</td>
				<td align="rigth">{{ $item->unidad7 }}</td>
				<td align="rigth">{{ $item->unidad8 }}</td>
				<td align="rigth"></td>
				@if($promedio != 0)
					<td align="rigth">{{ $item->unidad5/$promedio }}</td>
					<td align="rigth">{{ $item->unidad6/$promedio }}</td>
					<td align="rigth">{{ $item->unidad7/$promedio }}</td>
					<td align="rigth">{{ $item->unidad8/$promedio }}</td>
				@else
					<td align="rigth">0</td>
					<td align="rigth">0</td>
					<td align="rigth">0</td>
					<td align="rigth">0</td>
				@endif
				<td align="rigth"></td>
				<td align="rigth">{{ $item->unidad9 }}</td>
				<td align="rigth">{{ $item->unidad10 }}</td>
				
			</tr>
			@endforeach
		</tbody>
	</table>
@stop