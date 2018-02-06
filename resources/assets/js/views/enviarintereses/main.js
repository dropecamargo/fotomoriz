/**
* Class MainEnviarInteresView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainEnviarInteresView = Backbone.View.extend({

        el: '#enviarintereses-main',
        events: {
            'click .btn-search': 'search',
            'click .btn-clear': 'clear',
            'ifToggled .select-all': 'selectAll',
            'ifChanged .checkboxes-table': 'estadoChecks',
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            var _this = this;
            this.$enviarinteresesSearchTable = this.$('#enviarintereses-search-table');
            this.$selectAll = this.$('.select-all');

            // References
            this.$searchinteresNumero = this.$('#searchinteres_numero');
            this.$searchinteresTercero = this.$('#searchinteres_tercero');
            this.$searchinteresTerceroNombre = this.$('#searchinteres_tercero_nombre');
            this.$searchinteresMes = this.$('#searchinteres_mes');
            this.$searchinteresAno = this.$('#searchinteres_ano');

            this.enviarinteresesSearchTable = this.$enviarinteresesSearchTable.DataTable({
                dom: "<'row'<'col-sm-4'B><'col-sm-4 text-center'l>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                deferRender: true,
                serverSide: true,
                language: window.Misc.dataTableES(),
                ajax: {
                    url: window.Misc.urlFull( Route.route('enviarintereses.index') ),
                    data: function(data){
                            data.persistent = true,
                            data.intereses1_numero = _this.$searchinteresNumero.val();
                            data.intereses1_mes = _this.$searchinteresMes.val();
                            data.intereses1_ano = _this.$searchinteresAno.val();
                            data.tercero_nit = _this.$searchinteresTercero.val();
                            data.tercero_nombre = _this.$searchinteresTerceroNombre.val();
                    },
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'intereses1_numero', name: 'intereses1_numero' },
                    { data: 'sucursal_nombre', name: 'sucursal_nombre' },
                    { data: 'tercero_nombre', name: 'tercero_nombre' },
                    { data: 'intereses1_tasa', name: 'intereses1_tasa' },
                    { data: 'intereses1_dias_gracia', name: 'intereses1_dias_gracia' },
                    { data: 'intereses1_fecha', name: 'intereses1_fecha' },
                    { data: 'intereses1_usuario_elaboro', name: 'intereses1_usuario_elaboro' },
                    { data: 'intereses1_enviado', name: 'intereses1_enviado' }
                ],
                order: [
                	[ 1, 'desc', 6, 'desc' ]
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: '1%',
                        orderable: false,
                        searchable: false,
                        render: function ( data, full, row ) {
                            return "<input type='checkbox' name='id_"+ data +"' class='checkboxes-table' value='"+ data +"'/>";
                        }
                    },
                    {
                        targets: 1,
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('enviarintereses.show', {enviarintereses: full.id }) )  +'">' + data + '</a>';
                        }
                    },
                    {
                        targets: 8,
                        render: function ( data, type, full, row ) {
                            return data ? 'Si' : 'No';
                        }
                    }
                ],
                buttons: [
                    {
                        text: 'Enviar',
                        className: 'enviar',
                        action: function () {
                            var array = _this.enviarinteresesSearchTable.$('input[type="checkbox"]');

                            if( _this.enviarinteresesSearchTable.$('input[type="checkbox"]').is(':checked') ){
                                _this.confimSend( array );
                            }
                        }
                    }
                ],
                fnRowCallback: function( row, data ) {
                    if ( !data.intereses1_anulado ) {
                        $(row).css( {"color":"#00a65a"} );
                    }else {
                        $(row).css( {"color":"red"} );
                    }
                }
            });

            this.enviarinteresesSearchTable.on( 'draw', function () {
                _this.$selectAll.iCheck('uncheck');
                _this.ready();
            });

        },

        selectAll: function( e ) {
            // // Get all rows with search applied
            var rows = this.enviarinteresesSearchTable.rows({ 'search': 'applied' }).nodes();

            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.$(e.currentTarget).is(':checked')).iCheck('update');
        },

        estadoChecks: function( e ){
            // If checkbox is not checked
           if( !this.$(e.currentTarget).is(':checked') ){

              if( this.$selectAll.is(':checked') ){
                  this.$selectAll.iCheck('indeterminate');
              }
           }
        },

        confimSend: function( array ){
            var _this = this;
            var datos = window.Misc.formToJson( array );

            var cancelConfirm = new window.app.ConfirmWindow({
                parameters: {
                    template: _.template( ($('#interes-enviar-confirm-tpl').html() || '') ),
                    titleConfirm: 'Enviar intereses',
                    onConfirm: function () {
                        // Enviar intereses
                        $.ajax({
                            url: window.Misc.urlFull( Route.route('enviarintereses.enviar', datos) ),
                            type: 'GET',
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

                                window.Misc.successRedirect( resp.msg, window.Misc.urlFull( Route.route('enviarintereses.index') ) );
                            }
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError) {
                            window.Misc.removeSpinner( _this.el );
                            alertify.error(thrownError);
                        });
                    }
                }
            });

            cancelConfirm.render();
        },

        search: function(e) {
            e.preventDefault();

            this.enviarinteresesSearchTable.ajax.reload();
        },

        clear: function(e) {
            e.preventDefault();

            this.$searchinteresNumero.val('');
            this.$searchinteresTercero.val('');
            this.$searchinteresTerceroNombre.val('');
            this.$searchinteresMes.val('');
            this.$searchinteresAno.val('');

            this.enviarinteresesSearchTable.page.len( 10 ).draw();
            this.enviarinteresesSearchTable.ajax.reload();
        },

        /**
        * fires libraries js
        */
        ready: function () {
            // to fire plugins
            if( typeof window.initComponent.initICheck == 'function' )
                window.initComponent.initICheck();
        }
    });

})(jQuery, this, this.document);
