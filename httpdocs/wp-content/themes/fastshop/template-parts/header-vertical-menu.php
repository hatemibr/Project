<?php
/* MAIN THEME OPTIONS */
$enable_vertical_menu     = fastshop_get_option( 'enable_vertical_menu', '' );
$click_open_vertical_menu = fastshop_get_option( 'click_open_vertical_menu', 'yes' );
$vertical_item_visible    = fastshop_get_option( 'vertical_item_visible', 10 );
$header_style             = fastshop_get_option( 'fastshop_used_header', 'style-01' );
/* META BOX THEME OPTIONS */
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
	$enable_vertical_menu     = $meta_data[ 'metabox_enable_vertical_menu' ];
	$click_open_vertical_menu = $meta_data[ 'metabox_click_open_vertical_menu' ];
	$vertical_item_visible    = $meta_data[ 'metabox_vertical_item_visible' ];
	$header_style             = $meta_data[ 'fastshop_metabox_used_header' ];
}
?>
<?php
if (
	$header_style == 'style-02' ||
	$header_style == 'style-08' ||
	$header_style == 'style-09' ||
	$header_style == 'style-16' ||
	$header_style == 'style-19' ||
	$header_style == 'style-20' ||
	$header_style == 'style-22'
) :
?>
<?php if ( $enable_vertical_menu == 'yes' ): ?>
	<?php
	$block_vertical_class = array( 'vertical-wapper block-nav-category' );

	/* MAIN THEME OPTIONS */
	$vertical_menu_title             = fastshop_get_option( 'vertical_menu_title', 'Shop By Category' );
	$vertical_menu_button_all_text   = fastshop_get_option( 'vertical_menu_button_all_text', 'All categoryes' );
	$vertical_menu_button_close_text = fastshop_get_option( 'vertical_menu_button_close_text', 'Close' );
	/* META BOX THEME OPTIONS */
	if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
		$vertical_menu_title             = $meta_data[ 'metabox_vertical_menu_title' ];
		$vertical_menu_button_all_text   = $meta_data[ 'metabox_vertical_menu_button_all_text' ];
		$vertical_menu_button_close_text = $meta_data[ 'metabox_vertical_menu_button_close_text' ];
	}

	if ( $enable_vertical_menu == 'yes' ) {
		$block_vertical_class[] = 'has-vertical-menu';
	}
	if ( $click_open_vertical_menu == 'yes' ) {
		$block_vertical_class[] = 'alway-open';
	}
	?>
    <!-- block category -->
    <div data-items="<?php echo esc_attr( $vertical_item_visible ); ?>"
         class="<?php echo esc_attr( implode( ' ', $block_vertical_class ) ); ?>">
        <div class="block-title">
            <span class="icon-title"><i class="fa fa-bars" aria-hidden="true"></i></span>
            <span class="text-title"><?php echo esc_html( $vertical_menu_title ); ?></span>
        </div>
        <div class="block-content verticalmenu-content">
			<?php
			wp_nav_menu( array(
					'menu'            => 'vertical_menu',
					'theme_location'  => 'vertical_menu',
					'depth'           => 4,
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'fastshop-nav vertical-menu',
					'fallback_cb'     => 'Fastshop_navwalker::fallback',
					'walker'          => new Fastshop_navwalker(),
				)
			);
			?>
            <div class="view-all-category">
                <a href="javascript:void(0)"
                   data-closetext="<?php echo esc_attr( $vertical_menu_button_close_text ); ?>"
                   data-alltext="<?php echo esc_attr( $vertical_menu_button_all_text ) ?>"
                   class="btn-view-all open-cate"><?php echo esc_html( $vertical_menu_button_all_text ) ?></a>
            </div>
        </div>
    </div><!-- block category -->
<?php endif; ?>
<?php endif; ?>