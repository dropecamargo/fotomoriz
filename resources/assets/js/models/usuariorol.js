/**
* Class UsuarioRolModel extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.UsuarioRolModel = Backbone.Model.extend({
        urlRoot: function () {
            return window.Misc.urlFull( Route.route('tercerosinterno.roles.index') );
        },
        idAttribute: 'id',
        defaults: {
        	'user_id': '',
        	'role_id': ''
        }
    });

})(this, this.document);
