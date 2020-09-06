<?php get_header(); ?>
<?php
/*Shop layout*/
$fastshop_woo_shop_layout  = fastshop_get_option( 'sidebar_shop_page_position', 'left' );
$fastshop_woo_shop_sidebar = fastshop_get_option( 'shop_page_sidebar', 'shop-widget-area' );

if ( is_product() ) {
	$fastshop_woo_shop_layout  = fastshop_get_option( 'sidebar_product_position', 'left' );
	$fastshop_woo_shop_sidebar = fastshop_get_option( 'single_product_sidebar', 'product-widget-area' );
}

/*Main container class*/
$main_container_class   = array();
$main_container_class[] = 'main-container shop-page';
if ( $fastshop_woo_shop_layout == 'full' ) {
	$main_container_class[] = 'no-sidebar';
} else {
	$main_container_class[] = $fastshop_woo_shop_layout . '-sidebar';
}

/*Setting single product*/

$main_content_class   = array();
$main_content_class[] = 'main-content';
if ( $fastshop_woo_shop_layout == 'full' ) {
	$main_content_class[] = 'col-sm-12';
} else {
	$main_content_class[] = 'col-lg-9 col-md-8 has-sidebar';
}

$slidebar_class   = array();
$slidebar_class[] = 'sidebar';
if ( $fastshop_woo_shop_layout != 'full' ) {
	$slidebar_class[] = 'col-lg-3 col-md-4';
}
?>
    <div class="<?php echo esc_attr( implode( ' ', $main_container_class ) ); ?>">
		<div class="container">
            <?php
            /**
             * woocommerce_before_main_content hook
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
            ?>
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $main_content_class ) ); ?>">
					<?php
					if ( !is_single() ) {
						do_action( 'fastshop_shop_banners');
					}
					?>
					<?php
					/**
					 * fastshop_woocommerce_before_loop_start hook
					 */
					do_action( 'fastshop_woocommerce_before_loop_start' );

					woocommerce_content();

					/**
					 * fastshop_woocommerce_before_loop_start hook
					 */
					do_action( 'fastshop_woocommerce_fater_loop_start' );
					?>
                </div>
				<?php
				/**
				 * woocommerce_after_main_content hook.
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );
				?>
				<?php if ( $fastshop_woo_shop_layout != "full" ): ?>
                    <div class="<?php echo esc_attr( implode( ' ', $slidebar_class ) ); ?>">
						<?php if ( is_active_sidebar( $fastshop_woo_shop_sidebar ) ) : ?>
                            <div id="widget-area" class="widget-area shop-sidebar">
								<?php dynamic_sidebar( $fastshop_woo_shop_sidebar ); ?>
                            </div><!-- .widget-area -->
						<?php endif; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>