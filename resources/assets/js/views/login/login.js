/**
* Class AppRouter  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.UserLoginView = Backbone.View.extend({

        el: '#login-box', 

        /**
        * Constructor Method
        */
        initialize : function() {
 			//console.log('initialize UserLoginView');
            //Init Attributes 
            this.$loginForm = $('#form-login-account');
            this.$loginForm.validator();
        },

        /*
        * Render View Element
        */
        render: function(){
            //
        }
    });


})(jQuery, this, this.document);
