@extends('admin.roles.main')

@section('module')
    <section class="content-header">
        <h1>
            Roles <small>Administración de roles</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
            <li><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="active">{{ $rol->id }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-success" id="rol-show">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6">
    					<label class="control-label">Mostrar nombre</label>
    					<div>{{ $rol->display_name }}</div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="control-label">Nombre</label>
                        <div>{{ $rol->name }}</div>
                    </div>
                </div>
                <div class="row">
                	<div class="form-group col-md-9">
    					<label class="control-label">Descripcion</label>
    					<div>{{ $rol->description }}</div>
    				</div>
                </div>
            </div>

            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-2 col-md-offset-4 col-sm-6 col-xs-6">
                        <a href=" {{ route('roles.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <a href=" {{ route('roles.edit', ['rol' => $rol->id])}}" class="btn btn-primary btn-sm btn-block"> {{trans('app.edit')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
