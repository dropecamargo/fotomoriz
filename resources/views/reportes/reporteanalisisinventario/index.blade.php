@extends('layout.layout')

@section('title') Reporte Analisis Inventario @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte Analisis Inventario
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte Analisis Inventario</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form action="{{ route('reporteanalisisinventario.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
					<div class="row">
						
						
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-2">
							<label for="mes" class="control-label">Mes</label>
	                        <select name="mes" id="mes" class="form-control" required>
								@foreach( config('koi.meses') as $key => $value)
									<option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>
						

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="ano" class="control-label">Año</label>
	                        <select name="ano" id="ano" class="form-control" required>
								@for($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
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