<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'vc_before_init', 'fastshop_language_settings' );
function fastshop_language_settings()
{

	$params = array(
		array(
			"type"        => "textfield",
			"heading"     => esc_html__( "Extra class name", "fastshop" ),
			"param_name"  => "el_class",
			"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "fastshop" ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => esc_html__( 'Css', 'fastshop' ),
			'param_name' => 'css',
			'group'      => esc_html__( 'Design options', 'fastshop' ),
		),
    );

	$map_settings = array(
		'name'        => esc_html__( 'Fastshop: Language', 'fastshop' ),
		'base'        => 'fastshop_language', // shortcode
		'class'       => '',
		'category'    => esc_html__( 'Fastshop Elements', 'fastshop' ),
		'description' => __( 'Display a language.', 'fastshop' ),
		'params'      => $params,
	);

	vc_map( $map_settings );
}

function fastshop_language( $atts )
{

	$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_language', $atts ) : $atts;

	$default_atts = array(
		'css'            => '',
		'el_class'       => '',
    );

	extract( shortcode_atts( $default_atts, $atts ) );
	$css_class = $el_class . '';
	if ( function_exists( 'vc_shortcode_custom_css_class' ) ):
		$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
	endif;

	ob_start();
	?>
    <div class="fastshop-language <?php echo esc_attr( $css_class ); ?>">
		<?php if( class_exists('SitePress') ):
			global $sitepress;

			$current_language = $sitepress->get_current_language();
			$active_languages = $sitepress->get_ls_languages();

			?>
            <ul class="language-list">
				<?php foreach( $active_languages as $key=>$value ) : ?>
                    <li class="switcher-option">
                        <a href="<?php echo esc_url( $value['url'] ); ?>">
							<?php if( !empty( $value['country_flag_url'] ) ) : ?>
                                <img class="switcher-flag icon" alt="flag" src="<?php echo esc_url( $value['country_flag_url'] ); ?>" />
							<?php endif; ?>
							<?php echo esc_html( $value['native_name'] ); ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php endif; ?>
    </div>
	<?php
	$html = ob_get_clean();

	return balanceTags( $html );
}

add_shortcode( 'fastshop_language', 'fastshop_language' );
