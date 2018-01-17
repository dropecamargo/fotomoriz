/**
* Class Intereses1Model extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.Intereses1Model = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('generarintereses.index') );
        },
        defaults: {

        }
    });

})(this, this.document);
