<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// http://motors.stylemixthemes.com/classified/add-car/

if ( ! function_exists( 'famiau_listing' ) ) {
	function famiau_listing( $atts ) {
		$default_atts = array(
			'title'                          => esc_html__( 'Select Your Vehicle', 'famiau' ),
			'style'                          => 'dropdown', // dropdown, mega_filter
			'filter_col'                     => 4, // For dropdown style
			'filter_by__famiau_make'         => 'yes', // Has 2 dash
			'filter_by__famiau_model'        => 'yes',
			'filter_by__famiau_fuel_type'    => 'yes',
			'filter_by__famiau_car_status'   => 'yes',
			'filter_by__famiau_year'         => 'yes',
			'filter_by__famiau_price'        => 'yes',
			'filter_by__famiau_gearbox_type' => 'yes',
			'min_year'                       => 0,
			'max_year'                       => 0,
			'show_submit_btn'                => 'yes',
			'submit_btn_text'                => esc_html__( 'Filter', 'famiau' )
		);
		$atts         = wp_parse_args( $atts, $default_atts );
		
		if ( ! empty( $atts ) ) {
			// $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'famiau_listing', $atts ) : $atts;
			extract( $atts );
		}
		
		$all_options = famiau_get_all_options();
		
		$filter_html     = '';
		$content_html    = '';
		$submit_btn_html = '';
		$html            = '';
		
		$avai_fiters_by = array(
			'_famiau_make',
			'_famiau_model',
			'_famiau_fuel_type',
			'_famiau_car_status',
			'_famiau_year',
			'_famiau_price',
			'_famiau_gearbox_type'
		);
		
		$style           = $atts['style'];
		$show_submit_btn = $atts['show_submit_btn'] == 'yes';
		
		
		switch ( $style ) {
			case 'dropdown':
				$filter_col = intval( $atts['filter_col'] );
				if ( ! in_array( $filter_col, array( 1, 2, 3, 4, 6 ) ) ) {
					$filter_col = 4;
				}
				$filter_box_class = '';
				switch ( $filter_col ) {
					case 1:
						$filter_box_class .= 'col-xs-12';
						break;
					case 2:
						$filter_box_class .= 'col-xs-12 col-sm-6';
						break;
					case 3:
						$filter_box_class .= 'col-xs-12 col-sm-6 col-md-4';
						break;
					case 4:
						$filter_box_class .= 'col-xs-12 col-sm-4 col-md-3';
						break;
					case 6:
						$filter_box_class .= 'col-xs-6 col-sm-3 col-md-3 col-lg-2';
						break;
				}
				
				foreach ( $avai_fiters_by as $filter ) {
					if ( isset( $atts["filter_by_$filter"] ) ) {
						if ( $atts["filter_by_$filter"] == 'yes' ) {
							switch ( $filter ) {
								case '_famiau_make':
									// $all_makes         = isset( $all_options['all_makes'] ) ? $all_options['all_makes'] : array();
									$makes_select_html = famiau_makes_select_html( '', 'famiau-field famiau-filter-by-' . $filter, $filter, $filter, false );
									$filter_html       .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' ' . $filter_box_class . '">' . $makes_select_html . '</div>';
									break;
								case '_famiau_model':
									$filter_html .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' ' . $filter_box_class . '">' .
									                '<select class="famiau-field famiau-select famiau-model-select famiau-filter-by-' . $filter . '" name="' . $filter . '" id="' . $filter . '">' .
									                '<option data-model="" value="">' . esc_html__( 'Any Model', 'famiau' ) . '</option>' .
									                '</select>' .
									                '</div>';
									break;
								case '_famiau_fuel_type':
									$fuel_types_select_html = famiau_fuel_types_select_html( '', 'famiau-field famiau-filter-by-' . $filter, $filter, $filter, false );
									$filter_html            .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' ' . $filter_box_class . '">' . $fuel_types_select_html . '</div>';
									break;
								case '_famiau_car_status':
									$car_statuses_select_html = famiau_car_status_select_html( '', 'famiau-field famiau-filter-by-' . $filter, $filter, $filter, '', false );
									$filter_html              .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' ' . $filter_box_class . '">' . $car_statuses_select_html . '</div>';
									break;
								case '_famiau_year':
									$this_year = intval( date( 'Y' ) );
									$min_year  = intval( $atts['min_year'] ) <= 0 ? intval( $all_options['_famiau_min_year'] ) : 1700;
									$max_year  = intval( $atts['max_year'] ) <= 0 ? intval( $all_options['_famiau_max_year'] ) : $this_year;
									if ( $max_year < $min_year ) {
										$max_year = $min_year;
									}
									if ( $max_year > $this_year ) {
										$max_year = $this_year;
									}
									$years_range_select_html = '';
									$years_from_first_option = '<option value="0">' . esc_html__( 'From Year', 'famiau' ) . '</option>';
									$years_to_first_option   = '<option value="' . $this_year . '">' . esc_html__( 'To Year', 'famiau' ) . '</option>';
									for ( $y = $min_year; $y <= $max_year; $y ++ ) {
										$years_range_select_html .= '<option value="' . $y . '">' . $y . '</option>';
									}
									if ( $years_range_select_html != '' ) {
										$years_range_select_html = '<select class="famiau-field famiau-filter-by-' . $filter . ' famiau-filter-from-year famisp-select-num-min" name="' . $filter . '_from" id="' . $filter . '_from">' . $years_from_first_option . $years_range_select_html . '</select>' .
										                           '<select class="famiau-field famiau-filter-by-' . $filter . ' famiau-filter-to-year famisp-select-num-max" name="' . $filter . '_to" id="' . $filter . '_to">' . $years_to_first_option . $years_range_select_html . '</select>';
										$filter_html             .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' famiau-select-min-max-group ' . $filter_box_class . '"><div class="famiau-select-group-inner">' . $years_range_select_html . '</div></div>';
									}
									break;
								case '_famiau_price':
									$prices_range = famiau_get_prices_range();
									$price_step   = 5000;
									if ( $price_step > $prices_range['max_price'] ) {
										$price_step = $prices_range['max_price'];
									}
									$first_range_num = $price_step;
									if ( $price_step > 0 && $first_range_num != $price_step ) {
										$i = 1;
										while ( $first_range_num < $prices_range['min_price'] && $first_range_num < $prices_range['max_price'] ) {
											$first_range_num = $price_step * $i;
											$i ++;
										}
									}
									if ( $price_step <= 0 ) {
										$price_step = 1;
									}
									$price_from_select_html  = '<option value="' . $prices_range['min_price'] . '" data-price_format="' . htmlentities2( esc_attr( famiau_wc_price( $prices_range['min_price'] ) ) ) . '">' . $prices_range['min_price'] . '</option>';
									$price_from_first_option = '<option value="0" data-price_format="">' . esc_html__( 'Min Price', 'famiau' ) . '</option>';
									$price_to_first_option   = '<option value="' . $prices_range['max_price'] . '" data-price_format="' . htmlentities2( esc_attr( famiau_wc_price( $prices_range['max_price'] ) ) ) . '">' . esc_html__( 'Max Price', 'famiau' ) . '</option>';
									for ( $price = $first_range_num; $price <= $prices_range['max_price']; $price += $price_step ) {
										$price_from_select_html .= '<option value="' . $price . '" data-price_format="' . htmlentities2( esc_attr( famiau_wc_price( $price ) ) ) . '">' . $price . '</option>';
									}
									$price_to_select_html   = $price_from_select_html;
									$price_from_select_html = '<select class="famiau-field famiau-filter-by-' . $filter . ' famiau-filter-from-price famisp-select-num-min" name="' . $filter . '_from" id="' . $filter . '_from">' . $price_from_first_option . $price_from_select_html . '</select>';
									$price_to_select_html   = '<select class="famiau-field famiau-filter-by-' . $filter . ' famiau-filter-to-price famisp-select-num-max" name="' . $filter . '_to" id="' . $filter . '_to">' . $price_to_first_option . $price_to_select_html . '</select>';
									$filter_html            .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' famiau-select-min-max-group ' . $filter_box_class . '"><div class="famiau-select-group-inner">' . $price_from_select_html . $price_to_select_html . '</div></div>';
									break;
								case '_famiau_gearbox_type':
									$gearbox_type_select_html = famiau_gearbox_type_select_html( '', 'famiau-field famiau-filter-by-' . $filter, $filter, $filter, '', false );
									$filter_html              .= '<div class="famiau-filter-box famiau-filter-box-' . $filter . ' ' . $filter_box_class . '">' . $gearbox_type_select_html . '</div>';
									break;
							}
							
						}
						
					}
				}
				if ( $filter_html != '' ) {
					if ( $show_submit_btn ) {
						$submit_btn_html = '<div class="famiau-filter-box famiau-filter-button-box ' . $filter_box_class . '"><button type="button" class="button famiau-btn famiau-submit-filter-btn">' . esc_html( $atts['submit_btn_text'] ) . '</button></div>';
					}
					$filter_html = '<div class="row">' . $filter_html . $submit_btn_html . '</div>';
				}
				break;
			case 'mega_filter':
				break;
		}
		
		$content_html .= famiau_display_listing_results( array(), false );
		
		$filter_html  = apply_filters( 'famiau_listing_filter_html', $filter_html, $atts );
		$content_html = apply_filters( 'famiau_listing_content_html', $content_html, $atts );
		
		ob_start();
		do_action( 'famiau_before_listing' );
		?>
        <div class="famiau-listing-wrap famiau-listting-<?php echo esc_attr( $style ); ?>-wrap">
            <div class="famiau-listing-inner">

                <div class="famiau-listing-head">
					<?php
					if ( trim( $atts['title'] ) != '' ) {
						echo sprintf( '<h2 class="famiau-listing-title">%s</h2>', $atts['title'] );
					}
					?>
					<?php do_action( 'famiau_before_listing_filter' ); ?>
                    <div class="famiau-listing-filter-wrap">
						<?php echo $filter_html; ?>
                    </div>
					<?php do_action( 'famiau_after_listing_filter' ); ?>
                </div>

                <div class="famiau-listing-body">
					<?php do_action( 'famiau_before_listing_content' ); ?>
                    <div class="famiau-listing-content-wrap">
						<?php echo $content_html; ?>
                    </div>
					<?php do_action( 'famiau_after_listing_content' ); ?>
                </div>


            </div>
        </div>
		<?php
		do_action( 'famiau_after_listing' );
		$html .= ob_get_clean();
		
		$html = apply_filters( 'famiau_listing_html', $html, $atts );
		
		return $html;
		
	}
	
	add_shortcode( 'famiau_listing', 'famiau_listing' );
}

