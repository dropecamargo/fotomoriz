/**
* Class MainPresupuestoGastosView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainPresupuestoGastosView = Backbone.View.extend({

        el: '#presupuestosg-main',
        events: {
            'click .btn-search': 'search',
            'click .btn-clear': 'clear',
            'click .btn-import-files': 'openModalImport'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            var _this = this;

            this.$presupuestogSearchTable = this.$('#presupuestog-search-table');
            this.$searchMes = this.$('#search_mes');
            this.$searchAno = this.$('#search_ano');
            this.$searchUnidad = this.$('#search_unidad');

            this.presupuestogSearchTable = this.$presupuestogSearchTable.DataTable({
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				processing: true,
                serverSide: true,
            	language: window.Misc.dataTableES(),
                ajax: {
                    url: window.Misc.urlFull( Route.route('presupuestosg.index') ),
                    data: function( data ) {
                        data.persistent = true;
                        data.search_mes = _this.$searchMes.val();
                        data.search_ano = _this.$searchAno.val();
                        data.search_unidad = _this.$searchUnidad.val();
                    }
                },
                columns: [
                    { data: 'presupuestog_mes', name: 'presupuestog_mes'},
                    { data: 'presupuestog_ano', name: 'presupuestog_ano'},
                    { data: 'unidaddecision_nombre', name: 'unidaddecision.unidaddecision_nombre'},
                    { data: 'presupuestog_nivel1', name: 'presupuestog_nivel1'},
                    { data: 'presupuestog_nivel2', name: 'presupuestog_nivel2'},
                    { data: 'presupuestog_valor', name: 'presupuestog_valor' },
                ],
                columnDefs: [
                    {
                        targets: 5,
                        className:"text-right",
                        render: function ( data ) {
                            return window.Misc.currency( data );
                        }
                    },
                ],
                order: [
                    [ 1, 'desc']
                ],
			});
        },

        search: function(e) {
            e.preventDefault();

            this.presupuestogSearchTable.ajax.reload();
        },

        clear: function(e) {
            e.preventDefault();

            this.$searchMes.val('');
            this.$searchAno.val('');
            this.$searchUnidad.val('').trigger('change');
            this.presupuestogSearchTable.ajax.reload();
        },

        openModalImport: function(e){
            e.preventDefault();

            var _this = this;

            // ImportActionView undelegateEvents
            if ( this.importActionView instanceof Backbone.View ){
                this.importActionView.stopListening();
                this.importActionView.undelegateEvents();
            }
            this.importActionView = new app.ImportDataActionView({
                parameters: {
                    title: 'presupuesto de gastos',
                    url: window.Misc.urlFull( Route.route('import.presupuestosg') ),
                    datatable: _this.presupuestogSearchTable
                }
            });
            this.importActionView.render();
        },
    });

})(jQuery, this, this.document);
