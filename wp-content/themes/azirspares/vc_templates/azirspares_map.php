<?php
if ( !class_exists( 'Azirspares_Shortcode_Map' ) ) {
	class Azirspares_Shortcode_Map extends Azirspares_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'map';

		static public function add_css_generate( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'azirspares_map', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			$css .= '.azirspares-google-maps.' . $atts['azirspares_custom_id'] . ' { min-height:' . $atts['map_height'] . 'px;} ';

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'azirspares_map', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class    = array( 'azirspares-google-maps' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'azirspares_map', $atts );
			ob_start();
			$id = uniqid();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                 id="az-google-maps-<?php echo esc_attr( $id ); ?>"
                 data-address="<?php echo esc_attr( $atts['address'] ); ?>"
                 data-phone="<?php echo esc_attr( $atts['phone'] ); ?>"
                 data-email="<?php echo esc_attr( $atts['email'] ); ?>"
                 data-title="<?php echo esc_attr( $atts['title'] ); ?>"
                 data-latitude="<?php echo esc_attr( $atts['latitude'] ); ?>"
                 data-longitude="<?php echo esc_attr( $atts['longitude'] ); ?>"
                 data-zoom="<?php echo esc_attr( $atts['zoom'] ); ?>"
                 data-map_type="<?php echo esc_attr( $atts['map_type'] ); ?>"
                 data-id="az-google-maps-<?php echo esc_attr( $id ); ?>">
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Azirspares_Shortcode_Map', $html, $atts, $content );
		}
	}

	new Azirspares_Shortcode_Map();
}