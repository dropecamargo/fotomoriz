/**
* Class MainPermisoView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.MainPermisoView = Backbone.View.extend({

        el: '#permisos-main',

        /**
        * Constructor Method
        */
        initialize : function() {
            
            this.$permisosSearchTable = this.$('#permisos-search-table');
            this.$permisosSearchTable.DataTable({
				processing: true,
                serverSide: true,
            	language: window.Misc.dataTableES(),
                ajax: window.Misc.urlFull( Route.route('permisos.index') ),
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'display_name', name: 'display_name'},
                    { data: 'description', name: 'description' },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: '15%'
                    }
                ]
			});
        }
    });

})(jQuery, this, this.document);
