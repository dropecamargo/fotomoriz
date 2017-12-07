@extends('layout.layout')

@section('title') Intereses @stop

@section('content')
<section class="content-header">
    <h1>
        Enviar intereses
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
        <li class="active">Enviar intereses</li>
    </ol>
</section>

<section class="content">
    <div class="box box-danger" id="sintereses-main">
        <div class="box-body">
            {!! Form::open(['method'=>'get', 'id' => 'form-send-interes', 'data-toggle' => 'validator']) !!}
                <div class="box-body">
                    <div class="row">
                        <label for="cierre" class="control-label col-sm-1 col-sm-offset-2">Cierre</label>
                        <div class="form-group col-sm-2">
                            <select name="mes" id="mes" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @foreach( config('koi.meses') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-sm-2">
                            <select name="ano" id="ano" class="form-control select" required>
                                <option disabled selected hidden value>Seleccione</option>
                                @for($i = date('Y'); $i >= config('koi.app.ano'); $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="interes_inicio" class="control-label col-sm-1 col-sm-offset-2">N° inicial</label>
                        <div class="form-group col-sm-2">
                            <input type="number" id="interes_inicio" name="interes_inicio" placeholder="Interes" class="form-control input-sm" min="1" step="1" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <label for="interes_fin" class="control-label col-sm-1">N° final</label>
                        <div class="form-group col-sm-2">
                            <input type="number" id="interes_fin" name="interes_fin" placeholder="Interes" class="form-control input-sm" min="1" step="1" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                            <button type="submit" class="btn btn-default btn-sm btn-block">Enviar intereses</button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif


</section>
@stop
