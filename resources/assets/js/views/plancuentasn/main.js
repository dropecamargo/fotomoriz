/**
* Class MainPlanCuentasNView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainPlanCuentasNView = Backbone.View.extend({

        el: '#plancuentasn-main',

        /**
        * Constructor Method
        */
        initialize : function() {

            this.$plancuentasnSearchTable = this.$('#plancuentasn-search-table');
            this.$plancuentasnSearchTable.DataTable({
				processing: true,
                serverSide: true,
            	language: window.Misc.dataTableES(),
                ajax: window.Misc.urlFull( Route.route('plancuentasn.index') ),
                columns: [
                    { data: 'plancuentasn_cuenta', name: 'plancuentasn_cuenta'},
                    { data: 'plancuentasn_nombre', name: 'plancuentasn_nombre'},
                    { data: 'plancuentasn_naturaleza', name: 'plancuentasn_naturaleza'}
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('plancuentasn.show', {plancuentasn: data }) )  +'">' + data + '</a>';
                        }
                    },
                    {
                        targets: 2,
                        searchable: false,
                        render: function ( data, type, full, row ) {
                            return data == 'D' ? 'DEBITO' : 'CREDITO';
                        }
                    }
                ]
			});
        }
    });

})(jQuery, this, this.document);
