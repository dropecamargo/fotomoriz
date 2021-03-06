@extends('layout.layout')

@section('title') Reporte entradas y salidas @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte entradas y salidas
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte entradas y salidas</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form action="{{ route('reporteentradassalidas.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
					<div class="row">
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-2">
							<label for="fecha_inicial" class="control-label">Fecha inicial</label>
							<input type="text" id="fecha_inicial" name="fecha_inicial" placeholder="Fecha inicial" class="form-control input-sm datepicker" value="{{ date('Y-m-d') }}" required>
						</div>

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="fecha_final" class="control-label">Fecha final</label>
							<input type="text" id="fecha_final" name="fecha_final" placeholder="Fecha final" class="form-control input-sm datepicker" value="{{ date('Y-m-d') }}" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-4">
							<label for="sucursal" class="control-label">Sucursal</label>
	                        <select name="sucursal" id="sucursal" class="form-control select2-default-clear" required>
                                <option value=""></option>
	                        	@foreach( App\Models\Base\Sucursal::getSucursales() as $key => $value )
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
