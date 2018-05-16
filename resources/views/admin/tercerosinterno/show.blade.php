@extends('admin.tercerosinterno.main')

@section('breadcrumb')
	<li><a href="{{ route('tercerosinterno.index') }}">Terceros internos</a></li>
	<li class="active">{{ $tercerointerno->tercerointerno_codigo }}</li>
@stop

@section('module')
	<div class="box box-danger" id="tercerosinterno-show">
		<div class="box-body">
			<div class="row">
				<div class="form-group col-md-3">
					<label class="control-label">Código</label>
					<div>{{ $tercerointerno->tercerointerno_codigo }}</div>
				</div>
				<div class="form-group col-md-9">
					<label class="control-label">Nombres</label>
					<div>{{ $tercerointerno->tercero_nombre }} </div>
				</div>
			</div>

	        <div class="row">
				<div class="form-group col-md-6">
					<label class="control-label">Código</label>
					<div>{{ $tercerointerno->tercero_nit }} - {{ $tercerointerno->tercero_razon_social }}</div>
				</div>
				<div class="form-group col-md-6">
					<label class="control-label">U. de desicion</label>
					<div>{{ $tercerointerno->unidaddecision_nombre }}</div>
				</div>
			</div>

	        <div class="row">
				<div class="form-group col-md-3">
					<label class="control-label">Fecha de ingreso</label>
					<div>{{ $tercerointerno->tercerointerno_ingreso }}</div>
				</div>
				<div class="form-group col-md-3">
					<label class="control-label">Usuario</label>
					<div>{{ $tercerointerno->tercerointerno_usuario }}</div>
				</div>
				<div class="form-group col-md-3">
					<label class="control-label">Código heison</label>
					<div>{{ $tercerointerno->tercerointerno_heison }}</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-3">
					<label class="control-label">Cargo</label>
					<div>{{ $tercerointerno->tercerointerno_cargo }}</div>
				</div>
				<div class="form-group col-md-3">
					<label class="control-label">Vendedor</label>
					<div>{!! $tercerointerno->tercerointerno_vendedor ? 'Si' : 'No' !!}</div>
				</div>
				<div class="form-group col-md-3">
					<label class="control-label">Tecnico</label>
					<div>{!! $tercerointerno->tercerointerno_tecnico ? 'Si' : 'No' !!}</div>
				</div>
				<div class="form-group col-md-3">
					<label class="control-label">Activo</label>
					<div>{!! $tercerointerno->tercerointerno_activo ? 'Si' : 'No' !!}</div>
				</div>
	        </div>

		    <div class="row">
	            <div class="col-md-offset-5 col-md-2 col-sm-6 col-xs-6">
					<a href="{{ route('tercerosinterno.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
	            </div>
	        </div><br/>

			{{-- Tab empleados --}}
			<div class="row">
		    	<div class="form-group col-md-offset-2 col-md-8">
					<div class="box box-danger" id="wrapper-roles">
						<div class="box-header with-border">
							<h3 class="box-title">Roles de usuario</h3>
						</div>
						<div class="box-body">
							<form method="POST" accept-charset="UTF-8" id="form-item-roles" data-toggle="validator">
								<div class="row">
									<label for="role_id" class="control-label col-sm-1 col-md-offset-1 hidden-xs">Rol</label>
									<div class="form-group col-md-7 col-xs-9">
										<select name="role_id" id="role_id" class="form-control select2-default" required>
											@foreach( App\Models\Base\Rol::getRoles() as $key => $value)
												<option value="{{ $key }}">{{ $value }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-md-2 col-xs-3">
										<button type="submit" class="btn btn-success btn-sm btn-block">
											<i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
							</form>
							<!-- table table-bordered table-striped -->
							<div class="table-responsive no-padding">
								<table id="browse-roles-list" class="table table-bordered" cellspacing="0">
									<thead>
										<tr>
											<th width="5%"></th>
											<th width="95%">Nombre</th>
										</tr>
									</thead>
									<tbody>
										{{-- Render content roles --}}
									</tbody>
								</table>
							</div>
						</div>
		            </div>
	            </div>
			</div>
		</div>
	</div>

	<script type="text/template" id="roles-item-list-tpl">
		<% if(edit) { %>
	        <td class="text-center">
	            <a class="btn btn-default btn-xs item-roles-remove" data-resource="<%- id %>">
	                <span><i class="fa fa-times"></i></span>
	            </a>
	    	</td>
	    <% } %>
		<td><%- display_name %></td>
	</script>
@stop
