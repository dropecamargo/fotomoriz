/**
* Class AppRouter  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.AppRouter = new( Backbone.Router.extend({
        routes : {
            'login(/)': 'getLogin',

            // Routes Admin
            'tercerosinterno(/)': 'getTercerosInternoMain',
            'tercerosinterno/:tercerointerno(/)': 'getTercerosInternoShow',

            'roles(/)': 'getRolesMain',
            'roles/create(/)': 'getRolesCreate',
            'roles/:roles/edit(/)': 'getRolesEdit',

            'permisos(/)': 'getPermisosMain',
            'modulos(/)': 'getModulosMain',

            // Generar intereses
            'generarintereses(/)': 'getGenerarInteresesMain',

            'enviarintereses(/)': 'getEnviarInteresesMain',
            'enviarintereses/:enviarintereses(/)': 'getEnviarInteresesShow',

            'reporteverextractos(/)': 'getVerExtractosMain',
            'reporteverextractos/:reporteverextractos(/)': 'getVerExtractosShow',

            // Routes accounting
            'presupuestosg(/)': 'getPresupuestoGastosMain',

            'plancuentasn(/)': 'getPlanCuentasNMain',
            'plancuentasn/:plancuentasn/edit(/)': 'getPlanCuentasNEdit',
        },

        /**
        * Parse queryString to object
        */
        parseQueryString : function(queryString) {
            var params = {};
            if(queryString) {
                _.each(
                    _.map(decodeURI(queryString).split(/&/g),function(el,i){
                        var aux = el.split('='), o = {};
                        if(aux.length >= 1){
                            var val = undefined;
                            if(aux.length == 2)
                                val = aux[1];
                            o[aux[0]] = val;
                        }
                        return o;
                    }),
                    function(o){
                        _.extend(params,o);
                    }
                );
            }
            return params;
        },

        /**
        * Constructor Method
        */
        initialize : function ( opts ){
            // Initialize resources
            this.componentReporteView = new app.ComponentReporteView();
            this.componentGlobalView = new app.ComponentGlobalView();
            this.componentSearchTerceroView = new app.ComponentSearchTerceroView();
      	},

        /**
        * Start Backbone history
        */
        start: function () {
            var config = { pushState: true };

            if( document.domain.search(/(104.236.57.82|localhost)/gi) != '-1' )
                config.root = '/fotomoriz/public/';

            Backbone.history.start( config );
        },

        /**
        * show view in Calendar Event
        * @param String show
        */
        getLogin: function () {

            if ( this.loginView instanceof Backbone.View ){
                this.loginView.stopListening();
                this.loginView.undelegateEvents();
            }

            this.loginView = new app.UserLoginView( );
        },

        /**
        * show view main tercerosinterno
        */
        getTercerosInternoMain: function () {
            if ( this.mainTerceroInternoView instanceof Backbone.View ){
                this.mainTerceroInternoView.stopListening();
                this.mainTerceroInternoView.undelegateEvents();
            }

            this.mainTerceroInternoView = new app.MainTerceroInternoView( );
        },

        /**
        * show view show tercerosinterno
        */
        getTercerosInternoShow: function (tercerointerno) {
            this.tercerointernoModel = new app.TerceroInternoModel();
            this.tercerointernoModel.set({'id': tercerointerno}, {'silent':true});

            if ( this.showTerceroInternoView instanceof Backbone.View ){
                this.showTerceroInternoView.stopListening();
                this.showTerceroInternoView.undelegateEvents();
            }

            this.showTerceroInternoView = new app.ShowTerceroInternoView({ model: this.tercerointernoModel });
        },

        /**
        * show view main roles
        */
        getRolesMain: function () {

            if ( this.mainRolesView instanceof Backbone.View ){
                this.mainRolesView.stopListening();
                this.mainRolesView.undelegateEvents();
            }

            this.mainRolesView = new app.MainRolesView( );
        },

        /**
        * show view create roles
        */
        getRolesCreate: function () {
            this.rolModel = new app.RolModel();

            if ( this.createRolView instanceof Backbone.View ){
                this.createRolView.stopListening();
                this.createRolView.undelegateEvents();
            }

            this.createRolView = new app.CreateRolView({ model: this.rolModel });
            this.createRolView.render();
        },

        /**
        * show view edit roles
        */
        getRolesEdit: function (roles) {
            this.rolModel = new app.RolModel();
            this.rolModel.set({'id': roles}, {silent: true});

            if ( this.editRolView instanceof Backbone.View ){
                this.editRolView.stopListening();
                this.editRolView.undelegateEvents();
            }

            if ( this.createRolView instanceof Backbone.View ){
                this.createRolView.stopListening();
                this.createRolView.undelegateEvents();
            }

            this.editRolView = new app.EditRolView({ model: this.rolModel });
            this.rolModel.fetch();
        },

        /**
        * show main view permisos
        */
        getPermisosMain: function () {

            if ( this.mainPermisoView instanceof Backbone.View ){
                this.mainPermisoView.stopListening();
                this.mainPermisoView.undelegateEvents();
            }

            this.mainPermisoView = new app.MainPermisoView( );
        },

        /**
        * show main view modulos
        */
        getModulosMain: function () {

            if ( this.mainModuloView instanceof Backbone.View ){
                this.mainModuloView.stopListening();
                this.mainModuloView.undelegateEvents();
            }

            this.mainModuloView = new app.MainModuloView( );
        },

        /**
        * main view permisos
        */
        getGenerarInteresesMain: function () {

            if ( this.mainGenerarInteresView instanceof Backbone.View ){
                this.mainGenerarInteresView.stopListening();
                this.mainGenerarInteresView.undelegateEvents();
            }

            this.mainGenerarInteresView = new app.MainGenerarInteresView( );
        },

        /**
        * main view permisos
        */
        getEnviarInteresesMain: function () {

            if ( this.mainEnviarInteresView instanceof Backbone.View ){
                this.mainEnviarInteresView.stopListening();
                this.mainEnviarInteresView.undelegateEvents();
            }

            this.mainEnviarInteresView = new app.MainEnviarInteresView( );
        },

        /**
        * show view show generar intereses
        */
        getEnviarInteresesShow: function ( interes ) {
            this.intereses1Model = new app.Intereses1Model();
            this.intereses1Model.set( {'id': interes}, {'silent':true});

            if ( this.showEnviarInteresView instanceof Backbone.View ){
                this.showEnviarInteresView.stopListening();
                this.showEnviarInteresView.undelegateEvents();
            }

            this.showEnviarInteresView = new app.ShowEnviarInteresView({ model: this.intereses1Model });
        },
        /**
        * Main view extractos
        */
        getVerExtractosMain: function () {
            if ( this.mainVerExtractosView instanceof Backbone.View ){
                this.mainVerExtractosView.stopListening();
                this.mainVerExtractosView.undelegateEvents();
            }

            this.mainVerExtractosView = new app.MainVerExtractosView( );
        },

        getVerExtractosShow: function (reporteverextractos) {

            if ( this.showVerExtractoView instanceof Backbone.View ){
                this.showVerExtractoView.stopListening();
                this.showVerExtractoView.undelegateEvents();
            }

            this.showVerExtractoView = new app.ShowVerExtractoView({ id: reporteverextractos });
        },

        /**
        * Routes Accounting
        * View main
        */
        getPresupuestoGastosMain: function () {

            if ( this.mainPresupuestoGastosView instanceof Backbone.View ){
                this.mainPresupuestoGastosView.stopListening();
                this.mainPresupuestoGastosView.undelegateEvents();
            }

            this.mainPresupuestoGastosView = new app.MainPresupuestoGastosView( );
        },

        /**
        * View main
        */
        getPlanCuentasNMain: function () {
            if ( this.mainPlanCuentasNView instanceof Backbone.View ){
                this.mainPlanCuentasNView.stopListening();
                this.mainPlanCuentasNView.undelegateEvents();
            }

            this.mainPlanCuentasNView = new app.MainPlanCuentasNView( );
        },

        /**
        * View edit
        */
        getPlanCuentasNEdit: function (plancuentasn) {
            this.planCuentaNModel = new app.PlanCuentaNModel();
            this.planCuentaNModel.set({'id': plancuentasn}, {silent: true});

            if ( this.editPlanCuentaNView instanceof Backbone.View ){
                this.editPlanCuentaNView.stopListening();
                this.editPlanCuentaNView.undelegateEvents();
            }

            this.editPlanCuentaNView = new app.EditPlanCuentaNView({ model: this.planCuentaNModel });
            this.planCuentaNModel.fetch();
        },

    }) );

})(jQuery, this, this.document);
