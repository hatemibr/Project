<?php
/* ==================== HOOK SHOP ==================== */
/* Remove Div cover content shop */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
/* Custom shop control */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_main_content', 'fastshop_woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_shop_loop', 'fastshop_shop_top_control', 10 );
add_action( 'fastshop_shop_banners', 'fastshop_shop_banners', 1 );
/*Custom product per page*/
add_filter( 'loop_shop_per_page', 'fastshop_loop_shop_per_page', 20 );
add_filter( 'woof_products_query', 'fastshop_woof_products_query', 20 );
/* Remove CSS */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
/* Custom Product Thumbnail */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'fastshop_template_loop_product_thumbnail', 10 );
/* ==================== HOOK SHOP ==================== */
/* ==================== SINGLE PRODUCT =============== */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
add_action( 'woocommerce_single_product_summary', 'fastshop_custom_ratting_single_product', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'fastshop_woo_get_stock_status', 15 );
add_action( 'woocommerce_single_product_summary', 'fastshop_display_product_countdown_in_loop', 15 );
add_filter( 'woocommerce_single_product_summary', 'fastshop_utilities_single_product', 40 );
/* UPSELL */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'fastshop_upsell_display', 15 );
/* RELATED */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'fastshop_related_products', 20 );
/* CROSS SELL */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'fastshop_cross_sell_products', 30 );
/* ==================== SINGLE PRODUCT =============== */
/* ==================== HOOK PRODUCT ================= */
/*Remove woocommerce_template_loop_product_link_open */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
/*Custom product name*/
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
add_action( 'woocommerce_shop_loop_item_title', 'fastshop_template_loop_product_title', 10 );
/* Add countdown in product */
add_action( 'fastshop_display_product_countdown_in_loop', 'fastshop_display_product_countdown_in_loop', 1 );
/* Stock status */
add_action( 'fastshop_woo_get_stock_status', 'fastshop_woo_get_stock_status', 1 );
/* Short Product description */
add_action( 'fastshop_product_short_description', 'fastshop_product_short_description', 15 );
/* Custom flash icon */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'fastshop_group_flash', 5 );
/* Remove Star-Ratting */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
/* Add categories to product */
add_action( 'fastshop_add_categories_product', 'fastshop_add_categories_product', 1 );
/* ==================== HOOK PRODUCT ==================== */
/* WC_Vendors */
if ( class_exists( 'WC_Vendors' ) && class_exists( 'WCV_Vendor_Shop' ) ) {
	// Add sold by to product loop before add to cart
	if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9 );
		add_action( 'woocommerce_shop_loop_item_title', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 1 );
	}
}
if ( !function_exists( 'fastshop_carousel_products' ) ) {
	function fastshop_carousel_products( $prefix, $data_args )
	{
		$classes                    = array();
		$fastshop_woo_product_style = fastshop_get_option( 'fastshop_shop_product_style', 1 );
		$classes[]      = 'product-item style-' . $fastshop_woo_product_style;
		$template_style = 'style-' . $fastshop_woo_product_style;
		$woo_ls_items = fastshop_get_option( '' . $prefix . '_ls_items', 3 );
		$woo_lg_items = fastshop_get_option( '' . $prefix . '_lg_items', 3 );
		$woo_md_items = fastshop_get_option( '' . $prefix . '_md_items', 3 );
		$woo_sm_items = fastshop_get_option( '' . $prefix . '_sm_items', 2 );
		$woo_xs_items = fastshop_get_option( '' . $prefix . '_xs_items', 1 );
		$woo_ts_items = fastshop_get_option( '' . $prefix . '_ts_items', 1 );
		$data_reponsive = array(
			'0'    => array(
				'items' => $woo_ts_items,
			),
			'480'  => array(
				'items' => $woo_xs_items,
			),
			'768'  => array(
				'items' => $woo_sm_items,
			),
			'992'  => array(
				'items' => $woo_md_items,
			),
			'1200' => array(
				'items' => $woo_lg_items,
			),
			'1500' => array(
				'items' => $woo_ls_items,
			),
		);
		$data_reponsive = json_encode( $data_reponsive );
		$title          = fastshop_get_option( '' . $prefix . '_products_title', '' );
		if ( $data_args ) : ?>
            <section class="up-sells upsells products product-grid">
                <h2 class="product-grid-title"><?php echo esc_html( $title ) ?></h2>
                <div class="owl-carousel owl-products nav2 top-right equal-container better-height" data-margin="30"
                     data-nav="true" data-dots="false" data-loop="false"
                     data-responsive='<?php echo esc_attr( $data_reponsive ); ?>'>
					<?php foreach ( $data_args as $value ) : ?>
                        <div <?php post_class( $classes ) ?>>
							<?php
							$post_object = get_post( $value->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object );
							wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </section>
		<?php endif;
		wp_reset_postdata();
	}
}
if ( !function_exists( 'fastshop_cross_sell_products' ) ) {
	function fastshop_cross_sell_products( $limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc' )
	{
		if ( is_checkout() ) {
			return;
		}
		// Get visible cross sells then sort them at random.
		$cross_sells                 = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$woocommerce_loop['name']    = 'cross-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );
		// Handle orderby and limit results.
		$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
		fastshop_carousel_products( 'fastshop_woo_crosssell', $cross_sells );
	}
}
if ( !function_exists( 'fastshop_related_products' ) ) {
	function fastshop_related_products()
	{
		global $product;
		$defaults = array(
			'posts_per_page' => 6,
			'columns'        => 6,
			'orderby'        => 'rand',
			'order'          => 'desc',
		);
		$args = wp_parse_args( $defaults );
		// Get visible related products then sort them at random.
		$args['related_products']    = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$args['related_products']    = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
		$woocommerce_loop['name']    = 'related';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $args['columns'] );
		$related_products = $args['related_products'];
		fastshop_carousel_products( 'fastshop_woo_related', $related_products );
	}
}
if ( !function_exists( 'fastshop_upsell_display' ) ) {
	function fastshop_upsell_display( $orderby = 'rand', $order = 'desc', $limit = '-1', $columns = 4 )
	{
		global $product;
		// Handle the legacy filter which controlled posts per page etc.
		$args = array(
			'posts_per_page' => 4,
			'orderby'        => 'rand',
			'columns'        => 4,
		);
		$woocommerce_loop['name']    = 'up-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns );
		$orderby                     = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
		$limit                       = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
		// Get visible upsells then sort them at random, then limit result set.
		$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;
		fastshop_carousel_products( 'fastshop_woo_upsell', $upsells );
	}
}
// Utilities
if ( !function_exists( 'fastshop_utilities_single_product' ) ) {
	function fastshop_utilities_single_product()
	{
		global $product;
		$share_link_title = 'Product on ' . ucfirst( wp_get_theme()->get( 'Name' ) );
		$share_link_url   = get_permalink( $product->get_id() );
		?>
        <div class="share">
            <a class="print" data-element="single-product" href="javascript:print();">
                <i class="fa fa-print"></i>
				<?php echo esc_html__( 'Print', 'fastshop' ); ?>
            </a>
            <a class="send-to-friend"
               href="mailto:?subject=<?php echo urlencode( esc_html__( 'I wanted you to see this site', 'fastshop' ) ) ?>&amp;body=<?php echo urlencode( $share_link_url ) ?>&amp;title=<?php echo esc_attr( $share_link_title ); ?>"
               title="<?php echo esc_html__( 'Email', 'fastshop' ) ?>">
                <i class="fa fa-envelope-o"></i>
				<?php echo esc_html__( 'Send to a friend', 'fastshop' ); ?>
            </a>
        </div>
		<?php
	}
}
/* CUSTOM RATTING SINGLE PRODUCT */
if ( !function_exists( 'fastshop_custom_ratting_single_product' ) ) {
	function fastshop_custom_ratting_single_product()
	{
		global $product;
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
			return;
		}
		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();
		if ( $rating_count > 0 ) : ?>

            <div class="woocommerce-product-rating">
                <span class="star-rating">
                    <span style="width:<?php echo( ( $average / 5 ) * 100 ); ?>%">
                        <?php
						/* translators: 1: average rating 2: max rating (i.e. 5) */
						printf(
							__( '%1$s out of %2$s', 'fastshop' ),
							'<strong class="rating">' . esc_html( $average ) . '</strong>',
							'<span>5</span>'
						);
						?>
                    </span>
                </span>
                <span>
                    <?php
					/* translators: %s: rating count */
					printf(
						_n( 'based on %s customer rating', 'Based on %s ratings', $rating_count, 'fastshop' ),
						'<span class="rating">' . esc_html( $rating_count ) . '</span>'
					);
					?>
                </span>
				<?php if ( comments_open() ) : ?>
                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                        <!--                        <i class="pe-7s-pen"></i>-->
                        <i class="fa fa-pencil" aria-hidden="true"></i>
						<?php echo esc_html__( 'write a preview', 'fastshop' ) ?>
                    </a>
				<?php endif ?>
            </div>

		<?php endif;
	}
}
/* CUSTOM PRODUCT TITLE */
if ( !function_exists( 'fastshop_template_loop_product_title' ) ) {
	function fastshop_template_loop_product_title()
	{
		$title_class = array( 'product-name product_title' );
		?>
        <h3 class="<?php echo esc_attr( implode( ' ', $title_class ) ); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php
	}
}
/* CUSTOM PAGINATION */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'fastshop_custom_pagination', 10 );
if ( !function_exists( 'fastshop_custom_pagination' ) ) {
	function fastshop_custom_pagination()
	{
		global $wp_query;
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		?>
        <nav class="woocommerce-pagination pagination">
			<?php
			echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
						'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
						'format'    => '',
						'add_args'  => false,
						'current'   => max( 1, get_query_var( 'paged' ) ),
						'total'     => $wp_query->max_num_pages,
						'prev_text' => esc_html__( 'Previous', 'fastshop' ),
						'next_text' => esc_html__( 'Next', 'fastshop' ),
						'type'      => 'plain',
						'end_size'  => 3,
						'mid_size'  => 3,
					)
				)
			);
			?>
        </nav>
		<?php
	}
}
/* CUSTOM RATTING */
add_filter( "woocommerce_product_get_rating_html", "fastshop_get_rating_html", 10, 2 );
if ( !function_exists( 'fastshop_get_rating_html ' ) ) {
	function fastshop_get_rating_html( $rating_html, $rating )
	{
		global $product;
		$count = $product->get_review_count();
		if ( $rating > 0 ) {
			$rating_html = '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'fastshop' ), $rating ) . '">';
			$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong></span>';
			$rating_html .= '<p class="number-count">(' . esc_html( zeroise( $count, 2 ) ) . ')</p>';
			if ( $count < 2 ) {
				$rating_html .= '<p class="preview-count">(' . esc_html( $count ) . esc_html__( ' Preview', 'fastshop' ) . ')</p>';
			} else {
				$rating_html .= '<p class="preview-count">(' . esc_html( $count ) . esc_html__( ' Previews', 'fastshop' ) . ')</p>';
			}
			$rating_html .= '</div>';
		} else {
			$rating_html = '';
		}

		return $rating_html;
	}
}
/* CUSTOM PRODUCT THUMBNAIL */
if ( !function_exists( 'fastshop_template_loop_product_thumbnail' ) ) {
	function fastshop_template_loop_product_thumbnail()
	{
		global $product;
		$fastshop_enable_lazy = fastshop_get_option( 'fastshop_theme_lazy_load' );
		// GET SIZE IMAGE SETTING
		$w    = 300;
		$h    = 300;
		$crop = true;
		$size = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$w = $size['width'];
			$h = $size['height'];
			if ( !$size['crop'] ) {
				$crop = false;
			}
		}
		$w          = apply_filters( 'fastshop_shop_pruduct_thumb_width', $w );
		$h          = apply_filters( 'fastshop_shop_pruduct_thumb_height', $h );
		$lazy_check = false;
		if ( $fastshop_enable_lazy == 1 ) {
			$lazy_check = true;
		} elseif ( $w < 120 || $fastshop_enable_lazy != 1 ) {
			$lazy_check = false;
		}
		if ( is_shop() || is_product() || is_cart() ) {
			$w = 230;
			$h = 230;
		}
		ob_start();
		?>
        <a class="thumb-link" href="<?php the_permalink(); ?>">
			<?php
			$image_thumb = fastshop_resize_image( get_post_thumbnail_id( $product->get_id() ), null, $w, $h, true, $lazy_check );
			?>
			<?php echo force_balance_tags( $image_thumb['img'] ); ?>
        </a>
		<?php
		echo ob_get_clean();
	}
}
/* ADD CATEGORIES LIST IN PRODUCT */
if ( !function_exists( 'fastshop_add_categories_product' ) ) {
	function fastshop_add_categories_product()
	{
		$html = '';
		$html .= '<span class="cat-list">';
		$html .= wc_get_product_category_list( get_the_ID() );
		$html .= '</span>';
		echo htmlspecialchars_decode( $html );
	}
}
/* CUSTOM BREADCRUMB */
if ( !function_exists( 'fastshop_woocommerce_breadcrumb' ) ) {
	function fastshop_woocommerce_breadcrumb()
	{
		$args = array(
			'delimiter'   => '',
			'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs"><ul class="breadcrumb">',
			'wrap_after'  => '</ul><a href="' . wp_get_referer() . '">' . esc_html__( 'Return To Previous Page', 'fastshop' ) . '</a></nav>',
			'before'      => '<li>',
			'after'       => '</li>',
		);
		woocommerce_breadcrumb( $args );
	}
}
/* HOOK CONTROL */
if ( !function_exists( 'fastshop_shop_top_control' ) ) {
	function fastshop_shop_top_control()
	{
		get_template_part( 'template-parts/shop-top', 'control' );
	}
}
/* SET VIEW MORE */
if ( isset( $_POST["shop_display_mode"] ) ) {
	session_start();
	$_SESSION['shop_display_mode'] = $_POST["shop_display_mode"];
}
/* VIEW MORE */
if ( !function_exists( 'fastshop_shop_view_more' ) ) {
	function fastshop_shop_view_more()
	{
		$shop_display_mode = fastshop_get_option( 'shop_page_layout', 'grid' );
		if ( isset( $_SESSION['shop_display_mode'] ) ) {
			$shop_display_mode = $_SESSION['shop_display_mode'];
		}
		?>
        <div class="grid-view-mode">
            <a data-mode="grid"
               class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-17grid"></i>
				<?php echo esc_html__( 'Grid', 'fastshop' ) ?>
            </a>
            <a data-mode="list"
               class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-18list"></i>
				<?php echo esc_html__( 'List', 'fastshop' ) ?>
            </a>
        </div>
		<?php
	}
}
if ( !function_exists( 'fastshop_loop_shop_per_page' ) ) {
	function fastshop_loop_shop_per_page()
	{
		$fastshop_woo_products_perpage = fastshop_get_option( 'product_per_page', '12' );
		if ( isset( $_SESSION['fastshop_woo_products_perpage'] ) ) {
			$fastshop_woo_products_perpage = $_SESSION['fastshop_woo_products_perpage'];
		}

		return $fastshop_woo_products_perpage;
	}
}
if ( !function_exists( 'fastshop_woof_products_query' ) ) {
	function fastshop_woof_products_query( $wr )
	{
		$fastshop_woo_products_perpage = fastshop_get_option( 'product_per_page', '12' );
		$wr['posts_per_page']          = $fastshop_woo_products_perpage;

		return $wr;
	}
}
/* SET PRODUCT PER PAGE */
if ( isset( $_POST["fastshop_woo_products_perpage"] ) ) {
	session_start();
	$_SESSION['fastshop_woo_products_perpage'] = $_POST["fastshop_woo_products_perpage"];
}
/*----------------------
POST PER PAGE SHOP
----------------------*/
if ( !function_exists( 'fastshop_shop_post_perpage' ) ) {
	function fastshop_shop_post_perpage()
	{
		$perpage = fastshop_get_option( 'product_per_page', '12' );
		if ( isset( $_SESSION['fastshop_woo_products_perpage'] ) ) {
			$perpage = $_SESSION['fastshop_woo_products_perpage'];
		}
		$i = 0;
		?>
        <select name="perpage" class="option-perpage">
            <option value="5" <?php if ( $perpage == 5 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '05 per page', 'fastshop' ); ?>
            </option>
            <option value="10" <?php if ( $perpage == 10 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '10 per page', 'fastshop' ); ?>
            </option>
            <option value="15" <?php if ( $perpage == 15 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '15 per page', 'fastshop' ); ?>
            </option>
            <option value="20" <?php if ( $perpage == 20 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '20 per page', 'fastshop' ); ?>
            </option>
            <option value="25" <?php if ( $perpage == 25 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '25 per page', 'fastshop' ); ?>
            </option>
            <option value="30" <?php if ( $perpage == 30 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '30 per page', 'fastshop' ); ?>
            </option>
            <option value="35" <?php if ( $perpage == 35 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '35 per page', 'fastshop' ); ?>
            </option>
            <option value="40" <?php if ( $perpage == 40 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '40 per page', 'fastshop' ); ?>
            </option>
            <option value="45" <?php if ( $perpage == 45 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '45 per page', 'fastshop' ); ?>
            </option>
            <option value="50" <?php if ( $perpage == 50 ) {
				echo 'selected';
				$i++;
			} ?>><?php echo esc_html__( '50 per page', 'fastshop' ); ?>
            </option>
            <option value="" <?php if ( $i == 0 ) {
				echo 'selected';
			} ?>><?php echo esc_html__( 'Choose value ...', 'fastshop' ); ?>
            </option>
        </select>
		<?php
	}
}
/* SHOP BANNER */
if ( !function_exists( 'fastshop_shop_banners' ) ) {
	function fastshop_shop_banners()
	{
		get_template_part( 'template-parts/shop', 'banners' );
	}
}
/* QUICK VIEW */
if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
	// Class frontend
	$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
	$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
	// Class frontend
	if ( ( !wp_is_mobile() && $enable ) || ( wp_is_mobile() && $enable_on_mobile && $enable ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 15 );
		add_action( 'fastshop_function_shop_loop_item_quickview', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 5 );
	}
}
/* WISH LIST */
if ( class_exists( 'YITH_WCWL' ) && get_option( 'yith_wcwl_enabled' ) == 'yes' ) {
	if ( !function_exists( 'fastshop_wc_loop_product_wishlist_btn' ) ) {
		function fastshop_wc_loop_product_wishlist_btn()
		{
			if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			}
		}
	}
	add_action( 'fastshop_function_shop_loop_item_wishlist', 'fastshop_wc_loop_product_wishlist_btn', 1 );
}
/* COMPARE */
if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
	global $yith_woocompare;
	$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	if ( $yith_woocompare->is_frontend() || $is_ajax ) {
		if ( $is_ajax ) {
			if ( !class_exists( 'YITH_Woocompare_Frontend' ) ) {
				if ( file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
					require_once( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' );
				}
			}
			$yith_woocompare->obj = new YITH_Woocompare_Frontend();
		}
		/* Remove button */
		remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
		/* Add compare button */
		if ( !function_exists( 'fastshop_wc_loop_product_compare_btn' ) ) {
			function fastshop_wc_loop_product_compare_btn()
			{
				if ( shortcode_exists( 'yith_compare_button' ) ) {
					echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
				} // End if ( shortcode_exists( 'yith_compare_button' ) )
				else {
					if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
						$YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
						echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
					}
				}
			}
		}
		add_action( 'fastshop_function_shop_loop_item_compare', 'fastshop_wc_loop_product_compare_btn', 1 );
	}
}
/* GROUP NEW FLASH */
if ( !function_exists( 'fastshop_group_flash' ) ) {
	function fastshop_group_flash()
	{
		global $product;
		?>
        <div class="flash">
			<?php
			woocommerce_show_product_loop_sale_flash();
			fastshop_show_product_loop_new_flash();
			?>
        </div>
		<?php
	}
}
if ( !function_exists( 'fastshop_show_product_loop_new_flash' ) ) {
	/**
	 * Get the sale flash for the loop.
	 *
	 * @subpackage    Loop
	 */
	function fastshop_show_product_loop_new_flash()
	{
		wc_get_template( 'loop/new-flash.php' );
	}
}
add_filter( 'woocommerce_sale_flash', 'fastshop_custom_sale_flash' );
if ( !function_exists( 'fastshop_custom_sale_flash' ) ) {
	function fastshop_custom_sale_flash( $text )
	{
		$percent = fastshop_get_percent_discount();
		if ( $percent != '' ) {
			return '<span class="onsale">' . $percent . '</span>';
		} else {
			return '';
		}
	}
}
if ( !function_exists( 'fastshop_get_percent_discount' ) ) {
	function fastshop_get_percent_discount()
	{
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;
				for ( $i = 0; $i < count( $available_variations ); ++$i ) {
					$variation_id = $available_variations[$i]['variation_id'];
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}
					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}
			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}

		return $percent;
	}
}
/* GROUP NEW FLASH */
/* STOCK STATUS */
if ( !function_exists( 'fastshop_woo_get_stock_status' ) ) {
	function fastshop_woo_get_stock_status()
	{
		global $product;
		?>
        <div class="product-info-stock-sku">
            <div class="stock available">
                <span class="label-available"><?php esc_html_e( 'Avaiability: ', 'fastshop' ); ?> </span><?php $product->is_in_stock() ? printf( esc_html__( '%s In Stock', 'fastshop' ), $product->get_stock_quantity() ) : esc_html_e( 'Out Of Stock', 'fastshop' ); ?>
            </div>
        </div>
		<?php
	}
}
/* CUSTOM DESCRIPTION */
if ( !function_exists( 'fastshop_product_short_description' ) ) {
	function fastshop_product_short_description()
	{
		global $post;
		$shop_display_mode = fastshop_get_option( 'shop_page_layout', 'grid' );
		if ( isset( $_SESSION['shop_display_mode'] ) ) {
			$shop_display_mode = $_SESSION['shop_display_mode'];
		}
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( !$post->post_excerpt ) return;
			if ( $shop_display_mode == "grid" ) return;
			?>
            <div class="product-des">
				<?php the_excerpt(); ?>
            </div>
			<?php
		}
	}
}
/* COUNTDOWN IN LOOP */
if ( !function_exists( 'fastshop_display_product_countdown_in_loop' ) ) {
	function fastshop_display_product_countdown_in_loop()
	{
		global $product;
		$date = fastshop_get_max_date_sale( $product->get_id() );
		?>
		<?php if ( $date > 0 ):
		$y = date( 'Y', $date );
		$m    = date( 'm', $date );
		$d    = date( 'd', $date );
		$h    = date( 'h', $date );
		$i    = date( 'i', $date );
		$s    = date( 's', $date );
		?>
        <div class="product-count-down">
            <div class="fastshop-countdown" data-y="<?php echo esc_attr( $y ); ?>"
                 data-m="<?php echo esc_attr( $m ); ?>"
                 data-d="<?php echo esc_attr( $d ); ?>" data-h="<?php echo esc_attr( $h ); ?>"
                 data-i="<?php echo esc_attr( $i ); ?>" data-s="<?php echo esc_attr( $s ); ?>"></div>
        </div>
	<?php endif; ?>
		<?php
	}
}
// GET DATE SALE
if ( !function_exists( 'fastshop_get_max_date_sale' ) ) {
	function fastshop_get_max_date_sale( $product_id )
	{
		$time = 0;
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => -1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		if ( !empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "
        SELECT
        meta_value
        FROM $wpdb->postmeta
        WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ")
        ORDER BY meta_value DESC
        LIMIT 1
    "
			);
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		if ( !$sale_price_dates_to ) {
			$sale_price_dates_to = get_post_meta( $product_id, '_sale_price_dates_to', true );
			if ( $sale_price_dates_to == '' ) {
				$sale_price_dates_to = '0';
			}

			return $sale_price_dates_to;
		}
	}
}
/* AJAX UPDATE WISH LIST */
if ( !function_exists( ( 'fastshop_update_wishlist_count' ) ) ) {
	function fastshop_update_wishlist_count()
	{
		if ( function_exists( 'YITH_WCWL' ) ) {
			wp_send_json( YITH_WCWL()->count_products() );
		}
	}

	// Wishlist ajaxify update
	add_action( 'wp_ajax_fastshop_update_wishlist_count', 'fastshop_update_wishlist_count' );
	add_action( 'wp_ajax_nopriv_fastshop_update_wishlist_count', 'fastshop_update_wishlist_count' );
}
/* AJAX MINI CART */
if ( !function_exists( 'fastshop_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	add_filter( 'woocommerce_add_to_cart_fragments', 'fastshop_cart_link_fragment' );
	function fastshop_cart_link_fragment( $fragments )
	{
		global $woocommerce;
		ob_start();
		?>
        <div class="block-minicart fastshop-mini-cart">
			<?php get_template_part( 'template-parts/header-mini', 'cart' ); ?>
        </div>
		<?php
		$fragments['div.fastshop-mini-cart'] = ob_get_clean();

		return $fragments;
	}
}
/* REMOVE CART ITEM */
if ( !function_exists( 'fastshop_remove_cart_item_via_ajax' ) ) {
	add_action( 'wp_ajax_fastshop_remove_cart_item_via_ajax', 'fastshop_remove_cart_item_via_ajax' );
	add_action( 'wp_ajax_nopriv_fastshop_remove_cart_item_via_ajax', 'fastshop_remove_cart_item_via_ajax' );
	function fastshop_remove_cart_item_via_ajax()
	{
		$response = array(
			'message'        => '',
			'fragments'      => '',
			'cart_hash'      => '',
			'mini_cart_html' => '',
			'err'            => 'no',
		);
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
		$nonce         = isset( $_POST['nonce'] ) ? trim( $_POST['nonce'] ) : '';
		if ( $cart_item_key == '' || $nonce == '' ) {
			$response['err'] = 'yes';
			wp_send_json( $response );
		}
		if ( ( wp_verify_nonce( $nonce, 'woocommerce-cart' ) ) ) {
			if ( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) ) {
				WC()->cart->remove_cart_item( $cart_item_key );
				$product = wc_get_product( $cart_item['product_id'] );
				$item_removed_title = apply_filters( 'woocommerce_cart_item_removed_title', $product ? sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'fastshop' ), $product->get_name() ) : esc_html__( 'Item', 'fastshop' ), $cart_item );
				// Don't show undo link if removed item is out of stock.
				if ( $product->is_in_stock() && $product->has_enough_stock( $cart_item['quantity'] ) ) {
					$removed_notice = sprintf( esc_html__( '%s removed.', 'fastshop' ), $item_removed_title );
					$removed_notice .= ' <a href="' . esc_url( WC()->cart->get_undo_url( $cart_item_key ) ) . '">' . esc_html__( 'Undo?', 'fastshop' ) . '</a>';
				} else {
					$removed_notice = sprintf( esc_html__( '%s removed.', 'fastshop' ), $item_removed_title );
				}
				wc_add_notice( $removed_notice );
			}
		} else {
			$response['message'] = esc_html__( 'Security check error!', 'fastshop' );
			$response['err']     = 'yes';
			wp_send_json( $response );
		}
		ob_start();
		get_template_part( 'template-parts/header-mini', 'cart' );
		$mini_cart = ob_get_clean();
		$response['fragments']      = apply_filters( 'woocommerce_add_to_cart_fragments', array(
				'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		);
		$response['cart_hash']      = apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() );
		$response['mini_cart_html'] = $mini_cart;
		wp_send_json( $response );
		die();
	}
}
/**
 * Add this code to the functions.php file of your theme.
 */
