@extends('admin.tercerosinterno.main')

@section('breadcrumb')
	<li class="active">Terceros internos</li>
@stop

@section('module')
	<div class="box box-danger" id="tercerosinterno-main">
		<div class="box-body">
			{!! Form::open(['id' => 'form-koi-search-tercero-component', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form']) !!}
				<div class="form-group">
					<label for="tercerointerno_codigo" class="col-md-1 control-label">Documento</label>
					<div class="col-md-2">
						{!! Form::text('tercerointerno_codigo', session('search_tercerointerno_codigo'), ['id' => 'tercerointerno_codigo', 'class' => 'form-control input-sm']) !!}
					</div>

					<label for="tercerointerno_nombre" class="col-md-1 control-label">Nombre</label>
					<div class="col-md-8">
						{!! Form::text('tercerointerno_nombre', session('search_tercerointerno_nombre'), ['id' => 'tercerointerno_nombre', 'class' => 'form-control input-sm input-toupper']) !!}
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-4 col-md-2 col-xs-4">
						<button type="button" class="btn btn-default btn-block btn-sm btn-clear">Limpiar</button>
					</div>
					<div class="col-md-2 col-xs-4">
						<button type="button" class="btn btn-primary btn-block btn-sm btn-search">Buscar</button>
					</div>
				</div>
			{!! Form::close() !!}

			<div class="table-responsive">
				<table id="tercerosinterno-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Documento</th>
							<th>Nombre</th>
							<th>Razon Social</th>
							<th>Nombre1</th>
							<th>Nombre2</th>
							<th>Apellido1</th>
							<th>Apellido2</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
@stop
