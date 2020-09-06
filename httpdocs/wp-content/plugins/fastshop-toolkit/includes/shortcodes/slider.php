<?php

if ( !class_exists( 'Fastshop_Shortcode_Slider' ) ) {
	class Fastshop_Shortcode_Slider extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slider';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			$css = '';
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' || $atts[ 'owl_navigation_position' ] == 'nav2 top-right' || $atts[ 'owl_navigation_position' ] == 'nav2 top-center' ) {
				$css .= '.' . $atts[ 'slider_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ top:' . $atts[ 'owl_navigation_position_top' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' ) {
				$css .= '.' . $atts[ 'slider_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ left:' . $atts[ 'owl_navigation_offset_left' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-right' ) {
				$css .= '.' . $atts[ 'slider_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ right:' . $atts[ 'owl_navigation_offset_right' ] . 'px;} ';
			}

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_slider', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );


			$css_class = array( 'fastshop-slider' );

			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'slider_custom_id' ];

			$owl_class[] = $atts[ 'owl_navigation_position' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			$owl_settings = $this->generate_carousel_data_attributes( '', $atts );

			$owl_class   = array( 'owl-carousel' );
			$owl_class[] = $atts[ 'owl_navigation_position' ];

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts[ 'title' ] ) : ?>
                    <div class="top-title">
                        <h2 class="fastshop-title"><?php echo esc_html( $atts[ 'title' ] ); ?></h2>
                    </div>
				<?php endif; ?>
                <div class="<?php echo esc_attr( implode( ' ', $owl_class ) ); ?>" <?php echo $owl_settings; ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_slider', force_balance_tags( $html ), $atts, $content );
		}
	}
}