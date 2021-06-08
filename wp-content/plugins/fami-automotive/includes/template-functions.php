<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famiau_template_redirect( $original_template ) {
	$all_options = famiau_get_all_options();
	
	if ( is_page() ) {
		$page_for_automotive     = $all_options['_famiau_page_for_automotive'];
		$page_for_account        = $all_options['_famiau_page_for_account'];
		$page_id                 = get_the_ID();
		$pages_exclude           = array();
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
		
		if ( in_array( $page_id, $pages_exclude ) ) {
			return $original_template;
		}
		
		if ( $page_id == $page_for_automotive ) {
			// return FAMIAU_PATH . 'includes/listing-page.php';
			return famiau_locate_template( 'archive-famiau.php' );
		} else {
			if ( $page_id == $page_for_account ) {
				// return FAMIAU_PATH . 'includes/account-page.php';
				return famiau_locate_template( 'account/famiau-account.php' );
			}
		}
	}
	
	if ( is_post_type_archive( 'famiau' ) ) {
		return famiau_locate_template( 'archive-famiau.php' );
	}
	
	if ( is_singular( 'famiau' ) ) {
		return famiau_locate_template( 'single-famiau.php' );
	}

	return $original_template;
}

add_filter( 'template_include', 'famiau_template_redirect' );

/**
 * Get template part implementation
 *
 * Looks at the theme directory first
 */
function famiau_get_template_part( $slug, $name = '', $args = array() ) {
	$famiAutomotive = new famiAutomotive();
	
	$defaults = array(
		'pro' => false
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	
	// Look in yourtheme/famiau/slug-name.php and yourtheme/famiau/slug.php
	$template = locate_template( array(
		                             $famiAutomotive->template_path() . "{$slug}-{$name}.php",
		                             $famiAutomotive->template_path() . "{$slug}.php"
	                             ) );
	
	$template_path = apply_filters( 'famiau_set_template_path', FAMIAU_PATH . '/templates', $template, $args );
	
	// Get default slug-name.php
	if ( ! $template && $name && file_exists( $template_path . "/{$slug}-{$name}.php" ) ) {
		$template = $template_path . "/{$slug}-{$name}.php";
	}
	
	if ( ! $template && file_exists( $template_path . "/{$slug}.php" ) ) {
		$template = $template_path . "/{$slug}.php";
	}
	
	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'famiau_get_template_part', $template, $slug, $name );
	
	if ( $template ) {
		include( $template );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 *
 * @param mixed  $template_name
 * @param array  $args          (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 *
 * @return void
 */
function famiau_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	
	$located = famiau_locate_template( $template_name, $template_path, $default_path );
	
	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0' );
		
		return;
	}
	
	do_action( 'famiau_before_template_part', $template_name, $template_path, $located, $args );
	
	include( $located );
	
	do_action( 'famiau_after_template_part', $template_name, $template_path, $located, $args );
}


/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @access public
 *
 * @param mixed  $template_name
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 *
 * @return string
 */
function famiau_locate_template( $template_name, $template_path = '', $default_path = '', $pro = false ) {
	$famiAutomotive = new famiAutomotive();
	
	if ( ! $template_path ) {
		$template_path = $famiAutomotive->template_path();
	}
	
	if ( ! $default_path ) {
		$default_path = FAMIAU_PATH . '/templates/';
	}
	
	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
		)
	);
	
	// Get default template
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}
	
	// Return what we found
	return apply_filters( 'famiau_locate_template', $template, $template_name, $template_path );
}