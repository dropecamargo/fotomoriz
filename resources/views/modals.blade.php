<!-- Modal confirm -->
<div class="modal fade" id="modal-confirm-component" data-backdrop="static" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header small-box {{ config('koi.template.bg') }}">
				<button type="button" class="close icon-close-koi" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="inner-title-modal modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="content-modal"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-sm confirm-action">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal search -->
<div class="modal fade" id="modal-search-component" data-backdrop="static" data-keyboard="true" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="content-modal"></div>
		</div>
	</div>
</div>

<script type="text/template" id="koi-search-tercero-component-tpl">
	<div class="modal-header small-box {{ config('koi.template.bg') }}">
		<button type="button" class="close icon-close-koi" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h4 class="inner-title-modal modal-title">Buscador de terceros</h4>
	</div>
	{!! Form::open(['id' => 'form-koi-search-tercero-component', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form']) !!}
		<div class="modal-body">
			<div class="form-group">
				<label for="koi_search_tercero_nit" class="col-md-1 control-label">Documento</label>
				<div class="col-md-2">
					{!! Form::text('koi_search_tercero_nit', null, ['id' => 'koi_search_tercero_nit', 'class' => 'form-control input-sm']) !!}
				</div>

				<label for="koi_search_tercero_nombre" class="col-md-1 control-label">Nombre</label>
				<div class="col-md-8">
					{!! Form::text('koi_search_tercero_nombre', null, ['id' => 'koi_search_tercero_nombre', 'class' => 'form-control input-sm input-toupper']) !!}
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-4 col-md-2 col-xs-6">
					<button type="button" class="btn btn-primary btn-block btn-sm btn-search-koi-search-tercero-component">Buscar</button>
				</div>
				<div class="col-md-2 col-xs-6">
					<button type="button" class="btn btn-default btn-block btn-sm btn-clear-koi-search-tercero-component">Limpiar</button>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-12 table-responsive">
					<table id="koi-search-tercero-component-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
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
	{!! Form::close() !!}
</script>
