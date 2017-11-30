/**
* Class MainInteresView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainInteresView = Backbone.View.extend({

        el: '#intereses-main',
        events: {
            'click .submit-interes': 'submitForm',
            'submit #form-intereses': 'onStore',
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            this.$form = this.$('#form-intereses');
            this.ready();
        },

        /**
        * Event submit form
        */
        submitForm: function(e) {
            this.$form.submit();
        },

        /**
        * Event store form
        */
        onStore: function(e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();
                var _this = this,
                    data = window.Misc.formToJson( e.target );

                $.ajax({
                    url: window.Misc.urlFull( Route.route('intereses.index') ),
                    type: 'GET',
                    data: {
                        data: data
                    },
                    beforeSend: function() {
                        window.Misc.setSpinner( _this.el );
                    }
                })
                .done(function(resp) {
                    window.Misc.removeSpinner( _this.el );


                    // response success or error
                    var text = resp.success ? '' : resp.errors;
                    if( _.isObject( resp.errors ) ) {
                        text = window.Misc.parseErrors(resp.errors);
                    }

                    if( !resp.success ) {
                        alertify.error(text);
                        return;
                    }

                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    window.Misc.removeSpinner( _this.spinner );
                    alertify.error(thrownError);
                });
            }
        },

        /**
        * fires libraries js
        */
        ready: function () {
            // to fire plugins
            if( typeof window.initComponent.initValidator == 'function' )
                window.initComponent.initValidator();

            if( typeof window.initComponent.initSpinner == 'function' )
                window.initComponent.initSpinner();

            if( typeof window.initComponent.initDatePicker == 'function' )
                window.initComponent.initDatePicker();
        },
    });

})(jQuery, this, this.document);
