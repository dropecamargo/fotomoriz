@extends('layout.layout')

@section('title') Plan de cuentas @stop

@section('content')
    <section class="content-header">
        <h1>
			Plan de cuentas <small>Administración plan de cuentas</small>
        </h1>
        <ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
            @yield('breadcrumb')
        </ol>
    </section>

    <section class="content">
        @yield('module')
    </section>

    <script type="text/template" id="add-plancuentasn-tpl">
        <div class="row">
            <div class="form-group col-sm-2">
				<label class="control-label">Cuenta</label>
                <div><%- plancuentasn_cuenta %></div>
			</div>
            <div class="form-group col-sm-10">
                <label class="control-label">Nombre</label>
                <div><%- plancuentasn_nombre %></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label class="control-label">Naturaleza</label>
                <div><%- plancuentasn_naturaleza == 'D' ? 'DEBITO' : 'CREDITO' %></div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">Clase</label>
                <div><%- plancuentasn_clase %></div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">Grupo</label>
                <div><%- plancuentasn_grupo %></div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">Tercero</label>
                <div><%- plancuentasn_tercero ? 'SI' : 'NO' %></div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">Base</label>
                <div><%- plancuentasn_base ? 'SI' : 'NO' %></div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">Bloqueo</label>
                <div><%- plancuentasn_bloqueo ? 'SI' : 'NO' %></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label class="control-label">Concepto</label>
                <input type="text" id="plancuentasn_concepto" value="<%- plancuentasn_concepto %>" placeholder="Concepto" class="form-control input-sm input-toupper" name="plancuentasn_concepto" maxlength="25" required>
            </div>
        </div>
    </script>
@stop
