<?php get_header(); ?>
<?php
/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_page_side_options', true );
/*Default page layout*/
$fastshop_page_extra_class = isset( $data_meta[ 'page_extra_class' ] ) ? $data_meta[ 'page_extra_class' ] : '';
$fastshop_page_layout      = isset( $data_meta[ 'sidebar_page_layout' ] ) ? $data_meta[ 'sidebar_page_layout' ] : 'left';
$fastshop_page_sidebar     = isset( $data_meta[ 'page_sidebar' ] ) ? $data_meta[ 'page_sidebar' ] : 'widget-area';

/*Main container class*/
$fastshop_main_container_class   = array();
$fastshop_main_container_class[] = $fastshop_page_extra_class;
$fastshop_main_container_class[] = 'main-container';
if ( $fastshop_page_layout == 'full' ) {
	$fastshop_main_container_class[] = 'no-sidebar';
} else {
	$fastshop_main_container_class[] = $fastshop_page_layout . '-sidebar';
}
$fastshop_main_content_class   = array();
$fastshop_main_content_class[] = 'main-content';
if ( $fastshop_page_layout == 'full' ) {
	$fastshop_main_content_class[] = 'col-sm-12';
} else {
	$fastshop_main_content_class[] = 'col-lg-9 col-md-8';
}
$fastshop_slidebar_class   = array();
$fastshop_slidebar_class[] = 'sidebar';
if ( $fastshop_page_layout != 'full' ) {
	$fastshop_slidebar_class[] = 'col-lg-3 col-md-4';
}
?>
    <main class="site-main <?php echo esc_attr( implode( ' ', $fastshop_main_container_class ) ); ?>">
        <div class="container">
			<?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
			<?php get_template_part( 'template-parts/page', 'banner' ); ?>
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $fastshop_main_content_class ) ); ?>">
                    <div class="page-title">
						<?php the_title(); ?>
                    </div>
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'fastshop' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'fastshop' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									)
								);
								?>
                            </div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
							<?php
						}
					}
					?>
                </div>
				<?php if ( $fastshop_page_layout != "full" ): ?>
					<?php if ( is_active_sidebar( $fastshop_page_sidebar ) ) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr( implode( ' ', $fastshop_slidebar_class ) ); ?>">
							<?php dynamic_sidebar( $fastshop_page_sidebar ); ?>
                        </div><!-- .widget-area -->
					<?php endif; ?>
				<?php endif; ?>
            </div>
        </div>
    </main>
<?php get_footer(); ?>