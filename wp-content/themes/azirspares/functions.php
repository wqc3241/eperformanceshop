<?php
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}
if ( ! class_exists( 'Azirspares_Functions' ) ) {
	class Azirspares_Functions {
		/**
		 * @var Azirspares_Functions The one true Azirspares_Functions
		 * @since 1.0
		 */
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Azirspares_Functions ) ) {
				self::$instance = new Azirspares_Functions;
			}
			add_action( 'after_setup_theme', array( self::$instance, 'azirspares_setup' ) );
			add_action( 'widgets_init', array( self::$instance, 'azirspares_widgets_init' ) );
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'admin_enqueue_scripts' ), 99 );
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'azirspares_enqueue_scripts' ), 99 );
			add_filter( 'get_default_comment_status', array(
				self::$instance,
				'azirspares_open_default_comments_for_page'
			), 10, 3 );
			add_filter( 'comment_form_fields', array(
				self::$instance,
				'azirspares_move_comment_field_to_bottom'
			), 10, 3 );
			add_action( 'upload_mimes', array( self::$instance, 'azirspares_add_file_types_to_uploads' ) );
			self::azirspares_includes();
			
			return self::$instance;
		}
		
		public function azirspares_setup() {
			load_theme_textdomain( 'azirspares', get_template_directory() . '/languages' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'custom-background' );
			add_theme_support( 'customize-selective-refresh-widgets' );
			/*This theme uses wp_nav_menu() in two locations.*/
			register_nav_menus( array(
				                    'primary'          => esc_html__( 'Primary Menu', 'azirspares' ),
				                    'vertical_menu'    => esc_html__( 'Vertical Menu', 'azirspares' ),
				                    'top_left_menu'    => esc_html__( 'Top Left Menu', 'azirspares' ),
				                    'top_right_menu'   => esc_html__( 'Top Right Menu', 'azirspares' ),
				                    'burger_icon_menu' => esc_html__( 'Burger Icon Menu', 'azirspares' ),
				                    'burger_list_menu' => esc_html__( 'Burger List Menu', 'azirspares' ),
				                    'listing'          => esc_html__( 'Listing Menu', 'azirspares' ),
			                    )
			);
			add_theme_support( 'html5', array(
				                          'search-form',
				                          'comment-form',
				                          'comment-list',
				                          'gallery',
				                          'caption',
			                          )
			);
			add_theme_support( 'post-formats', array(
				                                 'image',
				                                 'video',
				                                 'quote',
				                                 'link',
				                                 'gallery',
				                                 'audio',
			                                 )
			);
			/*Support WooCommerce*/
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'wc-product-gallery-zoom' );
			
			self::support_gutenberg();
		}
		
		public function support_gutenberg() {
			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );
			
			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );
			
			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			
			// Enqueue editor styles.
			add_editor_style( 'style-editor.css' );
			
			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __( 'Small', 'azirspares' ),
						'shortName' => __( 'S', 'azirspares' ),
						'size'      => 13,
						'slug'      => 'small',
					),
					array(
						'name'      => __( 'Normal', 'azirspares' ),
						'shortName' => __( 'M', 'azirspares' ),
						'size'      => 14,
						'slug'      => 'normal',
					),
					array(
						'name'      => __( 'Large', 'azirspares' ),
						'shortName' => __( 'L', 'azirspares' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => __( 'Huge', 'azirspares' ),
						'shortName' => __( 'XL', 'azirspares' ),
						'size'      => 48,
						'slug'      => 'huge',
					),
				)
			);
			
			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
		}
		
		public function azirspares_move_comment_field_to_bottom( $fields ) {
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;
			
			return $fields;
		}
		
		/**
		 * Register widget area.
		 *
		 * @since azirspares 1.0
		 *
		 * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		function azirspares_widgets_init() {
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Area', 'azirspares' ),
				                  'id'            => 'widget-area',
				                  'description'   => esc_html__( 'Add widgets here to appear in your blog sidebar.', 'azirspares' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Shop', 'azirspares' ),
				                  'id'            => 'widget-shop',
				                  'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'azirspares' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Product', 'azirspares' ),
				                  'id'            => 'widget-product',
				                  'description'   => esc_html__( 'Add widgets here to appear in your single product sidebar.', 'azirspares' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Category', 'azirspares' ),
				                  'id'            => 'widget-category',
				                  'description'   => esc_html__( 'Add widgets here to appear in your category sidebar.', 'azirspares' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
		}
		
		/**
		 * Register custom fonts.
		 */
		function azirspares_fonts_url() {
			/**
			 * Translators: If there are characters in your language that are not
			 * supported by Montserrat, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$azirspares_enable_typography = $this->azirspares_get_option( 'azirspares_enable_typography' );
			$azirspares_typography_group  = $this->azirspares_get_option( 'typography_group' );
			$settings                     = get_option( 'wpb_js_google_fonts_subsets' );
			$font_families                = array();
			if ( $azirspares_enable_typography == 1 && ! empty( $azirspares_typography_group ) ) {
				foreach ( $azirspares_typography_group as $item ) {
					$font_families[] = str_replace( ' ', '+', $item['azirspares_typography_font_family']['family'] );
				}
			}
			$font_families[] = 'Rubik:300,300i,400,400i,500,500i,700,700i,900,900i';
			$font_families[] = 'Roboto:300,300i,400,400i,500,500i,700,700i';
			$query_args      = array(
				'family' => urlencode( implode( '|', $font_families ) ),
			);
			if ( ! empty( $settings ) ) {
				$query_args['subset'] = implode( ',', $settings );
			}
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			
			return esc_url_raw( $fonts_url );
		}
		
		function admin_enqueue_scripts() {
			wp_enqueue_style( 'azirspares-fonts', self::azirspares_fonts_url(), array(), null );
		}
		
		/**
		 * Enqueue scripts and styles.
		 *
		 * @since azirspares 1.0
		 */
		function azirspares_enqueue_scripts() {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_dequeue_style( 'yith-quick-view' );
			
			// Add custom fonts, used in the main stylesheet.
			wp_enqueue_style( 'azirspares-fonts', self::azirspares_fonts_url(), array(), null );
			/* Theme stylesheet. */
			wp_enqueue_style( 'animate-css' );
			wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/fonts/flaticon/flaticon.css' ), array(), '1.0' );
			wp_enqueue_style( 'pe-icon-7-stroke', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.css' ), array(), '1.0' );
			wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'growl', get_theme_file_uri( '/assets/css/jquery.growl.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.css' ), array(), '1.0' );
			wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/assets/css/jquery.scrollbar.css' ), array(), '1.0' );
			wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'azirspares-style', get_theme_file_uri( '/assets/css/style.css' ), array(), '1.0', 'all' );
			wp_enqueue_style( 'azirspares-main-style', get_stylesheet_uri() );
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			/* SCRIPTS */
			$azirspares_gmap_api_key = $this->azirspares_get_option( 'gmap_api_key' );
			if ( $azirspares_gmap_api_key ) {
				$azirspares_gmap_api_key = '//maps.googleapis.com/maps/api/js?key=' . esc_attr( $azirspares_gmap_api_key );
			} else {
				$azirspares_gmap_api_key = '//maps.google.com/maps/api/js?sensor=true';
			}
			if ( ! is_admin() ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
			}
			wp_enqueue_script( 'gmap', esc_url( $azirspares_gmap_api_key ), array(), false );
			wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/libs/chosen.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/libs/bootstrap.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'threesixty', get_theme_file_uri( '/assets/js/libs/threesixty.min.js' ), array(), '1.0.7', true );
			wp_enqueue_script( 'growl', get_theme_file_uri( '/assets/js/libs/jquery.growl.min.js' ), array(), '1.0.0', true );
			wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/libs/magnific-popup.min.js' ), array(), '1.1.0', true );
			wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/libs/slick.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'scrollbar', get_theme_file_uri( '/assets/js/libs/jquery.scrollbar.min.js' ), array(), '1.0.0', true );
			/* http://hilios.github.io/jQuery.countdown/documentation.html */
			wp_enqueue_script( 'countdown', get_theme_file_uri( '/assets/js/libs/countdown.min.js' ), array(), '1.0.0', true );
			/* http://jquery.eisbehr.de/lazy */
			wp_enqueue_script( 'lazy-load', get_theme_file_uri( '/assets/js/libs/lazyload.min.js' ), array(), '1.7.9', true );
			wp_enqueue_script( 'wow', get_theme_file_uri( '/assets/js/libs/wow.min.js' ), array(), false, true );
			wp_enqueue_script( 'azirspares-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0', true );
			wp_localize_script( 'azirspares-script', 'azirspares_ajax_frontend', array(
				                                       'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
				                                       'security'                        => wp_create_nonce( 'azirspares_ajax_frontend' ),
				                                       'added_to_cart_notification_text' => apply_filters( 'azirspares_added_to_cart_notification_text', esc_html__( 'has been added to cart!', 'azirspares' ) ),
				                                       'view_cart_notification_text'     => apply_filters( 'azirspares_view_cart_notification_text', esc_html__( 'View Cart', 'azirspares' ) ),
				                                       'added_to_cart_text'              => apply_filters( 'azirspares_adding_to_cart_text', esc_html__( 'Product has been added to cart!', 'azirspares' ) ),
				                                       'wc_cart_url'                     => ( function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '' ),
				                                       'added_to_wishlist_text'          => get_option( 'yith_wcwl_product_added_text', esc_html__( 'Product has been added to wishlist!', 'azirspares' ) ),
				                                       'wishlist_url'                    => ( function_exists( 'YITH_WCWL' ) ? esc_url( YITH_WCWL()->get_wishlist_url() ) : '' ),
				                                       'browse_wishlist_text'            => get_option( 'yith_wcwl_browse_wishlist_text', esc_html__( 'Browse Wishlist', 'azirspares' ) ),
				                                       'growl_notice_text'               => esc_html__( 'Notice!', 'azirspares' ),
				                                       'removed_cart_text'               => esc_html__( 'Product Removed', 'azirspares' ),
				                                       'wp_nonce_url'                    => ( function_exists( 'wc_get_cart_url' ) ? wp_nonce_url( wc_get_cart_url() ) : '' ),
			                                       )
			);
			$azirspares_enable_popup        = $this->azirspares_get_option( 'azirspares_enable_popup' );
			$azirspares_enable_popup_mobile = $this->azirspares_get_option( 'azirspares_enable_popup_mobile' );
			$azirspares_popup_delay_time    = $this->azirspares_get_option( 'azirspares_popup_delay_time' );
			$atts                           = array(
				'owl_vertical'            => true,
				'owl_responsive_vertical' => 1199,
				'owl_loop'                => false,
				'owl_slide_margin'        => '12',
				'owl_focus_select'        => true,
				'owl_ts_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_ts_items', 4 ),
				'owl_xs_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_xs_items', 4 ),
				'owl_sm_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_sm_items', 4 ),
				'owl_md_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_md_items', 4 ),
				'owl_lg_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_lg_items', 4 ),
				'owl_ls_items'            => $this->azirspares_get_option( 'azirspares_product_thumbnail_ls_items', 4 ),
			);
			$atts                           = apply_filters( 'azirspares_thumb_product_single_slide', $atts );
			$owl_settings                   = explode( ' ', apply_filters( 'azirspares_carousel_data_attributes', 'owl_', $atts ) );
			wp_localize_script( 'azirspares-script', 'azirspares_global_frontend',
			                    array(
				                    'azirspares_enable_popup'        => $azirspares_enable_popup,
				                    'azirspares_popup_delay_time'    => $azirspares_popup_delay_time,
				                    'azirspares_enable_popup_mobile' => $azirspares_enable_popup_mobile,
				                    'data_slick'                     => urldecode( $owl_settings[3] ),
				                    'data_responsive'                => urldecode( $owl_settings[6] ),
				                    'countdown_day'                  => esc_html__( 'Days', 'azirspares' ),
				                    'countdown_hrs'                  => esc_html__( 'Hours', 'azirspares' ),
				                    'countdown_mins'                 => esc_html__( 'Mins', 'azirspares' ),
				                    'countdown_secs'                 => esc_html__( 'Secs', 'azirspares' ),
			                    )
			);
		}
		
		public static function azirspares_get_id() {
			$id_page = get_the_ID();
			if ( class_exists( 'WooCommerce' ) && is_woocommerce() && ! is_product() ) {
				$id_page = get_option( 'woocommerce_shop_page_id' );
				if ( ! $id_page ) {
					$id_page = get_the_ID();
				}
			}
			
			return $id_page;
		}
		
		public static function azirspares_get_option( $option_name, $default = '' ) {
			$get_value = isset( $_GET[ $option_name ] ) ? $_GET[ $option_name ] : '';
			$cs_option = null;
			if ( defined( 'CS_VERSION' ) ) {
				$cs_option = get_option( CS_OPTION );
			}
			if ( isset( $_GET[ $option_name ] ) ) {
				$cs_option = $get_value;
				$default   = $get_value;
			}
			$options = apply_filters( 'cs_get_option', $cs_option, $option_name, $default );
			if ( ! empty( $option_name ) && ! empty( $options[ $option_name ] ) ) {
				$option = $options[ $option_name ];
				if ( is_array( $option ) && isset( $option['multilang'] ) && $option['multilang'] == true ) {
					if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
						if ( isset( $option[ ICL_LANGUAGE_CODE ] ) ) {
							return $option[ ICL_LANGUAGE_CODE ];
						}
					}
				}
				
				return $option;
			} else {
				return ( ! empty( $default ) ) ? $default : null;
			}
		}
		
		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status       Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type    Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 *
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function azirspares_open_default_comments_for_page( $status, $post_type, $comment_type ) {
			if ( 'page' == $post_type ) {
				return 'open';
			}
			
			return $status;
		}
		
		function azirspares_add_file_types_to_uploads( $file_types ) {
			$new_filetypes        = array();
			$new_filetypes['svg'] = 'image/svg+xml';
			$file_types           = array_merge( $file_types, $new_filetypes );
			
			return $file_types;
		}
		
		public static function azirspares_includes() {
			include_once get_parent_theme_file_path( '/framework/framework.php' );
			defined( 'CS_ACTIVE_FRAMEWORK' ) or define( 'CS_ACTIVE_FRAMEWORK', true );
			defined( 'CS_ACTIVE_METABOX' ) or define( 'CS_ACTIVE_METABOX', true );
			defined( 'CS_ACTIVE_TAXONOMY' ) or define( 'CS_ACTIVE_TAXONOMY', false );
			defined( 'CS_ACTIVE_SHORTCODE' ) or define( 'CS_ACTIVE_SHORTCODE', false );
			defined( 'CS_ACTIVE_CUSTOMIZE' ) or define( 'CS_ACTIVE_CUSTOMIZE', false );
		}
	}
}
if ( ! function_exists( 'Azirspares_Functions' ) ) {
	function Azirspares_Functions() {
		return Azirspares_Functions::instance();
	}
	
	Azirspares_Functions();
}

