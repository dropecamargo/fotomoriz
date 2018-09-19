@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
    <table class="tbtitle">
    	<thead>
            <tr><th class="center">Fecha de {{ $fechai }} hasta {{ $fechaf }} </th></tr>
    	</thead>
    </table>

    <div class="container">
        @foreach ( $data as $agrupacion )
            <div class="agrupacion">
                {{ $agrupacion->agrupacion }}

                @foreach ( $agrupacion->grupos as $grupo )
                    <div class="grupos">
                        {{ $grupo->grupo }}
                    </div>

                    @foreach ( $grupo->unificaciones as $unificacion )
                        <div class="unificaciones">
                            <table class="configtable" border="1">
                                <thead>
                                    <tr>
                                        <th width="15%" colspan="2" class="noborder">{{ $unificacion->unificacion }}</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <th class="color-blue noborder">{{ $item['sucursal'] }}</th>
                                        @endforeach
                                        <th width="10%" class="color-blue noborder">TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>VENTAS</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format($item['ventas'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>DESCUENTOS</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format($item['descuentos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>DEVOLUCIONES</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format(0, 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>TOTAL</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format(0, 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>PRESUPUESTO</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format(0, 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>PORCENTAJE</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">0%</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>COSTO</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format($item['costos'], 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>MARGEN</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">{{ number_format(0, 2, ',', '.') }}</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                    <tr>
                                        <th class="noborder"></th>
                                        <th>P_MARGEN</th>
                                        @foreach ( $unificacion->detalle as $item )
                                            <td class="right">0%</td>
                                        @endforeach
                                        <th>0</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @endforeach
    </div>
@stop
