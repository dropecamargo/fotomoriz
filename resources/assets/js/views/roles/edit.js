/**
* Class EditRolView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.EditRolView = Backbone.View.extend({

        el: '#rol-create',
        template: _.template( ($('#add-rol-tpl').html() || '') ),
        events: {
            'submit #form-roles': 'onStore',
            'click .toggle-children': 'toggleChildren',
            'click .btn-set-permission': 'changePermissions'
        },
        parameters: {
        },

        /**
        * Constructor Method
        */
        initialize : function(opts) {
            // Initialize
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({}, this.parameters, opts.parameters);

            // Attributes
            this.stuffToDo = { };
            this.stuffToVw = { };

            // Events
            this.listenTo( this.model, 'change', this.render );
            this.listenTo( this.model, 'sync', this.responseServer );
            this.listenTo( this.model, 'request', this.loadSpinner );
        },

        /*
        * Render View Element
        */
        render: function() {
            var attributes = this.model.toJSON();
                attributes.edit = true;
            this.$el.html( this.template(attributes) );

            this.spinner = this.$('#spinner-main');
        },

        /**
        * Event Create Folder
        */
        onStore: function (e) {
            if (!e.isDefaultPrevented()) {

                e.preventDefault();
                var data = window.Misc.formToJson( e.target );
                this.model.save( data, {patch: true, silent: true} );
            }
        },

        /**
        * Event toggle children
        */
        toggleChildren: function (e) {
            e.preventDefault();

            var resource = $(e.currentTarget).attr("data-resource"),
                father = $(e.currentTarget).attr("data-father"),
                nivel1 = $(e.currentTarget).attr("data-nivel1"),
                nivel2 = $(e.currentTarget).attr("data-nivel2"),
                _this = this;

            if ( (this.stuffToVw[resource] instanceof Backbone.View) == false )
            {
                this.stuffToDo[resource] = new app.PermisosRolList();
                this.stuffToVw[resource] = new app.PermisosRolListView({
                    el: '#wrapper-permisions-'+resource,
                    collection: this.stuffToDo[resource],
                    parameters: {
                        wrapper: this.$('#wrapper-father-'+father),
                        permissions: this.model.get('permissions'),
                        father: resource,
                        dataFilter: {
                            'role_id': this.model.get('id'),
                            'nivel1': nivel1,
                            'nivel2': nivel2
                        }
                   }
                });
            }

        },

        changePermissions: function(e) {
            e.preventDefault();

            var resource = $(e.currentTarget).attr("data-resource"),
                father = $(e.currentTarget).attr("data-father"),
                collection = this.stuffToDo[father],
                model = collection.get(resource),
                _this = this;

            if ( this.createPermisoRolView instanceof Backbone.View ){
                this.createPermisoRolView.stopListening();
                this.createPermisoRolView.undelegateEvents();
            }

            this.createPermisoRolView = new app.CreatePermisoRolView({
                model: model,
                collection: collection,
                parameters: {
                    permissions: this.model.get('permissions'),
                    dataFilter: {
                        'role_id': this.model.get('id'),
                        'nivel1': model.get('nivel1'),
                        'nivel2': model.get('nivel2')
                    }
                }
            });
            this.createPermisoRolView.render();
        },

        /**
        * Load spinner on the request
        */
        loadSpinner: function (model, xhr, opts) {
            window.Misc.setSpinner( this.spinner );
        },

        /**
        * response of the server
        */
        responseServer: function ( model, resp, opts ) {
            window.Misc.removeSpinner( this.spinner );

            if(!_.isUndefined(resp.success)) {
                // response success or error
                var text = resp.success ? '' : resp.errors;
                if( _.isObject( resp.errors ) ) {
                    text = window.Misc.parseErrors(resp.errors);
                }

                if( !resp.success ) {
                    alertify.error(text);
                    return;
                }

                // Redirect to edit rol
                window.Misc.redirect( window.Misc.urlFull( Route.route('roles.edit', { roles: resp.id}) ) );
            }
        }
    });

})(jQuery, this, this.document);
