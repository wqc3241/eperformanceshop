<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'famiau_additional_listing_info' ) ) {
	function famiau_additional_listing_info() {
		$additional_args = array(
			'car_bodies'      => array(
				'tab_name'               => esc_html__( 'Car Bodies', 'famiau' ),
				'short_desc'             => esc_html__( 'Add/Edit list of car bodies' ),
				'option_key'             => 'all_car_bodies',
				'meta_key'               => '_famiau_body',
				'form_label'             => esc_html__( 'Add Car Body', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a car body name', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New Car Body', 'famiau' )
			),
			'drive'           => array(
				'tab_name'               => esc_html__( 'Drive', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_drives',
				'meta_key'               => '_famiau_drive',
				'form_label'             => esc_html__( 'Add Drive', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter drive', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New Drive', 'famiau' )
			),
			'exterior_colors' => array(
				'tab_name'               => esc_html__( 'Exterior Colors', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_exterior_colors',
				'meta_key'               => '_famiau_exterior_color',
				'form_label'             => esc_html__( 'Add Color', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter color name', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New Color', 'famiau' ),
				'form_input_class'       => ''
			),
			'interior_colors' => array(
				'tab_name'               => esc_html__( 'Interior Colors', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_interior_colors',
				'meta_key'               => '_famiau_interior_color',
				'form_label'             => esc_html__( 'Add Color', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter color name', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New Color', 'famiau' ),
				'form_input_class'       => ''
			),
		
		);
		$additional_args = apply_filters( 'famiau_additional_listing_info', $additional_args );
		
		return $additional_args;
	}
}

if ( ! function_exists( 'famiau_car_features' ) ) {
	function famiau_car_features() {
		$car_features = array(
			'car_features_comfort'       => array(
				'tab_name'               => esc_html__( 'Comfort', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_comforts',
				'meta_key'               => '_famiau_car_features_comforts',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
			'car_features_entertainment' => array(
				'tab_name'               => esc_html__( 'Entertainment', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_entertainments',
				'meta_key'               => '_famiau_car_features_entertainments',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
			'car_features_safety'        => array(
				'tab_name'               => esc_html__( 'Safety', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_safety',
				'meta_key'               => '_famiau_car_features_safety',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
			'car_features_seat'          => array(
				'tab_name'               => esc_html__( 'Seats', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_seats',
				'meta_key'               => '_famiau_car_features_seats',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
			'car_features_window'        => array(
				'tab_name'               => esc_html__( 'Windows', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_windows',
				'meta_key'               => '_famiau_car_features_windows',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
			'car_features_other'         => array(
				'tab_name'               => esc_html__( 'Others Features', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_car_features_others',
				'meta_key'               => '_famiau_car_features_others',
				'input_type'             => 'checkbox', // Default select
				'form_label'             => esc_html__( 'Add New', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a feature', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New', 'famiau' )
			),
		
		);
		$car_features = apply_filters( 'famiau_car_features', $car_features );
		
		return $car_features;
	}
}

if ( ! function_exists( 'famiau_seler_notes_suggestions' ) ) {
	function famiau_seler_notes_suggestions() {
		$seler_notes_suggestions = array(
			'seller_notes_suggestions' => array(
				'tab_name'               => esc_html__( 'Seller\'s Note Suggestions', 'famiau' ),
				'short_desc'             => '',
				'option_key'             => 'all_seller_notes_suggestions',
				'meta_key'               => '_famiau_seller_notes_suggestions',
				'input_type'             => 'suggestion', // Default select
				'form_label'             => esc_html__( 'Add New Suggestions', 'famiau' ),
				'form_input_placeholder' => esc_html__( 'Enter a suggestions', 'famiau' ),
				'form_submit_text'       => esc_html__( 'Add New Suggestions', 'famiau' )
			)
		
		);
		$seler_notes_suggestions = apply_filters( 'famiau_seler_notes_suggestions', $seler_notes_suggestions );
		
		return $seler_notes_suggestions;
	}
}

if ( ! function_exists( 'famiau_get_all_options' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famiau_get_all_options() {
		$default_all_options = array(
			// '_famiau_enable_listing_for_woocommerce'  => 'no',
			'_famiau_show_listing_form_without_login' => 'no',
			'_famiau_enable_filter_mobile'            => 'yes',
			'_famiau_enable_instant_filter'           => 'no',
			'_famiau_enable_lazy_load'                => 'yes',
			'_famiau_page_for_automotive'             => 0,
			'_famiau_page_for_account'                => 0,
			'_famiau_page_for_term'                   => 0,
			'_famiau_sellers_can_create_acc'          => 'yes',
			'_famiau_add_listing_must_accept_term'    => 'yes',
			'_famiau_listing_must_moderated'          => 'yes',
			'_famiau_accept_term_text'                => esc_html__( 'I agree with storaging of my data by this website.', 'famiau' ),
			'famiau_currency'                         => 'USD',
			'_famiau_currency_pos'                    => 'left',
			'_famiau_thousand_separator'              => ',',
			'_famiau_decimal_separator'               => '.',
			'_famiau_num_of_decimals'                 => 2,
			'_famiau_listing_cf7'                     => 0,
			'all_makes'                               => array(),
			'all_fuel_types'                          => array(),
			'all_car_bodies'                          => array(),
			'all_drives'                              => array(),
			'all_exterior_colors'                     => array(),
			'all_interior_colors'                     => array(),
			'all_seller_notes_suggestions'            => array(),
			'all_car_features_comforts'               => array(),
			'all_car_features_entertainments'         => array(),
			'all_car_features_safety'                 => array(),
			'all_car_features_windows'                => array(),
			'all_car_features_others'                 => array(),
			'_famiau_min_year'                        => 1700,
			'_famiau_max_year'                        => date( 'Y' ),
			'_famiau_imgs_upload_desc'                => '',
			'_famiau_video_links_desc'                => '',
			'_famiau_prices_desc'                     => '',
			'_famiau_gmap_api_key'                    => '',
			'_famiau_load_gmap_js'                    => 'yes',
			'_famiau_force_cf7_scripts'               => 'no',
		);
		$all_options         = get_option( 'famiau_all_settings', array() );
		$all_options         = wp_parse_args( $all_options, $default_all_options );
		
		return $all_options;
	}
}

if ( ! function_exists( 'famiau_get_option' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famiau_get_option( $option_name = '', $default = null ) {
		$all_options = famiau_get_all_options();
		$option_val  = isset( $all_options[ $option_name ] ) ? $all_options[ $option_name ] : $default;
		
		return $option_val;
	}
}

if ( ! function_exists( 'famiau_get_listing_info' ) ) {
	function famiau_get_listing_info( $listing_id = 0 ) {
		$listing_id = intval( $listing_id );
		if ( $listing_id <= 0 ) {
			$listing_id = get_the_ID();
		}
		$listing_info = get_post_meta( $listing_id, '_famiau_metabox_options', true );
		
		return $listing_info;
	}
}

if ( ! function_exists( 'famiau_delete_listing_post_type' ) ) {
	/**
	 * Delete listing post type if listing is removed
	 *
	 * @param $listing_id
	 * @param $post_id
	 * @param $user
	 */
	function famiau_delete_listing_post_type( $listing_id, $post_id, $user ) {
		$listings_table = FAMIAU_LISTINGS_TABLE;
		$listing_info   = famiau_get_row_info( $listings_table, "AND id={$listing_id}" );
		if ( $listing_info ) {
			// Check
			if ( ! $post_id || $listing_info->author_login != $user->user_login ) {
				return;
			}
			wp_delete_post( $post_id, true );
		}
	}
	
	add_action( 'famiau_delete_listing', 'famiau_delete_listing_post_type', 10, 3 );
}

if ( ! function_exists( 'famiau_sold_listing_post_type' ) ) {
	/**
	 * Delete listing post type if listing is removed
	 *
	 * @param $listing_id
	 * @param $post_id
	 * @param $user
	 */
	function famiau_sold_listing_post_type( $listing_id, $post_id, $user ) {
		$listings_table = FAMIAU_LISTINGS_TABLE;
		$listing_info   = famiau_get_row_info( $listings_table, "AND id={$listing_id}" );
		if ( $listing_info ) {
			// Check
			if ( ! $post_id || $listing_info->author_login != $user->user_login ) {
				return;
			}
			wp_delete_post( $post_id, true );
		}
	}
	
	add_action( 'famiau_sold_listing', 'famiau_sold_listing_post_type', 10, 3 );
}

if ( ! function_exists( 'famiau_is_automotive_page' ) ) {
	function famiau_is_automotive_page() {
		$is_automotive_page = false;
		if ( is_singular( 'page' ) ) {
			$this_page_id       = get_the_ID();
			$automotive_page_id = famiau_get_page( 'automotive' );
			if ( $this_page_id == $automotive_page_id ) {
				$is_automotive_page = true;
			}
		}
		
		return $is_automotive_page;
	}
}

if ( ! function_exists( 'famiau_get_account_page_link' ) ) {
	function famiau_get_account_page_link() {
		return famiau_get_page_link( 'account' );
	}
}

if ( ! function_exists( 'famiau_is_account_page' ) ) {
	function famiau_is_account_page() {
		$is_account_page = false;
		if ( is_singular( 'page' ) ) {
			$this_page_id    = get_the_ID();
			$account_page_id = famiau_get_page( 'account' );
			if ( $this_page_id == $account_page_id ) {
				$is_account_page = true;
			}
		}
		
		return $is_account_page;
	}
}

if ( ! function_exists( 'famiau_is_term_page' ) ) {
	function famiau_is_term_page() {
		$is_term_page = false;
		if ( is_singular( 'page' ) ) {
			$this_page_id = get_the_ID();
			$term_page_id = famiau_get_page( 'term' );
			if ( $this_page_id == $term_page_id ) {
				$is_term_page = true;
			}
		}
		
		return $is_term_page;
	}
}

if ( ! function_exists( 'famiau_is_attachment_of_author' ) ) {
	function famiau_is_attachment_of_author( $attachment_id = 0, $author_id = 0 ) {
		if ( $attachment_id <= 0 || $author_id <= 0 ) {
			return false;
		}
		$post_data            = get_post( $attachment_id, ARRAY_A );
		$attachment_author_id = isset( $post_data['post_author'] ) ? $post_data['post_author'] : 0;
		
		return $author_id == $attachment_author_id && $attachment_author_id > 0;
	}
}

if ( ! function_exists( 'famiau_get_page' ) ) {
	/**
	 * @param string $page_name - automotive, account, term
	 *
	 * @return int
	 */
	function famiau_get_page( $page_name = '' ) {
		$page_id = - 1;
		if ( trim( $page_name ) == '' ) {
			return $page_id;
		}
		$page_id = intval( famiau_get_option( '_famiau_page_for_' . $page_name, - 1 ) );
		
		return $page_id;
	}
}

if ( ! function_exists( 'famiau_get_page_link' ) ) {
	function famiau_get_page_link( $page_name = '' ) {
		$page_id = famiau_get_page( $page_name );
		$url     = '';
		if ( $page_id ) {
			$url = get_permalink( $page_id );
		}
		
		return $url;
	}
}

if ( ! function_exists( 'famiau_get_all_make_names' ) ) {
	function famiau_get_all_make_names() {
		global $famiau;
		$all_makes      = isset( $famiau['all_makes'] ) ? $famiau['all_makes'] : array();
		$all_make_names = array();
		if ( ! empty( $all_makes ) ) {
			foreach ( $all_makes as $make ) {
				if ( isset( $make['make'] ) ) {
					$all_make_names[] = $make['make'];
				}
			}
		}
		
		return $all_make_names;
	}
}

if ( ! function_exists( 'famiau_get_models_by_make' ) ) {
	/**
	 * @param string $make
	 *
	 * @return array
	 */
	function famiau_get_models_by_make( $make = '' ) {
		$models = array();
		if ( trim( $make ) == '' ) {
			return $models;
		}
		$all_options = famiau_get_all_options();
		$all_makes   = isset( $all_options['all_makes'] ) ? $all_options['all_makes'] : array();
		
		if ( empty( $all_makes ) ) {
			return $models;
		}
		
		foreach ( $all_makes as $make_arg ) {
			if ( $make == $make_arg['make'] ) {
				$models = $make_arg['models'];
				
				return $models;
			}
		}
		
		return $models;
	}
}

if ( ! function_exists( 'famiau_get_prices_range' ) ) {
	function famiau_get_prices_range() {
		global $wpdb;
		$prices_range = array(
			'min_price' => 0,
			'max_price' => 0
		);
		
		$listings_table = FAMIAU_LISTINGS_TABLE;
		
		$sql = $wpdb->prepare(
			"SELECT MAX(CAST(fl._famiau_price AS unsigned)) AS max_price, MIN(CAST(fl._famiau_price AS unsigned)) AS min_price " .
			"FROM {$listings_table} fl " .
			"INNER JOIN {$wpdb->prefix}posts p " .
			"ON fl.product_id = p.ID " .
			"WHERE fl.listing_status='approved' " .
			"AND p.post_status = '%s'",
			array( 'publish' )
		);

//		$sql = $wpdb->prepare(
//			"SELECT MAX(CAST(pm.meta_value AS unsigned)) AS max_price, MIN(CAST(pm.meta_value AS unsigned)) AS min_price " .
//			"FROM {$wpdb->prefix}postmeta pm " .
//			"INNER JOIN {$wpdb->prefix}posts p " .
//			"ON pm.post_id = p.ID " .
//			"WHERE pm.meta_key='_famiau_price' " .
//			"AND p.post_status = '%s'",
//			array( 'publish' )
//		);
		
		$result = $wpdb->get_row( $sql );
		if ( $result ) {
			$prices_range['min_price'] = $result->min_price;
			$prices_range['max_price'] = $result->max_price;
		}
		
		return $prices_range;
	}
}

if ( ! function_exists( 'famiau_wc_price' ) ) {
	/**
	 * Format the price with a currency symbol.
	 *
	 * @param  float $price              Raw price.
	 * @param  array $args               Arguments to format a price {
	 *                                   Array of arguments.
	 *                                   Defaults to empty array.
	 *
	 * @type bool    $ex_tax_label       Adds exclude tax label.
	 *                                      Defaults to false.
	 * @type string  $currency           Currency code.
	 *                                      Defaults to empty string (Use the result from get_woocommerce_currency()).
	 * @type string  $decimal_separator  Decimal separator.
	 *                                      Defaults the result of wc_get_price_decimal_separator().
	 * @type string  $thousand_separator Thousand separator.
	 *                                      Defaults the result of wc_get_price_thousand_separator().
	 * @type string  $decimals           Number of decimals.
	 *                                      Defaults the result of wc_get_price_decimals().
	 * @type string  $price_format       Price format depending on the currency position.
	 *                                      Defaults the result of get_woocommerce_price_format().
	 * }
	 * @return string
	 */
	function famiau_wc_price( $price, $args = array() ) {
		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $price, $args );
		}
		
		return $price;
	}
}

if ( ! function_exists( 'famiau_woocommerce_product_query_tax_query' ) ) {
	function famiau_woocommerce_product_query_tax_query( $tax_query ) {
		
		$tax_query[] = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'famiau',
			'operator' => 'NOT IN'
		);
		
		return $tax_query;
	}
	
	add_filter( 'woocommerce_product_query_tax_query', 'famiau_woocommerce_product_query_tax_query', 10, 1 );
}

if ( ! function_exists( 'famiau_prdctfltr_wc_tax' ) ) {
	function famiau_prdctfltr_wc_tax( $tax_query ) {
		
		$tax_query[] = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'famiau',
			'operator' => 'NOT IN'
		);
		
		return $tax_query;
	}
	
	add_filter( 'prdctfltr_tax_query', 'famiau_prdctfltr_wc_tax', 10, 1 );
}

if ( ! function_exists( 'famiau_wc_get_template_part' ) ) {
	function famiau_wc_get_template_part( $slug, $name = '' ) {
		if ( function_exists( 'wc_get_template_part' ) ) {
			wc_get_template_part( $slug, $name );
		}
	}
}

if ( ! function_exists( 'famiau_wc_template_part' ) ) {
	function famiau_wc_template_part( $template, $slug, $name ) {
		global $product;
//		if ( ! is_singular( 'product' ) || $slug != 'content' || $name != 'single-product' ) {
//			return $template;
//		}
		
		
		if ( is_singular( 'product' ) ) {
			if ( $slug != 'content' || $name != 'single-product' ) {
				return $template;
			}
		} else {
			if ( ! in_array( $slug, array(
					'content',
					'account/famiau-account',
					'account/famiau-my-listings',
					'account/famiau-navigation',
					'account/famiau-dashboard',
					'account/famiau-my-listing',
					'account/famiau-inbox',
					'account/famiau-personal-info',
					'account/famiau-change-pw',
					'listing-form/famiau-listing',
					'listing-form/famiau-car',
					'listing-form/famiau-upload',
					'listing-form/famiau-video',
					'listing-form/famiau-seller',
					'listing-form/famiau-asking',
				) ) || $slug == 'content' && $name != 'product' ) {
				return $template;
			}
		}
		
		if ( in_array( $slug, array( 'content' ) ) ) {
			if ( $product->get_type() != 'famiau' || trim( $slug ) == '' ) {
				return $template;
			}
		}
		
		$slug_args = explode( '/', $slug );
		$last_slug = str_replace( 'famiau-', '', $slug_args[ count( $slug_args ) - 1 ] );
		if ( $last_slug != '' ) {
			$slug_args[ count( $slug_args ) - 1 ] = 'famiau-' . $last_slug;
		}
		$slug = implode( '/', $slug_args );
		
		
		// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php.
		if ( $name && ! WC_TEMPLATE_DEBUG_MODE ) {
			if ( file_exists( get_stylesheet_directory() . "/woocommerce/{$slug}-{$name}.php" ) ) {
				$template = get_stylesheet_directory() . "/woocommerce/{$slug}-{$name}.php";
			} else {
				if ( file_exists( FAMIAU_PATH . "templates/{$slug}-{$name}.php" ) ) {
					$template = FAMIAU_PATH . "templates/{$slug}-{$name}.php";
				}
			}
		}
		
		if ( ! $template && $name && file_exists( FAMIAU_PATH . "templates/{$slug}-{$name}.php" ) ) {
			$template = FAMIAU_PATH . "templates/{$slug}-{$name}.php";
		}
		
		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( WC()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
			$template = WC()->plugin_path() . "/templates/{$slug}-{$name}.php";
		}
		
		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
		if ( ! $template && ! WC_TEMPLATE_DEBUG_MODE ) {
			if ( file_exists( FAMIAU_PATH . "templates/{$slug}.php" ) ) {
				$template = FAMIAU_PATH . "templates/{$slug}.php";
			}
		}
		
		return $template;
	}
	
	add_filter( 'wc_get_template_part', 'famiau_wc_template_part', 10, 3 );
}

if ( ! function_exists( 'famiau_add_make_item_template' ) ) {
	function famiau_add_make_item_template( $make = '{make}', $models = '{models}' ) {
		
		$models_html = '';
		if ( is_array( $models ) ) {
			if ( ! empty( $models ) ) {
				foreach ( $models as $model ) {
					$models_html .= famiau_model_item_template( $model );
				}
			}
			$models = htmlentities2( json_encode( $models ) );
		}
		
		$html = '<div class="famiau-make-item famiau-item" data-models="' . esc_attr( $models ) . '" data-make="' . esc_attr( $make ) . '"><div class="famiau-item-inner">' . esc_html( $make ) . '</div>' .
		        '<a href="#" class="famiau-toggle-edit-sub-item">' . esc_html__( 'Models', 'famiau' ) . '</a>' .
		        '<a href="#" class="remove-btn" title="Remove">x</a>' .
		        '<div class="famiau-models-list-wrap famiau-sub-items-list-wrap">
                    <div class="famiau-sub-items-list-inner-wrap famiau-box-shadow">
                        <div class="famiau-add-model-form-wrap">
                            <form method="post" class="famiau-add-model-form">
                                <input type="text" class="famiau-model-input" />
                                <button type="submit" class="famiau-add-new-model-btn famiau-button">' . esc_html__( 'Add New Model', 'famiau' ) . '</button>
                            </form>
                        </div>
                        <div class="famiau-models-list famiau-sub-items-list">' . $models_html . '</div>
                    </div>
                </div>' .
		        '</div>';
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_model_item_template' ) ) {
	function famiau_model_item_template( $model = '{model}' ) {
		$html = '<div class="famiau-model-item famiau-sub-item" data-model="' . esc_attr( $model ) . '"><div class="famiau-sub-item-inner">' . esc_html( $model ) . '</div>' .
		        '<a href="#" class="famiau-remove-sub-item-btn" title="Remove">x</a>' .
		        '</div>';
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_popup_template' ) ) {
	function famiau_popup_template() {
		$html = '';
		
		ob_start();
		?>
        <div class="famiau-popup-wrap">
            <div class="famiau-popup-inner">
                <div class="famiau-popup-content-wrap">
                    <div class="famiau-popup-content">

                    </div>
                    <a href="#" class="famiau-close-popup">X</a>
                </div>
            </div>
        </div>
		<?php
		$html .= ob_get_clean();
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_desc_group_form_template' ) ) {
	function famiau_desc_group_form_template() {
		$html = '';
		
		ob_start();
		?>
        <div class="famiau-custom-desc-group famiau-box-shadow">
            <label class="famiau-lb"><?php esc_html_e( 'Group Name', 'famiau' ); ?></label>
            <input type="text" class="famiau-group-name-input"
                   placeholder="<?php esc_attr_e( 'Enter group name', 'famiau' ); ?>" value=""/>
            <div class="famiau-custom-desc-details-list">

            </div>
            <a href="#"
               class="famiau-button famiau-add-custom-desc-detail-btn"><?php esc_html_e( 'Add New Description', 'famiau' ); ?></a>
            <a href="#"
               class="famiau-button-warning famiau-remove-desc-group-btn float-right"><?php esc_html_e( 'Remove This Group', 'famiau' ); ?></a>
        </div>
		<?php
		$html .= ob_get_clean();
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_desc_detail_form_template' ) ) {
	function famiau_desc_detail_form_template( $desc_label = '', $desc_val = '', $is_heading_desc = 'no' ) {
		$html = '';
		
		ob_start();
		?>
        <div class="famiau-desc-detail famiau-box-shadow">
            <div class="famiau-row">
                <div class="famiau-part-left">
                    <label class="famiau-lb"><?php esc_html_e( 'Label', 'famiau' ); ?></label>
                    <input type="text" class="famiau-desc-label-input"
                           placeholder="<?php esc_attr_e( 'Description label', 'famiau' ); ?>"
                           value="<?php echo esc_attr( $desc_label ); ?>"/>
                </div>
                <div class="famiau-part-right">
                    <label class="famiau-lb"><?php esc_html_e( 'Value', 'famiau' ); ?></label>
                    <input type="text" class="famiau-desc-value-input"
                           placeholder="<?php esc_attr_e( 'Description value', 'famiau' ); ?>"
                           value="<?php echo esc_attr( $desc_val ); ?>"/>
                </div>
                <div class="famiau-actions-wrap">
                    <label class="famiau-lb-wrap famiau-lb">
                        <input type="checkbox" <?php echo $is_heading_desc == 'yes' ? 'checked' : ''; ?>
                               class="famiau-cb famiau-is-heading-desc-cb"
                               value=""/> <?php esc_html_e( 'Is heading description', 'famiau' ); ?>
                    </label>
                    <a href="#" class="famiau-del-desc-detail-btn">X</a>
                </div>
            </div>
        </div>
		<?php
		$html .= ob_get_clean();
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_select_html' ) ) {
	function famiau_select_html( $args = array(), $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$html = '';
		if ( empty( $args ) ) {
			return '';
		}
		
		foreach ( $args as $val => $display ) {
			$html .= '<option ' . selected( true, $val == $selected, false ) . ' value="' . esc_attr( $val ) . '">' . esc_html( $display ) . '</option>';
		}
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select ' . esc_attr( $class ) . '" ';
		
		$html = '<select ' . $html_atts . '>' . $html . '</select>';
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_select_no_key_html' ) ) {
	function famiau_select_no_key_html( $items = array(), $selected = '', $class = '', $name = '', $id = '', $args = array(), $echo = true ) {
		
		$args_default = array(
			'no_items_text'   => esc_html__( ' --- No item to select --- ', 'famiau' ),
			'first_item_text' => esc_html__( ' --- Select item --- ', 'famiau' )
		);
		$args         = wp_parse_args( $args, $args_default );
		
		$html      = '';
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-item-select ' . esc_attr( $class ) . '" ';
		
		$options_html = '';
		if ( empty( $items ) ) {
			$options_html .= '<option data-item="" value="">' . $args['no_items_text'] . '</option>';
		} else {
			$options_html .= '<option data-item="" value="">' . $args['first_item_text'] . '</option>';
			foreach ( $items as $item ) {
				$options_html .= '<option data-item="' . esc_attr( $item ) . '" ' . selected( true, $item == $selected, false ) . ' value="' . esc_attr( $item ) . '">' . esc_attr( $item ) . '</option>';
			}
		}
		
		if ( trim( $options_html ) != '' ) {
			$html = '<select data-selected="' . $selected . '" ' . $html_atts . '>' . $options_html . '</select>';
		}
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_car_status_select_html' ) ) {
	function famiau_car_status_select_html( $selected = '', $class = '', $name = '', $id = '', $first_option_text = '', $echo = true ) {
		if ( $first_option_text == '' ) {
			$first_option_text = esc_html__( 'Select Car Condition', 'famiau' );
		}
		$car_statuses = array(
			''               => $first_option_text,
			'new'            => esc_html__( 'New', 'famiau' ),
			'used'           => esc_html__( 'Used', 'famiau' ),
			'certified-used' => esc_html__( 'Certified Used', 'famiau' )
		);
		$class        .= ' famiau-car-status-select';
		$html         = famiau_select_html( $car_statuses, $selected, $class, $name, $id, false );
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_gearbox_type_select_html' ) ) {
	function famiau_gearbox_type_select_html( $selected = '', $class = '', $name = '', $id = '', $first_option_text = '', $echo = true ) {
		if ( $first_option_text == '' ) {
			$first_option_text = esc_html__( 'Select Car Condition', 'famiau' );
		}
		$gearboxes = array(
			''               => $first_option_text,
			'manual'         => esc_html__( 'Manual', 'famiau' ),
			'auto'           => esc_html__( 'Automatic', 'famiau' ),
			'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
		);
		$class     .= ' famiau-gearbox-type-select';
		$html      = famiau_select_html( $gearboxes, $selected, $class, $name, $id, false );
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_all_pages_select_html' ) ) {
	
	/**
	 * Display/Return page select html, exclude blog page, frontpage, shop page, cart, checkout, my account page, terms page
	 *
	 * @param string $selected
	 * @param string $class
	 * @param string $name
	 * @param string $id
	 * @param array  $exception
	 * @param bool   $echo
	 *
	 * @return string
	 */
	function famiau_all_pages_select_html( $selected = '', $class = '', $name = '', $id = '', $exception = array(), $echo = true ) {
		if ( is_numeric( $exception ) || is_string( $exception ) ) {
			$exception = array( $exception );
		}
		
		$pages_exclude           = $exception;
		$blog_page_id            = get_option( 'page_for_posts', 0 ); // Blog page
		$front_page_id           = get_option( 'page_on_front', 0 ); // Front page
		$page_for_privacy_policy = get_option( 'wp_page_for_privacy_policy', 0 );
		
		$pages_exclude[] = $blog_page_id;
		$pages_exclude[] = $front_page_id;
		$pages_exclude[] = $page_for_privacy_policy;
		if ( class_exists( 'WooCommerce' ) ) {
			$myaccount_page_id = wc_get_page_id( 'myaccount' );
			$shop_page_id      = wc_get_page_id( 'shop' );
			$cart_page_id      = wc_get_page_id( 'cart' );
			$checkout_page_id  = wc_get_page_id( 'checkout' );
			$terms_page_id     = wc_get_page_id( 'terms' );
			
			$pages_exclude[] = $myaccount_page_id;
			$pages_exclude[] = $shop_page_id;
			$pages_exclude[] = $cart_page_id;
			$pages_exclude[] = $checkout_page_id;
			$pages_exclude[] = $terms_page_id;
		}
		
		$pages_args = array(
			'sort_order'   => 'asc',
			'sort_column'  => 'post_title',
			'hierarchical' => 1,
			'exclude'      => $pages_exclude,
			'include'      => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'authors'      => '',
			'child_of'     => 0,
			'parent'       => - 1,
			'exclude_tree' => '',
			'number'       => '',
			'offset'       => 0,
			'post_type'    => 'page',
			'post_status'  => 'publish'
		);
		$all_pages  = get_pages( $pages_args );
		
		$html = '<option value="">' . esc_html__( 'Select Page', 'famiau' ) . '</option>';
		
		if ( ! empty( $all_pages ) ) {
			foreach ( $all_pages as $page ) {
				$html .= '<option ' . selected( true, $page->ID == $selected, false ) . ' value="' . esc_attr( $page->ID ) . '">' . esc_html( $page->post_title ) . '</option>';
			}
		}
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-automotive-page-select ' . esc_attr( $class ) . '" ';
		
		$html = '<select ' . $html_atts . '>' . $html . '</select>';
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
		
	}
}

if ( ! function_exists( 'famiau_all_post_types_select_html' ) ) {
	
	/**
	 * Display/Return page select html, exclude blog page, frontpage, shop page, cart, checkout, my account page, terms page
	 *
	 * @param string $selected
	 * @param string $class
	 * @param string $name
	 * @param string $id
	 * @param array  $exception
	 * @param bool   $echo
	 *
	 * @return string
	 */
	function famiau_all_post_types_select_html( $post_type = 'page', $selected = '', $class = '', $name = '', $id = '', $exception = array(), $echo = true ) {
		if ( is_numeric( $exception ) || is_string( $exception ) ) {
			$exception = array( $exception );
		}
		
		$pages_exclude = $exception;
		
		$posts_args = array(
			'exclude'     => $pages_exclude,
			'post_type'   => $post_type,
			'post_status' => 'publish'
		);
		
		$query_posts = new WP_Query( $posts_args );
		
		$html = '<option value="">' . esc_html__( 'Select Option', 'famiau' ) . '</option>';
		
		if ( $query_posts->have_posts() ) {
			while ( $query_posts->have_posts() ) {
				$query_posts->the_post();
				$html .= '<option ' . selected( true, get_the_ID() == $selected, false ) . ' value="' . esc_attr( get_the_ID() ) . '">' . esc_html( get_the_title() ) . '</option>';
			}
		}
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-automotive-post-type-select ' . esc_attr( $class ) . '" ';
		
		$html = '<select ' . $html_atts . '>' . $html . '</select>';
		
		wp_reset_postdata();
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
		
	}
}

if ( ! function_exists( 'famiau_makes_select_html' ) ) {
	function famiau_makes_select_html( $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$all_options = famiau_get_all_options();
		$all_makes   = isset( $all_options['all_makes'] ) ? $all_options['all_makes'] : array();
		$html        = '';
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-make-select ' . esc_attr( $class ) . '" ';
		
		$options_html = '';
		if ( empty( $all_makes ) ) {
			$options_html .= '<option data-models="" value="">' . esc_html__( 'No makes', 'famiau' ) . '</option>';
		} else {
			$options_html .= '<option data-models="" value="">' . esc_html__( 'Select make', 'famiau' ) . '</option>';
			foreach ( $all_makes as $make ) {
				$make_name    = isset( $make['make'] ) ? $make['make'] : '';
				$models       = isset( $make['models'] ) ? $make['models'] : array();
				$models       = htmlentities2( json_encode( $models ) );
				$options_html .= '<option data-models="' . esc_attr( $models ) . '" ' . selected( true, $make_name == $selected, false ) . ' value="' . esc_attr( $make_name ) . '">' . esc_attr( $make_name ) . '</option>';
			}
		}
		
		if ( trim( $options_html ) != '' ) {
			$html = '<select ' . $html_atts . '>' . $options_html . '</select>';
		}
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_models_select_html' ) ) {
	function famiau_models_select_html( $models = array(), $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		
		$html      = '';
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-model-select ' . esc_attr( $class ) . '" ';
		
		$options_html = '';
		if ( empty( $models ) ) {
			$options_html .= '<option data-model="" value="">' . esc_html__( ' --- No model to select --- ', 'famiau' ) . '</option>';
		} else {
			$options_html .= '<option data-model="" value="">' . esc_html__( ' --- Select model --- ', 'famiau' ) . '</option>';
			foreach ( $models as $model ) {
				$options_html .= '<option data-model="' . esc_attr( $model ) . '" ' . selected( true, $model == $selected, false ) . ' value="' . esc_attr( $model ) . '">' . esc_attr( $model ) . '</option>';
			}
		}
		
		if ( trim( $options_html ) != '' ) {
			$html = '<select data-selected="' . $selected . '" ' . $html_atts . '>' . $options_html . '</select>';
		}
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_fuel_types_select_html' ) ) {
	function famiau_fuel_types_select_html( $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$all_options    = famiau_get_all_options();
		$all_fuel_types = isset( $all_options['all_fuel_types'] ) ? $all_options['all_fuel_types'] : array();
		$html           = '';
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-fuel_type-select ' . esc_attr( $class ) . '" ';
		
		$options_html = '';
		if ( empty( $all_fuel_types ) ) {
			$options_html .= '<option value="">' . esc_html__( ' --- No fuel type to select --- ', 'famiau' ) . '</option>';
		} else {
			$options_html .= '<option value="">' . esc_html__( ' --- Select fuel type --- ', 'famiau' ) . '</option>';
			foreach ( $all_fuel_types as $fuel_type ) {
				$options_html .= '<option ' . selected( true, $fuel_type == $selected, false ) . ' value="' . esc_attr( $fuel_type ) . '">' . esc_attr( $fuel_type ) . '</option>';
			}
		}
		
		if ( trim( $options_html ) != '' ) {
			$html = '<select ' . $html_atts . '>' . $options_html . '</select>';
		}
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_users_select_html' ) ) {
	/**
	 * @param string $selected User login name
	 * @param string $class
	 * @param string $name
	 * @param string $id
	 * @param bool   $echo
	 */
	function famiau_users_select_html( $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$html = '';
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famiau-select famiau-user-select ' . esc_attr( $class ) . '" ';
		
		$options_html = '';
		
		$users_args       = array(
			'role__in' => array( 'famiau_user' )
		);
		$all_famiau_users = get_users( $users_args );
		
		if ( empty( $all_famiau_users ) ) {
			$options_html .= '<option value="">' . esc_html__( 'No User To Select', 'famiau' ) . '</option>';
		} else {
			$options_html .= '<option value="">' . esc_html__( 'Select User', 'famiau' ) . '</option>';
			foreach ( $all_famiau_users as $famiau_user ) {
				$options_html .= '<option ' . selected( true, $famiau_user->user_login == $selected, false ) . ' value="' . esc_attr( $famiau_user->user_login ) . '">' . esc_attr( $famiau_user->display_name ) . '</option>';
			}
		}
		
		if ( trim( $options_html ) != '' ) {
			$html = '<select ' . $html_atts . '>' . $options_html . '</select>';
		}
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
		
	}
}

if ( ! function_exists( 'famiau_gearbox_display_text' ) ) {
	function famiau_gearbox_display_text( $gearbox_type = '' ) {
		$gearboxes = array(
			'manual'         => esc_html__( 'Manual', 'famiau' ),
			'auto'           => esc_html__( 'Automatic', 'famiau' ),
			'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
		);
		
		return isset( $gearboxes[ $gearbox_type ] ) ? $gearboxes[ $gearbox_type ] : '';
	}
}

if ( ! function_exists( 'famiau_display_listing' ) ) {
	function famiau_display_listing() {
		echo do_shortcode( '[famiau_listing]' );
	}
	
	add_action( 'famiau_listing', 'famiau_display_listing', 10 );
}

if ( ! function_exists( 'famiau_display_listing_results' ) ) {
	function famiau_display_listing_results( $filters = array(), $echo = true ) {
		$html = '';
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$filters_default = array(
			'_famiau_make'         => '',
			'_famiau_model'        => '',
			'_famiau_fuel_type'    => '',
			'_famiau_car_status'   => '',
			'_famiau_gearbox_type' => '',
			'_famiau_year'         => array(),
			'_famiau_price'        => array(),
		);
		$filters         = wp_parse_args( $filters, $filters_default );
		
		$query_args = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'tax_query'   => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'famiau'
				)
			),
			'meta_query'  => array()
		);
		
		foreach ( $filters as $filter_key => $filter_val ) {
			if ( is_string( $filter_val ) ) {
				if ( trim( $filter_val ) != '' ) {
					$query_args['meta_query'][] = array(
						'key'     => $filter_key,
						'value'   => $filter_val,
						'compare' => '='
					);
				}
			} else {
				if ( is_array( $filter_val ) ) {
					if ( ! empty( $filter_val ) ) {
						$query_args['meta_query'][] = array(
							'key'     => $filter_key,
							'value'   => $filter_val,
							'type'    => 'numeric',
							'compare' => 'BETWEEN'
						);
					}
				}
			}
		}
		
		if ( ! empty( $query_args['meta_query'] ) ) {
			$query_args['meta_query']['relation'] = 'AND';
		}
		
		$query = new WP_Query( $query_args );
		
		if ( $query->have_posts() ) {
			ob_start();
			echo '<ul class="products famiau-products">';
			while ( $query->have_posts() ) {
				$query->the_post();
				famiau_wc_get_template_part( 'content', 'product' );
			}
			echo '</ul>';
			$html .= ob_get_clean();
		}
		
		wp_reset_postdata();
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_mask_phone_display_html' ) ) {
	function famiau_mask_phone_display_html( $phone_number = '', $dealer_id = 0, $class = '' ) {
		$phone_number = trim( $phone_number );
		$first_4chars = substr( $phone_number, 0, 4 );
		$html         = '<div class="famiau-phone-mask-wrap ' . esc_attr( $class ) . '">
                            <i class="fa fa-phone"></i>
                            <div class="phone famiau-phone-mask">' . sprintf( esc_html__( '%s*******', 'famiau' ), esc_attr( $first_4chars ) ) . '</div>
                            <span class="famiau-show-number"
                                  data-dealer_id="' . esc_attr( $dealer_id ) . '">' . esc_html__( 'Show number', 'famiau' ) . '</span>
                        </div>';
		
		return apply_filters( 'famiau_mask_phone_display', $html, $phone_number, $dealer_id, $class );
	}
}

if ( ! function_exists( 'famiau_get_contact_form_html' ) ) {
	function famiau_get_contact_form_html() {
		$all_options = famiau_get_all_options();
		$html        = '';
		if ( ! defined( 'WPCF7_VERSION' ) ) {
			return '';
		}
		
		$listing_cf7_id = intval( $all_options['_famiau_listing_cf7'] );
		
		if ( 'publish' != get_post_status( $listing_cf7_id ) ) {
			return '';
		}
		
		// Get selected contact form content
		$cf7_form = get_post_meta( $listing_cf7_id, '_form', true );
		
		// Check contact form html
		$famiau_content_str = '[textarea famiau-content';
		if ( strpos( $cf7_form, $famiau_content_str ) === false ) {
			// Add listing field
			$cf7_form .= '<div class="famiau-listing-field-wrap famiau-hidden">[textarea famiau-content class:famiau-hidden]</div>';
			update_post_meta( $listing_cf7_id, '_form', $cf7_form );
		}
		
		// Get selected contact form email template
		$cf7_mail = get_post_meta( $listing_cf7_id, '_mail', true );
		if ( ! isset( $cf7_mail['body'] ) ) {
			$cf7_mail['body'] = '';
		}
		
		// Check contact email html
		$famiau_mail_field_str = '[famiau-content]';
		if ( strpos( $cf7_mail['body'], $famiau_mail_field_str ) === false ) {
			// Add mail field template
			$cf7_mail['body'] .= '<p>[famiau-content]</p>';
			update_post_meta( $listing_cf7_id, '_mail', $cf7_mail );
		}
		
		// Get form html
		$html .= do_shortcode( '[contact-form-7 id="' . esc_attr( $listing_cf7_id ) . '"]' );
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_current_user_listings_html' ) ) {
	function famiau_current_user_listings_html() {
		$html = '';
		ob_start();
		famiau_wc_get_template_part( 'account/famiau-my-listings', 'table' );
		$html .= ob_get_clean();
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_get_dealer_info_html' ) ) {
	/**
	 * @param     $dealer
	 * @param int $avatar_size
	 *
	 * @return string
	 */
	function famiau_get_dealer_info_html( $dealer, $avatar_size = 110 ) {
		$dealer_listing_mobile = get_user_meta( $dealer->ID, 'famiau_user_mobile', true );
		$html                  = '';
		ob_start();
		?>
        <div class="famiau-dealer-info">
            <div class="famiau-dealer-avatar-wrap">
				<?php echo get_avatar( $dealer->ID, $avatar_size ); ?>
                <span class="dealer-name"><?php echo esc_html( $dealer->display_name ); ?></span>
            </div>
            <div class="famiau-dealer-contact-info">
				<?php
				if ( trim( $dealer_listing_mobile ) != '' ) {
					echo famiau_mask_phone_display_html( $dealer_listing_mobile, $dealer->ID, 'dealer-contact-unit' );
				}
				?>
            </div>
        </div>
		<?php
		$html .= ob_get_clean();
		
		return $html;
	}
}

if ( ! function_exists( 'famiau_price' ) ) {
	/**
	 * Will not use
	 */
	function famiau_price( $price, $args = array() ) {
		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $price, $args );
		}
		
		return $price;
	}
}

if ( ! function_exists( 'famiau_get_all_listings_info_for_map' ) ) {
	function famiau_get_all_listings_info_for_map() {
		global $wpdb;
		$table_name = FAMIAU_LISTINGS_TABLE;
		$sql        = "SELECT product_id AS post_id, listing_title, _famiau_car_status AS car_status, _famiau_year AS year, _famiau_mileage AS mileage, _famiau_fuel_type AS fuel_type, _famiau_gearbox_type AS gearbox_type, attachment_ids, _famiau_price AS price, _famiau_car_address AS car_address, _famiau_car_latitude AS latitude, _famiau_car_longitude AS longitude ";
		$sql        .= "FROM {$table_name} ";
		$sql        .= "WHERE listing_status = 'approved' ";
		
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
		
		return $results;
	}
}

if ( ! function_exists( 'famiau_get_map_cluster_styles' ) ) {
	function famiau_get_map_cluster_styles() {
		$cluster_styles = array(
			array(
				'textColor' => 'white',
				'url'       => FAMIAU_URI . 'assets/vendors/markerclusterer/images/m1.png',
				'width'     => 53,
				'height'    => 52
			),
			array(
				'textColor' => 'white',
				'url'       => FAMIAU_URI . 'assets/vendors/markerclusterer/images/m2.png',
				'width'     => 56,
				'height'    => 55
			),
			array(
				'textColor' => 'white',
				'url'       => FAMIAU_URI . 'assets/vendors/markerclusterer/images/m3.png',
				'width'     => 66,
				'height'    => 65
			),
			array(
				'textColor' => 'white',
				'url'       => FAMIAU_URI . 'assets/vendors/markerclusterer/images/m4.png',
				'width'     => 78,
				'height'    => 77
			),
			array(
				'textColor' => 'white',
				'url'       => FAMIAU_URI . 'assets/vendors/markerclusterer/images/m5.png',
				'width'     => 90,
				'height'    => 89
			),
		);
		
		$cluster_styles = apply_filters( 'famiau_map_cluster_styles', $cluster_styles );
		
		return $cluster_styles;
	}
}

if ( ! function_exists( 'famiau_resize_image' ) ) {
	/**
	 * @param int    $attach_id
	 * @param string $img_url
	 * @param int    $width
	 * @param int    $height
	 * @param bool   $crop
	 * @param bool   $place_hold        Using place hold image if the image does not exist
	 * @param bool   $use_real_img_hold Using real image for holder if the image does not exist
	 * @param string $solid_img_color   Solid placehold image color (not text color). Random color if null
	 *
	 * @since 1.0
	 * @return array
	 */
	function famiau_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
		/*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
		if ( is_singular() && ! $attach_id ) {
			if ( has_post_thumbnail() && ! post_password_required() ) {
				$attach_id = get_post_thumbnail_id();
			}
		}
		/*this is an attachment, so we have the ID*/
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
			/*this is not an attachment, let's use the image url*/
		} else if ( $img_url ) {
			$file_path        = str_replace( get_site_url(), get_home_path(), $img_url );
			$actual_file_path = rtrim( $file_path, '/' );
			if ( ! file_exists( $actual_file_path ) ) {
				$file_path        = parse_url( $img_url );
				$actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
			}
			if ( file_exists( $actual_file_path ) ) {
				$orig_size    = getimagesize( $actual_file_path );
				$image_src[0] = $img_url;
				$image_src[1] = $orig_size[0];
				$image_src[2] = $orig_size[1];
			} else {
				$image_src[0] = '';
				$image_src[1] = 0;
				$image_src[2] = 0;
			}
		}
		if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
			$file_info = pathinfo( $actual_file_path );
			$extension = '.' . $file_info['extension'];
			/*the image path without the extension*/
			$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
			$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			/*checking if the file size is larger than the target size*/
			/*if it is smaller or the same size, stop right here and return*/
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				/*the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)*/
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image        = array(
						'url'    => $cropped_img_url,
						'width'  => $width,
						'height' => $height,
					);
					
					return $vt_image;
				}
				
				if ( $crop == false ) {
					/*calculate the size proportionaly*/
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
					/*checking if the file already exists*/
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $resized_img_url,
							'width'  => $proportional_size[0],
							'height' => $proportional_size[1],
						);
						
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
					);
				}
				$new_img_path = $img_editor->generate_filename();
				if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					);
				}
				if ( ! is_string( $new_img_path ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					);
				}
				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
				/*resized output*/
				$vt_image = array(
					'url'    => $new_img,
					'width'  => $new_img_size[0],
					'height' => $new_img_size[1],
				);
				
				return $vt_image;
			}
			/*default output - without resizing*/
			$vt_image = array(
				'url'    => $image_src[0],
				'width'  => $image_src[1],
				'height' => $image_src[2],
			);
			
			return $vt_image;
		} else {
			if ( $place_hold ) {
				$width  = intval( $width );
				$height = intval( $height );
				/*Real image place hold (https://unsplash.it/)*/
				if ( $use_real_img_hold ) {
					$random_time = time() + rand( 1, 100000 );
					$vt_image    = array(
						'url'    => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
						'width'  => $width,
						'height' => $height,
					);
				} else {
					$vt_image = array(
						'url'    => famiau_no_image( array( 'width' => $width, 'height' => $height ) ),
						// 'https://via.placeholder.com/' . $width . 'x' . $height,
						'width'  => $width,
						'height' => $height,
					);
				}
				
				return $vt_image;
			}
		}
		
		return false;
	}
}


if ( ! function_exists( 'famiau_no_image' ) ) {
	/**
	 * No image generator
	 *
	 * @since 1.0
	 *
	 * @param $size : array, image size
	 * @param $echo : bool, echo or return no image url
	 **/
	function famiau_no_image( $size = array( 'width' => 500, 'height' => 500 ), $echo = false, $transparent = false
	) {
		$noimage_dir = FAMIAU_PATH . 'assets';
		$noimage_uri = FAMIAU_URI . 'assets';
		$suffix      = ( $transparent ) ? '_transparent' : '';
		if ( ! is_array( $size ) || empty( $size ) ):
			$size = array( 'width' => 500, 'height' => 500 );
		endif;
		if ( ! is_numeric( $size['width'] ) && $size['width'] == '' || $size['width'] == null ):
			$size['width'] = 'auto';
		endif;
		if ( ! is_numeric( $size['height'] ) && $size['height'] == '' || $size['height'] == null ):
			$size['height'] = 'auto';
		endif;
		
		if ( file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) ) {
			if ( $echo ) {
				echo esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
			}
			
			return esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
		}
		
		// base image must be exist
		$img_base_fullpath = $noimage_dir . '/images/noimage/no_image' . $suffix . '.png';
		$no_image_src      = $noimage_uri . '/images/noimage/no_image' . $suffix . '.png';
		// Check no image exist or not
		if ( ! file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) && is_writable( $noimage_dir . '/images/noimage/' ) ):
			$no_image = wp_get_image_editor( $img_base_fullpath );
			if ( ! is_wp_error( $no_image ) ):
				$no_image->resize( $size['width'], $size['height'], true );
				$no_image_name = $no_image->generate_filename( $size['width'] . 'x' . $size['height'], $noimage_dir . '/images/noimage/', null );
				$no_image->save( $no_image_name );
			endif;
		endif;
		// Check no image exist after resize
		$noimage_path_exist_after_resize = $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		if ( file_exists( $noimage_path_exist_after_resize ) ):
			$no_image_src = $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		endif;
		
		if ( $echo ) {
			echo esc_url( $no_image_src );
		}
		
		return esc_url( $no_image_src );
	}
}

if ( ! function_exists( 'famiau_img_lazy' ) ) {
	function famiau_img_lazy( $width = 1, $height = 1 ) {
		// $img_lazy = 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20' . $width . '%20' . $height . '%27%2F%3E';
		// $img_lazy = 'https://via.placeholder.com/' . $width . 'x' . $height . '/fff/fff';
		$img_lazy = famiau_no_image(
			array(
				'width'  => $width,
				'height' => $height
			), false, true );
		
		return $img_lazy;
	}
}

if ( ! function_exists( 'famiau_img_output' ) ) {
	/**
	 * @param array  $img
	 * @param string $class
	 * @param string $alt
	 * @param string $title
	 *
	 * @return string
	 */
	function famiau_img_output( $img, $class = '', $alt = '', $title = '' ) {
		
		$img_default = array(
			'width'  => '',
			'height' => '',
			'url'    => ''
		);
		$img         = wp_parse_args( $img, $img_default );
		$enable_lazy = famiau_is_enable_lazy_load();
		
		if ( $enable_lazy ) {
			$img_lazy = famiau_img_lazy( $img['width'], $img['height'] );
			$img_html = '<img class="fami-img fami-lazy lazy ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . $img_lazy . '" data-src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		} else {
			$img_html = '<img class="fami-img ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		}
		
		return $img_html;
	}
}

if ( ! function_exists( 'famiau_is_enable_lazy_load' ) ) {
	function famiau_is_enable_lazy_load() {
		return famiau_get_option( '_famiau_enable_lazy_load', 'yes' ) == 'yes';
	}
}

if ( ! function_exists( 'famiau_get_row_info' ) ) {
	function famiau_get_row_info( $table, $filter = "", $order = "" ) {
		global $wpdb;
		$sql    = "SELECT * " .
		          "FROM {$table} " .
		          "WHERE 1 " .
		          "{$filter} " .
		          "{$order} " .
		          "LIMIT 0, 1" .
		          " ";
		$result = $wpdb->get_row( $sql );
		
		return $result;
	}
}

if (!function_exists('famiau_box_leading_num')) {
    function famiau_box_leading_num( $num = 0 ) {
	    $num_text = $num;
	    if ( $num < 10 ) {
		    $num_text = '0' . $num;
	    }
	
	    return $num_text;
    }
}
