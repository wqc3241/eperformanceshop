<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// defined('FAMIAU_');

if ( ! class_exists( 'famiAuDatabase' ) ) {
	class  famiAuDatabase {
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiAuDatabase ) ) {
				self::$instance = new famiAuDatabase;
				self::$instance->setup_constants();
				self::$instance->setup_db_tables();
			}
			
			return self::$instance;
		}
		
		private function setup_db_tables() {
			global $wpdb;
			if ( ! function_exists( 'dbDelta' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			}
			$sql = "CREATE TABLE {$wpdb ->prefix}famiau_listings (
				  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  product_id bigint(20) unsigned DEFAULT 0 COMMENT 'listing id (not WooCommerce product)',
				  listing_title varchar(255) DEFAULT '',
				  author_login varchar(60) DEFAULT '' COMMENT 'User login name',
				  _famiau_car_status varchar(30) DEFAULT '' COMMENT 'Car condition',
				  _famiau_make varchar(50) DEFAULT '',
				  _famiau_model varchar(50) DEFAULT '',
				  _famiau_year int(4) unsigned DEFAULT 0,
				  _famiau_body varchar(30) DEFAULT '' COMMENT 'Car body',
				  _famiau_mileage int(8) unsigned DEFAULT 0,
				  _famiau_fuel_type varchar(30) DEFAULT '',
				  _famiau_engine float(3,2) unsigned DEFAULT 0.00,
				  _famiau_gearbox_type varchar(30) DEFAULT '' COMMENT 'Transmission',
				  _famiau_drive varchar(30) DEFAULT '',
				  _famiau_exterior_color varchar(50) DEFAULT '',
				  _famiau_interior_color varchar(50) DEFAULT '',
				  _famiau_registered_date varchar(30) DEFAULT '',
				  _famiau_car_address varchar(120) DEFAULT '',
				  _famiau_vin varchar(30) DEFAULT '' COMMENT 'Vehicle identification number',
				  _famiau_car_number_of_seats tinyint(2) unsigned DEFAULT 0,
				  _famiau_car_number_of_doors tinyint(2) unsigned DEFAULT 0,
				  _famiau_fueling_system varchar(255) DEFAULT '',
				  _famiau_fuel_consumption varchar(80) DEFAULT '',
				  _famiau_car_latitude varchar(30) DEFAULT '',
				  _famiau_car_longitude varchar(30) DEFAULT '',
				  _famiau_car_features_comforts text DEFAULT NULL,
				  _famiau_car_features_entertainments text DEFAULT NULL,
				  _famiau_car_features_safety text DEFAULT NULL,
				  _famiau_car_features_seats text DEFAULT NULL,
				  _famiau_car_features_windows text DEFAULT NULL,
				  _famiau_car_features_others text DEFAULT NULL,
				  attachment_ids text DEFAULT NULL,
				  _famiau_video_url varchar(255) DEFAULT '',
				  _famiau_desc text DEFAULT NULL,
				  _famiau_seller_notes_suggestions text DEFAULT NULL,
				  _famiau_price decimal(20,4) unsigned DEFAULT 0.0000,
				  _famiau_accept_term varchar(10) DEFAULT 'no' COMMENT 'yes, no',
				  listing_status varchar(20) DEFAULT 'waiting' COMMENT 'waiting, approved, deleted',
				  note varchar(255) DEFAULT '',
				  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			dbDelta( $sql );
		}
		
		protected function setup_constants() {
			global $wpdb;
			$this->define( 'FAMIAU_LISTINGS_TABLE', "{$wpdb->prefix}famiau_listings" );
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
	}
	
	famiAuDatabase::instance();
}