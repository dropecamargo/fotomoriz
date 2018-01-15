@extends('layout.layout')

@section('title') Ver extractos @stop

@section('content')
    <section class="content-header">
		<h1>
			Ver extractos
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Ver extractos</li>
		</ol>
    </section>

   	<section class="content">
        <div id="verextractos-main">
            <div class="box box-danger">
                <div class="box-body table-responsive">
                    <table id="extractos-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
	</section>
@stop
