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

			.color-cell {
				 background-color: #FFFF99;
			}

			.bold-cell {
				font-size: 10;
				font-weight: bold;
			}

			tr > td {
			    border: 1px solid #D3D3D3;
			}

			.border-top {
				border-top: 2px solid #000;
			}

			.border-bottom {
				border-bottom: 2px solid #000;
			}

			.border-right {
				border-right: 2px solid #000;
			}

			.border-left {
				border-left: 2px solid #000;
			}
		</style>
	</head>
	<body>
		<table border="1" cellspacing="0" cellpadding="0">
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
			@php
				$arpmestotal = $realmestotal = $arpacutotal = $realacutotal = $row = 0;
			@endphp

			@foreach ($data as $cuenta)
				@php $countrows = count($cuenta->cuentas) @endphp
				@foreach ($cuenta->cuentas as $item)
					@php
						$varmes = $item->mes - $item->arpmes;
						$varacu = $item->anoacu - $item->arpacu;
						$promedioacu = ($item->anoacu > 0) ? ($item->arpacu/$item->anoacu)-1 : 0;

						$arpmestotal += $item->arpmes;
						$realmestotal += $item->mes;
						$arpacutotal += $item->arpacu;
						$realacutotal += $item->anoacu;

						if ($row == 0) {
							$class = 'border-top';
						} else if ($row == $countrows) {
							$class = 'border-bototm';
						} else {
							$class = '';
						}
					@endphp

					<tr>
						<td class="{{ $class }} border-left" align="left">{{ strval($item->codigo) }}</td>
						<td class="{{ $class }} border-right" align="left">{{ $item->cuenta }}</td>
						<td class="{{ $class }}" class="noborder"></td>
						<td class="{{ $class }} border-left" align="center">{!! " ".number_format($item->arpmes,2,',','.') !!}</td>
						<td class="{{ $class }}" align="center" class="color-cell">{!! " ".number_format($item->mes,2,',','.') !!}</td>
						<td class="{{ $class }} border-right" align="center">{!! " ".number_format($varmes,2,',','.') !!}</td>
						<td class="{{ $class }}" class="noborder"></td>
						<td class="{{ $class }} border-left" align="center">{!! " ".number_format($item->arpacu,2,',','.') !!}</td>
						<td class="{{ $class }}" align="center" class="color-cell">{!! " ".number_format($item->anoacu,2,',','.') !!}</td>
						<td class="{{ $class }}" align="center">{!! " ".number_format($varacu,2,',','.') !!}</td>
						<td class="{{ $class }} border-right" align="center">{!! " ".number_format($promedioacu,2,',','.') !!}%</td>
						<td class="{{ $class }}" class="noborder"></td>
						<td class="{{ $class }} border-left" align="center">{!! " ".number_format(0,2,',','.') !!}</td>
						<td class="{{ $class }}" align="center">{!! " ".number_format(0,2,',','.') !!}</td>
						<td class="{{ $class }} border-right" align="center">{!! " ".number_format(0,2,',','.') !!}</td>
					</tr>
					@php $row++ @endphp
				@endforeach
				@php $row = 0 @endphp
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
		</table>
	</body>
</html>
