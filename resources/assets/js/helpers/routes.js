(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["POST"],"uri":"auth\/login","name":"auth.login","action":"App\Http\Controllers\Auth\LoginController@postLogin"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/logout","name":"auth.logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/integrate","name":"auth.integrate","action":"App\Http\Controllers\Auth\LoginController@integrate"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"dashboard","action":"App\Http\Controllers\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros\/search","name":"terceros.search","action":"App\Http\Controllers\Admin\TerceroController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"terceros","name":"terceros.index","action":"App\Http\Controllers\Admin\TerceroController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"tercerosinterno\/roles","name":"tercerosinterno.roles.index","action":"App\Http\Controllers\Admin\UsuarioRolController@index"},{"host":null,"methods":["POST"],"uri":"tercerosinterno\/roles","name":"tercerosinterno.roles.store","action":"App\Http\Controllers\Admin\UsuarioRolController@store"},{"host":null,"methods":["DELETE"],"uri":"tercerosinterno\/roles\/{roles}","name":"tercerosinterno.roles.destroy","action":"App\Http\Controllers\Admin\UsuarioRolController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"tercerosinterno","name":"tercerosinterno.index","action":"App\Http\Controllers\Admin\TerceroInternoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"tercerosinterno\/{tercerosinterno}","name":"tercerosinterno.show","action":"App\Http\Controllers\Admin\TerceroInternoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/permisos","name":"roles.permisos.index","action":"App\Http\Controllers\Admin\PermisoRolController@index"},{"host":null,"methods":["PUT","PATCH"],"uri":"roles\/permisos\/{permisos}","name":"roles.permisos.update","action":"App\Http\Controllers\Admin\PermisoRolController@update"},{"host":null,"methods":["DELETE"],"uri":"roles\/permisos\/{permisos}","name":"roles.permisos.destroy","action":"App\Http\Controllers\Admin\PermisoRolController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"roles","name":"roles.index","action":"App\Http\Controllers\Admin\RolController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/create","name":"roles.create","action":"App\Http\Controllers\Admin\RolController@create"},{"host":null,"methods":["POST"],"uri":"roles","name":"roles.store","action":"App\Http\Controllers\Admin\RolController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{roles}","name":"roles.show","action":"App\Http\Controllers\Admin\RolController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{roles}\/edit","name":"roles.edit","action":"App\Http\Controllers\Admin\RolController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"roles\/{roles}","name":"roles.update","action":"App\Http\Controllers\Admin\RolController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"permisos","name":"permisos.index","action":"App\Http\Controllers\Admin\PermisoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"modulos","name":"modulos.index","action":"App\Http\Controllers\Admin\ModuloController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"generarintereses","name":"generarintereses.index","action":"App\Http\Controllers\Receivable\GenerarInteresController@index"},{"host":null,"methods":["POST"],"uri":"generarintereses","name":"generarintereses.store","action":"App\Http\Controllers\Receivable\GenerarInteresController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses\/enviar","name":"enviarintereses.enviar","action":"App\Http\Controllers\Receivable\EnviarInteresController@enviar"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses\/anular\/{enviarinteres}","name":"enviarintereses.anular","action":"App\Http\Controllers\Receivable\EnviarInteresController@anular"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses\/exportar\/{enviarinteres}","name":"enviarintereses.exportar","action":"App\Http\Controllers\Receivable\EnviarInteresController@exportar"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses\/detalle","name":"enviarintereses.detalle.index","action":"App\Http\Controllers\Receivable\DetalleEnviarInteresController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"amortizaciones","name":"amortizaciones.index","action":"App\Http\Controllers\Receivable\AmortizacionCreditoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses","name":"enviarintereses.index","action":"App\Http\Controllers\Receivable\EnviarInteresController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"enviarintereses\/{enviarintereses}","name":"enviarintereses.show","action":"App\Http\Controllers\Receivable\EnviarInteresController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"rintereses","name":"rintereses.index","action":"App\Http\Controllers\Report\ReporteInteresesGeneradosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reportefacturaselectronicas","name":"reportefacturaselectronicas.index","action":"App\Http\Controllers\Report\ReporteFelFacturasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteedades","name":"reporteedades.index","action":"App\Http\Controllers\Report\ReporteEdadesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteposfechados","name":"reporteposfechados.index","action":"App\Http\Controllers\Report\ReportePosFechadosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporterecibos","name":"reporterecibos.index","action":"App\Http\Controllers\Report\ReporteRecibosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteresumencobro","name":"reporteresumencobro.index","action":"App\Http\Controllers\Report\ReporteResumenCobroController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteverextractos","name":"reporteverextractos.index","action":"App\Http\Controllers\Report\ReporteVerExtractoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteverextractos\/{reporteverextractos}","name":"reporteverextractos.show","action":"App\Http\Controllers\Report\ReporteVerExtractoController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"reportesabanacobros","name":"reportesabanacobros.index","action":"App\Http\Controllers\Report\ReporteSabanaCostoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"presupuestosg\/exportar","name":"presupuestosg.exportar","action":"App\Http\Controllers\Accounting\PresupuestoGastoController@exportar"},{"host":null,"methods":["GET","HEAD"],"uri":"presupuestosg","name":"presupuestosg.index","action":"App\Http\Controllers\Accounting\PresupuestoGastoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reportearp","name":"reportearp.index","action":"App\Http\Controllers\Report\ReporteArpController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteentradassalidas","name":"reporteentradassalidas.index","action":"App\Http\Controllers\Report\ReporteEntradasSalidasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"reporteanalisisinventario","name":"reporteanalisisinventario.index","action":"App\Http\Controllers\Report\ReporteAnalisisInventarioController@index"},{"host":null,"methods":["POST"],"uri":"import\/presupuestog","name":"import.presupuestosg","action":"App\Http\Controllers\Accounting\PresupuestoGastoController@import"}],
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

