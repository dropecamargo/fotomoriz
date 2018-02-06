/**
* Class MainGenerarInteresView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainGenerarInteresView = Backbone.View.extend({

        el: '#intereses-main',
        events: {
            'click .sumbit-generarintereses': 'sumbitIntereses',
            'submit #form-generar-intereses': 'onStore',
        },

        /**
        * Constructor Method
        */
        initialize : function(opts) {
            // Initialize
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({}, this.parameters, opts.parameters);

            this.$form = this.$('#form-generar-intereses');
            this.ready();
        },

        sumbitIntereses: function(e) {
            this.$form.submit();
        },

        /**
        * Event store presupuestoasesor
        */
        onStore: function (e) {
            var _this = this;

            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                var data = window.Misc.formToJson( e.target );

                $.ajax({
                    type: "POST",
                    url: window.Misc.urlFull( Route.route('generarintereses.store') ),
                    data: data,
                    beforeSend: function() {
                        window.Misc.setSpinner( _this.el );
                    }
                })
                .done(function(resp) {
                    window.Misc.removeSpinner( _this.el );

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
                    }

                    window.Misc.successRedirect( resp.msg, window.Misc.urlFull(Route.route('generarintereses.index')) );
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    window.Misc.removeSpinner( _this.el );
                    alertify.error(thrownError);
                });
            }
        },

        /**
        * fires libraries js
        */
        ready: function () {
            // to fire plugins
            if( typeof window.initComponent.initToUpper == 'function' )
                window.initComponent.initToUpper();

            if( typeof window.initComponent.initValidator == 'function' )
                window.initComponent.initValidator();

            if( typeof window.initComponent.initDatePicker == 'function' )
                window.initComponent.initDatePicker();
        }
    });

})(jQuery, this, this.document);
