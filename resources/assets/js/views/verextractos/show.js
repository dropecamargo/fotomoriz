/**
* Class ShowVerExtractoView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ShowVerExtractoView = Backbone.View.extend({

        el: '#verextractos-show',

        /**
        * Constructor Method
        */
        initialize : function(opts) {
            this.$extractosSearchTable = this.$('#extractos-files-search-table');

            this.$extractosSearchTable.DataTable({
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                language: window.Misc.dataTableES(),
                ajax: window.Misc.urlFull( Route.route('reporteverextractos.show', {reporteverextractos: opts.id }) ),
                columns: [
                    { data: 'name', name: 'name' }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ full.url +'" target="_blank"> <i class="fa fa-file-pdf-o"></i> ' + full.name + '</a>';
                        }
                    }
                ]
            });
        }
    });

})(jQuery, this, this.document);
