@extends('layout.layout')

@section('title') Amortizaciones @stop

@section('content')
    <section class="content-header">
		<h1>
			Amortizaciones creditos
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Amortizaciones creditos</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger">
			<div class="box-body">
                <form action="{{ route('amortizaciones.index') }}" method="GET" data-toggle="validator">
                    <input class="hidden" id="type-reporte-koi-component" name="type"></input>
                    <div class="row">
                        <label for="amortizacion_valor" class="control-label col-sm-1 col-md-offset-3">Valor</label>
                        <div class="form-group col-sm-2">
                            <input type="text" id="amortizacion_valor" name="amortizacion_valor" class="form-control input-sm" data-currency required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <label for="amortizacion_interes" class="control-label col-sm-1">EA %</label>
                        <div class="form-group col-sm-2">
                            <input type="text" id="amortizacion_interes" name="amortizacion_interes" placeholder="Tasa" class="form-control input-sm spinner-percentage" maxlength="6" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="amortizacion_cuota" class="control-label col-sm-1 col-md-offset-3">Cuotas</label>
                        <div class="form-group col-sm-2">
                            <input type="number" id="amortizacion_cuota" name="amortizacion_cuota" min="0" class="form-control input-sm" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <label for="amortizacion_gracia" class="control-label col-sm-1">Gracia</label>
                        <div class="form-group col-sm-2">
                            <input type="number" id="amortizacion_gracia" name="amortizacion_gracia" min="0" class="form-control input-sm" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="amortizacion_seguro" class="control-label col-sm-1 col-md-offset-3">Seguro</label>
                        <div class="form-group col-sm-2">
                            <input type="text" id="amortizacion_seguro" name="amortizacion_seguro" class="form-control input-sm" data-currency required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                            <button type="submit" class="btn btn-default btn-sm btn-block btn-export-pdf-koi-component">
                                <i class="fa fa-file-text-o"></i> {{ trans('app.pdf') }}
                            </button>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</section>
@stop
