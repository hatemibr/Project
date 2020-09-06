(function ($) {
    "use strict"; // Start of use strict
    /* CUSTOM VARIATIONS */
    function variations_custom() {
        $('.variations_form').find('.data-val').html('');
        $('.variations_form select').each(function () {
            var _this = $(this);
            _this.find('option').each(function () {
                var _ID        = $(this).parent().attr('id');
                var _data      = $(this).data(_ID);
                var _value     = $(this).attr('value');
                var _data_type = $(this).data('type');
                var _itemclass = '';

                if ( $(this).is(':selected') ) {
                    _itemclass = 'active';
                }

                if ( _value !== '' ) {
                    if ( _data_type == 'color' || _data_type == 'photo' ) {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" style="background: ' + _data + ';background-size: cover; background-repeat: no-repeat " data-value="' + _value + '"></a>');
                    } else {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '">' + _value + '</a>');
                    }
                }
            });
        });
    };
    $(document).on('click', '.variations_form .change-value', function () {
        var _this   = $(this);
        var _change = _this.data('value');

        _this.parent().parent().children('select').val(_change).trigger('change');
        _this.addClass('active').siblings().removeClass('active');
        return false;
    });
    $(document).on('click', '.reset_variations', function () {
        $('.variations_form').find('.change-value').removeClass('active');
    });

    /* ---------------------------------------------
     Scripts After Ajax
     --------------------------------------------- */

    $(document).ajaxComplete(function (event, xhr, settings) {
        variations_custom();
    });

    /* ---------------------------------------------
     Scripts load
     --------------------------------------------- */

    $(window).load(function () {
        variations_custom();
    });

    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */

    $(window).on("change", function () {
        variations_custom();
    });
})(jQuery); // End of use strict