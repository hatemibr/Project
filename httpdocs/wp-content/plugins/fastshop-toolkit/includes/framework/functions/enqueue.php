<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !function_exists( 'cs_admin_enqueue_scripts' ) ) {
	function cs_admin_enqueue_scripts( $hook )
	{
	    $cs_uri = CS_URI;
		if ( is_ssl() ) {
			$cs_uri = str_replace( 'http://', 'https://', CS_URI );
		}
		// admin utilities
		wp_enqueue_media();

		// wp core styles
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		// framework core styles
		wp_enqueue_style( 'cs-framework', $cs_uri . '/assets/css/cs-framework.css', array(), '1.0.0', 'all' );
		wp_enqueue_style( 'font-awesome', $cs_uri . '/assets/css/font-awesome.css', array(), '4.2.0', 'all' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'cs-framework-rtl', $cs_uri . '/assets/css/cs-framework-rtl.css', array(), '1.0.0', 'all' );
		}

		// wp core scripts
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );

		// framework core scripts
		wp_enqueue_script( 'cs-plugins', $cs_uri . '/assets/js/cs-plugins.js', array(), '1.0.0', true );
		wp_enqueue_script( 'cs-framework', $cs_uri . '/assets/js/cs-framework.js', array( 'cs-plugins' ), '1.0.0', true );


		/* CUSTOM FRAMEWORK */

		wp_enqueue_style( 'customs-framework', $cs_uri . '/assets/css/custom.css', array(), '1.0.0', 'all' );
		wp_enqueue_script( 'cs-select-preview', $cs_uri . '/fields/select_preview/select_preview.js', array( 'cs-plugins' ), '1.0.0', true );

		if ( $hook == 'fastshop_page_fastshop' ) {
			// ACE Editor
			wp_enqueue_script( 'cs-vendor-ace', $cs_uri . '/fields/ace_editor/assets/ace.js', array(), '1.0.0', true );
			wp_enqueue_script( 'cs-vendor-ace-mode', $cs_uri . '/fields/ace_editor/assets/mode-css.js', array(), '1.0.0', true );

			wp_enqueue_script( 'cs-vendor-ace-language_tools', $cs_uri . '/fields/ace_editor/assets/ext-language_tools.js', array(), '1.0.0', true );
			wp_enqueue_script( 'cs-vendor-ace-css', $cs_uri . '/fields/ace_editor/assets/css.js', array(), '1.0.0', true );
			wp_enqueue_script( 'cs-vendor-ace-text', $cs_uri . '/fields/ace_editor/assets/text.js', array(), '1.0.0', true );
			wp_enqueue_script( 'cs-vendor-ace-javascript', $cs_uri . '/fields/ace_editor/assets/javascript.js', array(), '1.0.0', true );
			// You do not need to use a separate file if you do not like.
			wp_enqueue_script( 'cs-vendor-ace-load', $cs_uri . '/fields/ace_editor/assets/ace-load.js', array(), '1.0.0', true );
		}
	}

	add_action( 'admin_enqueue_scripts', 'cs_admin_enqueue_scripts' );
}