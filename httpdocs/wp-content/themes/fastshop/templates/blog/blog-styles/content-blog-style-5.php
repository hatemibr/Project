<?php
$fastshop_blog_lazy = fastshop_get_option( 'fastshop_theme_lazy_load' );
$lazy_check         = $fastshop_blog_lazy == 1 ? true : false;
$image_thumb        = fastshop_resize_image( get_post_thumbnail_id(), null, 870, 525, false, $lazy_check );
?>
<h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<ul class="blog-meta">
    <li class="author">
        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
			<?php the_author() ?>
        </a>
    </li>
    <li class="date">
		<?php echo get_the_date( 'j F, Y' ); ?>
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
<div class="blog-thumb">
    <a href="<?php the_permalink(); ?>"><?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?></a>
</div>
<div class="blog-info">
    <div class="blog-content">
		<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 50, esc_html__( '...', 'fastshop' ) ); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="blog-readmore">
		<?php echo esc_html__( 'READ MORE', 'fastshop' ); ?>
    </a>
</div>