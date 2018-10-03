@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
    <table class="tbtitle">
    	<thead>
            <tr><th class="center">Fecha de {{ $fechai }} hasta {{ $fechaf }} </th></tr>
    	</thead>
    </table>

    @foreach ( $data as $agrupacion )
        <div class="agrupacion">
            <!-- Nombre de la agrupacion -->
            <h4>{{ $agrupacion->nombre }}</h4>

            @foreach ( $agrupacion->grupos as $grupo )
                <div class="grupos">
                    <!-- Nombre de la grupo -->
                    <h4>{{ $grupo->nombre }}</h4>

                    @foreach ( $grupo->unificaciones as $unificacion )
                        <!-- Variables para calcular total en cada row -->
                        @php
                            $u_ventas = $u_descuentos = $u_devoluciones = $u_total = $u_presupuesto = $u_costo = 0;
                        @endphp
                        <div class="unificaciones">
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
                                        <td colspan="11">VENTAS</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_ventas += $item['ventas'] @endphp
                                            <td class="right">{{ number_format($item['ventas'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_ventas, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">DESCUENTOS</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_descuentos += $item['descuentos'] @endphp
                                            <td class="right">{{ number_format($item['descuentos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_descuentos, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">DEVOLUCIONES</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_devoluciones += $item['devoluciones'] @endphp
                                            <td class="right">{{ number_format($item['devoluciones'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_devoluciones, 2, ',', '.') }}</th>

                                    </tr>
                                    <tr>
                                        <td colspan="11">TOTAL</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_total += $item['total'] @endphp
                                            <td class="right">{{ number_format($item['total'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_total, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">PRESUPUESTO</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php $u_presupuesto += $item['presupuesto'] @endphp
                                            <td class="right">{{ number_format($item['presupuesto'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_presupuesto, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">PORCENTAJE</td>
                                        @foreach ( $unificacion->lineas as $item )
                                           <td class="right">{{ number_format($item['porcentaje'], 2, '.', '') }}%</td>
                                        @endforeach
                                        @php
                                            $u_porcentaje = ($u_presupuesto != 0) ? ($u_total*100)/$u_presupuesto : 0;
                                        @endphp
                                        <th class="right">{{ number_format($u_porcentaje, 2, '.', '') }}%</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">COSTO</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            @php
                                                $u_costo += $item['costos']
                                            @endphp
                                            <td class="right">{{ number_format($item['costos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th class="right">{{ number_format($u_costo, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">MARGEN</td>
                                        @foreach ( $unificacion->lineas as $item )
                                            <td class="right">{{ number_format($item['margen'], 2, ',', '.') }}</td>
                                        @endforeach
                                        @php
                                            $u_margen = $u_total-$u_costo
                                        @endphp
                                        <th class="right">{{ number_format($u_margen, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <td colspan="11">P_MARGEN</td>
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
                        <table class="configtable" border="1">
                            <tbody>
                                <!-- Total por fila en grupo -->
                                @php
                                    $gr_ventas = $gr_descuentos = $gr_devoluciones = $gr_totales = $gr_presupuestos = $gr_costos = 0;
                                @endphp
                                <tr class="color-black">
                                    <th colspan="11">TOTALES GRUPO {{ $grupo->grupo }}: {{ $grupo->nombre }}</th>
                                    @foreach($grupo->totales as $g_total)
                                        <th>{{ $g_total->sucursal }}</th>
                                    @endforeach
                                    <th class="width-10">TOTAL</th>
                                </tr>
                                <tr>
                                    <th colspan="11">VENTAS</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_ventas += $g_total->ventas
                                        @endphp
                                        <th class="right">{{ number_format($g_total->ventas, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_ventas, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">DESCUENTOS</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_descuentos += $g_total->descuentos
                                        @endphp
                                        <th class="right">{{ number_format($g_total->descuentos, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_descuentos, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">DEVOLUCIONES</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_devoluciones += $g_total->devoluciones
                                        @endphp
                                        <th class="right">{{ number_format($g_total->devoluciones, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_devoluciones, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">TOTAL</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_totales += $g_total->totales
                                        @endphp
                                        <th class="right">{{ number_format($g_total->totales, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_totales, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">PRESUPUESTO</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_presupuestos += $g_total->presupuestos
                                        @endphp
                                        <th class="right">{{ number_format($g_total->presupuestos, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_presupuestos, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">PORCENTAJE</th>
                                    @foreach($grupo->totales as $g_total)
                                        <th class="right">{{ number_format($g_total->porcentajes, 2, ',', '') }}%</th>
                                    @endforeach
                                    @php
                                        $gr_porcentajes = ($gr_presupuestos != 0) ? ($gr_totales*100)/$gr_presupuestos : 0;
                                    @endphp
                                    <th class="right">{{ number_format($gr_porcentajes, 2, ',', '') }}%</th>
                                </tr>
                                <tr>
                                    <th colspan="11">COSTO</th>
                                    @foreach($grupo->totales as $g_total)
                                        @php
                                            $gr_costos += $g_total->costos
                                        @endphp
                                        <th class="right">{{ number_format($g_total->costos, 2, ',', '.') }}</th>
                                    @endforeach
                                    <th class="right">{{ number_format($gr_costos, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">MARGEN</th>
                                    @foreach($grupo->totales as $g_total)
                                        <th class="right">{{ number_format($g_total->margenes, 2, ',', '.') }}</th>
                                    @endforeach
                                    @php
                                        $gr_margenes = $gr_totales-$gr_costos
                                    @endphp
                                    <th class="right">{{ number_format($gr_margenes, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="11">P_MARGEN</th>
                                    @foreach($grupo->totales as $g_total)
                                        <th class="right">{{ number_format($g_total->p_margenes, 2, ',', '') }}%</th>
                                    @endforeach
                                    @php
                                        $gr_p_margenes = ($gr_totales != 0) ? (100*($gr_totales-$gr_costos))/$gr_totales : 0
                                    @endphp
                                    <th class="right">{{ number_format($gr_p_margenes, 2, ',', '') }}%</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        <div class="detalles">
            <table class="configtable" border="1">
                <tbody>
                    <!-- Total por fila en agrupacion -->
                    @php
                        $ar_ventas = $ar_descuentos = $ar_devoluciones = $ar_totales = $ar_presupuestos = $ar_costos = 0;
                    @endphp
                    <tr class="color-black">
                        <th colspan="11">TOTALES AGRUPACIÓN {{ $agrupacion->agrupacion }}: {{ $agrupacion->nombre }}</th>
                        @foreach($agrupacion->totales as $a_total)
                            <th>{{ $a_total->sucursal }}</th>
                        @endforeach
                        <th class="width-10">TOTAL</th>
                    </tr>
                    <tr>
                        <th colspan="11">VENTAS</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_ventas += $a_total->ventas
                            @endphp
                            <th class="right">{{ number_format($a_total->ventas, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_ventas, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">DESCUENTOS</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_descuentos += $a_total->descuentos
                            @endphp
                            <th class="right">{{ number_format($a_total->descuentos, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_descuentos, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">DEVOLUCIONES</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_devoluciones += $a_total->devoluciones
                            @endphp
                            <th class="right">{{ number_format($a_total->devoluciones, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_devoluciones, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">TOTAL</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_totales += $a_total->totales
                            @endphp
                            <th class="right">{{ number_format($a_total->totales, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_totales, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">PRESUPUESTO</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_presupuestos += $a_total->presupuestos
                            @endphp
                            <th class="right">{{ number_format($a_total->presupuestos, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_presupuestos, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">PORCENTAJE</th>
                        @foreach($agrupacion->totales as $a_total)
                            <th class="right">{{ number_format($a_total->porcentajes, 2, ',', '') }}%</th>
                        @endforeach
                        @php
                            $ar_porcentajes = ($ar_presupuestos != 0) ? ($ar_totales*100)/$ar_presupuestos : 0;
                        @endphp
                        <th class="right">{{ number_format($ar_porcentajes, 2, ',', '') }}%</th>
                    </tr>
                    <tr>
                        <th colspan="11">COSTO</th>
                        @foreach($agrupacion->totales as $a_total)
                            @php
                                $ar_costos += $a_total->costos
                            @endphp
                            <th class="right">{{ number_format($a_total->costos, 2, ',', '.') }}</th>
                        @endforeach
                        <th class="right">{{ number_format($ar_costos, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">MARGEN</th>
                        @foreach($agrupacion->totales as $a_total)
                            <th class="right">{{ number_format($a_total->margenes, 2, ',', '.') }}</th>
                        @endforeach
                        @php
                            $ar_margenes = $ar_totales-$ar_costos
                        @endphp
                        <th class="right">{{ number_format($ar_margenes, 2, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="11">P_MARGEN</th>
                        @foreach($agrupacion->totales as $a_total)
                            <th class="right">{{ number_format($a_total->p_margenes, 2, ',', '') }}%</th>
                        @endforeach
                        @php
                            $ar_p_margenes = ($ar_totales != 0) ? (100*($ar_totales-$ar_costos))/$ar_totales : 0
                        @endphp
                        <th class="right">{{ number_format($ar_p_margenes, 2, ',', '') }}%</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@stop
