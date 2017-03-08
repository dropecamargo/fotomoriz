/**
* Class ComponentReporteView  of Backbone
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ComponentReporteView = Backbone.View.extend({

      	el: 'body',
		events: {
			'click .btn-export-pdf-koi-component': 'onPdf',
			'click .btn-export-xls-koi-component': 'onXls',
		},

        /**
        * Constructor Method
        */
		initialize: function() {
			// Initialize
		},

		onPdf: function(e) {
			this.$("#type-reporte-koi-component").val('pdf');
		},

		onXls: function(e) {
			this.$("#type-reporte-koi-component").val('xls');
		}
    });


})(jQuery, this, this.document);
