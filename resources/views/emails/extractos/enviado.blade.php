@extends('emails.layout')

@section('content')
    <p>Cliente: {{ $cliente->tercero_nombre }}</p>
    <p>Nit: {{ $cliente->tercero_nit }}</p>
    <div>Apreciado cliente.<br>
     <br>Adjunto encontrar&aacute; el extracto del estado de su cuenta con {{ $empresa->empresa_nombre }}, para su an&aacute;lisis.
     <br>Si desea resolver cualquier inquietud o si quiere en detalle la informaci&oacute;n suministrada, por favor contacte al Departamento de Cartera.
     <br>Atentamente,
     <br><br><br>William Nieves
     <br>Jefe Departamento de Cartera - {{ $empresa->empresa_nombre }}
     <br>Tel 3276868 ext 205, 245</div>
@stop
