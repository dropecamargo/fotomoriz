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
            'ifClicked #select_all': 'selectAll'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            var _this = this;
            this.$enviarinteresesSearchTable = this.$('#enviarintereses-search-table');

            // References
            this.$searchinteresNumero = this.$('#searchinteres_numero');
            this.$searchinteresTercero = this.$('#searchinteres_tercero');
            this.$searchinteresTerceroNombre = this.$('#searchinteres_tercero_nombre');
            this.$searchinteresMes = this.$('#searchinteres_mes');
            this.$searchinteresAno = this.$('#searchinteres_ano');

            this.enviarinteresesSearchTable = this.$enviarinteresesSearchTable.DataTable({
                dom: "<'row'<'col-sm-4'><'col-sm-4 text-center'l>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
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
                    { data: 'intereses1_numero', name: 'intereses1_numero' },
                    { data: 'intereses1_numero', name: 'intereses1_numero' },
                    { data: 'sucursal_nombre', name: 'sucursal_nombre' },
                    { data: 'tercero_nombre', name: 'tercero_nombre' },
                    { data: 'intereses1_tasa', name: 'intereses1_tasa' },
                    { data: 'intereses1_dias_gracia', name: 'intereses1_dias_gracia' },
                    { data: 'intereses1_fecha', name: 'intereses1_fecha' },
                    { data: 'intereses1_usuario_elaboro', name: 'intereses1_usuario_elaboro' }
                ],
                order: [
                	[ 6, 'desc' ]
                ],
                select: {
                    style: 'os',
                },
                columnDefs: [
                    {
                        targets:   0,
                        width: '1%',
                        orderable: false,
                        searchable: false,
                        render: function ( data, full, row ) {
                            return "<input type='checkbox' name='id[]' value="+ full.interes_codigo +"'/>";
                        }
                    },
                    {
                        targets: 1,
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('enviarintereses.show', {enviarintereses: full.interes_codigo }) )  +'">' + data + '</a>';
                        }
                    }
                ],
                fnRowCallback: function( row, data ) {
                    if ( parseInt(data.intereses1_enviado) ) {
                        $(row).css( {"color":"#00a65a"} );
                    }
                }
            });

            this.enviarinteresesSearchTable.on( 'draw', function () {
                _this.ready();
            });
        },

        selectAll: function( e ) {

            console.log( this.$(e.currentTarget).iCheck('toggle') );
            // $('input[type="checkbox"]', rows).iCheck('checked');
            this.enviarinteresesSearchTable.rows().select();
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
