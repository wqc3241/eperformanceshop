<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'famiauVcShortcode_Listings' ) ) {
	class famiauVcShortcode_Listings extends famiauVcShortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'listings'; // required
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'famiau_listings', $atts ) : $atts;
			extract( $atts );
			
			$css_class    = array( 'famiau-listings-shortcode-wrap' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'famiau_listings', $atts );
			
			$html = '';
			
			$number_of_listings = intval( $number_of_listings );
			if ( $number_of_listings <= 0 ) {
				$number_of_listings = 12;
			}
			
			if ( $listings_layout == 'default' ) {
				// global $famiau;
				$listings_layout = 'grid'; // need options
			}
			
			$query_args = array(
				'post_type'      => 'famiau',
				'posts_per_page' => $number_of_listings
			);
			
			switch ( $condition ) {
				case 'featured':
					$query_args['meta_query'] = array(
						array(
							'key'     => '_famiau_is_featured',
							'value'   => 'yes',
							'compare' => '='
						),
					);
					break;
				case 'custom':
					$famiau_ids = trim( $famiau_ids );
					if ( $famiau_ids != '' ) {
						$famiau_ids             = array_filter( explode( ',', $famiau_ids ), 'trim' );
						$query_args['post__in'] = $famiau_ids;
					}
					break;
			}
			
			ob_start();
			
			$listings = new WP_Query( $query_args );
			if ( $listings->have_posts() ) {
				while ( $listings->have_posts() ) {
					$listings->the_post();
					famiau_get_template_part( 'content-famiau', $listings_layout );
				}
			}
			wp_reset_postdata();
			$html .= ob_get_clean();
			
			if ( $html != '' ) {
				$html = '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '"><div class="famiau-row row"><div class="famiau-listings auto-clear famiau-auto-clear">' . $html . '</div></div></div>';
			}
			
			return apply_filters( 'famiauVcShortcode_Listings', $html, $atts, $content );
		}
	}
	
	new famiauVcShortcode_Listings();
}