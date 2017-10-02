/**
* Class RolModel extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.RolModel = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('roles.index') );
        },
        idAttribute: 'id',
        defaults: {
            'name': '',
            'display_name': '',
            'description': '',
            'permissions': []
        }
    });

})(this, this.document);
