<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'famiauVcShortcode_Map_Listings' ) ) {
	class famiauVcShortcode_Map_Listings extends famiauVcShortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'map_listings'; // required
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'famiau_map_listings', $atts ) : $atts;
			extract( $atts );
			
			$filters_selected = trim( $filters_selected );
			if ( $filters_selected == '' ) {
				return '';
			}
			
			$all_options = famiau_get_all_options();
			
			$css_class    = array( 'famiau-map-filters-wrap' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'famiau_map_listings', $atts );
			
			$html                 = '';
			$title_html           = '';
			$selects_html         = '';
			$location_search_html = '';
			$button_html          = '';
			$map_html             = '';
			
			$map_latitude     = floatval( $map_latitude );
			$map_longitude    = floatval( $map_longitude );
			$map_zoom_default = max( 1, min( 14, intval( $map_zoom_default ) ) );
			
			$map_data = array(
				'zoom'   => $map_zoom_default,
				'center' => array(
					'lat' => $map_latitude,
					'lng' => $map_longitude
				)
			);
			
			$listings_info  = famiau_get_all_listings_info_for_map();
			$map_id         = uniqid( 'famiau-gmap-listings-' );
			$cluster_styles = famiau_get_map_cluster_styles();
			$marker_img_url = FAMIAU_URI . 'assets/images/marker.png';
			
			$map_html .= '<div data-map_data="' . esc_attr( wp_json_encode( $map_data ) ) . '" data-listings_info="' . esc_attr( wp_json_encode( $listings_info ) ) . '" data-cluster_styles="' . esc_attr( wp_json_encode( $cluster_styles ) ) . '" data-marker_url="' . esc_url( $marker_img_url ) . '" id="' . esc_attr( $map_id ) . '" class="famiau-listings-map-display"></div>';
			
			// Title
			if ( trim( $title ) != '' ) {
				$title_html .= '<h3 class="famiau-title">' . esc_html( $title ) . '</h3>';
			}
			
			$filters_list = famiau_filers_list_for_dropdown_params();
			
			// Selects
			$filters_selected = explode( ',', $filters_selected );
			foreach ( $filters_selected as $filter ) {
				$first_select_text = array_search( $filter, $filters_list );
				if ( $first_select_text == '' ) {
					$first_select_text = esc_html__( 'Select Option', 'famiau' );
				}
				$filter_name             = $filter;
				$this_filter_select_html = '';
				if ( isset( $all_options[ $filter ] ) ) {
					if ( ! empty( $all_options[ $filter ] ) ) {
						if ( $filter == 'all_makes' ) {
							$filter_name  = '_famiau_make';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= famiau_makes_select_html( '', 'famiau-filter-select famiau-field famiau-filter-by-' . $filter_name, $filter_name, $filter_name, false );
							$selects_html .= '</div>';
						} else {
							$this_filter_select_html .= '<option value="">' . $first_select_text . '</option>';
							foreach ( $all_options[ $filter ] as $filter_option ) {
								$this_filter_select_html .= '<option value="' . esc_attr( $filter_option ) . '">' . esc_attr( $filter_option ) . '</option>';
							}
						}
					}
					
				} else {
					switch ( $filter ) {
						case 'models':
							$filter_name  = '_famiau_model';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= '<select class="famiau-field famiau-select famiau-model-select famiau-filter-by-' . $filter_name . '" name="' . $filter_name . '" id="' . $filter_name . '">';
							$selects_html .= '<option data-model="" value="">' . esc_html__( 'Any Model', 'famiau' ) . '</option>';
							$selects_html .= '</select>';
							$selects_html .= '</div>';
							break;
						case 'max_price':
							$price_list = trim( $price_list );
							if ( $price_list != '' ) {
								$price_list              = explode( ',', $price_list );
								$this_filter_select_html .= '<option value="0">' . esc_html( $price_select_text ) . '</option>';
								foreach ( $price_list as $price ) {
									$price                   = intval( $price );
									$price_formated          = famiau_get_price_format_without_html( $price );
									$this_filter_select_html .= '<option value="' . $price . '">' . $price_formated . '</option>';
								}
							}
							break;
						case '_famiau_car_status':
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= famiau_car_status_select_html( '', 'famiau-filter-select famiau-field famiau-filter-by-' . $filter_name, $filter_name, $filter_name, $first_select_text, false );
							$selects_html .= '</div>';
							break;
						case 'all_gearbox_types':
							$filter_name  = '_famiau_gearbox_type';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= famiau_gearbox_type_select_html( '', 'famiau-filter-select famiau-field famiau-filter-by-' . $filter_name, $filter_name, $filter_name, $first_select_text, false );
							$selects_html .= '</div>';
							break;
					}
				}
				
				if ( $this_filter_select_html != '' ) {
					$this_filter_select_html = '<select data-key="' . esc_attr( $filter_name ) . '" id="' . $filter_name . '" name="' . $filter_name . '" class="famiau-select famiau-filter-select famiau-field famiau-filter-by-' . esc_attr( $filter_name ) . '">' . $this_filter_select_html . '</select>';
					$selects_html            .= '<div class="famiau-box famiau-box-' . $filter_name . '">' . $this_filter_select_html . '</div>';
				}
			}
			
			// Location search
			$map_location_search_id = uniqid( 'famiau-map_location' );
			$location_search_html   .= '<div class="famiau-box famiau-box-map_location"><input class="famiau-field map_location" name="' . esc_attr( $map_location_search_id ) . '" id="' . esc_attr( $map_location_search_id ) . '" type="text" value="" placeholder="' . esc_html__( 'Enter a location', 'famiau' ) . '" /></div>';
			
			// Button
			$button_text = trim( $button_text ) == '' ? esc_html__( 'Search', 'famiau' ) : trim( $button_text );
			$button_html .= '<div class="famiau-box"><button type="submit" class="famiau-button button btn">' . esc_html( $button_text ) . '</button></div>';
			
			$enable_instant_filter = isset( $all_options['_famiau_enable_instant_filter'] ) ? $all_options['_famiau_enable_instant_filter'] == 'yes' : false;
			if ( $enable_instant_filter ) {
				$css_class[] = 'has-instant-filter';
			}
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="famiau-map-filters-inner">
                    <div class="famiau-map-filters">
						<?php echo $map_html; ?>
                        <form method="post" class="famiau-map-filters-form form-opened">
                            <div class="famiau-form-inner">
								<?php echo $title_html; ?>
                                <div class="famiau-fields-wrap">
									<?php echo $selects_html . $location_search_html; ?>
                                </div>
								<?php echo $button_html; ?>
                                <div class="famiau-close-open-map-filter famiau-filter-open"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			<?php
			
			$html .= ob_get_clean();
			
			return apply_filters( 'famiauVcShortcode_Map_Listings', $html, $atts, $content );
		}
	}
	
	new famiauVcShortcode_Map_Listings();
}