@extends('reports.layout', ['type' => $type, 'title' => $title])

@section('content')
    <table class="tbtitle">
    	<thead>
            <tr><th class="center">Fecha inicio: {{ $fechai }}  Fecha final: {{ $fechaf }} </th></tr>
    	</thead>
    </table>

    @foreach ( $data as $obj )
        {{ $obj->agrupacion }}<br>

        @foreach ( $obj->grupos as $grupo )
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $grupo->grupo }}<br>

            @foreach( $grupo->unificaciones as $unificacion )

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $unificacion }}<br>

            @endforeach
        @endforeach
    @endforeach
@stop
