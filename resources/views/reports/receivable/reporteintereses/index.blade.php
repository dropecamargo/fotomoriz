@extends('layout.layout')

@section('title') Reporte de intereses generados @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte de intereses generados
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte de intereses generados</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form action="{{ route('rintereses.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
                    <div class="row">
                        <label for="interes_cierre" class="control-label col-sm-1 col-sm-offset-3">Fecha</label>
                        <div class="form-group col-sm-2">
                            <select name="mes" id="mes" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @foreach( config('koi.meses') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-sm-2">
                            <select name="ano" id="ano" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @for($i = date('Y'); $i >= config('koi.app.ano'); $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="help-block with-errors"></div>
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
