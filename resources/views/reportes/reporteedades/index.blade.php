@extends('layout.layout')

@section('title') Reporte Edades Cartera @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte Edades Cartera
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte Edades Cartera</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form action="{{ route('reporteedades.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
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