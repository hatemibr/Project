<?php

if ( !class_exists( 'Fastshop_Shortcode_Iconbox' ) ) {
	class Fastshop_Shortcode_Iconbox extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'iconbox';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'banner_image'     => '',
			'style'            => '',
			'content_position' => 'left',
			'text_align'       => 'text-left',
			'el_class'         => '',
			'css'              => '',
			'banner_custom_id' => '',
			'icon_type'        => '',
		);


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_iconbox', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'fastshop-iconbox' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'iconbox_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$icon = $atts[ 'icon_' . $atts[ 'icon_type' ] ];
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="iconbox-inner">
					<?php if ( $icon ): ?>
                        <div class="icon"><span class="<?php echo esc_attr( $icon ) ?>"></span></div>
					<?php endif; ?>
                    <div class="content">
						<?php if ( $atts[ 'title' ] ): ?>
                            <h4 class="title">
								<?php echo esc_html( $atts[ 'title' ] ); ?>
                            </h4>
						<?php endif; ?>
						<?php if ( $atts[ 'text_content' ] ): ?>
                            <p class="text"><?php echo esc_html( $atts[ 'text_content' ] ); ?></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_iconbox', force_balance_tags( $html ), $atts, $content );
		}
	}
}