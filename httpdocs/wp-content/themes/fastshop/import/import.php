<?php
// Prevent direct access to this file
defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );
/**
 * Core class.
 *
 * @package  Ovic
 * @since    1.0
 */
if ( !class_exists( 'Ovic_Theme_Import' ) ) {
	class Ovic_Theme_Import
	{
		/**
		 * Define theme version.
		 *
		 * @var  string
		 */
		const VERSION = '1.0.0';

		public function __construct()
		{
			add_filter( 'ovic_import_config', array( $this, 'ovic_import_config' ) );
			add_filter( 'ovic_import_wooCommerce_attributes', array( $this, 'ovic_import_wooCommerce_attributes' ) );
		}

		function ovic_import_wooCommerce_attributes()
		{
			$attributes = array(
				array(
					'attribute_label'   => 'Size',
					'attribute_name'    => 'size',
					'attribute_type'    => 'select', // text, box_style, select
					'attribute_orderby' => 'menu_order',
					'attribute_public'  => '0',
				),
				array(
					'attribute_label'   => 'Color',
					'attribute_name'    => 'color',
					'attribute_type'    => 'select', // text, box_style, select
					'attribute_orderby' => 'menu_order',
					'attribute_public'  => '0',
				),
			);

			return $attributes;
		}

		function ovic_import_config( $data_filter )
		{
			$data_demo     = array();
			$registed_menu = array(
				'primary'        => esc_html__( 'Primary Menu', 'fastshop' ),
				'vertical_menu'  => esc_html__( 'Vertical Menu', 'fastshop' ),
				'top_right_menu' => esc_html__( 'Top Right Menu', 'fastshop' ),
			);
			$menu_location = array(
				'primary'        => 'Primary Menu',
				'vertical_menu'  => 'Vertical Menu',
				'top_right_menu' => 'Top Right Menu',
			);
			for ( $i = 1; $i <= 23; $i++ ) {
				$data_demo[] = array(
					'name'           => esc_html__( 'Demo ' . zeroise( $i, 2 ), 'fastshop' ),
					'slug'           => 'home-' . zeroise( $i, 2 ),
					'menus'          => $registed_menu,
					'homepage'       => 'Home ' . zeroise( $i, 2 ),
					'blogpage'       => 'Blog',
					'preview'        => get_theme_file_uri( 'import/previews/' . $i . '.jpg' ),
					'demo_link'      => 'https://fastshop.kutethemes.net/home-' . zeroise( $i, 2 ),
					'menu_locations' => $menu_location,
				);
			}
			$data_filter['data_import'] = array(
				'main_demo'        => 'https://fastshop.kutethemes.net',
				'theme_option'     => get_template_directory() . '/import/data/theme-options.txt',
				'content_path'     => get_template_directory() . '/import/data/content.xml',
				'content_path_rtl' => get_template_directory() . '/import/data/content-rtl.xml',
				'widget_path'      => get_template_directory() . '/import/data/widgets.wie',
				'revslider_path'   => get_template_directory() . '/import/revsliders/',
			);
			$data_filter['data_demos']  = $data_demo;

			return $data_filter;
		}
	}

	new Ovic_Theme_Import();
}