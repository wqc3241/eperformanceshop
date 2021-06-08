<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Register Custom Post Type
function famiau_custom_post_type() {
	
	$listings_page_id = famiau_get_page( 'automotive' );
	if ( $listings_page_id && get_post( $listings_page_id ) ) {
		$has_archive = urldecode( get_page_uri( $listings_page_id ) );
	} else {
		$has_archive = true; // default listings taxonomy archive
	}
	// $has_archive = $listings_page_id && get_post( $listings_page_id ) ? urldecode( get_page_uri( $listings_page_id ) ) : 'listings';
	
	$labels  = array(
		'name'                  => _x( 'AU Listings', 'Post Type General Name', 'famiau' ),
		'singular_name'         => _x( 'AU Listing', 'Post Type Singular Name', 'famiau' ),
		'menu_name'             => __( 'AU Listings', 'famiau' ),
		'name_admin_bar'        => __( 'AU Listings', 'famiau' ),
		'archives'              => __( 'Listing Archives', 'famiau' ),
		'attributes'            => __( 'Listing Attributes', 'famiau' ),
		'parent_item_colon'     => __( 'Parent Listing:', 'famiau' ),
		'all_items'             => __( 'All Listings', 'famiau' ),
		'add_new_item'          => __( 'Add New Listing', 'famiau' ),
		'add_new'               => __( 'Add New', 'famiau' ),
		'new_item'              => __( 'New Listing', 'famiau' ),
		'edit_item'             => __( 'Edit Listing', 'famiau' ),
		'update_item'           => __( 'Update Listing', 'famiau' ),
		'view_item'             => __( 'View Listing', 'famiau' ),
		'view_items'            => __( 'View Listings', 'famiau' ),
		'search_items'          => __( 'Search Listings', 'famiau' ),
		'not_found'             => __( 'Not found', 'famiau' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'famiau' ),
		'featured_image'        => __( 'Featured Image', 'famiau' ),
		'set_featured_image'    => __( 'Set featured image', 'famiau' ),
		'remove_featured_image' => __( 'Remove featured image', 'famiau' ),
		'use_featured_image'    => __( 'Use as featured image', 'famiau' ),
		'insert_into_item'      => __( 'Insert into listing', 'famiau' ),
		'uploaded_to_this_item' => __( 'Uploaded to this listing', 'famiau' ),
		'items_list'            => __( 'Listings list', 'famiau' ),
		'items_list_navigation' => __( 'Listings list navigation', 'famiau' ),
		'filter_items_list'     => __( 'Filter listing list', 'famiau' ),
	);
	$rewrite = array(
		'slug'       => 'listings',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);
	$rewrite = apply_filters( 'fami_au_listings_rewrite', $rewrite );
	/*
	$capabilities = array(
		'edit_post'          => 'edit_famiau',
		'edit_posts'         => 'edit_famiaus',
		'edit_others_posts'  => 'edit_other_famiaus',
		'publish_posts'      => 'publish_famiaus',
		'read_post'          => 'read_famiau',
		'read_private_posts' => 'read_private_famiaus',
		'delete_post'        => 'delete_famiau'
	);
	*/
	$args = array(
		'label'               => __( 'AU Listings', 'famiau' ),
		'description'         => __( 'Automotive listings', 'famiau' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', 'author' ),
		'taxonomies'          => array( 'famiau_cat' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 4,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => $has_archive, // true
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
		// 'capabilities'        => $capabilities,
		// as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly
		// 'map_meta_cap'        => true
	);
	register_post_type( 'famiau', $args );
	
}

add_action( 'init', 'famiau_custom_post_type', 0 );

// Register Custom Taxonomy
function famiau_custom_taxonomy() {
	
	$labels  = array(
		'name'                       => _x( 'AU Categories', 'Taxonomy General Name', 'famiau' ),
		'singular_name'              => _x( 'AU Category', 'Taxonomy Singular Name', 'famiau' ),
		'menu_name'                  => __( 'AU Categories', 'famiau' ),
		'all_items'                  => __( 'All Categories', 'famiau' ),
		'parent_item'                => __( 'Parent Category', 'famiau' ),
		'parent_item_colon'          => __( 'Parent Category:', 'famiau' ),
		'new_item_name'              => __( 'New Category Name', 'famiau' ),
		'add_new_item'               => __( 'Add New Category', 'famiau' ),
		'edit_item'                  => __( 'Edit Category', 'famiau' ),
		'update_item'                => __( 'Update Category', 'famiau' ),
		'view_item'                  => __( 'View Category', 'famiau' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'famiau' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'famiau' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'famiau' ),
		'popular_items'              => __( 'Popular Categories', 'famiau' ),
		'search_items'               => __( 'Search Categories', 'famiau' ),
		'not_found'                  => __( 'Not Found', 'famiau' ),
		'no_terms'                   => __( 'No categories', 'famiau' ),
		'items_list'                 => __( 'Categories list', 'famiau' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'famiau' ),
	);
	$rewrite = array(
		'slug'         => 'au-category',
		'with_front'   => true,
		'hierarchical' => false,
	);
	$args    = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'rewrite'           => $rewrite,
	);
	register_taxonomy( 'famiau_cat', array( 'famiau' ), $args );
	
}

add_action( 'init', 'famiau_custom_taxonomy', 0 );

function famiau_rest_api_allowed_post_types( $post_types ) {
	$post_types[] = 'famiau';
	
	return $post_types;
}

add_filter( 'rest_api_allowed_post_types', 'famiau_rest_api_allowed_post_types' );
