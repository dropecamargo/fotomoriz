@extends('accounting.plancuentasn.main')

@section('breadcrumb')
	<li><a href="{{ route('plancuentasn.index') }}">Plan de cuenta</a></li>
	<li><a href="{{ route('plancuentasn.show', ['plancuentasn' => $plancuentasn->plancuentasn_cuenta]) }}">{{ $plancuentasn->plancuentasn_cuenta }}</a></li>
	<li class="active">Editar</li>
@stop

@section('module')
	<div class="box box-danger" id="plancuentasn-edit">
		<form id="form-plancuentasn" method="POST" accept-charset="UTF-8" data-toggle="validator">
			<div class="box-body" id="render-form-plancuentasn">
				{{-- Render form plancuentasn --}}
			</div>

			<div class="box-footer with-border">
	        	<div class="row">
					<div class="col-sm-2 col-sm-offset-4 col-xs-6 text-left">
						<a href="{{ route('plancuentasn.show', ['plancuentasn' => $plancuentasn->plancuentasn_cuenta]) }}" class="btn btn-default btn-sm btn-block">{{ trans('app.cancel') }}</a>
					</div>
					<div class="col-sm-2 col-xs-6 text-right">
						<button type="submit" class="btn btn-primary btn-sm btn-block">{{ trans('app.save') }}</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@stop