/* Emorphis:Needhi Start Here */
// Removes Order Notes Title - Additional Information & Notes Field
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );
// Remove Order Notes Field
add_filter( 'woocommerce_checkout_fields' , 'remove_order_notes' );
function remove_order_notes( $fields ) {
     unset($fields['order']['order_comments']);
     return $fields;
}
/* after an order has been processed, we will use the  'woocommerce_thankyou' hook, to add our function, to send the data */
//woocommerce_checkout_order_processed 
//add_action('woocommerce_thankyou', 'wdm_send_order_to_ext'); 
//add_action('woocommerce_checkout_order_processed', 'wdm_send_order_to_ext',10,1); 
add_action( 'woocommerce_payment_complete', 'wdm_send_order_to_ext');
function wdm_send_order_to_ext( $order_id){
    $order = new WC_Order( $order_id ); 
	//if( 'pending' === $order->get_status() ) {
    $email = $order->billing_email;
    $phone = $order->billing_phone;
    $shipping_type = $order->get_shipping_method();
    $shipping_cost = $order->get_total_shipping();
	$name = !empty($order->shipping_first_name || $order->shipping_last_name)?$order->shipping_first_name." ".$order->shipping_last_name:$order->billing_first_name." ".$order->billing_last_name;
	$city = !empty($order->shipping_city)?$order->shipping_city:$order->billing_city;
	$zip = !empty($order->shipping_postcode)?$order->shipping_postcode:$order->billing_postcode;
	$address_1 = !empty($order->shipping_address_1)?$order->shipping_address_1:$order->billing_address_1;
	$address_2 = !empty($order->shipping_address_2)?$order->shipping_address_2:$order->billing_address_2;
	$state = !empty($order->shipping_state)?$order->shipping_state:$order->billing_state;
	$country = !empty($order->shipping_country)?$order->shipping_country:$order->billing_country;
	$company = !empty($order->shipping_company)?$order->shipping_company:$order->billing_company;
    // set the address fields
    $user_id = $order->user_id;
    // get product details
    $items = $order->get_items();
    $items_array = array();
	$locations = array("location"=>"default","combine_in_out_stock" => true);
	$prop_arr = array();
	$epa_arr = array();
    foreach( $items as $key => $item){
        $item_id = $item['product_id'];
        $product = new WC_Product($item_id);
		$prop_arr[] = $product->get_attribute('prop_65');
		$epa_arr[] = $product->get_attribute('epa');
			$items_array[] = array(
					"item_identifier" => $product->get_attribute('item_id'),//$product->get_sku(),
					"item_identifier_type" =>  "item_id",
					"quantity"=> $item['qty']
			);		
    }
	$locations['items'] = $items_array;
    // to test out the API, set $api_mode as ‘testing’
		$api_mode = 'production'; //production
		if($api_mode == 'testing'){
			$endpoint = "https://apitest.turn14.com/v1"; 
		}else{
			$endpoint = "https://api.turn14.com/v1"; 
		}		
		$recipient = array(
				  "company"=> $company,
				  "name"=> $name,
				  "address"=>$address_1,
				  "address_2" => $address_2,
				  "city" => $city,
				  "state"=> $state,
				  "country"=> $country,
				  "zip" =>$zip,
				  "email_address" => $email,
				  "phone_number" => $phone,
				  "is_shop_address"=> false
			);
			$prop = false;
			$epa = false;
			if(in_array("Y",$prop_arr) || in_array("Unknown",$prop_arr)){
				$prop = true;
			}
			if(in_array("N/A",$epa_arr) || in_array("Unknown",$epa_arr)){
				$epa = true;
			}
			
		$turn14_quote_data = array("environment"=>$api_mode,"po_number"=>$order_id, "locations"=>[$locations],"recipient"=>$recipient);
		$data = json_encode(array("data"=>$turn14_quote_data));
		$curl = curl_init();
		$auth_data = array(
			'client_id' 		=> '3e7cd86b1c1af44f6a1f10af6672407373d507d3',
			'client_secret' 	=> '21bd5028e305405df8319e56e265df2ab0cc4d68',
			'grant_type' 		=> 'client_credentials'
		);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
		curl_setopt($curl, CURLOPT_URL, "$endpoint/token");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$result = curl_exec($curl);
		curl_close($curl);
		if(!$result){
			//wc_add_notice( __( "Something Went Wrong." ), 'error' );
			$order->update_status( 'cancelled' );
			throw new Exception("Something Went Wrong.");
		}
		$token = json_decode($result)->access_token;
		if(!empty($token)){
			$ch = curl_init();
			$headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");
			curl_setopt($ch, CURLOPT_URL,"$endpoint/quote");
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec ($ch);   
			$err = curl_error($ch);
			curl_close ($ch);    
			$order_res = json_decode($response,true);
			if ($err) {
				$order->update_status( 'cancelled' );
				throw new Exception("E-Performance Shop - ".$err);
				//wc_add_notice( __( $err ), 'error' );				
			}elseif(isset($order_res['errors']) && isset($order_res['errors'][0]['detail'])){
					//wc_add_notice( __( $order_res['errors'][0]['detail'] ), 'error' );
					$order->update_status( 'cancelled' );
					throw new Exception("E-Performance Shop - ".$order_res['errors'][0]['detail']);
					
			} else {
						if(isset($order_res['data']) && isset($order_res['data']['id']) && isset($order_res['data']['attributes'])){
							$attr = $order_res['data']['attributes'];
							$quote_id = $order_res['data']['id'];
							//place order from quote
							$ship = array();
							foreach($attr['shipment'] as $shipment){
								$shipping = $shipment['shipping'];
							   // foreach($shipment['shipping'] as $shipping){
								    $ship[] = array("shipping_id"=> $shipping[0]['shipping_quote_id'],"saturday_delivery"=> $shipping[0]['saturday_delivery'], "signature_required"=> $shipping[0]['signature_required']);
							   // }
							}
							$order_from_quote = array("environment"=>$api_mode,"po_number"=>$order_id,"quote_id"=>$quote_id,"acknowledge_prop_65"=> $prop,"acknowledge_epa"=> $epa,"phone_number"=>$phone,"shipping"=>$ship);
							$q_data = json_encode(array("data"=>$order_from_quote));
							$ch = curl_init();
							$headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");
							curl_setopt($ch, CURLOPT_URL,"$endpoint/order/from_quote");
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $q_data);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$res = curl_exec ($ch);   
							$err = curl_error($ch);
							curl_close ($ch);  
							$quote_order_res = json_decode($res,true);
							if ($err) {
								//wc_add_notice( __( $err ), 'error' );
								$order->update_status( 'cancelled' );
								throw new Exception("E-Performance Shop - ".$err);
							}elseif(isset($quote_order_res['errors']) && isset($quote_order_res['errors'][0]['detail'])){
									//wc_add_notice( __( $quote_order_res['errors'][0]['detail'] ), 'error' );
									$order->update_status( 'cancelled' );
									throw new Exception("E-Performance Shop - ".$quote_order_res['errors'][0]['detail']);
							} else {
									if(isset($quote_order_res['data']) && isset($quote_order_res['data']['id'])){
										add_post_meta($order_id, "Turn14_Order_Id", $quote_order_res['data']['id']);
									}
									if(isset($quote_order_res['data']) && isset($quote_order_res['data']['attributes']['total'])){
										add_post_meta($order_id, "Turn14_total_amount", $quote_order_res['data']['attributes']['total']);
										//$total = get_post_meta( $order_id, 'Turn14_total_amount', true ); 
										//echo $total;
									}
								
							}
							
						}
			}
		}
	//}
 }
 //View order page
 //add_action( 'woocommerce_thankyou', 'custom_view_order_page', 20 );
 add_action( 'woocommerce_view_order', 'custom_view_order_page', 20 );
 
