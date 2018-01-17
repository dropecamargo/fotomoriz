/**
* Class ComponentSearchTerceroView  of Backbone
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ComponentSearchTerceroView = Backbone.View.extend({

      	el: 'body',
        template: _.template( ($('#koi-search-tercero-component-tpl').html() || '') ),
		events: {
			'change input.tercero-koi-component': 'terceroChanged',
            'click .btn-koi-search-tercero-component-table': 'searchTercero',
            'click .btn-search-koi-search-tercero-component': 'search',
            'click .btn-clear-koi-search-tercero-component': 'clear',
            'click .a-koi-search-tercero-component-table': 'setTercero'
		},

        /**
        * Constructor Method
        */
		initialize: function() {
			// Initialize
            this.$modalComponent = this.$('#modal-search-component');
		},

		searchTercero: function(e) {
            e.preventDefault();
            var _this = this;

            // Render template
            this.$modalComponent.find('.content-modal').html( this.template({ }) );

            // References
            this.$searchNit = this.$('#koi_search_tercero_nit');
            this.$searchName = this.$('#koi_search_tercero_nombre');

            this.$tercerosSearchTable = this.$modalComponent.find('#koi-search-tercero-component-table');
			this.$inputContent = this.$("#"+$(e.currentTarget).attr("data-field"));
            this.$inputName = this.$("#"+this.$inputContent.attr("data-name"));

            this.tercerosSearchTable = this.$tercerosSearchTable.DataTable({
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                language: window.Misc.dataTableES(),
                ajax: {
                    url: window.Misc.urlFull( Route.route('terceros.index') ),
                    data: function( data ) {
                        data.tercero_nit = _this.$searchNit.val();
                        data.tercero_nombre = _this.$searchName.val();
                    }
                },
                columns: [
                    { data: 'tercero_nit', name: 'tercero_nit' },
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
                        searchable: false,
                        render: function ( data, type, full, row ) {
                            return '<a href="#" class="a-koi-search-tercero-component-table">' + data + '</a>';
                        }
                    },
                    {
                        targets: 1,
                        width: '85%',
                        searchable: false
                    },
                    {
                        targets: [2, 3, 4, 5, 6],
                        visible: false,
                        searchable: false
                    }
                ]
            });

            // Modal show
            this.ready();
            this.$modalComponent.modal('show');
        },

        setTercero: function(e) {
            e.preventDefault();

            var data = this.tercerosSearchTable.row( $(e.currentTarget).parents('tr') ).data();

            this.$inputContent.val( data.tercero_nit );
            this.$inputName.val( data.tercero_nombre );

            this.$modalComponent.modal('hide');
        },

        search: function(e) {
            e.preventDefault();

            this.tercerosSearchTable.ajax.reload();
        },

        clear: function(e) {
            e.preventDefault();

            this.$searchNit.val('');
            this.$searchName.val('');

            this.tercerosSearchTable.ajax.reload();
        },

        terceroChanged: function(e) {
            var _this = this;

            this.$inputContent = $(e.currentTarget);
            this.$inputName = this.$("#"+$(e.currentTarget).attr("data-name"));
            this.$wraperConten = this.$("#"+$(e.currentTarget).attr("data-wrapper"));

            var tercero = this.$inputContent.val();

            // Before eval clear data
            this.$inputName.val('');

            if(!_.isUndefined(tercero) && !_.isNull(tercero) && tercero != '') {
                // Get tercero
                $.ajax({
                    url: window.Misc.urlFull(Route.route('terceros.search')),
                    type: 'GET',
                    data: { tercero_nit: tercero },
                    beforeSend: function() {
                        _this.$inputName.val('');
                        window.Misc.setSpinner( _this.$wraperConten );
                    }
                })
                .done(function(resp) {
                    window.Misc.removeSpinner( _this.$wraperConten );
                    if(resp.success) {
                        if(!_.isUndefined(resp.tercero_nombre) && !_.isNull(resp.tercero_nombre)){
                            _this.$inputName.val(resp.tercero_nombre);
                        }
                    }
	            })
	            .fail(function(jqXHR, ajaxOptions, thrownError) {
	                window.Misc.removeSpinner( _this.$wraperConten );
	                alertify.error(thrownError);
	            });
	     	}
		},

        /**
        * fires libraries js
        */
        ready: function () {
            // to fire plugins
            if( typeof window.initComponent.initToUpper == 'function' )
                window.initComponent.initToUpper();
        }
    });


})(jQuery, this, this.document);
