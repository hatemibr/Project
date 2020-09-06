<?php
/*
 Name:  Header style 08
 */
?>
<?php
$item_add             = fastshop_get_option( 'group_menu_header' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$item_add = $data_meta[ 'metabox_group_menu_header' ];
}
?>
<header id="header" class="header style8">
    <div class="top-header header-bg-dark">
        <div class="container">
            <ul class="fastshop-nav top-bar-menu left">
				<?php
				get_template_part( 'template-parts/header', 'language' );
				get_template_part( 'template-parts/header', 'currency' );
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
					<?php if ( !empty( $item_add ) ) : ?>
                        <ul class="contact-box custom-menu">
							<?php foreach ( $item_add as $value ) : ?>
                                <li class="contact-item">
                                    <a href="<?php echo esc_url( $value[ 'link_item' ] ); ?>">
										<?php if ( $value[ 'icon_item' ] ): ?>
                                            <i class="<?php echo esc_attr( $value[ 'icon_item' ] ); ?>"></i>
										<?php endif; ?>
										<?php echo esc_html( $value[ 'title_item' ] ); ?>
                                    </a>
                                </li>
							<?php endforeach; ?>
                        </ul>
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
    <div class="header-nav">
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