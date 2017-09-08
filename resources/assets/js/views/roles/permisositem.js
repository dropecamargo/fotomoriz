/**
* Class PermisosRolItemView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.PermisosRolItemView = Backbone.View.extend({

        tagName: 'tr',
        template: _.template( ($('#permissions-rol-list-tpl').html() || '') ),
        parameters: {
            father: null,
            permissions: [],
            edit: false
        },

        /**
        * Constructor Method
        */
        initialize: function(opts){
            // Extends parameters
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({},this.parameters, opts.parameters);

            // Events Listener
            this.listenTo( this.model, 'change', this.render );
        },

        /*
        * Render View Element
        */
        render: function(){
            var attributes = this.model.toJSON();
            attributes.edit = this.parameters.edit;
            attributes.father = this.parameters.father;
            attributes.permissions = this.parameters.permissions;

            this.$el.html( this.template(attributes) );
            return this;
        }
    });

})(jQuery, this, this.document);