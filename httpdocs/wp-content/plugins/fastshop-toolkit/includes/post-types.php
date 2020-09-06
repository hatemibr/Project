<?php
/**
 * @version    1.0
 * @package    Fastshop_Toolkit
 * @author     KuteThemes
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( !class_exists( 'Fastshop_Toolkit_Posttype' ) ) {
	class Fastshop_Toolkit_Posttype
	{

		public function __construct()
		{
			add_action( 'init', array( &$this, 'init' ), 9999 );
		}

		public static function init()
		{
			/*Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'fastshop-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'fastshop-toolkit' ),
					'add_new'            => __( 'Add new', 'fastshop-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'fastshop-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'fastshop-toolkit' ),
					'new_item'           => __( 'New menu item', 'fastshop-toolkit' ),
					'view_item'          => __( 'View menu item', 'fastshop-toolkit' ),
					'search_items'       => __( 'Search menu items', 'fastshop-toolkit' ),
					'not_found'          => __( 'No menu items found', 'fastshop-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'fastshop-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'fastshop-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'fastshop-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'fastshop-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'fastshop_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );

			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'fastshop-toolkit' ),
					'singular_name'      => __( 'Footers', 'fastshop-toolkit' ),
					'add_new'            => __( 'Add New', 'fastshop-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'fastshop-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'fastshop-toolkit' ),
					'new_item'           => __( 'New footer', 'fastshop-toolkit' ),
					'view_item'          => __( 'View footer', 'fastshop-toolkit' ),
					'search_items'       => __( 'Search template footer', 'fastshop-toolkit' ),
					'not_found'          => __( 'No template items found', 'fastshop-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'fastshop-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'fastshop-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'fastshop-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'fastshop-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'fastshop_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );

			/* Testimonials */

			$labels = array(
				'name'               => _x( 'Testimonials', 'fastshop-toolkit' ),
				'singular_name'      => _x( 'Testimonial', 'fastshop-toolkit' ),
				'add_new'            => __( 'Add New', 'fastshop-toolkit' ),
				'all_items'          => __( 'Testimonials', 'fastshop-toolkit' ),
				'add_new_item'       => __( 'Add New Testimonial', 'fastshop-toolkit' ),
				'edit_item'          => __( 'Edit Testimonial', 'fastshop-toolkit' ),
				'new_item'           => __( 'New Testimonial', 'fastshop-toolkit' ),
				'view_item'          => __( 'View Testimonial', 'fastshop-toolkit' ),
				'search_items'       => __( 'Search Testimonial', 'fastshop-toolkit' ),
				'not_found'          => __( 'No Testimonial found', 'fastshop-toolkit' ),
				'not_found_in_trash' => __( 'No Testimonial found in Trash', 'fastshop-toolkit' ),
				'parent_item_colon'  => __( 'Parent Testimonial', 'fastshop-toolkit' ),
				'menu_name'          => __( 'Testimonials', 'fastshop-toolkit' ),
			);
			$args   = array(
				'labels'              => $labels,
				'description'         => 'Post type Testimonial',
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
				),
				'hierarchical'        => false,
				'rewrite'             => true,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'fastshop_menu',
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				'menu_icon'           => 'dashicons-editor-quote',
			);

			register_post_type( 'testimonial', $args );
		}
	}

	new Fastshop_Toolkit_Posttype();
}
