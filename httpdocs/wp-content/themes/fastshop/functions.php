<?php
if ( !isset( $content_width ) ) $content_width = 900;
if ( !class_exists( 'Fastshop_Functions' ) ) {
	class Fastshop_Functions
	{
		/**
		 * Instance of the class.
		 *
		 * @since   1.0.0
		 *
		 * @var   object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since    1.0.0
		 */
		public function __construct()
		{
			add_action( 'after_setup_theme', array( $this, 'fastshop_setup' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			if ( !is_admin() ) {
				add_filter( 'script_loader_src', array( $this, 'fastshop_remove_query_string_version' ) );
				add_filter( 'style_loader_src', array( $this, 'fastshop_remove_query_string_version' ) );
			}
			add_filter( 'get_default_comment_status', array( $this, 'open_default_comments_for_page' ), 10, 3 );
			add_filter( 'comment_form_fields', array( $this, 'fastshop_move_comment_field_to_bottom' ), 10, 3 );
			$this->includes();
		}

		public function fastshop_setup()
		{
			/*
			* Make theme available for translation.
			* Translations can be filed in the /languages/ directory.
			* If you're building a theme based on boutique, use a find and replace
			* to change 'fastshop' to the name of your theme in all the template files
			*/
			load_theme_textdomain( 'fastshop', get_template_directory() . '/languages' );
			add_theme_support( 'automatic-feed-links' );
			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );
			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );
			add_theme_support( "custom-header" );
			if ( function_exists( 'fastshop_custom_background_cb' ) ) {
				$args = array(
					'wp-head-callback' => 'fastshop_custom_background_cb',
				);
				add_theme_support( 'custom-background', $args );
			} else {
				add_theme_support( 'custom-background' );
			}
			/*This theme uses wp_nav_menu() in two locations.*/
			register_nav_menus( array(
					'primary'        => esc_html__( 'Primary Menu', 'fastshop' ),
					'vertical_menu'  => esc_html__( 'Vertical Menu', 'fastshop' ),
					'top_right_menu' => esc_html__( 'Top Right Menu', 'fastshop' ),
				)
			);
			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
					'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
				)
			);
			if ( class_exists( 'WooCommerce' ) ) {
				/*Support woocommerce*/
				add_theme_support( 'woocommerce' );
				add_theme_support( 'wc-product-gallery-lightbox' );
				add_theme_support( 'wc-product-gallery-slider' );
				add_theme_support( 'wc-product-gallery-zoom' );
			}
		}

		public function fastshop_move_comment_field_to_bottom( $fields )
		{
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;

			return $fields;
		}

		/**
		 * Register widget area.
		 *
		 * @since fastshop 1.0
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		function widgets_init()
		{
			$opt_multi_slidebars = fastshop_get_option( 'multi_widget', '' );
			if ( is_array( $opt_multi_slidebars ) && count( $opt_multi_slidebars ) > 0 ) {
				foreach ( $opt_multi_slidebars as $value ) {
					if ( $value && $value != '' ) {
						register_sidebar( array(
								'name'          => $value['add_widget'],
								'id'            => 'custom-sidebar-' . sanitize_key( $value['add_widget'] ),
								'before_widget' => '<div id="%1$s" class="widget block-sidebar %2$s">',
								'after_widget'  => '</div>',
								'before_title'  => '<div class="title-widget widgettitle"><strong>',
								'after_title'   => '</strong></div>',
							)
						);
					}
				}
			}
			register_sidebar( array(
					'name'          => esc_html__( 'Widget Area', 'fastshop' ),
					'id'            => 'widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'fastshop' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
			register_sidebar( array(
					'name'          => esc_html__( 'Shop Widget Area', 'fastshop' ),
					'id'            => 'shop-widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'fastshop' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
			register_sidebar( array(
					'name'          => esc_html__( 'Product Widget Area', 'fastshop' ),
					'id'            => 'product-widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'fastshop' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
			register_sidebar( array(
					'name'          => esc_html__( 'Page Widget Area', 'fastshop' ),
					'id'            => 'page-widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'fastshop' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
		}

		function fastshop_remove_query_string_version( $src )
		{
			remove_query_arg( 'ver', $src );
			remove_query_arg( 'id', $src );
			remove_query_arg( 'type', $src );
			remove_query_arg( 'version', $src );

			return $src;
		}

		/**
		 * Register custom fonts.
		 */
		function fastshop_fonts_url()
		{
			$fonts_url = '';
			/*
			 * Translators: If there are characters in your language that are not
			 * supported by Open Sans, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$open_sans           = esc_html_x( 'on', 'Open Sans font: on or off', 'fastshop' );
			$fastshop_typography = fastshop_get_option( 'typography_font_family' );
			if ( 'off' !== $open_sans ) {
				$font_families   = array();
				$font_families[] = 'Open+Sans:300,300i,400,400i,600,600i,700,700i';
				$font_families[] = 'Lato:300,300i,400,400i,700,700i';
				$match           = array(
					'Open Sans',
					'Lato',
				);
				if ( !in_array( $fastshop_typography, $match ) ) {
					$font_families[] = str_replace( ' ', '+', $fastshop_typography['family'] );
				}
				$query_args = array(
					'family' => urlencode( implode( '|', $font_families ) ),
					'subset' => urlencode( 'latin,latin-ext' ),
				);
				$fonts_url  = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			}

			return esc_url_raw( $fonts_url );
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since fastshop 1.0
		 */
		function scripts()
		{
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'thickbox' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_dequeue_style( 'yith-quick-view' );
			wp_dequeue_script( 'prettyPhoto' );
			// Add custom fonts, used in the main stylesheet.
			wp_enqueue_style( 'fastshop-fonts', self::fastshop_fonts_url(), array(), null );
			/*Load our main stylesheet.*/
			wp_enqueue_style( 'animate', get_theme_file_uri( '/assets/css/animate.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'carousel', get_theme_file_uri( '/assets/css/owl.carousel.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'pe-icon-7-stroke', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/fonts/flaticon/flaticon.css' ), array(), '1.0' );
			wp_enqueue_style( 'fastshop_custom_css', get_theme_file_uri( '/assets/css/style.css' ), array(), '1.0' );
			wp_enqueue_style( 'fastshop-main-style', get_stylesheet_uri() );
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			/*Load lib js*/
			global $wp_query;
			$posts                 = $wp_query->posts;
			$fastshop_gmap_api_key = fastshop_get_option( 'gmap_api_key' );
			foreach ( $posts as $post ) {
				if ( is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'contact-form-7' ) ) {
					wp_dequeue_script( 'contact-form-7' );
				}
				if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'fastshop_googlemap' ) ) {
					if ( $fastshop_gmap_api_key ) {
						wp_enqueue_script( 'fastshop-api-map', esc_url( '//maps.googleapis.com/maps/api/js?key=' . trim( $fastshop_gmap_api_key ) ), array(), false, true );
					} else {
						wp_enqueue_script( 'fastshop-api-sensor', esc_url( '//maps.google.com/maps/api/js?sensor=true' ), array(), false, true );
					}
				}
			}
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'plugin_countdown', get_theme_file_uri( '/assets/js/vendor/jquery.plugin.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'actual', get_theme_file_uri( '/assets/js/vendor/jquery.actual.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/vendor/bootstrap.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'isotope-jquery', get_theme_file_uri( '/assets/js/vendor/isotope.pkgd.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/vendor/chosen.jquery.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'countdown', get_theme_file_uri( '/assets/js/vendor/jquery.countdown.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'lazyload', get_theme_file_uri( '/assets/js/vendor/jquery.lazyload.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'sticky', get_theme_file_uri( '/assets/js/vendor/jquery.sticky.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'carousel', get_theme_file_uri( '/assets/js/vendor/owl.carousel.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/vendor/slick.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'fastshop-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0' );
			$fastshop_enable_lazy         = fastshop_get_option( 'fastshop_theme_lazy_load' );
			$fastshop_enable_popup        = fastshop_get_option( 'fastshop_enable_popup' );
			$fastshop_enable_popup_mobile = fastshop_get_option( 'fastshop_enable_popup_mobile' );
			$fastshop_popup_delay_time    = fastshop_get_option( 'fastshop_popup_delay_time', '1' );
			$fastshop_enable_sticky_menu  = fastshop_get_option( 'fastshop_enable_sticky_menu' );
			wp_localize_script( 'fastshop-script', 'fastshop_ajax_frontend', array(
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'fastshop_ajax_frontend' ),
				)
			);
			wp_localize_script( 'fastshop-script', 'fastshop_global_frontend', array(
					'fastshop_enable_lazy'         => $fastshop_enable_lazy,
					'fastshop_enable_popup'        => $fastshop_enable_popup,
					'fastshop_enable_popup_mobile' => $fastshop_enable_popup_mobile,
					'fastshop_popup_delay_time'    => $fastshop_popup_delay_time,
					'fastshop_enable_sticky_menu'  => $fastshop_enable_sticky_menu,
				)
			);
		}

		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function open_default_comments_for_page( $status, $post_type, $comment_type )
		{
			if ( 'page' == $post_type ) {
				return 'open';
			}

			return $status;
			/*You could be more specific here for different comment types if desired*/
		}

		public function includes()
		{
			include_once( get_template_directory() . '/import/import.php' );
			include_once( get_template_directory() . '/framework/framework.php' );
			define( 'CS_ACTIVE_FRAMEWORK', true );
			define( 'CS_ACTIVE_METABOX', true );
			define( 'CS_ACTIVE_TAXONOMY', false );
			define( 'CS_ACTIVE_SHORTCODE', false );
			define( 'CS_ACTIVE_CUSTOMIZE', false );
		}
	}

	new  Fastshop_Functions();
}