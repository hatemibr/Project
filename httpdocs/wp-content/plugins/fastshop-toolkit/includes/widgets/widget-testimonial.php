<?php
/**
 *
 * Fastshop testimonial
 *
 */
if ( !class_exists( 'Testimonial_Widget' ) ) {
	class Testimonial_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-testimonial',
				'description' => 'Widget testimonial.',
			);

			parent::__construct( 'widget_testimonial', '1 - Fastshop Testimonial', $widget_ops );
		}

		function widget( $args, $instance )
		{

			extract( $args );

			echo $before_widget;
			if ( !empty( $instance[ 'title' ] ) ) {
				echo $before_title . $instance[ 'title' ] . $after_title;
			}

			$args = array(
				'post_type'           => 'testimonial',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			);

			if ( $instance[ 'choose_post' ] == '0' ) {
				$args[ 'posts_per_page' ] = $instance[ 'number' ];
			} else {
				$args[ 'post__in' ] = $instance[ 'ids' ];
			}

			$loop_posts = new WP_Query( $args );
			?>
			<?php if ( $loop_posts->have_posts() ) : ?>
            <div class="fastshop-testimonials style4">
                <div class="owl-carousel nav2" data-items="1" data-dots="true">
					<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
						<?php
						$meta_data = get_post_meta( get_the_ID(), '_custom_testimonial_options', true );
						$avatar    = '';
						$name      = '';
						$position  = '';
						if ( !empty( $meta_data ) ) {
							$avatar   = $meta_data[ 'avatar_testimonial' ];
							$name     = $meta_data[ 'name_testimonial' ];
							$position = $meta_data[ 'position_testimonial' ];
						}
						$image_thumb = fastshop_resize_image( $avatar, null, 140, 140, true, false );
						?>
                        <div <?php post_class( 'testimonial-item' ); ?>>
                            <p class="excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 22, esc_html__( '.', 'fastshop-toolkit' ) ); ?></p>
                            <div class="avatar">
                                <a href="<?php the_permalink(); ?>">
									<?php echo force_balance_tags( $image_thumb[ 'img' ] ); ?>
                                </a>
                                <div class="info">
                                    <h4 class="name">
                                        <a href="<?php the_permalink(); ?>"><?php echo esc_html( $name ); ?></a>
                                    </h4>
                                    <span class="position"><?php echo esc_html( $position ); ?></span>
                                </div>
                            </div>
                        </div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
                </div>
            </div>
		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
			<?php
			echo $after_widget;

		}

		function update( $new_instance, $old_instance )
		{

			$instance                  = $old_instance;
			$instance[ 'ids' ]         = $new_instance[ 'ids' ];
			$instance[ 'title' ]       = $new_instance[ 'title' ];
			$instance[ 'number' ]      = $new_instance[ 'number' ];
			$instance[ 'choose_post' ] = $new_instance[ 'choose_post' ];

			return $instance;

		}

		function form( $instance )
		{
			//
			// set defaults
			// -------------------------------------------------
			$instance = wp_parse_args(
				$instance,
				array(
					'title'       => '',
					'number'      => '3',
					'choose_post' => '0',
					'ids'         => '',
				)
			);

			$title_value = $instance[ 'title' ];
			$title_field = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'fastshop-toolkit' ),
			);
            echo '<p>';
			echo cs_add_element( $title_field, $title_value );
			echo '</p>';

			$choose_post_value = $instance[ 'choose_post' ];
			$choose_post_field = array(
				'id'         => $this->get_field_name( 'choose_post' ),
				'name'       => $this->get_field_name( 'choose_post' ),
				'type'       => 'select',
				'options'    => array(
					'0' => 'Loop Post',
					'1' => 'Each Post',
				),
				'attributes' => array(
					'data-depend-id' => 'choose_post',
				),
				'title'      => esc_html__( 'Choose Type Post', 'fastshop-toolkit' ),
			);
			echo '<p>';
			echo cs_add_element( $choose_post_field, $choose_post_value );
			echo '</p>';

			$ids_value = $instance[ 'ids' ];
			$ids_field = array(
				'id'         => $this->get_field_name( 'ids' ),
				'name'       => $this->get_field_name( 'ids' ),
				'type'       => 'select',
				'options'    => 'posts',
				'query_args' => array(
					'post_type' => 'testimonial',
					'orderby'   => 'post_date',
					'order'     => 'DESC',
				),
				'class'      => 'chosen',
				'attributes' => array(
					'multiple' => 'multiple',
					'style'    => 'width: 100%;',
				),
				'dependency' => array( 'choose_post', '==', '1' ),
				'title'      => esc_html__( 'Choose Type Post', 'fastshop-toolkit' ),
			);
			echo '<p>';
			echo cs_add_element( $ids_field, $ids_value );
			echo '</p>';

			$number_value = $instance[ 'number' ];
			$number_field = array(
				'id'         => $this->get_field_name( 'number' ),
				'name'       => $this->get_field_name( 'number' ),
				'type'       => 'number',
				'dependency' => array( 'choose_post', '==', '0' ),
				'title'      => esc_html__( 'Number Post', 'fastshop-toolkit' ),
			);
			echo '<p>';
			echo cs_add_element( $number_field, $number_value );
			echo '</p>';
		}
	}
}

if ( !function_exists( 'Testimonial_Widget_init' ) ) {
	function Testimonial_Widget_init()
	{
		register_widget( 'Testimonial_Widget' );
	}

	add_action( 'widgets_init', 'Testimonial_Widget_init', 2 );
}