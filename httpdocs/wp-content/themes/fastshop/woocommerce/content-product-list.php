<?php
/*
   Name: Content Product List
   Slug: content-product-list
*/
?>
<?php remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ); ?>
<?php add_action( 'fastshop_woo_get_stock_status', 'woocommerce_template_loop_price', 10 ); ?>
<div class="product-inner">
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
    <div class="product-thumb">
        <div class="thumb-inner">
            <?php
            /**
             * woocommerce_before_shop_loop_item_title hook.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            do_action('fastshop_function_shop_loop_item_quickview');
            ?>
        </div>
    </div>
    <div class="product-info">
		<?php
        do_action( 'fastshop_add_categories_product' );
		/**
		 * woocommerce_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );
		/**
		 * woocommerce_after_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
        <div class="excerpt-content">
            <?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 20, esc_html__( '...', 'fastshop' ) ); ?>
        </div>
    </div>
    <div class="product-button">
		<?php
		do_action( 'fastshop_woo_get_stock_status' );
		/**
		 * woocommerce_after_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		?>
        <div class="add-to-cart">
            <?php
            do_action( 'woocommerce_after_shop_loop_item' );
            ?>
        </div>
        <?php
        do_action('fastshop_function_shop_loop_item_compare');
        do_action('fastshop_function_shop_loop_item_wishlist');
        ?>
    </div>
</div>
<?php remove_action( 'fastshop_woo_get_stock_status', 'woocommerce_template_loop_price', 10 ); ?>
<?php add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ); ?>
