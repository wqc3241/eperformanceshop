<?php
/**
 * Plugin Name: Azirspares Toolkit
 * Plugin URI: https://themeforest.net/user/fami_themes
 * Description: The Azirspares Toolkit For WordPress Theme WooCommerce Shop.
 * Author: Fami Themes
 * Author URI: https://themeforest.net/user/fami_themes
 * Version: 1.3.0
 * Text Domain: azirspares-toolkit
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;
if ( !class_exists( 'Azirspares_Toolkit' ) ) {
	class  Azirspares_Toolkit
	{
		/**
		 * @var Azirspares_Toolkit The one true Azirspares_Toolkit
		 * @since 1.0
		 */
		private static $instance;

		public static function instance()
		{
			if ( !isset( self::$instance ) && !( self::$instance instanceof Azirspares_Toolkit ) ) {
				self::$instance = new Azirspares_Toolkit;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
			}

			return self::$instance;
		}

		public function after_setup_theme()
		{
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/import/import.php';
			/* MAILCHIP */
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/mailchimp/MCAPI.class.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/mailchimp/mailchimp-settings.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/mailchimp/mailchimp.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/live-search/live-search.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/mapper/includes/core.php';
		}

		public function setup_constants()
		{
			// Plugin version.
			if ( !defined( 'AZIRSPARES_TOOLKIT_VERSION' ) ) {
				define( 'AZIRSPARES_TOOLKIT_VERSION', '1.3.0' );
			}
			// Plugin Folder Path.
			if ( !defined( 'AZIRSPARES_TOOLKIT_PATH' ) ) {
				define( 'AZIRSPARES_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
			}
			// Plugin Folder URL.
			if ( !defined( 'AZIRSPARES_TOOLKIT_URL' ) ) {
				define( 'AZIRSPARES_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
			}
		}

		public function includes()
		{
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/admin/welcome.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/post-types.php';
            require_once AZIRSPARES_TOOLKIT_PATH . 'includes/frontend/includes/shortcode.php';
			require_once AZIRSPARES_TOOLKIT_PATH . 'includes/frontend/framework.php';
		}

		public function load_textdomain()
		{
			load_plugin_textdomain( 'azirspares-toolkit', false, AZIRSPARES_TOOLKIT_URL . 'languages' );
		}
	}
}
if ( !function_exists( 'AZIRSPARES_TOOLKIT' ) ) {
	function AZIRSPARES_TOOLKIT()
	{
		return Azirspares_Toolkit::instance();
	}

	AZIRSPARES_TOOLKIT();
	add_action( 'plugins_loaded', 'AZIRSPARES_TOOLKIT', 10 );
}
//check load mobile
if ( !function_exists( 'azirspares_toolkit_is_mobile' ) ) {
	function azirspares_toolkit_is_mobile() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
		
		return apply_filters( 'wp_is_mobile', $is_mobile );
	}
}