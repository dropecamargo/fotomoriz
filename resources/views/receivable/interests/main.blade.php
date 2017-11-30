@extends('layout.layout')

@section('title') Intereses @stop

@section('content')
<section class="content-header">
    <h1>
        Intereses <small>Generador de intereses</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
        <li class="active">Intereses</li>
    </ol>
</section>

<section class="content">
    <div class="box box-danger" id="intereses-main">
        <div class="box-body">
            @if (Session::has('message'))
            	<div class="alert alert-success">{{ Session::get('message') }}</div>
            @endif
            @if (count($errors) > 0)
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif

            {!! Form::open(['method'=>'get', 'id' => 'form-intereses', 'data-toggle' => 'validator']) !!}
                <div class="box-body">
                    <div class="row">
                        <label for="intereses1_tasa" class="control-label col-sm-1 col-sm-offset-2">Tasa %</label>
                        <div class="form-group col-sm-2">
                            <input type="text" id="intereses1_tasa" name="intereses1_tasa" placeholder="Tasa" class="form-control input-sm spinner-percentage" value="{{ old('intereses1_tasa') }}" maxlength="4" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="intereses1_dias_gracia" class="control-label col-sm-1 col-sm-offset-2">Dias gracia</label>
                        <div class="form-group col-sm-2">
                            <input id="intereses1_dias_gracia" name="intereses1_dias_gracia" class="form-control input-sm" type="number" value="{{ old('intereses1_dias_gracia') }}" min="0" step="1" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="intereses1_fecha" class="control-label col-sm-1 col-sm-offset-2">Fecha</label>
                        <div class="form-group col-md-2">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" id="intereses1_fecha" name="intereses1_fecha" class="form-control input-sm datepicker" value="{{ old('intereses1_fecha') }}" required>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>

                    <div class="row">
                        <label for="interes_cierre" class="control-label col-sm-1 col-sm-offset-2">Cierre</label>
                        <div class="form-group col-sm-2">
                            <select name="ano" id="ano" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @for($i = date('Y'); $i >= config('koi.app.ano'); $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-sm-2">
                            <select name="mes" id="mes" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @foreach( config('koi.meses') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="intereses1_observaciones" class="control-label col-sm-1 col-sm-offset-2">Observaciones</label>
                        <div class="form-group col-sm-7">
                            <textarea id="intereses1_observaciones" name="intereses1_observaciones" class="form-control" rows="2" placeholder="Observaciones"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                            <button type="submit" class="btn btn-default btn-sm btn-block">
                                <i class="fa fa-file-text-o"></i> Generar rutina
                            </button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@stop
