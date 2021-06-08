<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'famiauMenuScriptsStyles' ) ) {
	class famiauMenuScriptsStyles {
		public $version = '1.0.0';
		
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'famiau_admin_bar_menu' ), 1000 );
			add_action( 'admin_menu', array( $this, 'famiau_menu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}
		
		public function famiau_admin_bar_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'famiau_option',
				'title' => esc_html__( 'Fami Automotive', 'famiau' ),
				'href'  => admin_url( 'admin.php?page=famiau' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function famiau_menu_page() {
			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$menu_args = array(
				array(
					'page_title' => esc_html__( 'AU Settings', 'famiau' ),
					'menu_title' => esc_html__( 'AU Settings', 'famiau' ),
					'cap'        => 'manage_options',
					'menu_slug'  => 'famiau',
					'function'   => array( $this, 'famiau_menu_page_callback' ),
					'icon'       => '', // FAMIAU_URI . 'assets/images/logo.png',
					'parrent'    => '',
					'position'   => 4
				),
				array(
					'page_title' => esc_html__( 'All Listings (Admin)', 'famiau' ),
					'menu_title' => esc_html__( 'All Listings (Admin)', 'famiau' ),
					'cap'        => 'manage_options',
					'menu_slug'  => 'famiau-all-listings',
					'function'   => array( $this, 'famiau_menu_page_callback' ),
					'icon'       => '', // FAMIAU_URI . 'assets/images/logo.png',
					'parrent'    => 'famiau'
				)
			);
			foreach ( $menu_args as $menu_arg ) {
				if ( $menu_arg['parrent'] == '' ) {
					add_menu_page( $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'], $menu_arg['icon'], $menu_arg['position'] );
				} else {
					add_submenu_page( $menu_arg['parrent'], $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'] );
				}
			}
		}
		
		public function famiau_menu_page_callback() {
			$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
			if ( trim( $page ) != '' ) {
				$file_path = FAMIAU_PATH . 'includes/admin-pages/' . $page . '.php';
				if ( file_exists( $file_path ) ) {
					require_once FAMIAU_PATH . 'includes/admin-pages/' . $page . '.php';
				}
			}
		}
		
		function admin_scripts( $hook ) {
			global $typenow;
			
			wp_enqueue_style( 'jquery-ui' );
			// wp_enqueue_style( 'bootstrap-datepicker3', FAMIAU_URI . 'assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' );
			
			$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
			
			if ( in_array( $page, array( 'famiau', 'famiau-all-listings' ) ) ) {
				wp_enqueue_style( 'famiau-bootstrap-backend', FAMIAU_URI . 'assets/css/famiau-bootstrap-backend.css' );
			}
			
			wp_enqueue_style( 'famiau-backend', FAMIAU_URI . 'assets/css/backend.css' );
			
			if ( $typenow == 'product' ) {
				wp_dequeue_script( 'jquery-ui-datepicker' );
			}
			if ( $typenow == 'famiau' ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-slider' );
			// wp_enqueue_script( 'bootstrap-datepicker', FAMIAU_URI . 'assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js', array(), null );
			wp_enqueue_script( 'famiau-backend', FAMIAU_URI . 'assets/js/backend.js', array(), null );
			
			$backend_nonce       = wp_create_nonce( 'famiau_backend_nonce' );
			$import_settings_url = esc_url( add_query_arg( array(
				                                               'action' => 'famiau_import_all_settings',
				                                               'nonce'  => $backend_nonce
			                                               ), admin_url( 'admin-ajax.php' ) ) );
			wp_localize_script( 'famiau-backend', 'famiau',
			                    array(
				                    'ajaxurl'             => admin_url( 'admin-ajax.php' ),
				                    'security'            => $backend_nonce,
				                    'import_settings_url' => $import_settings_url,
				                    'html_temp'           => array(
					                    'popup'                     => famiau_popup_template(),
					                    'desc_group_form_template'  => famiau_desc_group_form_template(),
					                    'desc_detail_form_template' => famiau_desc_detail_form_template(),
					                    'make_item'                 => famiau_add_make_item_template( '{make}', '{models}' ),
					                    'model_item'                => famiau_model_item_template( '{model}' ),
				                    ),
				                    'text'                => array(
					                    'confirm_remove_item'      => esc_html__( 'Are you sure you want to remove this?', 'famiau' ),
					                    'confirm_remove_make_item' => esc_html__( 'Are you sure you want to remove this make?', 'famiau' ),
					                    'select_model'             => esc_html__( 'Select', 'famiau' ),
					                    'no_model_to_select'       => esc_html__( 'No model to select', 'famiau' ),
					                    'confirm_action_waiting'   => esc_html__( 'Are you sure you want to change status to reviewing (waiting)?', 'famiau' ),
					                    'confirm_action_deleted'   => esc_html__( 'Are you sure you want to change status to deleted?', 'famiau' ),
					                    'confirm_action_approved'  => esc_html__( 'Are you sure you want to change status to approved?', 'famiau' ),
					                    'confirm_import_settings'  => esc_html__( 'All current listings settings will be overwritten and CAN NOT BE UNDONE! Are you sure you want to import settings?', 'famiau' )
				                    )
			                    )
			);
		}
		
		function frontend_scripts( $hook ) {
			$all_options = famiau_get_all_options();
			
			wp_enqueue_style( 'bootstrap-datepicker3', FAMIAU_URI . 'assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' );
			wp_enqueue_style( 'bootstrap', FAMIAU_URI . 'assets/vendors/bootstrap/css/bootstrap.min.css' );
			if ( is_singular( 'famiau' ) ) {
				wp_enqueue_style( 'font-awesome', FAMIAU_URI . 'assets/vendors/font-awesome/css/font-awesome.min.css' );
			}
			
			$is_account_page = 'no';
			if ( is_singular() ) {
				global $post;
				if ( famiau_is_account_page() || has_shortcode( $post->post_content, 'famiau_add_listing_form' ) ) {
					wp_enqueue_media();
					wp_enqueue_script( 'jquery-ui-sortable' );
					$is_account_page = 'yes';
				}
			}
			
			if ( is_singular( 'famiau' ) ) {
				wp_enqueue_script( 'jquery-ui-tabs' );
				if ( defined( 'WPCF7_VERSION' ) ) {
					$force_cf7_scripts = trim( $all_options['_famiau_force_cf7_scripts'] ) == 'yes';
					$cf7_handle        = $force_cf7_scripts ? 'famiau-force-contact-form-7' : 'contact-form-7';
					wp_enqueue_script( $cf7_handle, wpcf7_plugin_url( 'includes/js/scripts.js' ), array( 'jquery' ), WPCF7_VERSION, true );
				}
				
				wp_enqueue_script( 'slick', FAMIAU_URI . 'assets/vendors/slick/slick.min.js', array(), null );
				wp_enqueue_script( 'single-famiau', FAMIAU_URI . 'assets/js/single-famiau.js', array(), null );
			}
			
			if ( is_post_type_archive( 'famiau' ) ) {
				$wp_scripts = wp_scripts();
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'jquery-ui-mouse' );
				wp_enqueue_script( 'jquery-ui-slider' );
				wp_enqueue_style( 'jquery-ui', FAMIAU_URI . 'assets/css/jquery-ui-1.10.0.custom.css' );
				wp_enqueue_script( 'jquery-ui-touch-punch', FAMIAU_URI . 'assets/vendors/jquery.ui.touch-punch/jquery.ui.touch-punch.min.js', array(
					'jquery-ui-widget',
					'jquery-ui-mouse'
				), null, false );
			}
			
			if ( famiau_is_account_page() ) {
				wp_enqueue_script( 'famiau-account', FAMIAU_URI . 'assets/js/account.js', array(), null );
			}
			
			$famiau_args = array(
				'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
				'security'                   => wp_create_nonce( 'famiau_nonce' ),
				'is_account_page'            => $is_account_page,
				'filter_groups_collapse_num' => 4,
				'price_format'               => famiau_price_format(),
				'text'                       => array(
					'all'                     => esc_attr__( 'All', 'famiau' ),
					'select_model'            => esc_html__( 'Select model', 'famiau' ),
					'no_model_to_select'      => esc_html__( 'No model to select', 'famiau' ),
					'expand_search'           => esc_html__( 'Expand Search [+]', 'famiau' ),
					'collapse_search'         => esc_html__( 'Collapse Search [-]', 'famiau' ),
					'confirm_del_my_listing'  => esc_html__( 'Are you sure you want to delete this listing? YOU CAN NOT BE UNDONE!', 'famiau' ),
					'confirm_sold_my_listing' => esc_html__( 'Are you sure you want to change this listing status to sold? YOU CAN NOT BE UNDONE!', 'famiau' ),
					'enter_location'          => esc_html__( 'Enter a location', 'famiau' )
				),
				'html'                       => array(
					'clear_popup_select' => '<div class="famiau-filter-bottom-nav"><a class="famiau-clear-popup-select"><span class="famiau-bottom-nav-text">' . esc_html__( 'Clear Selected', 'famiau' ) . '</span></a></div>'
				)
			);
			
			$gmap_api_key = trim( $all_options['_famiau_gmap_api_key'] );
			$load_gmap_js = trim( $all_options['_famiau_load_gmap_js'] ) == 'yes';
			if ( $load_gmap_js && $gmap_api_key != '' ) {
				wp_dequeue_script( 'gmap' );
				wp_enqueue_script( 'gmap', '//maps.googleapis.com/maps/api/js?key=' . $gmap_api_key . '&libraries=places', array(), false );
				wp_enqueue_script( 'markerclusterer', FAMIAU_URI . 'assets/vendors/markerclusterer/src/markerclusterer.js', array(), null );
			}
			
			if ( $all_options['_famiau_enable_lazy_load'] == 'yes' ) {
				wp_enqueue_script( 'lazyload', FAMIAU_URI . 'assets/vendors/lazyload/lazyload.min.js', array(), null );
			}
			
			wp_enqueue_script( 'bootstrap-datepicker', FAMIAU_URI . 'assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js', array(), null );
			wp_enqueue_script( 'famiau-frontend', FAMIAU_URI . 'assets/js/frontend.js', array(), null );
			wp_localize_script( 'famiau-frontend', 'famiau', $famiau_args );
			
			wp_enqueue_style( 'famiau-frontend', FAMIAU_URI . 'assets/css/frontend.css' );
		}
	}
	
	new famiauMenuScriptsStyles();
}