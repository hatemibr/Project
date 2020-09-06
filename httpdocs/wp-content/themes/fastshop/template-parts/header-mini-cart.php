<?php
global $woocommerce;
$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$header_style = $data_meta[ 'fastshop_metabox_used_header' ];
}
?>
    <div class="shopcart-dropdown">
        <a class="shopcart-icon" href="<?php echo wc_get_cart_url(); ?>">
            <i class="flaticon-04shopcart"></i>
            <span class="count"><?php echo WC()->cart->cart_contents_count ?></span>
        </a>

        <span class="shopcart-total cart-style1"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>

        <span class="item cart-style2"><?php printf( esc_html__( '%1$s item(s) - ', 'fastshop' ), WC()->cart->cart_contents_count ); ?></span>
        <span class="shopcart-total cart-style2"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>

        <span class="item cart-style3"><?php printf( esc_html__( '%1$s item(s)', 'fastshop' ), WC()->cart->cart_contents_count ); ?></span>

        <span class="item cart-style4"><?php echo esc_html__( 'item(s)', 'fastshop' ); ?></span>

        <div class="cart-style5">
            <span class="item"><?php echo esc_html__( 'Your Cart', 'fastshop' ); ?></span>
            <span class="shopcart-total"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
        </div>
        <div class="cart-style6">
            <span class="item"><?php printf( esc_html__( '%1$s item(s) - ', 'fastshop' ), WC()->cart->cart_contents_count ); ?></span>
            <span class="shopcart-total"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
            <span class="fa fa-arrow-right cart-icon"></span>
        </div>
    </div>
<?php if ( !WC()->cart->is_empty() ) : ?>
    <div class="shopcart-description">
        <div class="content-wrap">
            <div class="subtitle">
				<?php printf( esc_html__( 'You have %1$s item(s) in your cart', 'fastshop' ), WC()->cart->cart_contents_count ); ?>
            </div>
            <ol class="minicart-items">
				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ): ?>
					<?php $bag_product = apply_filters( 'woocommerce_cart_item_product', $cart_item[ 'data' ], $cart_item, $cart_item_key ); ?>
					<?php $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item[ 'product_id' ], $cart_item, $cart_item_key ); ?>

					<?php if ( $bag_product && $bag_product->exists() && $cart_item[ 'quantity' ] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ): ?>

						<?php
						$product_name  = apply_filters( 'woocommerce_cart_item_name', $bag_product->get_title(), $cart_item, $cart_item_key );
						$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $bag_product->get_image( 'shop_thumbnail' ), $cart_item, $cart_item_key );
						$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $bag_product ), $cart_item, $cart_item_key );
						?>
                        <li class="product-cart mini_cart_item">
                            <a class="product-media"
                               href="<?php echo esc_url( get_permalink( $cart_item[ 'product_id' ] ) ) ?>">
								<?php echo htmlspecialchars_decode( $thumbnail ); ?>
                            </a>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="<?php echo esc_url( get_permalink( $cart_item[ 'product_id' ] ) ) ?>"><?php echo esc_html( $product_name ); ?></a>
                                </h3>
                                <span class="product-quantity"><?php printf( esc_html__( 'Quantity: %1$s', 'fastshop' ), $cart_item[ 'quantity' ] ); ?></span>
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="qty product-price">' . sprintf( '%s', $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                            </div>
                            <div class="product-remove">
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times"></i></a>',
									esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'fastshop' ),
									esc_attr( $product_id ),
									esc_attr( $bag_product->get_sku() )
								), $cart_item_key
								);
								?>
                            </div>
                        </li>
					<?php endif; ?>
				<?php endforeach; ?>
            </ol>
            <div class="subtotal">
                <span class="total-title"><?php echo esc_html__( 'Total: ', 'fastshop' ); ?></span>
                <span class="total-price"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
            </div>
			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
            <div class="actions">
                <a class="button button-viewcart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                    <span><?php esc_html_e( 'View Cart', 'fastshop' ); ?></span>
                </a>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                   class="button button-checkout"><span><?php echo esc_html__( 'Checkout', 'fastshop' ); ?></span></a>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="shopcart-description shopcart-empty">
        <p class="empty-text" style="color: #000">
			<?php echo esc_html__( 'You have no item(s) in your cart', 'fastshop' ) ?>
        </p>
    </div>
<?php endif; ?>