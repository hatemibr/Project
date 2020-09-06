<?php

if ( !class_exists( 'Fastshop_Shortcode_Testimonials' ) ) {
	class Fastshop_Shortcode_Testimonials extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'testimonials';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			$css = '';

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_testimonials', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );


			$css_class   = array( 'fastshop-testimonials' );
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'testimonials_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$owl_settings = $this->generate_carousel_data_attributes( 'owl_', $atts );

			$args = array(
				'post_type'           => 'testimonial',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			);
			if ( $atts[ 'chose_testimonial' ] == '1' ) {
				$args[ 'posts_per_page' ] = $atts[ 'per_page' ];
			}
			if ( !empty( $ids_post ) ) {
				$args[ 'p' ] = $ids_post;
			}

			$loop_posts = new WP_Query( apply_filters( 'fastshop_shortcode_posts_query', $args, $atts ) );

			ob_start();
			?>
			<?php if ( $loop_posts->have_posts() ) : ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                    <div class="owl-carousel nav2 <?php esc_html( $atts[ 'owl_navigation_position' ] ); ?>"
                        <?php echo force_balance_tags( $owl_settings ); ?>>
                        <?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
                            <?php
                            $meta_data = get_post_meta( get_the_ID(), '_custom_testimonial_options', true );
                            $avatar    = '';
                            $name      = '';
                            $position  = '';
                            $rating    = '';
                            if ( !empty( $meta_data ) ) {
                                $avatar   = $meta_data[ 'avatar_testimonial' ];
                                $name     = $meta_data[ 'name_testimonial' ];
                                $position = $meta_data[ 'position_testimonial' ];
                                $rating   = $meta_data[ 'select_rating' ];
                            }
                            if ( $rating == 1 ) {
                                $width_rating = 20;
                            } elseif ( $rating == 2 ) {
                                $width_rating = 40;
                            } elseif ( $rating == 3 ) {
                                $width_rating = 60;
                            } elseif ( $rating == 4 ) {
                                $width_rating = 80;
                            } else {
                                $width_rating = 100;
                            }
                            if ( $atts[ 'style' ] == 'style5' ){
                                $width = '210';
                                $height = '210';
                            } else {
                                $width = '140';
                                $height = '140';
                            }
                            $image_thumb = fastshop_resize_image( $avatar, null, $width, $height, true, false );
                            ?>
                            <div <?php post_class( 'testimonial-item' ); ?>>
                                <?php if ( $atts[ 'style' ] == 'default' ) : ?>
                                    <div class="avatar">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                        </a>
                                        <div class="info">
                                            <h4 class="name">
                                                <a href="<?php the_permalink(); ?>"><?php echo esc_html( $name ); ?></a>
                                            </h4>
                                            <span class="position"><?php echo esc_html( $position ); ?></span>
                                        </div>
                                    </div>
                                    <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
                                <?php elseif ( $atts[ 'style' ] == 'style3' ): ?>
                                    <div class="avatar">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                        </a>
                                        <div class="info">
                                            <h4 class="name">
                                                <a href="<?php the_permalink(); ?>"><?php echo esc_html( $name ); ?></a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
                                        <div class="star-rating">
                                            <span style="width: <?php echo esc_attr( $width_rating ); ?>%"></span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if ( $atts[ 'style' ] == 'style1' || $atts[ 'style' ] == 'style3' || $atts[ 'style' ] == 'style4' ) : ?>
                                        <p class="excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 22, esc_html__( '.', 'fastshop' ) ); ?></p>
                                    <?php endif; ?>
                                    <?php if ( $atts[ 'style' ] == 'default' || $atts[ 'style' ] == 'style2' ) : ?>
                                        <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
                                    <?php endif; ?>
                                    <div class="avatar">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                        </a>
                                        <div class="info">
                                            <h4 class="name">
                                                <a href="<?php the_permalink(); ?>"><?php echo esc_html( $name ); ?></a>
                                            </h4>
                                            <span class="position"><?php echo esc_html( $position ); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                    <?php if ( $atts[ 'link' ] ): ?>
                        <div class="block-link">
                            <?php
                            $link = vc_build_link( $atts[ 'link' ] );
                            if ( $link[ 'target' ] == '' ) {
                                $link[ 'target' ] = '_self';
                            }
                            ?>
                            <div class="block-link-content">
                                <span class="text">
                                    <?php echo esc_html__( 'We Are', 'fastshop' ); ?>
                                    <span><?php echo esc_html__( 'Work!', 'fastshop' ); ?></span>
                                </span>
                            </div>
                            <a class="view-all" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                               target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
                                <?php echo esc_html__( 'See Details', 'fastshop' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_Testimonials', force_balance_tags( $html ), $atts, $content );
		}
	}
}