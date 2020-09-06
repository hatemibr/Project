<?php
if ( !class_exists( 'Fastshop_Shortcode_Googlemap' ) ) {
	class Fastshop_Shortcode_Googlemap extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'googlemap';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $atts['map_height'] > 0 ) {
				$css .= '.' . $atts['googlemap_custom_id'] . '.fastshop-google-maps { min-height:' . $atts['map_height'] . 'px;} ';
			}

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_googlemap', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'fastshop-google-maps' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['googlemap_custom_id'];
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			$id = uniqid();
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                 id="az-google-maps-<?php echo esc_attr( $id ); ?>">
            </div>
            <script type="text/javascript">
                function init_Map_<?php echo esc_attr( $id ); ?>() {
                    var $hue             = '',
                        $saturation      = '',
                        $modify_coloring = false,
                        $fastshop_map    = {
                            lat: <?php echo esc_attr( $atts['latitude'] ); ?>,
                            lng: <?php echo esc_attr( $atts['longitude'] ) ?>
                        };
                    if ( $modify_coloring === true ) {
                        var $styles = [
                            {
                                stylers: [
                                    {hue: $hue},
                                    {invert_lightness: false},
                                    {saturation: $saturation},
                                    {lightness: 1},
                                    {
                                        featureType: "landscape.man_made",
                                        stylers: [ {
                                            visibility: "on"
                                        } ]
                                    }
                                ]
                            }, {
                                featureType: 'water',
                                elementType: 'geometry',
                                stylers: [
                                    {color: '#46bcec'}
                                ]
                            }
                        ];
                    }
                    var map = new google.maps.Map(document.getElementById("az-google-maps-<?php echo esc_attr( $id ); ?>"), {
                        zoom: <?php echo esc_attr( $atts['zoom'] ) ?>,
                        center: $fastshop_map,
                        mapTypeId: google.maps.MapTypeId.<?php echo esc_attr( $atts['map_type'] ) ?>,
                        styles: $styles
                    });

                    var contentString = '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="fastshop-map-info">' +
                        '<h4 class="map-title"><?php echo esc_html( $atts['title'] ) ?></h4>' +
                        '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;<?php echo esc_html( $atts['address'] ) ?></span></div>' +
                        '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;<a href="tel:<?php echo esc_html( $atts['phone'] ) ?>"><?php echo esc_html( $atts['phone'] ) ?></a></span></div>' +
                        '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:<?php echo esc_html( $atts['email'] ) ?>">&nbsp;<?php echo esc_html( $atts['email'] ) ?></a></span></div> ' +
                        '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var marker = new google.maps.Marker({
                        position: $fastshop_map,
                        map: map
                    });
                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });
                }

                window.addEventListener('load',
                    function (ev) {
                        init_Map_<?php echo esc_attr( $id ); ?>();
                    }, false);
            </script>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_googlemap', force_balance_tags( $html ), $atts, $content );
		}
	}
}