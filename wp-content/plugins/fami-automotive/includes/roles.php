<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famiau_role_exists( $role ) {
	
	if ( ! empty( $role ) ) {
		return $GLOBALS['wp_roles']->is_role( $role );
	}
	
	return false;
}

function famiau_add_custom_role() {
	
	// remove_role( 'famiau_user' );
	if ( ! famiau_role_exists( 'famiau_user' ) ) {
		add_role( 'famiau_user', esc_html__( 'Fami Automotive User', 'famiau' ),
		          array(
			          'read'            => true,  // true allows this capability
			          'upload_files'    => true,  // true allows this capability
			          /*'edit_famiau'     => true,
			          'edit_famiaus'    => true,
			          'publish_famiaus' => true,
			          'read_famiau'     => true,
			          'delete_famiau'   => true, */
		          ) );
	}
	
	// gets the administrator role
	/*
	$admins = get_role( 'administrator' );
	
	$admins->add_cap( 'edit_famiau' );
	$admins->add_cap( 'edit_famiaus' );
	$admins->add_cap( 'edit_other_famiaus' );
	$admins->add_cap( 'publish_famiaus' );
	$admins->add_cap( 'read_famiau' );
	$admins->add_cap( 'read_private_famiaus' );
	$admins->add_cap( 'delete_famiau' );
	*/
	
}

add_action( 'init', 'famiau_add_custom_role' );

// Register User Contact Methods
function famiau_user_contact_methods( $user_contact_method ) {
	
	$user_contact_method['famiau_user_mobile'] = esc_html__( 'Listing Contact Mobile', 'famiau' );
	
	return $user_contact_method;
	
}

add_filter( 'user_contactmethods', 'famiau_user_contact_methods' );

function famiau_users_own_attachments( $wp_query_obj ) {
	
	global $current_user, $pagenow;
	
	if ( current_user_can( 'famiau_user' ) ) {
		$is_attachment_request = ( $wp_query_obj->get( 'post_type' ) == 'attachment' );
		
		if ( ! $is_attachment_request ) {
			return;
		}
		
		if ( ! is_a( $current_user, 'WP_User' ) ) {
			return;
		}
		
		if ( ! in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) ) {
			return;
		}
		
		if ( ! current_user_can( 'delete_pages' ) ) {
			$wp_query_obj->set( 'author', $current_user->ID );
		}
	}
	
	return;
}

add_action( 'pre_get_posts', 'famiau_users_own_attachments' );