function custom_view_order_page( $order_id ){
$order = wc_get_order( $order_id );
$order_status  = $order->get_status(); // Get the order status 
if ( $order->has_status('processing') ) {	
?>
     <h2>Tracking Details</h2>
    <table class="woocommerce-table shop_table tracking_info">
        <tbody>
			<?php
	$auth_data = array(
			'client_id' 		=> '3e7cd86b1c1af44f6a1f10af6672407373d507d3',
			'client_secret' 	=> '21bd5028e305405df8319e56e265df2ab0cc4d68',
			'grant_type' 		=> 'client_credentials'
		);
		//Get token by client_credentials
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
		curl_setopt($curl, CURLOPT_URL, 'https://api.turn14.com/v1/token');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$result = curl_exec($curl);
		curl_close($curl);
		$token = json_decode($result)->access_token;
		if(!empty($token)){
			$headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://api.turn14.com/v1/orders/po/$order_id");
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_HTTPGET, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($curl);
			curl_close($curl);
			$order_data = json_decode($data,true);
			if(isset($order_data['data']) && !empty($order_data['data'])){
				$attr = $order_data['data'][0]['attributes'];
				$tracking = $attr['tracking'];
				
				foreach($tracking as $track){
					if(isset($track['tracking_number']) && !empty($track['tracking_number'])){
						$tracking_no = $track['tracking_number'];
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, "https://api.turn14.com/v1/tracking/package_details?tracking_number=$tracking_no");
						curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
						curl_setopt($curl, CURLOPT_TIMEOUT, 30);
						curl_setopt($curl, CURLOPT_HTTPGET, 1);
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						$tdata = curl_exec($curl);
						curl_close($curl);
						$t_data = json_decode($tdata,true);
						$t_num = $t_data['data'][0]['attributes']['tracking_number'];
						if(isset($t_data['data']) && !empty($t_data['data']) && isset($t_data['data'][0]['attributes']['ship_date']) && !empty($t_data['data'][0]['attributes']['ship_date'])){?>
					 
					<tr>
					<th>Ship Date</th>
					<td><?php echo $t_data['data'][0]['attributes']['ship_date'];?></td>
				</tr>
						<?php }
						?>
						  <tr>
                <th>Shipping Carrier</th>
                <td><?php echo $t_data['data'][0]['attributes']['carrier_name'];?> - <?php echo $t_data['data'][0]['attributes']['service'];?></td>
            </tr>
						 <tr>
					<th>Tracking Number</th>
					<td><a href="https://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_US&InquiryNumber1=<?php echo $t_num;?>&requester=ST/trackdetails" target="_blank"><?php echo $t_num;?></a></td>
				</tr>
							<?php
					}
				}
			}
		}
?>
       
        </tbody>
    </table>
<?php }
}
  /* Custom Shipping */
 add_filter( 'woocommerce_package_rates', 'custom_flat_rate_cost_calculation', 10, 2 );
