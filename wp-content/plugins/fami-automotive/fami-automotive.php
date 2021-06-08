<?php
/**
 * Plugin Name: Fami Automotive Listings
 * Plugin URI: https://themeforest.net/user/fami_themes
 * Description: Automotive listing is a plugin for car dealership, auto dealer, automotive websites and business or any corporate websites in this field.
 * Author: Fami Themes
 * Author URI: https://themeforest.net/user/fami_themes
 * Version: 1.2.3
 * Text Domain: famiau
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function famiau_register_product_type() {
	if ( class_exists( 'WC_Product' ) ) {
		class WC_Product_Famiau extends WC_Product {
			public function __construct( $product = 0 ) {
				$this->supports[]   = 'ajax_add_to_cart';
				$this->product_type = 'famiau';
				parent::__construct( $product );
			}
			
			public function get_type() {
				return 'famiau';
			}
			
			public function add_to_cart_url() {
				$product_id = $this->id;
				if ( $this->is_purchasable() && $this->is_in_stock() && ! $this->has_variables() && ! $this->is_allow_change_qty() ) {
					$url = remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product_id ) );
				} else {
					$url = get_permalink( $product_id );
				}
				
				return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
			}
			
			public function add_to_cart_text() {
				if ( $this->is_purchasable() && $this->is_in_stock() ) {
					if ( ! $this->has_variables() && ! $this->is_allow_change_qty() ) {
						$text = get_option( '_famiau_archive_button_add', esc_html__( 'Add to cart', 'famiau' ) );
					} else {
						$text = get_option( '_famiau_archive_button_select', esc_html__( 'Select options', 'famiau' ) );
					}
				} else {
					$text = get_option( '_famiau_archive_button_read', esc_html__( 'Read more', 'famiau' ) );
				}
				
				return apply_filters( 'famiau_product_add_to_cart_text', $text, $this );
			}
			
			public function single_add_to_cart_text() {
				$text = get_option( '_famiau_single_button_add', esc_html__( 'Add to cart', 'famiau' ) );
				
				return apply_filters( 'famiau_product_single_add_to_cart_text', $text, $this );
			}
			
			public function is_purchasable() {
				$product_id = $this->id;
				
				return apply_filters( 'woocommerce_is_purchasable', $this->exists() && ( 'publish' === $this->get_status() || current_user_can( 'edit_post', $this->get_id() ) ) && ( '' !== $this->get_price() || is_numeric( get_post_meta( $product_id, 'famiau_sale_price_in_percent', true ) ) ), $this );
			}
			
			public function is_virtual() {
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_item_product = wc_get_product( $famiau_item['id'] );
						if ( $famiau_item_product ) {
							if ( $famiau_item_product->is_type( 'variable' ) ) {
								$childs = $famiau_item_product->get_children();
								if ( is_array( $childs ) && count( $childs ) > 0 ) {
									foreach ( $childs as $child ) {
										$product_child = wc_get_product( $child );
										if ( ! $product_child->is_virtual() ) {
											return false;
										}
									}
								}
							} else {
								if ( ! $famiau_item_product->is_virtual() ) {
									return false;
								}
							}
						}
					}
				}
				
				return true;
			}
			
			public function get_stock_quantity( $context = 'view' ) {
				$available_qty = array();
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_product = wc_get_product( $famiau_item['id'] );
						if ( ! $famiau_product || $famiau_product->is_type( 'famiau' ) || ( $famiau_product->get_stock_quantity() === null ) ) {
							continue;
						}
						$available_qty[] = floor( $famiau_product->get_stock_quantity() / $famiau_item['qty'] );
					}
				}
				if ( count( $available_qty ) > 0 ) {
					sort( $available_qty );
					
					return intval( $available_qty[0] );
				}
				
				return parent::get_stock_quantity( $context );
			}
			
			public function get_manage_stock( $context = 'view' ) {
				$manage_stock = false;
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_product = wc_get_product( $famiau_item['id'] );
						if ( ! $famiau_product || $famiau_product->is_type( 'famiau' ) ) {
							continue;
						}
						if ( $famiau_product->get_manage_stock( $context ) == true ) {
							return true;
						}
					}
				}
				
				return $manage_stock;
			}
			
			public function get_backorders( $context = 'view' ) {
				$backorders = 'yes';
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_product = wc_get_product( $famiau_item['id'] );
						if ( ! $famiau_product || $famiau_product->is_type( 'famiau' ) ) {
							continue;
						}
						if ( $famiau_product->get_backorders( $context ) == 'no' ) {
							return 'no';
						} elseif ( $famiau_product->get_backorders( $context ) == 'notify' ) {
							$backorders = 'notify';
						}
					}
				}
				
				return $backorders;
			}
			
			public function get_stock_status( $context = 'view' ) {
				$stock_status = 'instock';
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_product = wc_get_product( $famiau_item['id'] );
						if ( ! $famiau_product || $famiau_product->is_type( 'famiau' ) ) {
							continue;
						}
						if ( ( $famiau_product->get_stock_status( $context ) == 'outofstock' ) || ( ! $famiau_product->has_enough_stock( $famiau_item['qty'] ) ) ) {
							return 'outofstock';
						} elseif ( $famiau_product->get_stock_status( $context ) == 'onbackorder' ) {
							$stock_status = 'onbackorder';
						}
					}
				}
				
				return $stock_status;
			}
			
			public function get_weight( $context = 'view' ) {
				$famiau_weight = 0;
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_product = wc_get_product( $famiau_item['id'] );
						if ( ! $famiau_product || $famiau_product->is_type( 'famiau' ) ) {
							continue;
						}
						if ( $famiau_product->get_weight() ) {
							$famiau_weight += $famiau_product->get_weight() * $famiau_item['qty'];
						}
					}
				}
				if ( $famiau_weight > 0 ) {
					$this->set_weight( $famiau_weight );
					
					return $famiau_weight;
				}
				
				return parent::get_weight( $context );
			}
			
			// extra functions
			
			public function has_variables() {
				if ( $famiau_items = self::get_items() ) {
					foreach ( $famiau_items as $famiau_item ) {
						$famiau_item_product = wc_get_product( $famiau_item['id'] );
						if ( $famiau_item_product && $famiau_item_product->is_type( 'variable' ) ) {
							return true;
						}
					}
				}
				
				return false;
			}
			
			public function is_allow_change_qty() {
				$product_id              = $this->id;
				$famiau_allow_change_qty = get_post_meta( $product_id, 'famiau_allow_change_qty', true ) == 'no' ? false : true;
				
				return $famiau_allow_change_qty;
			}
			
			public function get_items() {
				$product_id = $this->id;
				$famiau_arr = array();
				if ( ( $famiau_ids = get_post_meta( $product_id, 'famiau_ids', true ) ) ) {
					$famiau_ids = explode( ',', $famiau_ids );
					
					if ( is_array( $famiau_ids ) && count( $famiau_ids ) > 0 ) {
						foreach ( $famiau_ids as $famiau_id ) {
							$famiau_arr[] = array(
								'id'  => absint( $famiau_id ),
								'qty' => 1
							);
						}
					}
				}
				if ( count( $famiau_arr ) > 0 ) {
					return $famiau_arr;
				} else {
					return false;
				}
			}
		}
		
	}
}

add_action( 'plugins_loaded', 'famiau_register_product_type' );
if ( ! function_exists( 'famiau_load_vc_mamager' ) ) {
	function famiau_load_vc_mamager() {
		include_once( plugin_dir_path( __FILE__ ) . 'bakery-page-builder/famiauVisualComposer.php' );
	}
}
add_action( 'plugins_loaded', 'famiau_load_vc_mamager' );

function famiau_woocommerce_product_class( $classname, $product_type ) {
	if ( $product_type == 'famiau' ) {
		$classname = 'WC_Product_Famiau';
	}
	
	return $classname;
}

add_filter( 'woocommerce_product_class', 'famiau_woocommerce_product_class', 10, 2 );

if ( ! class_exists( 'famiAutomotive' ) ) {
	
	class  famiAutomotive {
		
		public         $version = '1.0.0';
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiAutomotive ) ) {
				
				self::$instance = new famiAutomotive;
				self::$instance->update_terms();
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				self::$instance->all_options();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
				
				// Add to selector
				add_filter( 'product_type_selector', array( self::$instance, 'product_type_selector' ) );
				
				// Product data tabs
				add_filter( 'woocommerce_product_data_tabs', array( self::$instance, 'product_data_tabs' ) );
				
				// Product data panels
				add_action( 'woocommerce_product_data_panels', array( self::$instance, 'product_data_panels' ) );
				add_action( 'woocommerce_process_product_meta_famiau', array(
					self::$instance,
					'process_product_meta_famiau'
				) );
				
			}
			
			return self::$instance;
		}
		
		public function after_setup_theme() {
			if ( ! class_exists( 'CSFramework' ) ) {
				require_once FAMIAU_PATH . 'includes/classes/cs-framework/cs-framework.php';
			}
		}
		
		public function update_terms() {
			$term = get_term_by( 'name', 'famiau', 'product_type' );
			if ( !$term ) {
				$new_term = wp_insert_term( 'famiau', 'product_type' );
			}
		}
		
		public function setup_constants() {
			$this->define( 'FAMIAU_VERSION', $this->version );
			$this->define( 'FAMIAU_URI', plugin_dir_url( __FILE__ ) );
			$this->define( 'FAMIAU_PATH', plugin_dir_path( __FILE__ ) );
		}
		
		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'famiau_template_path', 'famiau/' );
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
		
		public function includes() {
			require_once FAMIAU_PATH . 'includes/roles.php';
			require_once FAMIAU_PATH . 'includes/database.php';
			require_once FAMIAU_PATH . 'includes/post-types.php';
			require_once FAMIAU_PATH . 'includes/meta-boxes.php';
			require_once FAMIAU_PATH . 'includes/menu-scripts-styles.php';
			require_once FAMIAU_PATH . 'includes/sidebars.php';
			require_once FAMIAU_PATH . 'includes/template-functions.php';
			require_once FAMIAU_PATH . 'includes/currencies.php';
			require_once FAMIAU_PATH . 'includes/helpers.php';
			require_once FAMIAU_PATH . 'includes/filter-functions.php';
			require_once FAMIAU_PATH . 'includes/shortcodes.php';
			include_once FAMIAU_PATH . 'bakery-page-builder/famiauVcShortcode.php';
			include_once FAMIAU_PATH . 'bakery-page-builder/vc-map-shortcodes.php';
			// require_once FAMIAU_PATH . 'includes/load-products-data.php';
			require_once FAMIAU_PATH . 'includes/backend.php';
			require_once FAMIAU_PATH . 'includes/frontend.php';
		}
		
		public function all_options() {
			global $famiau;
			$famiau = famiau_get_all_options();
			
			return $famiau;
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'famiau', false, FAMIAU_URI . 'languages' );
		}
		
		public function product_type_selector( $types ) {
			$types['famiau'] = esc_html__( 'Automotive', 'famiau' );
			
			return $types;
		}
		
		public function product_data_tabs( $tabs ) {
			$tabs['famiau'] = array(
				'label'  => esc_html__( 'Automotive', 'famiau' ),
				'target' => 'famiau_settings',
				'class'  => array( 'show_if_famiau' ),
			);
			
			return $tabs;
		}
		
		public function product_data_panels() {
			global $post;
			$post_id         = $post->ID;
			$all_options     = famiau_get_all_options();
			$make            = get_post_meta( $post_id, '_famiau_make', true );
			$model           = get_post_meta( $post_id, '_famiau_model', true );
			$fuel_type       = get_post_meta( $post_id, '_famiau_fuel_type', true );
			$car_status      = get_post_meta( $post_id, '_famiau_car_status', true );
			$year            = intval( get_post_meta( $post_id, '_famiau_year', true ) );
			$price           = floatval( get_post_meta( $post_id, '_famiau_price', true ) );
			$mileage         = floatval( get_post_meta( $post_id, '_famiau_mileage', true ) );
			$engine          = floatval( get_post_meta( $post_id, '_famiau_engine', true ) );
			$registered_date = get_post_meta( $post_id, '_famiau_registered_date', true );
			$vin             = get_post_meta( $post_id, '_famiau_vin', true );
			$gearbox_type    = get_post_meta( $post_id, '_famiau_gearbox_type', true );
			$car_address     = get_post_meta( $post_id, '_famiau_car_address', true );
			$car_latitude    = get_post_meta( $post_id, '_famiau_car_latitude', true );
			$car_longitude   = get_post_meta( $post_id, '_famiau_car_longitude', true );
			$description     = get_post_meta( $post_id, '_famiau_desc', true );
			
			$default_year = date( 'Y' );
			if ( $year <= 1700 ) {
				$year = 1700;
			}
			if ( $year > $default_year ) {
				$year = $default_year;
			}
			
			$additional_args = famiau_additional_listing_info();
			$car_features    = famiau_car_features();
			$additional_args = array_merge( $additional_args, $car_features );
			$additional_args = array_merge( $additional_args, famiau_seler_notes_suggestions() );
			
			?>
            <div id='famiau_settings' class='panel woocommerce_options_panel famiau-table-wrap'>

                <div class="options_group">
                    <p class="form-field _famiau_make_field">
                        <label for="_famiau_make"><?php esc_html_e( 'Select Make', 'famiau' ); ?></label>
						<?php famiau_makes_select_html( $make, 'famiau-meta-field select short', '_famiau_make', '_famiau_make' ); ?>
                    </p>
                    <p class="form-field _famiau_model_field">
                        <label for="_famiau_model"><?php esc_html_e( 'Select Model', 'famiau' ); ?></label>
						<?php famiau_models_select_html( array(), $model, 'famiau-meta-field select short', '_famiau_model', '_famiau_model' ); ?>
                    </p>
                    <p class="form-field _famiau_fuel_type_field">
                        <label for="_famiau_fuel_type"><?php esc_html_e( 'Select Fuel Type', 'famiau' ); ?></label>
						<?php famiau_fuel_types_select_html( $fuel_type, 'famiau-meta-field select short', '_famiau_fuel_type', '_famiau_fuel_type' ); ?>
                    </p>
                    <p class="form-field _famiau_car_status_field">
                        <label for="_famiau_car_status"><?php esc_html_e( 'Car Status', 'famiau' ); ?></label>
						<?php famiau_car_status_select_html( $car_status, 'famiau-meta-field select short', '_famiau_car_status', '_famiau_car_status' ); ?>
                    </p>
                    <p class="form-field _famiau_year_field">
                        <label for="_famiau_year"><?php esc_html_e( 'Year Of Manufacture', 'famiau' ); ?></label>
                        <input type="number" min="1700" max="<?php echo esc_attr( $default_year ); ?>"
                               class="famiau-meta-field short wc_input_decimal" name="_famiau_year"
                               id="_famiau_year" value="<?php echo esc_attr( $year ); ?>"
                               placeholder="<?php echo esc_attr( $default_year ); ?>">
                    </p>
                    <p class="form-field _famiau_gearbox_type_field">
                        <label for="_famiau_gearbox_type"><?php esc_html_e( 'Transmission', 'famiau' ); ?></label>
						<?php famiau_gearbox_type_select_html( $gearbox_type, 'famiau-meta-field select short', '_famiau_gearbox_type', '_famiau_gearbox_type' ); ?>
                    </p>
					<?php
					if ( ! empty( $additional_args ) ) {
						$select_args = array(
							'no_items_text'   => esc_html__( ' --- No item to select --- ', 'famiau' ),
							'first_item_text' => esc_html__( ' --- Select option --- ', 'famiau' )
						);
						foreach ( $additional_args as $option_key => $option_arg ) {
							$additional_fields_html = '';
							if ( isset( $all_options[ $option_arg['option_key'] ] ) ) {
								$items      = $all_options[ $option_arg['option_key'] ];
								$input_type = isset( $option_arg['input_type'] ) ? $option_arg['input_type'] : 'select';
								if ( ! empty( $items ) ) {
									if ( $input_type == 'select' ) {
										$selected               = get_post_meta( $post_id, $option_arg['meta_key'], true );
										$additional_fields_html .= famiau_select_no_key_html( $items, $selected, 'famiau-meta-field select short', $option_arg['meta_key'], $option_arg['meta_key'], $select_args, false );
									}
									if ( $input_type == 'checkbox' ) {
										$selected_items         = get_post_meta( $post_id, $option_arg['meta_key'], true );
										$additional_fields_html .= '<input data-meta_key="' . $option_arg['meta_key'] . '" type="hidden" class="famiau-meta-field famiau-meta-field-hidden famiau-hidden" name="' . $option_arg['meta_key'] . '" id="' . $option_arg['meta_key'] . '" value="' . esc_attr( $selected_items ) . '" />';
										if ( trim( $selected_items ) != '' ) {
											$selected_items = json_decode( $selected_items );
										} else {
											$selected_items = array();
										}
										
										foreach ( $items as $item ) {
											if ( in_array( $item, $selected_items ) ) {
												$additional_fields_html .= '<label class="famiau-secondary-lb"><input checked data-meta_key="' . $option_arg['meta_key'] . '" type="' . $input_type . '" class="famiau-meta-field-for-hidden" value="' . esc_attr( $item ) . '" /> ' . esc_html( $item ) . '</label>';
											} else {
												$additional_fields_html .= '<label  class="famiau-secondary-lb"><input data-meta_key="' . $option_arg['meta_key'] . '" type="' . $input_type . '" class="famiau-meta-field-for-hidden" value="' . esc_attr( $item ) . '" /> ' . esc_html( $item ) . '</label>';
											}
										}
										
									}
									if ( $input_type == 'suggestion' ) {
										$selected_items         = get_post_meta( $post_id, $option_arg['meta_key'], true );
										$additional_fields_html .= '<textarea class="famiau-meta-field famiau-has-suggestion short" name="' . $option_arg['meta_key'] . '" id="' . $option_arg['meta_key'] . '" placeholder="" rows="5" cols="20">' . esc_attr( $selected_items ) . '</textarea>';
										$additional_fields_html .= '<span class="famiau-suggestion-lbs famiau-lbs-group">';
										foreach ( $items as $item ) {
											$additional_fields_html .= '<label data-suggest_for="' . $option_arg['meta_key'] . '" data-suggest_val="' . esc_attr( $item ) . '" class="famiau-suggestion-lb">' . esc_attr( $item ) . '</label>';
										}
										$additional_fields_html .= '<span>';
									}
								}
							}
							if ( $additional_fields_html != '' ) { ?>
                                <p class="form-field <?php echo $option_arg['meta_key']; ?>_field">
                                    <label for="<?php echo $option_arg['meta_key']; ?>"><?php echo $option_arg['tab_name']; ?></label>
									<?php echo $additional_fields_html; ?>
                                </p>
								<?php
							}
						}
					}
					?>
                    <p class="form-field">
                        <label for="_famiau_price"><?php esc_html_e( 'Price', 'famiau' ); ?></label>
                        <input type="number" min="0" class="famiau-meta-field short"
                               name="_famiau_price" id="_famiau_price" value="<?php echo $price; ?>">
                    </p>
                    <p class="form-field">
                        <label for="_famiau_mileage"><?php esc_html_e( 'Mileage', 'famiau' ); ?></label>
                        <input type="number" min="0" class="famiau-meta-field short"
                               name="_famiau_mileage" id="_famiau_mileage" value="<?php echo $mileage; ?>">
                    </p>
                    <p class="form-field">
                        <label for="_famiau_engine"><?php esc_html_e( 'Engine', 'famiau' ); ?></label>
                        <input type="number" min="0" max="10" step="0.1" class="famiau-meta-field short"
                               name="_famiau_engine" id="_famiau_engine" value="<?php echo $engine; ?>">
                    </p>
                    <p class="form-field">
                        <label for="_famiau_registered_date"><?php esc_html_e( 'Registered', 'famiau' ); ?></label>
                        <input type="date" class="famiau-meta-field short"
                               name="_famiau_registered_date" id="_famiau_registered_date"
                               value="<?php echo esc_attr( $registered_date ); ?>">
                    </p>
                    <p class="form-field">
                        <label for="_famiau_vin"><?php esc_html_e( 'VIN', 'famiau' ); ?></label>
                        <input type="text" class="famiau-meta-field short"
                               name="_famiau_vin" id="_famiau_vin"
                               value="<?php echo esc_attr( $vin ); ?>"
                               placeholder="<?php esc_attr_e( 'Vehicle identification number', 'famiau' ); ?>">
                    </p>

                </div>

                <div class="options_group">
                    <h3 class="famiau-title"><?php esc_html_e( 'Car Location', 'famiau' ); ?></h3>
                    <p class="form-field">
                        <label for="_famiau_car_address"><?php esc_html_e( 'Car Address', 'famiau' ); ?></label>
                        <input type="text" min="0" class="famiau-meta-field short"
                               name="_famiau_car_address"
                               id="_famiau_car_address" value="<?php echo esc_html( $car_address ); ?>"
                               placeholder="<?php esc_attr_e( 'Enter the car address', 'famiau' ); ?>">
                    </p>
                    <p class="form-field">
                        <label for="_famiau_car_latitude"><?php esc_html_e( 'Car Latitude', 'famiau' ); ?></label>
                        <input type="text" min="0" class="famiau-meta-field short"
                               name="_famiau_car_latitude"
                               id="_famiau_car_latitude" value="<?php echo esc_html( $car_latitude ); ?>"
                               placeholder="<?php esc_attr_e( 'Enter latitude', 'famiau' ); ?>">
                        <span class="description"><a href="https://www.latlong.net/"
                                                     target="_blank"><?php esc_attr_e( 'Lat and Long Finder', 'famiau' ); ?></a></span>
                    </p>
                    <p class="form-field">
                        <label for="_famiau_car_longitude"><?php esc_html_e( 'Car Longitude', 'famiau' ); ?></label>
                        <input type="text" min="0" class="famiau-meta-field short"
                               name="_famiau_car_longitude"
                               id="_famiau_car_longitude" value="<?php echo esc_html( $car_longitude ); ?>"
                               placeholder="<?php esc_attr_e( 'Enter longitude', 'famiau' ); ?>">
                        <span class="description"><a href="https://www.latlong.net/"
                                                     target="_blank"><?php esc_attr_e( 'Lat and Long Finder', 'famiau' ); ?></a></span>
                    </p>

                </div>

                <div class="options_group famiau-desc-group">
                    <h3 class="famiau-title"><?php esc_html_e( 'Car Description', 'famiau' ); ?></h3>
                    <p class="form-field _famiau_desc_field">
                        <label for="_famiau_desc"><?php esc_html_e( 'Car Description', 'famiau' ); ?></label>
                        <textarea class="famiau-meta-field short" name="_famiau_desc" id="_famiau_desc" placeholder=""
                                  rows="5"
                                  cols="20"><?php echo esc_html( $description ); ?></textarea>
                    </p>
                    <div class="famiau-custom-desc-group-wrap">
                        <textarea name="_famiau_custom_desc_groups"
                                  class="famiau-hidden famiau-hidden-input famiau-meta-field"
                                  style="display: none; visibility: hidden;"></textarea>
                        <h4 class="famiau-small-title"><?php esc_html_e( 'Custom Description Groups', 'famiau' ); ?></h4>
                        <div class="famiau-custom-desc-group-inner">
                            <div class="famiau-custom-desc-groups-list">
								<?php $this->display_desc_groups_list( $post_id ); ?>
                            </div>
                        </div>
                        <button class="famiau-button-primary famiau-add-desc-group-form-btn"><?php esc_html_e( 'Add New Group', 'famiau' ); ?></button>
                    </div>
                </div>
            </div>
			<?php
		}
		
		public function display_desc_groups_list( $post_id ) {
			$custom_desc_groups = get_post_meta( $post_id, '_famiau_custom_desc_groups', true );
			$html               = '';
			if ( trim( $custom_desc_groups ) != '' ) {
				$custom_desc_groups = json_decode( $custom_desc_groups );
				if ( empty( $custom_desc_groups ) ) {
					return;
				}
				foreach ( $custom_desc_groups as $custom_desc_group ) {
					$group_name      = isset( $custom_desc_group->group_name ) ? $custom_desc_group->group_name : '';
					$group_desc_list = isset( $custom_desc_group->group_desc_list ) ? $custom_desc_group->group_desc_list : array();
					
					$desc_list_html = '';
					if ( ! empty( $group_desc_list ) ) {
						foreach ( $group_desc_list as $desc_detail ) {
							$desc_label      = isset( $desc_detail->desc_label ) ? $desc_detail->desc_label : '';
							$desc_val        = isset( $desc_detail->desc_val ) ? $desc_detail->desc_val : '';
							$is_heading_desc = isset( $desc_detail->is_heading_desc ) ? $desc_detail->is_heading_desc : 'no';
							$desc_list_html  .= famiau_desc_detail_form_template( $desc_label, $desc_val, $is_heading_desc );
						}
					}
					
					$html .= '<div class="famiau-custom-desc-group famiau-box-shadow">
                                    <label class="famiau-lb">' . esc_html__( 'Group Name', 'famiau' ) . '</label>
                                    <input type="text" class="famiau-group-name-input"
                                           placeholder="' . esc_attr__( 'Enter group name', 'famiau' ) . '" value="' . esc_attr( $group_name ) . '"/>
                                    <div class="famiau-custom-desc-details-list">
                                        ' . $desc_list_html . '
                                    </div>
                                    <a href="#"
                                       class="famiau-button famiau-add-custom-desc-detail-btn">' . esc_html__( 'Add New Description', 'famiau' ) . '</a>
                                    <a href="#"
                                       class="famiau-button-warning famiau-remove-desc-group-btn float-right">' . esc_html__( 'Remove This Group', 'famiau' ) . '</a>
                                </div>';
				}
			} else {
				$html = '<span class="description famiau-desc">' . esc_html__( 'No custom description group. Click "Add New Group" or "Copy From Another Group"', 'famiau' ) . '</span>';
			}
			echo $html;
		}
		
		/*
		 * Save all data
		 */
		public function process_product_meta_famiau( $post_id ) {
			
			$all_post_keys = array(
				'_famiau_make',
				'_famiau_model',
				'_famiau_fuel_type',
				'_famiau_car_status',
				'_famiau_year',
				'_famiau_price',
				'_famiau_mileage',
				'_famiau_engine',
				'_famiau_registered_date',
				'_famiau_vin',
				'_famiau_gearbox_type',
				'_famiau_car_address',
				'_famiau_car_latitude',
				'_famiau_car_longitude',
				'_famiau_desc',
				'_famiau_custom_desc_groups'
			);
			
			$additional_args = famiau_additional_listing_info();
			$car_features    = famiau_car_features();
			$additional_args = array_merge( $additional_args, $car_features );
			$additional_args = array_merge( $additional_args, famiau_seler_notes_suggestions() );
			
			if ( ! empty( $additional_args ) ) {
				
				foreach ( $additional_args as $option_key => $option_arg ) {
					$all_post_keys[] = $option_arg['meta_key'];
				}
			}
			
			foreach ( $all_post_keys as $post_key ) {
				if ( isset( $_POST[ $post_key ] ) ) {
					update_post_meta( $post_id, $post_key, $_POST[ $post_key ] );
				}
			}
			
		}
		
	}
}

if ( ! function_exists( 'famiau_init' ) ) {
	function famiau_init() {
		return famiAutomotive::instance();
	}
	
	famiau_init();
	// add_action( 'plugins_loaded', 'famiau_init', 10 );
}
