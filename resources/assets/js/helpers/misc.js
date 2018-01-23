/**
 * Utilities for seg component
 *
 *
 */
( function( $, window, document, undefined ){

    var Misc = function( a ){

        // attributes or global vars here

    }

    Misc.prototype = {

        /**
        * Inializes the functions when DOM ready
        */
        initialize: function(){

        },

        /**
         *  Serialize form into json format
         *
         *  @param { string } name class or id of the html element to embed the loader
         *  @return { object } form into json
         *
         */

        formToJson: function( selector ){
            var o = {}, a = [];
            if( $.prototype.isPrototypeOf(selector) ){
                a = selector.serializeArray();
            }
            else{
                a = $(selector).serializeArray();
            }

            $.each( a, function() {
                if ( o[ this.name ] !== undefined ) {
                    if ( ! o[this.name].push ) {
                        o[ this.name ] = [ o[ this.name ] ];
                    }

                    o[ this.name ].push( this.value || '' );

                } else {
                    o[ this.name ] = this.value || '';
                }
            });

            return o;
        },

        /**
        * validate the urls
        */
        isUrl : function( str ){

            // var patt = /^(http[s]?:\/\/(www\.)?|ftp:\/\/(www\.)?|www\.){1}([0-9A-Za-z-\.@:%_\+~#=]+)+\.[a-zA-Z]{2,3}(\/([^\n\r\s])*)?(\?([^\n\r\s])*)?/i;
            var patt = /^(http[s]?:\/\/(www\.)?|ftp:\/\/(www\.)?|www\.){1}([0-9A-Za-z-\.@:%_\+~#=]+)+(\/(.)*)?(\?(.)*)?/i;

            return patt.test( str );
        },

        /**
        * Build URI with route and base url
        */
        urlFull: function ( route ){

            if( !this.isUrl(document.url) )
                return route;

            route || (route = '');
            var patt = /^\//;
            patt.test(route) || (route = '/'+route);

            return document.url + route;
        },

        /**
        * Build URI with route and base url
        */
        parseErrors: function ( errors ){
            var text = '';
            if( _.isObject( errors ) ){

                var listError = '<ul>';

                $.each(errors, function(field, item) {
                     listError += '<li>'+ item[0] +'</li>';
                });
                listError += '</ul>';

                text = listError;
            }
            return text;
        },

        /**
        *  Sets a loading spinner in a box
        * @param { selector } String|Object Selector jQuery
        */
        setSpinner: function( selector ){
            if ( !selector ) return;

            $(selector).prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        },

        /**
        * Removes the loading spinner and trigger a callback
        * @param { wrap } String|Object wrapper jQuery element
        *
        */
        removeSpinner: function( selector ){

            var $selector = $(selector).find( '.overlay' );

            if($selector.length)
                $selector.remove();
        },

        /**
        * Display the DataTables interface in Spanish
        */
        dataTableES: function(){
            return {
                "sProcessing":     "<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        },

        /**
        * Format COP currency
        * @param { value }
        *
        */
        currency: function( value ){
            return accounting.formatMoney(value, '', 2, ".", ",");
        },

        /**
        * Redirect to an specific url or refresh the page
        * @param { string } the url to be redirect to
        *
        */
        redirect: function( url ){
            if( url !== undefined && url != ''){
                window.location = url;
            }else{
                window.location.reload();
            }
        },

        /**
        * Redirect to an specific url or refresh the page
        * @param { string } the url to be redirect to
        *
        */
        successRedirect: function( msg, url ){
            alertify.success(msg);
            setTimeout(function(){
                window.Misc.redirect( url );
            }, 500);
        },

    };

    window.Misc = new Misc();
    window.Misc.initialize();

})( jQuery, this, this.document );
