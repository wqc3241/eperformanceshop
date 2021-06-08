<?php
/**
 * Azirspares Footer Builder setup
 *
 * @author   FAMI
 * @category API
 * @package  Azirspares_Footer_Builder
 * @since    1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Azirspares Footer Builder setup
 *
 * @Active: add_theme_support( 'azirspares-footer-builder' );
 */
if ( !class_exists( 'Azirspares_Footer_Builder' ) ) {
	class Azirspares_Footer_Builder
	{
		public function __construct()
		{
			add_action( 'init', array( &$this, 'post_type' ), 999 );
			add_action( 'wp_footer', array( $this, 'azirspares_footer_content' ) );
			add_filter( 'azirspares_main_custom_css', array( $this, 'azirspares_shortcodes_custom_css' ) );
		}

		function azirspares_footer_content()
		{
            $data_meta           = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
            $footer_options      = ( class_exists( 'Azirspares_Functions' ) ) ? Azirspares_Functions::azirspares_get_option( 'azirspares_footer_options' ) : cs_get_option( 'azirspares_footer_options', '' );
            $footer_options      = isset( $data_meta['azirspares_footer_enable'] ) && $data_meta['azirspares_footer_enable'] == 1 && isset( $data_meta['metabox_azirspares_footer_options'] ) && $data_meta['metabox_azirspares_footer_options'] != '' ? $data_meta['metabox_azirspares_footer_options'] : $footer_options;
			$class = array( 'footer', 'azirspares-footer' );
			$args  = array(
				'post_type'      => 'footer',
				'posts_per_page' => 1,
			);
			if ( $footer_options ) {
				$args['p'] = $footer_options;
			}
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				$class[] = 'empty-footer';
			}
			ob_start(); ?>
            <footer id ="footer" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
				<?php if ( $query->have_posts() ): ?>
					<?php while ( $query->have_posts() ): $query->the_post();
						$post_id = get_post( get_the_ID() );
						$content = $post_id->post_content;
						$content = apply_filters( 'the_content', $content );
						$content = str_replace( ']]>', ']]>', $content );
						echo '<div class="container">' . wp_specialchars_decode( $content ) . '</div>';
					endwhile;
					wp_reset_postdata(); ?>
				<?php endif; ?>
            </footer>
			<?php echo apply_filters( 'azirspares_filter_content_footer', ob_get_clean(), $class );
		}

		function azirspares_shortcodes_custom_css( $css )
		{
			$args = array(
				'post_type'      => 'footer',
				'posts_per_page' => -1,
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$post_custom_css   = array();
				$post_custom_css[] = get_post_meta( get_the_ID(), '_wpb_post_custom_css', true );
				$post_custom_css[] = get_post_meta( get_the_ID(), '_Azirspares_Shortcode_custom_css', true );
				if ( count( $post_custom_css ) > 0 ) {
					$css .= implode( ' ', $post_custom_css );
				}
			endwhile;
			wp_reset_postdata();

			return $css;
		}

		function post_type()
		{
			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'azirspares-toolkit' ),
					'singular_name'      => __( 'Footers', 'azirspares-toolkit' ),
					'add_new'            => __( 'Add New', 'azirspares-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'azirspares-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'azirspares-toolkit' ),
					'new_item'           => __( 'New footer', 'azirspares-toolkit' ),
					'view_item'          => __( 'View footer', 'azirspares-toolkit' ),
					'search_items'       => __( 'Search template footer', 'azirspares-toolkit' ),
					'not_found'          => __( 'No template items found', 'azirspares-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'azirspares-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'azirspares-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'azirspares-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'azirspares-toolkit' ),
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
					'revisions',
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'azirspares_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );
		}
	}

	new Azirspares_Footer_Builder();
}