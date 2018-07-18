@extends('reports.accounting.reportearp.layout', ['type' => $type, 'title' => $title])

@section('content')
	<table class="rtable" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td class="noborder"></td>
			<td align="center" class="bold-cell">UNIDAD DE DECISION:</td>
			<td class="noborder"></td>
			<td colspan="3" align="center">{{ $unidad->unidaddecision_nombre }}</td>
			<td class="noborder"></td>
			<td colspan="4" align="center" class="bold-cell">FECHA:</td>
			<td class="noborder"></td>
			<td colspan="3" align="center"> {{ $mes }} / {{ $ano }} </td>
		</tr>

		<tr>
			<td colspan="17" class="noborder"></td>
		</tr>

		<tr>
			<td class="noborder"></td>
			<td rowspan="2" align="center" class="bold-cell">GASTOS</td>
			<td class="noborder"></td>
			<td colspan="3" align="center" class="bold-cell">MES</td>
			<td class="noborder"></td>
			<td colspan="4" align="center" class="bold-cell">ACUMULADO</td>
			<td class="noborder"></td>
			<td align="center" class="bold-cell">ARP</td>
			<td colspan="2" align="center" class="bold-cell">DISPONIBLE</td>
		</tr>

		<tr>
	    	<td class="noborder"></td>
	    	<td class="noborder"></td>
	    	<td class="noborder"></td>
			<td align="center" class="bold-cell">ARP</td>
			<td align="center" class="color-cell bold-cell">REAL</td>
			<td align="center" class="bold-cell">VAR</td>
			<td class="noborder"></td>
			<td align="center" class="bold-cell">ARP</td>
			<td align="center" class="color-cell bold-cell">REAL</td>
			<td align="center" class="bold-cell">VAR</td>
			<td align="center" class="bold-cell">%</td>
			<td class="noborder"></td>
			<td align="center" class="bold-cell">AÑO</td>
			<td align="center" class="bold-cell">$</td>
			<td align="center" class="bold-cell">%</td>
		</tr>
		@php
			$arpmestotal = $realmestotal = $arpacutotal = $realacutotal = 0;
		@endphp

		@foreach($auxiliar as $item)
			@php
				$varmes = $item->mes - $item->arpmes;
				$varacu = $item->anoacu - $item->arpacu;
				$promedioacu = ($item->anoacu > 0) ? ($item->arpacu/$item->anoacu)-1 : 0;

				$arpmestotal += $item->arpmes;
				$realmestotal += $item->mes;
				$arpacutotal += $item->arpacu;
				$realacutotal += $item->anoacu;
			@endphp
			<tr>
			    <td align="left">{{ $item->codigo }}</td>
			    <td align="left">{{ $item->cuenta }}</td>
				<td class="noborder"></td>
				<td align="center">{{ number_format($item->arpmes,2,',','.') }}</td>
				<td align="center" class="color-cell">{{ number_format($item->mes,2,',','.') }}</td>
				<td align="center">{{ number_format($varmes,2,',','.') }}</td>
				<td class="noborder"></td>
				<td align="center">{{ number_format($item->arpacu,2,',','.') }}</td>
				<td align="center" class="color-cell">{{ number_format($item->anoacu,2,',','.') }}</td>
				<td align="center">{{ number_format($varacu,2,',','.') }}</td>
				<td align="center">{{ number_format($promedioacu,2,',','.') }}%</td>
				<td class="noborder"></td>
				<td align="center">{{ number_format(0,2,',','.') }}</td>
				<td align="center">{{ number_format(0,2,',','.') }}</td>
				<td align="center">{{ number_format(0,2,',','.') }}</td>
			</tr>
		@endforeach

		@php
			$mespromediototal = ($realmestotal > 0) ? ($arpmestotal/$realmestotal)-1 : 0;
			$acupromediototal = ($realacutotal > 0) ? ($arpacutotal/$realacutotal)-1 : 0;
			$acuvartotal = $realacutotal - $arpacutotal;
		@endphp

		<tr>
			<td class="noborder"></td>
			<td class="bold-cell">Total</td>
			<td class="noborder"></td>
			<td align="center">{{ number_format($arpmestotal,2,',','.') }}</td>
			<td align="center">{{ number_format($realmestotal,2,',','.') }}</td>
			<td align="center">{{ number_format($mespromediototal,2,',','.') }}%</td>
			<td class="noborder"></td>
			<td align="center">{{ number_format($arpacutotal,2,',','.') }}</td>
			<td align="center">{{ number_format($realacutotal,2,',','.') }}</td>
			<td align="center">{{ number_format($acuvartotal,2,',','.') }}</td>
			<td align="center">{{ number_format($acupromediototal,2,',','.') }}%</td>
			<td class="noborder"></td>
			<td align="center">{{ number_format(0,2,',','.') }}</td>
			<td align="center">{{ number_format(0,2,',','.') }}</td>
			<td align="center">{{ number_format(0,2,',','.') }}</td>
		</tr>
	</table>
@stop
