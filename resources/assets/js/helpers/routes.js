(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["POST"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\AuthController@postLogin"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\AuthController@getLogin"},{"host":null,"methods":["GET","HEAD"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\AuthController@getLogout"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"dashboard","action":"App\Http\Controllers\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/dv","name":"terceros.dv","action":"App\Http\Controllers\Admin\TerceroController@dv"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/rcree","name":"terceros.rcree","action":"App\Http\Controllers\Admin\TerceroController@rcree"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/search","name":"terceros.search","action":"App\Http\Controllers\Admin\TerceroController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/contactos","name":"terceros.contactos.index","action":"App\Http\Controllers\Admin\ContactoController@index"},{"host":null,"methods":["POST"],"uri":"terceros\/contactos","name":"terceros.contactos.store","action":"App\Http\Controllers\Admin\ContactoController@store"},{"host":null,"methods":["PUT"],"uri":"terceros\/contactos\/{contactos}","name":"terceros.contactos.update","action":"App\Http\Controllers\Admin\ContactoController@update"},{"host":null,"methods":["PATCH"],"uri":"terceros\/contactos\/{contactos}","name":null,"action":"App\Http\Controllers\Admin\ContactoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"empresa","name":"empresa.index","action":"App\Http\Controllers\Admin\EmpresaController@index"},{"host":null,"methods":["PUT"],"uri":"empresa\/{empresa}","name":"empresa.update","action":"App\Http\Controllers\Admin\EmpresaController@update"},{"host":null,"methods":["PATCH"],"uri":"empresa\/{empresa}","name":null,"action":"App\Http\Controllers\Admin\EmpresaController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros","name":"terceros.index","action":"App\Http\Controllers\Admin\TerceroController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/create","name":"terceros.create","action":"App\Http\Controllers\Admin\TerceroController@create"},{"host":null,"methods":["POST"],"uri":"terceros","name":"terceros.store","action":"App\Http\Controllers\Admin\TerceroController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/{terceros}","name":"terceros.show","action":"App\Http\Controllers\Admin\TerceroController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/{terceros}\/edit","name":"terceros.edit","action":"App\Http\Controllers\Admin\TerceroController@edit"},{"host":null,"methods":["PUT"],"uri":"terceros\/{terceros}","name":"terceros.update","action":"App\Http\Controllers\Admin\TerceroController@update"},{"host":null,"methods":["PATCH"],"uri":"terceros\/{terceros}","name":null,"action":"App\Http\Controllers\Admin\TerceroController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"actividades","name":"actividades.index","action":"App\Http\Controllers\Admin\ActividadController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"actividades\/create","name":"actividades.create","action":"App\Http\Controllers\Admin\ActividadController@create"},{"host":null,"methods":["POST"],"uri":"actividades","name":"actividades.store","action":"App\Http\Controllers\Admin\ActividadController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"actividades\/{actividades}","name":"actividades.show","action":"App\Http\Controllers\Admin\ActividadController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"actividades\/{actividades}\/edit","name":"actividades.edit","action":"App\Http\Controllers\Admin\ActividadController@edit"},{"host":null,"methods":["PUT"],"uri":"actividades\/{actividades}","name":"actividades.update","action":"App\Http\Controllers\Admin\ActividadController@update"},{"host":null,"methods":["PATCH"],"uri":"actividades\/{actividades}","name":null,"action":"App\Http\Controllers\Admin\ActividadController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"departamentos","name":"departamentos.index","action":"App\Http\Controllers\Admin\DepartamentoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"departamentos\/{departamentos}","name":"departamentos.show","action":"App\Http\Controllers\Admin\DepartamentoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"municipios","name":"municipios.index","action":"App\Http\Controllers\Admin\MunicipioController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sucursales","name":"sucursales.index","action":"App\Http\Controllers\Admin\SucursalController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sucursales\/create","name":"sucursales.create","action":"App\Http\Controllers\Admin\SucursalController@create"},{"host":null,"methods":["POST"],"uri":"sucursales","name":"sucursales.store","action":"App\Http\Controllers\Admin\SucursalController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"sucursales\/{sucursales}","name":"sucursales.show","action":"App\Http\Controllers\Admin\SucursalController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"sucursales\/{sucursales}\/edit","name":"sucursales.edit","action":"App\Http\Controllers\Admin\SucursalController@edit"},{"host":null,"methods":["PUT"],"uri":"sucursales\/{sucursales}","name":"sucursales.update","action":"App\Http\Controllers\Admin\SucursalController@update"},{"host":null,"methods":["PATCH"],"uri":"sucursales\/{sucursales}","name":null,"action":"App\Http\Controllers\Admin\SucursalController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"puntosventa","name":"puntosventa.index","action":"App\Http\Controllers\Admin\PuntoVentaController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"puntosventa\/create","name":"puntosventa.create","action":"App\Http\Controllers\Admin\PuntoVentaController@create"},{"host":null,"methods":["POST"],"uri":"puntosventa","name":"puntosventa.store","action":"App\Http\Controllers\Admin\PuntoVentaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"puntosventa\/{puntosventa}","name":"puntosventa.show","action":"App\Http\Controllers\Admin\PuntoVentaController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"puntosventa\/{puntosventa}\/edit","name":"puntosventa.edit","action":"App\Http\Controllers\Admin\PuntoVentaController@edit"},{"host":null,"methods":["PUT"],"uri":"puntosventa\/{puntosventa}","name":"puntosventa.update","action":"App\Http\Controllers\Admin\PuntoVentaController@update"},{"host":null,"methods":["PATCH"],"uri":"puntosventa\/{puntosventa}","name":null,"action":"App\Http\Controllers\Admin\PuntoVentaController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"documentos","name":"documentos.index","action":"App\Http\Controllers\Contabilidad\DocumentoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"documentos\/create","name":"documentos.create","action":"App\Http\Controllers\Contabilidad\DocumentoController@create"},{"host":null,"methods":["POST"],"uri":"documentos","name":"documentos.store","action":"App\Http\Controllers\Contabilidad\DocumentoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"documentos\/{documentos}","name":"documentos.show","action":"App\Http\Controllers\Contabilidad\DocumentoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"documentos\/{documentos}\/edit","name":"documentos.edit","action":"App\Http\Controllers\Contabilidad\DocumentoController@edit"},{"host":null,"methods":["PUT"],"uri":"documentos\/{documentos}","name":"documentos.update","action":"App\Http\Controllers\Contabilidad\DocumentoController@update"},{"host":null,"methods":["PATCH"],"uri":"documentos\/{documentos}","name":null,"action":"App\Http\Controllers\Contabilidad\DocumentoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"folders","name":"folders.index","action":"App\Http\Controllers\Contabilidad\FolderController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"folders\/create","name":"folders.create","action":"App\Http\Controllers\Contabilidad\FolderController@create"},{"host":null,"methods":["POST"],"uri":"folders","name":"folders.store","action":"App\Http\Controllers\Contabilidad\FolderController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"folders\/{folders}","name":"folders.show","action":"App\Http\Controllers\Contabilidad\FolderController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"folders\/{folders}\/edit","name":"folders.edit","action":"App\Http\Controllers\Contabilidad\FolderController@edit"},{"host":null,"methods":["PUT"],"uri":"folders\/{folders}","name":"folders.update","action":"App\Http\Controllers\Contabilidad\FolderController@update"},{"host":null,"methods":["PATCH"],"uri":"folders\/{folders}","name":null,"action":"App\Http\Controllers\Contabilidad\FolderController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"centroscosto","name":"centroscosto.index","action":"App\Http\Controllers\Contabilidad\CentroCostoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"centroscosto\/create","name":"centroscosto.create","action":"App\Http\Controllers\Contabilidad\CentroCostoController@create"},{"host":null,"methods":["POST"],"uri":"centroscosto","name":"centroscosto.store","action":"App\Http\Controllers\Contabilidad\CentroCostoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"centroscosto\/{centroscosto}","name":"centroscosto.show","action":"App\Http\Controllers\Contabilidad\CentroCostoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"centroscosto\/{centroscosto}\/edit","name":"centroscosto.edit","action":"App\Http\Controllers\Contabilidad\CentroCostoController@edit"},{"host":null,"methods":["PUT"],"uri":"centroscosto\/{centroscosto}","name":"centroscosto.update","action":"App\Http\Controllers\Contabilidad\CentroCostoController@update"},{"host":null,"methods":["PATCH"],"uri":"centroscosto\/{centroscosto}","name":null,"action":"App\Http\Controllers\Contabilidad\CentroCostoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas\/nivel","name":"plancuentas.nivel","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@nivel"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas\/search","name":"plancuentas.search","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas","name":"plancuentas.index","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas\/create","name":"plancuentas.create","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@create"},{"host":null,"methods":["POST"],"uri":"plancuentas","name":"plancuentas.store","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas\/{plancuentas}","name":"plancuentas.show","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"plancuentas\/{plancuentas}\/edit","name":"plancuentas.edit","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@edit"},{"host":null,"methods":["PUT"],"uri":"plancuentas\/{plancuentas}","name":"plancuentas.update","action":"App\Http\Controllers\Contabilidad\PlanCuentasController@update"},{"host":null,"methods":["PATCH"],"uri":"plancuentas\/{plancuentas}","name":null,"action":"App\Http\Controllers\Contabilidad\PlanCuentasController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/detalle","name":"asientos.detalle.index","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@index"},{"host":null,"methods":["POST"],"uri":"asientos\/detalle","name":"asientos.detalle.store","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@store"},{"host":null,"methods":["DELETE"],"uri":"asientos\/detalle\/{detalle}","name":"asientos.detalle.destroy","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/exportar\/{asientos}","name":"asientos.exportar","action":"App\Http\Controllers\Contabilidad\AsientoController@exportar"},{"host":null,"methods":["POST"],"uri":"asientos\/detalle\/evaluate","name":"asientos.detalle.evaluate","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@evaluate"},{"host":null,"methods":["POST"],"uri":"asientos\/detalle\/validate","name":"asientos.detalle.validate","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@validation"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/detalle\/movimientos","name":"asientos.detalle.movimientos","action":"App\Http\Controllers\Contabilidad\DetalleAsientoController@movimientos"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos","name":"asientos.index","action":"App\Http\Controllers\Contabilidad\AsientoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/create","name":"asientos.create","action":"App\Http\Controllers\Contabilidad\AsientoController@create"},{"host":null,"methods":["POST"],"uri":"asientos","name":"asientos.store","action":"App\Http\Controllers\Contabilidad\AsientoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/{asientos}","name":"asientos.show","action":"App\Http\Controllers\Contabilidad\AsientoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"asientos\/{asientos}\/edit","name":"asientos.edit","action":"App\Http\Controllers\Contabilidad\AsientoController@edit"},{"host":null,"methods":["PUT"],"uri":"asientos\/{asientos}","name":"asientos.update","action":"App\Http\Controllers\Contabilidad\AsientoController@update"},{"host":null,"methods":["PATCH"],"uri":"asientos\/{asientos}","name":null,"action":"App\Http\Controllers\Contabilidad\AsientoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"rmayorbalance","name":"rmayorbalance.index","action":"App\Http\Controllers\Reporte\MayorBalanceController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"rplancuentas","name":"rplancuentas.index","action":"App\Http\Controllers\Reporte\PlanCuentasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"productos\/search","name":"productos.search","action":"App\Http\Controllers\Inventario\ProductoController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"productos\/rollos","name":"productos.rollos.index","action":"App\Http\Controllers\Inventario\ProdbodeRolloController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"productos","name":"productos.index","action":"App\Http\Controllers\Inventario\ProductoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"productos\/create","name":"productos.create","action":"App\Http\Controllers\Inventario\ProductoController@create"},{"host":null,"methods":["POST"],"uri":"productos","name":"productos.store","action":"App\Http\Controllers\Inventario\ProductoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"productos\/{productos}","name":"productos.show","action":"App\Http\Controllers\Inventario\ProductoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"productos\/{productos}\/edit","name":"productos.edit","action":"App\Http\Controllers\Inventario\ProductoController@edit"},{"host":null,"methods":["PUT"],"uri":"productos\/{productos}","name":"productos.update","action":"App\Http\Controllers\Inventario\ProductoController@update"},{"host":null,"methods":["PATCH"],"uri":"productos\/{productos}","name":null,"action":"App\Http\Controllers\Inventario\ProductoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"grupos","name":"grupos.index","action":"App\Http\Controllers\Inventario\GrupoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"grupos\/create","name":"grupos.create","action":"App\Http\Controllers\Inventario\GrupoController@create"},{"host":null,"methods":["POST"],"uri":"grupos","name":"grupos.store","action":"App\Http\Controllers\Inventario\GrupoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"grupos\/{grupos}","name":"grupos.show","action":"App\Http\Controllers\Inventario\GrupoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"grupos\/{grupos}\/edit","name":"grupos.edit","action":"App\Http\Controllers\Inventario\GrupoController@edit"},{"host":null,"methods":["PUT"],"uri":"grupos\/{grupos}","name":"grupos.update","action":"App\Http\Controllers\Inventario\GrupoController@update"},{"host":null,"methods":["PATCH"],"uri":"grupos\/{grupos}","name":null,"action":"App\Http\Controllers\Inventario\GrupoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"subgrupos","name":"subgrupos.index","action":"App\Http\Controllers\Inventario\SubGrupoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"subgrupos\/create","name":"subgrupos.create","action":"App\Http\Controllers\Inventario\SubGrupoController@create"},{"host":null,"methods":["POST"],"uri":"subgrupos","name":"subgrupos.store","action":"App\Http\Controllers\Inventario\SubGrupoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"subgrupos\/{subgrupos}","name":"subgrupos.show","action":"App\Http\Controllers\Inventario\SubGrupoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"subgrupos\/{subgrupos}\/edit","name":"subgrupos.edit","action":"App\Http\Controllers\Inventario\SubGrupoController@edit"},{"host":null,"methods":["PUT"],"uri":"subgrupos\/{subgrupos}","name":"subgrupos.update","action":"App\Http\Controllers\Inventario\SubGrupoController@update"},{"host":null,"methods":["PATCH"],"uri":"subgrupos\/{subgrupos}","name":null,"action":"App\Http\Controllers\Inventario\SubGrupoController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"unidades","name":"unidades.index","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"unidades\/create","name":"unidades.create","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@create"},{"host":null,"methods":["POST"],"uri":"unidades","name":"unidades.store","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"unidades\/{unidades}","name":"unidades.show","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"unidades\/{unidades}\/edit","name":"unidades.edit","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@edit"},{"host":null,"methods":["PUT"],"uri":"unidades\/{unidades}","name":"unidades.update","action":"App\Http\Controllers\Inventario\UnidadesMedidaController@update"},{"host":null,"methods":["PATCH"],"uri":"unidades\/{unidades}","name":null,"action":"App\Http\Controllers\Inventario\UnidadesMedidaController@update"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // Route.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // Route.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // Route.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // Route.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // Route.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // Route.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.Route = laroute;
    }

}).call(this);

