<?php
if ( !class_exists( 'Fastshop_Visual_Composer' ) ) {
	class Fastshop_Visual_Composer
	{
		public function __construct()
		{
			$this->define_constants();
			add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'vc_fonts' ) );
			add_action( 'vc_after_mapping', array( &$this, 'params' ) );
			add_action( 'vc_after_mapping', array( &$this, 'autocomplete' ) );
			/* Custom font Icon*/
			add_filter( 'vc_iconpicker-type-fastshopcustomfonts', array( &$this, 'iconpicker_type_fastshop_customfonts' ) );
			$this->map_shortcode();
			/* TEMPLATE DEFAULT */
			add_action( 'vc_load_default_templates_action', array( $this, 'fastshop_add_custom_template_for_vc' ) );
		}

		function is_url_exist( $url )
		{
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_NOBODY, true );
			curl_exec( $ch );
			$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			if ( $code == 200 ) {
				$status = true;
			} else {
				$status = false;
			}
			curl_close( $ch );

			return $status;
		}

		function fastshop_add_custom_template_for_vc()
		{
			$option_file_url = esc_url( 'https://fastshop.kutethemes.net/wp-content/uploads/template.txt' );
			if ( $this->is_url_exist( $option_file_url ) == true ) {
				$option_content  = wp_remote_get( $option_file_url );
				$option_content  = $option_content['body'];
				$option_content  = base64_decode( $option_content );
				$options_configs = json_decode( $option_content, true );
				foreach ( $options_configs as $value ) {
					$data                 = array();
					$data['name']         = $value['name'];
					$data['weight']       = 1;
					$data['custom_class'] = 'custom_template_for_vc_custom_template';
					$data['content']      = $value['content'];
					vc_add_default_templates( $data );
				}
			}
		}

		/**
		 * Define  Constants.
		 */
		private function define_constants()
		{
			$this->define( 'FASTSHOP_SHORTCODE_PREVIEW', get_theme_file_uri( '/framework/assets/images/shortcode-previews/' ) );
			$this->define( 'FASTSHOP_PRODUCT_STYLE_PREVIEW', get_theme_file_uri( '/woocommerce/product-styles/' ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value )
		{
			if ( !defined( $name ) ) {
				define( $name, $value );
			}
		}

		function params()
		{
			if ( function_exists( 'fastshop_toolkit_vc_param' ) ) {
				fastshop_toolkit_vc_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
				fastshop_toolkit_vc_param( 'select_preview', array( $this, 'select_preview_field' ) );
				fastshop_toolkit_vc_param( 'number', array( $this, 'number_field' ) );
			}
		}

		/**
		 * load param autocomplete render
		 * */
		public function autocomplete()
		{
			add_filter( 'vc_autocomplete_fastshop_products_ids_callback', array( $this, 'productIdAutocompleteSuggester' ), 10, 1 );
			add_filter( 'vc_autocomplete_fastshop_products_ids_render', array( $this, 'productIdAutocompleteRender' ), 10, 1 );
		}

		/*
         * taxonomy_field
         * */
		public function taxonomy_field( $settings, $value )
		{
			$dependency = '';
			$value_arr  = $value;
			if ( !is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['hide_empty'] ) && $settings['hide_empty'] ) {
				$settings['hide_empty'] = 1;
			} else {
				$settings['hide_empty'] = 0;
			}
			if ( !empty( $settings['taxonomy'] ) ) {
				$terms_fields = array();
				if ( isset( $settings['placeholder'] ) && $settings['placeholder'] ) {
					$terms_fields[] = "<option value=''>" . $settings['placeholder'] . "</option>";
				}
				$terms = get_terms( $settings['taxonomy'], array( 'hide_empty' => false, 'parent' => $settings['parent'], 'hide_empty' => $settings['hide_empty'] ) );
				if ( $terms && !is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( !empty( $settings['size'] ) ) ? 'size="' . $settings['size'] . '"' : '';
				$multiple = ( !empty( $settings['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="fastshop_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
					. implode( $terms_fields )
					. '</select>';
			}

			return $output;
		}

		public function number_field( $settings, $value )
		{
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( !$value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;" />' . $suffix;

			return $output;
		}

		public function select_preview_field( $settings, $value )
		{
			ob_start();
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				$i       = 0;
				?>
                <div class="container-select_preview">
                    <select id="fastshop_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                            name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                            class="fastshop_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
						<?php foreach ( $options as $k => $option ): ?>
							<?php
							if ( $i == 0 ) {
								$first_value = $k;
							}
							$i++;
							?>
							<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                            <option data-img="<?php echo esc_url( $option['img'] ); ?>"
                                    value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['alt'] ) ?></option>
						<?php endforeach; ?>
                    </select>
                    <div class="image-preview">
						<?php if ( isset( $options[$value] ) && $options[$value] && ( isset( $options[$value]['img'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[$value]['img'] ); ?>" alt="">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[$default]['img'] ); ?>" alt="">
						<?php endif; ?>
                    </div>
                </div>
				<?php
			}

			return ob_get_clean();
		}

		/**
		 * Suggester for autocomplete by id/name/title/sku
		 * @since 1.0
		 *
		 * @param $query
		 * @author Reapple
		 * @return array - id's from products with title/sku.
		 */
		public function productIdAutocompleteSuggester( $query )
		{
			global $wpdb;
			$product_id      = (int)$query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
    					FROM {$wpdb->posts} AS a
    					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
    					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : -1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && !empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'fastshop' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'fastshop' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'fastshop' ) . ': ' . $value['sku'] : '' );
					$results[]     = $data;
				}
			}

			return $results;
		}

		/**
		 * Find product by id
		 * @since 1.0
		 *
		 * @param $query
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function productIdAutocompleteRender( $query )
		{
			$query = trim( $query['value'] ); // get value from requested
			if ( !empty( $query ) ) {
				// get product
				$product_object = wc_get_product( (int)$query );
				if ( is_object( $product_object ) ) {
					$product_sku         = $product_object->get_sku();
					$product_title       = $product_object->get_title();
					$product_id          = $product_object->get_id();
					$product_sku_display = '';
					if ( !empty( $product_sku ) ) {
						$product_sku_display = ' - ' . esc_html__( 'Sku', 'fastshop' ) . ': ' . $product_sku;
					}
					$product_title_display = '';
					if ( !empty( $product_title ) ) {
						$product_title_display = ' - ' . esc_html__( 'Title', 'fastshop' ) . ': ' . $product_title;
					}
					$product_id_display = esc_html__( 'Id', 'fastshop' ) . ': ' . $product_id;
					$data               = array();
					$data['value']      = $product_id;
					$data['label']      = $product_id_display . $product_title_display . $product_sku_display;

					return !empty( $data ) ? $data : false;
				}

				return false;
			}

			return false;
		}

		public function vc_fonts( $fonts_list )
		{
			/* Gotham */
			$Gotham              = new stdClass();
			$Gotham->font_family = "Gotham";
			$Gotham->font_styles = "100,300,400,600,700";
			$Gotham->font_types  = "300 Light:300:light,400 Normal:400:normal";
			$fonts               = array( $Gotham );

			return array_merge( $fonts_list, $fonts );
		}

		/* Custom Font icon*/
		function iconpicker_type_fastshop_customfonts( $icons )
		{
			$icons['Flaticon'] = array(
				array( 'flaticon-01search' => '01' ),
				array( 'flaticon-02arrows' => '02' ),
				array( 'flaticon-03wishlist' => '03' ),
				array( 'flaticon-04shopcart' => '04' ),
				array( 'flaticon-05menu' => '05' ),
				array( 'flaticon-06accessories' => '06' ),
				array( 'flaticon-07furniture' => '07' ),
				array( 'flaticon-08women-shoes' => '08' ),
				array( 'flaticon-09handbag' => '09' ),
				array( 'flaticon-10watch' => '10' ),
				array( 'flaticon-11sport-shoes' => '11' ),
				array( 'flaticon-12clothes' => '12' ),
				array( 'flaticon-13male-telemarketer' => '13' ),
				array( 'flaticon-14credit-card-security' => '14' ),
				array( 'flaticon-15return-of-investment' => '15' ),
				array( 'flaticon-16transport' => '16' ),
				array( 'flaticon-20shirt' => '17' ),
				array( 'flaticon-21sunglass' => '18' ),
				array( 'flaticon-22shoes' => '19' ),
				array( 'flaticon-23book' => '20' ),
				array( 'flaticon-24accessories' => '21' ),
				array( 'flaticon-25bag' => '22' ),
			);

			return $icons;
		}

		public static function map_shortcode()
		{
			/* ADD PARAM*/
			vc_add_params(
				'vc_single_image',
				array(
					array(
						'param_name' => 'has_radius',
						'heading'    => esc_html__( 'Border Radius', 'fastshop' ),
						'group'      => esc_html__( 'Image Effect', 'fastshop' ),
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'On', 'fastshop' )  => 'radius-on',
							esc_html__( 'Off', 'fastshop' ) => 'none',
						),
						'sdt'        => 'radius-on',
					),
					array(
						'param_name' => 'image_effect',
						'heading'    => esc_html__( 'Effect', 'fastshop' ),
						'group'      => esc_html__( 'Image Effect', 'fastshop' ),
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Normal Effect', 'fastshop' )             => 'normal-effect',
							esc_html__( 'Normal Effect Dark Color', 'fastshop' )  => 'normal-effect dark-color-bg',
							esc_html__( 'Normal Effect Light Color', 'fastshop' ) => 'normal-effect light-color-bg',
							esc_html__( 'Plus Zoom', 'fastshop' )                 => 'plus-zoom',
							esc_html__( 'Underline Zoom', 'fastshop' )            => 'underline-zoom',
							esc_html__( 'Underline Center', 'fastshop' )          => 'underline-center',
							esc_html__( 'None', 'fastshop' )                      => 'none',
						),
						'sdt'        => 'normal-effect',
					),
				)
			);
			/* Map New Banner */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Banner', 'fastshop' ),
					'base'        => 'fastshop_banner', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a Banner lists.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'banner/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'banner/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'banner/style2.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "image Banner", "fastshop" ),
							"param_name" => "image_banner",
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style2' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", "fastshop" ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "Title of shortcode.", "fastshop" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => 'style2',
							),
						),
						array(
							"type"        => "textarea",
							"heading"     => esc_html__( "Descriptions", "fastshop" ),
							"param_name"  => "descriptions",
							"description" => esc_html__( "Descriptions of shortcode.", "fastshop" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2' ),
							),
						),
						array(
							"type"        => "vc_link",
							"heading"     => esc_html__( "Link to", "fastshop" ),
							"param_name"  => "link",
							"description" => esc_html__( "Link to something.", "fastshop" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2' ),
							),
						),
						array(
							"type"       => "attach_images",
							"heading"    => esc_html__( "Gallery Banner", "fastshop" ),
							"param_name" => "gallery_banner",
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'default', 'style1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'banner_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Section tabs */
			vc_map(
				array(
					'name'                      => esc_html__( 'Section', 'fastshop' ),
					'base'                      => 'vc_tta_section',
					'icon'                      => 'icon-wpb-ui-tta-section',
					'allowed_container_element' => 'vc_row',
					'is_container'              => true,
					'show_settings_on_create'   => false,
					'as_child'                  => array(
						'only' => 'vc_tta_tour,vc_tta_tabs,vc_tta_accordion',
					),
					'category'                  => esc_html__( 'Content', 'fastshop' ),
					'description'               => esc_html__( 'Section for Tabs, Tours, Accordions.', 'fastshop' ),
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'title',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'fastshop' ),
						),
						array(
							'type'        => 'el_id',
							'param_name'  => 'tab_id',
							'settings'    => array(
								'auto_generate' => true,
							),
							'heading'     => esc_html__( 'Section ID', 'fastshop' ),
							'description' => wp_kses( esc_html__( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'fastshop' ), array( 'a' => array( 'href' => array() ) ) ),
						),
						array(
							'type'        => 'checkbox',
							'param_name'  => 'add_icon',
							'heading'     => esc_html__( 'Add icon?', 'fastshop' ),
							'description' => esc_html__( 'Add icon next to section title.', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'i_position',
							'value'       => array(
								esc_html__( 'Before title', 'fastshop' ) => 'left',
								esc_html__( 'After title', 'fastshop' )  => 'right',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'heading'     => esc_html__( 'Icon position', 'fastshop' ),
							'description' => esc_html__( 'Select icon position.', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'fastshop' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'fastshop' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'fastshop' ) => 'fontflaticon',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'fastshop' ),
						),
						array(
							'param_name'  => 'icon_fastshopcustomfonts',
							'heading'     => esc_html__( 'Icon', 'fastshop' ),
							'description' => esc_html__( 'Select icon from library.', 'fastshop' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'fastshopcustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'fastshop' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							// default value to backend editor admin_label
							'settings'    => array(
								'emptyIcon'    => false,
								// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,
								// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'accordions_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                   => 'VcBackendTtaSectionView',
					'custom_markup'             => '
                    <div class="vc_tta-panel-heading">
                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
                    </div>
                    <div class="vc_tta-panel-body">
                        {{ editor_controls }}
                        <div class="{{ container-class }}">
                        {{ content }}
                        </div>
                    </div>',
					'default_content'           => '',
				)
			);
			/*Map New section title */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Section Title', 'fastshop' ),
					'base'        => 'fastshop_title', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a custom title.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'title/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'title/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'title/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'title/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 04', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'title/style4.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'fastshop' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'fastshop' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link.', 'fastshop' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style4' ),
							),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Descriptions', 'fastshop' ),
							'param_name'  => 'des',
							'description' => esc_html__( 'The Descriptions of shortcode', 'fastshop' ),
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							'param_name' => 'title_align',
							'heading'    => esc_html__( 'Title Align', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'fastshop' )   => 'text-left',
								esc_html__( 'Right', 'fastshop' )  => 'text-right',
								esc_html__( 'Center', 'fastshop' ) => 'text-center',
							),
							'sdt'        => 'text-left',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'default', 'style1', 'style2', 'style3' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'title_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			// Map new Tabs element.
			vc_map(
				array(
					'name'                    => esc_html__( 'Fastshop: Tabs', 'fastshop' ),
					'base'                    => 'fastshop_tabs',
					'icon'                    => 'icon-wpb-ui-tab-content',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description'             => esc_html__( 'Tabs content', 'fastshop' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 04', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style4.jpg',
								),
								'style5'  => array(
									'alt' => 'Style 05', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style5.jpg',
								),
								'style6'  => array(
									'alt' => 'Style 06', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style6.jpg',
								),
								'style7'  => array(
									'alt' => 'Style 07', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style7.jpg',
								),
								'style8'  => array(
									'alt' => 'Style 08', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style8.jpg',
								),
								'style9'  => array(
									'alt' => 'Style 09', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style9.jpg',
								),
								'style10' => array(
									'alt' => 'Style 10', //FASTSHOP_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'tabs/style10.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Color Tabs', 'fastshop' ),
							'param_name' => 'the_color',
							"dependency" => array( "element" => "style", "value" => array( 'style9' ) ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'the_title',
							'value'       => '',
							'admin_label' => true,
							'group'       => esc_html__( 'Title Setting', 'fastshop' ),
							"dependency"  => array( "element" => "style", "value" => array( 'style1', 'style6', 'style8', 'style9', 'style10' ) ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'fastshop' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link with title.(note: work with Title shortcode)', 'fastshop' ),
							'group'       => esc_html__( 'Title Setting', 'fastshop' ),
							"dependency"  => array( "element" => "style", "value" => array( 'style1', 'style6', 'style10' ) ),
						),
						vc_map_add_css_animation(),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => '1',
								esc_html__( 'No', 'fastshop' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'param_name' => 'position_tabs',
							'heading'    => esc_html__( 'Position Link Tabs', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'fastshop' )   => 'left',
								esc_html__( 'Right', 'fastshop' )  => 'right',
								esc_html__( 'Center', 'fastshop' ) => 'center',
							),
							'std'        => 'left',
							"dependency" => array( "element" => "style", "value" => array( 'style3', 'style4', 'style7' ) ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Active Section', 'fastshop' ),
							'param_name' => 'active_section',
							'std'        => '1',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Padding Tabs', 'fastshop' ),
							'param_name'  => 'padding_tabs',
							'std'         => '0',
							'description' => esc_html__( 'Ex: 60px', 'fastshop' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'fastshop' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'fastshop' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'fastshop' ),
						),
						array(
							'param_name'       => 'tabs_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'fastshop' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'fastshop' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaTabsView',
					'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
						. '<ul class="vc_tta-tabs-list">'
						. '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
						. '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'fastshop' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'fastshop' ), 2 ) . '"][/vc_tta_section]
                    ',
					'admin_enqueue_js'        => array(
						vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
					),
				)
			);
			/* Map New Accordion */
			vc_map(
				array(
					'name'                    => esc_html__( 'Fastshop: Accordions', 'fastshop' ),
					'base'                    => 'fastshop_accordions',
					'icon'                    => 'icon-wpb-ui-accordion',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description'             => esc_html__( 'Accordions content', 'fastshop' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'default', //fastshop_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/accordion/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01', //fastshop_SHORTCODE_PREVIEW
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/accordion/style1.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => '1',
								esc_html__( 'No', 'fastshop' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Tabs active', 'fastshop' ),
							'param_name' => 'active_tab',
							'sdt'        => '1',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'fastshop' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'fastshop' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'fastshop' ),
						),
						array(
							'param_name'       => 'accordions_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'fastshop' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'fastshop' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaAccordionView',
					'custom_markup'           => '
                        <div class="vc_tta-container" data-vc-action="collapseAll">
                            <div class="vc_general vc_tta vc_tta-accordion vc_tta-color-backend-accordion-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left vc_tta-gap-2">
                               <div class="vc_tta-panels vc_clearfix {{container-class}}">
                                  {{ content }}
                                  <div class="vc_tta-panel vc_tta-section-append">
                                     <div class="vc_tta-panel-heading">
                                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left">
                                           <a href="javascript:;" aria-expanded="false" class="vc_tta-backend-add-control">
                                               <span class="vc_tta-title-text">' . esc_attr__( 'Add Section', 'fastshop' ) . '</span>
                                                <i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>
                                            </a>
                                        </h4>
                                     </div>
                                  </div>
                               </div>
                            </div>
                        </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_attr__( 'Section', 'fastshop' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_attr__( 'Section', 'fastshop' ), 2 ) . '"][/vc_tta_section]
					',
				)
			);
			// Map new Products
			// CUSTOM PRODUCT SIZE
			$product_size_width_list = array();
			$width                   = 300;
			$height                  = 300;
			$crop                    = 1;
			if ( function_exists( 'wc_get_image_size' ) ) {
				$size   = wc_get_image_size( 'shop_catalog' );
				$width  = isset( $size['width'] ) ? $size['width'] : $width;
				$height = isset( $size['height'] ) ? $size['height'] : $height;
				$crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
			}
			for ( $i = 100; $i < $width; $i = $i + 10 ) {
				array_push( $product_size_width_list, $i );
			}
			$product_size_list                         = array();
			$product_size_list[$width . 'x' . $height] = $width . 'x' . $height;
			foreach ( $product_size_width_list as $k => $w ) {
				$w = intval( $w );
				if ( isset( $width ) && $width > 0 ) {
					$h = round( $height * $w / $width );
				} else {
					$h = $w;
				}
				$product_size_list[$w . 'x' . $h] = $w . 'x' . $h;
			}
			$product_size_list['Custom'] = 'custom';
			$attributes_tax              = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			$attributes = array();
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[$attribute->attribute_label] = $attribute->attribute_name;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Products', 'fastshop' ),
					'base'        => 'fastshop_products', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a product list.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'the_title',
							'admin_label' => true,
							"dependency"  => array( "element" => "product_style", "value" => array( '2', '9' ) ),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Sub title', 'fastshop' ),
							'param_name' => 'des',
							"dependency" => array( "element" => "product_style", "value" => array( '2' ) ),
						),
						array(
							'param_name' => 'title_align',
							'heading'    => esc_html__( 'Title align', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'fastshop' )   => 'text-left',
								esc_html__( 'Right', 'fastshop' )  => 'text-right',
								esc_html__( 'Center', 'fastshop' ) => 'text-center',
							),
							'std'        => 'text-left',
							"dependency" => array( "element" => "product_style", "value" => array( '2', '9' ) ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Product List style', 'fastshop' ),
							'param_name'  => 'productsliststyle',
							'value'       => array(
								esc_html__( 'Grid Bootstrap', 'fastshop' ) => 'grid',
								esc_html__( 'Owl Carousel', 'fastshop' )   => 'owl',
							),
							'description' => esc_html__( 'Select a style for list', 'fastshop' ),
							'admin_label' => true,
							'std'         => 'grid',
						),
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Product style', 'fastshop' ),
							'value'       => array(
								'1'  => array(
									'alt' => esc_html__( 'Style 01', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-1.jpg',
								),
								'2'  => array(
									'alt' => esc_html__( 'Style 02', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-2.jpg',
								),
								'3'  => array(
									'alt' => esc_html__( 'Style 03', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-3.jpg',
								),
								'4'  => array(
									'alt' => esc_html__( 'Style 04', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-4.jpg',
								),
								'5'  => array(
									'alt' => esc_html__( 'Style 05', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-5.jpg',
								),
								'6'  => array(
									'alt' => esc_html__( 'Style 06', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-6.jpg',
								),
								'7'  => array(
									'alt' => esc_html__( 'Style 07', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-7.jpg',
								),
								'8'  => array(
									'alt' => esc_html__( 'Style 08', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-8.jpg',
								),
								'9'  => array(
									'alt' => esc_html__( 'Style 09', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-9.jpg',
								),
								'10' => array(
									'alt' => esc_html__( 'Style 10', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-10.jpg',
								),
								'11' => array(
									'alt' => esc_html__( 'Style 11', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-11.jpg',
								),
								'12' => array(
									'alt' => esc_html__( 'Style 12', 'fastshop' ),
									'img' => FASTSHOP_PRODUCT_STYLE_PREVIEW . 'content-product-style-12.jpg',
								),
							),
							'default'     => '1',
							'admin_label' => true,
							'param_name'  => 'product_style',
							'description' => esc_html__( 'Select a style for product item', 'fastshop' ),
						),
						array(
							'heading'          => esc_html__( 'Button More items', 'fastshop' ),
							'type'             => 'checkbox',
							'param_name'       => 'more_items',
							'edit_field_class' => 'vc_col-sm-4',
						),
						array(
							'heading'          => esc_html__( 'Add border image', 'fastshop' ),
							'type'             => 'checkbox',
							'param_name'       => 'add_border_img',
							'edit_field_class' => 'vc_col-sm-8',
						),
						array(
							'heading'          => esc_html__( 'None Categories?', 'fastshop' ),
							'type'             => 'checkbox',
							'std'              => '',
							'param_name'       => 'none_cat',
							"dependency"       => array( "element" => "product_style", "value" => array( '1' ) ),
							'edit_field_class' => 'vc_col-sm-4',
						),
						array(
							'heading'          => esc_html__( 'None Rating?', 'fastshop' ),
							'type'             => 'checkbox',
							'std'              => '',
							'param_name'       => 'none_rating',
							"dependency"       => array( "element" => "product_style", "value" => array( '1' ) ),
							'edit_field_class' => 'vc_col-sm-4',
						),
						array(
							'heading'          => esc_html__( 'None Border?', 'fastshop' ),
							'type'             => 'checkbox',
							'std'              => '',
							'param_name'       => 'none_border',
							"dependency"       => array( "element" => "product_style", "value" => array( '1' ) ),
							'edit_field_class' => 'vc_col-sm-4',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image size', 'fastshop' ),
							'param_name'  => 'product_image_size',
							'value'       => $product_size_list,
							'description' => esc_html__( 'Select a size for product', 'fastshop' ),
							'admin_label' => true,
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Width", 'fastshop' ),
							"param_name" => "product_custom_thumb_width",
							"value"      => $width,
							"suffix"     => esc_html__( "px", 'fastshop' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Height", 'fastshop' ),
							"param_name" => "product_custom_thumb_height",
							"value"      => $height,
							"suffix"     => esc_html__( "px", 'fastshop' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						/*Products */
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'fastshop' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => true,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'fastshop' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'fastshop' ),
							'std'         => '',
							'group'       => esc_html__( 'Products options', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Target', 'fastshop' ),
							'param_name'  => 'target',
							'value'       => array(
								esc_html__( 'Best Selling Products', 'fastshop' ) => 'best-selling',
								esc_html__( 'Top Rated Products', 'fastshop' )    => 'top-rated',
								esc_html__( 'Recent Products', 'fastshop' )       => 'recent-product',
								esc_html__( 'Product Category', 'fastshop' )      => 'product-category',
								esc_html__( 'Products', 'fastshop' )              => 'products',
								esc_html__( 'Featured Products', 'fastshop' )     => 'featured_products',
								esc_html__( 'On Sale', 'fastshop' )               => 'on_sale',
								esc_html__( 'On New', 'fastshop' )                => 'on_new',
							),
							'description' => esc_html__( 'Choose the target to filter products', 'fastshop' ),
							'std'         => 'recent-product',
							'group'       => esc_html__( 'Products options', 'fastshop' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'fastshop' ),
							"param_name"  => "orderby",
							"value"       => array(
								'',
								esc_html__( 'Date', 'fastshop' )          => 'date',
								esc_html__( 'ID', 'fastshop' )            => 'ID',
								esc_html__( 'Author', 'fastshop' )        => 'author',
								esc_html__( 'Title', 'fastshop' )         => 'title',
								esc_html__( 'Modified', 'fastshop' )      => 'modified',
								esc_html__( 'Random', 'fastshop' )        => 'rand',
								esc_html__( 'Comment count', 'fastshop' ) => 'comment_count',
								esc_html__( 'Menu order', 'fastshop' )    => 'menu_order',
								esc_html__( 'Sale price', 'fastshop' )    => '_sale_price',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort.", 'fastshop' ),
							"dependency"  => array( "element" => "target", "value" => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute' ) ),
							'group'       => esc_html__( 'Products options', 'fastshop' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'fastshop' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'fastshop' )  => 'ASC',
								esc_html__( 'DESC', 'fastshop' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'fastshop' ),
							"dependency"  => array( "element" => "target", "value" => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute' ) ),
							'group'       => esc_html__( 'Products options', 'fastshop' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Product per page', 'fastshop' ),
							'param_name' => 'per_page',
							'value'      => 6,
							"dependency" => array( "element" => "target", "value" => array( 'best-selling', 'top-rated', 'recent-product', 'product-category', 'featured_products', 'product_attribute', 'on_sale', 'on_new' ) ),
							'group'      => esc_html__( 'Products options', 'fastshop' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'fastshop' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => true,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'fastshop' ),
							"dependency"  => array( "element" => "target", "value" => array( 'products' ) ),
							'group'       => esc_html__( 'Products options', 'fastshop' ),
						),
						/* OWL Settings */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'fastshop' )  => '1',
								esc_html__( '2 Rows', 'fastshop' ) => '2',
								esc_html__( '3 Rows', 'fastshop' ) => '3',
								esc_html__( '4 Rows', 'fastshop' ) => '4',
								esc_html__( '5 Rows', 'fastshop' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'fastshop' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'fastshop' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' ) => 'rows-space-0',
								esc_html__( '10px', 'fastshop' )    => 'rows-space-10',
								esc_html__( '20px', 'fastshop' )    => 'rows-space-20',
								esc_html__( '30px', 'fastshop' )    => 'rows-space-30',
								esc_html__( '40px', 'fastshop' )    => 'rows-space-40',
								esc_html__( '50px', 'fastshop' )    => 'rows-space-50',
								esc_html__( '60px', 'fastshop' )    => 'rows-space-60',
								esc_html__( '70px', 'fastshop' )    => 'rows-space-70',
								esc_html__( '80px', 'fastshop' )    => 'rows-space-80',
								esc_html__( '90px', 'fastshop' )    => 'rows-space-90',
								esc_html__( '100px', 'fastshop' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array(
								"element" => "owl_number_row", "value" => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'fastshop' ),
							'param_name'  => 'owl_autoplay',
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Dots', 'fastshop' ),
							'param_name'  => 'owl_dots',
							'description' => esc_html__( "Show dots.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Navigation', 'fastshop' ),
							'param_name'  => 'owl_navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Navigation position', 'fastshop' ),
							'param_name' => 'owl_navigation_position',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' )            => '',
								esc_html__( 'Style 2 Center', 'fastshop' )     => 'nav2',
								esc_html__( 'Style 2 Top Left', 'fastshop' )   => 'nav2 top-left',
								esc_html__( 'Style 2 Top Right', 'fastshop' )  => 'nav2 top-right',
								esc_html__( 'Style 2 Top Center', 'fastshop' ) => 'nav2 top-center',
							),
							'std'        => '',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array( "element" => "owl_navigation", "value" => array( 'true' ) ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Postion Top", 'fastshop' ),
							"param_name"  => "owl_navigation_position_top",
							"std"         => "0",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-left', 'nav2 top-right', 'nav2 top-center' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Offset Right", 'fastshop' ),
							"param_name"  => "owl_navigation_offset_right",
							"std"         => "0",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-right' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Offset Left", 'fastshop' ),
							"param_name"  => "owl_navigation_offset_left",
							"std"         => "0",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-left' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'fastshop' ),
							'param_name'  => 'owl_loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'fastshop' ),
							"param_name"  => "owl_slidespeed",
							"value"       => "200",
							"suffix"      => esc_html__( "milliseconds", 'fastshop' ),
							"description" => esc_html__( 'Slide speed in milliseconds', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'fastshop' ),
							"param_name"  => "owl_margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'fastshop' ),
							"param_name"  => "owl_ls_items",
							"std"         => "4",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px and < 1500px )", 'fastshop' ),
							"param_name"  => "owl_lg_items",
							"std"         => "4",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'fastshop' ),
							"param_name"  => "owl_md_items",
							"std"         => "3",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'fastshop' ),
							"param_name"  => "owl_sm_items",
							"std"         => "2",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'fastshop' ),
							"param_name"  => "owl_xs_items",
							"std"         => "2",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'fastshop' ),
							"param_name"  => "owl_ts_items",
							"std"         => "1",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'owl' ),
							),
						),
						/* Bostrap setting */
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'fastshop' ),
							'param_name' => 'boostrap_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' ) => 'rows-space-0',
								esc_html__( '10px', 'fastshop' )    => 'rows-space-10',
								esc_html__( '20px', 'fastshop' )    => 'rows-space-20',
								esc_html__( '30px', 'fastshop' )    => 'rows-space-30',
								esc_html__( '40px', 'fastshop' )    => 'rows-space-40',
								esc_html__( '50px', 'fastshop' )    => 'rows-space-50',
								esc_html__( '60px', 'fastshop' )    => 'rows-space-60',
								esc_html__( '70px', 'fastshop' )    => 'rows-space-70',
								esc_html__( '80px', 'fastshop' )    => 'rows-space-80',
								esc_html__( '90px', 'fastshop' )    => 'rows-space-90',
								esc_html__( '100px', 'fastshop' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Boostrap settings', 'fastshop' ),
							"dependency" => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'fastshop' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '15',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'fastshop' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on landscape tablet', 'fastshop' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on portrait tablet', 'fastshop' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '4',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'fastshop' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '6',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'fastshop' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '1 item', 'fastshop' )  => '12',
								esc_html__( '2 items', 'fastshop' ) => '6',
								esc_html__( '3 items', 'fastshop' ) => '4',
								esc_html__( '4 items', 'fastshop' ) => '3',
								esc_html__( '5 items', 'fastshop' ) => '15',
								esc_html__( '6 items', 'fastshop' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							"dependency"  => array(
								"element" => "productsliststyle", "value" => array( 'grid' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'products_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* new icon box*/
			vc_map(
				array(
					'name'     => esc_html__( 'Fastshop: Icon Box', 'fastshop' ),
					'base'     => 'fastshop_iconbox',
					'category' => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'params'   => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'icon_box/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Text white',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'icon_box/style2.jpg',
								),
								'style2'  => array(
									'alt' => 'Icon main color',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'icon_box/style1.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'icon_box/style3.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'admin_label' => true,
						),
						array(
							'param_name'  => 'text_content',
							'heading'     => esc_html__( 'Content', 'fastshop' ),
							'type'        => 'textarea',
							'admin_label' => true,
						),
						array(
							'param_name' => 'icon_type',
							'heading'    => esc_html__( 'Icon Library', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Font Awesome', 'fastshop' ) => 'fontawesome',
								esc_html__( 'Other', 'fastshop' )        => 'fastshopcustomfonts',
							),
						),
						array(
							'param_name'  => 'icon_fastshopcustomfonts',
							'heading'     => esc_html__( 'Icon', 'fastshop' ),
							'description' => esc_html__( 'Select icon from library.', 'fastshop' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'fastshopcustomfonts',
							),
							'dependency'  => array(
								'element' => 'icon_type',
								'value'   => 'fastshopcustomfonts',
							),
						),
						array(
							'param_name'  => 'icon_fontawesome',
							'heading'     => esc_html__( 'Icon', 'fastshop' ),
							'description' => esc_html__( 'Select icon from library.', 'fastshop' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon'    => true,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'icon_type',
								'value'   => 'fontawesome',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'fastshop' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'fastshop' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'fastshop' ),
						),
						array(
							'param_name'       => 'iconbox_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New blog */
			$categories_array = array(
				esc_html__( 'All', 'fastshop' ) => '',
			);
			$args             = array();
			$categories       = get_categories( $args );
			foreach ( $categories as $category ) {
				$categories_array[$category->name] = $category->slug;
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Blog', 'fastshop' ),
					'base'        => 'fastshop_blog', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a blog lists.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'style-1' => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style1.jpg',
								),
								'style-2' => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style2.jpg',
								),
								'style-3' => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style3.jpg',
								),
								'style-4' => array(
									'alt' => 'Style 04',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style4.jpg',
								),
								'style-5' => array(
									'alt' => 'Style 05',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style5.jpg',
								),
								'style-6' => array(
									'alt' => 'Style 06',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style6.jpg',
								),
								'style-7' => array(
									'alt' => 'Style 07',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style7.jpg',
								),
								'style-8' => array(
									'alt' => 'Style 08',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style8.jpg',
								),
								'style-9' => array(
									'alt' => 'Style 09',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'blog/style9.jpg',
								),
							),
							'default'     => 'style-1',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Number Post', 'fastshop' ),
							'param_name'  => 'per_page',
							'std'         => 3,
							'admin_label' => true,
							'description' => esc_html__( 'Number post in a slide', 'fastshop' ),
						),
						array(
							'param_name'  => 'category_slug',
							'type'        => 'dropdown',
							'value'       => $categories_array, // here I'm stuck
							'heading'     => esc_html__( 'Category filter:', 'fastshop' ),
							"admin_label" => true,
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'fastshop' ),
							"param_name"  => "orderby",
							"value"       => array(
								esc_html__( 'None', 'fastshop' )     => 'none',
								esc_html__( 'ID', 'fastshop' )       => 'ID',
								esc_html__( 'Author', 'fastshop' )   => 'author',
								esc_html__( 'Name', 'fastshop' )     => 'name',
								esc_html__( 'Date', 'fastshop' )     => 'date',
								esc_html__( 'Modified', 'fastshop' ) => 'modified',
								esc_html__( 'Rand', 'fastshop' )     => 'rand',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort retrieved posts.", 'fastshop' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'fastshop' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'fastshop' )  => 'ASC',
								esc_html__( 'DESC', 'fastshop' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'fastshop' ),
						),
						/* Owl */
						array(
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( '1 Row', 'fastshop' )  => '1',
								esc_html__( '2 Rows', 'fastshop' ) => '2',
								esc_html__( '3 Rows', 'fastshop' ) => '3',
								esc_html__( '4 Rows', 'fastshop' ) => '4',
								esc_html__( '5 Rows', 'fastshop' ) => '5',
							),
							'std'        => '1',
							'heading'    => esc_html__( 'The number of rows which are shown on block', 'fastshop' ),
							'param_name' => 'owl_number_row',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'fastshop' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' ) => 'rows-space-0',
								esc_html__( '10px', 'fastshop' )    => 'rows-space-10',
								esc_html__( '20px', 'fastshop' )    => 'rows-space-20',
								esc_html__( '30px', 'fastshop' )    => 'rows-space-30',
								esc_html__( '40px', 'fastshop' )    => 'rows-space-40',
								esc_html__( '50px', 'fastshop' )    => 'rows-space-50',
								esc_html__( '60px', 'fastshop' )    => 'rows-space-60',
								esc_html__( '70px', 'fastshop' )    => 'rows-space-70',
								esc_html__( '80px', 'fastshop' )    => 'rows-space-80',
								esc_html__( '90px', 'fastshop' )    => 'rows-space-90',
								esc_html__( '100px', 'fastshop' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array(
								"element" => "owl_number_row", "value" => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'        => 'false',
							'heading'    => esc_html__( 'AutoPlay', 'fastshop' ),
							'param_name' => 'autoplay',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Dots', 'fastshop' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show dots", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'fastshop' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Navigation position', 'fastshop' ),
							'param_name' => 'owl_navigation_position',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' )            => '',
								esc_html__( 'Style 2 Center', 'fastshop' )     => 'nav2',
								esc_html__( 'Style 2 Top Left', 'fastshop' )   => 'nav2 top-left',
								esc_html__( 'Style 2 Top Right', 'fastshop' )  => 'nav2 top-right',
								esc_html__( 'Style 2 Top Center', 'fastshop' ) => 'nav2 top-center',
							),
							'std'        => 'nav-center',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array( "element" => "navigation", "value" => array( 'true' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Postion Top", 'fastshop' ),
							"param_name" => "owl_navigation_position_top",
							"std"        => 0,
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-left', 'nav2 top-right', 'nav2 top-center' ),
							),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Offset Right", 'fastshop' ),
							"param_name" => "owl_navigation_offset_right",
							"std"        => 0,
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-right' ),
							),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Offset Left", 'fastshop' ),
							"param_name" => "owl_navigation_offset_left",
							"std"        => "0",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-left' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'fastshop' ),
							'param_name'  => 'owl_loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'fastshop' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'fastshop' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'fastshop' ),
							"param_name" => "ls_items",
							"value"      => "3",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'fastshop' ),
							"param_name" => "lg_items",
							"value"      => "3",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'fastshop' ),
							"param_name" => "md_items",
							"value"      => "3",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'fastshop' ),
							"param_name" => "sm_items",
							"value"      => "2",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'fastshop' ),
							"param_name" => "xs_items",
							"value"      => "2",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'fastshop' ),
							"param_name" => "ts_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'blog_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map new Container */
			vc_map(
				array(
					'name'                    => esc_html__( 'Fastshop: Container', 'fastshop' ),
					'base'                    => 'fastshop_container',
					'category'                => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'is_container'            => true,
					'js_view'                 => 'VcColumnView',
					'params'                  => array(
						array(
							'param_name'  => 'content_width',
							'heading'     => esc_html__( 'Content width', 'fastshop' ),
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'fastshop' )         => 'container',
								esc_html__( 'Custom Boostrap', 'fastshop' ) => 'custom_col',
								esc_html__( 'Custom Width', 'fastshop' )    => 'custom_width',
							),
							'admin_label' => true,
							'std'         => 'container',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'fastshop' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1500px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '15',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'fastshop' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1200px and < 1500px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on landscape tablet', 'fastshop' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=992px and < 1200px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on portrait tablet', 'fastshop' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=768px and < 992px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'fastshop' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=480  add < 768px )', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'fastshop' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'fastshop' ) => '12',
								esc_html__( '11 column - 11/12', 'fastshop' ) => '11',
								esc_html__( '10 column - 10/12', 'fastshop' ) => '10',
								esc_html__( '9 column - 9/12', 'fastshop' )   => '9',
								esc_html__( '8 column - 8/12', 'fastshop' )   => '8',
								esc_html__( '7 column - 7/12', 'fastshop' )   => '7',
								esc_html__( '6 column - 6/12', 'fastshop' )   => '6',
								esc_html__( '5 column - 5/12', 'fastshop' )   => '5',
								esc_html__( '4 column - 4/12', 'fastshop' )   => '4',
								esc_html__( '3 column - 3/12', 'fastshop' )   => '3',
								esc_html__( '2 column - 2/12', 'fastshop' )   => '2',
								esc_html__( '1 column - 1/12', 'fastshop' )   => '1',
								esc_html__( '4 column 5 - 4/5', 'fastshop' )  => '45',
								esc_html__( '3 column 5 - 3/5', 'fastshop' )  => '35',
								esc_html__( '2 column 5 - 2/5', 'fastshop' )  => '25',
								esc_html__( '1 column 5 - 1/5', 'fastshop' )  => '15',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device < 480px)', 'fastshop' ),
							'group'       => esc_html__( 'Boostrap settings', 'fastshop' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'param_name'  => 'number_width',
							'heading'     => esc_html__( 'width', 'fastshop' ),
							"description" => esc_html__( "you can width by px or %, ex: 100%", "fastshop" ),
							'std'         => '50%',
							'admin_label' => true,
							'type'        => 'textfield',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_width' ),
							),
						),
						'css' => array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'param_name'       => 'container_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Newsletter*/
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Newsletter', 'fastshop' ),
					'base'        => 'fastshop_newsletter', // shortcode
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a newsletter box.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'newsletter/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'newsletter/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'newsletter/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'newsletter/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 04',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'newsletter/style4.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Content align', 'fastshop' ),
							'param_name' => 'content_align',
							'value'      => array(
								esc_html__( 'Left', 'fastshop' )   => 'left',
								esc_html__( 'Right', 'fastshop' )  => 'right',
								esc_html__( 'Center', 'fastshop' ) => 'center',
							),
							'std'        => 'left',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'fastshop' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style1', 'style2', 'style3' ),
							),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Sub title', 'fastshop' ),
							'param_name' => 'subtitle',
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style3' ),
							),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Placeholder text", 'fastshop' ),
							"param_name" => "placeholder_text",
							'std'        => 'Your email address',
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Button text", 'fastshop' ),
							"param_name" => "button_text",
							'std'        => 'SUBSCRIBE',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'newsletter_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Instagram */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Instagram', 'fastshop' ),
					'base'        => 'fastshop_instagram', // shortcode
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a instagram box.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'fastshop' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'User Name Instagram', 'fastshop' ),
							'param_name'  => 'user_name',
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Token Instagram', 'fastshop' ),
							'param_name'  => 'token',
							'description' => wp_kses( sprintf( '<a href="%s" target="_blank">' . esc_html__( 'Get Token Instagram Here!', 'fastshop' ) . '</a>', 'http://instagram.pixelunion.net' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
							'std'         => '',
						),
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Items Instagram', 'fastshop' ),
							'param_name'       => 'items_limit',
							'description'      => esc_html__( 'the number items show', 'fastshop' ),
							'std'              => '',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							'type'             => 'dropdown',
							'heading'          => esc_html__( 'Number Column', 'fastshop' ),
							'param_name'       => 'number_col',
							'value'            => array(
								esc_html__( '1 item', 'fastshop' )  => '100',
								esc_html__( '2 items', 'fastshop' ) => '50',
								esc_html__( '3 items', 'fastshop' ) => '33.33',
								esc_html__( '4 items', 'fastshop' ) => '25',
								esc_html__( '5 items', 'fastshop' ) => '20',
								esc_html__( '6 items', 'fastshop' ) => '16.67',
							),
							'std'              => '25',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'instagram_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Vertical menu */
			$all_menu = array();
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( $menus && count( $menus ) > 0 ) {
				foreach ( $menus as $m ) {
					$all_menu[$m->name] = $m->slug;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Vertical Menu', 'fastshop' ),
					'base'        => 'fastshop_verticalmenu', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a vertical menu.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'vertical_menu/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 1',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'vertical_menu/style1.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'layout',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Position absolute menu', 'fastshop' ),
							'param_name' => 'position_menu',
							'std'        => 'yes',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => 'yes',
								esc_html__( 'No', 'fastshop' )  => 'no',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'fastshop' ),
							'admin_label' => true,
							'std'         => 'All Categories',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Menu', 'fastshop' ),
							'param_name'  => 'menu',
							'value'       => $all_menu,
							'description' => esc_html__( 'Select menu to display.', 'fastshop' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Button close text', 'fastshop' ),
							'param_name'  => 'button_close_text',
							'description' => esc_html__( 'Button close text', 'fastshop' ),
							'admin_label' => true,
							'std'         => esc_html__( 'Close', 'fastshop' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Button all text', 'fastshop' ),
							'param_name'  => 'button_all_text',
							'description' => esc_html__( 'Button all text', 'fastshop' ),
							'admin_label' => true,
							'std'         => esc_html__( 'All Categories', 'fastshop' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Limit items', 'fastshop' ),
							'param_name'  => 'limit_items',
							'description' => esc_html__( 'Limit item for showing', 'fastshop' ),
							'admin_label' => true,
							'std'         => '9',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'verticalmenu_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Custom menu*/
			$all_menu = array();
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( $menus && count( $menus ) > 0 ) {
				foreach ( $menus as $m ) {
					$all_menu[$m->name] = $m->slug;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Custom Menu', 'fastshop' ),
					'base'        => 'fastshop_custommenu', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a custom menu.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'custom_menu/default.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'layout',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'fastshop' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image Banner", 'fastshop' ),
							"param_name"  => "menu_banner",
							"admin_label" => true,
							'dependency'  => array(
								'element' => 'layout',
								'value'   => array( 'layout1' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Menu', 'fastshop' ),
							'param_name'  => 'menu',
							'value'       => $all_menu,
							'admin_label' => true,
							'description' => esc_html__( 'Select menu to display.', 'fastshop' ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_attr__( 'URL (Link)', 'fastshop' ),
							'param_name'  => 'link',
							'description' => esc_attr__( 'Add link.', 'fastshop' ),
							'dependency'  => array(
								'element' => 'layout',
								'value'   => array( 'layout1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'custommenu_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Testimonials */
			function get_testimonials( $post_type = 'testimonial' )
			{
				$posts  = get_posts( array(
						'posts_per_page' => -1,
						'post_type'      => $post_type,
						'post_status'    => 'publish',
					)
				);
				$result = array();
				foreach ( $posts as $post ) {
					$result[] = array(
						'value' => $post->ID,
						'label' => $post->post_title,
					);
				}

				return $result;
			}

			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Testimonials', 'fastshop' ),
					'base'        => 'fastshop_testimonials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a Testimonials.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 04',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/style4.jpg',
								),
								'style5'  => array(
									'alt' => 'Style 05',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'testimonials/style5.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"       => "dropdown",
							"heading"    => esc_html__( "Chose Testimonial", 'fastshop' ),
							"param_name" => "chose_testimonial",
							"value"      => array(
								esc_html__( 'Number Post', 'fastshop' ) => '1',
								esc_html__( 'Single Post', 'fastshop' ) => '0',
							),
							'std'        => '1',
						),
						array(
							'type'        => 'autocomplete',
							'class'       => '',
							'heading'     => esc_html__( 'Post Name', 'fastshop' ),
							'param_name'  => 'ids_post',
							'settings'    => array(
								'multiple'      => false,
								'sortable'      => true,
								'unique_values' => true,
								'values'        => get_testimonials(),
							),
							'description' => esc_html__( 'Enter List of post name', 'fastshop' ),
							"dependency"  => array( "element" => "chose_testimonial", "value" => array( '0' ) ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Number Post', 'fastshop' ),
							'param_name'  => 'per_page',
							'std'         => 3,
							'admin_label' => true,
							'description' => esc_html__( 'Number post in a slide', 'fastshop' ),
							"dependency"  => array( "element" => "chose_testimonial", "value" => array( '1' ) ),
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'fastshop' ),
							'param_name' => 'link',
							"dependency" => array( "element" => "style", "value" => array( 'style5' ) ),
						),
						/* Owl */
						array(
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'        => 'false',
							'heading'    => esc_html__( 'AutoPlay', 'fastshop' ),
							'param_name' => 'owl_autoplay',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'true',
							'heading'     => esc_html__( 'Dots', 'fastshop' ),
							'param_name'  => 'owl_dots',
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'description' => esc_html__( "Show dots", 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'true',
							'heading'     => esc_html__( 'Navigation', 'fastshop' ),
							'param_name'  => 'owl_navigation',
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'fastshop' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Navigation position', 'fastshop' ),
							'param_name' => 'owl_navigation_position',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' )            => '',
								esc_html__( 'Style 2 Center', 'fastshop' )     => 'nav2',
								esc_html__( 'Style 2 Top Left', 'fastshop' )   => 'nav2 top-left',
								esc_html__( 'Style 2 Top Right', 'fastshop' )  => 'nav2 top-right',
								esc_html__( 'Style 2 Top Center', 'fastshop' ) => 'nav2 top-center',
							),
							'std'        => '',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array( "element" => "owl_navigation", "value" => array( 'true' ) ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'fastshop' ),
							'param_name'  => 'owl_loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'fastshop' ),
							"param_name"  => "owl_slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'fastshop' ),
							"param_name"  => "owl_margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'fastshop' ),
							"param_name" => "owl_ls_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'fastshop' ),
							"param_name" => "owl_lg_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'fastshop' ),
							"param_name" => "owl_md_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'fastshop' ),
							"param_name" => "owl_sm_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'fastshop' ),
							"param_name" => "owl_xs_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'fastshop' ),
							"param_name" => "owl_ts_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'testimonials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Categories */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Categories', 'fastshop' ),
					'base'        => 'fastshop_categories', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display Categories.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 04',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style4.jpg',
								),
								'style5'  => array(
									'alt' => 'Style 05',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style5.jpg',
								),
								'style6'  => array(
									'alt' => 'Style 06',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'categories/style6.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Photo Category", "fastshop" ),
							"param_name"  => "bg_cat",
							"admin_label" => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style1', 'style2', 'style3', 'style4', 'style5' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title Category", "fastshop" ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "Title of shortcode.", "fastshop" ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Descriptions", "fastshop" ),
							"param_name"  => "des",
							'admin_label' => true,
							"description" => esc_html__( "Descriptions of shortcode.", "fastshop" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2' ),
							),
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'fastshop' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => true,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'fastshop' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'fastshop' ),
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style6' ),
							),
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'fastshop' ),
							"param_name"  => "taxonomy_style1",
							"value"       => '',
							'parent'      => '',
							'multiple'    => false,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'fastshop' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'fastshop' ),
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2', 'style3', 'style4', 'style5' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'fastshop' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link with title.(note: work with Title shortcode)', 'fastshop' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style6' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'categories_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Masonry */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Banner Masonry', 'fastshop' ),
					'base'        => 'fastshop_masonry',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a Banner Masonry.', 'fastshop' ),
					'params'      => array(
						array(
							"type"        => "param_group",
							"heading"     => esc_html__( "Items Masonry", "fastshop" ),
							"admin_label" => false,
							"param_name"  => "items",
							"params"      => array(
								array(
									"type"       => "attach_image",
									"heading"    => esc_html__( "Background", "fastshop" ),
									"param_name" => "bg_banner",
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__( 'Size item', 'fastshop' ),
									'value'      => array(
										esc_html__( '1', 'fastshop' ) => 1,
										esc_html__( '2', 'fastshop' ) => 2,
										esc_html__( '3', 'fastshop' ) => 3,
										esc_html__( '4', 'fastshop' ) => 4,
										esc_html__( '5', 'fastshop' ) => 5,
										esc_html__( '6', 'fastshop' ) => 6,
										esc_html__( '7', 'fastshop' ) => 7,
									),
									'param_name' => 'size_item',
								),
								array(
									"type"        => "textfield",
									"heading"     => esc_html__( "Title", "fastshop" ),
									"param_name"  => "title",
									"admin_label" => true,
								),
								array(
									'type'        => 'vc_link',
									'heading'     => esc_html__( 'URL (Link)', 'fastshop' ),
									'param_name'  => 'links',
									'description' => esc_html__( 'Add link to custom heading.', 'fastshop' ),
								),
							),
						),
						array(
							'param_name' => 'banner_effect',
							'heading'    => esc_html__( 'Effect', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Underline Zoom', 'fastshop' )   => 'underline-zoom',
								esc_html__( 'Underline Center', 'fastshop' ) => 'underline-center',
								esc_html__( 'Plus Zoom', 'fastshop' )        => 'plus-zoom',
								esc_html__( 'None', 'fastshop' )             => 'none',
							),
							'sdt'        => 'underline-zoom',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'masonry_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Slider */
			vc_map(
				array(
					'name'                    => esc_html__( 'Fastshop: Slider', 'fastshop' ),
					'base'                    => 'fastshop_slider',
					'category'                => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description'             => esc_html__( 'Display a custom slide.', 'fastshop' ),
					'as_parent'               => array( 'only' => 'vc_single_image, fastshop_custommenu, fastshop_categories, fastshop_products' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . 'slide/default.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'admin_label' => true,
							'heading'     => esc_html__( 'Title', 'fastshop' ),
							'description' => esc_html__( 'title of shortcode.', 'fastshop' ),
							'param_name'  => 'title',
						),
						/* Owl */
						array(
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'        => 'false',
							'heading'    => esc_html__( 'AutoPlay', 'fastshop' ),
							'param_name' => 'autoplay',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Dots', 'fastshop' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show dots.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'fastshop' )  => 'false',
								esc_html__( 'Yes', 'fastshop' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'fastshop' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Navigation position', 'fastshop' ),
							'param_name' => 'owl_navigation_position',
							'value'      => array(
								esc_html__( 'Default', 'fastshop' )            => '',
								esc_html__( 'Style 2 Center', 'fastshop' )     => 'nav2',
								esc_html__( 'Style 2 Top Left', 'fastshop' )   => 'nav2 top-left',
								esc_html__( 'Style 2 Top Right', 'fastshop' )  => 'nav2 top-right',
								esc_html__( 'Style 2 Top Center', 'fastshop' ) => 'nav2 top-center',
							),
							'std'        => 'nav-center',
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
							"dependency" => array( "element" => "navigation", "value" => array( 'true' ) ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Postion Top", 'fastshop' ),
							"param_name"  => "owl_navigation_position_top",
							"value"       => "0",
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_navigation_position", "value" => array( 'nav2 top-left', 'nav2 top-right', 'nav2 top-center' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'fastshop' ) => 'true',
								esc_html__( 'No', 'fastshop' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'fastshop' ),
							'param_name'  => 'owl_loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'fastshop' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'fastshop' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'fastshop' ),
							'group'       => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'fastshop' ),
							"param_name" => "ls_items",
							"value"      => "5",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'fastshop' ),
							"param_name" => "lg_items",
							"value"      => "4",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'fastshop' ),
							"param_name" => "md_items",
							"value"      => "3",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'fastshop' ),
							"param_name" => "sm_items",
							"value"      => "2",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'fastshop' ),
							"param_name" => "xs_items",
							"value"      => "2",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'fastshop' ),
							"param_name" => "ts_items",
							"value"      => "1",
							'group'      => esc_html__( 'Carousel settings', 'fastshop' ),
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'fastshop' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'fastshop' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'slider_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Contact */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Contact', 'fastshop' ),
					'base'        => 'fastshop_contact', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a contact list.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/contact/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/contact/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/contact/style2.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Phone", "fastshop" ),
							"param_name" => "phone",
						),
						array(
							"type"       => "textarea",
							"heading"    => esc_html__( "Email", "fastshop" ),
							"param_name" => "email",
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2' ),
							),
						),
						array(
							"type"       => "textarea",
							"heading"    => esc_html__( "Address", "fastshop" ),
							"param_name" => "address",
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'contact_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Social */
			$socials     = array();
			$all_socials = fastshop_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $value )
					$socials[$value['title_social']] = $key;
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Socials', 'fastshop' ),
					'base'        => 'fastshop_socials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a social list.', 'fastshop' ),
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'fastshop' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/socials/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/socials/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/socials/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 03',
									'img' => FASTSHOP_SHORTCODE_PREVIEW . '/socials/style3.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Title', 'fastshop' ),
							'param_name' => 'title',
						),
						array(
							'param_name' => 'text_align',
							'heading'    => esc_html__( 'Text align', 'fastshop' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'fastshop' )   => 'text-left',
								esc_html__( 'Right', 'fastshop' )  => 'text-right',
								esc_html__( 'Center', 'fastshop' ) => 'text-center',
							),
							'std'        => 'text-left',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__( 'Display on', 'fastshop' ),
							'param_name' => 'use_socials',
							'class'      => 'checkbox-display-block',
							'value'      => $socials,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "fastshop" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'socials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map Google Map */
			vc_map(
				array(
					'name'        => esc_html__( 'Fastshop: Google Map', 'fastshop' ),
					'base'        => 'fastshop_googlemap', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
					'description' => esc_html__( 'Display a google map.', 'fastshop' ),
					'params'      => array(
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", 'fastshop' ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "title.", 'fastshop' ),
							'std'         => 'Kute themes',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Phone", 'fastshop' ),
							"param_name"  => "phone",
							'admin_label' => true,
							"description" => esc_html__( "phone.", 'fastshop' ),
							'std'         => '088-465 9965 02',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Email", 'fastshop' ),
							"param_name"  => "email",
							'admin_label' => true,
							"description" => esc_html__( "email.", 'fastshop' ),
							'std'         => 'kutethemes@gmail.com',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Map Height", 'fastshop' ),
							"param_name"  => "map_height",
							'admin_label' => true,
							'std'         => '400',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Maps type', 'fastshop' ),
							'param_name' => 'map_type',
							'value'      => array(
								esc_html__( 'ROADMAP', 'fastshop' )   => 'ROADMAP',
								esc_html__( 'SATELLITE', 'fastshop' ) => 'SATELLITE',
								esc_html__( 'HYBRID', 'fastshop' )    => 'HYBRID',
								esc_html__( 'TERRAIN', 'fastshop' )   => 'TERRAIN',
							),
							'std'        => 'ROADMAP',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Address", 'fastshop' ),
							"param_name"  => "address",
							'admin_label' => true,
							"description" => esc_html__( "address.", 'fastshop' ),
							'std'         => 'Z115 TP. Thai Nguyen',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Longitude", 'fastshop' ),
							"param_name"  => "longitude",
							'admin_label' => true,
							"description" => esc_html__( "longitude.", 'fastshop' ),
							'std'         => '105.800286',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Latitude", 'fastshop' ),
							"param_name"  => "latitude",
							'admin_label' => true,
							"description" => esc_html__( "latitude.", 'fastshop' ),
							'std'         => '21.587001',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Zoom", 'fastshop' ),
							"param_name"  => "zoom",
							'admin_label' => true,
							"description" => esc_html__( "zoom.", 'fastshop' ),
							'std'         => '14',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'fastshop' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'fastshop' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design options', 'fastshop' ),
						),
						array(
							'param_name'       => 'googlemap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'fastshop' ),
							'type'             => 'el_id',
							'settings'         => array(
								'auto_generate' => true,
							),
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
		}
	}

	new Fastshop_Visual_Composer();
}
VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Fastshop_Tabs extends WPBakeryShortCode_VC_Tta_Accordion
{
}

class WPBakeryShortCode_Fastshop_Accordions extends WPBakeryShortCode_VC_Tta_Accordion
{
}

class WPBakeryShortCode_Fastshop_Container extends WPBakeryShortCodesContainer
{
}

class WPBakeryShortCode_Fastshop_Slider extends WPBakeryShortCodesContainer
{
}