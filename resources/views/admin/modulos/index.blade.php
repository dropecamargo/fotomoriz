@extends('layout.layout')

@section('title') Modulo @stop

@section('content')
    <section class="content-header">
		<h1>
			Modulos <small>Administración de modulo</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Modulo</li>
		</ol>
    </section>

	<section class="content">
		<div id="modulos-main">
			<div class="box box-danger">
				<div class="box-body table-responsive">
					<table id="modulos-search-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Nombre</th>
				                <th>Key</th>
				            </tr>
				        </thead>
				    </table>
				</div>
			</div>
		</div>
    </section>
@stop
