<?php
/**
 * Plugin Name: Fastshop Toolkit
 * Plugin URI:  http://kutethemes.net
 * Description: FastShop toolkit for Fastshop theme. Currently supports the following theme functionality: shortcodes, CPT.
 * Version:     1.1.1
 * Author:      Kutethemes Team
 * Author URI:  http://kutethemes.net
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fastshop-toolkit
 */

// Define url to this plugin file.
define( 'FASTSHOP_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );

// Define path to this plugin file.
define( 'FASTSHOP_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );

// Include function plugins if not include.
if ( !function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function fastshop_toolkit_load_textdomain()
{
	load_plugin_textdomain( 'fastshop-toolkit', false, FASTSHOP_TOOLKIT_PATH . 'languages' );
}

add_action( 'init', 'fastshop_toolkit_load_textdomain' );

// Run shortcode in widget text
add_filter( 'widget_text', 'do_shortcode' );

// Register custom post types
include_once( FASTSHOP_TOOLKIT_PATH . '/includes/post-types.php' );

// Register init

if ( !function_exists( 'fastshop_toolkit_init' ) ) {
	function fastshop_toolkit_init()
	{
		include_once( FASTSHOP_TOOLKIT_PATH . '/includes/init.php' );
		if ( class_exists( 'Vc_Manager' ) ) {
			// Register custom shortcodes
			include_once( FASTSHOP_TOOLKIT_PATH . '/includes/shortcode.php' );
		}
	}
}

add_action( 'fastshop_toolkit_init', 'fastshop_toolkit_init' );

if ( !function_exists( 'fastshop_toolkit_install' ) ) {
	function fastshop_toolkit_install()
	{
		do_action( 'fastshop_toolkit_init' );
	}
}
add_action( 'plugins_loaded', 'fastshop_toolkit_install', 11 );
