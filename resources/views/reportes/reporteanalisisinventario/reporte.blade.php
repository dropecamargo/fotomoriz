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
				<th colspan="1" align="center">TRANSITO</th>
			</tr>
			
			
			<tr>
		    
				
				<th width="60px" align="left">Linea</th>
				<th width="20px" align="left">Referencia</th>
				<th width="80px" align="left">Producto</th>
				<th width="20px" align="left">{{ $nmes1 }}</th>
				<th width="20px" align="left">{{ $nmes2 }}</th>
				<th width="20px" align="left">{{ $nmes3 }}</th>
				<th width="20px" align="left">{{ $nmes4 }}</th>
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
				<th width="20px" align="left">USD</th>
				
				
			</tr>
		</thead>
		<tbody>
			@foreach($auxiliar as $item)
			<tr>
			    <td align="rigth">{{ $item->linea }}</td>
				<td align="left">{{ $item->referencia }}</td>
				<td align="left">{{ $item->nombre }}</td>
				
				<td align="rigth">{{ $item->costo1 }}</td>
				<td align="rigth">{{ $item->costo2 }}</td>
				<td align="rigth">{{ $item->costo3 }}</td>
				<td align="rigth">{{ $item->costo4 }}</td>
				<th align="rigth">{{ ($item->costo1+$item->costo2+$item->costo3+$item->costo4)/4 }}</th>
				<td align="rigth">{{ $item->costo5 }}</td>
				<td align="rigth">{{ $item->costo6 }}</td>
				<td align="rigth">{{ $item->costo7 }}</td>
				<td align="rigth">{{ $item->costo8 }}</td>
				<td align="rigth"></td>
				@if($item->costo5 != 0)
					<td align="rigth">{{ $item->costo1/$item->costo5 }}</td>
				@else
					<td align="rigth">0</td>
				@endif
				@if($item->costo6 != 0)
					<td align="rigth">{{ $item->costo2/$item->costo6 }}</td>
			    @else
					<td align="rigth">0</td>
				@endif
				@if($item->costo7 != 0)
					<td align="rigth">{{ $item->costo3/$item->costo7 }}</td>
				@else
					<td align="rigth">0</td>
				@endif
				@if($item->costo8 != 0)
					<td align="rigth">{{ $item->costo4/$item->costo8 }}</td>
				@else
					<td align="rigth">0</td>
				@endif
				<td align="rigth"></td>
				<td align="rigth">{{ $item->costo9 }}</td>
				
			</tr>
			@endforeach
		</tbody>
	</table>
@stop