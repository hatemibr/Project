<?php
if ( !class_exists( 'WooCommerce' ) ) {
    return false;
}
$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
    $header_style = $data_meta[ 'fastshop_metabox_used_header' ];
}

$myaccount_page_id                         = get_option( 'woocommerce_myaccount_page_id' );
$myaccount_link                            = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
$woocommerce_enable_myaccount_registration = get_option( 'woocommerce_enable_myaccount_registration' );

$logout_url = "#";
if ( $myaccount_page_id ) {
	if ( function_exists( 'woocommerce_get_page_id' ) ) {
		if ( function_exists( 'wc_get_page_id' ) ) {
			$logout_url = wp_logout_url( get_permalink( wc_get_page_id( 'shop' ) ) );
		} else {
			$logout_url = wp_logout_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) );
		}

		if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
			$logout_url = str_replace( 'http:', 'https:', $logout_url );
		}
	}

}
?>
<?php if ( is_user_logged_in() ): ?>
	<?php
	$currentUser = wp_get_current_user();
	?>
    <li class="menu-item menu-item-has-children dropdown switcher-login">
        <a data-toggle="dropdown" class="dropdown-toggle" href="<?php echo esc_url( $myaccount_link ); ?>">
            <?php if ( $header_style == 'style-10' ) : ?>
                <span class="icon fa fa-user" aria-hidden="true"></span>
            <?php endif; ?>
            <?php if ( $header_style == 'style-16' ) : ?>
                <span class="icon flaticon-27arrows" aria-hidden="true"></span>
            <?php endif; ?>
			<?php esc_html_e( 'Hi, ', 'fastshop' );
			echo esc_html($currentUser->display_name); ?>
        </a>
		<?php if ( function_exists( 'wc_get_account_menu_items' ) ): ?>
            <ul class="submenu">
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                    <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php else: ?>
            <ul class="submenu dropdown-menu">
                <li>
                    <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php esc_html_e( 'Logout', 'fastshop' ); ?></a>
                </li>
            </ul>
		<?php endif; ?>
    </li>
<?php else: ?>
    <li class="menu-item dropdown switcher-login">
        <a href="<?php echo esc_url( $myaccount_link ); ?>">
            <?php if ( $header_style == 'style-10' ) : ?>
                <span class="icon fa fa-user" aria-hidden="true"></span>
            <?php endif; ?>
            <?php if ( $header_style == 'style-16' ) : ?>
                <span class="icon flaticon-27arrows" aria-hidden="true"></span>
            <?php endif; ?>
			<?php if ( $woocommerce_enable_myaccount_registration == "yes" ) : ?>
				<?php echo esc_html__( 'Register / Sign in', 'fastshop' ); ?>
			<?php else: ?>
				<?php echo esc_html__( 'Login', 'fastshop' ); ?>
			<?php endif; ?>
        </a>
    </li>
<?php endif; ?>