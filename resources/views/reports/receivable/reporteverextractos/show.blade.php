@extends('reports.receivable.reporteverextractos.main')

@section('breadcrumb')
    <li><a href="{{ route('reporteverextractos.index')}}">Ver extractos</a></li>
    <li class="active">{{ $id }}</li>
@stop

@section('module')
    <div id="verextractos-show" class="box box-danger">
        <div class="box-body">
            <table id="extractos-files-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Archivo</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div><br>

        <div class="box-footer with-border">
            <div class="row">
                <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6 text-left">
                    <a href=" {{ route('reporteverextractos.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
                </div>
            </div>
        </div>
    </div>
@stop
