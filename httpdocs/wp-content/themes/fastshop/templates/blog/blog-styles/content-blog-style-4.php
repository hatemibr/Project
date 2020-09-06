<?php
$fastshop_blog_lazy = fastshop_get_option( 'fastshop_theme_lazy_load' );
$lazy_check         = $fastshop_blog_lazy == 1 ? true : false;
$image_thumb        = fastshop_resize_image( get_post_thumbnail_id(), null, 570, 345, true, $lazy_check );
?>
<div class="blog-thumb">
    <a href="<?php the_permalink(); ?>">
		<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
    </a>
</div>
<div class="blog-info">
    <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <ul class="blog-meta">
        <li class="date">
			<?php echo get_the_date( 'j M Y' ); ?>
        </li>
        <li class="comment">
			<?php comments_number(
				esc_html__( '0 Comment', 'fastshop' ),
				esc_html__( '1 Comment', 'fastshop' ),
				esc_html__( '% Comments', 'fastshop' )
			);
			?>
        </li>
    </ul>
    <div class="blog-content">
		<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 40, '' ); ?>
    </div>
    <div class="blog-footer">
        <i class="fa fa-heart-o"></i>
        <i class="fa fa-comment-o"></i>
        <i class="fa fa-paper-plane-o"></i>
    </div>
</div>