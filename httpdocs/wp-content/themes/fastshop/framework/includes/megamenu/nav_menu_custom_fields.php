<?php
/**
 * Define all custom fields in menu
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
add_action( 'walker_nav_menu_custom_fields', 'fastshop_add_custom_fields', 10, 4 );
function fastshop_add_custom_fields( $item_id, $item, $depth, $args )
{
	if ( isset( $item->item_icon_type ) && $item->item_icon_type ) {
		$item_icon_type = $item->item_icon_type;
	} else {
		$item_icon_type = 'none';
	}
	?>
    <div class="clearfix"></div>
    <div class="cs-taxonomy container-megamenu container-<?php echo esc_attr( $depth ); ?>">
        <p class="item_icon_type">
            <label class="row_label">
                <strong><?php esc_html_e( 'Icon Settings', 'fastshop' ); ?></strong>
            </label><br/>
        </p>
		<?php
		echo cs_add_element(
			array(
				'id'         => "item_icon_type_{$item_id}",
				'name'       => "menu-item-megamenu-item_icon_type[{$item_id}]",
				'type'       => 'select',
				'options'    => array(
					'none'     => esc_html__( 'none', 'fastshop' ),
					'fonticon' => esc_html__( 'Font icon', 'fastshop' ),
					'image'    => esc_html__( 'Image', 'fastshop' ),
				),
				'attributes' => array(
					'data-depend-id' => "item_icon_type_{$item_id}",
				),
			), $item_icon_type
		);
		echo cs_add_element(
			array(
				'id'         => "menu-item-font-icon-{$item_id}",
				'name'       => "menu-item-megamenu-font_icon[{$item_id}]",
				'type'       => 'icon',
				'dependency' => array( "item_icon_type_{$item_id}", '==', 'fonticon' ),
			), $item->font_icon
		);
		echo cs_add_element(
			array(
				'id'         => "menu-item-imgicon-{$item_id}",
				'name'       => "menu-item-megamenu-img_icon[{$item_id}]",
				'type'       => 'image',
				'dependency' => array( "item_icon_type_{$item_id}", '==', 'image' ),
			), $item->img_note
		);
		?>
		<?php if ( 'megamenu' == $item->object ): ?>
            <p class="mega-menu-setting" style="margin: 0 0 15px;">
                <label class="row_label"><strong><?php esc_html_e( 'Mega menu settings', 'fastshop' ); ?></strong></label>
                <br/>
                <label>Menu width
                    <small><i>(Unit px)</i></small>
                </label><br>
                <input type="number" value="<?php echo esc_attr( $item->mega_menu_width ); ?>"
                       name="menu-item-megamenu-mega_menu_width[<?php echo esc_attr( $item_id ); ?>]"
                       id="menu-item-mega_menu_width-<?php echo esc_attr( $item_id ); ?>">
                <br/>
                <label>Menu custom URL</label><br>
                <input class="widefat" type="text" value="<?php echo esc_attr( $item->mega_menu_url ); ?>"
                       name="menu-item-megamenu-mega_menu_url[<?php echo esc_attr( $item_id ); ?>]"
                       id="menu-item-mega_menu_url-<?php echo esc_attr( $item_id ); ?>" placeholder="http://">
            </p>
		<?php endif; ?>
    </div><!-- .container-megamenu -->
<?php }