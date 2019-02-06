@extends('accounting.plancuentasn.main')

@section('breadcrumb')
    <li class="active">Plan de cuentas</li>
@stop

@section('module')
    <div id="plancuentasn-main" class="box box-danger">
        <div class="box-body">
            <div class="table-responsive">
                <table id="plancuentasn-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="20%">Cuenta</th>
                            <th width="70%">Nombre</th>
                            <th width="10%">Naturaleza</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
