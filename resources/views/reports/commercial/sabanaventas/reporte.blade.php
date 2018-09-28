@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
    <table class="tbtitle">
    	<thead>
            <tr><th class="center">Fecha de {{ $fechai }} hasta {{ $fechaf }} </th></tr>
    	</thead>
    </table>

    @foreach ( $data as $agrupacion )
        <div class="agrupacion" style="border: 1px solid black;">
            <h4>{{ $agrupacion->agrupacion }}</h4>

            @foreach ( $agrupacion->grupos as $key => $grupo )
                <div class="grupos" style="border: 1px solid blue;">
                    <h4>{{ $grupo->grupo }}</h4>

                    @php
                        $totalgrupos = [];
                    @endphp
                    @foreach ( $grupo->unificaciones as $unificacion )
                        <!-- Variables para calcular total en cada row -->
                        @php
                            $uventas = $udescuentos = $udevoluciones = $utotal = $upresupuesto = $uporcentaje = $ucosto = $umargen = $upmargen = 0;
                        @endphp
                        <div class="unificaciones" style="border: 1px solid yellow;">
                            <table class="configtable" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="11" class="noborder">{{ $unificacion->unificacion }}</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <th class="noborder" width="10%"><a href="#">{{ $item['sucursal'] }}</a></th>
                                        @endforeach
                                        <th width="10%" class="noborder">TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">VENTAS</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $uventas += $item['ventas'] @endphp
                                            <td class="right">{{ number_format($item['ventas'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($uventas, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">DESCUENTOS</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $udescuentos += $item['descuentos'] @endphp
                                            <td class="right">{{ number_format($item['descuentos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($udescuentos, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">DEVOLUCIONES</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $udevoluciones += $item['devoluciones'] @endphp
                                            <td class="right">{{ number_format($item['devoluciones'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($udevoluciones, 2, ',', '.') }}</th>

                                    </tr>
                                    <tr>
                                        <th colspan="11">TOTAL</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $utotal += $item['total'] @endphp
                                            <td class="right">{{ number_format($item['total'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($utotal, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">PRESUPUESTO</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $upresupuesto += $item['presupuesto'] @endphp
                                            <td class="right">{{ number_format($item['presupuesto'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($upresupuesto, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">PORCENTAJE</th>
                                        @foreach ( $unificacion->detalle as $item )
                                           @php
                                                $porcentaje = $item['presupuesto'] != 0 ? ($item['total']*100)/$item['presupuesto'] : 0;
                                           @endphp
                                           <td class="right">{{ number_format($porcentaje, 2, '.', '') }}%</td>
                                        @endforeach
                                        @php $uporcentaje = ($utotal != 0 && $upresupuesto != 0) ? ($utotal*100)/$upresupuesto : 0; @endphp
                                        <th class="right">{{ number_format($uporcentaje, 2, '.', '') }}%</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">COSTO</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $ucosto += $item['costos'] @endphp
                                            <td class="right">{{ number_format($item['costos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($ucosto, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">MARGEN</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php $margen = $item['total']-$item['costos'] @endphp
                                            <td class="right">{{ number_format($margen, 2, ',', '.') }}</td>
                                        @endforeach
                                        @php $umargen = $utotal-$ucosto; @endphp
                                        <th class="right">{{ number_format($umargen, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">P_MARGEN</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            @php
                                                $p_margen = ($item['total'] != 0) ? (100*($item['total']-$item['costos']))/$item['total'] : 0;
                                            @endphp
                                            <td class="right">{{ number_format($p_margen, '2', ',', '') }}%</td>
                                        @endforeach
                                        @php $upmargen = ($utotal != 0) ? (100*($utotal-$ucosto))/$utotal : 0; @endphp
                                        <th class="right">{{ number_format($upmargen, 2, ',', '') }}%</th>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                        </div>
                    @endforeach

                    <div class="detalle-unificaciones">
                        <table class="configtable" border="1">
                        <thead>
                            <tr>
                                <th colspan="4">Totales grupo {{ $key+1 }}: {{ $grupo->grupo }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Ventas</th>
                            </tr>
                            <tr>
                                <th>Descuentos</th>
                            </tr>
                            <tr>
                                <th>Devoluciones</th>
                            </tr>
                            <tr>
                                <th>Total</th>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            @endforeach
        <div class="detalles">
            <table class="configtable" border="1">
                <thead>
                    <tr>
                        <th width="10%">Linea</th>
                        <th>BOGOTA</th>
                        <th>MEDELLIN</th>
                        <th>CALI</th>
                        <th>BARRANQUILLA</th>
                        <th>PEREIRA</th>
                        <th>BUCARAMANGA</th>
                        <th>VILLAVICENCIO</th>
                        <th>NEIVA</th>
                        <th width="10%">Total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @endforeach
@stop
