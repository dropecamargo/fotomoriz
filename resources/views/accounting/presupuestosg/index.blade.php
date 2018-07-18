@extends('layout.layout')

@section('title') Presupuestos @stop

@section('content')
    <section class="content-header">
		<h1>
			Presupuesto de gastos <small>Administración de presupuesto de gastos</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Presupuesto de gastos</li>
		</ol>
    </section>

	<section class="content">
		<div id="presupuestosg-main">
			<div class="box box-danger">
				<div class="box-body">
                    {!! Form::open(['id' => 'form-koi-search-tercero-component', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form']) !!}
                        <div class="form-group">
                            <label for="search_mes" class="col-md-1 control-label">Mes</label>
                            <div class="col-md-2">
                                <select name="search_mes" id="search_mes" class="form-control" required>
                                    <option hidden disabled selected value="">Seleccione</option>
                                    @foreach( config('koi.meses') as $key => $value)
                                        <option value="{{ $key }}" {{ session('search_mes') == $key ? 'selected' : ''}}>{{ $value }}</option>
                                    @endforeach
								</select>
                            </div>

                            <label for="search_ano" class="col-md-1 control-label">Año</label>
                            <div class="col-md-2">
                                <select name="search_ano" id="search_ano" class="form-control" required>
                                    <option hidden disabled selected value="">Seleccione</option>
                                    @for($i = date('Y'); $i >= config('koi.app.ano'); $i--)
                                        <option value="{{ $i }}" {{ session('search_ano') == $i ? 'selected' : ''}}>{{ $i }}</option>
                                    @endfor
								</select>
                            </div>

                            <label for="search_unidad" class="col-md-1 control-label">Unidad</label>
                            <div class="col-md-5">
                                <select name="search_unidad" id="search_unidad" data-placeholder="Unidad de decision" class="form-control select2-default-clear">
									@foreach( App\Models\Accounting\UnidadDecision::getUnidadesDecision() as $key => $value)
										<option value="{{ $key }}" {{ session('search_unidad') == $key ? 'selected': '' }}>{{ $value }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-2 col-xs-4">
                                <button type="button" class="btn btn-default btn-block btn-sm btn-clear">Limpiar</button>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <button type="button" class="btn btn-primary btn-block btn-sm btn-search">Buscar</button>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <a class="btn btn-success btn-block btn-import-files"><i class="fa fa-upload"></i> Importar</a>
                            </div>
                        </div>
                    {!! Form::close() !!}

                    <div class="table-responsive">
                        <table id="presupuestog-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">Mes</th>
                                    <th width="10%">Año</th>
                                    <th width="30%">Unidad de decision</th>
                                    <th width="10%">Nivel 1</th>
                                    <th width="10%">nivel 2</th>
                                    <th width="30%" style="text-align: left;">Valor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
				</div>
			</div>
		</div>
    </section>
@stop
