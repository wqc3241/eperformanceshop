<?php

// Register Sidebars
function famiau_sidebars() {
	
	$args = array(
		'id'            => 'famiau-sidebar',
		'class'         => 'sidebar famiau-sidebar',
		'name'          => esc_html__( 'Fami AU Listings Sidebar', 'famiau' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	);
	register_sidebar( $args );
	
	$args = array(
		'id'            => 'famiau-single-sidebar',
		'class'         => 'sidebar famiau-sidebar famiau-single-sidebar',
		'name'          => esc_html__( 'Fami AU Single Sidebar', 'famiau' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	);
	register_sidebar( $args );
	
}

add_action( 'widgets_init', 'famiau_sidebars' );