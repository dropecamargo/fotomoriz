/**
* Class CreatePermisoRolView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.CreatePermisoRolView = Backbone.View.extend({

        el: '#modal-permisorol-component',
        template: _.template( ($('#edit-permissions-tpl').html() || '') ),
        events: {
            'submit #form-permisorol-component': 'onStore'
        },
        parameters: {
        	permissions : [],
            dataFilter: {}
        },

        /**
        * Constructor Method
        */
        initialize : function(opts) {
            // extends parameters
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({},this.parameters, opts.parameters);

            this.$el.find('.inner-title-modal').empty().html( this.model.get('display_name') );
            this.$wraperContent = this.$el.find('.modal-body');

            // Events
            this.listenTo( this.model, 'sync', this.responseServer );
            this.listenTo( this.model, 'request', this.loadSpinner );
        },

        /*
        * Render View Element
        */
        render: function(){
            // Attributes
            var attributes = this.model.toJSON();
            attributes.permissions = this.parameters.permissions;

            this.$el.find('.content-modal').empty().html( this.template( attributes ) );

            // to fire plugins
            this.ready();

            this.$el.modal('show');

            return this;
        },

        /**
        * fires libraries js
        */
        ready: function () {
            if( typeof window.initComponent.initICheck == 'function' )
                window.initComponent.initICheck();
        },

        /**
        * Event Create Contact
        */
        onStore: function (e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                var data = window.Misc.formToJson( e.target );
                data.role_id = this.parameters.dataFilter.role_id;

                this.model.save( data, {patch: true} );
            }
        },

        /**
        * Load spinner on the request
        */
        loadSpinner: function (model, xhr, opts) {
            window.Misc.setSpinner( this.$wraperContent );
        },

        /**
        * response of the server
        */
        responseServer: function ( model, resp, opts ) {
            window.Misc.removeSpinner( this.$wraperContent );

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

                this.collection.fetch({ data: this.parameters.dataFilter, reset: true });

            	this.$el.modal('hide');
            }
        }
    });

})(jQuery, this, this.document);
