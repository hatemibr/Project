<?php
$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$header_style = $data_meta[ 'fastshop_metabox_used_header' ];
}

$selected = '';
if ( isset( $_GET[ 'product_cat' ] ) && $_GET[ 'product_cat' ] ) {
	$selected = $_GET[ 'product_cat' ];
}
$args         = array(
	'show_option_none'  => esc_html__( 'All Categories', 'fastshop' ),
	'taxonomy'          => 'product_cat',
	'class'             => 'category-search-option',
	'hide_empty'        => 1,
	'orderby'           => 'name',
	'order'             => "asc",
	'tab_index'         => true,
	'hierarchical'      => true,
	'id'                => rand(),
	'name'              => 'product_cat',
	'value_field'       => 'slug',
	'selected'          => $selected,
	'option_none_value' => '0',
);
$class_drop_1 = '';
$class_drop_2 = '';
if (
    $header_style == 'style-01' ||
    $header_style == 'style-18' ||
    $header_style == 'style-22'
) {
	$class_drop_1 = 'default';
}
if (
	$header_style == 'style-04' ||
	$header_style == 'style-07' ||
	$header_style == 'style-13' ||
	$header_style == 'style-17' ||
	$header_style == 'style-21'
) {
	$class_drop_1 = 'style1 dropdown';
	$class_drop_2 = 'submenu';
}
if (
	$header_style == 'style-03' ||
	$header_style == 'style-05' ||
	$header_style == 'style-06' ||
	$header_style == 'style-08' ||
	$header_style == 'style-09' ||
	$header_style == 'style-10' ||
	$header_style == 'style-11' ||
	$header_style == 'style-12' ||
	$header_style == 'style-14' ||
	$header_style == 'style-15' ||
	$header_style == 'style-19' ||
	$header_style == 'style-20'
) {
	$class_drop_1 = 'style2';
}
?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>"
      class="form-search form-search-width-category <?php echo esc_attr( $class_drop_1 ); ?>">
	<?php if (
		$header_style == 'style-01' ||
		$header_style == 'style-06' ||
		$header_style == 'style-18' ||
		$header_style == 'style-20' ||
		$header_style == 'style-22'
	) : ?>
		<?php if ( class_exists( 'WooCommerce' ) ): ?>
            <input type="hidden" name="post_type" value="product"/>
            <input type="hidden" name="taxonomy" value="product_cat">
            <div class="category">
				<?php wp_dropdown_categories( $args ); ?>
            </div>
		<?php endif; ?>
	<?php endif; ?>
    <div class="form-content <?php echo esc_attr( $class_drop_2 ); ?>">
        <div class="inner">
            <input type="text" class="input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
                   placeholder="<?php
                       if (
                           $header_style == 'style-02' ||
                           $header_style == 'style-16'
                       ) {
                           esc_html_e( 'Search entire store here', 'fastshop' );
                       } elseif (
                           $header_style == 'style-06' ||
                           $header_style == 'style-10' ||
                           $header_style == 'style-11' ||
                           $header_style == 'style-14'
                       ) {
                           esc_html_e( 'Search for products...', 'fastshop' );
                       } elseif (
                           $header_style == 'style-20'
                       ) {
                       } else {
                           esc_html_e( 'Search ...', 'fastshop' );
                       }
                   ?>">
			<?php if (
				$header_style == 'style-04' ||
				$header_style == 'style-07' ||
				$header_style == 'style-13' ||
				$header_style == 'style-17' ||
				$header_style == 'style-21'
			) : ?>
                <button class="btn-search" type="submit"><i class="flaticon-01search"></i></button>
			<?php endif; ?>
        </div>
    </div>
	<?php if (
		$header_style == 'style-08' ||
		$header_style == 'style-09' ||
		$header_style == 'style-11' ||
		$header_style == 'style-12' ||
		$header_style == 'style-05' ||
		$header_style == 'style-19'
	) : ?>
		<?php if ( class_exists( 'WooCommerce' ) ): ?>
            <input type="hidden" name="post_type" value="product"/>
            <input type="hidden" name="taxonomy" value="product_cat">
            <div class="category">
				<?php wp_dropdown_categories( $args ); ?>
            </div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if (
		$header_style == 'style-04' ||
		$header_style == 'style-07' ||
		$header_style == 'style-13' ||
		$header_style == 'style-21'
	) : ?>
        <a class="btn-search-drop" href="javascript:void(0);"><i class="flaticon-01search"></i></a>
    <?php elseif( $header_style == 'style-17' ) : ?>
        <a class="btn-search-drop" href="javascript:void(0);"><i class="flaticon-29search"></i></a>
    <?php elseif( $header_style == 'style-20' ) : ?>
        <button class="btn-search" type="submit"><?php esc_html_e( 'SEARCH', 'fastshop' ); ?></button>
	<?php else: ?>
        <button class="btn-search" type="submit"><i class="flaticon-01search"></i></button>
	<?php endif ?>
</form><!-- block search -->