<?php
/* Session Start */
if ( !function_exists( 'fastshop_StartSession' ) ) {
	function fastshop_StartSession()
	{
		if ( !session_id() ) {
			session_start();
		}
	}
}
add_action( 'init', 'fastshop_StartSession', 1 );
/**
 *
 * Get option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !function_exists( 'fastshop_get_option' ) ) {
	function fastshop_get_option( $option_name = '', $default = '' )
	{
		$get_value = isset( $_GET[$option_name] ) ? $_GET[$option_name] : '';
		$cs_option = null;
		if ( defined( 'CS_VERSION' ) ) {
			$cs_option = get_option( CS_OPTION );
		}
		if ( isset( $_GET[$option_name] ) ) {
			$cs_option = $get_value;
			$default   = $get_value;
		}
		$options = apply_filters( 'cs_get_option', $cs_option, $option_name, $default );
		if ( !empty( $option_name ) && !empty( $options[$option_name] ) ) {
			return $options[$option_name];
		} else {
			return ( !empty( $default ) ) ? $default : null;
		}
	}
}
/* BODY CLASS */
add_filter( 'body_class', 'fastshop_body_class' );
if ( !function_exists( 'fastshop_body_class' ) ) {
	function fastshop_body_class( $classes )
	{
		$data_background = fastshop_get_option( 'background_page', '' );
		$background      = $data_background['image'];
		$color           = $data_background['color'];
		/* ENABLE PAGE OPTIONS */
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$hover_style          = 0;
		$block_vertical_menu  = 0;
		if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
			$hover_style         = isset( $meta_data['hover_style'] ) ? $meta_data['hover_style'] : '';
			$block_vertical_menu = isset( $meta_data['block_vertical_menu'] ) ? $meta_data['block_vertical_menu'] : '';
			if ( isset( $meta_data['bg_background_page'] ) ) {
				$page_background = $meta_data['bg_background_page'];
				$background      = $page_background['image'];
				$color           = $page_background['color'];
			}
		}
		/*Set demo home page*/
		$my_theme = wp_get_theme();
		if ( is_page() && $background || $color ) {
			$classes[] = 'fastshop-custom-background';
		}
		if ( $hover_style == 1 ) {
			$classes[] = 'hover-dark';
		}
		if ( $block_vertical_menu == 1 ) {
			$classes[] = 'vertical_allway_open';
		}
		$classes[] = $my_theme->get( 'Name' ) . "-" . $my_theme->get( 'Version' );

		return $classes;
	}
}
/* CONTROL MENU */
if ( !function_exists( 'fastshop_primary' ) ) {
	function fastshop_primary( $items, $args )
	{
		$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
			$header_style = $data_meta['fastshop_metabox_used_header'];
		}
		if ( $args->theme_location == 'primary' ) {
			ob_start();
			echo force_balance_tags( $items );
			if ( $header_style == 'style-04' ) {
				get_template_part( 'template-parts/header', 'userlink' );
			}
			$content = ob_get_contents();
			ob_clean();
			$items = $content;
		}

		return $items;
	}

	add_filter( 'wp_nav_menu_items', 'fastshop_primary', 10, 2 );
}
if ( !function_exists( 'fastshop_top_right_menu' ) ) {
	function fastshop_top_right_menu( $items, $args )
	{
		$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
			$header_style = $data_meta['fastshop_metabox_used_header'];
		}
		if ( $args->theme_location == 'top_right_menu' ) {
			ob_start();
			if (
				$header_style == 'style-03' ||
				$header_style == 'style-10' ||
				$header_style == 'style-14' ||
				$header_style == 'style-23'
			) {
				get_template_part( 'template-parts/header', 'language' );
				get_template_part( 'template-parts/header', 'currency' );
			}
			echo force_balance_tags( $items );
			if (
				$header_style == 'style-01' ||
				$header_style == 'style-08' ||
				$header_style == 'style-09' ||
				$header_style == 'style-16' ||
				$header_style == 'style-18' ||
				$header_style == 'style-19' ||
				$header_style == 'style-20' ||
				$header_style == 'style-22'
			) {
				if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) : ?>
					<?php global $yith_woocompare; ?>
                    <li class="menu-item compare-item yith-woocompare-widget">
                        <a href="<?php echo add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() ) ?>"
                           class="compare added" rel="nofollow">
							<?php if ( $header_style == 'style-16' ) : ?>
                                <span class="icon flaticon-02arrows"></span>
							<?php endif; ?>
							<?php echo esc_html__( 'Compare', 'fastshop' ); ?>
                        </a>
                    </li>
				<?php endif;
			}
			if ( $header_style == 'style-05' ) {
				get_template_part( 'template-parts/header', 'language' );
				get_template_part( 'template-parts/header', 'currency' );
			}
			if ( $header_style != 'style-10' ) {
				get_template_part( 'template-parts/header', 'userlink' );
			}
			if ( $header_style == 'style-07' ) {
				get_template_part( 'template-parts/header', 'social' );
			}
			$content = ob_get_contents();
			ob_clean();
			$items = $content;
		}

		return $items;
	}

	add_filter( 'wp_nav_menu_items', 'fastshop_top_right_menu', 10, 2 );
}
add_filter( 'wp_nav_menu_args', 'fastshop_wp_nav_menu_args', 10, 1 );
if ( !function_exists( 'fastshop_wp_nav_menu_args' ) ) {
	function fastshop_wp_nav_menu_args( $args )
	{
		$enable_demo          = fastshop_get_option( 'fastshop_enable_demo' );
		$header_style         = fastshop_get_option( 'fastshop_used_header', 'style-01' );
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
			$header_style = $data_meta['fastshop_metabox_used_header'];
		}
		if ( $args['theme_location'] == 'primary' && $enable_demo == 1 ) {
			if ( $header_style == 'style-02' ) {
				$args['menu'] = 'Primary Menu Home 02';
			} elseif ( $header_style == 'style-06' ) {
				$args['menu'] = 'Primary Menu Home 06';
			} elseif ( $header_style == 'style-07' ) {
				$args['menu'] = 'Primary Menu Home 07';
			} elseif ( $header_style == 'style-16' ) {
				$args['menu'] = 'Primary Menu Home 16';
			}
		}

		return $args;
	}
}
function fastshop_set_post_views( $postID )
{
	if ( get_post_type( $postID ) == 'post' ) {
		$count_key = 'fastshop_post_views_count';
		$count     = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			$count = 0;
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
		} else {
			$count++;
			update_post_meta( $postID, $count_key, $count );
		}
	}
}

