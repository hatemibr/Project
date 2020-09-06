<?php
$args = array(
	'container'     => 'div',
	'before'        => '',
	'after'         => '<a href="' . wp_get_referer() . '">' . esc_html__( 'Return To Previous Page', 'fastshop' ) . '</a>',
	'show_on_front' => true,
	'network'       => false,
	'show_title'    => true,
	'show_browse'   => false,
	'post_taxonomy' => array(),
	'echo'          => true,
);
if ( !is_front_page() ) {
	fastshop_breadcrumb( $args );
}