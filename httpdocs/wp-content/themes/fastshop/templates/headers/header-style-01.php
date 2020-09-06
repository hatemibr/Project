<?php
/*
 Name:  Header style 01
 */
?>
<?php
$email                = fastshop_get_option( 'header_email', '' );
$phone                = fastshop_get_option( 'header_support', '' );
$icon                 = fastshop_get_option( 'icon_header', '' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$email = $data_meta[ 'metabox_header_email' ];
	$phone = $data_meta[ 'metabox_header_support' ];
	$icon  = $data_meta[ 'metabox_icon_header' ];
}
?>
<header id="header" class="header style1 header-cart1">
    <div class="top-header header-bg-dark">
        <div class="container">
            <ul class="fastshop-nav top-bar-menu left">
				<?php
				get_template_part( 'template-parts/header', 'language' );
				get_template_part( 'template-parts/header', 'currency' );
				get_template_part( 'template-parts/header', 'social' );
				?>
            </ul>
			<?php
			wp_nav_menu( array(
					'menu'            => 'top_right_menu',
					'theme_location'  => 'top_right_menu',
					'depth'           => 2,
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'fastshop-nav top-bar-menu right',
					'fallback_cb'     => 'Fastshop_navwalker::fallback',
					'walker'          => new Fastshop_navwalker(),
				)
			);
			?>
        </div>
    </div>
    <div class="main-header header-bg-dark">
        <div class="container">
            <div class="header-content">
                <div class="logo">
					<?php fastshop_get_logo(); ?>
                </div>
                <div class="header-search-box">
					<?php fastshop_search_form(); ?>
                </div>
                <div class="header-control">
					<?php if ( $phone || $email || $icon ) : ?>
                        <div class="support-box">
							<?php if ( $icon ) : ?>
                                <figure class="icon">
                                    <img src="<?php echo esc_url( wp_get_attachment_image_url( $icon ) ); ?>"
                                         alt="<?php the_title(); ?>">
                                </figure>
							<?php endif; ?>
                            <div class="content">
                                <span class="phone"><?php echo esc_html( $phone ); ?></span>
                                <span class="email"><?php echo esc_html( $email ); ?></span>
                            </div>
                        </div>
					<?php endif; ?>
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
                    <a class="menu-bar mobile-navigation" href="javascript:void(0);">
                        <i class="flaticon-05menu"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-nav header-sticky header-bg-dark">
        <div class="container">
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
							'menu_class'      => 'clone-main-menu fastshop-nav main-menu center',
							'fallback_cb'     => 'Fastshop_navwalker::fallback',
							'walker'          => new Fastshop_navwalker(),
						)
					);
					?>
                </div>
            </div>
        </div>
    </div>
</header>