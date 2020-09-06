<?php $image_thumb = fastshop_resize_image( get_post_thumbnail_id(), null, 370, 224, true, false ); ?>
<div class="blog-thumb">
    <a href="<?php the_permalink(); ?>"><?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?></a>
</div>
<div class="blog-info">
    <h2 class="blog-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <div class="blog-content">
		<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 20, esc_html__( '...', 'fastshop' ) ); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="blog-readmore">
		<?php echo esc_html__( 'Read more', 'fastshop' ); ?>
    </a>
</div>