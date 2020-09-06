<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( !class_exists( 'Fastshop_MetaboxOption' ) ) {
	class Fastshop_MetaboxOption extends Fastshop_ThemeOption
	{
		public function __construct()
		{
			CSFramework_Metabox::instance( self::options() );
		}

		function options()
		{
			$options = array();

			// -----------------------------------------
			// Page Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_metabox_theme_options',
				'title'     => 'Custom Theme Options',
				'post_type' => 'page',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'page_banner_settings',
						'title'  => 'Banner Settings',
						'icon'   => 'fa fa-picture-o',
						'fields' => array(
							array(
								'id'         => 'fastshop_metabox_enable_banner',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Banner', 'fastshop' ),
								'default'    => false,
								'attributes' => array(
									'data-depend-id' => 'fastshop_metabox_enable_banner',
								),
							),
							array(
								'id'      => 'bg_banner_page',
								'type'    => 'background',
								'title'   => 'Background Banner',
								'default' => array(
									'image'      => '',
									'repeat'     => 'repeat-x',
									'position'   => 'center center',
									'attachment' => 'fixed',
									'size'       => 'cover',
									'color'      => '#ffbc00',
								),
							),
							array(
								'id'      => 'height_banner',
								'type'    => 'number',
								'title'   => 'Height Banner',
								'default' => '400',
							),
							array(
								'id'      => 'page_margin_top',
								'type'    => 'number',
								'title'   => 'Margin Top',
								'default' => 0,
							),
							array(
								'id'      => 'page_margin_bottom',
								'type'    => 'number',
								'title'   => 'Margin Bottom',
								'default' => 0,
							),
						),
					),
					array(
						'name'   => 'page_theme_options',
						'title'  => 'Theme Options',
						'icon'   => 'fa fa-wordpress',
						'fields' => array(
							array(
								'id'      => 'hover_style',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Hover Dark Color', 'fastshop' ),
								'default' => false,
							),
							array(
								'id'    => 'metabox_fastshop_logo',
								'type'  => 'image',
								'title' => 'Logo',
							),
							array(
								'id'      => 'metabox_fastshop_main_color',
								'type'    => 'color_picker',
								'title'   => 'Main Color',
								'default' => '#0188CC',
								'rgba'    => true,
							),
							array(
								'id'      => 'bg_background_page',
								'type'    => 'background',
								'title'   => 'Background Page',
								'default' => array(
									'image'      => '',
									'repeat'     => 'repeat-x',
									'position'   => 'center center',
									'attachment' => 'fixed',
									'size'       => 'cover',
									'color'      => '',
								),
							),
						),
					),
					array(
						'name'   => 'header_footer_theme_options',
						'title'  => 'Header & Footer Settings',
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'         => 'fastshop_metabox_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'fastshop' ),
								'desc'       => esc_html__( 'Select a header layout', 'fastshop' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-01',
								'attributes' => array(
									'data-depend-id' => 'fastshop_metabox_used_header',
								),
							),
							array(
								'id'              => 'metabox_group_menu_header',
								'type'            => 'group',
								'title'           => esc_html__( 'Group Field', 'fastshop' ),
								'button_title'    => esc_html__( 'Add New', 'fastshop' ),
								'accordion_title' => esc_html__( 'Add New Item', 'fastshop' ),
								'dependency'      => array(
									'fastshop_metabox_used_header', 'any', 'style-02,style-03,style-05,style-06,style-08,
						style-09,style-10,style-14,style-16,style-17,style-19,style-23',
								),
								'fields'          => array(
									array(
										'id'    => 'title_item',
										'type'  => 'text',
										'title' => 'Title',
									),
									array(
										'id'      => 'link_item',
										'type'    => 'text',
										'title'   => esc_html__( 'Link Item', 'fastshop' ),
										'default' => '#',
									),
									array(
										'id'      => 'icon_item',
										'type'    => 'icon',
										'title'   => esc_html__( 'Icon Item', 'fastshop' ),
										'default' => 'fa fa-phone',
									),
								),
							),
							array(
								'id'         => 'metabox_icon_header',
								'type'       => 'image',
								'title'      => esc_html__( 'Icon Contact', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_metabox_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'         => 'metabox_header_support',
								'type'       => 'text',
								'title'      => esc_html__( 'Phone Support', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_metabox_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'         => 'metabox_header_email',
								'type'       => 'text',
								'title'      => esc_html__( 'Email Support', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_metabox_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'      => 'fastshop_metabox_footer_options',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Select Footer Builder', 'fastshop' ),
								'options' => self::get_footer_preview(),
								'default' => 'default',
							),
						),
					),
					array(
						'name'   => 'vertical_theme_options',
						'title'  => 'Vertical Settings',
						'icon'   => 'fa fa-bar-chart',
						'fields' => array(
							array(
								'id'         => 'metabox_enable_vertical_menu',
								'type'       => 'select',
								'options'    => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'attributes' => array(
									'data-depend-id' => 'metabox_enable_vertical_menu',
								),
								'title'      => esc_html__( 'Vertical Menu', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header', 'any', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22',
								),
							),
							array(
								'id'         => 'block_vertical_menu',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Vertical Block', 'fastshop' ),
								'desc'       => esc_html__( 'can not toggle vertical menu', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => 'Vertical Menu Title',
								'id'         => 'metabox_vertical_menu_title',
								'type'       => 'text',
								'default'    => esc_html__( 'Shop By Category', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => 'Vertical Menu Button show all text',
								'id'         => 'metabox_vertical_menu_button_all_text',
								'type'       => 'text',
								'default'    => esc_html__( 'All Categories', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),

							array(
								'title'      => 'Vertical Menu Button close text',
								'id'         => 'metabox_vertical_menu_button_close_text',
								'type'       => 'text',
								'default'    => esc_html__( 'Close', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => esc_html__( 'Collapse', 'fastshop' ),
								'id'         => 'metabox_click_open_vertical_menu',
								'type'       => 'select',
								'options'    => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'desc'       => esc_html__( 'Vertical menu will expand on click', 'fastshop' ),
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => esc_html__( 'The number of visible vertical menu items', 'fastshop' ),
								'desc'       => esc_html__( 'The number of visible vertical menu items', 'fastshop' ),
								'id'         => 'metabox_vertical_item_visible',
								'default'    => 10,
								'type'       => 'number',
								'dependency' => array(
									'fastshop_metabox_used_header|metabox_enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
						),
					),
				),
			);

			// -----------------------------------------
			// Page Footer Meta box Options            -
			// -----------------------------------------

			$options[] = array(
				'id'        => '_custom_footer_options',
				'title'     => 'Custom Footer Options',
				'post_type' => 'footer',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'FOOTER STYLE',
						'fields' => array(
							array(
								'id'       => 'fastshop_footer_style',
								'type'     => 'select_preview',
								'title'    => esc_html__( 'Footer Style', 'fastshop' ),
								'subtitle' => esc_html__( 'Select a Footer Style', 'fastshop' ),
								'options'  => self::get_footer_options(),
								'default'  => 'default',
							),
						),
					),
				),
			);

			// -----------------------------------------
			// Page Testimonials Meta box Options      -
			// -----------------------------------------

			$options[] = array(
				'id'        => '_custom_testimonial_options',
				'title'     => 'Custom Testimonial Options',
				'post_type' => 'testimonial',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'Testimonial info',
						'fields' => array(
							array(
								'id'        => 'avatar_testimonial',
								'type'      => 'image',
								'title'     => 'Avatar',
								'add_title' => 'Add Avatar',
							),
							array(
								'id'      => 'select_rating',
								'type'    => 'radio',
								'title'   => 'Rating',
								'options' => array(
									'1' => esc_html__( '1 Star', 'fastshop' ),
									'2' => esc_html__( '2 Star', 'fastshop' ),
									'3' => esc_html__( '3 Star', 'fastshop' ),
									'4' => esc_html__( '4 Star', 'fastshop' ),
									'5' => esc_html__( '5 Star', 'fastshop' ),
								),
							),
							array(
								'id'    => 'name_testimonial',
								'type'  => 'text',
								'title' => 'Name',
							),
							array(
								'id'    => 'position_testimonial',
								'type'  => 'text',
								'title' => 'Position',
							),
						),
					),
				),
			);

			// -----------------------------------------
			// Page Product Meta box Options      	   -
			// -----------------------------------------

			$options[] = array(
				'id'        => '_custom_product_options',
				'title'     => 'Custom Product Options',
				'post_type' => 'product',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'link_video',
						'fields' => array(
							array(
								'id'    => 'fastshop_product_video',
								'type'  => 'text',
								'title' => 'Link Video',
							),
						),
					),
				),
			);

			// -----------------------------------------
			// Page Side Meta box Options              -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_page_side_options',
				'title'     => 'Custom Page Side Options',
				'post_type' => 'page',
				'context'   => 'side',
				'priority'  => 'default',
				'sections'  => array(
					array(
						'name'   => 'page_option',
						'fields' => array(
							array(
								'id'      => 'sidebar_page_layout',
								'type'    => 'image_select',
								'title'   => 'Single Post Sidebar Position',
								'desc'    => 'Select sidebar position on Page.',
								'options' => array(
									'left'  => CS_URI . '/assets/images/left-sidebar.png',
									'right' => CS_URI . '/assets/images/right-sidebar.png',
									'full'  => CS_URI . '/assets/images/default-sidebar.png',
								),
								'default' => 'left',
							),
							array(
								'id'         => 'page_sidebar',
								'type'       => 'select',
								'title'      => 'Page Sidebar',
								'options'    => self::get_sidebar_options(),
								'dependency' => array( 'sidebar_page_layout_full', '==', false ),
							),
							array(
								'id'    => 'page_extra_class',
								'type'  => 'text',
								'title' => 'Extra Class',
							),
						),
					),
				),
			);

			// -----------------------------------------
			// Post Side Meta box Options              -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_post_options',
				'title'     => 'Custom Post Options',
				'post_type' => 'post',
				'context'   => 'side',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'post_option',
						'fields' => array(
							array(
								'id'      => 'size_post_layout',
								'type'    => 'image_select',
								'title'   => 'Size Post image',
								'options' => array(
									'1' => get_theme_file_uri( 'assets/images/1.jpg' ),
									'2' => get_theme_file_uri( 'assets/images/2.jpg' ),
									'3' => get_theme_file_uri( 'assets/images/3.jpg' ),
								),
								'default' => '1',
							),
						),
					),
				),
			);

			return $options;
		}
	}

	new Fastshop_MetaboxOption();
}


