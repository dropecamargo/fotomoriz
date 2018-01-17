@extends('reports.receivable.reporteverextractos.main')

@section('breadcrumb')
    <li class="active">Extractos</li>
@stop

@section('module')
    <div id="verextractos-main">
        <div class="box box-danger">
            <div class="box-body table-responsive">
                <table id="extractos-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Directorio</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
