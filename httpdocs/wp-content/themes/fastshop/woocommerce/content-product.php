<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
// Ensure visibility
if ( empty( $product ) || !$product->is_visible() ) {
	return;
}
// Custom columns
$fastshop_woo_bg_items      = fastshop_get_option( 'fastshop_woo_bg_items', 4 );
$fastshop_woo_lg_items      = fastshop_get_option( 'fastshop_woo_lg_items', 4 );
$fastshop_woo_md_items      = fastshop_get_option( 'fastshop_woo_md_items', 4 );
$fastshop_woo_sm_items      = fastshop_get_option( 'fastshop_woo_sm_items', 6 );
$fastshop_woo_xs_items      = fastshop_get_option( 'fastshop_woo_xs_items', 6 );
$fastshop_woo_ts_items      = fastshop_get_option( 'fastshop_woo_ts_items', 12 );
$fastshop_woo_product_style = fastshop_get_option( 'fastshop_shop_product_style', 1 );
$hide_item                  = fastshop_get_option( 'hide_product_select', '' );
$shop_display_mode          = fastshop_get_option( 'shop_page_layout', 'grid' );
if ( isset( $_SESSION['shop_display_mode'] ) ) {
	$shop_display_mode = $_SESSION['shop_display_mode'];
}
$classes[] = 'product-item';
if ( $shop_display_mode == "grid" ) {
	$classes[] = 'col-bg-' . $fastshop_woo_bg_items;
	$classes[] = 'col-lg-' . $fastshop_woo_lg_items;
	$classes[] = 'col-md-' . $fastshop_woo_md_items;
	$classes[] = 'col-sm-' . $fastshop_woo_sm_items;
	$classes[] = 'col-xs-' . $fastshop_woo_xs_items;
	$classes[] = 'col-ts-' . $fastshop_woo_ts_items;
} else {
	$classes[] = 'list col-sm-12';
}
$template_style = 'style-' . $fastshop_woo_product_style;
$classes[]      = 'style-' . $fastshop_woo_product_style;
if ( !empty( $hide_item ) ) {
	foreach ( $hide_item as $value ) {
		$classes[] = $value;
	}
}
?>

<li <?php wc_product_class( $classes ); ?>>
	<?php if ( $shop_display_mode == "grid" ): ?>
		<?php wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
	<?php else: ?>
		<?php wc_get_template_part( 'content-product', 'list' ); ?>
	<?php endif; ?>
</li>
