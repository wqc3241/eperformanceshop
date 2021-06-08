<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famiau_filers_list_for_dropdown_params() {
	$filters_list = array(
		esc_html__( 'Years', 'famiau' )          => 'years',
		esc_html__( 'Condition', 'famiau' )      => '_famiau_car_status',
		esc_html__( 'Make', 'famiau' )           => 'all_makes',
		esc_html__( 'Model', 'famiau' )          => 'models',
		esc_html__( 'Price', 'famiau' )          => 'max_price',
		esc_html__( 'Fuel', 'famiau' )           => 'all_fuel_types',
		esc_html__( 'Car Body', 'famiau' )       => 'all_car_bodies',
		esc_html__( 'Drive', 'famiau' )          => 'all_drives',
		esc_html__( 'Exterior Color', 'famiau' ) => 'all_exterior_colors',
		esc_html__( 'Interior Color', 'famiau' ) => 'all_interior_colors',
		esc_html__( 'Transmission', 'famiau' )   => 'all_gearbox_types',
	);
	
	return $filters_list;
}

function famiau_vc_shortcode_params() {
	$filters_list = famiau_filers_list_for_dropdown_params();
	$param_maps   = array(
		'famiau_filter_dropdown'  => array(
			'base'        => 'famiau_filter_dropdown',
			'name'        => esc_html__( 'AU: Filter Dropdown', 'famiau' ),
			'icon'        => '', // FAMIAU_URI . 'assets/images/famiau_filter_dropdown.png' ,
			'category'    => esc_html__( 'AU', 'famiau' ),
			'description' => esc_html__( 'Display the listing filter as dropdowns', 'famiau' ),
			'params'      => array(
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'famiau' ),
					'std'        => esc_html__( 'Find A Car', 'famiau' ),
					'param_name' => 'title'
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Select Filters', 'famiau' ),
					'param_name' => 'filters_selected',
					'value'      => $filters_list,
					'std'        => 'all_makes,models,max_price'
				),
				array(
					'type'        => 'exploded_textarea',
					'heading'     => esc_html__( 'Price List', 'famiau' ),
					'std'         => '0,5000,10000,15000,20000,30000,40000,50000,70000,80000,100000',
					'param_name'  => 'price_list',
					'dependency'  => array(
						'element' => 'filters_selected',
						'value'   => array( 'max_price' ),
					),
					'description' => esc_html__( 'Enter the price list, each price per line', 'famiau' )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Price Select Text', 'famiau' ),
					'std'        => esc_html__( 'Max Price', 'famiau' ),
					'param_name' => 'price_select_text',
					'dependency' => array(
						'element' => 'filters_selected',
						'value'   => array( 'max_price' ),
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button Text', 'famiau' ),
					'std'        => esc_html__( 'Search', 'famiau' ),
					'param_name' => 'button_text'
				),
			)
		),
		'famiau_listings'         => array(
			'base'        => 'famiau_listings',
			'name'        => esc_html__( 'AU: Car Listings', 'famiau' ),
			'icon'        => '', // FAMIAU_URI . 'assets/images/famiau_listings.png' ,
			'category'    => esc_html__( 'AU', 'famiau' ),
			'description' => esc_html__( 'Display the car listings', 'famiau' ),
			'params'      => array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Layout', 'famiau' ),
					'param_name' => 'listings_layout',
					'std'        => 'default',
					'value'      => array(
						esc_html__( 'Default (Use General Settings)', 'automotive' ) => 'default',
						esc_html__( 'Grid', 'automotive' )                           => 'grid',
						esc_html__( 'List', 'automotive' )                           => 'list',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Condition', 'famiau' ),
					'param_name' => 'condition',
					'std'        => 'default',
					'value'      => array(
						esc_html__( 'Recent', 'famiau' )   => 'recent',
						esc_html__( 'Featured', 'famiau' ) => 'featured',
						esc_html__( 'Custom', 'famiau' )   => 'custom',
					),
				),
				array(
					'type'        => 'autocomplete',
					'heading'     => esc_html__( 'Select Listings', 'famiau' ),
					'param_name'  => 'famiau_ids',
					'settings'    => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true,
					),
					'save_always' => true,
					'description' => esc_html__( 'Select listings', 'famiau' ),
					'dependency'  => array( 'element' => 'condition', 'value' => array( 'custom' ) ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The number of listings to display', 'famiau' ),
					'param_name' => 'number_of_listings',
					'std'        => 12
				),
			)
		),
		// https://developers.google.com/maps/documentation/javascript/marker-clustering
		'famiau_map_listings'     => array(
			'base'        => 'famiau_map_listings',
			'name'        => esc_html__( 'AU: Map Listings', 'famiau' ),
			'icon'        => '', // FAMIAU_URI . 'assets/images/famiau_map_listings.png' ,
			'category'    => esc_html__( 'AU', 'famiau' ),
			'description' => esc_html__( 'Show listings on Google map', 'famiau' ),
			'params'      => array(
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Map Latitude', 'famiau' ),
					'param_name'  => 'map_latitude',
					'std'         => '39.011902',
					'description' => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.latlong.net/', __( 'Lat and Long Finder', 'famiau' ) )
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Map Longitude', 'famiau' ),
					'param_name'  => 'map_longitude',
					'std'         => '-98.484245',
					'description' => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.latlong.net/', __( 'Lat and Long Finder', 'famiau' ) )
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Default Zoom Level', 'famiau' ),
					'param_name'  => 'map_zoom_default',
					'std'         => '4',
					'description' => esc_html__( 'The value is between 1 and 15. The default is 4.', 'famiau' )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'famiau' ),
					'std'        => esc_html__( 'Search Options', 'famiau' ),
					'param_name' => 'title'
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Select Filters', 'famiau' ),
					'param_name' => 'filters_selected',
					'value'      => $filters_list,
					'std'        => 'all_makes,models,max_price'
				),
				array(
					'type'        => 'exploded_textarea',
					'heading'     => esc_html__( 'Price List', 'famiau' ),
					'std'         => '0,5000,10000,15000,20000,30000,40000,50000,70000,80000,100000',
					'param_name'  => 'price_list',
					'dependency'  => array(
						'element' => 'filters_selected',
						'value'   => array( 'max_price' ),
					),
					'description' => esc_html__( 'Enter the price list, each price per line', 'famiau' )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Price Select Text', 'famiau' ),
					'std'        => esc_html__( 'Max Price', 'famiau' ),
					'param_name' => 'price_select_text',
					'dependency' => array(
						'element' => 'filters_selected',
						'value'   => array( 'max_price' ),
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button Text', 'famiau' ),
					'std'        => esc_html__( 'Search', 'famiau' ),
					'param_name' => 'button_text'
				),
			)
		),
		'famiau_add_listing_form' => array(
			'base'        => 'famiau_add_listing_form',
			'name'        => esc_html__( 'AU: Add Listing Form', 'famiau' ),
			'icon'        => '', // FAMIAU_URI . 'assets/images/famiau_map_listings.png' ,
			'category'    => esc_html__( 'AU', 'famiau' ),
			'description' => esc_html__( 'Show add new listing form', 'famiau' ),
			'params'      => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Description', 'famiau' ),
					'std'         => '',
					'param_name'  => 'short_description',
					'description' => esc_html__( 'Description show before the form', 'famiau' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Button Text', 'famiau' ),
					'std'         => esc_html__( 'Submit New Listing', 'famiau' ),
					'param_name'  => 'submit_text',
					'description' => esc_html__( 'Submit new listing text', 'famiau' )
				),
			)
		)
	);
	$param_maps   = apply_filters( 'famiau_vc_shortcode_params', $param_maps );
	
	return $param_maps;
}

