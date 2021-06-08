<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'famiauMetaboxes' ) ) {
	class famiauMetaboxes {
		
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiauMetaboxes ) ) {
				
				self::$instance = new famiauMetaboxes;
				
				add_filter( 'cs_metabox_options', array( self::$instance, 'metabox_options' ) );
				
				// Custom Meta box
				add_action( 'add_meta_boxes', array( self::$instance, 'famiau_adding_custom_meta_boxes' ), 1, 2 );
				add_action( 'save_post', array( self::$instance, 'famiau_save_custom_meta_boxes' ) );
			}
			
			return self::$instance;
		}
		
		public function famiau_adding_custom_meta_boxes( $post_type, $post ) {
			add_meta_box(
				'famiau-others-meta-box',
				esc_html__( 'Others', 'famiau' ),
				array( self::$instance, 'famiau_custom_meta_boxes_callback' ),
				'famiau',
				'normal',
				'default'
			);
		}
		
		public function famiau_custom_meta_boxes_callback() {
			// $post is already set, and contains an object: the WordPress post
			global $post;
			$selected = get_post_meta( $post->ID, '_famiau_is_featured', true );
			
			// We'll use this nonce field later on when saving.
			wp_nonce_field( 'famiau_meta_box_nonce', 'famiau_meta_box_nonce' );
			?>
            <label for="_famiau_is_featured"><?php esc_html_e( 'Is Featured', 'famiau' ); ?></label>
            <select id="_famiau_is_featured" name="_famiau_is_featured" class="famiau-select">
                <option <?php selected( true, $selected == 'no' ); ?>
                        value="no"><?php esc_html_e( 'No', 'famiau' ); ?></option>
                <option <?php selected( true, $selected == 'yes' ); ?>
                        value="yes"><?php esc_html_e( 'Yes', 'famiau' ); ?></option>
            </select>
			<?php
		}
		
		public function famiau_save_custom_meta_boxes( $post_id ) {
			// Bail if we're doing an auto save
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			// if our nonce isn't there, or we can't verify it, bail
			if ( ! isset( $_POST['famiau_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['famiau_meta_box_nonce'], 'famiau_meta_box_nonce' ) ) {
				return;
			}
			
			// if our current user can't edit this post, bail
			if ( ! current_user_can( 'edit_post' ) ) {
				return;
			}
			
			if ( isset( $_POST['_famiau_is_featured'] ) ) {
				update_post_meta( $post_id, '_famiau_is_featured', esc_attr( $_POST['_famiau_is_featured'] ) );
			}
			
		}
		
		public function get_makes_args() {
			global $famiau;
			$makes       = array( '' => esc_html__( 'Select Make', 'famiau' ) );
			$makes_attrs = array();
			$makes_args  = array(
				'makes'       => $makes, // Select args
				'makes_attrs' => ''
			);
			
			$all_makes = $famiau['all_makes'];
			if ( empty( $all_makes ) ) {
				return $makes_args;
			}
			foreach ( $all_makes as $make ) {
				$makes[ $make['make'] ]       = $make['make'];
				$makes_attrs[ $make['make'] ] = $make['models'];
			}
			
			$makes_attrs               = esc_attr( wp_json_encode( $makes_attrs ) );
			$makes_args['makes_attrs'] = $makes_attrs;
			$makes_args['makes']       = $makes;
			
			return $makes_args;
		}
		
		public function get_car_info_select( $key = '', $first_option_val = '', $first_option_text = '' ) {
			global $famiau;
			if ( trim( $first_option_text ) == '' ) {
				$first_option_text = esc_html__( 'Select Option', 'famiau' );
			}
			$args                      = array();
			$args[ $first_option_val ] = $first_option_text;
			$key                       = trim( $key );
			if ( $key == '' || ! isset( $famiau[ $key ] ) ) {
				return $args;
			}
			
			if ( empty( $famiau[ $key ] ) ) {
				return $args;
			}
			
			foreach ( $famiau[ $key ] as $item ) {
				$args[ $item ] = $item;
			}
			
			return $args;
		}
		
		public function get_car_info_checkbox( $key = '' ) {
			global $famiau;
			$args = array();
			$key  = trim( $key );
			if ( $key == '' || ! isset( $famiau[ $key ] ) ) {
				return $args;
			}
			
			if ( empty( $famiau[ $key ] ) ) {
				return $args;
			}
			
			foreach ( $famiau[ $key ] as $item ) {
				$args[ $item ] = $item;
			}
			
			return $args;
		}
		
		function metabox_options( $options ) {
			global $famiau;
			$makes_args = $this->get_makes_args();
			// -----------------------------------------
			// Famiau Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_famiau_metabox_options',
				'title'     => esc_html__( 'Automotive Information', 'famiau' ),
				'post_type' => 'famiau',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'famiau_car_details',
						'title'  => esc_html__( 'Car Details', 'famiau' ),
						'fields' => array(
							array(
								'id'         => '_famiau_make',
								'title'      => esc_html__( 'Make', 'famiau' ),
								'type'       => 'select',
								'options'    => $makes_args['makes'],
								'class'      => 'famiau-select', // chosen
								'attributes' => array(
									'data-makes' => $makes_args['makes_attrs'],
								),
							),
							array(
								'id'         => '_famiau_model',
								'title'      => esc_html__( 'Model', 'famiau' ),
								'type'       => 'text',
								'attributes' => array(
									'type' => 'hidden',
								),
								'class'      => 'famiau-metabox-hidden-field', // chosen
							),
							array(
								'id'      => '_famiau_model_select',
								'title'   => esc_html__( 'Model', 'famiau' ),
								'type'    => 'select',
								'options' => array(
									'' => esc_html__( 'Select Model', 'famiau' )
								),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_fuel_type',
								'title'   => esc_html__( 'Fuel Type', 'famiau' ),
								'type'    => 'select',
								'options' => $this->get_car_info_select( 'all_fuel_types', '', esc_html__( 'Select Fuel Type', 'famiau' ) ),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_car_status',
								'title'   => esc_html__( 'Car Status', 'famiau' ),
								'type'    => 'select',
								'options' => array(
									''               => esc_html__( 'Select Car Condition', 'famiau' ),
									'new'            => esc_html__( 'New', 'famiau' ),
									'used'           => esc_html__( 'Used', 'famiau' ),
									'certified-used' => esc_html__( 'Certified Used', 'famiau' )
								),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'         => '_famiau_year',
								'type'       => 'number',
								'default'    => '0',
								'title'      => esc_html__( 'Year Of Manufacture', 'famiau' ),
								'attributes' => array(
									'max' => date( 'Y' ),
								),
							),
							array(
								'id'      => '_famiau_gearbox_type',
								'title'   => esc_html__( 'Transmission', 'famiau' ),
								'type'    => 'select',
								'options' => array(
									''               => esc_html__( 'Select Transmission', 'famiau' ),
									'manual'         => esc_html__( 'Manual', 'famiau' ),
									'auto'           => esc_html__( 'Auto', 'famiau' ),
									'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
								),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_body',
								'title'   => esc_html__( 'Body', 'famiau' ),
								'type'    => 'select',
								'options' => $this->get_car_info_select( 'all_car_bodies', '', esc_html__( 'Select Body', 'famiau' ) ),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_drive',
								'title'   => esc_html__( 'Drive', 'famiau' ),
								'type'    => 'select',
								'options' => $this->get_car_info_select( 'all_drives', '', esc_html__( 'Select Drive', 'famiau' ) ),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_exterior_color',
								'title'   => esc_html__( 'Exterior Colors', 'famiau' ),
								'type'    => 'select',
								'options' => $this->get_car_info_select( 'all_exterior_colors', '', esc_html__( 'Select Exterior Color', 'famiau' ) ),
								'class'   => 'famiau-select', // chosen
							),
							array(
								'id'      => '_famiau_interior_color',
								'title'   => esc_html__( 'Interior Colors', 'famiau' ),
								'type'    => 'select',
								'options' => $this->get_car_info_select( 'all_interior_colors', '', esc_html__( 'Select Interior Color', 'famiau' ) ),
								'class'   => 'famiau-select', // chosen
							),
							
							array(
								'id'         => '_famiau_seller_notes_suggestions',
								'type'       => 'textarea',
								'title'      => esc_html__( 'Seller\'s Note', 'automotive' ),
								'class'      => 'famiau-suggestions-field',
								'attributes' => array(
									'data-suggestions' => htmlentities2( json_encode( $famiau['all_seller_notes_suggestions'] ) ),
								),
							),
							array(
								'id'         => '_famiau_mileage',
								'type'       => 'number',
								'default'    => '0',
								'title'      => esc_html__( 'Mileage', 'famiau' ),
								'attributes' => array(
									'min' => 0,
								),
							),
							array(
								'id'         => '_famiau_engine',
								'type'       => 'number',
								'default'    => '0',
								'title'      => esc_html__( 'Engine', 'famiau' ),
								'attributes' => array(
									'min'  => 0,
									'max'  => 10000,
									'step' => 0.1
								),
							),
							array(
								'id'      => '_famiau_car_number_of_seats',
								'title'   => esc_html__( 'Number Of Seats', 'famiau' ),
								'type'    => 'number',
								'default' => '0'
							),
							array(
								'id'      => '_famiau_car_number_of_doors',
								'title'   => esc_html__( 'Number Of Doors', 'famiau' ),
								'type'    => 'number',
								'default' => '0'
							),
							array(
								'id'      => '_famiau_fueling_system',
								'title'   => esc_html__( 'Fueling System', 'famiau' ),
								'type'    => 'text',
								'default' => ''
							),
							array(
								'id'      => '_famiau_fuel_consumption',
								'title'   => esc_html__( 'Fuel Consumption', 'famiau' ),
								'type'    => 'text',
								'default' => ''
							),
							array(
								'id'         => '_famiau_registered_date',
								'type'       => 'date',
								'default'    => '',
								'title'      => esc_html__( 'Registered', 'famiau' ),
								'attributes' => array(
									'type' => 'date',
								),
							),
							array(
								'id'      => '_famiau_vin',
								'type'    => 'text',
								'default' => '',
								'title'   => esc_html__( 'VIN', 'famiau' ),
								'desc'    => esc_html__( 'Vehicle identification number', 'famiau' )
							),
						),
					),
					
					// Car Location
					array(
						'name'   => 'famiau_car_location',
						'title'  => esc_html__( 'Car Location', 'famiau' ),
						'fields' => array(
							array(
								'id'    => '_famiau_car_address',
								'type'  => 'text',
								'title' => esc_html__( 'Car Address', 'famiau' ),
							),
							array(
								'id'    => '_famiau_car_latitude',
								'type'  => 'text',
								'title' => esc_html__( 'Car Latitude', 'famiau' ),
								'desc'  => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.latlong.net/', __( 'Lat and Long Finder', 'famiau' ) )
							),
							array(
								'id'    => '_famiau_car_longitude',
								'type'  => 'text',
								'title' => esc_html__( 'Car Longitude', 'famiau' ),
								'desc'  => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.latlong.net/', __( 'Lat and Long Finder', 'famiau' ) )
							),
						),
					),
					
					// Car Features
					array(
						'name'   => 'famiau_car_features',
						'title'  => esc_html__( 'Car Features', 'famiau' ),
						'fields' => array(
							array(
								'id'      => '_famiau_car_features_comforts',
								'title'   => esc_html__( 'Comfort', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_comforts' ),
							),
							array(
								'id'      => '_famiau_car_features_entertainments',
								'title'   => esc_html__( 'Entertainment', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_entertainments' ),
							),
							array(
								'id'      => '_famiau_car_features_safety',
								'title'   => esc_html__( 'Safety', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_safety' ),
							),
							array(
								'id'      => '_famiau_car_features_seats',
								'title'   => esc_html__( 'Seats', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_seats' ),
							),
							array(
								'id'      => '_famiau_car_features_windows',
								'title'   => esc_html__( 'Windows', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_windows' ),
							),
							array(
								'id'      => '_famiau_car_features_others',
								'title'   => esc_html__( 'Others', 'famiau' ),
								'type'    => 'checkbox',
								'options' => $this->get_car_info_checkbox( 'all_car_features_others' ),
							),
						),
					),
					
					// Car Description
					array(
						'name'   => 'famiau_car_desc',
						'title'  => esc_html__( 'Car Description', 'famiau' ),
						'fields' => array(
							array(
								'id'    => '_famiau_desc',
								'type'  => 'wysiwyg',
								'title' => esc_html__( 'Car Description', 'famiau' ),
							),
						),
					),
					
					// Car Price
					array(
						'name'   => 'famiau_car_video_gallery',
						'title'  => esc_html__( 'Video And Gallery', 'famiau' ),
						'fields' => array(
							array(
								'id'      => '_famiau_video_url',
								'type'    => 'text',
								'default' => '',
								'title'   => esc_html__( 'Video URL', 'famiau' ),
								'class'   => 'famiau-video-field',
								'desc'    => esc_html__( 'Youtube or Vimeo video URL', 'famiau' )
							),
							array(
								'id'      => '_famiau_gallery',
								'type'    => 'gallery',
								'default' => '0',
								'title'   => esc_html__( 'Gallery', 'famiau' ),
							),
						),
					),
					
					// Car Price
					array(
						'name'   => 'famiau_car_price',
						'title'  => esc_html__( 'Car Price', 'famiau' ),
						'fields' => array(
							array(
								'id'         => '_famiau_price',
								'type'       => 'number',
								'default'    => '0',
								'title'      => esc_html__( 'Price', 'famiau' ),
								'attributes' => array(
									'min' => 0,
								),
							),
						),
					),
				
				),
			);
			
			return $options;
		}
	}
	
	// new famiauMetaboxes();
	famiauMetaboxes::instance();
}