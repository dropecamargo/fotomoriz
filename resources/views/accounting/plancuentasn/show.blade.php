@extends('accounting.plancuentasn.main')

@section('breadcrumb')
    <li><a href="{{route('plancuentasn.index')}}">Plan de cuenta</a></li>
    <li class="active">{{ $plancuentasn->plancuentasn_cuenta }}</li>
@stop

@section('module')
    <div class="box box-danger">
        <div class="box-body">
            <div class="row">
                <div class="form-group col-sm-2">
                    <label class="control-label">Cuenta</label>
                    <div>{{ $plancuentasn->plancuentasn_cuenta }}</div>
                </div>
                <div class="form-group col-sm-10">
                    <label class="control-label">Nombre</label>
                    <div>{{ $plancuentasn->plancuentasn_nombre }}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2">
                    <label class="control-label">Naturaleza</label>
                    <div>{{ $plancuentasn->plancuentasn_naturaleza == 'D' ? 'DEBITO' : 'CREDITO' }}</div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">Clase</label>
                    <div>{{ $plancuentasn->plancuentasn_clase }}</div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">Grupo</label>
                    <div>{{ $plancuentasn->plancuentasn_grupo }}</div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">Tercero</label>
                    <div>{{ $plancuentasn->plancuentasn_tercero ? 'SI' : 'NO' }}</div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">Base</label>
                    <div>{{ $plancuentasn->plancuentasn_base ? 'SI' : 'NO' }}</div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">Bloqueo</label>
                    <div>{{ $plancuentasn->plancuentasn_bloqueo ? 'SI' : 'NO' }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label">Concepto</label>
                    <div>{{ $plancuentasn->plancuentasn_concepto ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="box-footer with-border">
            <div class="row">
                <div class="col-sm-2 col-sm-offset-4 col-xs-6 text-left">
                    <a href=" {{ route('plancuentasn.index')}} " class="btn btn-default btn-sm btn-block"> {{ trans('app.comeback') }}</a>
                </div>
                <div class="col-sm-2 col-xs-6 text-right">
                    <a href=" {{ route('plancuentasn.edit', ['plancuentasn' => $plancuentasn->plancuentasn_cuenta]) }}" class="btn btn-primary btn-sm btn-block">{{trans('app.edit')}}</a>
                </div>
            </div>
        </div>
    </div>
@stop
