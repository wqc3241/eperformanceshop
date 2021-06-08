<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Old
 */
function famiau_dropdown_filter_frontend() {
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
	}
	
	$post_filters = isset( $_POST['filters'] ) ? $_POST['filters'] : array();
	$filters      = array(
		'_famiau_make'         => isset( $post_filters['_famiau_make'] ) ? $post_filters['_famiau_make'] : '',
		'_famiau_model'        => isset( $post_filters['_famiau_model'] ) ? $post_filters['_famiau_model'] : '',
		'_famiau_fuel_type'    => isset( $post_filters['_famiau_fuel_type'] ) ? $post_filters['_famiau_fuel_type'] : '',
		'_famiau_car_status'   => isset( $post_filters['_famiau_car_status'] ) ? $post_filters['_famiau_car_status'] : '',
		'_famiau_gearbox_type' => isset( $post_filters['_famiau_gearbox_type'] ) ? $post_filters['_famiau_gearbox_type'] : '',
		'_famiau_year'         => array(
			isset( $post_filters['_famiau_year_from'] ) ? intval( $post_filters['_famiau_year_from'] ) : 0,
			isset( $post_filters['_famiau_year_to'] ) ? intval( $post_filters['_famiau_year_to'] ) : 0
		),
		'_famiau_price'        => array(
			isset( $post_filters['_famiau_price_from'] ) ? floatval( $post_filters['_famiau_price_from'] ) : 0,
			isset( $post_filters['_famiau_price_to'] ) ? floatval( $post_filters['_famiau_price_to'] ) : 0
		)
	);
	
	// $price_from = isset($)
	
	$response['filters'] = $filters;
	
	$response['html'] = famiau_display_listing_results( $filters, false );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_dropdown_filter_frontend', 'famiau_dropdown_filter_frontend' );
add_action( 'wp_ajax_nopriv_famiau_dropdown_filter_frontend', 'famiau_dropdown_filter_frontend' );

