<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'famiau_mega_filter_frontend_html' ) ) {
	function famiau_mega_filter_frontend_html( $args = array(), $echo = true ) {
		$enable_instant_filter = famiau_get_option( '_famiau_enable_instant_filter', 'no' );
		$args_default          = array(
			'sub_split_num'           => 9999, // threshold to split to sub menu new menu
			'min_year'                => 0,
			'max_year'                => 0,
			'price_step'              => 1, // 5000
			'show_price_filter_input' => true,
			'instant_filter'          => 'no',
			'class'                   => ''
		);
		
		if ( ! isset( $args['instant_filter'] ) ) {
			$args['instant_filter'] = $enable_instant_filter;
		}
		$args                          = wp_parse_args( $args, $args_default );
		$args['user_filter']           = false;
		$args['listing_status_filter'] = false;
		// $args['filter_after_load'] = $args_default['filter_after_load'];
		$args['class'] .= isset( $_REQUEST['filter_dropdown_request'] ) ? ' have-filter-dropdown-request' : '';
		extract( $args );
		
		$request_natural_keys = array(
			'car_status',
			'min_year',
			'max_year',
			'make',
			'model',
			'max_price',
			'fuel',
			'car_body',
			'drive',
			'exterior_color',
			'interior_color',
			'gearbox'
		);
		foreach ( $request_natural_keys as $request_natural_key ) {
			if ( isset( $_REQUEST[ $request_natural_key ] ) ) {
				$args['class'] .= ' filter-after-load';
			}
		}
		
		// Checking for post parameters
		$post_parameters = array(
			'_famiau_car_status'   => isset( $_REQUEST['_famiau_car_status'] ) ? $_REQUEST['_famiau_car_status'] : '',
			'_famiau_min_year'         => isset( $_REQUEST['_famiau_min_year'] ) ? $_REQUEST['_famiau_min_year'] : '',
			'_famiau_max_year'         => isset( $_REQUEST['_famiau_max_year'] ) ? $_REQUEST['_famiau_max_year'] : '',
			'_famiau_make'         => isset( $_REQUEST['_famiau_make'] ) ? $_REQUEST['_famiau_make'] : '',
			'_famiau_model'        => isset( $_REQUEST['_famiau_model'] ) ? $_REQUEST['_famiau_model'] : '',
			'max_price'            => isset( $_REQUEST['max_price'] ) ? $_REQUEST['max_price'] : 0,
			'all_fuel_types'       => isset( $_REQUEST['all_fuel_types'] ) ? $_REQUEST['all_fuel_types'] : '',
			'all_car_bodies'       => isset( $_REQUEST['all_car_bodies'] ) ? $_REQUEST['all_car_bodies'] : '',
			'all_drives'           => isset( $_REQUEST['all_drives'] ) ? $_REQUEST['all_drives'] : '',
			'all_exterior_colors'  => isset( $_REQUEST['all_exterior_colors'] ) ? $_REQUEST['all_exterior_colors'] : '',
			'all_interior_colors'  => isset( $_REQUEST['all_interior_colors'] ) ? $_REQUEST['all_interior_colors'] : '',
			'_famiau_gearbox_type' => isset( $_REQUEST['_famiau_gearbox_type'] ) ? $_REQUEST['_famiau_gearbox_type'] : '',
		);
		
		$post_parameters_natural = array(
			'_famiau_car_status'   => isset( $_REQUEST['car_status'] ) ? $_REQUEST['car_status'] : $post_parameters['_famiau_car_status'],
			'_famiau_min_year'     => isset( $_REQUEST['min_year'] ) ? $_REQUEST['min_year'] : $post_parameters['_famiau_min_year'],
			'_famiau_max_year'     => isset( $_REQUEST['max_year'] ) ? $_REQUEST['max_year'] : $post_parameters['_famiau_max_year'],
			'_famiau_make'         => isset( $_REQUEST['make'] ) ? $_REQUEST['make'] : $post_parameters['_famiau_make'],
			'_famiau_model'        => isset( $_REQUEST['model'] ) ? $_REQUEST['model'] : $post_parameters['_famiau_model'],
			'max_price'            => $post_parameters['max_price'],
			'all_fuel_types'       => isset( $_REQUEST['fuel'] ) ? $_REQUEST['fuel'] : $post_parameters['all_fuel_types'],
			'all_car_bodies'       => isset( $_REQUEST['car_body'] ) ? $_REQUEST['car_body'] : $post_parameters['all_car_bodies'],
			'all_drives'           => isset( $_REQUEST['drive'] ) ? $_REQUEST['drive'] : $post_parameters['all_drives'],
			'all_exterior_colors'  => isset( $_REQUEST['exterior_color'] ) ? $_REQUEST['exterior_color'] : $post_parameters['all_exterior_colors'],
			'all_interior_colors'  => isset( $_REQUEST['interior_color'] ) ? $_REQUEST['interior_color'] : $post_parameters['all_interior_colors'],
			'_famiau_gearbox_type' => isset( $_REQUEST['gearbox'] ) ? $_REQUEST['gearbox'] : $post_parameters['_famiau_gearbox_type'],
		);
		
		$post_parameters = $post_parameters_natural;
		
		$html = famiau_mega_filter_html( $args, $post_parameters, false );
		$html = apply_filters( 'famiau_mega_filter_frontend_html', $html, $args );
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_mega_filter_html' ) ) {
	function famiau_mega_filter_html( $args = array(), $selected_parameters = array(), $echo = true ) {
		global $famiau;
		
		$args_default = array(
			'user_filter'             => false,
			'listing_status_filter'   => false,
			'sub_split_num'           => 9999, // threshold to split to sub menu new menu
			'min_year'                => 0,
			'max_year'                => 0,
			'price_step'              => 1, // 5000
			'show_price_filter_input' => false,
			'instant_filter'          => 'no',
			'class'                   => ''
		);
		
		$args = wp_parse_args( $args, $args_default );
		extract( $args );
		
		$html                        = '';
		$user_select_html            = '';
		$listing_status_select_html  = '';
		$makes_and_models_html       = '';
		$car_statuses_html           = '';
		$years_html                  = '';
		$transmission_html           = ''; // gearbox_type
		$price_range_html            = '';
		$fuel_types_html             = '';
		$exterior_colors_html        = '';
		$interior_colors_html        = '';
		$bodies_html                 = '';
		$drives_html                 = '';
		$seats_html                  = '';
		$comforts_html               = '';
		$entertainments_html         = '';
		$safety_html                 = '';
		$windows_html                = '';
		$have_imgs_html              = '';
		$filter_btn_html             = '';
		$clear_filter_btn_html       = '';
		$expand_collapse_filter_html = '<div class="famiau-exp-collapse-filter-wrap"><a href="#" class="famiau-exp-collapse-filter famiau-is-filter-collapse">' . esc_html__( 'Expand Search [+]', 'famiau' ) . '</a></div>';
		
		$filter_wrap_class = $class;
		$sub_split_num     = max( 1, intval( $sub_split_num ) );
		$instant_filter    = $instant_filter == 'yes';
		if ( $instant_filter ) {
			$filter_wrap_class .= ' has-instant-filter';
		}
		
		// User select
		if ( $user_filter ) {
			$user_select_html = famiau_users_select_html( '', 'famiau-filter-select', 'famiau_user_filter', 'famiau_user_filter', false );
			$user_select_html = '<div class="famiau-filter-group">' .
			                    '<div class="famiau-filter-box-left"><label>' . esc_html__( 'User', 'famiau' ) . '</label></div>' .
			                    '<div class="famiau-filter-box-right">' .
			                    '<div class="famiau-criteria-filtering">' .
			                    '<input type="hidden" class="famiau-filter-item famiau-active-filter famiau-filter-hidden famiau-author-filter-hidden" data-filter_key="author" data-filter_val="" value="" />' .
			                    $user_select_html .
			                    '</div>' .
			                    '</div>' .
			                    '</div>';
		}
		
		// Listing status select
		$all_statuses = array(
			''         => esc_html__( 'Select Status', 'famiau' ),
			'waiting'  => esc_html__( 'Reviewing', 'famiau' ),
			'approved' => esc_html__( 'Approved', 'famiau' ),
			'deleted'  => esc_html__( 'Deleted', 'famiau' ),
		);
		if ( $listing_status_filter ) {
			$selected                   = isset( $selected_parameters['_famiau_car_status'] ) ? $selected_parameters['_famiau_car_status'] : '';
			$listing_status_select_html .= famiau_select_html( $all_statuses, $selected, 'famiau-filter-select famiau-listing-status-select', 'famiau_listing_status_filter', 'famiau_listing_status_filter', false );
			$listing_status_select_html = '<div class="famiau-filter-group">' .
			                              '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Listing Status', 'famiau' ) . '</label></div>' .
			                              '<div class="famiau-filter-box-right">' .
			                              '<div class="famiau-criteria-filtering">' .
			                              '<input type="hidden" class="famiau-filter-item famiau-active-filter famiau-filter-hidden famiau-listing-status-filter-hidden" data-filter_key="listing_status" data-filter_val="" value="" />' .
			                              $listing_status_select_html .
			                              '</div>' .
			                              '</div>' .
			                              '</div>';
		}
		
		// Makes and models
		$all_makes = $famiau['all_makes'];
		if ( ! empty( $all_makes ) ) {
			$selected_make = isset( $selected_parameters['_famiau_make'] ) ? $selected_parameters['_famiau_make'] : '';
			foreach ( $all_makes as $make ) {
				$menu_item_class = 'famiau-filtter-menu-item famiau-menu-item famiau-menu-item-make';
				$models          = $make['models'];
				$models_html     = '';
				$total_models    = count( $models );
				$i               = 0;
				if ( ! empty( $models ) ) {
					$selected_model  = isset( $selected_parameters['_famiau_model'] ) ? $selected_parameters['_famiau_model'] : '';
					$menu_item_class .= ' parent menu-item-has-children menu-item-has-sub-menu';
					$models_html     .= '<div class="famiau-sub-nav">';
					$models_html     .= '<ul class="famiau-sub-menu famiau-criteria-filtering">';
					foreach ( $models as $model ) {
						$i ++;
						$this_class  = $selected_model == $model ? 'famiau-active-filter' : '';
						$models_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-model"><a data-filter_key="model" data-filter_val="' . esc_attr( $model ) . '" href="#" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_attr( $model ) . '</a></li>';
						if ( $i % $sub_split_num == 0 && $i < $total_models ) {
							$models_html .= '</ul>';
							$models_html .= '<ul class="famiau-sub-menu famiau-criteria-filtering">';
						}
					}
					$models_html .= '</ul>';
					$models_html .= '</div>';
				}
				
				$this_class            = $selected_make == $make['make'] ? 'famiau-active-filter' : '';
				$makes_and_models_html .= '<li class="' . esc_attr( $menu_item_class ) . '">';
				$makes_and_models_html .= '<a href="#" data-filter_key="make" data-filter_val="' . esc_attr( $make['make'] ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_attr( $make['make'] ) . '</a>';
				$makes_and_models_html .= $models_html;
				$makes_and_models_html .= '</li>';
			}
			$makes_and_models_html = '<div class="famiau-filter-group">' .
			                         '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Make', 'famiau' ) . '</label></div>' .
			                         '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $makes_and_models_html . '</ul></div>' .
			                         '</div>';
		}
		
		// Car statuses
		$car_statuses = array(
			''               => esc_html__( 'All', 'famiau' ),
			'new'            => esc_html__( 'New', 'famiau' ),
			'used'           => esc_html__( 'Used', 'famiau' ),
			'certified-used' => esc_html__( 'Certified Used', 'famiau' )
		);
		
		$selected_car_status = isset( $selected_parameters['_famiau_car_status'] ) ? $selected_parameters['_famiau_car_status'] : '';
		foreach ( $car_statuses as $car_status_val => $car_status ) {
			$this_class        = $selected_car_status == $car_status_val ? 'famiau-active-filter' : '';
			$car_statuses_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-car_status">';
			$car_statuses_html .= '<a href="#" data-filter_key="car_status" data-filter_val="' . esc_attr( $car_status_val ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $car_status ) . '</a>';
			$car_statuses_html .= '</li>';
		}
		$car_statuses_html = '<div class="famiau-filter-group">' .
		                     '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Condition', 'famiau' ) . '</label></div>' .
		                     '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $car_statuses_html . '</ul></div>' .
		                     '</div>';
		
		// Years
		$this_year = intval( date( 'Y' ) );
		$min_year  = intval( $min_year ) <= 0 ? intval( $famiau['_famiau_min_year'] ) : 1700;
		$max_year  = intval( $max_year ) <= 0 ? intval( $famiau['_famiau_max_year'] ) : $this_year;
		if ( $max_year < $min_year ) {
			$max_year = $min_year;
		}
		if ( $max_year > $this_year ) {
			$max_year = $this_year;
		}
		
		$years_from_first_option = '<option value="0">' . esc_html__( 'From Year', 'famiau' ) . '</option>';
		$years_to_first_option   = '<option value="' . $this_year . '">' . esc_html__( 'To Year', 'famiau' ) . '</option>';
		$selected_min_year           = isset( $selected_parameters['_famiau_min_year'] ) ? $selected_parameters['_famiau_min_year'] : '';
		$selected_max_year           = isset( $selected_parameters['_famiau_max_year'] ) ? $selected_parameters['_famiau_max_year'] : '';
		// $selected_year           = isset( $selected_parameters['_famiau_year'] ) ? $selected_parameters['_famiau_year'] : '';
		$option_min_years_html = '';
		$option_max_years_html = '';
		for ( $y = $min_year; $y <= $max_year; $y ++ ) {
			$option_min_years_html .= '<option ' . selected( true, $selected_min_year == $y, false ) . ' value="' . $y . '">' . $y . '</option>';
			$option_max_years_html .= '<option ' . selected( true, $selected_max_year == $y, false ) . ' value="' . $y . '">' . $y . '</option>';
			// $years_html .= '<option ' . selected( true, $selected_year == $y, false ) . ' value="' . $y . '">' . $y . '</option>';
		}
		if ( $option_min_years_html != '' && $option_max_years_html != '' ) {
			$years_html = '<select class="famiau-filter-item famiau-filter-by-year famiau-filter-from-year famisp-select-num-min" data-filter_key="from_year" data-filter_val="' . esc_attr( $min_year ) . '">' . $years_from_first_option . $option_min_years_html . '</select>' .
			              '<select class="famiau-filter-item famiau-filter-by-year famiau-filter-to-year famisp-select-num-max" data-filter_key="to_year" data-filter_val="' . esc_attr( $max_year ) . '">' . $years_to_first_option . $option_max_years_html . '</select>';
			$years_html = '<div class="famiau-filter-box famiau-filter-box-years famiau-select-min-max-group"><div class="famiau-select-group-inner">' . $years_html . '</div></div>';
			
			$years_html = '<div class="famiau-filter-group">' .
			              '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Year', 'famiau' ) . '</label></div>' .
			              '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $years_html . '</ul></div>' .
			              '</div>';
		}
		
		// Transmission
		$gearboxes        = array(
			''               => esc_html__( 'All', 'famiau' ),
			'manual'         => esc_html__( 'Manual', 'famiau' ),
			'auto'           => esc_html__( 'Automatic', 'famiau' ),
			'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
		);
		$selected_gearbox = isset( $selected_parameters['_famiau_gearbox_type'] ) ? $selected_parameters['_famiau_gearbox_type'] : '';
		foreach ( $gearboxes as $gearbox_val => $gearbox ) {
			$this_class        = $selected_gearbox == $gearbox_val ? 'famiau-active-filter' : '';
			$transmission_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-gearbox">';
			$transmission_html .= '<a href="#" data-filter_key="gearbox" data-filter_val="' . esc_attr( $gearbox_val ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $gearbox ) . '</a>';
			$transmission_html .= '</li>';
		}
		$transmission_html = '<div class="famiau-filter-group">' .
		                     '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Transmission', 'famiau' ) . '</label></div>' .
		                     '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $transmission_html . '</ul></div>' .
		                     '</div>';
		
		// Price range
		$selected_max_price = isset( $selected_parameters['max_price'] ) ? intval( $selected_parameters['max_price'] ) : 0;
		// $default_max_price = $selected_max_price > 0 ? $selected_max_price: 0;
		$prices_range = famiau_get_prices_range();
		$price_step   = intval( $price_step );
		if ( $price_step > $prices_range['max_price'] ) {
			$price_step = $prices_range['max_price'];
		}
		if ( $price_step <= 0 ) {
			$price_step = 1;
		}
		$first_range_num = $price_step;
		if ( $price_step > 0 && $first_range_num != $price_step ) {
			$i = 1;
			while ( $first_range_num < $prices_range['min_price'] && $first_range_num < $prices_range['max_price'] ) {
				$first_range_num = $price_step * $i;
				$i ++;
			}
		}
		$values           = array( $prices_range['min_price'], $prices_range['max_price'] );
		$price_input_html = '';
		if ( $show_price_filter_input ) {
			$price_input_html .= '<div class="famiau-slider-range-input-wrap">';
			$price_input_html .= '<input type="number" min="' . esc_attr( $prices_range['min_price'] ) . '" max="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['min_price'] ) . '" class="famiau-slider-range-input famiau-slider-range-input-min" />';
			$price_input_html .= '<input type="number" min="' . esc_attr( $prices_range['min_price'] ) . '" max="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['max_price'] ) . '" class="famiau-slider-range-input famiau-slider-range-input-max" />';
			$price_input_html .= '</div>';
		}
		$price_range_html .= '<div class="famiau-slider-range-wrap">';
		$price_range_html .= $price_input_html;
		$price_range_html .= '<input type="hidden" data-min="' . esc_attr( $prices_range['min_price'] ) . '" class="famiau-min-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="min_price" data-filter_val="' . esc_attr( $prices_range['min_price'] ) . '" value="' . esc_attr( $prices_range['min_price'] ) . '" />';
		$price_range_html .= '<input type="hidden" data-max="' . esc_attr( $prices_range['max_price'] ) . '" class="famiau-max-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="max_price" data-filter_val="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['max_price'] ) . '" />';
		$price_range_html .= '<div class="famiau-slider-range" data-range="yes" data-step="' . esc_attr( $price_step ) . '" data-min="' . esc_attr( $prices_range['min_price'] ) . '" data-max="' . esc_attr( $prices_range['max_price'] ) . '" data-selected_max="' . $selected_max_price . '" data-values="' . htmlentities2( json_encode( $values ) ) . '"></div>';
		// $price_range_html .= '<div class="famiau-slider-range-min-max"></div>';
		$price_range_html .= '</div>';
		
		$price_range_html = '<div class="famiau-filter-group">' .
		                    '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Price', 'famiau' ) . '</label></div>' .
		                    '<div class="famiau-filter-box-right">' . $price_range_html . '</div>' .
		                    '</div>';
		
		// Fuel type
		$all_fuel_types = $famiau['all_fuel_types'];
		if ( ! empty( $all_fuel_types ) ) {
			$selected_fuel_type = isset( $selected_parameters['all_fuel_types'] ) ? $selected_parameters['all_fuel_types'] : '';
			$fuel_types_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-fuel_type">';
			$fuel_types_html    .= '<a href="#" data-filter_key="fuel" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$fuel_types_html    .= '</li>';
			foreach ( $all_fuel_types as $fuel_type ) {
				$this_class      = $selected_fuel_type == $fuel_type ? 'famiau-active-filter' : '';
				$fuel_types_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-fuel_type">';
				$fuel_types_html .= '<a href="#" data-filter_key="fuel" data-filter_val="' . esc_attr( $fuel_type ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $fuel_type ) . '</a>';
				$fuel_types_html .= '</li>';
			}
			$fuel_types_html = '<div class="famiau-filter-group">' .
			                   '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Fuel', 'famiau' ) . '</label></div>' .
			                   '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $fuel_types_html . '</ul></div>' .
			                   '</div>';
		}
		
		// Exterior colors
		$all_exterior_colors = $famiau['all_exterior_colors'];
		if ( ! empty( $all_exterior_colors ) ) {
			$selected_exterior_color = isset( $selected_parameters['all_exterior_colors'] ) ? $selected_parameters['all_exterior_colors'] : '';
			$exterior_colors_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-exterior_color">';
			$exterior_colors_html    .= '<a href="#" data-filter_key="exterior_color" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$exterior_colors_html    .= '</li>';
			foreach ( $all_exterior_colors as $exterior_color ) {
				$this_class           = $selected_exterior_color == $exterior_color ? 'famiau-active-filter' : '';
				$exterior_colors_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-exterior_color">';
				$exterior_colors_html .= '<a href="#" data-filter_key="exterior_color" data-filter_val="' . esc_attr( $exterior_color ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $exterior_color ) . '</a>';
				$exterior_colors_html .= '</li>';
			}
			$exterior_colors_html = '<div class="famiau-filter-group">' .
			                        '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Exterior Color', 'famiau' ) . '</label></div>' .
			                        '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $exterior_colors_html . '</ul></div>' .
			                        '</div>';
		}
		
		// Interior colors
		$all_interior_colors = $famiau['all_interior_colors'];
		if ( ! empty( $all_interior_colors ) ) {
			$selected_interior_color = isset( $selected_parameters['all_interior_colors'] ) ? $selected_parameters['all_interior_colors'] : '';
			$interior_colors_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-interior_color">';
			$interior_colors_html    .= '<a href="#" data-filter_key="interior_color" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$interior_colors_html    .= '</li>';
			foreach ( $all_interior_colors as $interior_color ) {
				$this_class           = $selected_interior_color == $interior_color ? 'famiau-active-filter' : '';
				$interior_colors_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-interior_color">';
				$interior_colors_html .= '<a href="#" data-filter_key="interior_color" data-filter_val="' . esc_attr( $interior_color ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $interior_color ) . '</a>';
				$interior_colors_html .= '</li>';
			}
			$interior_colors_html = '<div class="famiau-filter-group">' .
			                        '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Interior Color', 'famiau' ) . '</label></div>' .
			                        '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $interior_colors_html . '</ul></div>' .
			                        '</div>';
		}
		
		// Bodies
		$all_car_bodies = $famiau['all_car_bodies'];
		if ( ! empty( $all_car_bodies ) ) {
			$selected_car_body = isset( $selected_parameters['all_car_bodies'] ) ? $selected_parameters['all_car_bodies'] : '';
			$bodies_html       .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-car_body">';
			$bodies_html       .= '<a href="#" data-filter_key="car_body" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$bodies_html       .= '</li>';
			foreach ( $all_car_bodies as $car_body ) {
				$this_class  = $selected_car_body == $car_body ? 'famiau-active-filter' : '';
				$bodies_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-car_body">';
				$bodies_html .= '<a href="#" data-filter_key="car_body" data-filter_val="' . esc_attr( $car_body ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $car_body ) . '</a>';
				$bodies_html .= '</li>';
			}
			$bodies_html = '<div class="famiau-filter-group">' .
			               '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Car Body', 'famiau' ) . '</label></div>' .
			               '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $bodies_html . '</ul></div>' .
			               '</div>';
		}
		
		// Drives
		$all_drives = $famiau['all_drives'];
		if ( ! empty( $all_drives ) ) {
			$selected_drive = isset( $selected_parameters['all_drives'] ) ? $selected_parameters['all_drives'] : '';
			$drives_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-drive">';
			$drives_html    .= '<a href="#" data-filter_key="drive" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$drives_html    .= '</li>';
			foreach ( $all_drives as $drive ) {
				$this_class  = $selected_drive == $drive ? 'famiau-active-filter' : '';
				$drives_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-drive">';
				$drives_html .= '<a href="#" data-filter_key="drive" data-filter_val="' . esc_attr( $drive ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $drive ) . '</a>';
				$drives_html .= '</li>';
			}
			$drives_html = '<div class="famiau-filter-group">' .
			               '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Drive', 'famiau' ) . '</label></div>' .
			               '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $drives_html . '</ul></div>' .
			               '</div>';
		}
		
		// Seats
		$all_seats = isset( $famiau['all_car_features_seats'] ) ? $famiau['all_car_features_seats'] : array();
		if ( ! empty( $all_seats ) ) {
			$selected_seat = isset( $selected_parameters['all_car_features_seats'] ) ? $selected_parameters['all_car_features_seats'] : '';
			$seats_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-seat">';
			$seats_html    .= '<a href="#" data-filter_key="seat" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$seats_html    .= '</li>';
			foreach ( $all_seats as $seat ) {
				$this_class = $selected_seat == $seat ? 'famiau-active-filter' : '';
				$seats_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-seat">';
				$seats_html .= '<a href="#" data-filter_key="seat" data-filter_val="' . esc_attr( $seat ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $seat ) . '</a>';
				$seats_html .= '</li>';
			}
			$seats_html = '<div class="famiau-filter-group">' .
			              '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Seat', 'famiau' ) . '</label></div>' .
			              '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $seats_html . '</ul></div>' .
			              '</div>';
		}
		
		// Comforts
		$all_comforts = isset( $famiau['all_car_features_comforts'] ) ? $famiau['all_car_features_comforts'] : array();
		if ( ! empty( $all_comforts ) ) {
			$selected_comfort = isset( $selected_parameters['all_car_features_comforts'] ) ? $selected_parameters['all_car_features_comforts'] : '';
			$comforts_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-comfort">';
			$comforts_html    .= '<a href="#" data-filter_key="comfort" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$comforts_html    .= '</li>';
			foreach ( $all_comforts as $comfort ) {
				$this_class    = $selected_comfort == $comfort ? 'famiau-active-filter' : '';
				$comforts_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-comfort">';
				$comforts_html .= '<a href="#" data-filter_key="comfort" data-filter_val="' . esc_attr( $comfort ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $comfort ) . '</a>';
				$comforts_html .= '</li>';
			}
			$comforts_html = '<div class="famiau-filter-group">' .
			                 '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Comfort', 'famiau' ) . '</label></div>' .
			                 '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $comforts_html . '</ul></div>' .
			                 '</div>';
		}
		
		// Entertainments
		$all_entertainments = isset( $famiau['all_car_features_entertainments'] ) ? $famiau['all_car_features_entertainments'] : array();
		if ( ! empty( $all_entertainments ) ) {
			$selected_entertainment = isset( $selected_parameters['all_car_features_entertainments'] ) ? $selected_parameters['all_car_features_entertainments'] : '';
			$entertainments_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-entertainment">';
			$entertainments_html    .= '<a href="#" data-filter_key="entertainment" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$entertainments_html    .= '</li>';
			foreach ( $all_entertainments as $entertainment ) {
				$this_class          = $selected_entertainment == $entertainment ? 'famiau-active-filter' : '';
				$entertainments_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-entertainment">';
				$entertainments_html .= '<a href="#" data-filter_key="entertainment" data-filter_val="' . esc_attr( $entertainment ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $entertainment ) . '</a>';
				$entertainments_html .= '</li>';
			}
			$entertainments_html = '<div class="famiau-filter-group">' .
			                       '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Entertainments', 'famiau' ) . '</label></div>' .
			                       '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $entertainments_html . '</ul></div>' .
			                       '</div>';
		}
		
		// Safety
		$all_safety = isset( $famiau['all_car_features_safety'] ) ? $famiau['all_car_features_safety'] : array();
		if ( ! empty( $all_safety ) ) {
			$selected_safety = isset( $selected_parameters['all_car_features_safety'] ) ? $selected_parameters['all_car_features_safety'] : '';
			$safety_html     .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-safety">';
			$safety_html     .= '<a href="#" data-filter_key="safety" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$safety_html     .= '</li>';
			foreach ( $all_safety as $safety ) {
				$this_class  = $selected_safety == $safety ? 'famiau-active-filter' : '';
				$safety_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-safety">';
				$safety_html .= '<a href="#" data-filter_key="safety" data-filter_val="' . esc_attr( $safety ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $safety ) . '</a>';
				$safety_html .= '</li>';
			}
			$safety_html = '<div class="famiau-filter-group">' .
			               '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Safety', 'famiau' ) . '</label></div>' .
			               '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $safety_html . '</ul></div>' .
			               '</div>';
		}
		
		// Windows
		$all_windows = isset( $famiau['all_car_features_windows'] ) ? $famiau['all_car_features_windows'] : array();
		if ( ! empty( $all_windows ) ) {
			$selected_window = isset( $selected_parameters['all_car_features_windows'] ) ? $selected_parameters['all_car_features_windows'] : '';
			$windows_html    .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-window">';
			$windows_html    .= '<a href="#" data-filter_key="window" data-filter_val="" class="famiau-filter-item">' . esc_html__( 'All', 'famiau' ) . '</a>';
			$windows_html    .= '</li>';
			foreach ( $all_windows as $window ) {
				$this_class   = $selected_window == $window ? 'famiau-active-filter' : '';
				$windows_html .= '<li class="famiau-filtter-menu-item famiau-menu-item famiau-menu-item-window">';
				$windows_html .= '<a href="#" data-filter_key="window" data-filter_val="' . esc_attr( $window ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">' . esc_html( $window ) . '</a>';
				$windows_html .= '</li>';
			}
			$windows_html = '<div class="famiau-filter-group">' .
			                '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Windows', 'famiau' ) . '</label></div>' .
			                '<div class="famiau-filter-box-right"><ul class="famiau-menu famiau-filter-menu famiau-criteria-filtering">' . $windows_html . '</ul></div>' .
			                '</div>';
		}
		
		// Have images
		$have_imgs_html .= '<label class="famiau-switch famiau-criteria-filtering">
                                <input type="hidden" data-filter_key="have_imgs" data-filter_val="no" class="famiau-filter-item famiau-active-filter"
                                       value="no">
                                <input class="famiau-filter-cb" type="checkbox">
                                <span class="famiau-slider round"></span>
                            </label>';
		$have_imgs_html = '<div class="famiau-filter-group">' .
		                  '<div class="famiau-filter-box-left"><label>' . esc_html__( 'Have Images', 'famiau' ) . '</label></div>' .
		                  '<div class="famiau-filter-box-right">' . $have_imgs_html . '</div>' .
		                  '</div>';
		
		// Filter button
		if ( ! $instant_filter ) {
			$filter_btn_html = '<div class="famiau-filter-group"><button class="famiau-button-primary famiau-mega-filter-submit-btn">' . esc_html__( 'Find Cars', 'famiau' ) . '</button></div>';
		}
		
		$clear_filter_btn_html .= '<div class="famiau-clear-filter-wrap famiau-hidden" style="display: none;"><button class="famiau-button-primary famiau-mega-filter-clear-btn">' . esc_html__( 'Clear Filter', 'famiau' ) . '</button></div>';
		
		// Html
		$html .= '<div class="famiau-mega-filter-wrap ' . esc_attr( $filter_wrap_class ) . '">';
		$html .= '<div class="famiau-mega-filter-inner">';
		$html .= $user_select_html . $listing_status_select_html;
		$html .= $makes_and_models_html . $car_statuses_html . $years_html . $transmission_html . $price_range_html;
		$html .= '<div class="famiau-ext-filter-wrap" style="display: none;">';
		$html .= $fuel_types_html . $exterior_colors_html . $interior_colors_html;
		$html .= $bodies_html . $drives_html . $seats_html . $comforts_html . $entertainments_html . $safety_html . $windows_html . $have_imgs_html;
		$html .= '</div>';
		$html .= $expand_collapse_filter_html;
		$html .= '</div>';
		$html .= $filter_btn_html;
		$html .= $clear_filter_btn_html;
		$html .= '</div>';
		
		$html = apply_filters( 'famiau_mega_filter_html', $html, $args );
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_mobile_mega_filter_html' ) ) {
	function famiau_mobile_mega_filter_html( $args = array(), $selected_parameters = array(), $echo = true ) {
		global $famiau;
		// $enable_instant_filter = famiau_get_option( '_famiau_enable_instant_filter', 'no' );
		
		$args_default = array(
			'listing_status_filter'   => false,
			'sub_split_num'           => 9999, // threshold to split to sub menu new menu
			'min_year'                => 0,
			'max_year'                => 0,
			'price_step'              => 1, // 5000
			'show_price_filter_input' => false,
			'instant_filter'          => 'no',
			'class'                   => ''
		);
		
		// Always disable instant filter on mobile filter
		if ( ! isset( $args['instant_filter'] ) ) {
			$args['instant_filter'] = 'no'; // $enable_instant_filter;
		}
		
		$args = wp_parse_args( $args, $args_default );
		extract( $args );
		
		$html                        = '';
		$user_select_html            = '';
		$listing_status_select_html  = '';
		$makes_and_models_html       = '';
		$car_statuses_html           = '';
		$years_html                  = '';
		$transmission_html           = ''; // gearbox_type
		$engine_range_html           = '';
		$price_range_html            = '';
		$fuel_types_html             = '';
		$exterior_colors_html        = '';
		$interior_colors_html        = '';
		$bodies_html                 = '';
		$drives_html                 = '';
		$seats_html                  = '';
		$comforts_html               = '';
		$entertainments_html         = '';
		$safety_html                 = '';
		$windows_html                = '';
		$have_imgs_html              = '';
		$filter_btn_html             = '';
		$clear_filter_btn_html       = '';
		$expand_collapse_filter_html = '<div class="famiau-exp-collapse-filter-wrap"><a href="#" class="famiau-exp-collapse-filter famiau-is-filter-collapse">' . esc_html__( 'Expand Search [+]', 'famiau' ) . '</a></div>';
		
		$filter_wrap_class = $class;
		$sub_split_num     = max( 1, intval( $sub_split_num ) );
		$instant_filter    = $instant_filter == 'yes';
		if ( $instant_filter ) {
			$filter_wrap_class .= ' has-instant-filter';
		}
		
		// Makes and models
		$all_makes = $famiau['all_makes'];
		if ( ! empty( $all_makes ) ) {
			$id                    = uniqid( 'make-' );
			$model_id              = uniqid( 'model-' );
			$selected_make         = isset( $selected_parameters['_famiau_make'] ) ? $selected_parameters['_famiau_make'] : '';
			$makes_and_models_html = '<div class="famiau-select-popup" data-depends_id="' . esc_attr( $model_id ) . '" data-depends_key="model" data-popup_text="' . esc_attr__( 'Make', 'famiau' ) . '" data-depends_text="' . esc_attr__( 'Model', 'famiau' ) . '" data-has_depends="yes" data-key_0="make" data-key_1="models" data-popup_data="' . htmlentities2( json_encode( $all_makes ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$makes_and_models_html = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_make ) . '" data-filter_key="make" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                         '<div class="part-left"><label>' . esc_html__( 'Make', 'famiau' ) . '</label></div>' .
			                         '<div class="part-right">' . $makes_and_models_html . '</div>' .
			                         '</div>';
		}
		
		// Price range
		$selected_max_price = isset( $selected_parameters['max_price'] ) ? intval( $selected_parameters['max_price'] ) : 0;
		// $default_max_price = $selected_max_price > 0 ? $selected_max_price: 0;
		$prices_range = famiau_get_prices_range();
		$price_step   = intval( $price_step );
		if ( $price_step > $prices_range['max_price'] ) {
			$price_step = $prices_range['max_price'];
		}
		if ( $price_step <= 0 ) {
			$price_step = 1;
		}
		$first_range_num = $price_step;
		if ( $price_step > 0 && $first_range_num != $price_step ) {
			$i = 1;
			while ( $first_range_num < $prices_range['min_price'] && $first_range_num < $prices_range['max_price'] ) {
				$first_range_num = $price_step * $i;
				$i ++;
			}
		}
		$values           = array( $prices_range['min_price'], $prices_range['max_price'] );
		$price_input_html = '';
		if ( $show_price_filter_input ) {
			$price_input_html .= '<div class="famiau-slider-range-input-wrap">';
			$price_input_html .= '<input type="number" min="' . esc_attr( $prices_range['min_price'] ) . '" max="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['min_price'] ) . '" class="famiau-slider-range-input famiau-slider-range-input-min" />';
			$price_input_html .= '<input type="number" min="' . esc_attr( $prices_range['min_price'] ) . '" max="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['max_price'] ) . '" class="famiau-slider-range-input famiau-slider-range-input-max" />';
			$price_input_html .= '</div>';
		}
		$price_range_html .= '<div class="famiau-slider-range-wrap">';
		$price_range_html .= $price_input_html;
		$price_range_html .= '<input type="hidden" data-min="' . esc_attr( $prices_range['min_price'] ) . '" class="famiau-min-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="min_price" data-filter_val="' . esc_attr( $prices_range['min_price'] ) . '" value="' . esc_attr( $prices_range['min_price'] ) . '" />';
		$price_range_html .= '<input type="hidden" data-max="' . esc_attr( $prices_range['max_price'] ) . '" class="famiau-max-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="max_price" data-filter_val="' . esc_attr( $prices_range['max_price'] ) . '" value="' . esc_attr( $prices_range['max_price'] ) . '" />';
		$price_range_html .= '<div class="famiau-slider-range" data-range="yes" data-step="' . esc_attr( $price_step ) . '" data-min="' . esc_attr( $prices_range['min_price'] ) . '" data-max="' . esc_attr( $prices_range['max_price'] ) . '" data-selected_max="' . $selected_max_price . '" data-values="' . htmlentities2( json_encode( $values ) ) . '"></div>';
		$price_range_html .= '</div>';
		
		$id               = uniqid( 'price-' );
		$display_format   = array(
			'min'           => esc_html__( 'From {min}', 'famiau' ),
			'max'           => esc_html__( 'Less than {max}', 'famiau' ),
			'between'       => esc_html__( 'From {from} to {to}', 'famiau' ),
			'default'       => '',
			'number_format' => famiau_price_format(),
			'thousand_sep'  => famiau_get_price_thousand_separator(),
			'decimal_sep'   => famiau_get_price_decimal_separator(),
			'decimals'      => famiau_get_price_decimals(), // tofixed
			'symbol'        => famiau_get_currency_symbol()
		);
		$price_range_html = '<div data-filter_type="slider" data-display_format="' . esc_attr( wp_json_encode( $display_format ) ) . '" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group">' .
		                    '<div class="part-left"><label>' . esc_html__( 'Price', 'famiau' ) . '</label> <span class="famiau-slider-desc-text"></span></div>' .
		                    '<div class="part-right">' . $price_range_html . '</div>' .
		                    '</div>';
		
		// Engine range
		$values            = array( 0, 10 );
		$engine_input_html = '';
		$engine_input_html .= '<div class="famiau-slider-range-input-wrap">';
		$engine_input_html .= '<input type="number" min="0" max="10" value="0" class="famiau-slider-range-input famiau-slider-range-input-min" />';
		$engine_input_html .= '<input type="number" min="0" max="10" value="10" class="famiau-slider-range-input famiau-slider-range-input-max" />';
		$engine_input_html .= '</div>';
		
		$engine_range_html .= '<div class="famiau-slider-range-wrap">';
		$engine_range_html .= $engine_input_html;
		$engine_range_html .= '<input type="hidden" data-min="0" class="famiau-min-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="min_engine" data-filter_val="0" value="0" />';
		$engine_range_html .= '<input type="hidden" data-max="10" class="famiau-max-range-hidden famiau-filter-item famiau-slider-range-hidden famiau-active-filter" data-filter_key="max_engine" data-filter_val="10" value="10" />';
		$engine_range_html .= '<div class="famiau-slider-range" data-range="yes" data-step="0.1" data-min="0" data-max="10" data-selected_max="10" data-values="' . htmlentities2( json_encode( $values ) ) . '"></div>';
		$engine_range_html .= '</div>';
		
		$id                = uniqid( 'engine-' );
		$display_format    = array(
			'min'           => esc_html__( 'From {min}', 'famiau' ),
			'max'           => esc_html__( 'Less than {max}', 'famiau' ),
			'between'       => esc_html__( 'From {from} to {to}', 'famiau' ),
			'default'       => '',
			'number_format' => '%2$s%1$s', // Symbol right
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'decimals'      => 1, // tofixed
			'symbol'        => esc_html__( 'L', 'famiau' )
		);
		$engine_range_html = '<div data-filter_type="slider" data-display_format="' . esc_attr( wp_json_encode( $display_format ) ) . '" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group">' .
		                     '<div class="part-left"><label>' . esc_html__( 'Engine', 'famiau' ) . '</label> <span class="famiau-slider-desc-text"></span></div>' .
		                     '<div class="part-right">' . $engine_range_html . '</div>' .
		                     '</div>';
		
		// Car statuses
		$car_statuses = array(
			'new'            => esc_html__( 'New', 'famiau' ),
			'used'           => esc_html__( 'Used', 'famiau' ),
			'certified-used' => esc_html__( 'Certified Used', 'famiau' )
		);
		
		$selected_car_status = isset( $selected_parameters['_famiau_car_status'] ) ? $selected_parameters['_famiau_car_status'] : '';
		foreach ( $car_statuses as $car_status_val => $car_status ) {
			$this_class        = $selected_car_status == $car_status_val ? 'famiau-active-filter' : '';
			$car_statuses_html .= '<button data-filter_key="car_status" data-filter_val="' . esc_attr( $car_status_val ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">';
			$car_statuses_html .= esc_html( $car_status );
			$car_statuses_html .= '</button>';
		}
		
		$id                = uniqid( 'car_status-' );
		$car_statuses_html = '<div data-filter_type="button" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group">' .
		                     '<div class="part-left"><label>' . esc_html__( 'Condition', 'famiau' ) . '</label></div>' .
		                     '<div class="part-right"><div class="famiau-group-buttons famiau-criteria-filtering">' . $car_statuses_html . '</div></div>' .
		                     '</div>';
		
		// Transmission
		$gearboxes        = array(
			'manual'         => esc_html__( 'Manual', 'famiau' ),
			'auto'           => esc_html__( 'Automatic', 'famiau' ),
			'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
		);
		$selected_gearbox = isset( $selected_parameters['_famiau_gearbox_type'] ) ? $selected_parameters['_famiau_gearbox_type'] : '';
		foreach ( $gearboxes as $gearbox_val => $gearbox ) {
			$this_class        = $selected_gearbox == $gearbox_val ? 'famiau-active-filter' : '';
			$transmission_html .= '<button data-filter_key="gearbox" data-filter_val="' . esc_attr( $gearbox_val ) . '" class="famiau-filter-item ' . esc_attr( $this_class ) . '">';
			$transmission_html .= esc_html( $gearbox );
			$transmission_html .= '</button>';
		}
		
		$id                = uniqid( 'gearbox-' );
		$transmission_html = '<div data-filter_type="button" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group">' .
		                     '<div class="part-left"><label>' . esc_html__( 'Transmission', 'famiau' ) . '</label></div>' .
		                     '<div class="part-right"><div class="famiau-group-buttons famiau-criteria-filtering">' . $transmission_html . '</div></div>' .
		                     '</div>';
		
		
		// Drives
		$all_drives = $famiau['all_drives'];
		if ( ! empty( $all_drives ) ) {
			$id             = uniqid( 'drive-' );
			$selected_drive = isset( $selected_parameters['all_drives'] ) ? $selected_parameters['all_drives'] : '';
			
			$drives_html = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_drives ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$drives_html = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_drive ) . '" data-filter_key="drive" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			               '<div class="part-left"><label>' . esc_html__( 'Drive', 'famiau' ) . '</label></div>' .
			               '<div class="part-right">' . $drives_html . '</div>' .
			               '</div>';
		}
		
		// Fuel type
		$all_fuel_types = $famiau['all_fuel_types'];
		if ( ! empty( $all_fuel_types ) ) {
			$id                 = uniqid( 'car_body-' );
			$selected_fuel_type = isset( $selected_parameters['all_fuel_types'] ) ? $selected_parameters['all_fuel_types'] : '';
			$fuel_types_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_fuel_types ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$fuel_types_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_fuel_type ) . '" data-filter_key="fuel" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                      '<div class="part-left"><label>' . esc_html__( 'Fuel', 'famiau' ) . '</label></div>' .
			                      '<div class="part-right">' . $fuel_types_html . '</div>' .
			                      '</div>';
		}
		
		// Bodies
		$all_car_bodies = $famiau['all_car_bodies'];
		if ( ! empty( $all_car_bodies ) ) {
			$id                = uniqid( 'fuel-' );
			$selected_car_body = isset( $selected_parameters['all_car_bodies'] ) ? $selected_parameters['all_car_bodies'] : '';
			$bodies_html       = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_car_bodies ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$bodies_html       = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_car_body ) . '" data-filter_key="car_body" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                     '<div class="part-left"><label>' . esc_html__( 'Body', 'famiau' ) . '</label></div>' .
			                     '<div class="part-right">' . $bodies_html . '</div>' .
			                     '</div>';
		}
		
		
		// Exterior colors
		$all_exterior_colors = $famiau['all_exterior_colors'];
		if ( ! empty( $all_exterior_colors ) ) {
			$id                      = uniqid( 'exterior_color-' );
			$selected_exterior_color = isset( $selected_parameters['all_exterior_colors'] ) ? $selected_parameters['all_exterior_colors'] : '';
			$exterior_colors_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_exterior_colors ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$exterior_colors_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_exterior_color ) . '" data-filter_key="exterior_color" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                           '<div class="part-left"><label>' . esc_html__( 'Exterior Colors', 'famiau' ) . '</label></div>' .
			                           '<div class="part-right">' . $exterior_colors_html . '</div>' .
			                           '</div>';
		}
		
		// Interior colors
		$all_interior_colors = $famiau['all_interior_colors'];
		if ( ! empty( $all_interior_colors ) ) {
			$id                      = uniqid( 'interior_color-' );
			$selected_interior_color = isset( $selected_parameters['all_interior_colors'] ) ? $selected_parameters['all_interior_colors'] : '';
			$interior_colors_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_interior_colors ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$interior_colors_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_interior_color ) . '" data-filter_key="interior_colors" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                           '<div class="part-left"><label>' . esc_html__( 'Interior Colors', 'famiau' ) . '</label></div>' .
			                           '<div class="part-right">' . $interior_colors_html . '</div>' .
			                           '</div>';
		}
		
		// Seats
		$all_seats = isset( $famiau['all_car_features_seats'] ) ? $famiau['all_car_features_seats'] : array();
		if ( ! empty( $all_seats ) ) {
			$id            = uniqid( 'seat-' );
			$selected_seat = isset( $selected_parameters['all_car_features_seats'] ) ? $selected_parameters['all_car_features_seats'] : '';
			$seats_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_seats ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$seats_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_seat ) . '" data-filter_key="seat" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                 '<div class="part-left"><label>' . esc_html__( 'Seat', 'famiau' ) . '</label></div>' .
			                 '<div class="part-right">' . $seats_html . '</div>' .
			                 '</div>';
		}
		
		// Comforts
		$all_comforts = isset( $famiau['all_car_features_comforts'] ) ? $famiau['all_car_features_comforts'] : array();
		if ( ! empty( $all_comforts ) ) {
			$id               = uniqid( 'comfort-' );
			$selected_comfort = isset( $selected_parameters['all_car_features_comforts'] ) ? $selected_parameters['all_car_features_comforts'] : '';
			$comforts_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_comforts ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$comforts_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_comfort ) . '" data-filter_key="comfort" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                    '<div class="part-left"><label>' . esc_html__( 'Comfort', 'famiau' ) . '</label></div>' .
			                    '<div class="part-right">' . $comforts_html . '</div>' .
			                    '</div>';
		}
		
		// Entertainments
		$all_entertainments = isset( $famiau['all_car_features_entertainments'] ) ? $famiau['all_car_features_entertainments'] : array();
		if ( ! empty( $all_entertainments ) ) {
			$id                     = uniqid( 'entertainment-' );
			$selected_entertainment = isset( $selected_parameters['all_car_features_entertainments'] ) ? $selected_parameters['all_car_features_entertainments'] : '';
			$entertainments_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_entertainments ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$entertainments_html    = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_entertainment ) . '" data-filter_key="entertainment" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                          '<div class="part-left"><label>' . esc_html__( 'Entertainments', 'famiau' ) . '</label></div>' .
			                          '<div class="part-right">' . $entertainments_html . '</div>' .
			                          '</div>';
		}
		
		// Safety
		$all_safety = isset( $famiau['all_car_features_safety'] ) ? $famiau['all_car_features_safety'] : array();
		if ( ! empty( $all_safety ) ) {
			$id              = uniqid( 'safety-' );
			$selected_safety = isset( $selected_parameters['all_car_features_safety'] ) ? $selected_parameters['all_car_features_safety'] : '';
			$safety_html     = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_safety ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$safety_html     = '<div data-filter_type="popup" data-filter_val="' . esc_attr( $selected_safety ) . '" data-filter_key="safety" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                   '<div class="part-left"><label>' . esc_html__( 'Safety', 'famiau' ) . '</label></div>' .
			                   '<div class="part-right">' . $safety_html . '</div>' .
			                   '</div>';
		}
		
		// Windows
		$all_windows = isset( $famiau['all_car_features_windows'] ) ? $famiau['all_car_features_windows'] : array();
		if ( ! empty( $all_windows ) ) {
			$id              = uniqid( 'window-' );
			$selected_window = isset( $selected_parameters['all_car_features_windows'] ) ? $selected_parameters['all_car_features_windows'] : '';
			$windows_html    = '<div class="famiau-select-popup" data-popup_data="' . htmlentities2( json_encode( $all_windows ) ) . '"><div class="famiau-select-popup-result">' . esc_html__( 'All', 'famiau' ) . '</div></div>';
			$windows_html    = '<div data-filter_type="popup"data-filter_val="' . esc_attr( $selected_window ) . '" data-filter_key="window" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter">' .
			                   '<div class="part-left"><label>' . esc_html__( 'Windows', 'famiau' ) . '</label></div>' .
			                   '<div class="part-right">' . $windows_html . '</div>' .
			                   '</div>';
		}
		
		// Have images
		$have_imgs = array(
			'yes' => esc_html__( 'Yes', 'famiau' ),
			'no'  => esc_html__( 'No', 'famiau' )
		);
		
		foreach ( $have_imgs as $have_img_key => $have_img_val ) {
			$have_imgs_html .= '<button data-filter_key="have_imgs" data-filter_val="' . esc_attr( $have_img_key ) . '" class="famiau-filter-item">';
			$have_imgs_html .= esc_html( $have_img_val );
			$have_imgs_html .= '</button>';
		}
		$id = uniqid( 'have_imgs-' );
		
		$have_imgs_html = '<div data-filter_type="button" id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-display-flex famiau-justify-content-space-between famiau-align-items-center">' .
		                  '<div class="part-left"><label>' . esc_html__( 'Have Images', 'famiau' ) . '</label></div>' .
		                  '<div class="part-right"><div class="famiau-group-buttons famiau-criteria-filtering">' . $have_imgs_html . '</div></div>' .
		                  '</div>';
		
		
		// Years
		$this_year = intval( date( 'Y' ) );
		$min_year  = intval( $min_year ) <= 0 ? intval( $famiau['_famiau_min_year'] ) : 1700;
		$max_year  = intval( $max_year ) <= 0 ? intval( $famiau['_famiau_max_year'] ) : $this_year;
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
			$id         = uniqid( 'years-' );
			$years_html = '<select class="famiau-filter-item famiau-filter-by-year famiau-filter-from-year famisp-select-num-min" data-filter_key="from_year" data-filter_val="' . esc_attr( $min_year ) . '">' . $years_from_first_option . $years_html . '</select>' .
			              '<select class="famiau-filter-item famiau-filter-by-year famiau-filter-to-year famisp-select-num-max" data-filter_key="to_year" data-filter_val="' . esc_attr( $max_year ) . '">' . $years_to_first_option . $years_html . '</select>';
			$years_html = '<div class="famiau-filter-box part-years famiau-select-min-max-group"><div class="famiau-select-group-inner">' . $years_html . '</div></div>';
			
			$years_html = '<div id="' . esc_attr( $id ) . '" class="famiau-mobile-filter-group famiau-display-flex famiau-justify-content-space-between famiau-align-items-center">' .
			              '<div class="part-left"><label>' . esc_html__( 'Year', 'famiau' ) . '</label></div>' .
			              '<div class="part-right"><div class="famiau-group-selects">' . $years_html . '</div></div>' .
			              '</div>';
		}
		
		
		// Filter button
		if ( ! $instant_filter ) {
			$filter_btn_html = '<button class="famiau-button-primary famiau-mega-filter-submit-btn famiau-mega-mobile-filter-submit-btn">' . esc_html__( 'Find Cars', 'famiau' ) . '</button>';
		}
		
		$clear_filter_btn_html .= '<button class="famiau-button-primary famiau-mega-filter-clear-btn">' . esc_html__( 'Clear Filter', 'famiau' ) . '</button>';
		
		$action_btns_html = '<div class="famiau-mobile-filter-group famiau-mobile-filter-actions">' . $filter_btn_html . $clear_filter_btn_html . '</div>';
		
		// Html
		$html .= '<div class="famiau-filter-top-nav"><a class="famiau-back-filter"><i class="fa fa-arrow-left"></i></a> <span data-default_text="' . esc_attr__( 'Search', 'famiau' ) . '" class="famiau-top-nav-text">' . esc_attr__( 'Search', 'famiau' ) . '</span></div>';
		$html .= '<div class="famiau-mega-filter-wrap famiau-mobile-filter-wrap ' . esc_attr( $filter_wrap_class ) . '">';
		$html .= '<div class="famiau-mega-filter-inner famiau-mobile-filter-inner">';
		$html .= $user_select_html . $listing_status_select_html;
		$html .= $makes_and_models_html . $price_range_html . $engine_range_html . $car_statuses_html . $drives_html . $fuel_types_html . $years_html . $transmission_html;
		$html .= $exterior_colors_html . $interior_colors_html;
		$html .= $bodies_html . $seats_html . $comforts_html . $entertainments_html . $safety_html . $windows_html . $have_imgs_html;
		$html .= $action_btns_html;
		$html .= '</div>';
		$html .= '</div>';
		
		$html = apply_filters( 'famiau_mobile_mega_filter_html', $html, $args );
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_admin_listing_query_results' ) ) {
	function famiau_admin_listing_query_results( $where = '', $prepare = '' ) {
		global $wpdb;
		
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}
		
		$html       = '';
		$table_name = FAMIAU_LISTINGS_TABLE;
		if ( trim( $where ) != '' ) {
			$sql = $wpdb->prepare( "SELECT * FROM {$table_name} WHERE 1 {$where}", $prepare );
		} else {
			$sql = "SELECT * FROM {$table_name}";
		}
		
		// return $sql;
		
		$rows        = $wpdb->get_results( $sql );
		$all_actions = array(
			'waiting'  => esc_html__( 'Review', 'famiau' ),
			'approved' => esc_html__( 'Approve', 'famiau' ),
			'deleted'  => esc_html__( 'Delete', 'famiau' ),
			'sold'     => esc_html__( 'Sold', 'famiau' ),
		);
		
		// Results html
		ob_start();
