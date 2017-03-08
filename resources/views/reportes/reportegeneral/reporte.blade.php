@extends('reportes.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="20px"></th>
				<th width="20px"></th>
				<th colspan="6" align="center">ENTRADAS</th>
				<th width="20px"></th>
				<th colspan="4" align="center">SALIDAS</th>
			</tr>
			<tr>
				<th width="20px" align="left">Referencia</th>
				<th width="20px" align="left">Producto</th>
				<th width="20px" align="center">Entrada</th>
				<th width="20px" align="center">Traslado</th>
				<th width="20px" align="center">Facturas</th>
				<th width="20px" align="center">Devoluciones</th>
				<th width="20px" align="center">Remisiones</th>
				<th width="20px" align="center">Ajustes</th>
				<th></th>
				<th width="20px" align="center">Traslados</th>
				<th width="20px" align="center">Facturas</th>
				<th width="20px" align="center">Remisiones</th>
				<th width="20px" align="center">Ajustes</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
@stop