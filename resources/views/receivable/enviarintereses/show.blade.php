@extends('receivable.enviarintereses.main')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
    <li><a href="{{ route('enviarintereses.index') }}">Enviar interes</a></li>
    <li class="active">{{ $enviarinteres->interes_codigo }}</li>
@stop

@section('module')
    <div class="box box-danger" id="enviarintereses-show">
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label">Cliente</label>
                    <div>{{ $enviarinteres->tercero_nombre }}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
					<label class="control-label">Numero</label>
                    <div>{{ $enviarinteres->intereses1_numero }}</div>
                </div>

                <div class="form-group col-md-3">
                    <label class="control-label">Sucursal</label>
                    <div>{{ $enviarinteres->sucursal_nombre }}</div>
                </div>
            	<div class="form-group col-md-3">
					<label class="control-label">Tasa</label>
                    <div>{{ $enviarinteres->intereses1_tasa }}</div>
				</div>
            	<div class="form-group col-md-3">
					<label class="control-label">Dias gracia</label>
                    <div>{{ $enviarinteres->intereses1_dias_gracia }}</div>
				</div>
            </div>
            <div class="row">
            	<div class="form-group col-md-3">
					<label class="control-label">Fecha</label>
                    <div>{{ $enviarinteres->intereses1_fecha }}</div>
				</div>
            	<div class="form-group col-md-3">
					<label class="control-label">Fecha corte</label>
                    <div>{{ $enviarinteres->intereses1_fecha_cierre }}</div>
				</div>
            </div>
            <div class="row">
            	<div class="form-group col-md-6">
					<label class="control-label">Elaboro</label>
                    <div>Usuario: {{ $enviarinteres->intereses1_usuario_elaboro }} <br> Fecha: {{ $enviarinteres->intereses1_fecha_elaboro }} - {{ $enviarinteres->intereses1_hora_elaboro }}</div>
				</div>
            </div>
        </div>

        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-2 col-md-offset-4 col-sm-6 col-xs-6">
                    <a href=" {{ route('enviarintereses.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-6">
                    <a href="#" class="btn btn-danger btn-sm btn-block">Anular</a>
                </div>
            </div>
        </div>

        <div class="box box-solid" id="wrapper-detalle-interes">
            <div class="box-body">
                <!-- table table-bordered table-striped -->
                <div class="box-body table-responsive no-padding">
                    <table id="browse-interes-detalle-list" class="table table-hover table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">Documento</th>
                                <th width="65%">Numero</th>
                                <th width="10%">Cta</th>
                                <th width="10%">Fecha</th>
                                <th width="10%">Vencimiento</th>
                                <th width="10%">D/Mora</th>
                                <th width="10%">Cobrados</th>
                                <th width="10%">Cobrando</th>
                                <th width="10%">Valor</th>
                                <th width="10%">Intereses</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Render content productos --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7"></td>
                                <th class="text-right">Subtotal</th>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                            </tr>
                            <tr>
                                <td colspan="7"></td>
                                <th class="text-right">IVA</th>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                            </tr>
                            <tr>
                                <td colspan="7"></td>
                                <th class="text-right">Total</th>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                                <td class="text-center" id="subtotal-cantidad">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
       </div>
    </div>
@stop
