<?php
if ( !class_exists( 'Fastshop_MegaMenu' ) ) {
	class Fastshop_MegaMenu
	{
		public $custom_fields;

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct()
		{
			$this->custom_fields = array(
				'font_icon',
				'item_icon_type',
				'mega_menu_width',
				'mega_menu_url',
				'img_note',
			);
			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );
			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );
			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );
			$this->includes();
			add_filter( 'fastshop_main_custom_css', array( $this, 'custom_css_megamenu' ) );
		} // end constructor

		/**
		 * Add custom fields to $item nav object
		 * in order to be used in custom Walker
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function add_custom_nav_fields( $menu_item )
		{
			foreach ( $this->custom_fields as $key ) {
				$menu_item->$key = get_post_meta( $menu_item->ID, '_menu_item_megamenu_' . $key, true );
			}

			return $menu_item;
		}

		/**
		 * Save menu custom fields
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args )
		{
			foreach ( $this->custom_fields as $key ) {
				if ( !isset( $_REQUEST['menu-item-megamenu-' . $key][$menu_item_db_id] ) ) {
					$_REQUEST['menu-item-megamenu-' . $key][$menu_item_db_id] = '';
				}
				$value = $_REQUEST['menu-item-megamenu-' . $key][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_megamenu_' . $key, $value );
			}
		}

		/**
		 * Define new Walker edit
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function edit_walker( $walker, $menu_id )
		{
			return 'Walker_Nav_Menu_Edit_Custom';
		}

		function includes()
		{
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/nav_menu_custom_fields.php' );
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/nav_edit_custom_walker.php' );
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/walker.php' );
		}

		public function custom_css_megamenu( $css )
		{
			$args        = array(
				'posts_per_page' => -1,
				'post_type'      => 'megamenu',
				'post_status'    => 'publish',
			);
			$posts_array = get_posts( $args );
			if ( $posts_array ) {
				$shortcodes_custom_css = '';
				foreach ( $posts_array as $post ) {
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_wpb_post_custom_css', true );
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_Fastshop_Shortcode_custom_css', true );
				}
				if ( !empty( $shortcodes_custom_css ) ) {
					$css .= $shortcodes_custom_css;
				}
			}

			return $css;
		}
	}

	new Fastshop_MegaMenu();
}