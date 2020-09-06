<?php

if ( !class_exists( 'Fastshop_Shortcode_banner' ) ) {
	class Fastshop_Shortcode_banner extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'banner';


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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_banner', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class = array( 'fastshop-banner' );

			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'banner_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts[ 'css' ], ' ' ), '', $atts );
			}
			$gallery = explode( ',', $atts[ 'gallery_banner' ] );

			if ( $atts[ 'style' ] == 'default' ) {
				$width_thumb  = 114;
				$height_thumb = 76;

				$width  = 460;
				$height = 500;
			} else {
				$width_thumb  = 80;
				$height_thumb = 80;

				$width  = 500;
				$height = 440;
			}
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts[ 'style' ] == 'style2' ) : ?>
                    <div class="thumb-banner">
						<?php $image_thumb = fastshop_resize_image( $atts[ 'image_banner' ], null, 570, 340, true, false ); ?>
							<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                    </div>
                    <div class="content-banner">
						<?php if ( $atts[ 'descriptions' ] ): ?>
                            <p class="sub-title"><?php echo htmlspecialchars_decode( $atts[ 'descriptions' ] ); ?></p>
						<?php endif; ?>
						<?php if ( $atts[ 'title' ] ): ?>
                            <h4 class="title"><?php echo esc_html( $atts[ 'title' ] ); ?></h4>
						<?php endif; ?>
						<?php if ( $atts[ 'link' ] ) : ?>
							<?php $link = vc_build_link( $atts[ 'link' ] ); ?>
                            <a class="view-all button" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                               target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
								<?php echo esc_html( $link[ 'title' ] ) ?>
                            </a>
						<?php endif; ?>
                    </div>
				<?php else: ?>
                    <div class="main-slide">
						<?php foreach ( $gallery as $value ) : ?>
							<?php $image_thumb = fastshop_resize_image( $value, null, $width, $height, true, false ); ?>
                            <div>
								<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                            </div>
						<?php endforeach; ?>
                    </div>
                    <div class="content-slide">
						<?php if ( $atts[ 'style' ] == 'style1' ) : ?>
                            <div class="top-content">
								<?php if ( $atts[ 'descriptions' ] ): ?>
                                    <p class="sub-title"><?php echo htmlspecialchars_decode( $atts[ 'descriptions' ] ); ?></p>
								<?php endif; ?>
								<?php if ( $atts[ 'link' ] ) : ?>
									<?php
									$link = vc_build_link( $atts[ 'link' ] );
									if ( $link[ 'target' ] == '' ) {
										$link[ 'target' ] = '_self';
									}
									?>
                                    <a class="view-all button" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                                       target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
										<?php echo esc_html( $link[ 'title' ] ) ?>
                                    </a>
								<?php endif; ?>
                            </div>
						<?php endif; ?>
                        <div class="second-slide">
							<?php foreach ( $gallery as $value ) : ?>
								<?php $image_thumb = fastshop_resize_image( $value, null, $width_thumb, $height_thumb, true, false ); ?>
                                <div>
									<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                </div>
							<?php endforeach; ?>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_banner', force_balance_tags( $html ), $atts, $content );
		}
	}
}