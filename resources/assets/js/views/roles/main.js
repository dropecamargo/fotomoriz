/**
* Class MainRolesView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainRolesView = Backbone.View.extend({

        el: '#roles-main',

        /**
        * Constructor Method
        */
        initialize : function() {

            this.$rolesSearchTable = this.$('#roles-search-table');
            this.$rolesSearchTable.DataTable({
                dom: "<'row'<'col-sm-4'B><'col-sm-4 text-center'l><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                language: window.Misc.dataTableES(),
                ajax: window.Misc.urlFull( Route.route('roles.index') ),
                columns: [
                    { data: 'display_name', name: 'display_name' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' }
                ],
                buttons: [
                    {
                        text: '<i class="fa fa-plus"></i> Nuevo Rol',
                        className: 'btn-sm',
                        action: function ( e, dt, node, config ) {
                            window.Misc.redirect( window.Misc.urlFull( Route.route('roles.create') ) )
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: '25%',
                        render: function ( data, type, full, row ) {
                            return '<a href="'+ window.Misc.urlFull( Route.route('roles.show', {roles: full.id }) )  +'">' + data + '</a>';
                        }
                    }
                ]
            });
        }
    });

})(jQuery, this, this.document);
