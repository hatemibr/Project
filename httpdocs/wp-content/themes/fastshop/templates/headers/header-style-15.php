<?php
/*
 Name:  Header style 15
 */
?>
<header id="header" class="header style15">
	<div class="main-header">
		<div class="container">
			<div class="header-content">
				<div class="logo">
					<?php fastshop_get_logo(); ?>
				</div>
				<div class="top-bar-menu right header-control">
                    <div class="menu-item header-search-box">
                        <?php fastshop_search_form(); ?>
                    </div>
                    <div class="menu-item">
                        <?php if ( class_exists( 'WooCommerce' ) ): ?>
                            <div class="block-minicart fastshop-mini-cart">
                                <?php get_template_part( 'template-parts/header-mini', 'cart' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
					<a class="menu-item menu-bar mobile-navigation" href="javascript:void(0);">
						<i class="flaticon-05menu"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="header-nav header-sticky">
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