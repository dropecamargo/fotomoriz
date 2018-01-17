/**
* Class MainVerExtractosView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainVerExtractosView = Backbone.View.extend({

        el: '#verextractos-main',

        /**
        * Constructor Method
        */
        initialize : function() {
            this.$extractosSearchTable = this.$('#extractos-search-table');

            this.$extractosSearchTable.DataTable({
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                language: window.Misc.dataTableES(),
                ajax: window.Misc.urlFull( Route.route('reporteverextractos.index') ),
                columns: [
                    { data: 'name', name: 'name' }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('reporteverextractos.show', {reporteverextractos: full.name }) ) +'"> <i class="fa fa-folder-open"></i> ' + full.name + '</a>';
                        }
                    }
                ]
            });
        }
    });

})(jQuery, this, this.document);