if ( ! function_exists( 'famiau_upload_shortcode' ) ) {
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	function famiau_upload_shortcode( $atts ) {
		
		extract(
			shortcode_atts(
				array(
					'wrap_class' => '',
					'btn_class'  => '',
					'img_ids'    => '',
					'multi'      => 'yes'
				), $atts
			)
		);
		
		$html       = '';
		$wrap_class .= ' ' . uniqid( 'famiau-upload-wrap-' );
		$btn_class  .= ' ' . uniqid( 'famiau-upload-btn-' );
		$img_ids    = trim( $img_ids );
		
		$btn_text             = esc_html__( 'Select Images', 'famiau' );
		$uploader_title       = esc_html__( 'Select Images', 'famiau' );
		$uploader_button_text = esc_html__( 'Select', 'famiau' );
		
		// check if user can upload files
		if ( current_user_can( 'upload_files' ) ) {
			$imgs_preview_html = '';
			if ( $img_ids != '' ) {
				$img_ids = explode( ',', $img_ids );
				foreach ( $img_ids as $img_id ) {
					$img_full          = famiau_resize_image( $img_id, null, 4000, 4000, true, true, false );
					$img               = famiau_resize_image( $img_id, null, 150, 150, true, true, false );
					$imgs_preview_html .= '<div class="famiau-img-preview-wrap"><img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" data-attachment_id="' . esc_attr( $img_id ) . '" data-img_full="' . htmlentities2( json_encode( $img_full ) ) . '" class="famiau-img-preview famiau-img-preview-' . esc_attr( $img_id ) . '" src="' . esc_url( $img['url'] ) . '"> <a href="#" class="remove-img-btn remove-btn">x</a></div>';
				}
			}
			
			$btn_html = '<button data-uploader_title="' . esc_attr( $uploader_title ) . '" data-uploader_button_text="' . esc_attr( $uploader_button_text ) . '" type="button" class="btn btn-default famiau-upload-btn ' . esc_attr( $btn_class ) . '">' . $btn_text . '</button>';
			$html     = '<div data-multi="' . esc_attr( $multi ) . '" class="famiau-upload-wrap ' . esc_attr( $wrap_class ) . '">' .
			            '<div class="famiau-main-img-wrap"></div>' .
			            '<div class="famiau-imgs-preview-wrap famiau-sortable">' . $imgs_preview_html . '</div>'
			            . $btn_html .
			            '</div>';
		} else {
			$img_size_full = array( 'width' => 920, 'height' => 590 );
			$img_full_url  = famiau_no_image( $img_size_full );
			$img_size      = array( 'width' => 220, 'height' => 220 );
			$img_url       = famiau_no_image( $img_size );
			$imgs_html     = '';
			for ( $i = 1; $i <= 4; $i ++ ) {
				$imgs_html .= '<div class="famiau-img-preview-wrap famiau-img-placehold-wrap"><img width="' . $img_size['width'] . '" height="' . $img_size['height'] . '" data-attachment_id="0" data-img_full="' . esc_url( $img_full_url ) . '" class="famiau-img-preview famiau-img-preview-0" src="' . esc_url( $img_url ) . '"> <a href="#" class="famiau-remove-img-btn famiau-remove-btn"><i class="fa fa-times"></i></a></div>';
			}
			$btn_html = '<button disabled data-uploader_title="' . esc_attr( $uploader_title ) . '" data-uploader_button_text="' . esc_attr( $uploader_button_text ) . '" type="button" class="btn btn-default disabled famiau-upload-btn ' . esc_attr( $btn_class ) . '">' . $btn_text . '</button>';
			$html     .= '<div data-multi="' . esc_attr( $multi ) . '" class="famiau-upload-wrap ' . esc_attr( $wrap_class ) . '">' .
			             '<div class="famiau-main-img-wrap"><img width="' . esc_attr( $img_size_full['width'] ) . '" height="' . esc_attr( $img_size_full['height'] ) . '" src="' . esc_url( $img_full_url ) . '" class="famiau-main-img-preview"></div>' .
			             '<div class="famiau-imgs-preview-wrap famiau-sortable">' . $imgs_html . '</div>'
			             . $btn_html .
			             '</div>';
		}
		
		$html = apply_filters( 'famiau_media_frontend_upload', $html, $atts );
		
		return $html;
	}
	
	add_shortcode( 'famiau_upload_media', 'famiau_upload_shortcode' );
}