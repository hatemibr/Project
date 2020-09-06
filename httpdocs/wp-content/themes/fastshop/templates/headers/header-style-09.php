<?php
/*
 Name:  Header style 09
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
<header id="header" class="header style9 header-cart4">
    <div class="top-header">
        <div class="container">
            <ul class="fastshop-nav top-bar-menu left">
				<?php if ( !empty( $item_add ) ) : ?>
					<?php foreach ( $item_add as $value ) : ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url( $value[ 'link_item' ] ); ?>">
								<?php if ( $value[ 'icon_item' ] ): ?>
                                    <span class="icon <?php echo esc_attr( $value[ 'icon_item' ] ); ?>"></span>
								<?php endif; ?>
								<?php echo esc_html( $value[ 'title_item' ] ); ?>
                            </a>
                        </li>
					<?php endforeach; ?>
				<?php endif; ?>
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
                <div class="logo">
					<?php fastshop_get_logo(); ?>
                </div>
                <ul class="top-bar-menu right">
                    <li class="menu-item header-search-box">
                        <?php fastshop_search_form(); ?>
                    </li>
                    <?php
                    get_template_part( 'template-parts/header', 'language' );
                    get_template_part( 'template-parts/header', 'currency' );
                    ?>
                    <li class="menu-item">
                        <?php if ( class_exists( 'WooCommerce' ) ): ?>
                            <div class="block-minicart fastshop-mini-cart">
                                <?php get_template_part( 'template-parts/header-mini', 'cart' ); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                    <li class="menu-item">
                        <a class="menu-bar mobile-navigation" href="javascript:void(0);">
                            <i class="flaticon-05menu"></i>
                        </a>
                    </li>
                </ul>
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