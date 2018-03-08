@extends('reports.layout', ['type' => 'pdf', 'title' => $title])

@section('content')
	<table class="itable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th align="center" width="5%">CUOTA</th>
				<th align="center">FECHA</th>
                <th align="center">TASA</th>
				<th align="center">AMORTIZACIÓN</th>
				<th align="center">FINANCIACIÓN</th>
				<th align="center">SEGUROS</th>
				<th align="center">TOTAL</th>
				<th align="center" width="15%">SALDO</th>
			</tr>
		</thead>
		<tbody>
			@foreach( $data as $item )
				<tr>
					<td class="left size-7">{{ $item->cuota }}</td>
					<td class="center size-7">{{$item->date}}</td>
                    <td class="right size-7">{{ $item->tasa }}</td>
					<td class="right size-7">{{ number_format($item->amortizacion, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($item->financiacion, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($item->seguro, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($item->total, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($item->saldo, 2, ',', '.') }}</td>
					{{--<td class="right size-7">{{ number_format($item->intereses2_saldo, 2, ',', '.') }}</td>
					<td class="right size-7">{{ number_format($item->intereses2_interes, 2, ',', '.') }}</td>--}}
				</tr>
			@endforeach
		</tbody>
	</table>
@stop
