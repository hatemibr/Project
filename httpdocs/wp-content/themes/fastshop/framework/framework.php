<?php
// Prevent direct access to this file
defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

/**
 * Core class.
 *
 * @package  KuteTheme
 * @since    1.0
 */
?>
<?php
if ( !class_exists( 'Fastshop_framework' ) ) {
	class Fastshop_framework
	{
		/**
		 * Define theme version.
		 *
		 * @var  string
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of the class.
		 *
		 * @since   1.0.0
		 *
		 * @var   object
		 */
		protected static $instance = null;

		/**
		 * Return an instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @return  object  A single instance of the class.
		 */
		public static function get_instance()
		{

			// If the single instance hasn't been set yet, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

		public function __construct()
		{
			$this->includes();
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		public function enqueue_scripts()
		{
			wp_enqueue_style( 'flaticon-css', get_theme_file_uri( '/assets/fonts/flaticon/flaticon.css' ), array(), '1.0' );
			wp_enqueue_style( '7-stroke-admin-css', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'custom-admin-css', get_theme_file_uri( '/framework/assets/css/admin.css' ), array(), '1.0' );

			wp_enqueue_script( 'custom-admin-js', get_theme_file_uri( '/framework/assets/js/admin.js' ), array(), '1.0' );
		}

		public function includes()
		{
			/* Classes */
			require_once get_parent_theme_file_path( '/framework/includes/classes/class-tgm-plugin-activation.php' );
			require_once get_parent_theme_file_path( '/framework/includes/classes/breadcrumbs.php' );

			/*Mega menu */
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/megamenu.php' );
			/*Plugin load*/
			require_once get_parent_theme_file_path( '/framework/settings/plugins-load.php' );

			/*Theme Functions*/
			require_once get_parent_theme_file_path( '/framework/includes/theme-functions.php' );
			/*WIDGETS*/

			if ( class_exists( 'WooCommerce' ) ) {
				require_once get_parent_theme_file_path( '/framework/includes/woo-functions.php' );
			}

			/* Custom css and js*/
			require_once get_parent_theme_file_path( '/framework/settings/custom-css-js.php' );

			// Register custom shortcodes
			if ( class_exists( 'Vc_Manager' ) ) {
				require_once get_parent_theme_file_path( '/framework/includes/visual-composer.php' );
			}
		}

	}

	new Fastshop_framework();
}
