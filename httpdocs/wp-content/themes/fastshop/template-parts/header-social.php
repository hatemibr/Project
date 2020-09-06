<?php
$all_socials    = fastshop_get_option( 'user_all_social' );
$get_all_social = fastshop_get_option( 'get_all_social' );

$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
    $header_style = $data_meta[ 'fastshop_metabox_used_header' ];
}
?>
<?php
$class_add_1 = '';
if (
    $header_style == 'style-07' ||
    $header_style == 'style-14'
) {
    $class_add_1 = 'style1';
}
?>
<li class="menu-item social-header <?php echo esc_attr( $class_add_1 ); ?>">
	<?php if ( !empty( $get_all_social ) ) : ?>
        <ul class="social-list">
			<?php foreach ( $get_all_social as $value ) : ?>
				<?php $array_social = $all_socials[ $value ]; ?>
                <li>
                    <a href="<?php echo esc_url( $array_social[ 'link_social' ] ) ?>" target="_blank">
                        <span class="<?php echo esc_attr( $array_social[ 'icon_social' ] ); ?>"></span>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
	<?php endif; ?>
</li>