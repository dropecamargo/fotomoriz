@extends('layout.layout')

@section('title') Roles @stop

@section('content')
    @yield ('module')

    <script type="text/template" id="add-rol-tpl">
        <section class="content-header">
            <h1>
                Roles <small>Administración de roles</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{trans('app.home')}}</a></li>
                <li><a href="{{ route('roles.index')}}">Rol</a></li>
                <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                    <li><a href="<%- window.Misc.urlFull( Route.route('roles.show', { roles: id}) ) %>"><%- id %></a></li>
                    <li class="active">Editar</li>
                <% }else{ %>
                    <li class="active">Nuevo</li>
                <% } %>
            </ol>
        </section>

        <section class="content">
            <div class="box box-success" id="spinner-main">
                <div class="box-body">
                    <form method="POST" accept-charset="UTF-8" id="form-roles" data-toggle="validator">
                        <div class="row">
                            <label for="display_name" class="col-sm-1 control-label">Nombre</label>
                            <div class="form-group col-sm-3">
                                <input id="display_name" value="<%- display_name %>" placeholder="Mostrar nombre" class="form-control input-sm" name="display_name" required>
                            </div>

                            <label for="name" class="col-sm-1 control-label">Key</label>
                            <div class="form-group col-sm-4">
                                <input id="name" value="<%- name %>" placeholder="Nombre" class="form-control input-sm" name="name" <%- typeof(id) !== 'undefined' ? 'readonly' : ''%> >
                            </div>
                        </div>
                        <div class="row">
                            <label for="display_name" class="col-sm-1 control-label">Descripcion</label>
                            <div class="form-group col-sm-8">
                                <textarea id="description" name="description" class="form-control" rows="2" placeholder="Descripcion"><%- description %></textarea>
                            </div>
                        </div>

                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-2 col-md-offset-4 col-sm-6 col-xs-6">
                                    <a href="<%- window.Misc.urlFull( (typeof(id) !== 'undefined' && !_.isUndefined(id) && !_.isNull(id) && id != '') ? Route.route('roles.show', { roles: id}) : Route.route('roles.index') ) %>" class="btn btn-default btn-sm btn-block">{{ trans('app.cancel') }}</a>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">{{ trans('app.save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </script>

    <!-- Modal add permisorol -->
    <div class="modal fade" id="modal-permisorol-component" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="content-permisorol-component">
                <div class="modal-header small-box {{ config('koi.template.bg') }}">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="inner-title-modal"></h4>
                </div>
                {!! Form::open(['id' => 'form-permisorol-component', 'data-toggle' => 'validator']) !!}
                    <div class="content-modal"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Continuar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script type="text/template" id="permissions-rol-list-tpl">
        <th><a href="#" class="btn-set-permission" data-father="<%- father %>" data-resource="<%- id %>"><%- display_name %></a></th>
        <% _.each(permissions, function(permission) { %>
            <td class="text-center">
                <span class="label label-<%- mpermissions.indexOf(permission.id) != -1 ? 'success' : 'danger'  %>">
                    <i class="fa fa-fw fa-<%- mpermissions.indexOf(permission.id) != -1 ? 'check' : 'close'  %>"></i>
                </span>
            </td>
        <% }); %>
    </script>

    <script type="text/template" id="edit-permissions-tpl">
        <div class="table-responsive no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <% _.each(permissions, function(permission) { %>
                            <th class="text-center"><%- permission.display_name %></th>
                        <% }); %>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <% _.each(permissions, function(permission) { %>
                            <td class="text-center">
                                <input type="checkbox" id="permiso_<%- permission.id %>" name="permiso_<%- permission.id %>" value="permiso_<%- permission.id %>" <%- mpermissions.indexOf(permission.id) != -1 ? 'checked': ''%>>
                            </td>
                        <% }); %>
                    </tr>
                </tbody>
            </table>
        </div>
    </script>
@stop
