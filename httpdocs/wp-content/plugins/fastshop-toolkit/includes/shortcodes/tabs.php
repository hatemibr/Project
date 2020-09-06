<?php

if ( !class_exists( 'Fastshop_Shortcode_Tabs' ) ) {
	class Fastshop_Shortcode_Tabs extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'tabs';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'style'          => '',
			'css_animation'  => '',
			'el_class'       => '',
			'css'            => '',
			'ajax_check'     => 'no',
			'tabs_custom_id' => '',
			'the_title'      => '',
			'link'           => '',
			'active_section' => '',
			'title_style'    => '',
			'des'            => '',
			'padding_tabs'   => '',
			'position_tabs'  => '',
			'the_color'      => '',
		);


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $atts[ 'the_color' ] ) {
				$css .= '.' . $atts[ 'tabs_custom_id' ] . '.fastshop-tabs .tab-head {
		            border-bottom-color: ' . $atts[ 'the_color' ] . '
			    }';
				$css .= '.' . $atts[ 'tabs_custom_id' ] . '.fastshop-tabs .tab-head .fastshop-title {
		            background-color: ' . $atts[ 'the_color' ] . '
			    }';
			}

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_tabs', $atts ) : $atts;
			// Extract shortcode parameters.
			extract(
				shortcode_atts(
					$this->default_atts,
					$atts
				)
			);

			$css_class   = array( 'fastshop-tabs' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'tabs_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class [] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$sections = $this->get_all_attributes( 'vc_tta_section', $content );
			$rand     = rand();
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head <?php echo esc_attr( $atts[ 'position_tabs' ] ); ?>">
						<?php if ( $atts[ 'the_title' ] ) : ?>
                            <div class="fastshop-title block-title style4">
                                <h2 class="title"><?php echo esc_html( $atts[ 'the_title' ] ); ?></h2>
								<?php if ( $atts[ 'link' ] && $atts[ 'style' ] != 'style10' ) : ?>
									<?php
									$link = vc_build_link( $atts[ 'link' ] );
									if ( $link[ 'target' ] == '' ) {
										$link[ 'target' ] = '_self';
									}
									?>
                                    <a class="view-all" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                                       target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
										<?php echo esc_html( $link[ 'title' ] ) ?>
                                    </a>
								<?php endif; ?>
                            </div>
						<?php endif; ?>
                        <div class="tab-link-wrap" <?php if ( $atts[ 'padding_tabs' ] ) : ?> style="padding-right: <?php echo esc_attr( $atts[ 'padding_tabs' ] ); ?>" <?php endif; ?>>
                            <ul class="tab-link">
								<?php
								$i         = 0;
								$sum       = 0;
								$style_css = '';
								if ( $atts[ 'style' ] == 'style2' ) {
									foreach ( $sections as $section ) {
										$sum++;
									}
									$percent   = 100 / intval( $sum );
									$style_css = 'style="width: ' . esc_attr( $percent ) . '%"';
								}

								?>
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

									?>
                                    <li class="<?php if ( $i == $atts[ 'active_section' ] ): ?>active<?php endif; ?>"
										<?php if ( $atts[ 'style' ] == 'style2' ) : echo force_balance_tags( $style_css ); endif; ?>>
                                        <a <?php if ( $i == $atts[ 'active_section' ] ) {
											echo 'class="loaded"';
										} ?> data-ajax="<?php echo esc_attr( $atts[ 'ajax_check' ] ) ?>"
                                             data-id='<?php echo get_the_ID(); ?>'
                                             data-animate="<?php echo esc_attr( $atts[ 'css_animation' ] ); ?>"
                                             data-section="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>"
                                             href="#<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>">
											<?php if ( $add_icon == true && $position_icon != 'right' ) : ?><i
                                                class="before-icon <?php echo esc_attr( $class_icon ); ?>"></i><?php endif; ?>
											<?php echo esc_html( $section[ 'title' ] ); ?>
											<?php if ( $add_icon == true && $position_icon == 'right' ) : ?><i
                                                class="after-icon <?php echo esc_attr( $class_icon ); ?>"></i><?php endif; ?>
                                        </a>
                                    </li>
								<?php endforeach; ?>
                            </ul>
							<?php if ( $atts[ 'style' ] == 'style5' ) : ?>
                                <div class="tab-underline"></div>
							<?php endif; ?>
							<?php if ( $atts[ 'style' ] == 'style10' ) : ?>
								<?php if ( $atts[ 'link' ] ) : ?>
									<?php $link = vc_build_link( $atts[ 'link' ] ); ?>
                                    <a class="view-all" href="<?php echo esc_url( $link[ 'url' ] ) ?>"
                                       target="<?php echo esc_attr( $link[ 'target' ] ) ?>">
										<?php echo esc_html( $link[ 'title' ] ) ?>
                                    </a>
								<?php endif; ?>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-container">
						<?php $i = 0; ?>
						<?php foreach ( $sections as $section ): ?>
							<?php $i++; ?>
                            <div class="tab-panel <?php if ( $i == $atts[ 'active_section' ] ): ?>active<?php endif; ?>"
                                 id="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>-<?php echo $rand; ?>">
								<?php
								if ( $atts[ 'ajax_check' ] == '1' ) {
									if ( $i == $atts[ 'active_section' ] ) {
										echo do_shortcode( $section[ 'content' ] );
									}
								} else {
									echo do_shortcode( $section[ 'content' ] );
								}
								?>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_tabs', force_balance_tags( $html ), $atts, $content );
		}
	}
}