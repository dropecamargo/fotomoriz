/**
* Class TerceroInternoModel extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.TerceroInternoModel = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('tercerosinterno.index') );
        },
        idAttribute: 'id',
        defaults: {
        }
    });

})(this, this.document);
