/**
* Class Intereses2List of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.Intereses2List = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('enviarintereses.detalle.index') );
        },
        model: app.Intereses2Model,

        /**
        * Constructor Method
        */
        initialize : function(){
        },

        subtotal: function() {
            return this.reduce(function(sum, model) {
                return sum + parseFloat( model.get('intereses2_saldo') )
            }, 0);
        },

        intereses: function() {
            return this.reduce(function(sum, model) {
                return sum + parseFloat( model.get('intereses2_interes') )
            }, 0);
        },

        iva: function() {
            return this.reduce(function(sum, model) {
                return sum + (((model.get('intereses2_saldo') - model.get('factura1_iva'))*model.get('intereses1_tasa')/100)/30)*model.get('intereses2_dias_a_cobrar')
            }, 0);
        },

        totalize: function() {
            var subtotal = this.subtotal();
            var intereses = this.intereses();
            var iva = this.iva();
            return { 'subtotal': subtotal, 'intereses': intereses, 'iva': iva }
        },
   });

})(this, this.document);
