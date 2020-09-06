<?php
/**
 *
 * Fastshop socials
 *
 */
if ( !class_exists( 'Socials_Widget' ) ) {
	class Socials_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-socials',
				'description' => 'Widget socials.',
			);

			parent::__construct( 'widget_socials', '1 - Fastshop Socials', $widget_ops );
		}

		function widget( $args, $instance )
		{
			extract( $args );
			$all_socials    = fastshop_get_option( 'user_all_social' );
			$get_all_social = $instance[ 'socials' ];
			echo $before_widget;
			if ( !empty( $instance[ 'title' ] ) ) {
				echo $before_title . $instance[ 'title' ] . $after_title;
			}
			?>
            <div class="conten-socials">
				<?php if ( !empty( $get_all_social ) ) : ?>
                    <ul class="social-list">
						<?php foreach ( $get_all_social as $value ) : ?>
							<?php $array_social = $all_socials[ $value ]; ?>
                            <li>
                                <a href="<?php echo esc_url( $array_social[ 'link_social' ] ) ?>" target="_blank">
                                    <span class="<?php echo esc_attr( $array_social[ 'icon_social' ] ); ?>"></span>
									<?php echo esc_html( $array_social[ 'title_social' ] ); ?>
                                </a>
                            </li>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance )
		{

			$instance              = $old_instance;
			$instance[ 'socials' ] = $new_instance[ 'socials' ];
			$instance[ 'title' ]   = $new_instance[ 'title' ];

			return $instance;

		}

		function form( $instance )
		{
			$data_meta = new Fastshop_ThemeOption();
			//
			// set defaults
			// -------------------------------------------------
			$instance = wp_parse_args(
				$instance,
				array(
					'socials' => '',
					'title'   => '',
				)
			);

			$title_value = $instance[ 'title' ];
			$title_field = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'fastshop-toolkit' ),
			);

			echo cs_add_element( $title_field, $title_value );

			$socials_value = $instance[ 'socials' ];
			$socials_field = array(
				'id'      => $this->get_field_name( 'socials' ),
				'name'    => $this->get_field_name( 'socials' ),
				'type'    => 'checkbox',
				'title'   => esc_html__( 'Select Social', 'fastshop-toolkit' ),
				'options' => $data_meta->social_options,
			);

			echo cs_add_element( $socials_field, $socials_value );
		}
	}
}

if ( !function_exists( 'Socials_Widget_init' ) ) {
	function Socials_Widget_init()
	{
		register_widget( 'Socials_Widget' );
	}

	add_action( 'widgets_init', 'Socials_Widget_init', 2 );
}