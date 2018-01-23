@extends('layout.layout')

@section('title') Enviar intereses @stop

@section('content')
    <section class="content-header">
        <h1>
            Enviar intereses <small>Administración envio de intereses</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{trans('app.home')}}</a></li>
            @yield('breadcrumb')
        </ol>
    </section>

    <section class="content">
        @yield ('module')
    </section>

    <script type="text/template" id="enviarinterese-detalle-item-tpl">
        <td><%- documentos_nombre %></td>
        <td><%- intereses2_num_origen %></td>
        <td class="text-center"><%- intereses2_cuo_origen %></td>
        <td class="text-center"><%- intereses2_expedicion %></td>
        <td class="text-center"><%- intereses2_vencimiento %></td>
        <td class="text-center"><%- intereses2_dias_mora %></td>
        <td class="text-center"><%- cobrados %></td>
        <td class="text-center"><%- intereses2_dias_a_cobrar %></td>
        <td class="text-right"><%- window.Misc.currency( intereses2_saldo ) %></td>
        <td class="text-right"><%- window.Misc.currency( intereses2_interes ) %></td>
    </script>

    <script type="text/template" id="interes-enviar-confirm-tpl">
        <p>¿Está seguro que desea enviar los interés?</p>
    </script>
@stop
