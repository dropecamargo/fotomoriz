@extends('receivable.enviarintereses.main')

@section('breadcrumb')
	<li class="active">Enviar intereses</li>
@stop

@section('module')
    <div id="enviarintereses-main">
        <div class="box box-danger">
            <div class="box-body">
                {!! Form::open(['id' => 'form-koi-search-factura-component', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form']) !!}
					<div class="row">
						<label for="searchinteres_mes" class="col-sm-1 control-label">Mes</label>
						<div class="form-group col-sm-2">
							<select name="mes" id="mes" class="form-control select" required>
								<option disabled selected hidden value>Seleccione</option>
								@foreach( config('koi.meses') as $key => $value)
									<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
							</select>
						</div>

						<label for="searchinteres_ano" class="col-sm-1 control-label">Año</label>
						<div class="form-group col-sm-2">
							<select name="ano" id="ano" class="form-control select" required>
								<option disabled selected hidden value>Seleccione</option>
								@for($i = date('Y'); $i >= config('koi.app.ano'); $i--)
									<option value="{{ $i }}">{{ $i }}</option>
								@endfor
							</select>
						</div>

						<label for="searchinteres_numero" class="col-sm-1 control-label">Numero</label>
						<div class="form-group col-sm-2">
							<input id="searchinteres_numero" placeholder="Numero" class="form-control input-sm" name="searchinteres_numero" type="text" maxlength="15" value="{{ session('searchinteres_numero') }}">
						</div>
					</div>

					<div class="row">
						<label for="searchinteres_tercero" class="col-sm-1 control-label">Tercero</label>
						<div class="form-group col-sm-2">
							<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-flat btn-koi-search-tercero-component-table" data-field="searchinteres_tercero">
										<i class="fa fa-user"></i>
									</button>
								</span>
								<input id="searchinteres_tercero" placeholder="Tercero" class="form-control tercero-koi-component input-sm" name="searchinteres_tercero" type="text" maxlength="15" data-name="searchinteres_tercero_nombre" value="{{ session('searchinteres_tercero') }}">
							</div>
						</div>
						<div class="col-sm-6">
							<input id="searchinteres_tercero_nombre" name="searchinteres_tercero_nombre" placeholder="Tercero beneficiario" class="form-control input-sm" type="text" maxlength="15" readonly value="{{ session('searchinteres_tercero_nombre') }}">
						</div>
	                </div>

					<div class="row">
	                    <div class="form-group">
	                        <div class="col-md-offset-4 col-md-2 col-xs-4">
	                            <button type="button" class="btn btn-default btn-block btn-sm btn-clear">Limpiar</button>
	                        </div>
	                        <div class="col-md-2 col-xs-4">
	                            <button type="button" class="btn btn-primary btn-block btn-sm btn-search">Buscar</button>
	                        </div>
	                    </div>
					</div>
                {!! Form::close() !!}
            </div>

            <div class="box-body table-responsive">
                <table id="enviarintereses-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" name="select_all" id="select_all" value="1"></th>
                            <th width="7%">Numero</th>
                            <th width="10%">Sucursal</th>
                            <th width="40%">Cliente</th>
                            <th width="10%">Tasa</th>
                            <th width="10%">D. Gracia</th>
                            <th width="10%">Fecha</th>
                            <th width="10%">Ususario</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
