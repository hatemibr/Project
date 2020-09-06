<?php
$using_placeholder   = fastshop_get_option( 'using_placeholder' );
$fastshop_blog_lazy  = fastshop_get_option( 'fastshop_theme_lazy_load' );
$sidebar_blog_layout = fastshop_get_option( 'sidebar_blog_layout', '' );

$lazy_check = $fastshop_blog_lazy == 1 ? true : false;
?>
<article <?php post_class( 'post-item post-single' ); ?>>
	<?php if ( $using_placeholder == 1 && !has_post_thumbnail() ||
		$sidebar_blog_layout == 'full' && !has_post_thumbnail() ) : ?>
		<?php return false; ?>
	<?php else: ?>
        <div class="post-thumb">
			<?php if ( $sidebar_blog_layout == 'full' ) : ?>
				<?php $url_image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ); ?>
                <img src="<?php echo esc_url( $url_image ); ?>" alt="<?php echo get_the_title(); ?>">
			<?php else: ?>
				<?php $image = fastshop_resize_image( get_post_thumbnail_id(), null, 870, 527, false, $lazy_check ); ?>
				<?php echo force_balance_tags( $image[ 'img' ] ); ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
    <div class="post-info">
        <h2 class="post-title"><?php the_title(); ?></h2>
        <ul class="post-meta meta1">
            <li class="date"><?php echo get_the_date(); ?></li>
            <li class="author">
                <span><?php echo esc_html__( 'posted by: ', 'fastshop' ) ?></span>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
					<?php the_author() ?>
                </a>
            </li>
			<?php
			if ( is_sticky() ) {
				printf( '<li class="sticky-post"><i class="fa fa-flag"></i>%s</li>', esc_html__( 'Sticky', 'fastshop' ) );
			}
			?>
        </ul>
        <div class="post-cat">
            <div class="content-cat">
				<?php echo esc_html__( 'Categories:', 'fastshop' ); ?>
				<?php the_category( ', ', '' ); ?>
            </div>
            <div class="content-cat">
				<?php the_tags(); ?>
            </div>
        </div>
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
					esc_html__( 'Continue reading %s', 'fastshop' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				)
			);

			wp_link_pages( array(
				'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'fastshop' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			)
			);
			?>
        </div>
    </div>
</article>