function custom_flat_rate_cost_calculation( $rates, $package )
{
	 $min = 0;
	 $max = 0;
	 $avg = 0;
	 foreach ($package['contents'] as $item_id => $values) {
		 $_product = $values['data'];
		 $min = $min + $_product->get_attribute('pa_shipping_min') * $values['quantity'];
		 $max = $max + $_product->get_attribute('pa_shipping_max') * $values['quantity'];
		 $avg = $avg + $_product->get_attribute('pa_shipping_avg') * $values['quantity'];
	 }
	
    // Iterating through each shipping rate
    foreach($rates as $rate_key => $rate_values){
        $method_id = $rate_values->method_id;
        $rate_id = $rate_values->id;
		// Get the original rate cost
		$orig_cost = $rates[$rate_id]->cost;
		// Calculate the new rate cost
		if($orig_cost == 9){
			$rates[$rate_id]->cost = $min;
		}else if($orig_cost == 10){
			$rates[$rate_id]->cost = $avg;
		}else if($orig_cost == 11){
			$rates[$rate_id]->cost = $max;
		}
    }
    return $rates;
}
add_action( 'woocommerce_after_shipping_rate', 'action_after_shipping_rate', 20, 2 );
function action_after_shipping_rate ( $method, $index ) {
    // Targeting checkout page only:
    if( is_cart() ) return; // Exit on cart page
	if( 'flat_rate:4' === $method->id ) {
		echo __("<p style='color: red;font-size: 12px;'>Based on shipping delivery will affect.</p>");
	}
   
}
add_action( 'woocommerce_single_product_summary', 'display_some_product_attributes', 25 );
function display_some_product_attributes(){
	global $product;
	$productAttribute = $product->get_attribute( 'pa_part-number' );
   echo "<span style='font-size: 16px;'>Part#: <span class='part-number'>".$productAttribute."</span></span>";
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'remove_featured_image', 10, 2);
function remove_featured_image($html, $attachment_id ) {
    global $post, $product;
    $featured_image = get_post_thumbnail_id( $post->ID );
   if( !empty( $product->get_gallery_image_ids() ) ) {
	   if ( $attachment_id == $featured_image )
			$html = '';
   }
		
	
    return $html;
}
add_filter( 'woocommerce_get_availability', 'custom_override_get_availability', 10, 2);

