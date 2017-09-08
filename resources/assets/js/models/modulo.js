/**
* Class ModuloModel extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.ModuloModel = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('roles.permisos.index') );
        },
        idAttribute: 'id',
        defaults: {
            'name': '',
            'display_name': '',
            'nivel1': '',
            'nivel2': '',
            'nivel3': '',
            'nivel4': ''
        }
    });

})(this, this.document);
