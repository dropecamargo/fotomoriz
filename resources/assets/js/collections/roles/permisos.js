/**
* Class PermisosRolList of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.PermisosRolList = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('roles.permisos.index') );
        },
        model: app.ModuloModel,

        /**
        * Constructor Method
        */
        initialize : function(){
        }
   });

})(this, this.document);
