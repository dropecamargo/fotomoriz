/**
* Init Class
*/

/*global*/
var app = app || {};

(function ($, window, document, undefined) {

    var InitComponent = function() {

        //Init Attributes
        $.ajaxSetup({
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    String.prototype.replaceAll = function(search, replace)
    {
        if(!replace)
            return this;
        return this.replace(new RegExp('[' + search + ']', 'g'), replace);
    };

    InitComponent.prototype = {

        /**
        * Constructor or Initialize Method
        */
        initialize: function () {
            //Initialize
            this.initApp();
            this.initICheck();
            this.initAlertify();
            this.initSelect2();
            this.initToUpper();
            this.initInputMask();
            this.initDatePicker();
        },

        /**
        * Init Backbone Application
        */
        initApp: function () {
            window.app.AppRouter.start();
        },

        /**
        * Init icheck
        */
        initICheck: function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
        },

        /**
        * Init alertify
        */
        initAlertify: function () {
            alertify.logPosition("bottom right");
        },

        /**
        * Init select2
        */
        initSelect2: function () {
            $('.select2-default').select2({ language: 'es', placeholder: 'Seleccione', allowClear: false });
            $('.select2-default-clear').select2({ language: 'es', placeholder: 'Seleccione', allowClear: true });
        },

        /**
        * Init toUpper
        */
        initToUpper: function () {
            $('.input-toupper').keyup(function(){
                $(this).val( $(this).val().toUpperCase() );
            });
        },

        /**
        * Init inputMask
        */
        initInputMask: function () {

            $("[data-mask]").inputmask();

            $("[data-currency]").inputmask({
                radixPoint: ",",
                alias: 'currency',
                removeMaskOnSubmit: true,
                unmaskAsNumber: true,
                min: 0
            });
        },

        /**
        * Init initValidator
        */
        initValidator: function () {

            $('form[data-toggle="validator"]').each(function () {
                var $form = $(this)
                $.fn.validator.call($form, $form.data())
            })
        },

        /**
        * Init Datepicker
        */
        initDatePicker: function () {

            $('.datepicker').datepicker({
                autoclose: true,
                language: 'es',
                format: 'yyyy-mm-dd'
            });
        }
    };

    //Init App Components
    //-----------------------
    $(function() {
        window.initComponent = new InitComponent();
        window.initComponent.initialize();
    });

})(jQuery, this, this.document);