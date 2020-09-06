<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fastshop
 */
?>
<?php get_header(); ?>
<?php

/* Blog Layout */
$fastshop_blog_layout = fastshop_get_option( 'sidebar_blog_layout', 'left' );
$fastshop_blog_style  = fastshop_get_option( 'fastshop_blog_style', 'list' );

if ( is_single() ) {
	/*Single post layout*/
	$fastshop_blog_layout = fastshop_get_option( 'sidebar_single_post_position', 'left' );
}

$fastshop_container_class = array( 'main-container' );

if ( $fastshop_blog_layout == 'full' ) {
	$fastshop_container_class[] = 'no-sidebar';
} else {
	$fastshop_container_class[] = $fastshop_blog_layout . '-sidebar';
}
$fastshop_content_class   = array();
$fastshop_content_class[] = 'main-content';
if ( $fastshop_blog_layout == 'full' ) {
	$fastshop_content_class[] = 'col-sm-12';
} else {
	$fastshop_content_class[] = 'col-lg-9 col-md-8';
}

$fastshop_slidebar_class   = array();
$fastshop_slidebar_class[] = 'sidebar';
if ( $fastshop_blog_layout != 'full' ) {
	$fastshop_slidebar_class[] = 'col-lg-3 col-md-4';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $fastshop_container_class ) ); ?>">
    <div class="container">
		<?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
        <div class="row">
            <div class="<?php echo esc_attr( implode( ' ', $fastshop_content_class ) ); ?>">
				<?php if ( is_search() ) : ?>
					<?php if ( have_posts() ) : ?>
                        <header class="page-header">
                            <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'fastshop' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                        </header><!-- .page-header -->
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( is_single() ) : ?>
					<?php
					while ( have_posts() ): the_post();
						fastshop_set_post_views( get_the_ID() );
						get_template_part( 'templates/blog/blog', 'single' );

						/*If comments are open or we have at least one comment, load up the comment template.*/
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
					?>
					<?php wp_reset_postdata(); ?>
				<?php else: ?>
					<?php get_template_part( 'templates/blog/blog', $fastshop_blog_style ); ?>
				<?php endif; ?>
            </div>
			<?php if ( $fastshop_blog_layout != "full" ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $fastshop_slidebar_class ) ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>