<?php
$fastshop_blog_lazy = fastshop_get_option( 'fastshop_theme_lazy_load' );
$lazy_check         = $fastshop_blog_lazy == 1 ? true : false;
$image_thumb        = fastshop_resize_image( get_post_thumbnail_id(), null, 370, 226, true, true, $lazy_check );
?>
<div class="blog-thumb">
    <a href="<?php the_permalink(); ?>">
		<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
    </a>
</div>
<div class="blog-info">
    <nav class="sticky-date">
        <span class="day"><?php echo get_the_date( 'j' ); ?></span>
        <span class="month"><?php echo get_the_date( 'M' ); ?></span>
    </nav>
    <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <ul class="blog-meta">
        <li class="date">
			<?php echo get_the_date( 'j M Y' ); ?>
        </li>
        <li class="comment">
            <i class="fa fa-comment"></i>
			<?php comments_number(
				esc_html__( '0 Comment', 'fastshop' ),
				esc_html__( '1 Comment', 'fastshop' ),
				esc_html__( '% Comments', 'fastshop' )
			);
			?>
        </li>
    </ul>
    <div class="blog-content"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 10, esc_html__( '...', 'fastshop' ) ); ?></div>
    <a href="<?php the_permalink(); ?>" class="blog-readmore">
		<?php echo esc_html__( 'Read more', 'fastshop' ); ?>
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
    </a>
</div>