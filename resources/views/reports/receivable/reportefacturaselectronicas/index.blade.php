@extends('layout.layout')

@section('title') Reporte Facturas Electronicas @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte facturas electronicas
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte facturas electronicas</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form action="{{ route('reportefacturaselectronicas.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-sm-offset-2 col-sm-4">
                            <label for="control-label">Tipo</label>
                            <select name="filtertype" id="filtertype" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                    <option value="_" selected>Todas</option>
                                    <option value="0">Facturas</option>
                                    <option value="1">Anuladas</option>
                                    <option value="2">Devoluciones</option>
                            </select>
                        </div>

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="filterinitialdate" class="control-label">Fecha inicial</label>
							<input type="text" id="filterinitialdate" name="filterinitialdate" placeholder="Fecha inicial" class="form-control input-sm datepicker" value="{{ date('Y-m-d') }}" required>
						</div>

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="filterenddate" class="control-label">Fecha final</label>
							<input type="text" id="filterenddate" name="filterenddate" placeholder="Fecha final" class="form-control input-sm datepicker" value="{{ date('Y-m-d') }}" required>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
							<button type="submit" class="btn btn-default btn-sm btn-block btn-export-pdf-koi-component">
                                <i class="fa fa-file-text-o"></i> {{ trans('app.pdf') }}
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
	</section>
@stop
