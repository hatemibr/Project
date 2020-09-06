<?php
$fastshop_data_cols       = fastshop_get_option( 'data_cols', '4' );
$fastshop_data_show_posts = fastshop_get_option( 'data_show_posts', '6' );
$fastshop_categories      = fastshop_get_option( 'blog_categories' );
$fastshop_blog_lazy       = fastshop_get_option( 'fastshop_theme_lazy_load' );
$lazy_check               = $fastshop_blog_lazy == 1 ? true : false;

$post_class = array( 'post-item portfolio-item' );
$args_id    = array();
?>
<?php if ( have_posts() ): ?>
    <div class="fastshop-portfolio type-grid-filter">
        <div class="portfolio_filter">
			<?php if ( !empty( $fastshop_categories ) ) : ?>
				<?php foreach ( $fastshop_categories as $key => $value ) : ?>
					<?php $term = get_category( $value ); ?>
                    <div data-filter=".category-<?php echo esc_attr( $term->slug ); ?>"
						<?php if ( $key == 0 ) : ?>
                            data-active=".category-<?php echo esc_attr( $term->slug ); ?>"
						<?php endif; ?>
                         class="item-filter <?php if ( $key == 0 ): echo esc_attr( 'filter-active' ); endif; ?>">
						<?php echo esc_html( $term->name ); ?>
                    </div>
				<?php endforeach; ?>
			<?php else: ?>
				<?php
				$terms = get_terms( 'category' );
				if ( !empty( $terms ) && !is_wp_error( $terms ) ) :
					foreach ( $terms as $key => $term ) : ?>
                        <div data-filter=".category-<?php echo esc_attr( $term->slug ); ?>"
							<?php if ( $key == 0 ) : ?>
                                data-active=".category-<?php echo esc_attr( $term->slug ); ?>"
							<?php endif; ?>
                             class="item-filter <?php if ( $key == 0 ): echo esc_attr( 'filter-active' ); endif; ?>">
							<?php echo esc_html( $term->name ); ?>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
        </div>
        <div class="portfolio-grid" data-layoutmode="fitRows"
             data-cols="<?php echo esc_attr( $fastshop_data_cols ); ?>">
			<?php if ( !empty( $fastshop_categories ) ) : ?>
				<?php foreach ( $fastshop_categories as $value ) : ?>
					<?php
					$args      = array(
						'post_type'      => 'post',
						'cat'            => $value,
						'posts_per_page' => $fastshop_data_show_posts,
					);
					$loop_post = new wp_query( $args );
					?>
					<?php while ( $loop_post->have_posts() ): $loop_post->the_post(); ?>
						<?php
						if ( !in_array( get_the_ID(), $args_id ) ) {
							array_push( $args_id, get_the_ID() );
						} else {
							continue;
						}
						?>
                        <article <?php post_class( $post_class ); ?>>
                            <div class="post-inner">
                                <div class="post-thumb">
									<?php $image_thumb = fastshop_resize_image( get_post_thumbnail_id(), null, 417, 260, true, $lazy_check ); ?>
                                    <a class="thumb-link" href="<?php the_permalink(); ?>">
										<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                    </a>
                                </div>
                                <div class="post-info">
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
                                    <h3 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                </div>
                            </div>
                        </article>
					<?php endwhile; ?>
				<?php endforeach; ?>
			<?php else: ?>
				<?php
				$terms = get_terms( 'category' );
				if ( !empty( $terms ) && !is_wp_error( $terms ) ) :
					foreach ( $terms as $term ) : ?>
						<?php
						$args      = array(
							'post_type'      => 'post',
							'cat'            => $term->term_id,
							'posts_per_page' => $fastshop_data_show_posts,
						);
						$loop_post = new wp_query( $args );
						?>
						<?php while ( $loop_post->have_posts() ): $loop_post->the_post(); ?>
							<?php
							if ( !in_array( get_the_ID(), $args_id ) ) {
								array_push( $args_id, get_the_ID() );
							} else {
								continue;
							}
							?>
                            <article <?php post_class( $post_class ); ?>>
                                <div class="post-inner">
                                    <div class="post-thumb">
										<?php $image_thumb = fastshop_resize_image( get_post_thumbnail_id(), null, 417, 260, true, $lazy_check ); ?>
                                        <a class="thumb-link" href="<?php the_permalink(); ?>">
											<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                        </a>
                                    </div>
                                    <div class="post-info">
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
                                        <h3 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                    </div>
                                </div>
                            </article>
						<?php endwhile; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
	<?php wp_reset_postdata(); ?>
<?php else: ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>
