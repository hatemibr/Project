/**
 * Created by khanh on 1/10/2017.
 */
(function ($) {
    "use strict"; // Start of use strict

    function autocomplete_taxonomy() {
        $('.fastshop_vc_taxonomy').each(function () {
            if( $(this).length > 0){
                $(this).chosen();
            }
        })
    }
    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function() {
        autocomplete_taxonomy();
        $(document).on('change','.fastshop_select_preview',function(){
            var url = $(this).find(':selected').data('img');
            $(this).parent('.container-select_preview').find('.image-preview img').attr('src',url);
        });
    });
    $(document).ajaxComplete(function (event, xhr, settings) {
        autocomplete_taxonomy();
    });

    /* ---------------------------------------------
     Scripts initialization
     --------------------------------------------- */
    $(window).load(function () {

    });
    $(window).bind("load", function () {

    });
})(jQuery); // End of use strict