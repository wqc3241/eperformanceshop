<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'famiauVcShortcode_Filter_Dropdown' ) ) {
	class famiauVcShortcode_Filter_Dropdown extends famiauVcShortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'filter_dropdown'; // required
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'famiau_filter_dropdown', $atts ) : $atts;
			extract( $atts );
			
			$filters_selected = trim( $filters_selected );
			if ( $filters_selected == '' ) {
				return '';
			}
			
			$all_options = famiau_get_all_options();
			
			$css_class    = array( 'famiau-filter-dropdown-wrap' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'famiau_filter_dropdown', $atts );
			
			$html         = '';
			$title_html   = '';
			$selects_html = '';
			$button_html  = '';
			
			// Title
			if ( trim( $title ) != '' ) {
				$title_html .= '<h3 class="famiau-title">' . esc_html( $title ) . '</h3>';
			}
			
			$filters_list = famiau_filers_list_for_dropdown_params();
			
			// Selects
			$filters_selected = explode( ',', $filters_selected );
			$box_num          = 0;
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
							$box_num ++;
							$filter_name  = '_famiau_make';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= '<span class="box-num box-num-' . famiau_box_leading_num( $box_num ) . '">' . famiau_box_leading_num( $box_num ) . '</span>';
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
						case 'years':
							// Years
							$years_html = '';
							$this_year  = intval( date( 'Y' ) );
							$min_year   = intval( $all_options['_famiau_min_year'] ) <= 0 ? 1700 : intval( $all_options['_famiau_min_year'] );
							$max_year   = intval( $all_options['_famiau_min_year'] ) <= 0 ? $this_year : intval( $all_options['_famiau_max_year'] );
							if ( $max_year < $min_year ) {
								$max_year = $min_year;
							}
							if ( $max_year > $this_year ) {
								$max_year = $this_year;
							}
							
							$years_from_first_option = '<option value="0">' . esc_html__( 'From Year', 'famiau' ) . '</option>';
							$years_to_first_option   = '<option value="' . $this_year . '">' . esc_html__( 'To Year', 'famiau' ) . '</option>';
							$selected_year           = isset( $selected_parameters['_famiau_year'] ) ? $selected_parameters['_famiau_year'] : '';
							for ( $y = $min_year; $y <= $max_year; $y ++ ) {
								$years_html .= '<option ' . selected( true, $selected_year == $y, false ) . ' value="' . $y . '">' . $y . '</option>';
							}
							if ( $years_html != '' ) {
							    $filter_name_min_year = 'min_year';
							    $filter_name_max_year = 'max_year';
							    $min_year_select_html = '<div class="famiau-box famiau-box-' . $filter_name_min_year . '"><select class="famiau-select famiau-filter-item famiau-filter-by-year famiau-filter-from-year famisp-select-num-min" name="'. $filter_name_min_year . '" data-filter_key="from_year" data-filter_val="' . esc_attr( $min_year ) . '">' . $years_from_first_option . $years_html . '</select></div>';
							    $max_year_select_html = '<div class="famiau-box famiau-box-' . $filter_name_max_year . '"><select class="famiau-select famiau-filter-item famiau-filter-by-year famiau-filter-to-year famisp-select-num-max" name="'. $filter_name_max_year . '"data-filter_key="to_year" data-filter_val="' . esc_attr( $max_year ) . '">' . $years_to_first_option . $years_html . '</select></div>';
							    
								$years_html = $min_year_select_html . $max_year_select_html;
								$years_html = '<div class="famiau-filter-box famiau-filter-box-years famiau-select-min-max-group"><div class="famiau-select-group-inner">' . $years_html . '</div></div>';
							}
							
							$selects_html .= $years_html;
							
							break;
						case 'models':
							$box_num ++;
							$filter_name  = '_famiau_model';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= '<span class="box-num box-num-' . famiau_box_leading_num( $box_num ) . '">' . famiau_box_leading_num( $box_num ) . '</span>';
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
							$box_num ++;
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= '<span class="box-num box-num-' . famiau_box_leading_num( $box_num ) . '">' . famiau_box_leading_num( $box_num ) . '</span>';
							$selects_html .= famiau_car_status_select_html( '', 'famiau-filter-select famiau-field famiau-filter-by-' . $filter_name, $filter_name, $filter_name, $first_select_text, false );
							$selects_html .= '</div>';
							break;
						case 'all_gearbox_types':
							$box_num ++;
							$filter_name  = '_famiau_gearbox_type';
							$selects_html .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
							$selects_html .= '<span class="box-num box-num-' . famiau_box_leading_num( $box_num ) . '">' . famiau_box_leading_num( $box_num ) . '</span>';
							$selects_html .= famiau_gearbox_type_select_html( '', 'famiau-filter-select famiau-field famiau-filter-by-' . $filter_name, $filter_name, $filter_name, $first_select_text, false );
							$selects_html .= '</div>';
							break;
					}
				}
				
				if ( $this_filter_select_html != '' ) {
					$box_num ++;
					$this_filter_select_html = '<select data-key="' . esc_attr( $filter_name ) . '" id="' . $filter_name . '" name="' . $filter_name . '" class="famiau-select famiau-filter-select famiau-field famiau-filter-by-' . esc_attr( $filter_name ) . '">' . $this_filter_select_html . '</select>';
					$selects_html            .= '<div class="famiau-box famiau-box-' . $filter_name . '">';
					$selects_html            .= '<span class="box-num box-num-' . famiau_box_leading_num( $box_num ) . '">' . famiau_box_leading_num( $box_num ) . '</span>';
					$selects_html            .= $this_filter_select_html;
					$selects_html            .= '</div>';
				}
			}
			
			// Button
			$button_text = trim( $button_text ) == '' ? esc_html__( 'Search', 'famiau' ) : trim( $button_text );
			$button_html .= '<div class="famiau-box"><button type="submit" class="famiau-button button btn">' . esc_html( $button_text ) . '</button></div>';
			
			$listings_page_url = get_permalink( famiau_get_page( 'automotive' ) );
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="famiau-filter-dropdown-inner">
					<?php echo $title_html; ?>
                    <div class="famiau-filter-dropdowns">
                        <form method="post" action="<?php echo esc_url( $listings_page_url ); ?>"
                              class="famiau-filter-dropdowns-form">
                            <input type="hidden" name="filter_dropdown_request"/>
							<?php echo $selects_html . $button_html; ?>
                        </form>
                    </div>
                </div>
            </div>
			<?php
			
			$html .= ob_get_clean();
			
			return apply_filters( 'famiauVcShortcode_Filter_Dropdown', $html, $atts, $content );
		}
	}
	
	new famiauVcShortcode_Filter_Dropdown();
}