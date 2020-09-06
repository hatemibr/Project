<?php
/*
     Name: Product style 9
     Slug: content-product-style-9
*/
?>
<?php remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 ); ?>
<?php remove_action( 'woocommerce_before_shop_loop_item_title', 'fastshop_group_flash', 5 ); ?>
<div class="product-inner equal-elem">
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
			?>
		</div>
	</div>
	<div class="product-info">
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
         * @hooked woocommerce_template_loop_rating - 20
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action( 'woocommerce_after_shop_loop_item_title' );
        ?>
	</div>
</div>
<?php add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 ); ?>
<?php add_action( 'woocommerce_before_shop_loop_item_title', 'fastshop_group_flash', 5 ); ?>
