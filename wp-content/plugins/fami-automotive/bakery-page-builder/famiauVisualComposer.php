<?php
/**
 * Famiau Visual composer setup
 *
 * @category API
 * @package  famiauVisualComposer
 * @since    1.0.0
 */

if ( ! class_exists( 'VcShortcodeAutoloader' ) ) {
	return;
}

if ( ! class_exists( 'famiauVisualComposer' ) ) {
	class famiauVisualComposer {
		public function __construct() {
			$this->params();
			$this->autocomplete();
			add_action( 'vc_before_init', array( $this, 'famiau_map_shortcode' ) );
			add_filter( 'vc_iconpicker-type-famiaucustomfonts', array( $this, 'iconpicker_type_famiaucustomfonts' ) );
			/* CUSTOM CSS EDITOR */
			add_action( 'vc_after_mapping', array( $this, 'famiau_add_param_all_shortcode' ) );
			add_filter( 'vc_shortcodes_css_class', array( $this, 'famiau_change_element_class_name' ), 10, 3 );
			add_filter( 'famiau_main_custom_css', array( $this, 'famiau_shortcodes_custom_css' ) );
			/* INCLUDE SHORTCODE */
			add_action( 'vc_after_init', array( $this, 'famiau_include_shortcode' ) );
		}
		
		function famiau_shortcodes_custom_css( $css ) {
			$id_page = '';
			// Get all custom inline CSS.
			if ( is_singular() ) {
				$id_page = get_the_ID();
			} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
				$id_page = get_option( 'woocommerce_shop_page_id' );
			}
			if ( $id_page != '' ) {
				$post_custom_css = get_post_meta( $id_page, '_Famiau_Shortcode_custom_css', true );
				$inline_css[]    = $post_custom_css;
				if ( count( $inline_css ) > 0 ) {
					$css .= implode( ' ', $inline_css );
				}
			}
			
			return $css;
		}
		
		public static function get_google_font_data( $tag, $atts, $key = 'google_fonts' ) {
			extract( $atts );
			$google_fonts_field          = WPBMap::getParam( $tag, $key );
			$google_fonts_obj            = new Vc_Google_Fonts();
			$google_fonts_field_settings = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : array();
			$google_fonts_data           = strlen( $atts[ $key ] ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $atts[ $key ] ) : '';
			
			return $google_fonts_data;
		}
		
		function change_font_container_output_data( $data, $fields, $values, $settings ) {
			if ( isset( $fields['text_align'] ) ) {
				$data['text_align'] = '
                <div class="vc_row-fluid vc_column">
                    <div class="wpb_element_label">' . __( 'Text align', 'famiau' ) . '</div>
                    <div class="vc_font_container_form_field-text_align-container">
                        <select class="vc_font_container_form_field-text_align-select">
                            <option value="" class="" ' . ( '' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'none', 'famiau' ) . '</option>
                            <option value="left" class="left" ' . ( 'left' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'left', 'famiau' ) . '</option>
                            <option value="right" class="right" ' . ( 'right' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'right', 'famiau' ) . '</option>
                            <option value="center" class="center" ' . ( 'center' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'center', 'famiau' ) . '</option>
                            <option value="justify" class="justify" ' . ( 'justify' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'justify', 'famiau' ) . '</option>
                        </select>
                    </div>';
				if ( isset( $fields['text_align_description'] ) && strlen( $fields['text_align_description'] ) > 0 ) {
					$data['text_align'] .= '
                    <span class="vc_description clear">' . $fields['text_align_description'] . '</span>
                    ';
				}
				$data['text_align'] .= '</div>';
			}
			
			return $data;
		}
		
		public static function famiau_responsive_vc_data() {
			$options['advanced_screen'] = array();
			$editor_names               = array(
				'desktop' => array(
					'screen' => 999999,
					'name'   => 'Desktop',
				),
				'laptop'  => array(
					'screen' => 1499,
					'name'   => 'Laptop',
				),
				'tablet'  => array(
					'screen' => 1199,
					'name'   => 'Tablet',
				),
				'ipad'    => array(
					'screen' => 991,
					'name'   => 'Ipad',
				),
				'mobile'  => array(
					'screen' => 767,
					'name'   => 'Mobile',
				),
			);
			if ( isset( $options['advanced_screen'] ) && ! empty( $options['advanced_screen'] ) ) {
				foreach ( $options['advanced_screen'] as $data ) {
					$delimiter = '_';
					$slug      = strtolower( trim( preg_replace( '/[\s-]+/', $delimiter, preg_replace( '/[^A-Za-z0-9-]+/', $delimiter, preg_replace( '/[&]/', 'and', preg_replace( '/[\']/', '', $data['name'] ) ) ) ), $delimiter ) );
					/* regen array */
					$editor_names[ $slug ] = array(
						'screen' => $data['screen'],
						'name'   => $data['name'],
					);
				}
			}
			
			return apply_filters( 'famiau_responsive_vc_data', $editor_names );
		}
		
		function famiau_change_element_class_name( $class_string, $tag, $atts ) {
			$editor_names = $this->famiau_responsive_vc_data();
			$atts         = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( $tag, $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$google_fonts_data = array();
			$class_string      = array( $class_string );
			$class_string[]    = isset( $atts['famiau_custom_id'] ) ? $atts['famiau_custom_id'] : '';
			$settings          = get_option( 'wpb_js_google_fonts_subsets' );
			if ( is_array( $settings ) && ! empty( $settings ) ) {
				$subsets = '&subset=' . implode( ',', $settings );
			} else {
				$subsets = '';
			}
			$class_string[] = isset( $atts["css"] ) ? vc_shortcode_custom_css_class( $atts["css"], '' ) : '';
			foreach ( $editor_names as $key => $data ) {
				$param_name     = ( $key == 'desktop' ) ? "css" : "css_{$key}";
				$class_string[] = isset( $atts[ $param_name ] ) ? vc_shortcode_custom_css_class( $atts[ $param_name ], '' ) : '';
				/* GOOGLE FONT */
				if ( isset( $atts["google_fonts_{$key}"] ) ) {
					$google_fonts_data = $this->get_google_font_data( $tag, $atts, "google_fonts_{$key}" );
				}
				if ( ( ! isset( $atts["use_theme_fonts_{$key}"] ) || 'yes' !== $atts["use_theme_fonts_{$key}"] ) && isset( $google_fonts_data['values']['font_family'] ) ) {
					wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
				}
			}
			
			return preg_replace( '/\s+/', ' ', implode( ' ', $class_string ) );
		}
		
		public function famiau_add_param_all_shortcode() {
			global $shortcode_tags;
			$editor_names = $this->famiau_responsive_vc_data();
			WPBMap::addAllMappedShortcodes();
			if ( count( $shortcode_tags ) > 0 ) {
				vc_add_params(
					'vc_tta_section',
					array(
						array(
							'type'       => 'attach_image',
							'param_name' => 'title_image',
							'heading'    => esc_html__( 'Title image', 'famiau' ),
							'group'      => esc_html__( 'Image Group', 'famiau' ),
						),
					)
				);
				vc_add_params(
					'vc_single_image',
					array(
						array(
							'param_name' => 'image_effect',
							'heading'    => esc_html__( 'Effect', 'famiau' ),
							'group'      => esc_html__( 'Image Effect', 'famiau' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'None', 'famiau' )                      => 'none',
								esc_html__( 'Opacity Effect', 'famiau' )            => 'effect opacity-effect',
								esc_html__( 'Scale Effect', 'famiau' )              => 'effect scale-effect',
								esc_html__( 'Normal Effect', 'famiau' )             => 'effect normal-effect',
								esc_html__( 'Normal Effect Dark Color', 'famiau' )  => 'effect normal-effect dark-bg',
								esc_html__( 'Normal Effect Light Color', 'famiau' ) => 'effect normal-effect light-bg',
								esc_html__( 'Bounce In', 'famiau' )                 => 'effect bounce-in',
								esc_html__( 'Plus Zoom', 'famiau' )                 => 'effect plus-zoom',
								esc_html__( 'Border Zoom', 'famiau' )               => 'effect border-zoom',
							),
							'sdt'        => 'none',
						),
					)
				);
				foreach ( $shortcode_tags as $tag => $function ) {
					
					if ( $tag != 'woocommerce_order_tracking' && $tag != 'woocommerce_cart' && $tag != 'woocommerce_checkout' ) {
						if ( strpos( $tag, 'vc_wp' ) === false && $tag != 'vc_btn' && $tag != 'vc_tta_section' && $tag != 'vc_icon' ) {
							vc_remove_param( $tag, 'css' );
							add_filter( 'vc_base_build_shortcodes_custom_css', function() {
								return '';
							} );
							add_filter( 'vc_font_container_output_data', array(
								$this,
								'change_font_container_output_data'
							), 10, 4 );
							$attributes = array(
								array(
									'type'        => 'textfield',
									'heading'     => esc_html__( 'Extra class name', 'famiau' ),
									'param_name'  => 'el_class',
									'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'famiau' ),
								),
								array(
									'param_name' => 'hidden_markup',
									'type'       => 'tabs',
									'group'      => esc_html__( 'Design Options', 'famiau' ),
								),
								array(
									'param_name'       => 'famiau_custom_id',
									'heading'          => esc_html__( 'Hidden ID', 'famiau' ),
									'type'             => 'uniqid',
									'edit_field_class' => 'hidden',
								),
							);
							/* CSS EDITOR */
							$count = 0;
							foreach ( $editor_names as $key => $data ) {
								$name              = ucfirst( $data['name'] );
								$hidden            = $count != 0 ? ' hidden' : '';
								$param_name        = ( $key == 'desktop' ) ? "css" : "css_{$key}";
								$screen            = $data['screen'] < 999999 ? " ( max-width: {$data['screen']}px )" : '';
								$attributes_editor = array(
									/* CSS EDITOR */
									array(
										'type'             => 'css_editor',
										'heading'          => esc_html__( "Screen {$name}{$screen}", 'famiau' ),
										'param_name'       => $param_name,
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
									),
									/* CHECKBOX BACKGROUND */
									array(
										'type'             => 'checkbox',
										'heading'          => esc_html__( 'Disable Background?', 'famiau' ),
										'param_name'       => "disable_bg_{$key}",
										'description'      => esc_html__( 'Disable Background in this screen.', 'famiau' ),
										'value'            => array( esc_html__( 'Yes', 'famiau' ) => 'yes' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
										'group'            => esc_html__( 'Design Options', 'famiau' ),
									),
									/* WIDTH CONTAINER */
									array(
										'type'             => 'number',
										'heading'          => esc_html__( "Width {$name}", 'famiau' ),
										'description'      => esc_html__( 'Custom width contain in this screen.', 'famiau' ),
										'param_name'       => "width_rows_{$key}",
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 vc_col-sm-6 {$key}{$hidden}",
									),
									/* UNIT CSS WIDTH */
									array(
										'type'             => 'dropdown',
										'heading'          => esc_html__( 'Unit', 'famiau' ),
										'param_name'       => "width_unit_{$key}",
										'value'            => array(
											esc_html__( 'Percent (%)', 'famiau' )     => '%',
											esc_html__( 'Pixel (px)', 'famiau' )      => 'px',
											esc_html__( 'Em (em)', 'famiau' )         => 'em',
											esc_html__( 'Max Height (vh)', 'famiau' ) => 'vh',
											esc_html__( 'Max Width (vw)', 'famiau' )  => 'vw',
										),
										'std'              => '%',
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 vc_col-sm-6 {$key}{$hidden}",
									),
									array(
										'type'             => 'number',
										'heading'          => esc_html__( 'Z-index', 'typenal-toolkit' ),
										'param_name'       => "z_index_{$key}",
										'description'      => esc_html__( 'Enter z-index.', 'typenal-toolkit' ),
										'group'            => esc_html__( 'Design Options', 'typenal-toolkit' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
									),
									array(
										'type'             => 'dropdown',
										'heading'          => esc_html__( 'Overflow', 'famiau' ),
										'param_name'       => "overflow_{$key}",
										'value'            => array(
											esc_html__( 'default', 'famiau' ) => '',
											esc_html__( 'visible', 'famiau' ) => 'visible',
											esc_html__( 'hidden', 'famiau' )  => 'hidden',
											esc_html__( 'scroll', 'famiau' )  => 'scroll',
											esc_html__( 'auto', 'famiau' )    => 'auto',
											esc_html__( 'initial', 'famiau' ) => 'initial',
											esc_html__( 'inherit', 'famiau' ) => 'inherit',
										),
										'std'              => '',
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 vc_col-sm-12 {$key}{$hidden}",
									),
									/* TEXT FONT */
									array(
										'type'             => 'textfield',
										'heading'          => esc_html__( 'Letter Spacing', 'famiau' ),
										'param_name'       => "letter_spacing_{$key}",
										'description'      => esc_html__( 'Enter letter spacing.', 'famiau' ),
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
									),
									array(
										'type'             => 'font_container',
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'param_name'       => "responsive_font_{$key}",
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
										'settings'         => array(
											'fields' => array(
												'text_align',
												'font_size',
												'line_height',
												'color',
												'text_align_description'  => esc_html__( 'Select text alignment.', 'famiau' ),
												'font_size_description'   => esc_html__( 'Enter font size.', 'famiau' ),
												'line_height_description' => esc_html__( 'Enter line height.', 'famiau' ),
												'color_description'       => esc_html__( 'Select heading color.', 'famiau' ),
											),
										),
									),
									array(
										'type'             => 'checkbox',
										'heading'          => esc_html__( 'Use theme default font family?', 'famiau' ),
										'param_name'       => "use_theme_fonts_{$key}",
										'value'            => array(
											esc_html__( 'Yes', 'famiau' ) => 'yes',
										),
										'std'              => 'yes',
										'description'      => esc_html__( 'Use font family from the theme.', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
										'group'            => esc_html__( 'Design Options', 'famiau' ),
									),
									array(
										'type'             => 'google_fonts',
										'param_name'       => "google_fonts_{$key}",
										'value'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
										'settings'         => array(
											'fields' => array(
												'font_family_description' => esc_html__( 'Select font family.', 'famiau' ),
												'font_style_description'  => esc_html__( 'Select font styling.', 'famiau' ),
											),
										),
										'dependency'       => array(
											'element'            => "use_theme_fonts_{$key}",
											'value_not_equal_to' => 'yes',
										),
										'group'            => esc_html__( 'Design Options', 'famiau' ),
										'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
									),
								);
								$attributes        = array_merge( $attributes, $attributes_editor );
								$count ++;
							}
						} else {
							$attributes = array(
								array(
									'type'        => 'textfield',
									'heading'     => esc_html__( 'Extra class name', 'famiau' ),
									'param_name'  => 'el_class',
									'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'famiau' ),
								),
								array(
									'param_name'       => 'famiau_custom_id',
									'heading'          => esc_html__( 'Hidden ID', 'famiau' ),
									'type'             => 'uniqid',
									'edit_field_class' => 'hidden',
								),
							);
						}
						
						vc_add_params( $tag, $attributes );
					}
				}
			}
		}
		
		public function iconpicker_type_famiaucustomfonts() {
			$icons['Famiau Fonts'] = array(
				array( 'flaticon-coupon' => 'Flaticon coupon' ),
				array( 'flaticon-delivery-truck' => 'Flaticon delivery truck' ),
				array( 'flaticon-truck' => 'Flaticon truck' ),
				array( 'flaticon-user' => 'Flaticon user' ),
				array( 'flaticon-place' => 'Flaticon place' ),
				array( 'flaticon-contract' => 'Flaticon contract' ),
				array( 'flaticon-telephone' => 'Flaticon telephone' ),
				array( 'flaticon-email' => 'Flaticon email' ),
				array( 'flaticon-box' => 'Flaticon box' ),
				array( 'flaticon-heart-shape-outline' => 'Flaticon heart shape outline' ),
				array( 'flaticon-package' => 'Flaticon package' ),
				array( 'flaticon-email-1' => 'Flaticon email 1' ),
				array( 'flaticon-win' => 'Flaticon win' ),
				array( 'flaticon-magnifying-glass-browser' => 'Flaticon magnifying glass browser' ),
				array( 'flaticon-online-shopping-cart' => 'Flaticon online shopping cart' ),
				array( 'flaticon-refresh-left-arrow' => 'Flaticon refresh left arrow' ),
				array( 'flaticon-localization' => 'Flaticon localization' ),
				array( 'flaticon-message' => 'Flaticon message' ),
				array( 'flaticon-paper-plane' => 'Flaticon paper plane' ),
				array( 'flaticon-blank-squared-bubble' => 'Flaticon blank squared bubble' ),
				array( 'flaticon-view' => 'Flaticon view' ),
				array( 'flaticon-shuffle' => 'Flaticon shuffle' ),
				array( 'flaticon-phone-call' => 'Flaticon phone call' ),
				array( 'flaticon-360-degrees' => 'Flaticon 360 degrees' ),
				array( 'flaticon-shield' => 'Flaticon shield' ),
				array( 'flaticon-recycling' => 'Flaticon recycling' ),
				array( 'flaticon-play-button' => 'Flaticon play button' ),
				array( 'flaticon-brake' => 'Flaticon brake' ),
				array( 'flaticon-motor' => 'Flaticon motor' ),
				array( 'flaticon-oil' => 'Flaticon oil' ),
				array( 'flaticon-car' => 'Flaticon car' ),
				array( 'flaticon-car-1' => 'Flaticon car 1' ),
				array( 'flaticon-exhaust-pipe' => 'Flaticon exhaust pipe' ),
				array( 'flaticon-comments' => 'Flaticon comments' ),
				array( 'flaticon-question' => 'Flaticon question' ),
				array( 'flaticon-diamond' => 'Flaticon diamond' ),
				array( 'flaticon-repair' => 'Flaticon repair' ),
				array( 'flaticon-car-parts' => 'Flaticon car parts' ),
				array( 'flaticon-filter' => 'Flaticon filter' ),
				array( 'flaticon-instagram' => 'Flaticon instagram' ),
				array( 'flaticon-envelope-of-white-paper' => 'Flaticon envelope of white paper' ),
				array( 'flaticon-profile' => 'Flaticon profile' ),
				array( 'flaticon-paper-plane-1' => 'Flaticon paper plane 1' ),
				array( 'flaticon-old-handphone' => 'Flaticon old handphone' ),
				array( 'flaticon-random' => 'Flaticon random' ),
				array( 'flaticon-settings-work-tool' => 'Flaticon settings work tool' ),
				array( 'flaticon-gas-station' => 'Flaticon gas station' ),
				array( 'flaticon-road-perspective' => 'Flaticon road perspective' ),
			);
			
			return $icons;
		}
		
		/**
		 * load param autocomplete render
		 * */
		public function autocomplete() {
			add_filter( 'vc_autocomplete_famiau_listings_famiau_ids_callback', array(
				$this,
				'famiauIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_famiau_listings_famiau_ids_render', array(
				$this,
				'famiauIdAutocompleteRender'
			), 10, 1 );
		}
		
		function params() {
//			vc_add_shortcode_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
//			vc_add_shortcode_param( 'number', array( $this, 'number_field' ) );
//			vc_add_shortcode_param( 'select_preview', array( $this, 'select_preview_field' ) );
//			vc_add_shortcode_param( 'datepicker', array( $this, 'datepicker_field' ) );
//			vc_add_shortcode_param( 'uniqid', array( $this, 'uniqid_field' ) );
//			vc_add_shortcode_param( 'tabs', array( $this, 'tabs_field' ) );
		}
		
		function tabs_field( $settings, $value ) {
			$editor_names = $this->famiau_responsive_vc_data();
			$output       = '<div class="tabs-css">';
			$count        = 0;
			foreach ( $editor_names as $key => $data ) {
				$name   = ucfirst( $data['name'] );
				$active = $count == 0 ? ' active' : '';
				$output .= "<span class='tab_css {$key}{$active}' data-tabs='{$key}'>{$name}</span>";
				$count ++;
			}
			$output .= '</div>';
			
			return $output;
		}
		
		public function uniqid_field( $settings, $value ) {
			if ( ! $value ) {
				$value = 'famiau_css_id_' . uniqid();
			}
			$output = '<input type="text" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';
			
			return $output;
		}
		
		/**
		 * date picker field
		 **/
		function datepicker_field( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( ! $value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$main_class = $param_name . ' ' . $type . ' ' . $class;
			ob_start();
			?>
            <label class="cs-field-date" <?php echo esc_attr( $dependency ); ?>>
                <input name="<?php echo esc_attr( $param_name ); ?>" value="<?php echo esc_attr( $value ); ?>"
                       type="text"
                       class="wpb_vc_param_value textfield <?php echo esc_attr( $main_class ); ?>"
                       style="min-width:100%; margin-right: 10px;"><?php echo esc_html( $suffix ); ?>
                <textarea class="cs-datepicker-options hidden">{"dateFormat":"m\/d\/yy"}</textarea>
            </label>
			<?php
			return $output = ob_get_clean();
		}
		
		public function select_preview_field( $settings, $value ) {
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				ob_start();
				?>
                <div class="container-select_preview">
                    <label for="<?php echo esc_attr( $settings['param_name'] ); ?>">
                        <select id="famiau_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                                name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                                class="famiau_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
							<?php foreach ( $options as $k => $option ): ?>
								<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                                <option data-preview="<?php echo esc_url( $option['preview'] ); ?>"
                                        value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['title'] ) ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>
                    <div class="image-preview">
						<?php if ( isset( $options[ $value ] ) && $options[ $value ] && ( isset( $options[ $value ]['preview'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $value ]['preview'] ); ?>"
                                 alt="<?php echo get_the_title(); ?>">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $default ]['preview'] ); ?>"
                                 alt="<?php echo get_the_title(); ?>">
						<?php endif; ?>
                    </div>
                </div>
				<?php
			}
			
			return ob_get_clean();
		}
		
		/**
		 * taxonomy_field
		 */
		public function taxonomy_field( $settings, $value ) {
			$dependency = '';
			$value_arr  = $value;
			if ( ! is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['options']['hide_empty'] ) && $settings['options']['hide_empty'] == true ) {
				$settings['options']['hide_empty'] = 1;
			} else {
				$settings['options']['hide_empty'] = 0;
			}
			if ( ! empty( $settings['options']['taxonomy'] ) ) {
				$terms_fields = array();
				if ( isset( $settings['options']['placeholder'] ) && $settings['options']['placeholder'] ) {
					$terms_fields[] = "<option value=''>" . $settings['options']['placeholder'] . "</option>";
				}
				$terms = get_terms( $settings['options']['taxonomy'],
				                    array(
					                    'hierarchical' => 1,
					                    'hide_empty'   => $settings['options']['hide_empty'],
				                    )
				);
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( ! empty( $settings['options']['size'] ) ) ? 'size="' . $settings['options']['size'] . '"' : '';
				$multiple = ( ! empty( $settings['options']['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="famiau_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
				            . implode( $terms_fields )
				            . '</select>';
			}
			
			return $output;
		}
		
		/**
		 * Suggester for autocomplete by id/name/title/sku
		 *
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return array - id's from products with title/sku.
		 */
		public function famiauIdAutocompleteSuggester( $query ) {
			$data                            = array();
			$args                            = array( 's' => $query, 'post_type' => 'famiau' );
			$args['vc_search_by_title_only'] = true;
			$args['numberposts']             = - 1;
			if ( strlen( $args['s'] ) == 0 ) {
				unset( $args['s'] );
			}
			add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
			$posts = get_posts( $args );
			if ( is_array( $posts ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$data[] = array(
						'value' => $post->ID,
						'label' => $post->post_title,
						'group' => $post->post_type,
					);
				}
			}
			
			return $data;
		}
		
		/**
		 * Find product by id
		 *
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return bool|array
		 */
		public function famiauIdAutocompleteRender( $query ) {
			$post = get_post( $query['value'] );
			
			return is_null( $post ) ? false : array(
				'label' => $post->post_title,
				'value' => $post->ID,
				'group' => $post->post_type
			);
		}
		
		public function number_field( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( ! $value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;line-height:23px;height:auto;" />' . $suffix;
			
			return $output;
		}
		
		public function famiau_vc_bootstrap( $dependency = null, $value_dependency = null ) {
			$data_value     = array();
			$data_bootstrap = array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Rows space', 'famiau' ),
					'param_name' => 'boostrap_rows_space',
					'value'      => array(
						esc_html__( 'Default', 'famiau' ) => 'rows-space-0',
						esc_html__( '10px', 'famiau' )    => 'rows-space-10',
						esc_html__( '20px', 'famiau' )    => 'rows-space-20',
						esc_html__( '30px', 'famiau' )    => 'rows-space-30',
						esc_html__( '40px', 'famiau' )    => 'rows-space-40',
						esc_html__( '50px', 'famiau' )    => 'rows-space-50',
						esc_html__( '60px', 'famiau' )    => 'rows-space-60',
						esc_html__( '70px', 'famiau' )    => 'rows-space-70',
						esc_html__( '80px', 'famiau' )    => 'rows-space-80',
						esc_html__( '90px', 'famiau' )    => 'rows-space-90',
						esc_html__( '100px', 'famiau' )   => 'rows-space-100',
					),
					'std'        => 'rows-space-0',
					'group'      => esc_html__( 'Boostrap settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Desktop', 'famiau' ),
					'param_name'  => 'boostrap_bg_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Desktop', 'famiau' ),
					'param_name'  => 'boostrap_lg_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on landscape tablet', 'famiau' ),
					'param_name'  => 'boostrap_md_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on portrait tablet', 'famiau' ),
					'param_name'  => 'boostrap_sm_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Mobile', 'famiau' ),
					'param_name'  => 'boostrap_xs_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Mobile', 'famiau' ),
					'param_name'  => 'boostrap_ts_items',
					'value'       => array(
						esc_html__( '1 item', 'famiau' )  => '12',
						esc_html__( '2 items', 'famiau' ) => '6',
						esc_html__( '3 items', 'famiau' ) => '4',
						esc_html__( '4 items', 'famiau' ) => '3',
						esc_html__( '5 items', 'famiau' ) => '15',
						esc_html__( '6 items', 'famiau' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'famiau' ),
					'group'       => esc_html__( 'Boostrap settings', 'famiau' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
			);
			if ( $dependency == null && $value_dependency == null ) {
				foreach ( $data_bootstrap as $value ) {
					unset( $value['dependency'] );
					$data_value[] = $value;
				}
			} else {
				$data_value = $data_bootstrap;
			}
			
			return $data_value;
		}
		
		public function famiau_vc_carousel( $dependency = null, $value_dependency = null ) {
			$data_value    = array();
			$data_carousel = array(
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( '1 Row', 'famiau' )  => '1',
						esc_html__( '2 Rows', 'famiau' ) => '2',
						esc_html__( '3 Rows', 'famiau' ) => '3',
						esc_html__( '4 Rows', 'famiau' ) => '4',
						esc_html__( '5 Rows', 'famiau' ) => '5',
						esc_html__( '6 Rows', 'famiau' ) => '6',
					),
					'std'        => '1',
					'heading'    => esc_html__( 'The number of rows which are shown on block', 'famiau' ),
					'param_name' => 'owl_number_row',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Rows space', 'famiau' ),
					'param_name' => 'owl_rows_space',
					'value'      => array(
						esc_html__( 'Default', 'famiau' ) => 'rows-space-0',
						esc_html__( '10px', 'famiau' )    => 'rows-space-10',
						esc_html__( '20px', 'famiau' )    => 'rows-space-20',
						esc_html__( '30px', 'famiau' )    => 'rows-space-30',
						esc_html__( '40px', 'famiau' )    => 'rows-space-40',
						esc_html__( '50px', 'famiau' )    => 'rows-space-50',
						esc_html__( '60px', 'famiau' )    => 'rows-space-60',
						esc_html__( '70px', 'famiau' )    => 'rows-space-70',
						esc_html__( '80px', 'famiau' )    => 'rows-space-80',
						esc_html__( '90px', 'famiau' )    => 'rows-space-90',
						esc_html__( '100px', 'famiau' )   => 'rows-space-100',
					),
					'std'        => 'rows-space-0',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => 'owl_number_row',
						'value'   => array( '2', '3', '4', '5', '6' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'famiau' ) => 'true',
						esc_html__( 'No', 'famiau' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'Vertical Mode', 'famiau' ),
					'param_name' => 'owl_vertical',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'famiau' ) => 'true',
						esc_html__( 'No', 'famiau' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'verticalSwiping', 'famiau' ),
					'param_name' => 'owl_verticalswiping',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => 'owl_vertical',
						'value'   => array( 'true' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'famiau' ) => 'true',
						esc_html__( 'No', 'famiau' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'AutoPlay', 'famiau' ),
					'param_name' => 'owl_autoplay',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Autoplay Speed', 'famiau' ),
					'param_name'  => 'owl_autoplayspeed',
					'value'       => '1000',
					'suffix'      => esc_html__( 'milliseconds', 'famiau' ),
					'description' => esc_html__( 'Autoplay speed in milliseconds', 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => 'owl_autoplay',
						'value'   => array( 'true' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No', 'famiau' )  => 'false',
						esc_html__( 'Yes', 'famiau' ) => 'true',
					),
					'std'         => 'true',
					'heading'     => esc_html__( 'Navigation', 'famiau' ),
					'param_name'  => 'owl_navigation',
					'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Navigation style', 'famiau' ),
					'param_name' => 'owl_navigation_style',
					'value'      => array(
						esc_html__( 'Top Right', 'famiau' )   => 'nav-top-right',
						esc_html__( 'Bottom Left', 'famiau' ) => 'nav-bottom-left',
						esc_html__( 'Center', 'famiau' )      => 'nav-center',
						esc_html__( 'Bottom', 'famiau' )      => 'nav-bottom',
					),
					'std'        => '',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array( 'element' => 'owl_navigation', 'value' => array( 'true' ) ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Navigation type', 'famiau' ),
					'param_name' => 'owl_navigation_type',
					'value'      => array(
						esc_html__( 'Circle', 'famiau' ) => 'circle',
						esc_html__( 'Square', 'famiau' ) => 'square',
					),
					'std'        => '',
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array( 'element' => 'owl_navigation', 'value' => array( 'true' ) ),
				),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No', 'famiau' )  => 'false',
						esc_html__( 'Yes', 'famiau' ) => 'true',
					),
					'std'         => 'false',
					'heading'     => esc_html__( 'Dots', 'famiau' ),
					'param_name'  => 'owl_dots',
					'description' => esc_html__( "Show dots buttons.", 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Yes', 'famiau' ) => 'true',
						esc_html__( 'No', 'famiau' )  => 'false',
					),
					'std'         => 'false',
					'heading'     => esc_html__( 'Loop', 'famiau' ),
					'param_name'  => 'owl_loop',
					'description' => esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion.', 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Slide Speed', 'famiau' ),
					'param_name'  => 'owl_slidespeed',
					'value'       => '300',
					'suffix'      => esc_html__( 'milliseconds', 'famiau' ),
					'description' => esc_html__( 'Slide speed in milliseconds', 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Margin', 'famiau' ),
					'param_name'  => 'owl_slide_margin',
					'value'       => '30',
					'suffix'      => esc_html__( 'Pixel', 'famiau' ),
					'description' => esc_html__( 'Distance( or space) between 2 item', 'famiau' ),
					'group'       => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency'  => array(
						'element' => 'owl_vertical',
						'value'   => array( 'false' ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 1500px )', 'famiau' ),
					'param_name' => 'owl_ls_items',
					'value'      => '4',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 1200px and < 1500px )', 'famiau' ),
					'param_name' => 'owl_lg_items',
					'value'      => '4',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 992px < 1200px )', 'famiau' ),
					'param_name' => 'owl_md_items',
					'value'      => '3',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on tablet (Screen resolution of device >=768px and < 992px )', 'famiau' ),
					'param_name' => 'owl_sm_items',
					'value'      => '2',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on mobile landscape(Screen resolution of device >=480px and < 768px)', 'famiau' ),
					'param_name' => 'owl_xs_items',
					'value'      => '2',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on mobile (Screen resolution of device < 480px)', 'famiau' ),
					'param_name' => 'owl_ts_items',
					'value'      => '1',
					'suffix'     => esc_html__( 'item(s)', 'famiau' ),
					'group'      => esc_html__( 'Carousel settings', 'famiau' ),
					'dependency' => array(
						'element' => $dependency,
						'value'   => array( $value_dependency ),
					),
				),
			);
			if ( $dependency == null && $value_dependency == null ) {
				$match = array(
					'owl_navigation_style',
					'owl_navigation_type',
					'owl_autoplayspeed',
					'owl_rows_space',
					'owl_verticalswiping',
				);
				foreach ( $data_carousel as $value ) {
					if ( ! in_array( $value['param_name'], $match ) ) {
						unset( $value['dependency'] );
					}
					$data_value[] = $value;
				}
			} else {
				$data_value = $data_carousel;
			}
			
			return $data_value;
		}
		
		public function famiau_param_visual_composer() {
			return famiau_vc_shortcode_params();
		}
		
		public function famiau_map_shortcode() {
			$param_maps = $this->famiau_param_visual_composer();
			if ( $param_maps ) {
				foreach ( $param_maps as $value ) {
					if ( $value['base'] == 'famiau_listings_carousel' ) {
						$value['params'] = array_merge(
							$value['params'],
							$this->famiau_vc_carousel()
						);
					}
					if ( function_exists( 'vc_map' ) ) {
						vc_map( $value );
					}
				}
			}
		}
		
		private function famiau_get_templates( $template_name ) {
			$active_plugin_wc = is_plugin_active( 'woocommerce/woocommerce.php' );
			$path_templates   = apply_filters( 'famiau_templates_shortcode', 'vc_templates' );
			if ( $template_name == 'famiau_products' && ! $active_plugin_wc ) {
				return;
			}
			$directory_shortcode = '';
			if ( is_file( get_template_directory() . '/' . $path_templates . '/' . $template_name . '.php' ) ) {
				$directory_shortcode = get_template_directory() . '/' . $path_templates;
			}
			if ( $directory_shortcode != '' ) {
				include_once $directory_shortcode . '/' . $template_name . '.php';
			}
		}
		
		function famiau_include_shortcode() {
			$param_maps = $this->famiau_param_visual_composer();
			foreach ( $param_maps as $shortcode ) {
				$this->famiau_get_templates( $shortcode['base'] );
			}
		}
	}
	
	new famiauVisualComposer();
}
VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Famiau_Tabs extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Famiau_Accordion extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Famiau_Slide extends WPBakeryShortCodesContainer {
}