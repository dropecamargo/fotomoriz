/**
* Class DetalleInteresView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.DetalleInteresView = Backbone.View.extend({

        el: '#browse-interes-detalle-list',
        parameters: {
            dataFilter: {}
        },

        /**
        * Constructor Method
        */
        initialize : function(opts){
            // extends parameters
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({},this.parameters, opts.parameters);

            // References
            this.$subtotal = this.$('#subtotal');
            this.$intereses = this.$('#intereses');
            this.$totaliva = this.$('#total-iva');
            this.$total = this.$('#total');

            // Events Listeners
            this.listenTo( this.collection, 'add', this.addOne );
            this.listenTo( this.collection, 'reset', this.addAll );
            this.listenTo( this.collection, 'request', this.loadSpinner);
            this.listenTo( this.collection, 'sync', this.responseServer);

            this.collection.fetch({ data: this.parameters.dataFilter, reset: true });
        },

        /**
        * Render view contact by model
        * @param Object Model instance
        */
        addOne: function (intereses2Model) {
            var view = new app.DetalleInteresItemView({
                model: intereses2Model,
            });
            intereses2Model.view = view;
            this.$el.append( view.render().el );

            // totalize actually in collection
            this.totalize();
        },

        /**
        * Render all view Marketplace of the collection
        */
        addAll: function () {
            this.$el.find('tbody').html('');
            this.collection.forEach( this.addOne, this );
        },

        /**
        * Render totalize valores
        */
        totalize: function () {
            var data = this.collection.totalize();

            // Calcular iva y total
            var iva = data.iva * (this.parameters.dataFilter.empresa_iva/100);
            var total = iva + data.intereses;

            if(this.$subtotal.length) {
                this.$subtotal.html( window.Misc.currency( data.subtotal ) );
            }

            if(this.$intereses.length) {
                this.$intereses.html( window.Misc.currency( data.intereses ) );
            }

            if(this.$totaliva.length) {
                this.$totaliva.html( window.Misc.currency( iva ) );
            }

            if(this.$total.length) {
                this.$total.html( window.Misc.currency( total ) );
            }
        },

        /**
        * Load spinner on the request
        */
        loadSpinner: function ( target, xhr, opts ) {
            window.Misc.setSpinner( this.el );
        },

        /**
        * response of the server
        */
        responseServer: function ( target, resp, opts ) {
            window.Misc.removeSpinner( this.el );
        }
   });

})(jQuery, this, this.document);
