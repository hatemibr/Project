<?php
$fastshop_data_cols = fastshop_get_option( 'data_cols', '3' );
$fastshop_blog_lazy = fastshop_get_option( 'fastshop_theme_lazy_load' );
$lazy_check         = $fastshop_blog_lazy == 1 ? true : false;
$post_class         = array( 'post-item portfolio-item' );
?>
<?php if ( have_posts() ) : ?>
    <div class="fastshop-portfolio type-masonry">
        <div class="portfolio-grid" data-layoutmode="masonry"
             data-cols="<?php echo esc_attr( $fastshop_data_cols ); ?>">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				$meta_option = get_post_meta( get_the_ID(), '_custom_post_options', true );
				$w           = 370;
				$h           = 226;

				if ( isset( $meta_option[ 'size_post_layout' ] ) ) {
					if ( $meta_option[ 'size_post_layout' ] == '1' ) {
						$w = 370;
						$h = 226;
					} elseif ( $meta_option[ 'size_post_layout' ] == '2' ) {
						$w = 370;
						$h = 420;
					} else {
						$w = 370;
						$h = 530;
					}
				}
				$image_thumb = fastshop_resize_image( get_post_thumbnail_id(), null, $w, $h, true, $lazy_check );
				?>
                <article <?php post_class( $post_class ); ?>>
                    <div class="post-inner">
                        <div class="post-thumb">
                            <a class="thumb-link" href="<?php the_permalink(); ?>">
								<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                            </a>
                        </div>
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
                            </ul>
                            <div class="post-content">
								<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 20, esc_html__( '...', 'fastshop' ) ); ?>
                            </div>
                            <div class="post-readmore">
                                <a class="read-more" href="<?php the_permalink(); ?>">
									<?php echo esc_html__( 'Read more', 'fastshop' ); ?>
                                    <i class="fa fa-arrow-circle-right"></i>
                                </a>
                                <span class="comment-count">
                                <i class="fa fa-comment"></i>
									<?php comments_number(
										esc_html__( '0 Comment', 'fastshop' ),
										esc_html__( '1 Comment', 'fastshop' ),
										esc_html__( '% Comments', 'fastshop' )
									);
									?>
                            </span>
                            </div>
                        </div>
                    </div>
                </article>
			<?php endwhile; ?>
        </div>
		<?php fastshop_paging_nav(); ?>
		<?php wp_reset_postdata(); ?>
    </div>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>