<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Azirspares Content Page
 *
 * Displays Content Page widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Azirspares/Widgets
 * @version  1.0.0
 * @extends  AZIRSPARES_Widget
 */
if ( !class_exists( 'Azirspares_Content_Page_Widget' ) ) {
	class Azirspares_Content_Page_Widget extends AZIRSPARES_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'azirspares_filter_settings_widget_content_page',
				array(
					'title'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'azirspares-toolkit' ),
					),
					'azirspares_page_id' => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Select Content', 'azirspares-toolkit' ),
						'options' => 'pages',
					),
				)
			);
			$this->widget_cssclass    = 'widget-azirspares-content_page azirspares-content_page';
			$this->widget_description = esc_html__( 'Display the customer Content Page.', 'azirspares-toolkit' );
			$this->widget_id          = 'widget_azirspares_content_page';
			$this->widget_name        = esc_html__( 'Azirspares: Content Page', 'azirspares-toolkit' );
			$this->settings           = $array_settings;
			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
		{
			$this->widget_start( $args, $instance );
			if ( $instance['azirspares_page_id'] ) {
				$post_id = get_post( $instance['azirspares_page_id'] );
				$content = $post_id->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]>', $content );
				/* GET CUSTOM CSS */
				$post_custom_css = get_post_meta( $instance['azirspares_page_id'], '_Azirspares_Shortcode_custom_css', true );
				echo '<style type="text/css">' . $post_custom_css . '</style>';
				echo $content;
			}
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Azirspares_Content_Page_Widget()
{
	register_widget( 'Azirspares_Content_Page_Widget' );
}

add_action( 'widgets_init', 'Azirspares_Content_Page_Widget' );