function famiau_vc_map_shortcodes() {
	$param_maps = famiau_vc_shortcode_params();
	
	if ( empty( $param_maps ) ) {
		return;
	}
	
	foreach ( $param_maps as $value ) {
		if ( function_exists( 'vc_map' ) ) {
			vc_map( $value );
		}
	}
	
}

add_action( 'vc_before_init', 'famiau_vc_map_shortcodes' );

function famiau_vc_templates( $template_name ) {
	$vc_templates  = apply_filters( 'famiau_vc_templates', 'vc_templates' );
	$template_path = FAMIAU_PATH . 'bakery-page-builder/' . $vc_templates;
	if ( file_exists( get_stylesheet_directory() . '/' . $vc_templates . '/' . $template_name . '.php' ) ) {
		include_once get_stylesheet_directory() . '/' . $vc_templates . '/' . $template_name . '.php';
	} else {
		if ( file_exists( get_stylesheet_directory() . '/' . $vc_templates . '/' . $template_name . '.php' ) ) {
			include_once get_template_directory_uri() . '/' . $vc_templates . '/' . $template_name . '.php';
		} else {
			if ( file_exists( $template_path . '/' . $template_name . '.php' ) ) {
				include_once $template_path . '/' . $template_name . '.php';
			}
		}
	}
}

function famiau_vc_shortcode_templates() {
	$param_maps = famiau_vc_shortcode_params();
	if ( ! empty( $param_maps ) ) {
		foreach ( $param_maps as $param_map ) {
			famiau_vc_templates( $param_map['base'] );
		}
	}
}

add_action( 'vc_after_init', 'famiau_vc_shortcode_templates' );