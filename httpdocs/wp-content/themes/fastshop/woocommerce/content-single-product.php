<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woothemes.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.4.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
$style_single_product     = fastshop_get_option( 'style_single_product', 'style-standard-horizon' );
$sidebar_product_position = fastshop_get_option( 'sidebar_product_position', 'left' );
if ( $sidebar_product_position != 'full' && $style_single_product == 'style-standard-vertical' ||
	$sidebar_product_position != 'full' && $style_single_product == 'style-with-thumbnail' ) {
	$style_single_product = 'style-standard-horizon';
}
?>
<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
$data_meta  = get_post_meta( get_the_ID(), '_custom_product_options', true );
$view_video = '';
if ( isset( $data_meta['fastshop_product_video'] ) && $data_meta['fastshop_product_video'] != '' ) {
	$id       = 'ytp' . rand();
	$response = wp_remote_get( 'http://www.youtube.com/oembed?url=' . urlencode( $data_meta['fastshop_product_video'] ) );
	if ( is_array( $response ) ) {
		$body = json_decode( $response['body'] ); // use the content
		$href = "#TB_inline?height={$body->height}&width={$body->width}&inlineId={$id}";
		ob_start();
		?>
        <div id="<?php echo esc_attr( $id ); ?>"
             style="display:none;"><?php echo htmlspecialchars_decode( $body->html ); ?></div>
        <a class="video-product thickbox" href="<?php echo esc_url( $href ); ?>">
            <span class="icon pe-7s-video"></span>
        </a>
		<?php
		$view_video = ob_get_clean();
	}
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $style_single_product ); ?>>
    <div class="single-left">
		<?php echo $view_video; ?>
		<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>
    </div>
    <div class="entry-summary">
        <div class="summary">
			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>
        </div>
    </div>
</div><!-- #product-<?php the_ID(); ?> -->
<?php
/**
 * woocommerce_after_single_product_summary hook.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
do_action( 'woocommerce_after_single_product_summary' );
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
