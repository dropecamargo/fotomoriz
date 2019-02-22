<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Format title --}}
		<title>{{ $type == 'xls' ? substr($title, 0 , 31) : $title }}</title>

		<style type="text/css">
		 	.size-1 {
				font-size: 1;
			}

			.noborder {
			    border: none !important;
			}

			.yellow {
				 background-color: #FFFF99;
			}

			.gray {
				 background-color: #D3D3D3;
			}

			.bold-cell {
				font-size: 10;
				font-weight: bold;
			}

			thead > tr > td {
			    border: 1px solid #000;
			}

			tfoot > tr > td {
			    border: 1px solid #000;
			}

			.border-top {
				border-top: 4px solid #000 !important;
			}

			.border-bottom {
				border-bottom: 4px solid #000 !important;
			}

			.border-left-right {
				border-right: 1px solid #000 !important;
				border-left: 1px solid #000 !important;
			}
		</style>
	</head>
	<body>
		<table border="1" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td class="noborder size-1">.</td>
					<td align="center" class="bold-cell">UNIDAD DE DECISION:</td>
					<td class="noborder"></td>
					<td colspan="3" align="center">{{ $unidad->unidaddecision_nombre }}</td>
					<td class="noborder"></td>
					<td colspan="4" align="center" class="bold-cell">FECHA:</td>
					<td class="noborder"></td>
					<td colspan="3" align="center"> {{ $mes }} / {{ $ano }} </td>
				</tr>
				<tr>
					<td colspan="15" class="noborder"></td>
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
			</thead>
			<tbody>
				@php
					$arpmestotal = $realmestotal = $arpacutotal = $realacutotal = $a = 0;
					$ultimapos = '';
				@endphp

				@foreach ($auxiliar as $item)
					@php
						$varmes = $item->mes - $item->arpmes;
						$varacu = $item->anoacu - $item->arpacu;
						$promedioacu = ($item->anoacu > 0) ? ($item->arpacu/$item->anoacu)-1 : 0;

						$arpmestotal += $item->arpmes;
						$realmestotal += $item->mes;
						$arpacutotal += $item->arpacu;
						$realacutotal += $item->anoacu;

						if ($item->concepto == $ultimapos) {
							$a++;
						} else {
							$a = 0;
						}

						if ($a == 0) {
							$border = 'border-top';
						} else {
							$border = '';
						}
					@endphp

					<tr>
						<td class="{{ $border }} border-left-right" align="left">{{ $item->codigo }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="left">{{ $item->cuenta }}&nbsp;</td>
						<td class="noborder"></td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format($item->arpmes,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right yellow" align="center">{{ number_format($item->mes,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format($varmes,2,',','.') }}&nbsp;</td>
						<td class="noborder"></td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format($item->arpacu,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right yellow" align="center">{{ number_format($item->anoacu,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format($varacu,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format($promedioacu,2,',','.') }}%</td>
						<td class="noborder"></td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format(0,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format(0,2,',','.') }}&nbsp;</td>
						<td class="{{ $border }} border-left-right" align="center">{{ number_format(0,2,',','.') }}&nbsp;</td>
					</tr>

					@php
						$ultimapos = $item->concepto;
					@endphp
				@endforeach
			</tbody>
			<tfoot>
				@php
				    $mespromediototal = ($realmestotal > 0) ? ($arpmestotal/$realmestotal)-1 : 0;
				    $acupromediototal = ($realacutotal > 0) ? ($arpacutotal/$realacutotal)-1 : 0;
				    $acuvartotal = $realacutotal - $arpacutotal;
				@endphp
				<tr>
				    <td class="noborder"></td>
				    <td class="bold-cell">Total</td>
				    <td class="noborder"></td>
				    <td align="center">{!! " ".number_format($arpmestotal,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format($realmestotal,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format($mespromediototal,2,',','.') !!}%</td>
				    <td class="noborder"></td>
				    <td align="center">{!! " ".number_format($arpacutotal,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format($realacutotal,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format($acuvartotal,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format($acupromediototal,2,',','.') !!}%</td>
				    <td class="noborder"></td>
				    <td align="center">{!! " ".number_format(0,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format(0,2,',','.') !!}</td>
				    <td align="center">{!! " ".number_format(0,2,',','.') !!}</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>
