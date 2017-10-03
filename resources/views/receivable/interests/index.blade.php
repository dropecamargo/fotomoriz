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
    <div class="box box-danger">
        <form action="#" method="GET" data-toggle="validator">
            <div class="box-body">
                <div class="row">
                    <label for="interes_tasa" class="control-label col-sm-1">Tasa</label>
                    <div class="form-group col-sm-3">
                        <input type="text" id="interes_tasa" name="interes_tasa" placeholder="Tasa" class="form-control input-sm spinner-percentage" maxlength="4" required>
                        <input type="text" id="actividad_tarifa" name="actividad_tarifa" value="<%- actividad_tarifa %>" placeholder="% Cree" class="form-control input-sm spinner-percentage" maxlength="4" required>
                    </div>
                </div>
                <div class="row">
                    <label for="interes_dias_gracia" class="control-label col-sm-1">Dias gracia</label>
                    <div class="form-group col-sm-3">
                        <input id="interes_dias_gracia" name="interes_dias_gracia" class="form-control input-sm" required>
                    </div>
                </div>
                <div class="row">
                    <label for="interes_fecha" class="control-label col-sm-1">Fecha</label>
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" id="interes_dias_gracia" name="interes_dias_gracia" class="form-control input-sm datepicker" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="interes_fecha_lq" class="control-label col-sm-1">Fecha Lq</label>
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" id="interes_fecha_lq" name="interes_fecha_lq" class="form-control input-sm datepicker" required>
                        </div>
                    </div>

                    <label for="interes_cierre" class="control-label col-sm-1">Cierre</label>
                    <div class="form-group col-sm-3">
                        <input id="interes_dias_gracia" name="interes_dias_gracia" class="form-control input-sm" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <input id="interes_dias_gracia" name="interes_dias_gracia" class="form-control input-sm" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                        <button type="submit" class="btn btn-default btn-sm btn-block btn-export-xls-koi-component">
                            <i class="fa fa-file-text-o"></i> {{ trans('app.xls') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@stop
