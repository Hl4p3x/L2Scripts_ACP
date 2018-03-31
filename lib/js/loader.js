/**
 * Created by пользователь on 06.07.2015.
 */
(function( $ ){
    $.fn.loader = function(action) {
        if(action == 'show'){
            this.html('<img id="loader" src="/media/loader/loader.gif"/>');
        }
        if(action == 'hide'){
            $('#loader').detach();
        }
    };
})( jQuery );