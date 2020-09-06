<?php

if ( !class_exists( 'Fastshop_Shortcode_masonry' ) ) {
	class Fastshop_Shortcode_masonry extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'masonry';


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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_masonry', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class = array( 'fastshop-masonry fastshop-portfolio' );

			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'masonry_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			$items = vc_param_group_parse_atts( $atts[ 'items' ] );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="portfolio-grid" data-layoutmode="packery">
					<?php foreach ( $items as $value ) : ?>
						<?php
						$width  = 300;
						$height = 300;
						if ( $value[ 'size_item' ] == 1 ) {
							$width  = 371;
							$height = 273;
						} elseif ( $value[ 'size_item' ] == 2 ) {
							$width  = 301;
							$height = 273;
						} elseif ( $value[ 'size_item' ] == 3 ) {
							$width  = 498;
							$height = 546;
						} elseif ( $value[ 'size_item' ] == 4 ) {
							$width  = 300;
							$height = 492;
						} elseif ( $value[ 'size_item' ] == 5 ) {
							$width  = 372;
							$height = 273;
						} elseif ( $value[ 'size_item' ] == 6 ) {
							$width  = 500;
							$height = 219;
						} elseif ( $value[ 'size_item' ] == 7 ) {
							$width  = 370;
							$height = 219;
						}
						$image_thumb = fastshop_resize_image( $value[ 'bg_banner' ], null, $width, $height, true, false );
						?>
                        <div class="banner-portfolio portfolio-item <?php echo $atts[ 'banner_effect' ]; ?>">
							<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                            <div class="block-text">
								<?php if ( $value[ 'title' ] ) : ?>
                                    <h3 class="title"><?php echo esc_html( $value[ 'title' ] ); ?></h3>
								<?php endif; ?>
								<?php if ( $value[ 'links' ] ) : ?>
									<?php
									$link = vc_build_link( $value[ 'links' ] );
									if ( $link[ 'target' ] == '' ) {
										$link[ 'target' ] = '_self';
									}
									?>
                                    <a class="button" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                                       target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
										<?php echo esc_html( $link[ 'title' ] ) ?>
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_masonry', force_balance_tags( $html ), $atts, $content );
		}
	}
}