// The hook in function $availability is passed via the filter!
    function custom_override_get_availability( $availability, $_product ) {
    if ( $_product->is_in_stock() ){
		 $stock_qty = $_product->get_stock_quantity();
		 $availability['availability'] = sprintf( __('%s In Stock', 'woocommerce'), $stock_qty);//__("In Stock", 'woocommerce');
		 /*if($stock_qty == 0){
			 $_product->set_stock_status( 'outofstock' );
		 }*/
		 
	} 
    return $availability;
    }
function custom_woocommerce_shortcode_products_query( $args ) {
   if ( 'yes' == get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
     $args['meta_query'][] = array(
       'key' => '_stock_status',
       'value' => 'instock',
       'compare' => 'IN'
     );
   }
   return $args;
}

add_filter( 'woocommerce_shortcode_products_query', 'custom_woocommerce_shortcode_products_query' );

/*add_action( 'woocommerce_before_shop_loop_item_title', 'display_sold_out_loop_woocommerce' );
 
function display_sold_out_loop_woocommerce() {
    global $product;
    if ($product->is_in_stock() ) {
		 $stock_qty = $product->get_stock_quantity();
		 if($stock_qty == 0){
			 $product->set_stock_status( 'outofstock' );
			// echo '<span class="out-of-stock">out of stock</span>';
		 }
        
    }
} */
//remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
/* Emorphis:Needhi End Here */