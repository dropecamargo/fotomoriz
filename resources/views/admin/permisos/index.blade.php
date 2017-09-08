@extends('layout.layout')

@section('title') Permisos @stop

@section('content')
    <section class="content-header">
		<h1>
			Permisos <small>Administración de permisos</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Permisos</li>
		</ol>
    </section>

	<section class="content">
		<div id="permisos-main">
			<div class="box box-danger">
				<div class="box-body table-responsive">
					<table id="permisos-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th width="25%">Nombre</th>
				                <th width="25%">Key</th>
				                <th width="50%">Descripcion</th>
				            </tr>
				        </thead>
				    </table>
				</div>
			</div>
		</div>
    </section>    
@stop
