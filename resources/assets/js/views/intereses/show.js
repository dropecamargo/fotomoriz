/**
* Class ShowEnviarInteresView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ShowEnviarInteresView = Backbone.View.extend({

        el: '#enviarintereses-show',
        /**
        * Constructor Method
        */
        initialize : function() {
            console.log('bitch');
            // Reference views
            // this.referenceViews();

        },

    //     /**
    //     * reference to views
    //     */
    //     referenceViews: function () {
    //         // Contact list
    //         this.contactsListView = new app.ContactsListView( {
    //             collection: this.contactsList,
    //             parameters: {
    //                 dataFilter: {
    //                     tercero_id: this.model.get('id')
    //                 }
    //            }
    //         });
    //     }

    });

})(jQuery, this, this.document);
