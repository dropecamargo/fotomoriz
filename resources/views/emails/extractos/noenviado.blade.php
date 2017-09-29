@extends('emails.layout')

@section('content')

    <table class="rtable" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td>Nit</td>
            <td>Razon Social</td>
            <td>Nombres</td>
            <td>Apellidos</td>
        </tr>
        @foreach($correos->noenviados as $noenviado)
            <tr>
                <td>{{ $noenviado->tercero_nit }}</td>
                <td>{{ $noenviado->tercero_razon_social }}</td>
                <td>{{ $noenviado->tercero_nombres }}</td>
                <td>{{ $noenviado->tercero_apellidos }}</td>
            </tr>
        @endforeach
    </table><br>

    <p>Lista de clientes(no enviados):</p>
    <div>Si desea resolver cualquier inquietud o si quiere en detalle la informaci&oacute;n suministrada, por favor contacte al Departamento de Cartera. <br>Atentamente, <br><br><br>William Nieves <br>Jefe Departamento de Cartera - {{ $empresa->empresa_nombre }} <br>Tel 3276868 ext 205, 245</div>
@stop
