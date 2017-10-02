/**
* Class ConfirmWindow
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ConfirmWindow = Backbone.View.extend({

        el: '#modal-confirm-component',
        parameters: {
            template: null,
            titleConfirm: '',
            onConfirm: null,
            callBack: null,
            dataFilter: {}
        },

        /**
        * Constructor Method
        */
        initialize : function(opts) {

            // extends attributtes
            if( opts != undefined && _.isObject(opts.parameters) )
                this.parameters = _.extend({}, this.parameters, opts.parameters );

        },

        /*
        * Render View Element
        */
        render: function() {
            var attributes = {};

            // Extend attributes confirm window
           	_.extend(attributes, this.parameters.dataFilter);
            this.$el.find('.content-modal').html( this.parameters.template(attributes) );

            // Change modal title
            this.$el.find('.inner-title-modal').html( this.parameters['titleConfirm'] );
			this.$el.modal('show');

            // delegate events
	        $(this.el).off('click', '.confirm-action');
            this.undelegateEvents();
            this.delegateEvents({ 'click .confirm-action': 'onConfirm' });

            return this;
        },

        /**
        * Confirm
        */
        onConfirm: function (e) {

            e.preventDefault();
            var _this = this;

            this.$el.modal('hide');

            if( typeof this.parameters.onConfirm == 'function' ) {
                this.parameters.onConfirm.call(null, this.parameters.dataFilter );
            }
        }
   });

})(jQuery, this, this.document);
