<?php
$blog_full_content   = fastshop_get_option( 'blog_full_content' );
$using_placeholder   = fastshop_get_option( 'using_placeholder' );
$fastshop_blog_lazy  = fastshop_get_option( 'fastshop_theme_lazy_load' );
$sidebar_blog_layout = fastshop_get_option( 'sidebar_blog_layout', 'left' );

$lazy_check = $fastshop_blog_lazy == 1 ? true : false;
?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'post-item' ); ?>>
			<?php if ( !is_search() ) : ?>
				<?php if ( $using_placeholder == 1 && !has_post_thumbnail() ||
					$sidebar_blog_layout == 'full' && !has_post_thumbnail() ) : ?>
				<?php else: ?>
                    <div class="post-thumb">
						<?php if ( $sidebar_blog_layout == 'full' ) : ?>
							<?php $url_image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ); ?>
                            <a class="thumb-link" href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url( $url_image ); ?>"
                                     alt="<?php echo get_the_title(); ?>">
                            </a>
						<?php else: ?>
							<?php $image = fastshop_resize_image( get_post_thumbnail_id(), null, 870, 527, false, $lazy_check ); ?>
                            <a class="thumb-link" href="<?php the_permalink(); ?>">
								<?php echo force_balance_tags( $image[ 'img' ] ); ?>
                            </a>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
			<?php endif; ?>
            <div class="post-info">
                <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <ul class="post-meta">
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
				<?php if ( $blog_full_content == 1 ) : ?>
                    <div class="post-cat">
                        <div class="content-cat">
							<?php echo esc_html__( 'Categories:', 'fastshop' ); ?>
							<?php the_category( ', ', '' ); ?>
                        </div>
                        <div class="content-cat">
							<?php the_tags(); ?>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="post-content">
					<?php if ( is_singular( 'testimonial' ) ) : ?>
						<?php the_excerpt(); ?>
					<?php else: ?>
						<?php if ( $blog_full_content == 1 ) : ?>
							<?php
							/* translators: %s: Name of current post */
							the_content( sprintf(
									__( 'Continue reading %s', 'fastshop' ),
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
						<?php else : ?>
							<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 49, esc_html__( '...', 'fastshop' ) ); ?>
						<?php endif; ?>
					<?php endif; ?>
                </div>
                <div class="post-readmore">
					<?php if ( is_singular( 'testimonial' ) ) : ?>
                        <a class="read-more" href="<?php the_permalink(); ?>">
							<?php echo esc_html__( 'Read more', 'fastshop' ); ?>
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
					<?php else: ?>
						<?php if ( $blog_full_content != 1 ) : ?>
                            <a class="read-more" href="<?php the_permalink(); ?>">
								<?php echo esc_html__( 'Read more', 'fastshop' ); ?>
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
						<?php endif; ?>
                        <span class="comment-count">
                                <i class="fa fa-comment"></i>
							<?php comments_number(
								esc_html__( '0 Comment', 'fastshop' ),
								esc_html__( '1 Comment', 'fastshop' ),
								esc_html__( '% Comments', 'fastshop' )
							);
							?>
                            </span>
					<?php endif; ?>
                </div>
            </div>
        </article>
	<?php endwhile; ?>
	<?php fastshop_paging_nav(); ?>
	<?php wp_reset_postdata(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>