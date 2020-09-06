<?php

if ( !class_exists( 'Fastshop_Shortcode_Newsletter' ) ) {

	class Fastshop_Shortcode_Newsletter extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'newsletter';


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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_newsletter', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'fastshop-newsletter' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'content_align' ];
			$css_class[] = $atts[ 'newsletter_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="content-newletter">
					<?php if ( $atts[ 'title' ] ): ?>
                        <h3 class="title"><?php echo esc_html( $atts[ 'title' ] ); ?></h3>
					<?php endif; ?>
					<?php if ( $atts[ 'subtitle' ] ): ?>
						<?php
						$args      = array(
							'a'      => array(
								'href'  => array(),
								'title' => array(),
							),
							'br'     => array(),
							'em'     => array(),
							'strong' => array(),
						);
						$subtitles = wp_kses( $atts[ 'subtitle' ], $args );
						?>
                        <p class="sub-title"><?php echo force_balance_tags( $subtitles ); ?></p>
					<?php endif; ?>
                    <div class="content">
                        <div class="newsletter-form-wrap">
                            <?php if ( $atts[ 'style' ] == 'style4' ) : ?>
                                <div class="email-wrap">
                                    <input class="email email-newsletter" type="email" name="email" placeholder="<?php echo esc_attr( $atts[ 'placeholder_text' ] ); ?>">
                                </div>
                            <?php else : ?>
                                <input class="email email-newsletter" type="email" name="email" placeholder="<?php echo esc_attr( $atts[ 'placeholder_text' ] ); ?>">
                            <?php endif ?>
                            <a href="javascript:void(0);" class="button btn-submit submit-newsletter">
								<?php echo esc_html( $atts[ 'button_text' ] ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_newsletter', force_balance_tags( $html ), $atts, $content );
		}
	}
}