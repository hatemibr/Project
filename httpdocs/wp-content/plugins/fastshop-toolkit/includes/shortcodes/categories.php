<?php

if ( !class_exists( 'Fastshop_Shortcode_categories' ) ) {

	class Fastshop_Shortcode_categories extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'categories';

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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_categories', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'fastshop-categories' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'categories_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts[ 'css' ], ' ' ), '', $atts );
			}

			if ( $atts[ 'style' ] == 'style2' ) {
				$width  = '270';
				$height = '177';
			} elseif ( $atts[ 'style' ] == 'style3' ) {
				$width  = '270';
				$height = '194';
			} elseif ( $atts[ 'style' ] == 'style4' ) {
				$width  = '270';
				$height = '194';
			} elseif ( $atts[ 'style' ] == 'style5' ) {
				$width  = '570';
				$height = '377';
			} else {
				$width  = '150';
				$height = '150';
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts[ 'bg_cat' ] ) : ?>
                    <div class="thumb">
                        <figure>
							<?php
							$fastshop_blog_lazy = fastshop_get_option( 'fastshop_theme_lazy_load');
							if ( $fastshop_blog_lazy == 1 ) {
								$lazy_check = true;
							} else {
								$lazy_check = false;
							}
							$image_thumb = fastshop_resize_image( $atts[ 'bg_cat' ], null, $width, $height, false, $lazy_check );
							?>
							<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                        </figure>
                    </div>
				<?php endif; ?>
                <div class="content">
					<?php if ( $atts[ 'title' ] ) : ?>
                        <h4 class="title">
							<?php if ( $atts[ 'style' ] == 'style3' || $atts[ 'style' ] == 'style4' ) : ?>
								<?php if ( $atts[ 'taxonomy_style1' ] ) : ?>
									<?php $term_link = get_term_link( $atts[ 'taxonomy_style1' ], 'product_cat' ); ?>
									<?php if ( !is_wp_error( $term_link ) ) : ?>
                                        <a href="<?php echo esc_url( $term_link ) ?>">
											<?php echo esc_html( $atts[ 'title' ] ); ?>
                                        </a>
									<?php else: ?>
                                        <a href="#">
											<?php echo esc_html( $atts[ 'title' ] ); ?>
                                        </a>
									<?php endif; ?>
								<?php endif; ?>
							<?php else: ?>
								<?php echo esc_html( $atts[ 'title' ] ); ?>
							<?php endif; ?>
                        </h4>
					<?php endif; ?>
					<?php if ( $atts[ 'des' ] ) : ?>
                        <span class="des"><?php echo esc_html( $atts[ 'des' ] ); ?></span>
					<?php endif; ?>
					<?php if ( $atts[ 'taxonomy_style1' ] && $atts[ 'style' ] != 'default' ) : ?>
						<?php $term_link = get_term_link( $atts[ 'taxonomy_style1' ], 'product_cat' ); ?>
						<?php if ( !is_wp_error( $term_link ) ) : ?>
                            <a class="view-all button" href="<?php echo esc_url( $term_link ) ?>">
								<?php echo esc_html__( 'SHOP NOW', 'fastshop-toolkit' ); ?>
                            </a>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $atts[ 'taxonomy' ] ): ?>
						<?php $taxonomy = explode( ',', $atts[ 'taxonomy' ] ); ?>
                        <ul class="info">
							<?php foreach ( $taxonomy as $value ) : ?>
								<?php $term_link = get_term_link( $value, 'product_cat' ); ?>
								<?php if ( !is_wp_error( $term_link ) ) : ?>
									<?php $prodcut_category = get_term_by( 'slug', $value, 'product_cat' ); ?>
                                    <li>
                                        <a href="<?php echo esc_url( $term_link ) ?>"><?php echo esc_html( $prodcut_category->name ); ?></a>
										<?php if ( $atts[ 'style' ] == 'style6' ): ?>
                                            <span class="count"><?php echo '(' . esc_html( $prodcut_category->count ) . ')'; ?></span>
										<?php endif; ?>
                                    </li>
								<?php endif; ?>
							<?php endforeach; ?>
                        </ul>
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
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_categories', force_balance_tags( $html ), $atts, $content );
		}
	}
}