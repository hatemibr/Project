<?php
/*
 Name:  Header style 21
 */
?>
<header id="header" class="header style13 style21 header-cart4">
    <div class="main-header box-template">
        <div class="header-content">
            <div class="top-bar-menu left">
                <div class="menu-item logo">
                    <?php fastshop_get_logo(); ?>
                </div>
                <div class="menu-item header-nav header-sticky">
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
            <div class="top-bar-menu right">
                <ul class="menu-item fastshop-nav top-bar-menu menu1">
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
                </ul>
                <div class="menu-item header-search-box">
                    <?php fastshop_search_form(); ?>
                </div>
                <?php
                wp_nav_menu( array(
                        'menu'            => 'top_right_menu',
                        'theme_location'  => 'top_right_menu',
                        'depth'           => 2,
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'menu-item fastshop-nav top-bar-menu menu2',
                        'fallback_cb'     => 'Fastshop_navwalker::fallback',
                        'walker'          => new Fastshop_navwalker(),
                    )
                );
                ?>
                <li class="menu-item">
                    <a class="menu-bar desktop-navigation" href="javascript:void(0);">
                        <i class="flaticon-05menu"></i>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-bar mobile-navigation" href="javascript:void(0);">
                        <i class="flaticon-05menu"></i>
                    </a>
                </li>
            </div>
        </div>
    </div>
</header>