<?php
if ( !function_exists( 'fastshop_custom_css' ) ) {
	function fastshop_custom_css()
	{
		$css = fastshop_get_option( 'fastshop_custom_css', '' );
		$css .= fastshop_theme_color();
		$css .= fastshop_vc_custom_css_footer();
		wp_add_inline_style( 'fastshop_custom_css', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'fastshop_custom_css', 999 );
if ( !function_exists( 'fastshop_theme_color' ) ) {
	function fastshop_theme_color()
	{
		$main_color           = fastshop_get_option( 'fastshop_main_color', '#0088CC' );
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
			$main_color = $meta_data['metabox_fastshop_main_color'];
		}
		$typography_font_family = fastshop_get_option( 'typography_font_family' );
		$typography_font_size   = fastshop_get_option( 'typography_font_size', 14 );
		$typography_line_height = fastshop_get_option( 'typography_line_height', 24 );
		/* Body Css */
		$css = '';
		$css .= 'body {';
		if ( !empty( $typography_font_family ) ) {
			$css .= 'font-family: ' . $typography_font_family['family'] . ', sans-serif;';
		}
		if ( $typography_font_size ) {
			$css .= 'font-size: ' . $typography_font_size . 'px;';
		}
		if ( $typography_line_height ) {
			$css .= 'line-height: ' . $typography_line_height . 'px;';
		}
		$css .= '}';
		/* Main color */
		$css .= '
		a:hover,
		a:focus,
		a:active,
		a.compare:hover,
		.header-box-nav .main-menu .submenu .menu-item:hover>a,
		.dropdown>.submenu li a:hover,
		.dropdown>.submenu li a:focus,
		.dropdown>.submenu li a:active,
		.header.style1 .top-header .submenu a:hover,
		.header.style1 .top-header .submenu a:focus,
		.header.style1 .top-header .submenu a:active,
		.widget_search .search-form .search-submit:hover,
		.product-item.list .product-info-stock-sku,
		.post-item .read-more,
		.comment .comment-body .edit-link:hover,
		.owl-carousel .owl-nav .owl-prev:hover,
		.owl-carousel .owl-nav .owl-next:hover,
		.woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover::before,
		.toolbar-products .modes a.active,
		.box-mobile-menu .toggle-submenu:hover,
		.vertical-menu .menu-item:hover>a,
		#yith-quick-view-modal #yith-quick-view-close:hover,
		.fastshop-title.style4 .view-all:hover,
		.fastshop-tabs.style5 .tab-link li.active a,
		.fastshop-testimonials.default .testimonial-item .position,
		.fastshop-blog.style-3 .blog-item .blog-readmore:hover,
		.widget_shopping_cart .mini_cart_item>a:not(.remove):hover,
		.block-minicart .shopcart-description .subtotal .total-price,
		.widget_shopping_cart .woocommerce-mini-cart__total>span,
		.footer.style1 .fastshop-custommenu.default li::before,
		.footer.default .fastshop-custommenu.default li::before,
		.widget_tag_cloud .tag-cloud-link:hover,
		.fastshop-tabs.style10 .tab-link-wrap .view-all:hover,
		.fastshop-blog.style-9 .blog-item .blog-readmore:hover,
		.fastshop-categories.style6 .view-all:hover,
		.error-404 .hightlight,
		.block-minicart .shopcart-dropdown:hover,
		.block-wishlist .wishlist-dropdown:hover,
		.footer.style23 .widget_tag_cloud .tag-cloud-link:hover,
		.fastshop-testimonials.style5 .block-link .view-all:hover,
        .post-item .post-meta .sticky-post,
        #customer_login .woocommerce-form__label-for-checkbox:hover,
        .post-item .post-cat .content-cat a:hover,
        .fastshop-products.style-7 .product-item a.compare:hover,
        .wcml_currency_switcher .wcml-cs-submenu li a:hover,
        .pingback>a:hover,
        .pingback .edit-link:hover,
        .section-heading .section-title span,
        .flex-control-nav .slick-arrow:hover,
        .widget_product_categories .cat-item .carets:hover
		{
			color: ' . $main_color . ';
		}
		.button:hover,
		button:hover,
		input[type="submit"]:hover,
		.widget #today,
		.add-to-cart>a:hover,
		.post-item .read-more::before,
		.comment-respond .comment-form input[type="submit"],
		.shop_table .actions .button:hover,
		.wc-proceed-to-checkout .button:hover,
		a.backtotop:hover i,
		.owl-carousel.nav2 .owl-nav .owl-prev:hover,
		.owl-carousel.nav2 .owl-nav .owl-next:hover,
		.normal-effect::before,
		.fastshop-blog .blog-item .sticky-date .month,
		.fastshop-tabs.style2 .tab-link li:hover,
		.fastshop-tabs.style2 .tab-link li.active,
		.fastshop-tabs.style4 .tab-link li:hover,
		.fastshop-tabs.style4 .tab-link li.active,
		.fastshop-testimonials .owl-carousel .owl-dot.active span,
		.fastshop-testimonials .owl-carousel .owl-dot:hover span,
		.fastshop-blog.style-5 .blog-item .blog-readmore,
		.fastshop-blog.style-6 .blog-item .blog-readmore:hover,
		.fastshop-categories.style4:hover .title,
		.fastshop-socials .social-item:hover,
		footer.footer div.fastshop-socials.style2 .social-item:hover,
		.fastshop-newsletter.default .submit-newsletter:hover,
		.fastshop-newsletter.style1 .submit-newsletter:hover,
		.fastshop-categories.style5:hover .view-all,
		.owl-carousel .owl-dot.active span,
		.owl-carousel .owl-dot:hover span,
		.slick-dots li:hover::before,
		.slick-dots li.slick-active::before,
		.fastshop-blog.style-7 .blog-item .blog-readmore,
		.panel .panel-title a[aria-expanded="true"]::after,
		.fastshop-newsletter.style1 .submit-newsletter,
		.fastshop-newsletter.style2 .submit-newsletter,
		.fastshop-newsletter.style4 .submit-newsletter,
		.fastshop-newsletter.style3 .submit-newsletter,
		.fastshop-testimonials.style4 .testimonial-item .excerpt,
		.footer.style9 .fastshop-newsletter .button,
		.form-search.style1 .form-content .btn-search,
		.header-bg-dark,
		.header.style5 .form-search .btn-search,
		.header.style15 .form-search .btn-search,
		.header.style6 .main-header,
		.header.style3 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-03 .block-minicart .shopcart-icon .count,
		.header.style6 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-06 .block-minicart .shopcart-icon .count,
		.header.style7 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-07 .block-minicart .shopcart-icon .count,
		.header.style9 .block-minicart .shopcart-icon .count,
		.header.style11 .block-minicart .shopcart-icon .count,
		.header.style12 .block-minicart .shopcart-icon .count,
		.header.style14 .block-minicart .shopcart-icon .count,
		.header.style19 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-19 .block-minicart .shopcart-icon .count,
		.header.style21 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-21 .block-minicart .shopcart-icon .count,
		.header.style22 .block-minicart .shopcart-icon .count,
		.header.style23 .block-minicart .shopcart-icon .count,
		.header-sticky-menu.style-23 .block-minicart .shopcart-icon .count,
		#customer_login input[type="submit"],
		.wpcf7 .contact-form input[type="submit"],
		.fastshop-testimonials.style5 .block-link .block-link-content,
		.modal-content .btn-submit,
		.fastshop-products.style-4 .add-to-cart>a,
		.woocommerce-cart-form .shop_table .quantity .btn-number:hover,
		.social-header.style1 .social-list li a:hover,
		.header.style8:not(.style19) .block-minicart .shopcart-dropdown,
		.header.style8 .block-nav-category .block-title,
		.header-sticky-menu.style-08 .block-nav-category .block-title,
		.header-sticky-menu.style-19 .block-nav-category .block-title,
		.header.style15 .main-header .header-control .block-minicart .shopcart-dropdown,
		.header.style15 .mobile-navigation,
		.header.style20 .form-search .btn-search,
		.header.header-cart6 .block-minicart .shopcart-dropdown .cart-style6 .cart-icon,
		.header.style20 .block-nav-category .block-title,
		.header-sticky-menu.style-20 .block-nav-category .block-title,
		.widget_layered_nav .inline-group>a:hover,
		.block-minicart .shopcart-description .actions .button-checkout,
        .widget_product_search .woocommerce-product-search input[type="submit"],
        .fastshop-masonry .banner-portfolio .block-text .button,
        .woocommerce-pagination .page-numbers.current, 
        .woocommerce-pagination a.page-numbers:hover,
        .fastshop-products.style-12 .product-item .add-to-cart>a:hover,
        .fastshop-banner.style2:hover .button,
        .wc-proceed-to-checkout .button,
        .single-left .video-product:hover,
        .woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover::before,
		.type-grid-filter .item-filter:hover,
		.type-grid-filter .item-filter.filter-active,
		.header.style20 .main-header .header-control .menu-bar
		{
			background-color: ' . $main_color . ';
		}
		.button:hover,
		button:hover,
		input[type="submit"]:hover,
		.add-to-cart>a:hover,
		a.backtotop:hover,
		.owl-carousel.nav2 .owl-nav .owl-prev:hover,
		.owl-carousel.nav2 .owl-nav .owl-next:hover,
		.fastshop-title.style1 .title,
		.fastshop-title.style2 .title,
		.fastshop-blog.style-2 .blog-item .blog-readmore:hover,
		.fastshop-tabs.style3 .tab-link li:hover a,
		.fastshop-tabs.style3 .tab-link li.active a,
		.fastshop-tabs.style4 .tab-link li:hover,
		.fastshop-tabs.style4 .tab-link li.active,
		.fastshop-categories.style1:hover,
		.fastshop-banner .content-slide .slick-dots li:hover,
		.fastshop-banner .content-slide .slick-dots li.slick-active,
		.fastshop-tabs.style7 .tab-link li.active a,
		.fastshop-tabs.style7 .tab-link li:hover a,
		.fastshop-newsletter.style1 .submit-newsletter:hover,
		.widget_tag_cloud .tag-cloud-link:hover,
		.fastshop-socials.style1 .social-item:hover,
		.fastshop-newsletter.style2 .submit-newsletter:hover,
		.fastshop-testimonials.style4 .testimonial-item .excerpt::before,
		.footer.style9 .fastshop-newsletter .button,
		.footer.style16 .fastshop-socials.style1 .social-item:hover,
		.footer.style22 .fastshop-socials.style1 .social-item:hover,
		.woocommerce-cart-form .shop_table .quantity .btn-number:hover,
		.header.style22 .form-search.default,
		.widget_layered_nav .inline-group>a:hover,
        .woocommerce-pagination .page-numbers.current, 
        .woocommerce-pagination a.page-numbers:hover,
        .single-left .video-product:hover,
        .woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover::before,
		.type-grid-filter .item-filter:hover,
		.type-grid-filter .item-filter.filter-active
		{
			border-color:' . $main_color . ';
		}
		@media (max-width: 1024px) {
		.fastshop-blog.style-2 .blog-item .blog-readmore,
		.fastshop-blog.style-9 .blog-item .blog-readmore
			{
				color:' . $main_color . ';
			}
		.fastshop-blog.style-6 .blog-item .blog-readmore
			{
				background-color:' . $main_color . ';
			}
		.fastshop-blog.style-2 .blog-item .blog-readmore
			{
				border-color:' . $main_color . ';
			}
		}
		.animation-tab,
		.preloader-wrap .icon-loaded 
		{
			border-top-color: ' . $main_color . ';
		}
        .flex-control-nav .slick-slide img.flex-active
		{
			border-bottom-color: ' . $main_color . ';
		}
		';

		return apply_filters( 'fastshop_main_custom_css', $css );
	}
}
if ( !function_exists( 'fastshop_vc_custom_css_footer' ) ) {
	function fastshop_vc_custom_css_footer()
	{
		$fastshop_footer_options = fastshop_get_option( 'fastshop_footer_options', '' );
		$enable_theme_options    = fastshop_get_option( 'enable_theme_options' );
		$data_option_meta        = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_option_meta ) && $enable_theme_options == 1 ) {
			$fastshop_footer_options = $data_option_meta['fastshop_metabox_footer_options'];
		}
		$shortcodes_custom_css = get_post_meta( $fastshop_footer_options, '_wpb_post_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $fastshop_footer_options, '_wpb_shortcodes_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $fastshop_footer_options, '_Fastshop_Shortcode_custom_css', true );

		return $shortcodes_custom_css;
	}
}
if ( !function_exists( 'fastshop_write_custom_js ' ) ) {
	function fastshop_write_custom_js()
	{
		$fastshop_custom_js = fastshop_get_option( 'fastshop_custom_js', '' );
		wp_enqueue_script( 'fastshop-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0' );
		wp_add_inline_script( 'fastshop-script', $fastshop_custom_js );
	}
}
add_action( 'wp_enqueue_scripts', 'fastshop_write_custom_js' );