if ( !function_exists( 'fastshop_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @since Fastshop 1.0
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
	 */
	function fastshop_paging_nav()
	{
		global $wp_query;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		echo get_the_posts_pagination();
	}
}
if ( !function_exists( 'fastshop_resize_image' ) ) {
	/**
	 * @param int $attach_id
	 * @param string $img_url
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
	 * @param bool $use_lazy
	 *
	 * @since 1.0
	 * @return array
	 */
	function fastshop_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $use_lazy = false )
	{
		$img_lazy = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
		if ( is_singular() && !$attach_id ) {
			if ( has_post_thumbnail() && !post_password_required() ) {
				$attach_id = get_post_thumbnail_id();
			}
		}
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
		}
		if ( !empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
			$file_info        = pathinfo( $actual_file_path );
			$extension        = '.' . $file_info['extension'];
			$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
			$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image        = array(
						'url'    => $cropped_img_url,
						'width'  => $width,
						'height' => $height,
						'img'    => '<img class="img-responsive" src="' . esc_url( $cropped_img_url ) . '" ' . image_hwstring( $width, $height ) . ' alt="fastshop">',
					);
					if ( $use_lazy == true ) {
						$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $cropped_img_url ) . '" ' . image_hwstring( $width, $height ) . ' alt="fastshop">';
					}

					return $vt_image;
				}
				if ( $crop == false ) {
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $resized_img_url,
							'width'  => $proportional_size[0],
							'height' => $proportional_size[1],
							'img'    => '<img class="img-responsive" src="' . esc_url( $resized_img_url ) . '" ' . image_hwstring( $proportional_size[0], $proportional_size[1] ) . ' alt="fastshop">',
						);
						if ( $use_lazy == true ) {
							$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $resized_img_url ) . '" ' . image_hwstring( $proportional_size[0], $proportional_size[1] ) . ' alt="fastshop">';
						}

						return $vt_image;
					}
				}
				/*no cache files - let's finally resize it*/
				$img_editor = wp_get_image_editor( $actual_file_path );
				if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
						'img'    => '',
					);
				}
				$new_img_path = $img_editor->generate_filename();
				if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
						'img'    => '',
					);
				}
				if ( !is_string( $new_img_path ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
						'img'    => '',
					);
				}
				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
				$vt_image     = array(
					'url'    => $new_img,
					'width'  => $new_img_size[0],
					'height' => $new_img_size[1],
					'img'    => '<img class="img-responsive" src="' . esc_url( $new_img ) . '" ' . image_hwstring( $new_img_size[0], $new_img_size[1] ) . ' alt="fastshop">',
				);
				if ( $use_lazy == true ) {
					$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $new_img ) . '" ' . image_hwstring( $new_img_size[0], $new_img_size[1] ) . ' alt="fastshop">';
				}

				return $vt_image;
			}
			$vt_image = array(
				'url'    => $image_src[0],
				'width'  => $image_src[1],
				'height' => $image_src[2],
				'img'    => '<img class="img-responsive" src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="fastshop">',
			);
			if ( $use_lazy == true ) {
				$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="fastshop">';
			}

			return $vt_image;
		} else {
			$width    = intval( $width );
			$height   = intval( $height );
			$vt_image = array(
				'url'    => 'http://via.placeholder.com/' . $width . 'x' . $height,
				'width'  => $width,
				'height' => $height,
				'img'    => '<img class="img-responsive" src="' . esc_url( 'http://via.placeholder.com/' . $width . 'x' . $height ) . '" ' . image_hwstring( $width, $height ) . ' alt="fastshop">',
			);

			return $vt_image;
		}
	}
}
/* GET LOGO */
if ( !function_exists( 'fastshop_get_logo' ) ) {
	/**
	 * Function get the site logo
	 *
	 * @since fastshop 1.0
	 * @author KuteTheme
	 **/
	function fastshop_get_logo()
	{
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$logo_url             = get_template_directory_uri() . '/assets/images/logo01.png';
		$logo                 = fastshop_get_option( 'fastshop_logo' );
		if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
			$logo = $meta_data['metabox_fastshop_logo'];
		}
		if ( $logo != '' ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
		}
		$html = '<a href="' . esc_url( home_url( '/' ) ) . '"><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="_rw" /></a>';
		echo apply_filters( 'fastshop_site_logo', $html );
	}
}
/* GET SEARCH FORM */
if ( !function_exists( 'fastshop_search_form' ) ) {
	/**
	 * Function get the search form template
	 *
	 * @since fastshop 1.0
	 * @author KuteTheme
	 **/
	function fastshop_search_form( $suffix = '' )
	{
		get_template_part( 'template-parts/search', 'form' . $suffix );
	}
}
/* GET HEADER */
if ( !function_exists( 'fastshop_get_header' ) ) {
	/**
	 * Function get the header form template
	 *
	 * @since fastshop 1.0
	 * @author KuteTheme
	 **/
	function fastshop_get_header()
	{
		/* Data MetaBox */
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$fastshop_used_header = fastshop_get_option( 'fastshop_used_header', 'style-01' );
		$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
			$fastshop_used_header = $data_meta['fastshop_metabox_used_header'];
		}
		get_template_part( 'templates/headers/header', $fastshop_used_header );
	}
}
/* GET HEADER */
/* GET FOOTER */
if ( !function_exists( 'fastshop_get_footer' ) ) {
	function fastshop_get_footer()
	{
		$fastshop_footer_options = fastshop_get_option( 'fastshop_footer_options', '' );
		/* Data MetaBox */
		$enable_theme_options = fastshop_get_option( 'enable_theme_options' );
		$data_option_meta     = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $data_option_meta ) && $enable_theme_options == 1 ) {
			$fastshop_footer_options = $data_option_meta['fastshop_metabox_footer_options'];
		}
		$data_meta = get_post_meta( $fastshop_footer_options, '_custom_footer_options', true );
		if ( empty( $data_meta ) ) {
			$fastshop_template_style = 'default';
		} else {
			$fastshop_template_style = $data_meta['fastshop_footer_style'];
		}
		ob_start();
		$query = new WP_Query( array( 'p' => $fastshop_footer_options, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post(); ?>
				<?php if ( $fastshop_template_style == 'default' ): ?>
                    <footer class="footer default">
                        <div class="container">
							<?php the_content(); ?>
                        </div>
                    </footer>
				<?php else: ?>
					<?php get_template_part( 'templates/footers/footer', $fastshop_template_style ); ?>
				<?php endif; ?>
			<?php endwhile;
		endif;
		wp_reset_postdata();
		echo ob_get_clean();
	}
}
/* GET FOOTER */
add_filter( 'nav_menu_css_class', 'fastshop_nav_class', 10, 3 );
if ( !function_exists( 'fastshop_nav_class' ) ) {
	function fastshop_nav_class( $classes, $item, $args )
	{
		if ( 'top_right_menu' === $args->theme_location ) {
			$classes[] = 'dropdown';
		}

		return $classes;
	}
}
add_filter( 'nav_menu_link_attributes', 'fastshop_nav_atts', 10, 3 );
if ( !function_exists( 'fastshop_nav_atts' ) ) {
	function fastshop_nav_atts( $atts, $item, $args )
	{
		if ( 'top_right_menu' === $args->theme_location ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'menu-item-object-megamenu', $item->classes ) ) {
				$atts['data-toggle'] = 'dropdown';
			}
		}

		return $atts;
	}
}