//		echo '<pre>';
//		print_r($sql);
//		echo '</pre>';
		?>
		<?php if ( $rows ) { ?>
			<?php
			$all_car_features           = famiau_car_features();
			$all_car_features_meta_keys = array();
			foreach ( $all_car_features as $car_feature ) {
				if ( isset( $car_feature['meta_key'] ) ) {
					$all_car_features_meta_keys[] = $car_feature['meta_key'];
				}
			}
			
			?>
            <table class="table table-responsive table-bordered famiau-table famiau-listings-table">
                <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e( 'Listing Info', 'famiau' ); ?></th>
                    <th><?php esc_html_e( 'Features', 'famiau' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'famiau' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'famiau' ); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ( $rows as $row ) {
					$imgs_html         = '';
					$address_html      = '';
					$video_html        = '';
					$seller_notes_html = '';
					$listing_info_html = '';
					$prices_html       = '';
					$feaures_html      = '';
					$status_html       = '<span class="famiau-listing-status listing-status-' . esc_attr( $row->listing_status ) . '">' . esc_attr( $row->listing_status ) . '</span>';
					$actions_html      = '';
					
					$this_actions = $all_actions;
					
					if ( isset( $this_actions[ $row->listing_status ] ) ) {
						unset( $this_actions[ $row->listing_status ] );
					}
					
					if ( ! empty( $this_actions ) ) {
						foreach ( $this_actions as $action => $action_text ) {
							$actions_html .= '<a href="#" data-action="' . esc_attr( $action ) . '" class="famiau-action famiau-action-' . esc_attr( $action ) . '">' . sanitize_text_field( $action_text ) . '</a>';
						}
					}
					
					$attachment_ids = trim( $row->attachment_ids );
					if ( $attachment_ids != '' ) {
						$attachment_ids = explode( ',', $attachment_ids );
						if ( ! empty( $attachment_ids ) ) {
							foreach ( $attachment_ids as $attachment_id ) {
								$img       = famiau_resize_image( $attachment_id, null, 100, 100, true, true, false );
								$imgs_html .= '<img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" class="famiau-listing-thumb" />';
							}
						}
						$imgs_html = '<div class="famiau-imgs-wrap">' . $imgs_html . '</div>';
					}
					
					$address = trim( $row->_famiau_car_address );
					if ( $address != '' ) {
						$address_html .= '<p><span>' . esc_html__( 'Car Address', 'famiau' ) . '</span> ' . esc_html( $address ) . '</p>';
					}
					$latitude  = trim( $row->_famiau_car_latitude );
					$longitude = trim( $row->_famiau_car_longitude );
					if ( $latitude != '' && $longitude != '' ) {
						$address_html .= '<p>' .
						                 '<span>' . esc_html__( 'Latitude', 'famiau' ) . '</span> <span class="_famiau_car_latitude">' . esc_html( $latitude ) . '</span>' .
						                 '<span>' . esc_html__( 'Longitude', 'famiau' ) . '</span> <span class="_famiau_car_longitude">' . esc_html( $longitude ) . '</span>' .
						                 '</p>';
					}
					
					$title      = trim( $row->listing_title );
					$title_html = '';
					if ( $title == '' ) {
						$car_status = isset( $car_statuses[ $row->_famiau_car_status ] ) ? $car_statuses[ $row->_famiau_car_status ] : '';
						$title      = $car_status . ' ' . $row->_famiau_make . ' ' . $row->_famiau_model . ' ' . $row->_famiau_year;
					}
					$title_html = '<h4 class="listing-title">' . $title . '</h4>';
					
					// Price
					$price       = $row->_famiau_price;
					$price       = famiau_price( $price );
					$prices_html .= '<p class="famiau-price">' . $price . '</p>';
					
					$listing_info_html .= '<div class="listing-info-wrap">';
					$listing_info_html .= '<div data-item_key="_famiau_body" class="famiau-info-item"><span>' . esc_html__( 'Body', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_body ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_mileage" class="famiau-info-item"><span>' . esc_html__( 'Mileage', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_mileage ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_fuel_type" class="famiau-info-item"><span>' . esc_html__( 'Fuel type', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_fuel_type ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_engine" class="famiau-info-item"><span>' . esc_html__( 'Engine', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_engine ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_gearbox_type" class="famiau-info-item"><span>' . esc_html__( 'Transmission', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_gearbox_type ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_drive" class="famiau-info-item"><span>' . esc_html__( 'Drive', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_drive ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_exterior_color" class="famiau-info-item"><span>' . esc_html__( 'Exterior Color', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_exterior_color ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_interior_color" class="famiau-info-item"><span>' . esc_html__( 'Interior Color', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_interior_color ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_registered_date" class="famiau-info-item"><span>' . esc_html__( 'Registered', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_registered_date ) . '</span></div>';
					$listing_info_html .= '<div data-item_key="_famiau_vin" class="famiau-info-item"><span>' . esc_html__( 'VIN', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_vin ) . '</span></div>';
					$listing_info_html .= '<div>';
					
					if ( ! empty( $all_car_features_meta_keys ) ) {
						foreach ( $all_car_features_meta_keys as $car_feature_meta_key ) {
							if ( isset( $row->$car_feature_meta_key ) ) {
								$features = unserialize( $row->$car_feature_meta_key );
								if ( ! empty( $features ) ) {
									$feaures_html .= '<p>';
									foreach ( $features as $feature ) {
										$feaures_html .= '<span class="famiau-car-feature">' . esc_html( $feature ) . '</span>';
									}
									$feaures_html .= '</p>';
								}
							}
						}
					}
					
					// Video
					$video_url = trim( $row->_famiau_video_url );
					if ( $video_url != '' ) {
						$video_html .= '<p>' . sprintf( __( 'Click %s for video', 'famiau' ), '<a href="' . esc_url( $video_url ) . '" target="_blank">' . esc_html__( 'here', 'famiau' ) . '</a>' ) . '</p>';
					} else {
						$video_html .= '<p>' . esc_html__( 'No video', 'famiau' ) . '</p>';
					}
					
					$seller_notes = trim( $row->_famiau_seller_notes_suggestions );
					if ( $seller_notes != '' ) {
						$seller_notes_html .= '<p class="famiau-seller-note">' . esc_html( $seller_notes ) . '</p>';
					}
					
					?>
                    <tr data-listing_id="<?php echo esc_attr( $row->id ); ?>"
                        data-product_id="<?php echo esc_attr( $row->product_id ); ?>"
                        class="famiau-listing-tr famiau-listing-tr-<?php echo esc_attr( $row->listing_status ); ?>">
                        <td class="famiau-imgs-td"><?php echo $title_html . $prices_html . $imgs_html . $address_html . $video_html . $seller_notes_html; ?></td>
                        <td class="famiau-info-td"><?php echo $listing_info_html; ?></td>
                        <td class="famiau-features-td"><?php echo $feaures_html; ?></td>
                        <td class="famiau-status-td"><?php echo $status_html; ?></td>
                        <td class="famiau-actions-td"><?php echo $actions_html; ?></td>
                    </tr>
					<?php
				}
				?>
                </tbody>
            </table>
		<?php }
		$html .= ob_get_clean();
		
		return $html;
	}
}