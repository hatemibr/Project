<?php

if ( !class_exists( 'Fastshop_Shortcode_blog' ) ) {
	class Fastshop_Shortcode_blog extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'blog';


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
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' || $atts[ 'owl_navigation_position' ] == 'nav2 top-right' || $atts[ 'owl_navigation_position' ] == 'nav2 top-center' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ top:' . $atts[ 'owl_navigation_position_top' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ left:' . $atts[ 'owl_navigation_offset_left' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-right' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ right:' . $atts[ 'owl_navigation_offset_right' ] . 'px;} ';
			}

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_blog', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class = array( 'fastshop-blog' );
			$owl_class = $atts[ 'owl_navigation_position' ];

			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'blog_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$owl_settings = $this->generate_carousel_data_attributes( '', $atts );

			$args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts[ 'per_page' ],
				'suppress_filter'     => true,
				'orderby'             => $atts[ 'orderby' ],
				'order'               => $atts[ 'order' ],
			);
			if ( !empty( $ids_post ) ) {
				$args[ 'p' ] = $ids_post;
			}

			if ( $atts[ 'category_slug' ] ) {
				$idObj = get_category_by_slug( $atts[ 'category_slug' ] );
				if ( is_object( $idObj ) ) {
					$args[ 'cat' ] = $idObj->term_id;
				}
			}

			$loop_posts = new WP_Query( apply_filters( 'fastshop_shortcode_posts_query', $args, $atts ) );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $loop_posts->have_posts() ) : ?>
                    <div class="owl-carousel equal-container better-height <?php echo esc_attr( $owl_class ); ?>" <?php echo force_balance_tags( $owl_settings ); ?>>
                        <div class="owl-one-row">
							<?php
							$i     = 1;
							$count = 0;
							?>
							<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
								<?php
								$count++;
								$class_post = array( 'blog-item' );

								if ( $count % 2 == 0 ) {
									$class_post[] = 'right';
								} else {
									$class_post[] = 'left';
								}
								?>
                                <div class="<?php echo esc_attr( $atts[ 'owl_rows_space' ] ); ?>">
                                    <div <?php post_class( $class_post ); ?>>
										<?php get_template_part( 'templates/blog/blog-styles/content-blog', $atts[ 'style' ] ); ?>
                                    </div>
                                </div>
								<?php
								if ( $i % $atts[ 'owl_number_row' ] == 0 && $i < $atts[ 'per_page' ] ) {
									echo '</div><div class="owl-one-row">';
								}
								$i++;
								?>
							<?php endwhile; ?>
                        </div>
                    </div>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_blog', force_balance_tags( $html ), $atts, $content );
		}
	}
}