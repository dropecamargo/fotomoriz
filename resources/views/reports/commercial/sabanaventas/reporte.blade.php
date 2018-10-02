@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
    <table class="tbtitle">
    	<thead>
            <tr><th class="center">Fecha de {{ $fechai }} hasta {{ $fechaf }} </th></tr>
    	</thead>
    </table>

    @foreach ( $data as $agrupacion )
        <div class="agrupacion" style="border: 1px solid black;">
            <!-- Nombre de la agrupacion -->
            <h4>{{ $agrupacion->nombre }}</h4>

            @foreach ( $agrupacion->grupos as $grupo )
                <div class="grupos" style="border: 1px solid blue;">
                    <!-- Nombre de la grupo -->
                    <h4>{{ $grupo->nombre }}</h4>

                    @foreach ( $grupo->unificaciones as $unificacion )
                        <!-- Variables para calcular total en cada row -->
                        @php
                            $u_ventas = $u_descuentos = $u_devoluciones = $u_total = $u_presupuesto = $u_costo = 0;
                        @endphp
                        <div class="unificaciones" style="border: 1px solid yellow;">
                            <table class="configtable" border="1">
                                <tbody>
                                    <tr>
                                        <!-- Nombre de la unificacion -->
                                        <th colspan="11" class="noborder">{{ $unificacion->nombre }}</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            <th class="noborder width-10"><a href="#">{{ $item['sucursal'] }}</a></th>
                                        @endforeach
                                        <th class="noborder width-10">TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">VENTAS</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_ventas += $item['ventas'] @endphp
                                            <td class="right">{{ number_format($item['ventas'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_ventas, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">DESCUENTOS</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_descuentos += $item['descuentos'] @endphp
                                            <td class="right">{{ number_format($item['descuentos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_descuentos, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">DEVOLUCIONES</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_devoluciones += $item['devoluciones'] @endphp
                                            <td class="right">{{ number_format($item['devoluciones'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_devoluciones, 2, ',', '.') }}</th>

                                    </tr>
                                    <tr>
                                        <th colspan="11">TOTAL</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_total += $item['total'] @endphp
                                            <td class="right">{{ number_format($item['total'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_total, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">PRESUPUESTO</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_presupuesto += $item['presupuesto'] @endphp
                                            <td class="right">{{ number_format($item['presupuesto'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_presupuesto, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">PORCENTAJE</th>
                                        @foreach ( $unificacion->lineas as $item )
                                           <td class="right">{{ number_format($item['porcentaje'], 2, '.', '') }}%</td>
                                        @endforeach
                                        @php
                                            $u_porcentaje = ($u_presupuesto != 0) ? ($u_total*100)/$u_presupuesto : 0;
                                        @endphp
                                        <th class="right">{{ number_format($u_porcentaje, 2, '.', '') }}%</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">COSTO</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php
                                                $u_costo += $item['costos']
                                            @endphp
                                            <td class="right">{{ number_format($item['costos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_costo, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">MARGEN</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            <td class="right">{{ number_format($item['margen'], 2, ',', '.') }}</td>
                                        @endforeach
                                        @php
                                            $u_margen = $u_total-$u_costo
                                        @endphp
                                        <th class="right">{{ number_format($u_margen, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="11">P_MARGEN</th>
                                        @foreach ( $unificacion->lineas as $item )
                                            <td class="right">{{ number_format($item['p_margen'], '2', ',', '') }}%</td>
                                        @endforeach
                                        @php
                                            $u_pmargen = ($u_total != 0) ? (100*($u_total-$u_costo))/$u_total : 0
                                        @endphp
                                        <th class="right">{{ number_format($u_pmargen, 2, ',', '') }}%</th>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    @endforeach

                    <div class="detalle-grupos">
                        <table class="configtable-grupos" border="1">
                            <tbody>
                                <tr>
                                    <th colspan="11">TOTALES GRUPO {{ $grupo->grupo }}: {{ $grupo->nombre }}</th>
                                    <th class="width-10">TOTAL</th>
                                </tr>
                                <tr>
                                    <th colspan="11">VENTAS</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">DESCUENTOS</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">DEVOLUCIONES</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">TOTAL</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">PRESUPUESTO</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">PORCENTAJE</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">COSTO</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">MARGEN</th>
                                    <th class="right">0</th>
                                </tr>
                                <tr>
                                    <th colspan="11">P_MARGEN</th>
                                    <th class="right">0</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        <div class="detalles">
            <table class="configtable" border="1">
                <tbody>
                    <tr>
                        <th class="width-10">Linea</th>
                        <th>BOGOTA</th>
                        <th>MEDELLIN</th>
                        <th>CALI</th>
                        <th>BARRANQUILLA</th>
                        <th>PEREIRA</th>
                        <th>BUCARAMANGA</th>
                        <th>VILLAVICENCIO</th>
                        <th>NEIVA</th>
                        <th class="width-10">Total</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@stop
