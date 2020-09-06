;(function ($) {
    "use strict"; // Start of use strict

    var FASTSHOP_FRAMEWORK = {
        init: function () {
            this.fastshop_init_lazy_load();
            this.fastshop_sticky_header();
            this.fastshop_init_carousel();
            this.fastshop_auto_width_vertical_menu();
            this.fastshop_resizeMegamenu();
            this.fastshop_clone_main_menu();
            this.fastshop_box_mobile_menu();
            this.fastshop_isotop();
            this.fastshop_show_other_item_vertical_menu();
            this.fastshop_category_product();
            this.fastshop_category_vertical();
            this.fastshop_stick_check();
            this.fastshop_sticky_product();
            this.fastshop_countdown();
            this.fastshop_woo_quantily();
            this.fastshop_tab_fade_effect();
            this.fastshop_ajax_tabs();
            this.fastshop_ajax_remove();
            this.fastshop_add_to_cart_single();
            this.fastshop_update_wishlist_count();
            this.fastshop_ajax_shop_page();
            this.fastshop_item_dropdown();
            this.fastshop_slick_slider();
            this.fastshop_banner_slide();
            this.fastshop_init_popup();
            this.fastshop_click_action();
            this.fastshop_hover_product_item();
            this.fastshop_better_equal_elems();
        },
        onResize: function () {
            this.fastshop_stick_check();
            this.fastshop_init_carousel();
            this.fastshop_better_equal_elems();
            this.fastshop_auto_width_vertical_menu();
            this.fastshop_resizeMegamenu();
            this.fastshop_clone_main_menu();
            this.fastshop_box_mobile_menu();
            this.fastshop_isotop();
        },
        fastshop_resizeMegamenu: function () {
            var window_size = jQuery('body').innerWidth();
            window_size += FASTSHOP_FRAMEWORK.fastshop_get_scrollbar_width();
            if ( window_size > 1024 ) {
                if ( $('#header-sticky-menu .main-menu-wapper').length > 0 ) {
                    var container = $('#header-sticky-menu .main-menu-wapper');
                    if ( container != 'undefined' ) {
                        var container_width  = 0;
                        container_width      = container.innerWidth();
                        var container_offset = container.offset();
                        setTimeout(function () {
                            $('.main-menu .item-megamenu').each(function (index, element) {
                                $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                                var sub_menu_width  = $(element).children('.megamenu').outerWidth(),
                                    item_width      = $(element).outerWidth(),
                                    container_left  = container_offset.left,
                                    container_right = (container_left + container_width),
                                    item_left       = $(element).offset().left,
                                    overflow_left   = (sub_menu_width / 2 > (item_left - container_left)),
                                    overflow_right  = ((sub_menu_width / 2 + item_left) > container_right);
                                $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                                if ( overflow_left ) {
                                    var left = (item_left - container_left);
                                    $(element).children('.megamenu').css({'left': -left + 'px'});
                                }
                                if ( overflow_right && !overflow_left ) {
                                    var left = (item_left - container_left);
                                    left     = left - (container_width - sub_menu_width);
                                    $(element).children('.megamenu').css({'left': -left + 'px'});
                                }
                            })
                        }, 100);
                    }
                }
            }
        },
        fastshop_get_scrollbar_width: function () {
            var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
                $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
                inner  = $inner[ 0 ],
                outer  = $outer[ 0 ];
            jQuery('body').append(outer);
            var width1 = inner.offsetWidth;
            $outer.css('overflow', 'scroll');
            var width2 = outer.clientWidth;
            $outer.remove();
            return (width1 - width2);
        },
        fastshop_auto_width_vertical_menu: function () {
            if ( $(window).innerWidth() > 1024 ) {
                var full_width = parseInt($('.container').innerWidth()) - 30,
                    menu_width = parseInt($('.verticalmenu-content').actual('width')),
                    w          = (full_width - menu_width);
                $('.verticalmenu-content').find('.megamenu').each(function () {
                    $(this).css('max-width', w + 'px');
                });
            }
        },
        fastshop_show_other_item_vertical_menu: function () {
            if ( $('.block-nav-category').length > 0 ) {
                $('.block-nav-category').each(function () {
                    var all_item   = 0,
                        limit_item = $(this).data('items') - 1;
                    all_item       = $(this).find('.vertical-menu>li').length;

                    if ( all_item > (limit_item + 1) ) {
                        $(this).addClass('show-button-all');
                    }
                    $(this).find('.vertical-menu>li').each(function (i) {
                        all_item = all_item + 1;
                        if ( i > limit_item ) {
                            $(this).addClass('link-other');
                        }
                    })
                })
            }
        },
        fastshop_category_product: function () {
            $(".widget_product_categories .product-categories").each(function () {
                var _main = $(this);
                _main.find('.cat-parent').each(function () {
                    if ( $(this).hasClass('current-cat-parent') ) {
                        $(this).addClass('show-sub');
                        $(this).children('.children').slideDown(400);
                    }
                    $(this).children('.children').before('<span class="carets"></span>');
                });
                _main.children('.cat-parent').each(function () {
                    var curent = $(this).find('.children');
                    $(this).children('.carets').on('click', function () {
                        $(this).parent().toggleClass('show-sub');
                        $(this).parent().children('.children').slideToggle(400);
                        _main.find('.children').not(curent).slideUp(400);
                        _main.find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                    });
                    var next_curent = $(this).find('.children');
                    next_curent.children('.cat-parent').each(function () {
                        var child_curent = $(this).find('.children');
                        $(this).children('.carets').on('click', function () {
                            $(this).parent().toggleClass('show-sub');
                            $(this).parent().parent().find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                            $(this).parent().parent().find('.children').not(child_curent).slideUp(400);
                            $(this).parent().children('.children').slideToggle(400);
                        })
                    });
                });
            });
        },
        fastshop_category_vertical: function () {
            $('.block-nav-category .vertical-menu').each(function () {
                var _main = $(this);
                _main.children('.menu-item.parent').each(function () {
                    var curent = $(this).find('.submenu');
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().children('.submenu').slideToggle(500);
                        _main.find('.submenu').not(curent).slideUp(500);
                        $(this).parent().toggleClass('show-submenu');
                        _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                    });
                    var next_curent = $(this).find('.submenu');
                    next_curent.children('.menu-item.parent').each(function () {
                        var child_curent = $(this).find('.submenu');
                        $(this).children('.toggle-submenu').on('click', function () {
                            $(this).parent().parent().find('.submenu').not(child_curent).slideUp(500);
                            $(this).parent().children('.submenu').slideToggle(500);
                            $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                            $(this).parent().toggleClass('show-submenu');
                        })
                    });
                });
            });
            $(document).on('click', '.open-cate', function () {
                $(this).closest('.block-nav-category').find('li.link-other').each(function (e) {
                    $(this).slideDown();
                });
                var closetext = $(this).data('closetext');
                $(this).addClass('close-cate').removeClass('open-cate').html(closetext);
                e.preventDefault();
            });

            /* Close Categorie */
            $(document).on('click', '.close-cate', function (e) {
                $(this).closest('.block-nav-category').find('li.link-other').each(function () {
                    $(this).slideUp();
                });
                var alltext = $(this).data('alltext');
                $(this).addClass('open-cate').removeClass('close-cate').html(alltext);
                e.preventDefault();
            });

            $(".block-nav-category .block-title").on('click', function () {
                $(this).toggleClass('active');
                $(this).parent().toggleClass('has-open');
                $("body").toggleClass("category-open");
            });

            if ( $('.category-search-option').length > 0 ) {
                $('.category-search-option').chosen();
            }
        },
        fastshop_clone_main_menu: function () {
            var _header_clone  = $(document.getElementById('header')).find('.clone-main-menu'),
                _mobile_clone  = $(document.getElementById('box-mobile-menu')).find('.clone-main-menu'),
                _header_target = $(document.getElementById('header')).find('.box-header-nav'),
                _mobile_target = $(document.getElementById('box-mobile-menu')).find('.box-inner');

            if ( $(window).innerWidth() <= 1024 ) {
                if ( _header_clone.length > 0 ) {
                    _header_clone.each(function () {
                        $(this).appendTo(_mobile_target);
                    });
                }
            } else {
                if ( _mobile_clone.length > 0 ) {
                    _mobile_clone.each(function () {
                        $(this).appendTo(_header_target);
                    });
                }
            }
        },
        fastshop_box_mobile_menu: function () {
            if ( $(window).innerWidth() <= 1024 ) {
                var _content_mobile = $(document.getElementById('box-mobile-menu')),
                    _back_button    = _content_mobile.find('#back-menu'),
                    _clone_menu     = _content_mobile.find('.clone-main-menu'),
                    _title_menu     = _content_mobile.find('.box-title');

                _clone_menu.each(function () {
                    var _this = $(this);
                    _this.addClass('active');
                    _this.find('.toggle-submenu').on('click', function (e) {
                        var _self      = $(this),
                            _text_next = _self.prev().text();

                        _this.removeClass('active');
                        _title_menu.html(_text_next);
                        _this.find('li').removeClass('mobile-active');
                        _self.parent().addClass('mobile-active');
                        _self.parent().closest('.submenu').css({
                            'position': 'static',
                            'height': '0',
                        });
                        _back_button.css('display', 'block');
                        e.preventDefault();
                    })
                });
                _back_button.on('click', function (e) {
                    _clone_menu.find('li.mobile-active').each(function () {
                        _clone_menu.find('li').removeClass('mobile-active');
                        if ( $(this).parent().hasClass('main-menu') ) {
                            _clone_menu.addClass('active');
                            _title_menu.html('MAIN MENU');
                            _back_button.css('display', 'none');
                        } else {
                            _clone_menu.removeClass('active');
                            $(this).parent().parent().addClass('mobile-active');
                            $(this).parent().css({
                                'position': 'absolute',
                                'height': 'auto',
                            });
                            var text_prev = $(this).parent().parent().children('a').text();
                            _title_menu.html(text_prev);
                        }
                    })
                    e.preventDefault();
                });
            } else {
                $('html').css('overflow', 'visible');
                $('body').removeClass('box-mobile-menu-open');
            }
            $(document).on('click', ".desktop-navigation", function () {
                $('body').toggleClass('desktop-navigation-open');
            });
            $('.mobile-navigation').on('click', function (e) {
                $('html').css('overflow', 'hidden');
                $('body').addClass('box-mobile-menu-open');
                e.preventDefault();
            });
            $('#box-mobile-menu .close-menu,.body-overlay').on('click', function (e) {
                $('html').css('overflow', 'visible');
                $('body').removeClass('box-mobile-menu-open');
                e.preventDefault();
            });
        },
        fastshop_sticky_product: function () {
            if ( $('body.single-product .style-with-sticky').length > 0 ) {
                var _height_header = $('#header-sticky-menu').outerHeight();
                if ( $('body').hasClass('admin-bar') ) {
                    _height_header += 32;
                }
                console.log(_height_header);
                $('body.single-product .style-with-sticky').each(function () {
                    $(this).find('.summary').sticky({
                        topSpacing: _height_header + 20,
                    });
                    $(window).resize($(this).find('.summary').sticky('update'));
                })
            }
        },
        fastshop_stick_check: function () {
            if ( $('body.single-product .style-with-sticky').length > 0 ) {
                var scrollUp = 0;
                $(window).scroll(function (event) {
                    var scrollTop          = $(this).scrollTop(),
                        _height_header = $('#header-sticky-menu').outerHeight(),
                        total_gallery     = $('.woocommerce-product-gallery--with-images').offset().top + $('.woocommerce-product-gallery--with-images').outerHeight(),
                        total_summary     = $('.summary').outerHeight();

                    if ( $('body').hasClass('admin-bar') ) {
                        _height_header += 32;
                    }
                    var height_single_left = total_gallery - total_summary - _height_header - 20;
                    //Remove summary sticky
                    if ( scrollTop > height_single_left / 2 ) {
                        $('.summary').addClass('remove-sticky-detail-half')
                    } else {
                        $('.summary').removeClass('remove-sticky-detail-half');
                    }
                    if ( scrollTop > height_single_left ) {
                        $('.summary').addClass('remove-sticky-detail')
                    } else {
                        $('.summary').removeClass('remove-sticky-detail');
                    }

                    scrollUp = scrollTop;
                })
            }
        },
        fastshop_init_carousel: function () {
            $(".owl-carousel").each(function (index, el) {
                var config     = $(this).data(),
                    animateOut = $(this).data('animateout'),
                    animateIn  = $(this).data('animatein'),
                    slidespeed = $(this).data('slidespeed');
                config.navText = [ '<i class="pe-7s-angle-left"></i>', '<i class="pe-7s-angle-right"></i>' ];

                if ( typeof animateOut != 'undefined' ) {
                    config.animateOut = animateOut;
                }
                if ( typeof animateIn != 'undefined' ) {
                    config.animateIn = animateIn;
                }
                if ( typeof (slidespeed) != 'undefined' ) {
                    config.smartSpeed = slidespeed;
                }
                if ( $('body').hasClass('rtl') ) {
                    config.rtl = true;
                }

                var owl = $(this);
                owl.owlCarousel(config);
                owl.on('change.owl.carousel', function (event) {
                    var total_active = owl.find('.owl-item.active').length;
                    var i            = 0;
                    owl.find('.owl-item').removeClass('item-first item-last');
                    setTimeout(function () {
                        owl.find('.owl-item.active').each(function () {
                            i++;
                            if ( i == 1 ) {
                                $(this).addClass('item-first');
                            }
                            if ( i == total_active ) {
                                $(this).addClass('item-last');
                            }
                        });

                    }, 100);
                });
                owl.on('translated.owl.carousel', function (event) {
                    FASTSHOP_FRAMEWORK.fastshop_init_lazy_load();
                });
            });
        },
        fastshop_countdown: function () {
            if ( $('.fastshop-countdown').length > 0 ) {
                var labels = [ 'Years', 'Months', 'Weeks', 'Days', 'Hrs', 'Mins', 'Secs' ],
                    layout = '<span class="box-count day"><span class="number">{dnn}</span> <span class="text">Days</span></span><span class="dot">:</span><span class="box-count hrs"><span class="number">{hnn}</span> <span class="text">Hours</span></span><span class="dot">:</span><span class="box-count min"><span class="number">{mnn}</span> <span class="text">Mins</span></span><span class="dot">:</span><span class="box-count secs"><span class="number">{snn}</span> <span class="text">Secs</span></span>';
                $('.fastshop-countdown').each(function () {
                    var austDay = new Date($(this).data('y'), $(this).data('m') - 1, $(this).data('d'), $(this).data('h'), $(this).data('i'), $(this).data('s'));
                    $(this).countdown({
                        until: austDay,
                        labels: labels,
                        layout: layout
                    });
                });
            }
        },
        fastshop_woo_quantily: function () {
            $('body').on('click', '.quantity .quantity-plus', function (e) {
                var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                    val_qty  = parseInt(obj_qty.val()),
                    min_qty  = parseInt(obj_qty.attr('min')),
                    max_qty  = parseInt(obj_qty.attr('max')),
                    step_qty = parseInt(obj_qty.data('step'));
                val_qty      = val_qty + step_qty;
                if ( max_qty && val_qty > max_qty ) {
                    val_qty = max_qty;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                e.preventDefault();
            });
            $(window).on("change", function () {
                $('.quantity').each(function () {
                    var _obj_qty = $(this).find('input.qty'),
                        _value   = _obj_qty.val(),
                        _max_qty = parseInt(_obj_qty.attr('max'));
                    if ( _value > _max_qty ) {
                        $(this).find('.quantity-plus').css('pointer-events', 'none')
                    } else {
                        $(this).find('.quantity-plus').css('pointer-events', 'auto')
                    }
                })
            });

            $('body').on('click', '.quantity .quantity-minus', function (e) {
                var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                    val_qty  = parseInt(obj_qty.val()),
                    min_qty  = parseInt(obj_qty.attr('min')),
                    max_qty  = parseInt(obj_qty.attr('max')),
                    step_qty = parseInt(obj_qty.data('step'));
                val_qty      = val_qty - step_qty;
                if ( min_qty && val_qty < min_qty ) {
                    val_qty = min_qty;
                }
                if ( !min_qty && val_qty < 0 ) {
                    val_qty = 0;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                e.preventDefault();
            });
        },
        fastshop_init_lazy_load: function () {
            if ( $('.lazy').length > 0 ) {
                var _config = [];

                _config.beforeLoad     = function (element) {
                    element.parent().addClass('loading-lazy');
                };
                _config.afterLoad      = function (element) {
                    element.parent().removeClass('loading-lazy');
                };
                _config.effect         = "fadeIn";
                _config.enableThrottle = true;
                _config.throttle       = 250;
                _config.effectTime     = 1000;
                _config.threshold      = 0;

                $('.lazy').lazy(_config);
                $('.megamenu .lazy').lazy({delay: 100});
                $('.widget .lazy').lazy({delay: 100});
            }
        },
        fastshop_tab_fade_effect: function () {
            // effect click
            $(document).on('click', '.fastshop-tabs .tab-link a', function (e) {
                var tab_id       = $(this).attr('href'),
                    tab_animated = $(this).data('animate');

                $(this).parent().addClass('active').siblings().removeClass('active');
                $(tab_id).addClass('active').siblings().removeClass('active');

                tab_animated = (tab_animated == undefined || tab_animated == "") ? '' : tab_animated;
                if ( tab_animated == "" ) {
                    e.preventDefault();
                }

                $(tab_id).find('.product-list-owl .owl-item.active, .product-list-grid .product-item').each(function (i) {

                    var t     = $(this),
                        style = $(this).attr("style"),
                        delay = i * 400;
                    style     = (style == undefined) ? '' : style;
                    t.attr("style", style +
                        ";-webkit-animation-delay:" + delay + "ms;"
                        + "-moz-animation-delay:" + delay + "ms;"
                        + "-o-animation-delay:" + delay + "ms;"
                        + "animation-delay:" + delay + "ms;"
                    ).addClass(tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                        t.removeClass(tab_animated + ' animated');
                        t.attr("style", style);
                    });
                })
            })
        },
        fastshop_ajax_tabs: function () {
            $(document).on('click', '[data-ajax="1"]', function (e) {
                if ( !$(this).hasClass('loaded') ) {
                    var _this      = $(this),
                        id         = _this.data('id'),
                        tab_id     = _this.attr('href'),
                        section_id = _this.data('section'),
                        loaded     = _this.closest('.tab-link').find('a.loaded').attr('href');

                    $(tab_id).closest('.tab-container').append('<div class="cssload-wapper" style="min-height: 300px;position: relative"><div class="cssload-square"><div class="animation-tab"></div></div></div>');
                    $(tab_id).closest('.panel-collapse').append('<div class="cssload-wapper" style="min-height: 300px;position: relative"><div class="cssload-square"><div class="animation-tab"></div></div></div>');
                    $.ajax({
                        type: 'POST',
                        url: fastshop_ajax_frontend.ajaxurl,
                        data: {
                            action: 'fastshop_ajax_tabs',
                            security: fastshop_ajax_frontend.security,
                            id: id,
                            section_id: section_id,
                        },
                        success: function (response) {
                            if ( response[ 'success' ] == 'ok' ) {
                                $(loaded).html('');
                                $('[href="' + loaded + '"]').removeClass('loaded');
                                $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                                $(tab_id).closest('.panel-collapse').find('.cssload-wapper').remove();
                                $(tab_id).html($(response[ 'html' ]).find('.vc_tta-panel-body').html());
                                _this.addClass('loaded');
                            }
                        },
                        complete: function () {
                            FASTSHOP_FRAMEWORK.fastshop_countdown();
                            FASTSHOP_FRAMEWORK.fastshop_better_equal_elems();
                            FASTSHOP_FRAMEWORK.fastshop_init_carousel();
                            FASTSHOP_FRAMEWORK.fastshop_init_lazy_load();
                            FASTSHOP_FRAMEWORK.fastshop_tab_fade_effect();
                            FASTSHOP_FRAMEWORK.fastshop_hover_product_item();
                        }
                    });
                }
                e.preventDefault();
            });
        },
        fastshop_ajax_remove: function () {
            function fastshop_get_url_var(key, url) {
                var result = new RegExp(key + "=([^&]*)", "i").exec(url);
                return result && result[ 1 ] || "";
            }

            $(document).on('click', '.fastshop-mini-cart .mini_cart_item a.remove', function (e) {
                var $this      = $(this),
                    thisItem   = $this.closest('.fastshop-mini-cart'),
                    remove_url = $this.attr('href'),
                    product_id = $this.attr('data-product_id');

                if ( thisItem.is('.loading') ) {
                    e.preventDefault();
                }

                if ( $.trim(remove_url) !== '' && $.trim(remove_url) !== '#' ) {

                    thisItem.addClass('loading');

                    var nonce         = fastshop_get_url_var('_wpnonce', remove_url),
                        cart_item_key = fastshop_get_url_var('remove_item', remove_url),
                        data          = {
                            action: 'fastshop_remove_cart_item_via_ajax',
                            product_id: product_id,
                            cart_item_key: cart_item_key,
                            nonce: nonce
                        };

                    $.post(fastshop_ajax_frontend[ 'ajaxurl' ], data, function (response) {

                        if ( response[ 'err' ] != 'yes' ) {
                            $('.fastshop-mini-cart').html(response[ 'mini_cart_html' ]);
                        }
                        thisItem.removeClass('loading');

                    });

                    e.preventDefault();
                }

                e.preventDefault();

            });
        },
        fastshop_add_to_cart_single: function () {
            $(document).on('click', '.single_add_to_cart_button', function (e) {
                e.preventDefault();
                var _this = $(this);
                if ( !_this.hasClass('disabled') ) {
                    var _product_id = _this.val(),
                        _form       = _this.closest('form'),
                        _form_data  = _form.serialize();

                    if ( _product_id != '' ) {
                        var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
                    } else {
                        var _data = _form_data;
                    }
                    _this.addClass('loading');
                    $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', ''), _data, function (response) {
                        $(document.body).trigger('wc_fragment_refresh');
                        _this.removeClass('loading');
                    });
                }
            });
        },
        fastshop_better_equal_elems: function () {
            setTimeout(function () {
                $('.equal-container.better-height').each(function () {
                    var $this = $(this);
                    if ( $this.find('.equal-elem').length ) {
                        $this.find('.equal-elem').css({
                            'height': 'auto'
                        });
                        var elem_height = 0;
                        $this.find('.equal-elem').each(function () {
                            var this_elem_h = $(this).height();
                            if ( elem_height < this_elem_h ) {
                                elem_height = this_elem_h;
                            }
                        });
                        $this.find('.equal-elem').height(elem_height);
                    }
                });
            }, 600);
        },
        fastshop_update_wishlist_count: function () {
            var fastshop_update_wishlist_count = function () {
                $.ajax({
                    beforeSend: function () {

                    },
                    complete: function () {

                    },
                    data: {
                        action: 'fastshop_update_wishlist_count'
                    },
                    success: function (data) {
                        //do something
                        $('.block-wishlist .count').text(data);
                    },

                    url: fastshop_ajax_frontend[ 'ajaxurl' ]
                });
            };

            $('body').on('added_to_wishlist removed_from_wishlist', fastshop_update_wishlist_count);
        },
        fastshop_ajax_shop_page: function () {
            /* ORDER BY */
            $(document).on('submit', '.woocommerce-ordering', function (e) {
                e.preventDefault();
            });
            $(document).on('change', '.woocommerce-ordering .orderby', function (e) {
                var _val = $(this).val(),
                    _url = window.location.href,
                    xhttp;

                _url += (_url.indexOf("?") === -1 ? "?" : "&") + "orderby=" + _val;

                $('.main-content').addClass('loading');

                if ( window.XMLHttpRequest )
                    xhttp = new XMLHttpRequest();
                else
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                xhttp.onreadystatechange = function () {
                    if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                        var $html       = $.parseHTML(xhttp.responseText),
                            $new_form   = $('.main-content', $html),
                            $new_form_2 = $('.woocommerce-breadcrumb', $html);

                        $('.main-content').replaceWith($new_form);
                        $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                        $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                        $('.main-content').removeClass('loading');
                        FASTSHOP_FRAMEWORK.fastshop_slick_slider();
                        FASTSHOP_FRAMEWORK.fastshop_init_lazy_load();
                        FASTSHOP_FRAMEWORK.fastshop_better_equal_elems();
                    }
                };
                xhttp.open("GET", _url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(null);
                e.preventDefault();
            });

            /* SET PRODUCT PER PAGE */
            $(document).on('change', '.shop-perpage .option-perpage', function (e) {
                var _mode = $(this).val(),
                    _url  = window.location.href,
                    xhttp;

                _url += (_url.indexOf("?") === -1 ? "?" : "&") + 'product_per_page=' + _mode;

                $('.main-content').addClass('loading');

                if ( window.XMLHttpRequest )
                    xhttp = new XMLHttpRequest();
                else
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                xhttp.onreadystatechange = function () {
                    if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                        var $html       = $.parseHTML(xhttp.responseText),
                            $new_form   = $('.main-content', $html),
                            $new_form_2 = $('.woocommerce-breadcrumb', $html);

                        $('.main-content').replaceWith($new_form);
                        $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                        $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                        $('.main-content').removeClass('loading');
                        FASTSHOP_FRAMEWORK.fastshop_slick_slider();
                        FASTSHOP_FRAMEWORK.fastshop_init_lazy_load();
                        FASTSHOP_FRAMEWORK.fastshop_better_equal_elems();
                    }
                };
                xhttp.open("POST", _url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("fastshop_woo_products_perpage=" + _mode);
                e.preventDefault();
            });
            /*  VIEW GRID LIST */
            $(document).on('click', '.display-mode', function (e) {
                var _mode = $(this).data('mode'),
                    _url  = window.location.href,
                    xhttp;

                _url += (_url.indexOf("?") === -1 ? "?" : "&") + 'shop_page_layout=' + _mode;

                $(this).addClass('active').siblings().removeClass('active');
                $('.main-content').addClass('loading');

                if ( window.XMLHttpRequest )
                    xhttp = new XMLHttpRequest();
                else
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                xhttp.onreadystatechange = function () {
                    if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                        var $html       = $.parseHTML(xhttp.responseText),
                            $new_form   = $('.main-content', $html),
                            $new_form_2 = $('.woocommerce-breadcrumb', $html);

                        $('.main-content').replaceWith($new_form);
                        $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                        $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                        $('.main-content').removeClass('loading');
                        FASTSHOP_FRAMEWORK.fastshop_slick_slider();
                        FASTSHOP_FRAMEWORK.fastshop_init_lazy_load();
                        FASTSHOP_FRAMEWORK.fastshop_better_equal_elems();
                    }
                };
                xhttp.open("POST", _url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("shop_display_mode=" + _mode);
                e.preventDefault();
            });
        },
        fastshop_item_dropdown: function () {
            $(document).mouseup(function (e) {
                var container = $('.shopcart-description');
                $(document).on('click', '.shopcart-dropdown', function (e) {
                    $(this).parent().find('.shopcart-description').toggleClass('open');
                    e.preventDefault();
                });
                if ( !container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0 ) // ... nor a descendant of the container
                {
                    container.removeClass('open');
                }
            });
        },
        fastshop_slick_slider: function () {
            if ( $('body').hasClass('rtl') ) {
                $('.banner-shop').slick({
                    rtl: true,
                });
            } else {
                $('.banner-shop').slick();
            }
            $('.style-standard-horizon .flex-control-thumbs,.style-with-sticky .flex-control-thumbs').each(function () {
                if ( $(this).children().length == 0 ) {
                    return;
                }
                var _config = [];

                _config.slidesToShow  = 4;
                _config.infinite      = false;
                _config.focusOnSelect = true;
                _config.prevArrow     = '<span class="pe-7s-angle-left"></span>';
                _config.nextArrow     = '<span class="pe-7s-angle-right"></span>';
                _config.responsive    = [
                    {
                        breakpoint: 1025,
                        settings: {
                            slidesToShow: 3,
                        }
                    }
                ];
                if ( $('body').hasClass('rtl') ) {
                    _config.rtl = true;
                }

                $(this).slick(_config);
            });
            $('.style-standard-vertical .flex-control-thumbs').each(function () {
                if ( $(this).children().length == 0 ) {
                    return;
                }
                var _config = [];

                _config.vertical      = true;
                _config.slidesToShow  = 4;
                _config.infinite      = false;
                _config.focusOnSelect = true;
                _config.prevArrow     = '<span class="pe-7s-angle-up"></span>';
                _config.nextArrow     = '<span class="pe-7s-angle-down"></span>';
                _config.responsive    = [
                    {
                        breakpoint: 1025,
                        settings: {
                            vertical: false,
                            slidesToShow: 3,
                            prevArrow: '<span class="pe-7s-angle-left"></span>',
                            nextArrow: '<span class="pe-7s-angle-right"></span>',
                        }
                    }
                ];
                if ( $('body').hasClass('rtl') ) {
                    _config.rtl = true;
                }

                $(this).slick(_config);
            });
        },
        fastshop_banner_slide: function () {
            var _config_main = [],
                _config_dots = [];

            _config_main.slidesToShow = 1;
            _config_main.fade         = true;
            _config_main.arrows       = false;
            _config_main.infinite     = false;
            if ( $('body').hasClass('rtl') ) {
                _config_main.rtl = true;
            }
            _config_main.asNavFor = '.fastshop-banner .second-slide';
            $('.fastshop-banner .main-slide').slick(_config_main);

            _config_dots.slidesToShow  = 3;
            _config_dots.arrows        = false;
            _config_dots.dots          = true;
            _config_dots.infinite      = false;
            _config_dots.focusOnSelect = true;
            if ( $('body').hasClass('rtl') ) {
                _config_dots.rtl = true;
            }
            _config_dots.asNavFor = '.fastshop-banner .main-slide';
            $('.fastshop-banner .second-slide').slick(_config_dots);
        },
        fastshop_quickview_slide: function () {
            $('#yith-quick-view-content .woocommerce-gallery-carousel').not('.slick-initialized').each(function (e) {
                if ( $(this).children().length === 0 ) {
                    e.preventDefault();
                }
                var _this   = $(this),
                    _config = [];

                if ( $('body').hasClass('rtl') ) {
                    _config.rtl = true;
                }
                _config.slidesToShow = 1;
                _config.arrows       = false;
                _config.fade         = true;

                _this.slick(_config);
            });
        },
        fastshop_init_popup: function () {
            if ( fastshop_global_frontend.fastshop_enable_popup_mobile != 1 ) {
                if ( $(window).width() + FASTSHOP_FRAMEWORK.fastshop_get_scrollbar_width() < 768 ) {
                    return false;
                }
            }
            var disabled_popup_by_user = getCookie('fastshop_disabled_popup_by_user');
            if ( disabled_popup_by_user == 'true' ) {
                return false;
            } else {
                if ( $('body').hasClass('home') && fastshop_global_frontend.fastshop_enable_popup == 1 ) {
                    setTimeout(function () {
                        $('#popup-newsletter').modal({
                            keyboard: false
                        })
                    }, fastshop_global_frontend.fastshop_popup_delay_time);

                }
            }
            $(document).on('change', '.fastshop_disabled_popup_by_user', function () {
                if ( $(this).is(":checked") ) {
                    setCookie("fastshop_disabled_popup_by_user", 'true', 7);
                } else {
                    setCookie("fastshop_disabled_popup_by_user", '', 0);
                }
            });

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires     = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; " + expires;
            }

            function getCookie(cname) {
                var name = cname + "=",
                    ca   = document.cookie.split(';');
                for ( var i = 0; i < ca.length; i++ ) {
                    var c = ca[ i ];
                    while ( c.charAt(0) == ' ' ) {
                        c = c.substring(1);
                    }
                    if ( c.indexOf(name) == 0 ) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        },
        fastshop_click_action: function () {
            $(document).on('click', 'a.backtotop', function () {
                $('html, body').animate({scrollTop: 0}, 800);
            });
            $('.form-search.style1 .btn-search-drop').on('click', function () {
                $(this).parent().toggleClass('open');
            })
        },
        fastshop_sticky_header: function () {
            if ( fastshop_global_frontend.fastshop_enable_sticky_menu == 1 ) {
                var _height = $('#header').outerHeight(),
                    _winw   = $(window).innerWidth();
                if ( _winw > 1024 ) {
                    if ( $(window).scrollTop() > _height ) {
                        $('#header-sticky-menu').addClass('active');
                    } else {
                        $('#header-sticky-menu').removeClass('active');
                    }
                }
            }
        },
        fastshop_isotop: function () {
            function kt_masonry($masonry) {
                var t = $masonry.data("cols");
                if ( t == "1" ) {
                    var n = $masonry.width();
                    var r = 1;
                    return r
                }
                if ( t == "2" ) {
                    var n = $masonry.width();
                    var r = 2;
                    if ( n < 600 ) r = 1;
                    return r
                } else if ( t == "3" ) {
                    var n = $masonry.width();
                    var r = 3;
                    if ( n < 600 ) r = 1;
                    else if ( n >= 600 && n < 768 ) r = 2;
                    else if ( n >= 768 && n < 992 ) r = 3;
                    else if ( n >= 992 ) r = 3;
                    return r
                } else if ( t == "4" ) {
                    var n = $masonry.width();
                    var r = 4;
                    if ( n < 600 ) r = 1;
                    else if ( n >= 600 && n < 768 ) r = 2;
                    else if ( n >= 768 && n < 992 ) r = 3;
                    else if ( n >= 992 ) r = 4;
                    return r
                } else if ( t == "5" ) {
                    var n = $masonry.width();
                    var r = 5;
                    if ( n < 600 ) r = 1;
                    else if ( n >= 600 && n < 768 ) r = 2;
                    else if ( n >= 768 && n < 992 ) r = 3;
                    else if ( n >= 992 && n < 1140 ) r = 4;
                    else if ( n >= 1140 ) r = 5;
                    return r
                } else if ( t == "6" ) {
                    var n = $masonry.width();
                    var r = 5;
                    if ( n < 600 ) r = 1;
                    else if ( n >= 600 && n < 768 ) r = 2;
                    else if ( n >= 768 && n < 992 ) r = 3;
                    else if ( n >= 992 && n < 1160 ) r = 4;
                    else if ( n >= 1160 ) r = 6;
                    return r
                } else if ( t == "8" ) {
                    var n = $masonry.width();
                    var r = 5;
                    if ( n < 600 ) r = 1;
                    else if ( n >= 600 && n < 768 ) r = 2;
                    else if ( n >= 768 && n < 992 ) r = 3;
                    else if ( n >= 992 && n < 1160 ) r = 4;
                    else if ( n >= 1160 ) r = 8;
                    return r
                }
            };

            function cp_s($masonry) {
                var t = kt_masonry($masonry);
                var n = $masonry.width();
                var r = n / t;
                r     = Math.floor(r);
                $masonry.find(".portfolio-item").each(function (t) {
                    $(this).css({
                        width: r + "px"
                    });
                });
            };

            $('.fastshop-portfolio').each(function () {
                var $masonry    = $(this).find('.portfolio-grid'),
                    $layoutMode = $masonry.data('layoutmode'),
                    $selector   = $(this).find('.portfolio_filter .item-filter').data('active');

                cp_s($masonry);
                // init Isotope
                var $grid = $masonry.isotope({
                    itemSelector: '.portfolio-item',
                    layoutMode: $layoutMode,
                    itemPositionDataEnabled: true,
                    filter: $selector
                });

                $grid.imagesLoaded().progress(function () {
                    $grid.isotope({
                        itemSelector: '.portfolio-item',
                        layoutMode: $layoutMode,
                        itemPositionDataEnabled: true,
                        filter: $selector
                    });
                });

                $(this).find('.portfolio_filter .item-filter').on('click', function () {
                    if ( fastshop_global_frontend.fastshop_enable_lazy == 1 ) {
                        $masonry.find('.lazy').each(function () {
                            $(this).attr('src', $(this).data('src'));
                        });
                    }
                    var $filterValue = $(this).attr('data-filter');
                    $grid.isotope({
                        filter: $filterValue
                    });
                    $(this).closest('.fastshop-portfolio').find('.portfolio_filter .item-filter').removeClass('filter-active');
                    $(this).addClass('filter-active');
                });

            });
        },
        fastshop_hover_product_item: function () {
            var _winw = $(window).innerWidth();
            if ( _winw > 1024 ) {
                $('.product-list-owl .product-item.style-1, .product-grid .owl-carousel .product-item.style-1').hover(
                    function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '10px 10px 200px',
                            'margin': '-10px -10px -200px',
                        });
                    }, function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '0',
                            'margin': '0',
                        });
                    }
                );

                $('.product-list-owl .product-item.style-10').hover(
                    function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '10px 10px 200px',
                            'margin': '-10px -10px -200px',
                        });
                    }, function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '0',
                            'margin': '0',
                        });
                    }
                );

                $('.product-list-owl .product-item.style-11').hover(
                    function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '10px 10px 200px',
                            'margin': '-10px -10px -200px',
                        });
                    }, function () {
                        $(this).closest('.owl-stage-outer').css({
                            'padding': '0',
                            'margin': '0',
                        });
                    }
                );
            }
        },
        fastshop_alert_variable_product: function () {
            $('.single_add_to_cart_button').each(function () {
                var _this = $(this);
                if ( _this.hasClass('disabled') ) {
                    _this.popover({
                        content: 'Plz Select option before Add To Cart.',
                        trigger: 'hover'
                    });
                } else {
                    _this.popover('destroy');
                }
            })
        },
    }

    /* Reinit some important things after ajax */
    $(document).on('qv_loader_stop', function () {
        FASTSHOP_FRAMEWORK.fastshop_quickview_slide();
    });

    $(document).on('change', function () {
        FASTSHOP_FRAMEWORK.fastshop_alert_variable_product();
    });

    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */
    $(window).on('resize', function () {
        FASTSHOP_FRAMEWORK.onResize();
    });

    /* ---------------------------------------------
     Scripts scroll
     --------------------------------------------- */

    $(document).scroll(function () {
        FASTSHOP_FRAMEWORK.fastshop_sticky_header();
        if ( $(window).scrollTop() > 500 ) {
            $('.backtotop').show();
        } else {
            $('.backtotop').hide();
        }
    });

    window.addEventListener('load',
        function (ev) {
            FASTSHOP_FRAMEWORK.init();
        }, false);
})(jQuery, window, document); // End of use strict