<?php

if ( !class_exists( 'Fastshop_Shortcode_Products' ) ) {
	class Fastshop_Shortcode_Products extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'products';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			extract( $atts );
			$css = '';
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' || $atts[ 'owl_navigation_position' ] == 'nav2 top-right' || $atts[ 'owl_navigation_position' ] == 'nav2 top-center' ) {
				$css .= '.' . $atts[ 'products_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ top:' . $atts[ 'owl_navigation_position_top' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' ) {
				$css .= '.' . $atts[ 'products_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ left:' . $atts[ 'owl_navigation_offset_left' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-right' ) {
				$css .= '.' . $atts[ 'products_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ right:' . $atts[ 'owl_navigation_offset_right' ] . 'px;} ';
			}

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_products', $atts ) : $atts;

			extract( $atts );
			$css_class   = array( 'fastshop-products' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'products_custom_id' ];
			$css_class[] = 'style-' . $product_style;
			if ( $atts[ 'none_border' ] == true ) {
				$css_class[] = 'fastshop-hide-border';
			}
			if ( $the_title ) {
				$css_class[] = 'has-title';
			}
			if ( $atts[ 'add_border_img' ] == true ) {
				$css_class[] = 'add-border-img';
			}

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}


			/* Product Size */

			if ( $product_image_size ) {
				if ( $product_image_size == 'custom' ) {
					$thumb_width  = $product_custom_thumb_width;
					$thumb_height = $product_custom_thumb_height;
				} else {
					$product_image_size = explode( "x", $product_image_size );
					$thumb_width        = $product_image_size[ 0 ];
					$thumb_height       = $product_image_size[ 1 ];
				}
				if ( $thumb_width > 0 ) {
					add_filter( 'fastshop_shop_pruduct_thumb_width', create_function( '', 'return ' . $thumb_width . ';' ) );
				}
				if ( $thumb_height > 0 ) {
					add_filter( 'fastshop_shop_pruduct_thumb_height', create_function( '', 'return ' . $thumb_height . ';' ) );
				}
			}
			$products      = $this->getProducts( $atts );
			$total_product = $products->post_count;

			$product_item_class   = array( 'product-item', $atts[ 'target' ] );
			$product_item_class[] = 'style-' . $product_style;
			$product_item_class[] = $atts[ 'none_cat' ] == true ? 'hide-cat' : '';
			$product_item_class[] = $atts[ 'none_rating' ] == true ? 'hide-rating' : '';
			$product_item_class[] = $atts[ 'none_border' ] == true ? 'hide-border' : '';

			$product_list_class = array();
			$owl_settings       = '';
			if ( $productsliststyle == 'grid' ) {
				$product_list_class[] = 'product-list-grid row auto-clear equal-container better-height ';

				$product_item_class[] = $boostrap_rows_space;
				$product_item_class[] = 'col-bg-' . $boostrap_bg_items;
				$product_item_class[] = 'col-lg-' . $boostrap_lg_items;
				$product_item_class[] = 'col-md-' . $boostrap_md_items;
				$product_item_class[] = 'col-sm-' . $boostrap_sm_items;
				$product_item_class[] = 'col-xs-' . $boostrap_xs_items;
				$product_item_class[] = 'col-ts-' . $boostrap_ts_items;
			}
			if ( $productsliststyle == 'owl' ) {
				if ( $total_product < $owl_lg_items ) {
					$atts[ 'owl_loop' ] = 'false';
				}
				$product_list_class[] = 'product-list-owl owl-carousel equal-container better-height ' . $owl_navigation_position;

				$product_item_class[] = $owl_rows_space;

				$owl_settings = $this->generate_carousel_data_attributes( 'owl_', $atts );
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $the_title ) : ?>
                    <div class="fastshop-title default <?php echo $atts[ 'title_align' ]; ?>">
                        <h2 class="title"><?php echo esc_html( $the_title ); ?></h2>
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
				<?php endif; ?>
				<?php if ( $products->have_posts() ): ?>
					<?php if ( $productsliststyle == 'grid' ): ?>
                        <ul class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>">
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                <li <?php post_class( $product_item_class ); ?>>
									<?php wc_get_template_part( 'product-styles/content-product-style', $product_style ); ?>
                                </li>
							<?php endwhile; ?>
                        </ul>
                        <!-- OWL Products -->
					<?php elseif ( $productsliststyle == 'owl' ) : ?>
						<?php $i = 1; ?>
                        <div class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>" <?php echo force_balance_tags( $owl_settings ); ?>>
                            <div class="owl-one-row">
								<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                    <div <?php post_class( $product_item_class ); ?>>
										<?php wc_get_template_part( 'product-styles/content-product-style', $product_style ); ?>
                                    </div>
									<?php
									if ( $i % $owl_number_row == 0 && $i < $total_product ) {
										echo '</div><div class="owl-one-row">';
									}
									$i++;
									?>
								<?php endwhile; ?>
                            </div>
                        </div>
					<?php endif; ?>
					<?php if ( $atts[ 'more_items' ] == true ) : ?>
                        <div class="more-items">
                            <a href="<?php echo get_page_link( get_page_by_title( 'shop' )->ID ); ?>"
                               class=" cp-button button-block button-larger">
								<?php echo esc_html__( 'More items', 'fastshop-toolkit' ); ?>
                            </a>
                        </div>
					<?php endif; ?>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'fastshop-toolkit' ); ?></strong>
                    </p>
				<?php endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_products', force_balance_tags( $html ), $atts, $content );
		}
	}
}