add_filter( 'wcml_multi_currency_ajax_actions', 'fastshop_add_action_to_multi_currency_ajax', 10, 1 );
function fastshop_add_action_to_multi_currency_ajax( $ajax_actions )
{
	$ajax_actions[] = 'fastshop_ajax_tabs'; // Add a AJAX action to the array

	return $ajax_actions;
}

function fastshop_detect_shortcode( $id, $tab_id )
{
	$post              = get_post( $id );
	$content           = preg_replace( '/\s+/', ' ', $post->post_content );
	$shortcode_section = '';
	preg_match_all( '/\[vc_tta_section(.*?)vc_tta_section\]/', $content, $matches );
	if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
		foreach ( $matches[0] as $key => $value ) {
			preg_match_all( '/tab_id="([^"]+)"/', $value, $matches_ids );
			foreach ( $matches_ids[1] as $matches_id ) {
				if ( $tab_id == $matches_id ) {
					$shortcode_section = $value;
				}
			}
		}
	}

	return $shortcode_section;
}

/* AJAX TABS */
if ( !function_exists( ( 'fastshop_ajax_tabs' ) ) ) {
	function fastshop_ajax_tabs()
	{
		$response   = array(
			'html'    => '',
			'message' => '',
			'success' => 'no',
		);
		$section_id = isset( $_POST['section_id'] ) ? $_POST['section_id'] : '';
		$id         = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$shortcode  = fastshop_detect_shortcode( $id, $section_id );
		WPBMap::addAllMappedShortcodes();
		$response['html']    = do_shortcode( $shortcode );
		$response['success'] = 'ok';
		wp_send_json( $response );
		die();
	}

	// TABS ajaxify update
	add_action( 'wp_ajax_fastshop_ajax_tabs', 'fastshop_ajax_tabs' );
	add_action( 'wp_ajax_nopriv_fastshop_ajax_tabs', 'fastshop_ajax_tabs' );
}
/**
 *
 * REMOVE DESCRIPTION HEADING, INFOMATION HEADING
 */
add_filter( 'woocommerce_product_description_heading', 'fastshop_product_description_heading' );
if ( !function_exists( 'fastshop_product_description_heading' ) ) {
    function fastshop_product_description_heading()
    {
        return '';
    }
}
add_filter( 'woocommerce_product_additional_information_heading', 'fastshop_product_additional_information_heading' );
if ( !function_exists( 'fastshop_product_additional_information_heading' ) ) {
    function fastshop_product_additional_information_heading()
    {
        return '';
    }
}