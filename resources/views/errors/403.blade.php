@extends('layout.layout')

@section('title') ¡Acceso no autorizado! @stop

@section('content')
	<section class="content-header">
		<h1> <i class="fa fa-lock text-yellow"></i> ¡Acceso no autorizado! </h1>
    </section>

    <section class="content">
		<p>
			No ha sido autorizado a ingresar a este módulo. Puedes revisar los permisos con el <strong>administrador</strong>. <button type="button" class="btn btn-default btn-sm history-back">{{ trans('app.comeback') }}</button>
		</p>
	</section>
@stop