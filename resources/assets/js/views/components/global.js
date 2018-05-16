/**
* Class ComponentGlobalView  of Backbone
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ComponentGlobalView = Backbone.View.extend({

      	el: 'body',
		events: {
            'click .sidebar-toggle': 'clickSidebar',
            'hidden.bs.modal': 'multiModal',
            'click .history-back': 'clickHistoryBack'
		},

        /**
        * Constructor Method
        */
		initialize: function() {
			// Initialize
		},

		clickSidebar: function(e) {
			e.preventDefault();

			var expiration = new Date();
			expiration.setFullYear(expiration.getFullYear() + 1);

			// Create or update the cookie:
			document.cookie = "sidebar_toggle=" + (this.$el.hasClass('sidebar-collapse') ? '' : 'sidebar-collapse') + "; path=/; expires=" + expiration.toUTCString();
		},

        multiModal: function(){
            if( $('.modal.in').length > 0){
                $('body').addClass('modal-open');
            }
        },

        clickHistoryBack: function(e) {
			e.preventDefault();

			window.history.back();
		}
    });


})(jQuery, this, this.document);
