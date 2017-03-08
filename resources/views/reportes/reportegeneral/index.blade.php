@extends('layout.layout')

@section('title') Reporte @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-success" id="reporte-create">
	    	<form action="{{ route('reportes.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
					<div class="row">
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-2">
							<label for="fecha_inicio" class="control-label">Fecha Inicio</label>
							<select name="fecha_inicio" id="fecha_inicio" class="form-control" required>
								@for($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="fecha_salida" class="control-label">Fecha Salida</label>
							<select name="fecha_salida" id="fecha_salida" class="form-control" required>
								@for($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-4">
							<label for="sucursal" class="control-label">Sucursal</label>
	                        <select name="sucursal" id="sucursal" class="form-control select2-default-clear">
	                        	@foreach($sucursal as $key => $value )
	                        		<option value="{{ $key }}" <%- sucursal == '{{ $key }}' ? 'selected': ''%>{{ $value }}</option>
	                        	@endforeach
	                        </select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
							<button type="submit" class="btn btn-default btn-sm btn-block btn-export-xls-koi-component">
								<i class="fa fa-file-text-o"></i> {{ trans('app.xls') }}
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
@stop