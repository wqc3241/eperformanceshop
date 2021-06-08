<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famiau_save_all_settings_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! wp_verify_nonce( $nonce, 'famiau_backend_nonce' ) ) {
		$response['message'][] = esc_html__( 'Security check error!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$all_settings             = isset( $_POST['all_settings'] ) ? $_POST['all_settings'] : array();
	$response['all_settings'] = $all_settings;
	$new_all_settings         = array();
	
	if ( isset( $all_settings['famiau_settings'] ) ) {
		if ( ! empty( $all_settings['famiau_settings'] ) ) {
			foreach ( $all_settings['famiau_settings'] as $setting ) {
				$new_all_settings[ $setting['setting_key'] ] = $setting['setting_val'];
			}
		}
		unset( $all_settings['famiau_settings'] );
	}
	
	if ( isset( $all_settings['all_makes'] ) ) {
		$all_makes = array();
		if ( ! empty( $all_settings['all_makes'] ) ) {
			foreach ( $all_settings['all_makes'] as $make ) {
				$all_makes[] = $make;
			}
		}
		$new_all_settings['all_makes'] = $all_makes;
		unset( $all_settings['all_makes'] );
	}
	
	if ( isset( $all_settings['all_fuel_types'] ) ) {
		$all_fuel_types = array();
		if ( ! empty( $all_settings['all_fuel_types'] ) ) {
			foreach ( $all_settings['all_fuel_types'] as $fuel_type ) {
				// Don't duplicate fuel type
				if ( in_array( $fuel_type, $all_fuel_types ) ) {
					continue;
				}
				$all_fuel_types[] = $fuel_type;
			}
		}
		$new_all_settings['all_fuel_types'] = $all_fuel_types;
		unset( $all_settings['all_makes'] );
	}
	
	if ( ! empty( $all_settings ) ) {
		foreach ( $all_settings as $option_key => $option_val ) {
			if ( is_string( $option_val ) ) {
				$new_all_settings[ $option_key ] = $option_val;
			} elseif ( is_array( $option_val ) ) {
				$items = array();
				foreach ( $option_val as $item ) {
					// Don't duplicate item
					if ( in_array( $item, $items ) ) {
						continue;
					}
					$items[] = $item;
				}
				$new_all_settings[ $option_key ] = $items;
			}
		}
	}
	
	$response['new_all_settings'] = $new_all_settings;
	update_option( 'famiau_all_settings', $new_all_settings );

//	$response['all_settings']     = $all_settings;
//	$response['new_all_settings'] = $new_all_settings;
	
	$response['message'][] = esc_html__( 'All settings saved', 'famiau' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_save_all_settings_via_ajax', 'famiau_save_all_settings_via_ajax' );

/**
 * Add a post display state for special Fami Automotive pages in the page list table.
 *
 * @param array   $post_states An array of post display states.
 * @param WP_Post $post        The current post object.
 */
function famiau_add_display_post_states( $post_states, $post ) {
	$page_for_automotive = famiau_get_page( 'automotive' );
	$page_for_account    = famiau_get_page( 'account' );
	$page_for_term       = famiau_get_page( 'term' );
	if ( $page_for_automotive === $post->ID ) {
		$post_states['_famiau_page_for_automotive'] = __( 'Automotive Page', 'famiau' );
	}
	if ( $page_for_account === $post->ID ) {
		$post_states['_famiau_page_for_account'] = __( 'Automotive Account', 'famiau' );
	}
	if ( $page_for_term === $post->ID ) {
		$post_states['_famiau_page_for_term'] = __( 'Automotive Term Page', 'famiau' );
	}
	
	return $post_states;
}

add_filter( 'display_post_states', 'famiau_add_display_post_states', 10, 2 );

function famiau_backend_mega_filter_results_via_ajax() {
	global $wpdb;
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_backend_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'You do not have enough authority to do this!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$filter_data             = isset( $_POST['filter_data'] ) ? $_POST['filter_data'] : array();
	$response['filter_data'] = $filter_data;
	
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
		'listing_status' => 'listing_status'
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
			$where     .= "{$max_relation} _famiau_price <= '%d' ";
			$prepare[] = $filter_data['max_price'];
		}
		$where .= ")";
	}
	
	// Have images conditional?
	$must_have_imgs = isset( $filter_data['have_imgs'] ) ? $filter_data['have_imgs'] == 'yes' : false;
	if ( $must_have_imgs ) {
		$where .= "AND attachment_ids <> '' ";
	}

//	$response['where'] = $where;
//	$response['prepare'] = $prepare;
	
	// Get html results
	$response['html'] = famiau_admin_listing_query_results( $where, $prepare );
	if ( $response['html'] == '' ) {
		$response['message'][] = esc_html__( 'No result is found. Please try again with other filter criteria.', 'famiau' );
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_backend_mega_filter_results_via_ajax', 'famiau_backend_mega_filter_results_via_ajax' );

function famiau_do_admin_listing_action_via_ajax() {
	global $wpdb;
	$response = array(
		'message'            => array(),
		'html'               => '',
		'actions_html'       => '',
		'old_listing_status' => '',
		'new_listing_status' => '',
		'err'                => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_backend_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'You do not have enough authority to do this!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$action_name = isset( $_POST['action_name'] ) ? $_POST['action_name'] : '';
	$listing_id  = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
	
	$table_name = FAMIAU_LISTINGS_TABLE;
	
	// Get current listing info
	$this_listing_info = famiau_get_row_info( FAMIAU_LISTINGS_TABLE, "AND id = '{$listing_id}'" );
	
	// Check listing exists
	if ( ! $this_listing_info ) {
		$response['message'][] = esc_html__( 'The listing does not exist or has been deleted', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check valid action
	if ( ! in_array( $action_name, array( 'waiting', 'approved', 'deleted', 'sold' ) ) ) {
		$response['message'][] = esc_html__( 'Invalid listing status', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['old_listing_status'] = $this_listing_info->listing_status;
	
	$data           = array(
		'listing_status' => $action_name
	);
	$where          = array(
		'id' => $listing_id
	);
	$format         = array( '%s' );
	$where_format   = array( '%d' );
	$update_results = $wpdb->update( $table_name, $data, $where, $format, $where_format );
	
	if ( ! $update_results ) {
		$response['message'][] = esc_html__( 'Update listing status failed. Please try again later.', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	// New listing info
	$new_listing_info               = famiau_get_row_info( FAMIAU_LISTINGS_TABLE, "AND id = '{$listing_id}'" );
	$post_id                        = intval( $new_listing_info->product_id );
	$response['new_listing_status'] = $new_listing_info->listing_status;
	
	$actions = array(
		'waiting'  => esc_html__( 'Review', 'famiau' ),
		'approved' => esc_html__( 'Approve', 'famiau' ),
		'deleted'  => esc_html__( 'Delete', 'famiau' ),
		'sold'     => esc_html__( 'Sold', 'famiau' ),
	);
	
	if ( isset( $actions[ $new_listing_info->listing_status ] ) ) {
		unset( $actions[ $new_listing_info->listing_status ] );
	}
	
	if ( ! empty( $actions ) ) {
		foreach ( $actions as $action => $action_text ) {
			$response['actions_html'] .= '<a href="#" data-action="' . esc_attr( $action ) . '" class="famiau-action famiau-action-' . esc_attr( $action ) . '">' . sanitize_text_field( $action_text ) . '</a>';
		}
	}
	
	// Check author
	$author = get_user_by( 'login', $new_listing_info->author_login );
	if ( ! $author ) {
		$response['message'][] = esc_html__( 'Invalid author!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$car_statuses = array(
		'new'            => esc_html__( 'New', 'famiau' ),
		'used'           => esc_html__( 'Used', 'famiau' ),
		'certified-used' => esc_html__( 'Certified Used', 'famiau' )
	);
	
	switch ( $action_name ) {
		case 'waiting':
			// Delete the listing post
			$post = wp_delete_post( $post_id, true );
//			if ( ! $post ) {
//				$response['message'][] = esc_html__( 'Error while deleting the listing!', 'famiau' );
//				$response['err']       = 'yes';
//				wp_send_json( $response );
//			}
			break;
		case 'approved':
			if ( $post_id > 0 ) {
				$post_status = get_post_status( $post_id );
				// Check is listing exist or not
				if ( false !== $post_status ) {
					$response['message'][] = sprintf( esc_html__( 'The listing already exist. Current listing status "%s"', 'famiau' ), $post_status );
					$response['err']       = 'yes';
					wp_send_json( $response );
				}
			}
			
			$listing_title = trim( $new_listing_info->listing_title );
			if ( $listing_title == '' ) {
				// $listing_title = $new_listing_info->
				$car_status_text = isset( $car_statuses[ $new_listing_info->_famiau_car_status ] ) ? $car_statuses[ $new_listing_info->_famiau_car_status ] : '';
				$listing_title   = $car_status_text . ' ' . $new_listing_info->_famiau_make . ' ' . $new_listing_info->_famiau_model . ' ' . $new_listing_info->_famiau_year;
			}
			
			$new_product_args = array(
				'post_author'  => $author->ID,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_title'   => $listing_title,
				'post_parent'  => '',
				'post_type'    => 'famiau',
			);
			//Create product
			$post_id = wp_insert_post( $new_product_args );
			if ( $post_id ) {
				
				$attachment_ids = explode( ',', $new_listing_info->attachment_ids );
				
				// Set first image as feature image
				if ( isset( $attachment_ids[0] ) ) {
					set_post_thumbnail( $post_id, $attachment_ids[0] );
				}
				
				$avai_car_statuses = array( 'new', 'used', 'certified-used' );
				$car_status        = $new_listing_info->_famiau_car_status;
				if ( ! in_array( $car_status, $avai_car_statuses ) ) {
					$car_status = '';
				}
				
				$registered_date = trim( str_replace( '-', '/', $new_listing_info->_famiau_registered_date ) );
				$registered_date = date( 'Y-m-d', strtotime( $registered_date ) );
				
				// $meta_data = get_post_meta( $post_id, '_famiau_metabox_options', true );
				$meta_data = array(
					'_famiau_make'                        => $new_listing_info->_famiau_make,
					'_famiau_model'                       => $new_listing_info->_famiau_model,
					'_famiau_model_select'                => $new_listing_info->_famiau_model,
					'_famiau_fuel_type'                   => $new_listing_info->_famiau_fuel_type,
					'_famiau_car_status'                  => $car_status,
					'_famiau_year'                        => $new_listing_info->_famiau_year,
					'_famiau_gearbox_type'                => $new_listing_info->_famiau_gearbox_type,
					'_famiau_body'                        => $new_listing_info->_famiau_body,
					'_famiau_drive'                       => $new_listing_info->_famiau_drive,
					'_famiau_exterior_color'              => $new_listing_info->_famiau_exterior_color,
					'_famiau_interior_color'              => $new_listing_info->_famiau_interior_color,
					'_famiau_seller_notes_suggestions'    => $new_listing_info->_famiau_seller_notes_suggestions,
					'_famiau_mileage'                     => $new_listing_info->_famiau_mileage,
					'_famiau_engine'                      => $new_listing_info->_famiau_engine,
					'_famiau_car_number_of_seats'         => $new_listing_info->_famiau_car_number_of_seats,
					'_famiau_car_number_of_doors'         => $new_listing_info->_famiau_car_number_of_doors,
					'_famiau_fueling_system'              => $new_listing_info->_famiau_fueling_system,
					'_famiau_fuel_consumption'            => $new_listing_info->_famiau_fuel_consumption,
					'_famiau_registered_date'             => $registered_date,
					'_famiau_vin'                         => $new_listing_info->_famiau_vin,
					'_famiau_car_address'                 => $new_listing_info->_famiau_car_address,
					'_famiau_car_latitude'                => $new_listing_info->_famiau_car_latitude,
					'_famiau_car_longitude'               => $new_listing_info->_famiau_car_longitude,
					'_famiau_car_features_comforts'       => unserialize( $new_listing_info->_famiau_car_features_comforts ),
					'_famiau_car_features_entertainments' => unserialize( $new_listing_info->_famiau_car_features_entertainments ),
					'_famiau_car_features_safety'         => unserialize( $new_listing_info->_famiau_car_features_safety ),
					'_famiau_car_features_seats'          => unserialize( $new_listing_info->_famiau_car_features_seats ),
					'_famiau_car_features_windows'        => unserialize( $new_listing_info->_famiau_car_features_windows ),
					'_famiau_car_features_others'         => unserialize( $new_listing_info->_famiau_car_features_others ),
					'_famiau_desc'                        => $new_listing_info->_famiau_desc,
					'_famiau_video_url'                   => $new_listing_info->_famiau_video_url,
					'_famiau_gallery'                     => $new_listing_info->attachment_ids,
					'_famiau_price'                       => $new_listing_info->_famiau_price,
				);
				update_post_meta( $post_id, '_famiau_metabox_options', $meta_data );
				
				$data         = array(
					'product_id' => $post_id
				);
				$where        = array(
					'id' => $new_listing_info->id
				);
				$format       = array( '%d' );
				$where_format = array( '%d' );
				$wpdb->update( $table_name, $data, $where, $format, $where_format );
				$response['message'][] = sprintf( esc_html__( 'New listing created: %s (#%s)', 'famiau' ), $listing_title, $post_id );
			}
			
			break;
		case 'deleted':
		case 'sold':
			// Delete the product
			$post = wp_delete_post( $post_id, true );
//			if ( ! $post ) {
//				$response['message'][] = esc_html__( 'Error while deleting product!', 'famiau' );
//				$response['err']       = 'yes';
//				wp_send_json( $response );
//			}
			break;
	}
	
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_do_admin_listing_action_via_ajax', 'famiau_do_admin_listing_action_via_ajax' );

/**
 * Deprecated   This function only works with WooCommerce
 */
function famiau_do_admin_listing_action_via_ajax_bak() {
	global $wpdb;
	$response = array(
		'message'            => array(),
		'html'               => '',
		'actions_html'       => '',
		'old_listing_status' => '',
		'new_listing_status' => '',
		'err'                => 'no'
	);
	
	$security     = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	$nonce_action = 'famiau_backend_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'You do not have enough authority to do this!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$action_name = isset( $_POST['action_name'] ) ? $_POST['action_name'] : '';
	$listing_id  = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
	
	$table_name = FAMIAU_LISTINGS_TABLE;
	
	// Get current listing info
	$this_listing_info = famiau_get_row_info( FAMIAU_LISTINGS_TABLE, "AND id = '{$listing_id}'" );
	
	// Check listing exists
	if ( ! $this_listing_info ) {
		$response['message'][] = esc_html__( 'The listing does not exist or has been deleted', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check valid action
	if ( ! in_array( $action_name, array( 'waiting', 'approved', 'deleted' ) ) ) {
		$response['message'][] = esc_html__( 'Invalid listing status', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['old_listing_status'] = $this_listing_info->listing_status;
	
	$data           = array(
		'listing_status' => $action_name
	);
	$where          = array(
		'id' => $listing_id
	);
	$format         = array( '%s' );
	$where_format   = array( '%d' );
	$update_results = $wpdb->update( $table_name, $data, $where, $format, $where_format );
	
	if ( ! $update_results ) {
		$response['message'][] = esc_html__( 'Update listing status failed. Please try again later.', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	// New listing info
	$new_listing_info               = famiau_get_row_info( FAMIAU_LISTINGS_TABLE, "AND id = '{$listing_id}'" );
	$product_id                     = intval( $new_listing_info->product_id );
	$response['new_listing_status'] = $new_listing_info->listing_status;
	
	$actions = array(
		'waiting'  => esc_html__( 'Review', 'famiau' ),
		'approved' => esc_html__( 'Approve', 'famiau' ),
		'deleted'  => esc_html__( 'Delete', 'famiau' ),
	);
	
	if ( isset( $actions[ $new_listing_info->listing_status ] ) ) {
		unset( $actions[ $new_listing_info->listing_status ] );
	}
	
	if ( ! empty( $actions ) ) {
		foreach ( $actions as $action => $action_text ) {
			$response['actions_html'] .= '<a href="#" data-action="' . esc_attr( $action ) . '" class="famiau-action famiau-action-' . esc_attr( $action ) . '">' . sanitize_text_field( $action_text ) . '</a>';
		}
	}
	
	if ( ! class_exists( 'WooCommerce' ) ) {
		$response['message'][] = esc_html__( 'WooCommerce is not installed or not activated.', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	// Check author
	$author = get_user_by( 'login', $new_listing_info->author_login );
	if ( ! $author ) {
		$response['message'][] = esc_html__( 'Invalid author!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$car_statuses = array(
		'new'            => esc_html__( 'New', 'famiau' ),
		'used'           => esc_html__( 'Used', 'famiau' ),
		'certified-used' => esc_html__( 'Certified Used', 'famiau' )
	);
	
	switch ( $action_name ) {
		case 'waiting':
			// Delete the product
			$post = wp_delete_post( $product_id, true );
//			if ( ! $post ) {
//				$response['message'][] = esc_html__( 'Error while deleting product!', 'famiau' );
//				$response['err']       = 'yes';
//				wp_send_json( $response );
//			}
			break;
		case 'approved':
			// Create new product if not exist
			$product = wc_get_product( $product_id );
			if ( $product ) {
				if ( $product->is_type( 'famiau' ) ) {
					$response['message'][] = esc_html__( 'The product already exist.', 'famiau' );
					$response['err']       = 'yes';
					wp_send_json( $response );
				}
			}
			
			$listing_title = trim( $new_listing_info->listing_title );
			if ( $listing_title == '' ) {
				// $listing_title = $new_listing_info->
				$car_status_text = isset( $car_statuses[ $new_listing_info->_famiau_car_status ] ) ? $car_statuses[ $new_listing_info->_famiau_car_status ] : '';
				$listing_title   = $car_status_text . ' ' . $new_listing_info->_famiau_make . ' ' . $new_listing_info->_famiau_model . ' ' . $new_listing_info->_famiau_year;
			}
			
			$new_product_args = array(
				'post_author'  => $author->ID,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_title'   => $listing_title,
				'post_parent'  => '',
				'post_type'    => 'product',
			);
			//Create product
			$product_id = wp_insert_post( $new_product_args );
			if ( $product_id ) {
				
				update_post_meta( $product_id, '_visibility', 'visible' );
				update_post_meta( $product_id, '_stock_status', 'instock' );
				update_post_meta( $product_id, 'total_sales', '0' );
				update_post_meta( $product_id, '_downloadable', 'no' );
				update_post_meta( $product_id, '_virtual', 'no' );
				update_post_meta( $product_id, '_regular_price', '' );
				update_post_meta( $product_id, '_sale_price', '' );
				update_post_meta( $product_id, '_purchase_note', '' );
				update_post_meta( $product_id, '_featured', '' );
				update_post_meta( $product_id, '_weight', '' );
				update_post_meta( $product_id, '_length', '' );
				update_post_meta( $product_id, '_width', '' );
				update_post_meta( $product_id, '_height', '' );
				update_post_meta( $product_id, '_sku', '' );
				update_post_meta( $product_id, '_product_attributes', array() );
				update_post_meta( $product_id, '_sale_price_dates_from', '' );
				update_post_meta( $product_id, '_sale_price_dates_to', '' );
				update_post_meta( $product_id, '_price', '' );
				update_post_meta( $product_id, '_sold_individually', '' );
				update_post_meta( $product_id, '_manage_stock', 'no' );
				update_post_meta( $product_id, '_backorders', 'no' );
				update_post_meta( $product_id, '_stock', '' );
				update_post_meta( $product_id, '_product_image_gallery', $new_listing_info->attachment_ids );
				
				$attachment_ids = explode( ',', $new_listing_info->attachment_ids );
				
				// Set first image as feature image
				if ( isset( $attachment_ids[0] ) ) {
					set_post_thumbnail( $product_id, $attachment_ids[0] );
				}
				
				$avai_car_statuses = array( 'new', 'used', 'certified-used' );
				$car_status        = $new_listing_info->_famiau_car_status;
				if ( ! in_array( $car_status, $avai_car_statuses ) ) {
					$car_status = '';
				}
				
				$features_comforts       = json_encode( unserialize( $new_listing_info->_famiau_car_features_comforts ) );
				$features_entertainments = json_encode( unserialize( $new_listing_info->_famiau_car_features_entertainments ) );
				$features_safety         = json_encode( unserialize( $new_listing_info->_famiau_car_features_safety ) );
				$features_seats          = json_encode( unserialize( $new_listing_info->_famiau_car_features_seats ) );
				$features_windows        = json_encode( unserialize( $new_listing_info->_famiau_car_features_windows ) );
				$features_others         = json_encode( unserialize( $new_listing_info->_famiau_car_features_others ) );
				$registered_date         = trim( str_replace( '-', '/', $new_listing_info->_famiau_registered_date ) );
				$registered_date         = date( 'Y-m-d', strtotime( $registered_date ) );
				// $registered_date = preg_replace( '#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $registered_date );
				
				wp_set_object_terms( $product_id, 'famiau', 'product_type' );
				update_post_meta( $product_id, '_famiau_car_status', $car_status );
				update_post_meta( $product_id, '_famiau_make', $new_listing_info->_famiau_make );
				update_post_meta( $product_id, '_famiau_model', $new_listing_info->_famiau_model );
				update_post_meta( $product_id, '_famiau_year', $new_listing_info->_famiau_year );
				update_post_meta( $product_id, '_famiau_body', $new_listing_info->_famiau_body );
				update_post_meta( $product_id, '_famiau_mileage', $new_listing_info->_famiau_mileage );
				update_post_meta( $product_id, '_famiau_fuel_type', $new_listing_info->_famiau_fuel_type );
				update_post_meta( $product_id, '_famiau_engine', $new_listing_info->_famiau_engine );
				update_post_meta( $product_id, '_famiau_gearbox_type', $new_listing_info->_famiau_gearbox_type );
				update_post_meta( $product_id, '_famiau_drive', $new_listing_info->_famiau_drive );
				update_post_meta( $product_id, '_famiau_exterior_color', $new_listing_info->_famiau_exterior_color );
				update_post_meta( $product_id, '_famiau_interior_color', $new_listing_info->_famiau_interior_color );
				update_post_meta( $product_id, '_famiau_registered_date', $registered_date );
				update_post_meta( $product_id, '_famiau_vin', $new_listing_info->_famiau_vin );
				update_post_meta( $product_id, '_famiau_car_address', $new_listing_info->_famiau_car_address );
				update_post_meta( $product_id, '_famiau_car_latitude', $new_listing_info->_famiau_car_latitude );
				update_post_meta( $product_id, '_famiau_car_longitude', $new_listing_info->_famiau_car_longitude );
				update_post_meta( $product_id, '_famiau_car_features_comforts', $features_comforts );
				update_post_meta( $product_id, '_famiau_car_features_entertainments', $features_entertainments );
				update_post_meta( $product_id, '_famiau_car_features_safety', $features_safety );
				update_post_meta( $product_id, '_famiau_car_features_seats', $features_seats );
				update_post_meta( $product_id, '_famiau_car_features_windows', $features_windows );
				update_post_meta( $product_id, '_famiau_car_features_others', $features_others );
				update_post_meta( $product_id, '_famiau_video_url', $new_listing_info->_famiau_video_url );
				update_post_meta( $product_id, '_famiau_seller_notes_suggestions', $new_listing_info->_famiau_seller_notes_suggestions );
				update_post_meta( $product_id, '_famiau_price', $new_listing_info->_famiau_price );
				
				$data         = array(
					'product_id' => $product_id
				);
				$where        = array(
					'id' => $new_listing_info->id
				);
				$format       = array( '%d' );
				$where_format = array( '%d' );
				$wpdb->update( $table_name, $data, $where, $format, $where_format );
				$response['message'][] = sprintf( esc_html__( 'New listing created: %s (#%s)', 'famiau' ), $listing_title, $product_id );
			}
			
			break;
		case 'deleted':
			// Delete the product
			$post = wp_delete_post( $product_id, true );
//			if ( ! $post ) {
//				$response['message'][] = esc_html__( 'Error while deleting product!', 'famiau' );
//				$response['err']       = 'yes';
//				wp_send_json( $response );
//			}
			break;
	}
	
	
	wp_send_json( $response );
	die();
}

function famiau_export_settings_link() {
	$nonce = wp_create_nonce( 'famiau-export-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'famiau_export_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

function famiau_import_settings_action_link() {
	$nonce = wp_create_nonce( 'famiau-import-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'famiau_import_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

/**
 * Export all settings via ajax
 */
function famiau_export_all_settings() {
	global $famiau;
	
	header( 'Content-disposition: attachment; filename=listings_settings.json' );
	header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'famiau-export-settings';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		die( esc_html__( 'Security check error', 'famiau' ) );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		die( esc_html__( 'Cheating!? Huh?', 'famiau' ) );
	}
	
	die( wp_json_encode( $famiau ) );
}

add_action( 'wp_ajax_famiau_export_all_settings', 'famiau_export_all_settings' );

function famiau_import_all_settings() {
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'famiau_backend_nonce';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['files'] = $_FILES;
	
	if ( ! isset( $_FILES['famiau_import_file']['error'] ) || is_array( $_FILES['famiau_import_file']['error'] ) ) {
		$response['message'][] = esc_html__( 'Invalid parameters.', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	switch ( $_FILES['famiau_import_file']['error'] ) {
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			$response['message'][] = esc_html__( 'No file sent.', 'famiau' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			$response['message'][] = esc_html__( 'Exceeded filesize limit.', 'famiau' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		default:
			$response['message'][] = esc_html__( 'Unknown errors.', 'famiau' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
	}
	
	$imported_file_name = isset( $_FILES['famiau_import_file']['name'] ) ? $_FILES['famiau_import_file']['name'] : null;
	
	// Check file size
	if ( $_FILES['famiau_import_file']['size'] > 500000 ) {
		$response['message'][] = esc_html__( 'Sorry, uploaded file is too large!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check file type
	$file_ext = famiau_get_file_ext_if_allowed( $imported_file_name );
	if ( $file_ext !== 'json' ) {
		$response['message'][] = esc_html__( 'Wrong file extension!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$upload_dir  = wp_upload_dir();
	$target_file = $upload_dir . basename( $imported_file_name );
	if ( move_uploaded_file( $_FILES['famiau_import_file']['tmp_name'], $target_file ) ) {
		if ( file_exists( $target_file ) ) {
			WP_Filesystem();
			global $wp_filesystem;
			$file_content = $wp_filesystem->get_contents( $target_file );
			
			$response['file_content'] = $file_content;
			// Remove file after read
			$wp_filesystem->delete( $target_file );
			
			// Check JSON format
			$all_settings = json_decode( $file_content, true );
			if ( ! $all_settings ) {
				$response['message'][] = esc_html__( 'Wrong file format!!!', 'famiau' );
				$response['err']       = 'yes';
				wp_send_json( $response );
			}
			
			// EVERYTHING IS OK FOR IMPORT SETTINGS
			update_option( 'famiau_all_settings', $all_settings );
			$response['message'][] = esc_html__( 'All settings imported. Reloading the page...', 'famiau' );
			wp_send_json( $response );
			
		} else {
			$response['message'][] = esc_html__( 'Can\'t find moved file!!!', 'famiau' );
			$response['err']       = 'yes';
			wp_send_json( $response );
		}
	} else {
		$response['message'][] = esc_html__( 'Can\'t move file!!!', 'famiau' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famiau_import_all_settings', 'famiau_import_all_settings' );

/**
 * @param      $filename
 * @param null $mimes
 *
 * @return array
 */
function famiau_check_filetype( $filename, $mimes = null ) {
	if ( empty( $mimes ) ) {
		$mimes = array(
			'json' => 'application/json'
		);
	}
	
	$type = false;
	$ext  = false;
	
	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext  = $ext_matches[1];
			break;
		}
	}
	
	return compact( 'ext', 'type' );
}

function famiau_get_file_ext_if_allowed( $filename, $mimes = null ) {
	$filetype = famiau_check_filetype( $filename, $mimes );
	if ( isset( $filetype['ext'] ) ) {
		return $filetype['ext'];
	}
	
	return '';
}

/**
 * Check the automotive listings table when save famiau post type
 *
 * @param $post_id
 */
function famiau_save_post_callback( $post_id ) {
	global $post, $wpdb;
	if ( ! isset( $post->post_type ) ) {
		return;
	}
	if ( $post->post_type != 'famiau' || ! current_user_can( 'administrator' ) || wp_is_post_revision( $post ) ) {
		return;
	}
	
	$post_id = intval( $post_id );
	
	// if post id exist in the automotive listings table
	$row       = famiau_get_row_info( FAMIAU_LISTINGS_TABLE, "AND product_id={$post_id}" );
	$is_update = false;
	if ( isset( $row->product_id ) ) {
		$is_update = true;
	}
	
	if ( ! isset( $_POST['_famiau_metabox_options'] ) ) {
		return;
	}
	
	$listing_data = $_POST['_famiau_metabox_options'];
	$author_id    = $post->post_author;
	$author       = get_user_by( 'ID', $author_id );
	
	$listing_gallery = '';
	if ( has_post_thumbnail( $post_id ) ) {
		$listing_gallery .= get_post_thumbnail_id( $post_id );
	}
	$listing_gallery = isset( $listing_data['_famiau_gallery'] ) ? $listing_gallery . ',' . $listing_data['_famiau_gallery'] : $listing_gallery;
	
	
	// Add new listing to listings table if not exist
	$listing_title                       = isset( $_POST['post_title'] ) ? $_POST['post_title'] : '';
	$author_login                        = $author->user_login;
	$_famiau_car_status                  = isset( $listing_data['_famiau_car_status'] ) ? $listing_data['_famiau_car_status'] : '';
	$_famiau_make                        = isset( $listing_data['_famiau_make'] ) ? $listing_data['_famiau_make'] : '';
	$_famiau_model                       = isset( $listing_data['_famiau_model'] ) ? $listing_data['_famiau_model'] : '';
	$_famiau_year                        = isset( $listing_data['_famiau_year'] ) ? $listing_data['_famiau_year'] : 0;
	$_famiau_body                        = isset( $listing_data['_famiau_body'] ) ? $listing_data['_famiau_body'] : '';
	$_famiau_mileage                     = isset( $listing_data['_famiau_mileage'] ) ? $listing_data['_famiau_mileage'] : '';
	$_famiau_fuel_type                   = isset( $listing_data['_famiau_fuel_type'] ) ? $listing_data['_famiau_fuel_type'] : '';
	$_famiau_engine                      = isset( $listing_data['_famiau_engine'] ) ? $listing_data['_famiau_engine'] : 0;
	$_famiau_gearbox_type                = isset( $listing_data['_famiau_gearbox_type'] ) ? $listing_data['_famiau_gearbox_type'] : '';
	$_famiau_drive                       = isset( $listing_data['_famiau_drive'] ) ? $listing_data['_famiau_drive'] : '';
	$_famiau_exterior_color              = isset( $listing_data['_famiau_exterior_color'] ) ? $listing_data['_famiau_exterior_color'] : '';
	$_famiau_interior_color              = isset( $listing_data['_famiau_interior_color'] ) ? $listing_data['_famiau_interior_color'] : '';
	$_famiau_registered_date             = isset( $listing_data['_famiau_registered_date'] ) ? $listing_data['_famiau_registered_date'] : '';
	$_famiau_car_address                 = isset( $listing_data['_famiau_car_address'] ) ? $listing_data['_famiau_car_address'] : '';
	$_famiau_vin                         = isset( $listing_data['_famiau_vin'] ) ? $listing_data['_famiau_vin'] : '';
	$_famiau_car_number_of_seats         = isset( $listing_data['_famiau_car_number_of_seats'] ) ? $listing_data['_famiau_car_number_of_seats'] : 0;
	$_famiau_car_number_of_doors         = isset( $listing_data['_famiau_car_number_of_doors'] ) ? $listing_data['_famiau_car_number_of_doors'] : 0;
	$_famiau_fueling_system              = isset( $listing_data['_famiau_fueling_system'] ) ? $listing_data['_famiau_fueling_system'] : '';
	$_famiau_fuel_consumption            = isset( $listing_data['_famiau_fuel_consumption'] ) ? $listing_data['_famiau_fuel_consumption'] : '';
	$_famiau_car_latitude                = isset( $listing_data['_famiau_car_latitude'] ) ? $listing_data['_famiau_car_latitude'] : '';
	$_famiau_car_longitude               = isset( $listing_data['_famiau_car_longitude'] ) ? $listing_data['_famiau_car_longitude'] : '';
	$_famiau_car_features_comforts       = isset( $listing_data['_famiau_car_features_comforts'] ) ? $listing_data['_famiau_car_features_comforts'] : '';
	$_famiau_car_features_entertainments = isset( $listing_data['_famiau_car_features_entertainments'] ) ? $listing_data['_famiau_car_features_entertainments'] : '';
	$_famiau_car_features_safety         = isset( $listing_data['_famiau_car_features_safety'] ) ? $listing_data['_famiau_car_features_safety'] : '';
	$_famiau_car_features_seats          = isset( $listing_data['_famiau_car_features_seats'] ) ? $listing_data['_famiau_car_features_seats'] : '';
	$_famiau_car_features_windows        = isset( $listing_data['_famiau_car_features_windows'] ) ? $listing_data['_famiau_car_features_windows'] : '';
	$_famiau_car_features_others         = isset( $listing_data['_famiau_car_features_others'] ) ? $listing_data['_famiau_car_features_others'] : '';
	$attachment_ids                      = $listing_gallery;
	$_famiau_video_url                   = isset( $listing_data['_famiau_video_url'] ) ? $listing_data['_famiau_video_url'] : '';
	$_famiau_desc                        = isset( $listing_data['_famiau_desc'] ) ? $listing_data['_famiau_desc'] : '';
	$_famiau_seller_notes_suggestions    = isset( $listing_data['_famiau_seller_notes_suggestions'] ) ? $listing_data['_famiau_seller_notes_suggestions'] : '';
	$_famiau_price                       = isset( $listing_data['_famiau_price'] ) ? $listing_data['_famiau_price'] : '';
	$_famiau_accept_term                 = isset( $listing_data['_famiau_accept_term'] ) ? $listing_data['_famiau_accept_term'] : 'yes';
	$listing_status                      = 'waiting';
	$note                                = 'add_from_update_post';
	
	if ( current_user_can( 'manage_options' ) ) {
		$listing_status = 'approved';
	}
	
	$data = array(
		'author_login'                        => $author_login,
		'_famiau_car_status'                  => $_famiau_car_status,
		'_famiau_make'                        => $_famiau_make,
		'_famiau_model'                       => $_famiau_model,
		'_famiau_year'                        => $_famiau_year,
		'listing_title'                       => $listing_title,
		'_famiau_body'                        => $_famiau_body,
		'_famiau_mileage'                     => $_famiau_mileage,
		'_famiau_fuel_type'                   => $_famiau_fuel_type,
		'_famiau_engine'                      => $_famiau_engine,
		'_famiau_gearbox_type'                => $_famiau_gearbox_type,
		'_famiau_drive'                       => $_famiau_drive,
		'_famiau_exterior_color'              => $_famiau_exterior_color,
		'_famiau_interior_color'              => $_famiau_interior_color,
		'_famiau_registered_date'             => $_famiau_registered_date,
		'_famiau_vin'                         => $_famiau_vin,
		'_famiau_car_number_of_seats'         => $_famiau_car_number_of_seats,
		'_famiau_car_number_of_doors'         => $_famiau_car_number_of_doors,
		'_famiau_fueling_system'              => $_famiau_fueling_system,
		'_famiau_fuel_consumption'            => $_famiau_fuel_consumption,
		'_famiau_car_address'                 => $_famiau_car_address,
		'_famiau_car_latitude'                => $_famiau_car_latitude,
		'_famiau_car_longitude'               => $_famiau_car_longitude,
		'_famiau_car_features_comforts'       => serialize( $_famiau_car_features_comforts ),
		'_famiau_car_features_entertainments' => serialize( $_famiau_car_features_entertainments ),
		'_famiau_car_features_safety'         => serialize( $_famiau_car_features_safety ),
		'_famiau_car_features_seats'          => serialize( $_famiau_car_features_seats ),
		'_famiau_car_features_windows'        => serialize( $_famiau_car_features_windows ),
		'_famiau_car_features_others'         => serialize( $_famiau_car_features_others ),
		'attachment_ids'                      => $attachment_ids,
		'_famiau_video_url'                   => $_famiau_video_url,
		'_famiau_desc'                        => $_famiau_desc,
		'_famiau_seller_notes_suggestions'    => $_famiau_seller_notes_suggestions,
		'_famiau_price'                       => $_famiau_price,
		'_famiau_accept_term'                 => $_famiau_accept_term,
		'note'                                => $note,
	);
	
	$format = array(
		'%s', // $author_login,
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
		'%s', // $note
	);
	
	
	// Update exist
	if ( $is_update ) {
		$data['note'] = 'update_from_update_post';
		$where        = array(
			'product_id' => $post_id
		);
		$where_format = array( '%d' );
		$wpdb->update( FAMIAU_LISTINGS_TABLE, $data, $where, $format, $where_format );
	} // Insert new
	else {
		$data['product_id']     = $post_id;
		$format[]               = '%d';
		$data['listing_status'] = $listing_status;
		$format[]               = '%s';
		$wpdb->insert( FAMIAU_LISTINGS_TABLE, $data, $format );
	}
	
}

add_action( 'save_post', 'famiau_save_post_callback' );