function famiau_frontend_mega_filter_results_via_ajax() {
	global $wpdb;
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$filter_data = isset( $_POST['filter_data'] ) ? $_POST['filter_data'] : array();
	
	$query_key_maps = array(
		'author'         => 'author_login',
		'make'           => '_famiau_make',
		'model'          => '_famiau_model',
		'car_status'     => '_famiau_car_status',
		'gearbox'        => '_famiau_gearbox_type',
		'fuel'           => '_famiau_fuel_type',
		'exterior_color' => '_famiau_exterior_color',
		'interior_color' => '_famiau_interior_color',
		'car_body'       => '_famiau_body',
		'drive'          => '_famiau_drive',
		'seat'           => '_famiau_car_features_seats',
		'comfort'        => '_famiau_car_features_comforts',
		'entertainment'  => '_famiau_car_features_entertainments',
		'safety'         => '_famiau_car_features_safety',
		'window'         => '_famiau_car_features_windows',
		// 'listing_status' => 'listing_status'
	);
	
	$where       = '';
	$order       = '';
	$order_field = '';
	$prepare     = array();
	foreach ( $query_key_maps as $key_map => $key_val ) {
		if ( isset( $filter_data[ $key_map ] ) ) {
			if ( trim( $filter_data[ $key_map ] ) != '' ) {
				switch ( $key_map ) {
					case 'seat':
					case 'comfort':
					case 'entertainment':
					case 'window':
						$where     .= "AND {$key_val} LIKE '%s' ";
						$prepare[] = '%' . $wpdb->esc_like( $filter_data[ $key_map ] ) . '%';
						break;
					default:
						$where     .= "AND {$key_val} = '%s' ";
						$prepare[] = $filter_data[ $key_map ];
						break;
				}
			}
		}
	}
	
	// Year filter
	if ( isset( $filter_data['from_year'] ) ) {
		$where     .= "AND _famiau_year >= '%d' ";
		$prepare[] = $filter_data['from_year'];
	}
	if ( isset( $filter_data['to_year'] ) ) {
		$where     .= "AND _famiau_year <= '%d' ";
		$prepare[] = $filter_data['to_year'];
	}
	
	// Price range filter
	if ( isset( $filter_data['min_price'] ) || isset( $filter_data['max_price'] ) ) {
		$where        .= "AND (_famiau_price = 0 OR ";
		$max_relation = "";
		if ( isset( $filter_data['min_price'] ) ) {
			$where        .= "_famiau_price >= '%d' ";
			$prepare[]    = $filter_data['min_price'];
			$max_relation = "AND";
		}
		if ( isset( $filter_data['max_price'] ) ) {
			$where     .= "{$max_relation} _famiau_price <= '%d' ";
			$prepare[] = $filter_data['max_price'];
		}
		$where .= ") ";
	}
	
	// Engine range filter
	if ( isset( $filter_data['min_engine'] ) || isset( $filter_data['max_engine'] ) ) {
		$where        .= "AND (_famiau_engine = 0 OR ";
		$max_relation = "";
		if ( isset( $filter_data['min_engine'] ) ) {
			$where        .= "_famiau_engine >= '%f' ";
			$prepare[]    = $filter_data['min_engine'];
			$max_relation = "AND";
		}
		if ( isset( $filter_data['max_engine'] ) ) {
			$where     .= "{$max_relation} _famiau_engine <= '%f' ";
			$prepare[] = $filter_data['max_engine'];
		}
		$where .= ") ";
	}
	
	// Have images conditional?
	$must_have_imgs = isset( $filter_data['have_imgs'] ) ? $filter_data['have_imgs'] == 'yes' : false;
	if ( $must_have_imgs ) {
		$where .= "AND attachment_ids <> '' ";
	}
	
	// Shorting
	$avai_shorting = array(
		'year_asc'     => array(
			'order_by' => '_famiau_year',
			'order'    => 'ASC'
		),
		'year_desc'    => array(
			'order_by' => '_famiau_year',
			'order'    => 'DESC'
		),
		'price_asc'    => array(
			'order_by' => '_famiau_price',
			'order'    => 'ASC'
		),
		'price_desc'   => array(
			'order_by' => '_famiau_price',
			'order'    => 'DESC'
		),
		'mileage_desc' => array(
			'order_by' => '_famiau_mileage',
			'order'    => 'ASC'
		),
		'mileage_asc'  => array(
			'order_by' => '_famiau_mileage',
			'order'    => 'DESC'
		),
	);
	$shorting      = isset( $filter_data['shorting'] ) ? $filter_data['shorting'] : 'default';
	if ( array_key_exists( $shorting, $avai_shorting ) ) {
		$order       .= "ORDER BY " . $avai_shorting[ $shorting ]['order_by'] . " " . $avai_shorting[ $shorting ]['order'];
		$order_field = "," . $avai_shorting[ $shorting ]['order_by'];
	}
	
	$content_layout = isset( $filter_data['layout'] ) ? $filter_data['layout'] : '';
	
	$table_name = FAMIAU_LISTINGS_TABLE;
	$sql        = $wpdb->prepare( "SELECT product_id{$order_field} FROM {$table_name} WHERE listing_status='approved' {$where} {$order}", $prepare );
	$results    = $wpdb->get_results( $sql );
	
	$response['sql'] = $sql;
	
	$posts_in = array();
	if ( $results ) {
		foreach ( $results as $result ) {
			$posts_in[] = $result->product_id;
		}
	}
	
	if ( empty( $posts_in ) ) {
		$response['message'][] = esc_html__( 'Sorry, no results', 'famiau' );
		wp_send_json( $response );
	}
	
	$paged      = isset( $filter_data['paged'] ) ? intval( $filter_data['paged'] ) : 1;
	$query_args = array(
		'post_type' => 'famiau',
		'post__in'  => $posts_in,
		'paged'     => $paged
	);
	
	if ( $order != '' ) {
		$query_args['orderby'] = 'post__in';
	}
	
	ob_start();
	$listings = new WP_Query( $query_args );
	if ( $listings->have_posts() ) {
		while ( $listings->have_posts() ) {
			$listings->the_post();
			famiau_get_template_part( 'content-famiau', $content_layout );
		}
		?>
        <div class="col-xs-12"><?php the_famiau_pagination( $listings->max_num_pages, $paged ); ?></div>
		<?php
	}
	wp_reset_postdata();
	$response['html'] .= ob_get_clean();
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_frontend_mega_filter_results_via_ajax', 'famiau_frontend_mega_filter_results_via_ajax' );
add_action( 'wp_ajax_nopriv_famiau_frontend_mega_filter_results_via_ajax', 'famiau_frontend_mega_filter_results_via_ajax' );

function famiau_map_filter_via_ajax() {
	global $wpdb;
	$response = array(
		'message'       => array(),
		'html'          => '',
		'listings_info' => array(),
		'err'           => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$filter_data    = isset( $_POST['filter_data'] ) ? $_POST['filter_data'] : array();
	$query_key_maps = array(
		'_famiau_car_status'   => '_famiau_car_status',
		'_famiau_make'         => '_famiau_make',
		'_famiau_model'        => '_famiau_model',
		'all_fuel_types'       => '_famiau_fuel_type',
		'all_car_bodies'       => '_famiau_body',
		'all_drives'           => '_famiau_drive',
		'all_exterior_colors'  => '_famiau_exterior_color',
		'all_interior_colors'  => '_famiau_interior_color',
		'_famiau_gearbox_type' => '_famiau_gearbox_type'
	);
	
	$where   = '';
	$prepare = array();
	foreach ( $query_key_maps as $key_map => $key_val ) {
		if ( isset( $filter_data[ $key_map ] ) ) {
			if ( trim( $filter_data[ $key_map ] ) != '' ) {
				switch ( $key_map ) {
					case 'seat':
					case 'comfort':
					case 'entertainment':
					case 'window':
						$where     .= "AND {$key_val} LIKE '%s' ";
						$prepare[] = '%' . $wpdb->esc_like( $filter_data[ $key_map ] ) . '%';
						break;
					default:
						$where     .= "AND {$key_val} = '%s' ";
						$prepare[] = $filter_data[ $key_map ];
						break;
				}
			}
		}
	}
	
	// Year filter
	if ( isset( $filter_data['from_year'] ) ) {
		$where     .= "AND _famiau_year >= '%d' ";
		$prepare[] = $filter_data['from_year'];
	}
	if ( isset( $filter_data['to_year'] ) ) {
		$where     .= "AND _famiau_year <= '%d' ";
		$prepare[] = $filter_data['to_year'];
	}
	
	// Price range filter
	if ( isset( $filter_data['min_price'] ) || isset( $filter_data['max_price'] ) ) {
		$where        .= "AND (_famiau_price = 0 OR ";
		$max_relation = "";
		if ( isset( $filter_data['min_price'] ) ) {
			$where        .= "_famiau_price >= '%d' ";
			$prepare[]    = $filter_data['min_price'];
			$max_relation = "AND";
		}
		if ( isset( $filter_data['max_price'] ) ) {
			if ( $filter_data['max_price'] > 0 ) {
				$where     .= "{$max_relation} _famiau_price <= '%d' ";
				$prepare[] = $filter_data['max_price'];
			} else {
				$where .= "1 ";
			}
		}
		$where .= ")";
	}
	
	// Have images conditional?
	$must_have_imgs = isset( $filter_data['have_imgs'] ) ? $filter_data['have_imgs'] == 'yes' : false;
	if ( $must_have_imgs ) {
		$where .= "AND attachment_ids <> '' ";
	}
	
	$table_name      = FAMIAU_LISTINGS_TABLE;
	$select_what     = "SELECT product_id AS post_id, listing_title, _famiau_car_status AS car_status, _famiau_year AS year, _famiau_mileage AS mileage, _famiau_fuel_type AS fuel_type, _famiau_gearbox_type AS gearbox_type, attachment_ids, _famiau_price AS price, _famiau_car_address AS car_address, _famiau_car_latitude AS latitude, _famiau_car_longitude AS longitude ";
	$sql             = $wpdb->prepare( "{$select_what} FROM {$table_name} WHERE listing_status='approved' {$where}", $prepare );
	$response['sql'] = $sql;
//	$response['err'] = 'yes';
//	wp_send_json( $response );
	$results = array();
	$rows    = $wpdb->get_results( $sql, ARRAY_A );
	if ( $rows ) {
		$car_statuses = array(
			'new'            => esc_html__( 'New', 'famiau' ),
			'used'           => esc_html__( 'Used', 'famiau' ),
			'certified-used' => esc_html__( 'Certified Used', 'famiau' )
		);
		$gearboxes    = array(
			'manual'         => esc_html__( 'Manual', 'famiau' ),
			'auto'           => esc_html__( 'Automatic', 'famiau' ),
			'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
		);
		foreach ( $rows as $row ) {
			$row_info                    = $row;
			$row_info['permalink']       = esc_url( get_permalink( $row['post_id'] ) );
			$row_info['car_status_text'] = isset( $car_statuses[ $row_info['car_status'] ] ) ? $car_statuses[ $row_info['car_status'] ] : $row_info['car_status'];
			$row_info['gearbox_type']    = isset( $gearboxes[ $row_info['gearbox_type'] ] ) ? $gearboxes[ $row_info['gearbox_type'] ] : $row_info['gearbox_type'];
			$row_info['price']           = $row_info['price'] > 0 ? famiau_get_price_html( $row_info['price'] ) : esc_html__( 'Call Us', 'famiau' );
			$attachment_ids              = $row['attachment_ids'];
			if ( trim( $attachment_ids ) != '' ) {
				$attachment_ids    = explode( ',', $attachment_ids );
				$thumb             = famiau_resize_image( $attachment_ids[0], null, 150, 90, true, true, false );
				$row_info['thumb'] = $thumb;
			}
			$results[] = $row_info;
			
		}
	}
	
	$response['listings_info'] = $results;
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_map_filter_via_ajax', 'famiau_map_filter_via_ajax' );
add_action( 'wp_ajax_nopriv_famiau_map_filter_via_ajax', 'famiau_map_filter_via_ajax' );

function famiau_add_new_listing_via_ajax() {
	global $wpdb, $current_user, $famiau;
	
	$response = array(
		'message'          => array(),
		'my_listings_html' => '',
		'err'              => 'no'
	);
	
	if ( ! is_user_logged_in() ) {
		$response['message'][] = esc_html__( 'You must login to do this.', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check user permission
	if ( ! current_user_can( 'famiau_user' ) ) {
		$response['message'][] = esc_html__( 'You are not authorized to do this!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$listing_data             = isset( $_POST['listing_data'] ) ? $_POST['listing_data'] : Array();
	$attachment_ids           = isset( $listing_data['attachment_ids'] ) ? trim( $listing_data['attachment_ids'] ) : '';
	$attachment_ids_ok        = '';
	$response['listing_data'] = $listing_data;
	
	// Check the attachment author
	if ( $attachment_ids != '' ) {
		$attachment_ids = explode( ',', $attachment_ids );
		if ( ! empty( $attachment_ids ) ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( famiau_is_attachment_of_author( $attachment_id, $current_user->ID ) ) {
					$attachment_ids_ok .= $attachment_ids_ok == '' ? $attachment_id : ',' . $attachment_id;
				}
			}
		}
	}
	
	$has_accept_term = isset( $listing_data['_famiau_accept_term'] ) ? $listing_data['_famiau_accept_term'] : 'no';
	
	$car_status                  = isset( $listing_data['_famiau_car_status'] ) ? $listing_data['_famiau_car_status'] : '';
	$make                        = isset( $listing_data['_famiau_make'] ) ? $listing_data['_famiau_make'] : '';
	$model                       = isset( $listing_data['_famiau_model'] ) ? $listing_data['_famiau_model'] : '';
	$year                        = isset( $listing_data['_famiau_year'] ) ? intval( $listing_data['_famiau_year'] ) : 0;
	$listing_title               = isset( $listing_data['listing_title'] ) ? sanitize_text_field( $listing_data['listing_title'] ) : '';
	$body                        = isset( $listing_data['_famiau_body'] ) ? $listing_data['_famiau_body'] : '';
	$mileage                     = isset( $listing_data['_famiau_mileage'] ) ? $listing_data['_famiau_mileage'] : '';
	$fuel_type                   = isset( $listing_data['_famiau_fuel_type'] ) ? $listing_data['_famiau_fuel_type'] : '';
	$engine                      = isset( $listing_data['_famiau_engine'] ) ? $listing_data['_famiau_engine'] : '';
	$gearbox_type                = isset( $listing_data['_famiau_gearbox_type'] ) ? $listing_data['_famiau_gearbox_type'] : '';
	$drive                       = isset( $listing_data['_famiau_drive'] ) ? $listing_data['_famiau_drive'] : '';
	$exterior_color              = isset( $listing_data['_famiau_exterior_color'] ) ? $listing_data['_famiau_exterior_color'] : '';
	$interior_color              = isset( $listing_data['_famiau_interior_color'] ) ? $listing_data['_famiau_interior_color'] : '';
	$registered_date             = isset( $listing_data['_famiau_registered_date'] ) ? $listing_data['_famiau_registered_date'] : '';
	$vin                         = isset( $listing_data['_famiau_vin'] ) ? $listing_data['_famiau_vin'] : '';
	$car_number_of_seats         = isset( $listing_data['_famiau_car_number_of_seats'] ) ? $listing_data['_famiau_car_number_of_seats'] : 0;
	$car_number_of_doors         = isset( $listing_data['_famiau_car_number_of_doors'] ) ? $listing_data['_famiau_car_number_of_doors'] : 0;
	$fueling_system              = isset( $listing_data['_famiau_fueling_system'] ) ? $listing_data['_famiau_fueling_system'] : '';
	$fuel_consumption            = isset( $listing_data['_famiau_fuel_consumption'] ) ? $listing_data['_famiau_fuel_consumption'] : '';
	$car_address                 = isset( $listing_data['_famiau_car_address'] ) ? $listing_data['_famiau_car_address'] : '';
	$car_latitude                = isset( $listing_data['_famiau_car_latitude'] ) ? $listing_data['_famiau_car_latitude'] : '';
	$car_longitude               = isset( $listing_data['_famiau_car_longitude'] ) ? $listing_data['_famiau_car_longitude'] : '';
	$car_features_comforts       = isset( $listing_data['_famiau_car_features_comforts'] ) ? serialize( $listing_data['_famiau_car_features_comforts'] ) : serialize( array() );
	$car_features_entertainments = isset( $listing_data['_famiau_car_features_entertainments'] ) ? serialize( $listing_data['_famiau_car_features_entertainments'] ) : serialize( array() );
	$car_features_safety         = isset( $listing_data['_famiau_car_features_safety'] ) ? serialize( $listing_data['_famiau_car_features_safety'] ) : serialize( array() );
	$car_features_seats          = isset( $listing_data['_famiau_car_features_seats'] ) ? serialize( $listing_data['_famiau_car_features_seats'] ) : serialize( array() );
	$car_features_windows        = isset( $listing_data['_famiau_car_features_windows'] ) ? serialize( $listing_data['_famiau_car_features_windows'] ) : serialize( array() );
	$car_features_others         = isset( $listing_data['_famiau_car_features_others'] ) ? serialize( $listing_data['_famiau_car_features_others'] ) : serialize( array() );
	$video_url                   = isset( $listing_data['_famiau_video_url'] ) ? trim( $listing_data['_famiau_video_url'] ) : '';
	$desc                        = isset( $listing_data['_famiau_desc'] ) ? $listing_data['_famiau_desc'] : '';
	$seller_notes_suggestions    = isset( $listing_data['_famiau_seller_notes_suggestions'] ) ? $listing_data['_famiau_seller_notes_suggestions'] : '';
	$price                       = isset( $listing_data['_famiau_price'] ) ? $listing_data['_famiau_price'] : '';
	$note                        = isset( $listing_data['note'] ) ? $listing_data['note'] : '';
	
	if ( $video_url != '' ) {
		$video_url = esc_url( $video_url );
	}
	
	$add_listing_must_accept_term = isset( $famiau['_famiau_add_listing_must_accept_term'] ) ? $famiau['_famiau_add_listing_must_accept_term'] == 'yes' : true;
	if ( $add_listing_must_accept_term ) {
		if ( $has_accept_term != 'yes' ) {
			$response['message'][] = esc_html__( 'You must accept our terms & conditions', 'famiau' );
			$response['err']       = 'yes';
			wp_send_json( $response );
		}
	}
	
	// Check requirement fields
	// Check car status
	if ( ! in_array( $car_status, array( 'new', 'used', 'certified-used' ) ) ) {
		$response['message'][] = esc_html__( 'Invalid condition!', 'famiau' );
		$response['err']       = 'yes';
	}
	
	// Check make
	$all_make_names = famiau_get_all_make_names();
	if ( ! in_array( $make, $all_make_names ) ) {
		$response['message'][] = esc_html__( 'Invalid maker!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Get all valid models
	$all_models             = famiau_get_models_by_make( $make );
	$response['all_models'] = $all_models;
	// Check valid model
	if ( ! in_array( $model, $all_models ) ) {
		$response['message'][] = esc_html__( 'Invalid model!', 'famiau' );
		$response['err']       = 'yes';
	}
	
	// Check year
	if ( $year < 1700 || $year > date( 'Y' ) ) {
		$response['message'][] = esc_html__( 'Invalid year!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( $response['err'] == 'yes' ) {
		wp_send_json( $response );
	}
	
	// Everything is ok, update the database
	$data = array(
		'author_login'                        => $current_user->user_login,
		'_famiau_car_status'                  => $car_status,
		'_famiau_make'                        => $make,
		'_famiau_model'                       => $model,
		'_famiau_year'                        => $year,
		'listing_title'                       => $listing_title,
		'_famiau_body'                        => $body,
		'_famiau_mileage'                     => $mileage,
		'_famiau_fuel_type'                   => $fuel_type,
		'_famiau_engine'                      => $engine,
		'_famiau_gearbox_type'                => $gearbox_type,
		'_famiau_drive'                       => $drive,
		'_famiau_exterior_color'              => $exterior_color,
		'_famiau_interior_color'              => $interior_color,
		'_famiau_registered_date'             => $registered_date,
		'_famiau_vin'                         => $vin,
		'_famiau_car_number_of_seats'         => $car_number_of_seats,
		'_famiau_car_number_of_doors'         => $car_number_of_doors,
		'_famiau_fueling_system'              => $fueling_system,
		'_famiau_fuel_consumption'            => $fuel_consumption,
		'_famiau_car_address'                 => $car_address,
		'_famiau_car_latitude'                => $car_latitude,
		'_famiau_car_longitude'               => $car_longitude,
		'_famiau_car_features_comforts'       => $car_features_comforts,
		'_famiau_car_features_entertainments' => $car_features_entertainments,
		'_famiau_car_features_safety'         => $car_features_safety,
		'_famiau_car_features_seats'          => $car_features_seats,
		'_famiau_car_features_windows'        => $car_features_windows,
		'_famiau_car_features_others'         => $car_features_others,
		'attachment_ids'                      => $attachment_ids_ok,
		'_famiau_video_url'                   => $video_url,
		'_famiau_desc'                        => $desc,
		'_famiau_seller_notes_suggestions'    => $seller_notes_suggestions,
		'_famiau_price'                       => $price,
		'_famiau_accept_term'                 => $has_accept_term,
		'listing_status'                      => 'waiting',
		'note'                                => $note
	);
	
	$format = array(
		'%s', // $current_user->user_login,
		'%s', // $car_status,
		'%s', // $make,
		'%s', // $model,
		'%d', // $year,
		'%s', // $listing_title,
		'%s', // $body,
		'%d', // $mileage,
		'%s', // $fuel_type,
		'%f', // $engine,
		'%s', // $gearbox_type,
		'%s', // $drive,
		'%s', // $exterior_color,
		'%s', // $interior_color,
		'%s', // $registered_date,
		'%s', // $vin,
		'%d', // $car_number_of_seats,
		'%d', // $car_number_of_doors,
		'%s', // $fueling_system,
		'%s', // $fuel_consumption,
		'%s', // $car_address,
		'%s', // $car_latitude,
		'%s', // $car_longitude,
		'%s', // $car_features_comforts,
		'%s', // $car_features_entertainments,
		'%s', // $car_features_safety,
		'%s', // $car_features_seats,
		'%s', // $car_features_windows,
		'%s', // $car_features_others,
		'%s', // $attachment_ids_ok,
		'%s', // $video_url,
		'%s', // $desc,
		'%s', // $seller_notes_suggestions,
		'%f', // $price,
		'%s', // $has_accept_term,
		'%s', // 'waiting',
		'%s', // $note
	);
	
	$success_count = $wpdb->insert( FAMIAU_LISTINGS_TABLE, $data, $format );
	if ( $success_count ) {
		$response['message'][]        = esc_html__( 'Thank you for submitting the listing, we will review your listing quickly.', 'famiau' );
		$response['my_listings_html'] = famiau_current_user_listings_html();
	} else {
		$response['message'][] = esc_html__( 'Submit listing failed, please try again later', 'famiau' );
		$response['err']       = 'yes';
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_add_new_listing_via_ajax', 'famiau_add_new_listing_via_ajax' );
add_action( 'wp_ajax_nopriv_famiau_add_new_listing_via_ajax', 'famiau_add_new_listing_via_ajax' );

function famiau_delete_my_listing_via_ajax() {
	global $wpdb, $current_user;
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	// Check user permission
	if ( ! current_user_can( 'famiau_user' ) ) {
		$response['message'][] = esc_html__( 'You are not authorized to do this!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
	
	$listings_table = FAMIAU_LISTINGS_TABLE;
	$listing_info   = famiau_get_row_info( $listings_table, "AND id={$listing_id}" );
	if ( ! $listing_info ) {
		$response['message'][] = esc_html__( 'The listing does not exist or has been deleted', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check if listing belong to current user
	if ( $current_user->user_login !== $listing_info->author_login ) {
		$response['message'][] = esc_html__( 'You can\'t delete this listing!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Change listing status to deleted
	$data         = array(
		'listing_status' => 'deleted',
		'product_id'     => 0 // Set product id (famiau post type id) to 0
	);
	$format       = array( '%s', '%d' );
	$where        = array( 'id' => $listing_id, 'author_login' => $current_user->user_login );
	$where_format = array( '%d', '%s' );
	
	$update_results = $wpdb->update( $listings_table, $data, $where, $format, $where_format );
	if ( ! $update_results ) {
		$response['message'][] = esc_html__( 'Database error!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Delete listing post type (famiau)
	/**
	 * Hooked: famiau_delete_listing_post_type - 10
	 */
	do_action( 'famiau_delete_listing', $listing_id, $listing_info->product_id, $current_user );
	
	$response['message'][] = esc_html__( 'The listing has been deleted.', 'famiau' );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_delete_my_listing_via_ajax', 'famiau_delete_my_listing_via_ajax' );

function famiau_to_sold_my_listing_via_ajax() {
	global $wpdb, $current_user;
	
	$response = array(
		'message'                 => array(),
		'html'                    => '',
		'new_listing_status_html' => '',
		'err'                     => 'no'
	);
	
	// Check user permission
	if ( ! current_user_can( 'famiau_user' ) ) {
		$response['message'][] = esc_html__( 'You are not authorized to do this!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
	
	$listings_table = FAMIAU_LISTINGS_TABLE;
	$listing_info   = famiau_get_row_info( $listings_table, "AND id={$listing_id}" );
	if ( ! $listing_info ) {
		$response['message'][] = esc_html__( 'The listing does not exist or has been deleted', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check if listing belong to current user
	if ( $current_user->user_login !== $listing_info->author_login ) {
		$response['message'][] = esc_html__( 'You can\'t change this listing status!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Change listing status to sold
	$data         = array(
		'listing_status' => 'sold',
		'product_id'     => 0 // Set product id (famiau post type id) to 0
	);
	$format       = array( '%s', '%d' );
	$where        = array( 'id' => $listing_id, 'author_login' => $current_user->user_login );
	$where_format = array( '%d', '%s' );
	
	$update_results = $wpdb->update( $listings_table, $data, $where, $format, $where_format );
	if ( ! $update_results ) {
		$response['message'][] = esc_html__( 'Database error!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Delete listing post type (famiau)
	/**
	 * Hooked: famiau_sold_listing_post_type - 10
	 */
	do_action( 'famiau_sold_listing', $listing_id, $listing_info->product_id, $current_user );
	
	$new_listing_info                    = famiau_get_row_info( $listings_table, "AND id={$listing_id}" );
	$response['new_listing_status_html'] = '<span class="famiau-listing-status listing-status-' . esc_attr( $new_listing_info->listing_status ) . '">' . esc_html( $new_listing_info->listing_status ) . '</span>';;
	
	$response['message'][] = esc_html__( 'The listing has been sold.', 'famiau' );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_to_sold_my_listing_via_ajax', 'famiau_to_sold_my_listing_via_ajax' );

function famiau_show_mask_number_via_ajax() {
	global $famiau;
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	// Check user permission
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$dealer_id    = isset( $_POST['dealer_id'] ) ? intval( $_POST['dealer_id'] ) : 0;
	$phone_number = get_user_meta( $dealer_id, 'famiau_user_mobile', true );
	
	$response['html'] = '<i class="fa fa-phone"></i><div class="phone famiau-phone-mask famiau-has-unmask">' . esc_attr( $phone_number ) . '</div>';
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_show_mask_number_via_ajax', 'famiau_show_mask_number_via_ajax' );
add_action( 'wp_ajax_nopriv_famiau_show_mask_number_via_ajax', 'famiau_show_mask_number_via_ajax' );

function famiau_login_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau-login';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$username   = isset( $_POST['username'] ) ? $_POST['username'] : '';
	$password   = isset( $_POST['password'] ) ? $_POST['password'] : '';
	$rememberme = isset( $_POST['rememberme'] ) ? $_POST['rememberme'] : '';
	
	$creds                  = array();
	$creds['user_login']    = $username;
	$creds['user_password'] = $password;
	$creds['remember']      = $rememberme != '';
	$user                   = wp_signon( $creds, false );
	if ( is_wp_error( $user ) ) {
		$response['message'][] = $user->get_error_message();
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['message'][] = esc_html__( 'You have successfully logged in, reloading the page', 'famiau' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_nopriv_famiau_login_via_ajax', 'famiau_login_via_ajax' );

function famiau_reg_new_user_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$user_login   = isset( $_POST['username'] ) ? stripcslashes( $_POST['username'] ) : '';
	$user_email   = isset( $_POST['email'] ) ? strtolower( $_POST['email'] ) : '';
	$user_pass    = isset( $_POST['password'] ) ? $_POST['password'] : '';
	$cf_user_pass = isset( $_POST['cf_password'] ) ? $_POST['cf_password'] : '';
	
	if ( $user_pass === '' || $user_pass !== $cf_user_pass ) {
		$response['message'][] = esc_html__( 'Passwords do not match!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$user_data = array(
		'user_login'    => $user_login,
		'user_email'    => $user_email,
		'user_pass'     => $user_pass,
		'user_nicename' => $user_email,
		'display_name'  => $user_email,
		'role'          => 'famiau_user'
	);
	$user_id   = wp_insert_user( $user_data );
	if ( is_wp_error( $user_id ) ) {
		$response['message'][] = $user_id->get_error_message();
		wp_send_json( $response );
	}
	
	$response['message'][] = esc_html__( 'You have successfully registered your new account.', 'famiau' );
	
	// Login after register successfully
	$creds                  = array();
	$creds['user_login']    = $user_login;
	$creds['user_password'] = $user_pass;
	$user                   = wp_signon( $creds, false );
	if ( is_wp_error( $user ) ) {
		$response['message'][] = $user->get_error_message();
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['message'][] = esc_html__( 'You have successfully logged in, reloading the page', 'famiau' );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_nopriv_famiau_reg_new_user_via_ajax', 'famiau_reg_new_user_via_ajax' );

function famiau_account_navigation() {
	famiau_wc_get_template_part( 'account/famiau-navigation', '' );
}

add_action( 'famiau_account_navigation', 'famiau_account_navigation' );

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request     URL the user is coming from.
 * @param object $user        Logged user's data.
 *
 * @return string
 */

function famiau_login_redirect( $redirect_to, $request, $user ) {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return $redirect_to;
	}
	// is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// check for subscribers
		if ( in_array( 'famiau_user', $user->roles ) ) {
			$famiau_account_page = famiau_get_page( 'account' );
			if ( $famiau_account_page > 0 ) {
				$redirect_to = get_permalink( $famiau_account_page );
			}
		}
	}
	
	return $redirect_to;
}

add_filter( 'login_redirect', 'famiau_login_redirect', 10, 3 );

function famiau_wc_login_redirect( $redirect_to, $user ) {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return $redirect_to;
	}
	// is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for subscribers
		if ( in_array( 'famiau_user', $user->roles ) ) {
			$famiau_account_page = famiau_get_page( 'account' );
			if ( $famiau_account_page > 0 ) {
				$redirect_to = get_permalink( $famiau_account_page );
			}
		}
	}
	
	return $redirect_to;
}

add_filter( 'woocommerce_login_redirect', 'famiau_wc_login_redirect', 10, 2 );

function famiau_logout_url() {
	$automotive_page = famiau_get_page( 'automotive' );
	$logout_url      = '';
	if ( $automotive_page > 0 ) {
		$logout_url = wp_logout_url( esc_url( get_permalink( $automotive_page ) ) );
	} else {
		$logout_url = wp_logout_url( esc_url( get_home_url() ) );
	}
	
	return apply_filters( 'famiau_logout_url', $logout_url );
}

function the_famiau_pagination( $max_num_pages = 0, $paged = 0 ) {
	global $wp_query;
	
	$max_num_pages = intval( $max_num_pages );
	if ( $max_num_pages <= 0 ) {
		$max_num_pages = $wp_query->max_num_pages;
	}
	
	$paged = intval( $paged );
	
	$page_nav_args = array(
		'total'     => $max_num_pages,
		'prev_text' => '<i class="fa fa-angle-left"></i>',
		'next_text' => '<i class="fa fa-angle-right"></i>'
	);
	
	if ( $paged > 1 ) {
		$page_nav_args['current'] = $paged;
	}
	
	echo '<nav class="navigation pagination" role="navigation">
			<h2 class="screen-reader-text">' . esc_html__( 'Listings Navigation', 'famiau' ) . '</h2>
			<div class="nav-links">' . paginate_links( $page_nav_args ) . '</div>
		</nav>';
}

function the_famiau_pagination_bak( $max_num_pages = 0 ) {
	global $wp_query;
	
	$max_num_pages = intval( $max_num_pages );
	if ( $max_num_pages <= 0 ) {
		$max_num_pages = $wp_query->max_num_pages;
	}
	// Don't print empty markup if there's only one page.
	if ( $max_num_pages >= 2 ) {
		echo get_the_posts_pagination(
			array(
				'total'              => $max_num_pages,
				'screen_reader_text' => esc_html__( 'Listings Navigation', 'famiau' ),
				'before_page_number' => '',
				'prev_text'          => '<i class="fa fa-angle-left"></i>',
				'next_text'          => '<i class="fa fa-angle-right"></i>',
			)
		);
	}
}