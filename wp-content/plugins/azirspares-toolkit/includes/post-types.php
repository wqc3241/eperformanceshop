<?php
/**
 * @version    1.0
 * @package    Azirspares_Toolkit
 * @author     Azirspares
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( !class_exists( 'Azirspares_Post_Type' ) ) {
	class Azirspares_Post_Type
	{
		public function __construct()
		{
			add_action( 'init', array( &$this, 'init' ), 9999 );
		}

		public static function init()
		{
			/* Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'azirspares-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'azirspares-toolkit' ),
					'add_new'            => __( 'Add new', 'azirspares-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'azirspares-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'azirspares-toolkit' ),
					'new_item'           => __( 'New menu item', 'azirspares-toolkit' ),
					'view_item'          => __( 'View menu item', 'azirspares-toolkit' ),
					'search_items'       => __( 'Search menu items', 'azirspares-toolkit' ),
					'not_found'          => __( 'No menu items found', 'azirspares-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'azirspares-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'azirspares-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'azirspares-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'azirspares-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'azirspares_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );
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
				'supports'            => array( 'title', 'editor' ),
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

	new Azirspares_Post_Type();
}
