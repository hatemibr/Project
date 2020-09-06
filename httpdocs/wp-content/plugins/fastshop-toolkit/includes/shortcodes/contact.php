<?php

if ( !class_exists( 'Fastshop_Shortcode_contact' ) ) {
	class Fastshop_Shortcode_contact extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'contact';


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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_contact', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class = array( 'fastshop-contact' );

			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'contact_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts[ 'style' ] == 'style2' ): ?>
					<?php if ( $atts[ 'email' ] ) : ?>
						<?php
						$args  = array(
							'a'      => array(
								'href'  => array(),
								'title' => array(),
							),
							'br'     => array(),
							'em'     => array(),
							'strong' => array(),
						);
						$email = wp_kses( $atts[ 'email' ], $args );
						?>
                        <div class="email">
                            <span class="fa fa-envelope"></span>
                            <div class="content">
                                <p><?php echo esc_html__( 'Email', 'fastshop' ); ?></p>
                                <span class="text"><?php echo force_balance_tags( $email ); ?></span>
                            </div>
                        </div>
					<?php endif; ?>
				<?php else: ?>
					<?php if ( $atts[ 'address' ] ) : ?>
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
						$descriptions = wp_kses( $atts[ 'address' ], $args );
						?>
                        <div class="address">
							<?php if ( $atts[ 'style' ] == 'style1' ) : ?>
                                <span class="fa fa-map-marker"></span>
							<?php endif; ?>
                            <span class="text"><?php echo balanceTags( $descriptions ); ?></span>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( $atts[ 'phone' ] ) : ?>
                    <div class="phone">
						<?php if ( $atts[ 'style' ] == 'default' ) : ?>
                            <span class="title"><?php echo esc_html__( 'Tel: ', 'fastshop' ) ?></span>
						<?php else: ?>
                            <span class="fa fa-phone"></span>
						<?php endif; ?>
						<?php if ( $atts[ 'style' ] == 'style2' ) : ?>
                            <div class="content">
                                <p><?php echo esc_html__( 'Phone', 'fastshop' ); ?></p>
                                <span class="text"><?php echo force_balance_tags( $atts[ 'phone' ] ); ?></span>
                            </div>
						<?php else: ?>
                            <span class="text"><?php echo esc_html( $atts[ 'phone' ] ); ?></span>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
				<?php if ( $atts[ 'style' ] == 'style2' ): ?>
					<?php if ( $atts[ 'address' ] ) : ?>
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
						$descriptions = wp_kses( $atts[ 'address' ], $args );
						?>
                        <div class="address">
                            <span class="fa fa-map-marker"></span>
                            <div class="content">
                                <p><?php echo esc_html__( 'Mail Office', 'fastshop' ); ?></p>
                                <span class="text"><?php echo balanceTags( $descriptions ); ?></span>
                            </div>
                        </div>
					<?php endif; ?>
				<?php else: ?>
					<?php if ( $atts[ 'email' ] ) : ?>
						<?php
						$args  = array(
							'a'      => array(
								'href'  => array(),
								'title' => array(),
							),
							'br'     => array(),
							'em'     => array(),
							'strong' => array(),
						);
						$email = wp_kses( $atts[ 'email' ], $args );
						?>
                        <div class="email">
							<?php if ( $atts[ 'style' ] == 'style1' ) : ?>
                                <span class="fa fa-envelope"></span>
							<?php endif; ?>
                            <span class="text"><?php echo force_balance_tags( $email ); ?></span>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_Contact', force_balance_tags( $html ), $atts, $content );
		}
	}
}