<?php

if ( !class_exists( 'Fastshop_Shortcode_Accordions' ) ) {
	class Fastshop_Shortcode_Accordions extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'accordions';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'style'                => '',
			'el_class'             => '',
			'css'                  => '',
			'accordions_custom_id' => '',
			'active_tab'           => '',
			'ajax_check'           => '',
		);


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );

			return '';
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_accordions', $atts ) : $atts;
			// Extract shortcode parameters.
			extract(
				shortcode_atts(
					$this->default_atts,
					$atts
				)
			);

			$css_class = array( 'fastshop-accordions panel-group' );

			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'accordions_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$sections = $this->get_all_attributes( 'vc_tta_section', $content );
			$rand     = rand();
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>" role="tablist"
                 aria-multiselectable="true">
				<?php $i = 0; ?>
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
					<?php foreach ( $sections as $section ): ?>
						<?php
						$i++;

						/* Get icon from section tabs */
						$type_icon = isset( $section[ 'i_type' ] ) ? $section[ 'i_type' ] : '';
						$add_icon  = isset( $section[ 'add_icon' ] ) ? $section[ 'add_icon' ] : '';

						if ( $type_icon == 'fontflaticon' ) {
							$class_icon = isset( $section[ 'icon_fastshopcustomfonts' ] ) ? $section[ 'icon_fastshopcustomfonts' ] : '';
						} else {
							$class_icon = isset( $section[ 'icon_fontawesome' ] ) ? $section[ 'icon_fontawesome' ] : '';
						}

						$position_icon = isset( $section[ 'i_position' ] ) ? $section[ 'i_position' ] : '';
						$icon          = '';
						if ( $add_icon == true ) {
							$icon = '<i class="' . esc_attr( $class_icon ) . '"></i>';
						}

						?>
                        <div class="panel panel-default <?php if ( $i == $atts[ 'active_tab' ] ): ?>active<?php endif; ?>">
                            <div class="panel-heading" role="tab"
                                 id="accordion-<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>">
                                <h4 class="panel-title">
									<?php if ( $add_icon == true && $position_icon != 'right' ) : echo balanceTags( $icon ); endif; ?>
                                    <a class="<?php if ( $i == $atts[ 'active_tab' ] ): ?>loaded<?php endif; ?>"
                                       role="button" data-toggle="collapse"
                                       data-ajax="<?php echo esc_attr( $atts[ 'ajax_check' ] ) ?>"
                                       data-id='<?php echo get_the_ID(); ?>'
                                       data-parent=".<?php echo esc_attr( $atts[ 'accordions_custom_id' ] ); ?>"
                                       href="#<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>"
                                       data-section="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>"
									   <?php if ( $i == $atts[ 'active_tab' ] ): ?>aria-expanded="true"
									   <?php else: ?>aria-expanded="false"<?php endif; ?>
                                       aria-controls="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>">
										<?php echo esc_html( $section[ 'title' ] ); ?>
                                        <span></span>
                                    </a>
									<?php if ( $add_icon == true && $position_icon == 'right' ) : echo balanceTags( $icon ); endif; ?>
                                </h4>
                            </div>
                            <div id="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>"
                                 class="panel-collapse collapse <?php if ( $i == $atts[ 'active_tab' ] ): ?>in<?php endif; ?>"
                                 role="tabpanel"
                                 aria-labelledby="accordion-<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>">
								<?php
								if ( $atts[ 'ajax_check' ] == '1' ) {
									if ( $i == $atts[ 'active_tab' ] ) {
										echo do_shortcode( $section[ 'content' ] );
									}
								} else {
									echo do_shortcode( $section[ 'content' ] );
								}
								?>
                            </div>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_accordions', force_balance_tags( $html ), $atts, $content );
		}
	}
}