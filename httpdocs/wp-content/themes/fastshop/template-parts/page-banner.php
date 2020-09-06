<?php
/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

$fastshop_metabox_enable_banner  = isset( $data_meta[ 'fastshop_metabox_enable_banner' ] ) ? $data_meta[ 'fastshop_metabox_enable_banner' ] : '';
$fastshop_page_header_background = isset( $data_meta[ 'bg_banner_page' ] ) ? $data_meta[ 'bg_banner_page' ] : '';
$fastshop_page_heading_height    = isset( $data_meta[ 'height_banner' ] ) ? $data_meta[ 'height_banner' ] : '';
$fastshop_page_margin_top        = isset( $data_meta[ 'page_margin_top' ] ) ? $data_meta[ 'page_margin_top' ] : '';
$fastshop_page_margin_bottom     = isset( $data_meta[ 'page_margin_bottom' ] ) ? $data_meta[ 'page_margin_bottom' ] : '';
$css                             = '';

if ( $fastshop_metabox_enable_banner != 1 ) {
	return;
}
if ( $fastshop_page_header_background && $fastshop_page_header_background != "" ) {
	$css .= 'background-image:  url("' . esc_url( $fastshop_page_header_background[ 'image' ] ) . '");';
	$css .= 'background-repeat: ' . esc_attr( $fastshop_page_header_background[ 'repeat' ] ) . ';';
	$css .= 'background-position:   ' . esc_attr( $fastshop_page_header_background[ 'position' ] ) . ';';
	$css .= 'background-attachment: ' . esc_attr( $fastshop_page_header_background[ 'attachment' ] ) . ';';
	$css .= 'background-size:   ' . esc_attr( $fastshop_page_header_background[ 'size' ] ) . ';';
	$css .= 'background-color:  ' . esc_attr( $fastshop_page_header_background[ 'color' ] ) . ';';
}
if ( $fastshop_page_heading_height && $fastshop_page_heading_height != "" ) {
	$css .= 'min-height:' . $fastshop_page_heading_height . 'px;';
}
if ( $fastshop_page_margin_top && $fastshop_page_margin_top != "" ) {
	$css .= 'margin-top:' . $fastshop_page_margin_top . 'px;';
}
if ( $fastshop_page_margin_bottom && $fastshop_page_margin_bottom != "" ) {
	$css .= 'margin-bottom:' . $fastshop_page_margin_bottom . 'px;';
}

?>
<!-- Banner page -->
<div class="banner-page banner-blog1" style='<?php echo esc_attr( $css ); ?>'>
    <div class="content-banner">
    </div>
</div>
<!-- /Banner page -->