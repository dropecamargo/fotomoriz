@extends('layout.layout')

@section('title') Reporte sabana de ventas costos @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte sabana de ventas costos
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte sabana de ventas costos</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-danger" id="reporte-create">
	    	<form {{-- action="route('reportesabanacobros.index')" method="GET" --}} data-toggle="validator">
			 	<input class="hidden" id="type-reporte-koi-component" name="type"></input>
				<div class="box-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <select class="form-control select2-default" name="filtersucursales[]" multiple="multiple" data-placeholder="Sucursales">
                                <option value="0">Todas</option>
                                @foreach( App\Models\Base\Sucursal::getSucursalesCommercial() as $key => $value )
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div class="row">
						<div class="form-group col-md-offset-1 col-xs-6 col-sm-3 col-md-2">
							<label for="filtermesi" class="control-label">Mes inicial</label>
	                        <select name="filtermesi" id="filtermesi" class="form-control" required>
								@foreach( config('koi.meses') as $key => $value)
									<option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="filteranoi" class="control-label">Año inicial</label>
	                        <select name="filteranoi" id="filteranoi" class="form-control" required>
								@for($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>

						<div class="form-group col-md-offset-2 col-xs-6 col-sm-3 col-md-2">
							<label for="filtermesf" class="control-label">Mes final</label>
	                        <select name="filtermesf" id="filtermesf" class="form-control" required>
								@foreach( config('koi.meses') as $key => $value)
									<option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="filteranof" class="control-label">Año final</label>
	                        <select name="filteranof" id="filteranof" class="form-control" required>
								@for($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
							<button type="submit" class="btn btn-default btn-sm btn-block btn-export-pdf-koi-component">
								<i class="fa fa-file-text-o"></i> {{ trans('app.pdf') }}
							</button>
						</div>
					</div>
				</div>
			</form>
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
	</section>
@stop
