<?php
/*
 Name:  Header style 12
 */
?>
<header id="header" class="header style12 header-cart1">
    <div class="container">
        <div class="top-header">
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
    <div class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="header-search-box">
					<?php fastshop_search_form(); ?>
                </div>
                <div class="logo">
					<?php fastshop_get_logo(); ?>
                </div>
                <div class="header-control">
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
                                    </a>
									<span class="text"><?php echo esc_html__( 'Wishlist', 'fastshop' ); ?></span>
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
    <div class="header-nav header-bg-dark">
        <div class="container">
            <div class="header-nav-inner">
				<?php get_template_part( 'template-parts/header', 'vertical-menu' ); ?>
                <div class="box-header-nav">
                    <div class="main-menu-wapper"></div>
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