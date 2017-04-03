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
			</tr>
			
			<tr>
				
				<th width="60px" align="left">Linea</th>
				<th width="20px" align="left">Referencia</th>
				<th width="80px" align="left">Producto</th>
				<th width="20px" align="left">{{ $nmes1 }}</th>
				<th width="20px" align="left">{{ $nmes2 }}</th>
				<th width="20px" align="left">{{ $nmes3 }}</th>
				<th width="20px" align="left">{{ $nmes4 }}</th>
				<th width="5px" align="left"></th>
				<th width="20px" align="left">{{ $nmes1 }}</th>
				<th width="20px" align="left">{{ $nmes2 }}</th>
				<th width="20px" align="left">{{ $nmes3 }}</th>
				<th width="20px" align="left">{{ $nmes4 }}</th>
				
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
				<td align="rigth">{{ $item->unidad5 }}</td>
				<td align="rigth">{{ $item->unidad6 }}</td>
				<td align="rigth">{{ $item->unidad7 }}</td>
				<td align="rigth">{{ $item->unidad8 }}</td>
				
			</tr>
			@endforeach
		</tbody>
	</table>
@stop