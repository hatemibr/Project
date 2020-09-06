<?php

if ( !class_exists( 'Fastshop_Shortcode_Title' ) ) {
	class Fastshop_Shortcode_Title extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'title';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_title', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'fastshop-title' );
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'title_align' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'title_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $title ): ?>
                    <h2 class="title">
						<?php echo esc_html( $title ); ?>
                    </h2>
				<?php endif; ?>
				<?php if ( $atts[ 'link' ] ) : ?>
					<?php
					$link = vc_build_link( $atts[ 'link' ] );
					if ( $link[ 'target' ] == '' ) {
						$link[ 'target' ] = '_self';
					}
					?>
                    <a class="view-all" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                       target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
						<?php echo esc_html( $link[ 'title' ] ) ?>
                    </a>
				<?php endif; ?>
				<?php if ( $des ): ?>
					<?php
					$args         = array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'br'     => array(),
						'em'     => array(),
						'strong' => array(),
					);
					$descriptions = wp_kses( $des, $args );
					?>
                    <p class="sub-title"><?php echo balanceTags( $descriptions ); ?></p>
				<?php endif; ?>
            </div>

			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_title', force_balance_tags( $html ), $atts, $content );
		}
	}
}