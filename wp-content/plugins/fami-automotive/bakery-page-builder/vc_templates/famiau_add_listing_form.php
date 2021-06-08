<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'famiauVcShortcode_Add_Listing_Form' ) ) {
	class famiauVcShortcode_Add_Listing_Form extends famiauVcShortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'add_listing_form'; // required
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'famiau_add_listing_form', $atts ) : $atts;
			extract( $atts );
			
			$css_class    = array( 'famiau-add-listing-shortcode-wrap' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'famiau_add_listing_form', $atts );
			
			$html            = '';
			$short_desc_html = '';
			$form_html       = '';
			
			if ( trim( $short_description ) != '' ) {
				$short_desc_html .= '<div class="famiau-box famiau-step-box famiau-page-short-desc-box">' . wpautop( do_shortcode( $short_description ) ) . '</div>';
			}
			
			ob_start();
			famiau_get_template_part( 'listing-form/famiau-listing', 'form' );
			$form_html .= ob_get_clean();
			
			$html .= $short_desc_html . $form_html;
			
			if ( $html != '' ) {
				$html = '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $html . '</div>';
			}
			
			return apply_filters( 'famiauVcShortcode_Add_Listing_Form', $html, $atts, $content );
		}
	}
	
	new famiauVcShortcode_Add_Listing_Form();
}