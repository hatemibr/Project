<?php
$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$header_style = $data_meta[ 'fastshop_metabox_used_header' ];
}

if ( $header_style == 'style-03' || $header_style == 'style-04' || $header_style == 'style-13' || $header_style == 'style-21' ) {
	$class_contain = 'box-template';
} else {
	$class_contain = 'container';
}
$class_drop_1 = '';
$class_drop_2 = '';
if (
    $header_style == 'style-01' ||
    $header_style == 'style-02' ||
    $header_style == 'style-04' ||
    $header_style == 'style-09' ||
    $header_style == 'style-11' ||
    $header_style == 'style-12' ||
    $header_style == 'style-14' ||
    $header_style == 'style-22'
) {
    $class_drop_1 = 'header-bg-dark';
}
if (
    $header_style == 'style-16'
) {
    $class_drop_2 = 'header-bg-dark';
}
?>
<div id="header-sticky-menu" class="header-sticky-menu <?php echo esc_attr( $header_style ); ?> <?php echo esc_attr( $class_drop_1 ); ?>">
    <div class="<?php echo esc_attr( $class_contain ); ?>">
        <div class="header-sticky-inner <?php echo esc_attr( $class_drop_2 ); ?>">
            <div class="main-menu-wapper"></div>
            <div class="header-nav-inner">
                <?php get_template_part( 'template-parts/header', 'vertical-menu' ); ?>
                <div class="box-header-nav">
                    <?php
                    wp_nav_menu( array(
                            'menu'            => 'primary',
                            'theme_location'  => 'primary',
                            'depth'           => 3,
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'fastshop-nav main-menu center',
                            'fallback_cb'     => 'Fastshop_navwalker::fallback',
                            'walker'          => new Fastshop_navwalker(),
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="sticky-cart">
                <?php if ( defined( 'YITH_WCWL' ) ) : ?>
                    <?php
                    $yith_wcwl_wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
                    $wishlist_url               = get_page_link( $yith_wcwl_wishlist_page_id );
                    ?>
                    <?php if ( $wishlist_url != '' ) : ?>
                        <div class="block-wishlist">
                            <div class="wishlist-dropdown">
                                <a class="wishlist-icon woo-wishlist-link"
                                   href="<?php echo esc_url( $wishlist_url ); ?>">
                                    <i class="flaticon-03wishlist"></i>
                                    <span class="count"><?php echo YITH_WCWL()->count_products(); ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ( class_exists( 'WooCommerce' ) ): ?>
                    <div class="block-minicart fastshop-mini-cart">
                        <?php get_template_part( 'template-parts/header-mini', 'cart' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>