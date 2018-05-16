/**
* Class ShowTerceroInternoView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ShowTerceroInternoView = Backbone.View.extend({

        el: '#tercerosinterno-show',
        events: {
            'submit #form-item-roles': 'onStoreRol'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            // Model exist
            this.rolList = new app.RolList();

            // Reference views
            this.referenceViews();
        },

        /**
        * reference to views
        */
        referenceViews: function () {
            // Rol list
            this.rolesListView = new app.RolesListView( {
                collection: this.rolList,
                parameters: {
                    edit: true,
                    wrapper: this.$('#wrapper-roles'),
                    dataFilter: {
                        tercerointerno_id: this.model.get('id')
                    }
               }
            });
        },

        /**
        * Event add item rol
        */
        onStoreRol: function (e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                // Prepare global data
                var data = window.Misc.formToJson( e.target );
                this.rolList.trigger( 'store', data );
            }
        },
    });

})(jQuery, this, this.document);
