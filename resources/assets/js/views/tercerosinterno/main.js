/**
* Class MainTerceroInternoView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainTerceroInternoView = Backbone.View.extend({

        el: '#tercerosinterno-main',
        events: {
            'click .btn-search': 'search',
            'click .btn-clear': 'clear'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            var _this = this;

            // Rerefences
            this.$tercerosSearchTable = this.$('#tercerosinterno-search-table');
            this.$searchCodigo = this.$('#tercerointerno_codigo');
            this.$searchName = this.$('#tercerointerno_nombre');

            this.tercerosSearchTable = this.$tercerosSearchTable.DataTable({
				dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				processing: true,
                serverSide: true,
            	language: window.Misc.dataTableES(),
                ajax: {
                    url: window.Misc.urlFull( Route.route('tercerosinterno.index') ),
                    data: function( data ) {
                        data.persistent = true;
                        data.tercerointerno_codigo = _this.$searchCodigo.val();
                        data.tercerointerno_nombre = _this.$searchName.val();
                    }
                },
                columns: [
                    { data: 'tercerointerno_codigo', name: 'tercerointerno_codigo' },
                    { data: 'tercero_nombre', name: 'tercero_nombre' },
                    { data: 'tercero_razon_social', name: 'tercero_razon_social'},
                    { data: 'tercero_nombre1', name: 'tercero_nombre1' },
                    { data: 'tercero_nombre2', name: 'tercero_nombre2' },
                    { data: 'tercero_apellido1', name: 'tercero_apellido1' },
                    { data: 'tercero_apellido2', name: 'tercero_apellido2' }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: '15%',
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('tercerosinterno.show', {tercerosinterno: full.tercerointerno_codigo }) )  +'">' + data + '</a>';
                        }
                    },
                    {
                        targets: 1,
                        width: '85%',
                        searchable: false
                    },
                    {
                        targets: [2, 3, 4, 5, 6],
                        visible: false
                    }
                ]
			});
        },

        search: function(e) {
            e.preventDefault();

            this.tercerosSearchTable.ajax.reload();
        },

        clear: function(e) {
            e.preventDefault();

            this.$searchCodigo.val('');
            this.$searchName.val('');

            this.tercerosSearchTable.ajax.reload();
        },
    });

})(jQuery, this, this.document);
