/**
* Class ImportDataActionView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ImportDataActionView = Backbone.View.extend({

        el: 'body',
        template: _.template( ($('#import-data-tpl').html() || '') ),
        events: {
            'click .btn-import': 'submitForm',
            'submit #form-import-component': 'onStore'
        },

        /**
        * Constructor Method
        */
        initialize : function( opts ) {
            // Extends parameters
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({}, this.parameters, opts.parameters);

            // Reference fields
            this.$modal = $('#modal-import-file-component');
            this.$modal.find('.modal-title').text( 'Importando ' + this.parameters.title );
            this.$modal.find('.content-modal').empty().html( this.template({title: this.parameters.title}) );

            // Export file
            if (this.parameters.call == 'presupuesto') {
                this.$modal.find('.content-modal').append('<a href="'+ window.Misc.urlFull( Route.route('presupuestosg.exportar') ) +'">Descargar formato</a>');
            }

            this.$wrapper = this.$('#modal-wrapper-import-file');
            this.$form = $('#form-import-component');

            this.$modal.modal('show');
            this.ready();
        },

        /*
        * Submit form on file
        */
        submitForm: function(e) {
            this.$form.submit();
        },

        /*
        * On store file
        */
        onStore: function(e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                var _this = this;
                    formData =  new FormData( e.target );

                $.ajax({
                    url: _this.parameters.url,
                    data: formData,
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        window.Misc.setSpinner( _this.$wrapper );
                    }
                })
                .done(function(resp) {
                    window.Misc.removeSpinner( _this.$wrapper );
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

                        _this.$modal.modal('hide');
                        alertify.success(resp.msg);
                        _this.parameters.datatable.ajax.reload();
                    }
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    window.Misc.removeSpinner( _this.$wrapper );
                    alertify.error(thrownError);
                });
            }
        },

    	/**
        * fires libraries js
        */
        ready: function () {
            // to fire plugin
            if( typeof window.initComponent.initValidator == 'function' )
                window.initComponent.initValidator();

            if( typeof window.initComponent.initSelectFile == 'function' )
                window.initComponent.initSelectFile();

            if( typeof window.initComponent.initSelect2 == 'function' )
                window.initComponent.initSelect2();
        },
    });

})(jQuery, this, this.document);
