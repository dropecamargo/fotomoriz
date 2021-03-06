/**
* Class ShowEnviarInteresView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ShowEnviarInteresView = Backbone.View.extend({

        el: '#enviarintereses-show',
        events: {
            'click .anular-interes': 'amularInteres',
            'click .export-interes': 'exportInteres'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            this.detalleInteresList = new app.Intereses2List();

            // Recuperar empresa IVA
            this.empresaiva = this.$('#empresa_iva').val();

            // Reference views
            this.referenceViews();

        },

        amularInteres: function( e ){
            e.preventDefault();

            var _this = this;
            var cancelConfirm = new window.app.ConfirmWindow({
                parameters: {
                    template: _.template( ($('#interes-anular-confirm-tpl').html() || '') ),
                    titleConfirm: 'Anular interés',
                    onConfirm: function () {
                        // Close cotizacion
                        $.ajax({
                            url: window.Misc.urlFull( Route.route('enviarintereses.anular', { enviarinteres: _this.model.get('id') }) ),
                            type: 'GET',
                            beforeSend: function() {
                                window.Misc.setSpinner( _this.spinner );
                            }
                        })
                        .done(function(resp) {
                            window.Misc.removeSpinner( _this.spinner );

                            if(!_.isUndefined(resp.success)) {
                                // response success or error
                                var text = resp.success ? '' : resp.errors;
                                if( _.isObject( resp.errors ) ) {
                                    text = window.Misc.parseErrors(resp.errors);
                                }

                                if( !resp.success ) {
                                    alertify.error(text);
                                    return;
                                }

                                window.Misc.successRedirect( resp.msg, window.Misc.urlFull( Route.route('enviarintereses.show', { enviarintereses: _this.model.get('id') }) ) );
                            }
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError) {
                            window.Misc.removeSpinner( _this.spinner );
                            alertify.error(thrownError);
                        });
                    }
                }
            });

            cancelConfirm.render();
        },

        /**
        * export to PDF
        */
        exportInteres: function (e) {
            e.preventDefault();

            // Redirect to pdf
            window.open( window.Misc.urlFull( Route.route('enviarintereses.exportar', { enviarinteres: this.model.get('id') })) );
        },

        /**
        * reference to views
        */
        referenceViews: function () {
            // Contact list
            this.detalleInteresView = new app.DetalleInteresView( {
                collection: this.detalleInteresList,
                parameters: {
                    dataFilter: {
                        interes: this.model.get('id'),
                        empresa_iva: parseInt(this.empresaiva)
                    }
               }
            });
        }
    });
})(jQuery, this, this.document);
