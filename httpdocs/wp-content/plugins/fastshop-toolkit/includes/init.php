<?php
include_once( 'classes/welcome.php' );
include_once( 'framework/cs-framework.php' );
/* MAILCHIP */
include_once( 'classes/mailchimp/MCAPI.class.php' );
include_once( 'classes/mailchimp/mailchimp-settings.php' );
include_once( 'classes/mailchimp/mailchimp.php' );
/* WIDGET */
include_once( 'widgets/widget-socials.php' );
include_once( 'widgets/widget-testimonial.php' );

if ( !function_exists( 'fastshop_toolkit_vc_param' ) ) {
	function fastshop_toolkit_vc_param( $key = false, $value = false )
	{
		if ( class_exists( 'Vc_Manager' ) ) {
			return vc_add_shortcode_param( $key, $value );
		}
	}

	add_action( 'init', 'fastshop_toolkit_vc_param' );
}
if ( class_exists( 'WooCommerce' ) ) {
	include_once( 'widgets/widget-products.php' );
	include_once( 'widgets/widget-woo-layered-nav.php' );
	include_once( 'classes/woo-attributes-swatches/woo-term.php' );
	include_once( 'classes/woo-attributes-swatches/woo-product-attribute-meta.php' );
	/* SHARE SINGLE PRODUCT */
	if ( !function_exists( 'fastshop_single_product_share' ) ) {
		function fastshop_single_product_share()
		{
			global $post;
			$enable_share_product  = fastshop_get_option( 'enable_share_product' );
			$share_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$share_link_url        = get_permalink( $post->ID );
			$share_link_title      = get_the_title();
			$share_twitter_summary = get_the_excerpt();

			if ( $enable_share_product == 1 ) :
				?>
                <div class="fastshop-single-product-socials">
                    <a target="_blank" class="facebook"
                       href="https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D=<?php echo $share_link_title ?>&amp;p%5Burl%5D=<?php echo urlencode( $share_link_url ) ?>"
                       title="<?php _e( 'Facebook', 'fastshop' ) ?>">
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/facebook.png' ?>" alt="">
                    </a>
                    <a target="_blank" class="googleplus"
                       href="https://plus.google.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;title=<?php echo $share_link_title ?>"
                       title="<?php _e( 'Google+', 'fastshop' ) ?>"
                       onclick='javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/gplus.png' ?>" alt="">
                    </a>
                    <a target="_blank" class="pinterest"
                       href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( $share_link_url ) ?>&amp;description=<?php echo $share_twitter_summary ?>&amp;media=<?php echo $share_image_url ?>"
                       title="<?php _e( 'Pinterest', 'fastshop' ) ?>"
                       onclick="window.open(this.href); return false;">
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/share.png' ?>" alt="">
                    </a>
                    <a target="_blank" class="twitter"
                       href="https://twitter.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;text=<?php echo $share_twitter_summary ?>"
                       title="<?php _e( 'Twitter', 'yith-woocommerce-wishlist' ) ?>">
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/tweet.png' ?>" alt="">
                    </a>
                </div>
				<?php
			endif;
		}
	}
	add_action( 'woocommerce_share', 'fastshop_single_product_share' );
	/* SHARE SINGLE PRODUCT */
}


/* CUSTOM BACKGROUND */
if ( !function_exists( 'fastshop_custom_background_cb' ) ) {
	function fastshop_custom_background_cb()
	{
		$data_background = fastshop_get_option( 'background_page', '' );
		$background      = $data_background[ 'image' ];
		$color           = $data_background[ 'color' ];
		$repeat          = $data_background[ 'repeat' ];
		$position        = $data_background[ 'position' ];
		$attachment      = $data_background[ 'attachment' ];
		$size            = $data_background[ 'size' ];

		/* ENABLE PAGE OPTIONS */
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
			if ( isset( $meta_data[ 'bg_background_page' ] ) ) {
				$page_background = $meta_data[ 'bg_background_page' ];
				$background      = $page_background[ 'image' ];
				$color           = $page_background[ 'color' ];
				$repeat          = $page_background[ 'repeat' ];
				$position        = $page_background[ 'position' ];
				$attachment      = $page_background[ 'attachment' ];
				$size            = $page_background[ 'size' ];
			}
		}
		// A default has to be specified in style.css. It will not be printed here.

		if ( is_page() && !$background || !$color ) {
			if ( is_customize_preview() ) {
				echo '
            <style type="text/css" id="custom-background-css"></style>';
			}
		} else {
			return;
		}

		$style = $color ? "background-color: $color;" : '';

		if ( $background != '' ) {
			$image = ' background-image: url("' . esc_url_raw( $background ) . '");';

			// Background Position.

			$position = " background-position: $position;";

			// Background Size.

			$size = " background-size: $size;";

			// Background Repeat.

			$repeat = " background-repeat: $repeat;";

			// Background Scroll.

			$attachment = " background-attachment: $attachment;";

			$style .= $image . $position . $size . $repeat . $attachment;
		}
		?>
        <style type="text/css" id="custom-background-css">
            body.fastshop-custom-background {
            <?php echo trim( $style ); ?>
            }
        </style>
		<?php
	}
}