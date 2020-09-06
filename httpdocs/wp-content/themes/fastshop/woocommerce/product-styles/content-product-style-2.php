<?php
/*
     Name: Product style 2
     Slug: content-product-style-2
*/
?>
<?php remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20); ?>
<?php add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5); ?>
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
    <div class="equal-elem">
        <?php
        /**
         * woocommerce_shop_loop_item_title hook.
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action( 'woocommerce_shop_loop_item_title' );
        ?>
        <?php
        /**
         * woocommerce_after_shop_loop_item_title hook.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action( 'woocommerce_after_shop_loop_item_title' );
        ?>
    </div>
	<?php do_action( 'fastshop_display_product_countdown_in_loop' ); ?>
    <div class="add-to-cart">
        <?php
        /**
         * woocommerce_after_shop_loop_item hook.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action( 'woocommerce_after_shop_loop_item' );
        ?>
    </div>
</div>
<?php remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5); ?>
<?php add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20); ?>