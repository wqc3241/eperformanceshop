<?php
/**
 * Azirspares Visual composer setup
 *
 * @author   KHANH
 * @category API
 * @package  Azirspares_Visual_composer
 * @since    1.0.0
 */
if ( !defined( 'AZIRSPARES_FRAMEWORK_URI' ) ) {
	define( 'AZIRSPARES_FRAMEWORK_URI', '/' );
}
if ( !class_exists( 'Azirspares_Visual_composer' ) ) {
	class Azirspares_Visual_composer
	{
		public function __construct()
		{
			$this->params();
			$this->autocomplete();
			add_action( 'vc_before_init', array( $this, 'azirspares_map_shortcode' ) );
			add_filter( 'vc_iconpicker-type-azirsparescustomfonts', array( $this, 'iconpicker_type_azirsparescustomfonts' ) );
			/* CUSTOM CSS EDITOR */
			add_action( 'vc_after_mapping', array( $this, 'azirspares_add_param_all_shortcode' ) );
			add_filter( 'vc_shortcodes_css_class', array( $this, 'azirspares_change_element_class_name' ), 10, 3 );
			add_filter( 'azirspares_main_custom_css', array( $this, 'azirspares_shortcodes_custom_css' ) );
			/* INCLUDE SHORTCODE */
			add_action( 'vc_after_init', array( $this, 'azirspares_include_shortcode' ) );
		}

        function azirspares_shortcodes_custom_css($css)
        {
            $id_page = '';
            // Get all custom inline CSS.
            if (is_singular()) {
                $id_page = get_the_ID();
            } elseif (class_exists('WooCommerce') && is_shop()) {
                $id_page = get_option('woocommerce_shop_page_id');
            }
            if ($id_page != '') {
                $post_custom_css = get_post_meta($id_page, '_Azirspares_Shortcode_custom_css', true);
                $inline_css[] = $post_custom_css;
                if (count($inline_css) > 0) {
                    $css .= implode(' ', $inline_css);
                }
            }

            return $css;
        }
        public static function get_google_font_data($tag, $atts, $key = 'google_fonts')
        {
            extract($atts);
            $google_fonts_field = WPBMap::getParam($tag, $key);
            $google_fonts_obj = new Vc_Google_Fonts();
            $google_fonts_field_settings = isset($google_fonts_field['settings'], $google_fonts_field['settings']['fields']) ? $google_fonts_field['settings']['fields'] : array();
            $google_fonts_data = strlen($atts[$key]) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes($google_fonts_field_settings, $atts[$key]) : '';

            return $google_fonts_data;
        }
		function change_font_container_output_data( $data, $fields, $values, $settings )
		{
			if ( isset( $fields['text_align'] ) ) {
				$data['text_align'] = '
                <div class="vc_row-fluid vc_column">
                    <div class="wpb_element_label">' . __( 'Text align', 'azirspares-toolkit' ) . '</div>
                    <div class="vc_font_container_form_field-text_align-container">
                        <select class="vc_font_container_form_field-text_align-select">
                            <option value="" class="" ' . ( '' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'none', 'azirspares-toolkit' ) . '</option>
                            <option value="left" class="left" ' . ( 'left' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'left', 'azirspares-toolkit' ) . '</option>
                            <option value="right" class="right" ' . ( 'right' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'right', 'azirspares-toolkit' ) . '</option>
                            <option value="center" class="center" ' . ( 'center' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'center', 'azirspares-toolkit' ) . '</option>
                            <option value="justify" class="justify" ' . ( 'justify' === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . __( 'justify', 'azirspares-toolkit' ) . '</option>
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
        public static function azirspares_responsive_vc_data()
        {
            $options['advanced_screen'] = array();
            $editor_names = array(
                'desktop' => array(
                    'screen' => 999999,
                    'name' => 'Desktop',
                ),
                'laptop' => array(
                    'screen' => 1499,
                    'name' => 'Laptop',
                ),
                'tablet' => array(
                    'screen' => 1199,
                    'name' => 'Tablet',
                ),
                'ipad' => array(
                    'screen' => 991,
                    'name' => 'Ipad',
                ),
                'mobile' => array(
                    'screen' => 767,
                    'name' => 'Mobile',
                ),
            );
            if (isset($options['advanced_screen']) && !empty($options['advanced_screen']))
                foreach ($options['advanced_screen'] as $data) {
                    $delimiter = '_';
                    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', $data['name'])))), $delimiter));
                    /* regen array */
                    $editor_names[$slug] = array(
                        'screen' => $data['screen'],
                        'name' => $data['name'],
                    );
                }

            return apply_filters('azirspares_responsive_vc_data', $editor_names);
        }
		function azirspares_change_element_class_name( $class_string, $tag, $atts )
		{
            $editor_names = $this->azirspares_responsive_vc_data();
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes($tag, $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $google_fonts_data = array();
            $class_string = array($class_string);
            $class_string[] = isset($atts['azirspares_custom_id']) ? $atts['azirspares_custom_id'] : '';
            $settings = get_option('wpb_js_google_fonts_subsets');
            if (is_array($settings) && !empty($settings)) {
                $subsets = '&subset=' . implode(',', $settings);
            } else {
                $subsets = '';
            }
            $class_string[] = isset($atts["css"]) ? vc_shortcode_custom_css_class($atts["css"], '') : '';
            foreach ($editor_names as $key => $data) {
                $param_name = ($key == 'desktop') ? "css" : "css_{$key}";
                $class_string[] = isset($atts[$param_name]) ? vc_shortcode_custom_css_class($atts[$param_name], '') : '';
                /* GOOGLE FONT */
                if (isset($atts["google_fonts_{$key}"]))
                    $google_fonts_data = $this->get_google_font_data($tag, $atts, "google_fonts_{$key}");
                if ((!isset($atts["use_theme_fonts_{$key}"]) || 'yes' !== $atts["use_theme_fonts_{$key}"]) && isset($google_fonts_data['values']['font_family'])) {
                    wp_enqueue_style('vc_google_fonts_' . vc_build_safe_css_class($google_fonts_data['values']['font_family']), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets);
                }
            }

            return preg_replace('/\s+/', ' ', implode(' ', $class_string));
		}

		public function azirspares_add_param_all_shortcode()
		{
			global $shortcode_tags;
            $editor_names = $this->azirspares_responsive_vc_data();
			WPBMap::addAllMappedShortcodes();
			if ( count( $shortcode_tags ) > 0 ) {
				vc_add_params(
					'vc_tta_section',
					array(
						array(
							'type'       => 'attach_image',
							'param_name' => 'title_image',
							'heading'    => esc_html__( 'Title image', 'azirspares-toolkit' ),
							'group'      => esc_html__( 'Image Group', 'azirspares-toolkit' ),
						),
					)
				);
				vc_add_params(
					'vc_single_image',
					array(
						array(
							'param_name' => 'image_effect',
							'heading'    => esc_html__( 'Effect', 'azirspares-toolkit' ),
							'group'      => esc_html__( 'Image Effect', 'azirspares-toolkit' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'None', 'azirspares-toolkit' )                      => 'none',
								esc_html__( 'Opacity Effect', 'azirspares-toolkit' )            => 'effect opacity-effect',
								esc_html__( 'Scale Effect', 'azirspares-toolkit' )              => 'effect scale-effect',
								esc_html__( 'Normal Effect', 'azirspares-toolkit' )             => 'effect normal-effect',
								esc_html__( 'Normal Effect Dark Color', 'azirspares-toolkit' )  => 'effect normal-effect dark-bg',
								esc_html__( 'Normal Effect Light Color', 'azirspares-toolkit' ) => 'effect normal-effect light-bg',
								esc_html__( 'Bounce In', 'azirspares-toolkit' )                 => 'effect bounce-in',
								esc_html__( 'Plus Zoom', 'azirspares-toolkit' )                 => 'effect plus-zoom',
								esc_html__( 'Border Zoom', 'azirspares-toolkit' )               => 'effect border-zoom',
							),
							'sdt'        => 'none',
						),
					)
				);
                foreach ($shortcode_tags as $tag => $function) {
	                if ( $tag != 'woocommerce_order_tracking' && $tag !='woocommerce_cart' && $tag != 'woocommerce_checkout') {
		                if ( strpos( $tag, 'vc_wp' ) === false && $tag != 'vc_btn' && $tag != 'vc_tta_section' && $tag != 'vc_icon' ) {
			                vc_remove_param( $tag, 'css' );
			                add_filter( 'vc_base_build_shortcodes_custom_css', function () {
				                return '';
			                } );
			                add_filter( 'vc_font_container_output_data', array(
				                $this,
				                'change_font_container_output_data'
			                ), 10, 4 );
			                $attributes = array(
				                array(
					                'type'        => 'textfield',
					                'heading'     => esc_html__( 'Extra class name', 'azirspares-toolkit' ),
					                'param_name'  => 'el_class',
					                'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'azirspares-toolkit' ),
				                ),
				                array(
					                'param_name' => 'hidden_markup',
					                'type'       => 'tabs',
					                'group'      => esc_html__( 'Design Options', 'azirspares-toolkit' ),
				                ),
				                array(
					                'param_name'       => 'azirspares_custom_id',
					                'heading'          => esc_html__( 'Hidden ID', 'azirspares-toolkit' ),
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
						                'heading'          => esc_html__( "Screen {$name}{$screen}", 'azirspares-toolkit' ),
						                'param_name'       => $param_name,
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
						                'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
					                ),
					                /* CHECKBOX BACKGROUND */
					                array(
						                'type'             => 'checkbox',
						                'heading'          => esc_html__( 'Disable Background?', 'azirspares-toolkit' ),
						                'param_name'       => "disable_bg_{$key}",
						                'description'      => esc_html__( 'Disable Background in this screen.', 'azirspares-toolkit' ),
						                'value'            => array( esc_html__( 'Yes', 'azirspares-toolkit' ) => 'yes' ),
						                'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
					                ),
					                /* WIDTH CONTAINER */
					                array(
						                'type'             => 'number',
						                'heading'          => esc_html__( "Width {$name}", 'azirspares-toolkit' ),
						                'description'      => esc_html__( 'Custom width contain in this screen.', 'azirspares-toolkit' ),
						                'param_name'       => "width_rows_{$key}",
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
						                'edit_field_class' => "vc_col-xs-12 vc_col-sm-6 {$key}{$hidden}",
					                ),
					                /* UNIT CSS WIDTH */
					                array(
						                'type'             => 'dropdown',
						                'heading'          => esc_html__( 'Unit', 'azirspares-toolkit' ),
						                'param_name'       => "width_unit_{$key}",
						                'value'            => array(
							                esc_html__( 'Percent (%)', 'azirspares-toolkit' )     => '%',
							                esc_html__( 'Pixel (px)', 'azirspares-toolkit' )      => 'px',
							                esc_html__( 'Em (em)', 'azirspares-toolkit' )         => 'em',
							                esc_html__( 'Max Height (vh)', 'azirspares-toolkit' ) => 'vh',
							                esc_html__( 'Max Width (vw)', 'azirspares-toolkit' )  => 'vw',
						                ),
						                'std'              => '%',
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
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
						                'heading'          => esc_html__( 'Overflow', 'azirspares-toolkit' ),
						                'param_name'       => "overflow_{$key}",
						                'value'            => array(
							                esc_html__( 'default', 'azirspares-toolkit' ) => '',
							                esc_html__( 'visible', 'azirspares-toolkit' ) => 'visible',
							                esc_html__( 'hidden', 'azirspares-toolkit' )  => 'hidden',
							                esc_html__( 'scroll', 'azirspares-toolkit' )  => 'scroll',
							                esc_html__( 'auto', 'azirspares-toolkit' )    => 'auto',
							                esc_html__( 'initial', 'azirspares-toolkit' ) => 'initial',
							                esc_html__( 'inherit', 'azirspares-toolkit' ) => 'inherit',
						                ),
						                'std'              => '',
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
						                'edit_field_class' => "vc_col-xs-12 vc_col-sm-12 {$key}{$hidden}",
					                ),
					                /* TEXT FONT */
					                array(
						                'type'             => 'textfield',
						                'heading'          => esc_html__( 'Letter Spacing', 'azirspares-toolkit' ),
						                'param_name'       => "letter_spacing_{$key}",
						                'description'      => esc_html__( 'Enter letter spacing.', 'azirspares-toolkit' ),
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
						                'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
					                ),
					                array(
						                'type'             => 'font_container',
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
						                'param_name'       => "responsive_font_{$key}",
						                'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
						                'settings'         => array(
							                'fields' => array(
								                'text_align',
								                'font_size',
								                'line_height',
								                'color',
								                'text_align_description'  => esc_html__( 'Select text alignment.', 'azirspares-toolkit' ),
								                'font_size_description'   => esc_html__( 'Enter font size.', 'azirspares-toolkit' ),
								                'line_height_description' => esc_html__( 'Enter line height.', 'azirspares-toolkit' ),
								                'color_description'       => esc_html__( 'Select heading color.', 'azirspares-toolkit' ),
							                ),
						                ),
					                ),
					                array(
						                'type'             => 'checkbox',
						                'heading'          => esc_html__( 'Use theme default font family?', 'azirspares-toolkit' ),
						                'param_name'       => "use_theme_fonts_{$key}",
						                'value'            => array(
							                esc_html__( 'Yes', 'azirspares-toolkit' ) => 'yes',
						                ),
						                'std'              => 'yes',
						                'description'      => esc_html__( 'Use font family from the theme.', 'azirspares-toolkit' ),
						                'edit_field_class' => "vc_col-xs-12 {$key}{$hidden}",
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
					                ),
					                array(
						                'type'             => 'google_fonts',
						                'param_name'       => "google_fonts_{$key}",
						                'value'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
						                'settings'         => array(
							                'fields' => array(
								                'font_family_description' => esc_html__( 'Select font family.', 'azirspares-toolkit' ),
								                'font_style_description'  => esc_html__( 'Select font styling.', 'azirspares-toolkit' ),
							                ),
						                ),
						                'dependency'       => array(
							                'element'            => "use_theme_fonts_{$key}",
							                'value_not_equal_to' => 'yes',
						                ),
						                'group'            => esc_html__( 'Design Options', 'azirspares-toolkit' ),
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
					                'heading'     => esc_html__( 'Extra class name', 'azirspares-toolkit' ),
					                'param_name'  => 'el_class',
					                'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'azirspares-toolkit' ),
				                ),
				                array(
					                'param_name'       => 'azirspares_custom_id',
					                'heading'          => esc_html__( 'Hidden ID', 'azirspares-toolkit' ),
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

		public function iconpicker_type_azirsparescustomfonts()
		{
			$icons['Azirspares Fonts'] = array(
                array('flaticon-coupon'=>'Flaticon coupon'),
                array('flaticon-delivery-truck'=>'Flaticon delivery truck'),
                array('flaticon-truck'=>'Flaticon truck'),
                array('flaticon-user'=>'Flaticon user'),
                array('flaticon-place'=>'Flaticon place'),
                array('flaticon-contract'=>'Flaticon contract'),
                array('flaticon-telephone'=>'Flaticon telephone'),
                array('flaticon-email'=>'Flaticon email'),
                array('flaticon-box'=>'Flaticon box'),
                array('flaticon-heart-shape-outline'=>'Flaticon heart shape outline'),
                array('flaticon-package'=>'Flaticon package'),
                array('flaticon-email-1'=>'Flaticon email 1'),
                array('flaticon-win'=>'Flaticon win'),
                array('flaticon-magnifying-glass-browser'=>'Flaticon magnifying glass browser'),
                array('flaticon-online-shopping-cart'=>'Flaticon online shopping cart'),
                array('flaticon-refresh-left-arrow'=>'Flaticon refresh left arrow'),
                array('flaticon-localization'=>'Flaticon localization'),
                array('flaticon-message'=>'Flaticon message'),
                array('flaticon-paper-plane'=>'Flaticon paper plane'),
                array('flaticon-blank-squared-bubble'=>'Flaticon blank squared bubble'),
                array('flaticon-view'=>'Flaticon view'),
                array('flaticon-shuffle'=>'Flaticon shuffle'),
                array('flaticon-phone-call'=>'Flaticon phone call'),
                array('flaticon-360-degrees'=>'Flaticon 360 degrees'),
                array('flaticon-shield'=>'Flaticon shield'),
                array('flaticon-recycling'=>'Flaticon recycling'),
                array('flaticon-play-button'=>'Flaticon play button'),
                array('flaticon-brake'=>'Flaticon brake'),
                array('flaticon-motor'=>'Flaticon motor'),
                array('flaticon-oil'=>'Flaticon oil'),
                array('flaticon-car'=>'Flaticon car'),
                array('flaticon-car-1'=>'Flaticon car 1'),
                array('flaticon-exhaust-pipe'=>'Flaticon exhaust pipe'),
                array('flaticon-comments'=>'Flaticon comments'),
                array('flaticon-question'=>'Flaticon question'),
                array('flaticon-diamond'=>'Flaticon diamond'),
                array('flaticon-repair'=>'Flaticon repair'),
                array('flaticon-car-parts'=>'Flaticon car parts'),
                array('flaticon-filter'=>'Flaticon filter'),
                array('flaticon-instagram'=>'Flaticon instagram'),
                array('flaticon-envelope-of-white-paper'=>'Flaticon envelope of white paper'),
                array('flaticon-profile'=>'Flaticon profile'),
                array('flaticon-paper-plane-1'=>'Flaticon paper plane 1'),
                array('flaticon-old-handphone'=>'Flaticon old handphone'),
                array('flaticon-random'=>'Flaticon random'),
                array('flaticon-settings-work-tool'=>'Flaticon settings work tool'),
                array('flaticon-gas-station'=>'Flaticon gas station'),
                array('flaticon-road-perspective'=>'Flaticon road perspective'),
                array('flaticon-tick'=>'Flaticon tick'),
                array('flaticon-startup'=>'Flaticon startup'),
                array('flaticon-pencil'=>'Flaticon pencil'),
            );

			return $icons;
		}

		/**
		 * load param autocomplete render
		 * */
		public function autocomplete()
		{
			add_filter( 'vc_autocomplete_azirspares_products_ids_callback', array( $this, 'productIdAutocompleteSuggester' ), 10, 1 );
			add_filter( 'vc_autocomplete_azirspares_products_ids_render', array( $this, 'productIdAutocompleteRender' ), 10, 1 );
		}

		function params()
		{
			vc_add_shortcode_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
			vc_add_shortcode_param( 'number', array( $this, 'number_field' ) );
			vc_add_shortcode_param( 'select_preview', array( $this, 'select_preview_field' ) );
			vc_add_shortcode_param( 'datepicker', array( $this, 'datepicker_field' ) );
			vc_add_shortcode_param( 'uniqid', array( $this, 'uniqid_field' ) );
			vc_add_shortcode_param( 'tabs', array( $this, 'tabs_field' ) );
		}

        function tabs_field($settings, $value)
        {
            $editor_names = $this->azirspares_responsive_vc_data();
            $output = '<div class="tabs-css">';
            $count = 0;
            foreach ($editor_names as $key => $data) {
                $name = ucfirst($data['name']);
                $active = $count == 0 ? ' active' : '';
                $output .= "<span class='tab_css {$key}{$active}' data-tabs='{$key}'>{$name}</span>";
                $count++;
            }
            $output .= '</div>';

            return $output;
        }

		public function uniqid_field( $settings, $value )
		{
			if ( !$value ) {
				$value = 'azirspares_css_id_' . uniqid();
			}
			$output = '<input type="text" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';

			return $output;
		}

		/**
		 * date picker field
		 **/
		function datepicker_field( $settings, $value )
		{
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( !$value && isset( $settings['std'] ) ) {
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

		public function select_preview_field( $settings, $value )
		{
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				ob_start();
				?>
                <div class="container-select_preview">
                    <label for="<?php echo esc_attr( $settings['param_name'] ); ?>">
                        <select id="azirspares_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                                name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                                class="azirspares_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
							<?php foreach ( $options as $k => $option ): ?>
								<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                                <option data-preview="<?php echo esc_url( $option['preview'] ); ?>"
                                        value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['title'] ) ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>
                    <div class="image-preview">
						<?php if ( isset( $options[$value] ) && $options[$value] && ( isset( $options[$value]['preview'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[$value]['preview'] ); ?>"
                                 alt="<?php echo get_the_title(); ?>">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[$default]['preview'] ); ?>"
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
		public function taxonomy_field( $settings, $value )
		{
			$dependency = '';
			$value_arr  = $value;
			if ( !is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['options']['hide_empty'] ) && $settings['options']['hide_empty'] == true ) {
				$settings['options']['hide_empty'] = 1;
			} else {
				$settings['options']['hide_empty'] = 0;
			}
			if ( !empty( $settings['options']['taxonomy'] ) ) {
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
				if ( $terms && !is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( !empty( $settings['options']['size'] ) ) ? 'size="' . $settings['options']['size'] . '"' : '';
				$multiple = ( !empty( $settings['options']['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="azirspares_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
					. implode( $terms_fields )
					. '</select>';
			}

			return $output;
		}

		/**
		 * Suggester for autocomplete by id/name/title/sku
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return array - id's from products with title/sku.
		 */
		public function productIdAutocompleteSuggester( $query )
		{
			global $wpdb;
			$product_id      = (int)$query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
					FROM {$wpdb->posts} AS a
					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : -1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && !empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'azirspares-toolkit' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'azirspares-toolkit' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'azirspares-toolkit' ) . ': ' . $value['sku'] : '' );
					$results[]     = $data;
				}
			}

			return $results;
		}

		/**
		 * Find product by id
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return bool|array
		 */
		public function productIdAutocompleteRender( $query )
		{
			$query = trim( $query['value'] ); // get value from requested
			if ( !empty( $query ) ) {
				// get product
				$product_object = wc_get_product( (int)$query );
				if ( is_object( $product_object ) ) {
					$product_sku         = $product_object->get_sku();
					$product_title       = $product_object->get_title();
					$product_id          = $product_object->get_id();
					$product_sku_display = '';
					if ( !empty( $product_sku ) ) {
						$product_sku_display = ' - ' . esc_html__( 'Sku', 'azirspares-toolkit' ) . ': ' . $product_sku;
					}
					$product_title_display = '';
					if ( !empty( $product_title ) ) {
						$product_title_display = ' - ' . esc_html__( 'Title', 'azirspares-toolkit' ) . ': ' . $product_title;
					}
					$product_id_display = esc_html__( 'Id', 'azirspares-toolkit' ) . ': ' . $product_id;
					$data               = array();
					$data['value']      = $product_id;
					$data['label']      = $product_id_display . $product_title_display . $product_sku_display;

					return !empty( $data ) ? $data : false;
				}

				return false;
			}

			return false;
		}

		public function number_field( $settings, $value )
		{
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( !$value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;line-height:23px;height:auto;" />' . $suffix;

			return $output;
		}

		public function azirspares_vc_bootstrap( $dependency = null, $value_dependency = null )
		{
			$data_value     = array();
			$data_bootstrap = array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Rows space', 'azirspares-toolkit' ),
					'param_name' => 'boostrap_rows_space',
					'value'      => array(
						esc_html__( 'Default', 'azirspares-toolkit' ) => 'rows-space-0',
						esc_html__( '10px', 'azirspares-toolkit' )    => 'rows-space-10',
						esc_html__( '20px', 'azirspares-toolkit' )    => 'rows-space-20',
						esc_html__( '30px', 'azirspares-toolkit' )    => 'rows-space-30',
						esc_html__( '40px', 'azirspares-toolkit' )    => 'rows-space-40',
						esc_html__( '50px', 'azirspares-toolkit' )    => 'rows-space-50',
						esc_html__( '60px', 'azirspares-toolkit' )    => 'rows-space-60',
						esc_html__( '70px', 'azirspares-toolkit' )    => 'rows-space-70',
						esc_html__( '80px', 'azirspares-toolkit' )    => 'rows-space-80',
						esc_html__( '90px', 'azirspares-toolkit' )    => 'rows-space-90',
						esc_html__( '100px', 'azirspares-toolkit' )   => 'rows-space-100',
					),
					'std'        => 'rows-space-0',
					'group'      => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Desktop', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_bg_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Desktop', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_lg_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on landscape tablet', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_md_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '4',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on portrait tablet', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_sm_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Mobile', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_xs_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Items per row on Mobile', 'azirspares-toolkit' ),
					'param_name'  => 'boostrap_ts_items',
					'value'       => array(
						esc_html__( '1 item', 'azirspares-toolkit' )  => '12',
						esc_html__( '2 items', 'azirspares-toolkit' ) => '6',
						esc_html__( '3 items', 'azirspares-toolkit' ) => '4',
						esc_html__( '4 items', 'azirspares-toolkit' ) => '3',
						esc_html__( '5 items', 'azirspares-toolkit' ) => '15',
						esc_html__( '6 items', 'azirspares-toolkit' ) => '2',
					),
					'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Boostrap settings', 'azirspares-toolkit' ),
					'std'         => '6',
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
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

		public function azirspares_vc_carousel( $dependency = null, $value_dependency = null )
		{
			$data_value    = array();
			$data_carousel = array(
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( '1 Row', 'azirspares-toolkit' )  => '1',
						esc_html__( '2 Rows', 'azirspares-toolkit' ) => '2',
						esc_html__( '3 Rows', 'azirspares-toolkit' ) => '3',
						esc_html__( '4 Rows', 'azirspares-toolkit' ) => '4',
						esc_html__( '5 Rows', 'azirspares-toolkit' ) => '5',
						esc_html__( '6 Rows', 'azirspares-toolkit' ) => '6',
					),
					'std'        => '1',
					'heading'    => esc_html__( 'The number of rows which are shown on block', 'azirspares-toolkit' ),
					'param_name' => 'owl_number_row',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Rows space', 'azirspares-toolkit' ),
					'param_name' => 'owl_rows_space',
					'value'      => array(
						esc_html__( 'Default', 'azirspares-toolkit' ) => 'rows-space-0',
						esc_html__( '10px', 'azirspares-toolkit' )    => 'rows-space-10',
						esc_html__( '20px', 'azirspares-toolkit' )    => 'rows-space-20',
						esc_html__( '30px', 'azirspares-toolkit' )    => 'rows-space-30',
						esc_html__( '40px', 'azirspares-toolkit' )    => 'rows-space-40',
						esc_html__( '50px', 'azirspares-toolkit' )    => 'rows-space-50',
						esc_html__( '60px', 'azirspares-toolkit' )    => 'rows-space-60',
						esc_html__( '70px', 'azirspares-toolkit' )    => 'rows-space-70',
						esc_html__( '80px', 'azirspares-toolkit' )    => 'rows-space-80',
						esc_html__( '90px', 'azirspares-toolkit' )    => 'rows-space-90',
						esc_html__( '100px', 'azirspares-toolkit' )   => 'rows-space-100',
					),
					'std'        => 'rows-space-0',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => 'owl_number_row', 'value' => array( '2', '3', '4', '5', '6' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'Vertical Mode', 'azirspares-toolkit' ),
					'param_name' => 'owl_vertical',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'verticalSwiping', 'azirspares-toolkit' ),
					'param_name' => 'owl_verticalswiping',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => 'owl_vertical', 'value' => array( 'true' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
					),
					'std'        => 'false',
					'heading'    => esc_html__( 'AutoPlay', 'azirspares-toolkit' ),
					'param_name' => 'owl_autoplay',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Autoplay Speed', 'azirspares-toolkit' ),
					'param_name'  => 'owl_autoplayspeed',
					'value'       => '1000',
					'suffix'      => esc_html__( 'milliseconds', 'azirspares-toolkit' ),
					'description' => esc_html__( 'Autoplay speed in milliseconds', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => 'owl_autoplay', 'value' => array( 'true' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
					),
					'std'         => 'true',
					'heading'     => esc_html__( 'Navigation', 'azirspares-toolkit' ),
					'param_name'  => 'owl_navigation',
					'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Navigation style', 'azirspares-toolkit' ),
					'param_name' => 'owl_navigation_style',
					'value'      => array(
						esc_html__( 'Top Right', 'azirspares-toolkit' ) => 'nav-top-right',
						esc_html__( 'Bottom Left', 'azirspares-toolkit' ) => 'nav-bottom-left',
						esc_html__( 'Center', 'azirspares-toolkit' )    => 'nav-center',
						esc_html__( 'Bottom', 'azirspares-toolkit' )    => 'nav-bottom',
					),
					'std'        => '',
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array( 'element' => 'owl_navigation', 'value' => array( 'true' ) ),
				),
                array(
                    'type'       => 'dropdown',
                    'heading'    => esc_html__( 'Navigation type', 'azirspares-toolkit' ),
                    'param_name' => 'owl_navigation_type',
                    'value'      => array(
                        esc_html__( 'Circle', 'azirspares-toolkit' )        => 'circle',
                        esc_html__( 'Square', 'azirspares-toolkit' )        => 'square',
                    ),
                    'std'        => '',
                    'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
                    'dependency' => array( 'element' => 'owl_navigation', 'value' => array( 'true' ) ),
                ),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
					),
					'std'         => 'false',
					'heading'     => esc_html__( 'Dots', 'azirspares-toolkit' ),
					'param_name'  => 'owl_dots',
					'description' => esc_html__( "Show dots buttons.", 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Yes', 'azirspares-toolkit' ) => 'true',
						esc_html__( 'No', 'azirspares-toolkit' )  => 'false',
					),
					'std'         => 'false',
					'heading'     => esc_html__( 'Loop', 'azirspares-toolkit' ),
					'param_name'  => 'owl_loop',
					'description' => esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion.', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Slide Speed', 'azirspares-toolkit' ),
					'param_name'  => 'owl_slidespeed',
					'value'       => '300',
					'suffix'      => esc_html__( 'milliseconds', 'azirspares-toolkit' ),
					'description' => esc_html__( 'Slide speed in milliseconds', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'Margin', 'azirspares-toolkit' ),
					'param_name'  => 'owl_slide_margin',
					'value'       => '30',
					'suffix'      => esc_html__( 'Pixel', 'azirspares-toolkit' ),
					'description' => esc_html__( 'Distance( or space) between 2 item', 'azirspares-toolkit' ),
					'group'       => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency'  => array(
						'element' => 'owl_vertical', 'value' => array( 'false' ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 1500px )', 'azirspares-toolkit' ),
					'param_name' => 'owl_ls_items',
					'value'      => '4',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 1200px and < 1500px )', 'azirspares-toolkit' ),
					'param_name' => 'owl_lg_items',
					'value'      => '4',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on desktop (Screen resolution of device >= 992px < 1200px )', 'azirspares-toolkit' ),
					'param_name' => 'owl_md_items',
					'value'      => '3',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on tablet (Screen resolution of device >=768px and < 992px )', 'azirspares-toolkit' ),
					'param_name' => 'owl_sm_items',
					'value'      => '2',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on mobile landscape(Screen resolution of device >=480px and < 768px)', 'azirspares-toolkit' ),
					'param_name' => 'owl_xs_items',
					'value'      => '2',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'The items on mobile (Screen resolution of device < 480px)', 'azirspares-toolkit' ),
					'param_name' => 'owl_ts_items',
					'value'      => '1',
					'suffix'     => esc_html__( 'item(s)', 'azirspares-toolkit' ),
					'group'      => esc_html__( 'Carousel settings', 'azirspares-toolkit' ),
					'dependency' => array(
						'element' => $dependency, 'value' => array( $value_dependency ),
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
					if ( !in_array( $value['param_name'], $match ) ) {
						unset( $value['dependency'] );
					}
					$data_value[] = $value;
				}
			} else {
				$data_value = $data_carousel;
			}

			return $data_value;
		}

		public function azirspares_param_visual_composer()
		{
			$param = array();

			return apply_filters( 'azirspares_add_param_visual_composer', $param );
		}

		public function azirspares_map_shortcode()
		{
			$param_maps = $this->azirspares_param_visual_composer();
			foreach ( $param_maps as $value ) {
				if ( $value['base'] == 'azirspares_products' || $value['base'] == 'azirspares_instagram'  || $value['base'] == 'azirspares_blog' || $value['base'] == 'azirspares_category' ) {
					$value['params'] = array_merge(
						$value['params'],
						$this->azirspares_vc_carousel( 'productsliststyle', 'owl' ),
						$this->azirspares_vc_bootstrap( 'productsliststyle', 'grid' )
					);
				}
				if ( $value['base'] == 'azirspares_slide') {
					$value['params'] = array_merge(
						$value['params'],
						$this->azirspares_vc_carousel()
					);
				}
				if ( function_exists( 'vc_map' ) ) {
					vc_map( $value );
				}
			}
		}

		private function azirspares_get_templates( $template_name )
		{
			$active_plugin_wc = is_plugin_active( 'woocommerce/woocommerce.php' );
			$path_templates   = apply_filters( 'azirspares_templates_shortcode', 'vc_templates' );
			if ( $template_name == 'azirspares_products' && !$active_plugin_wc )
				return;
			$directory_shortcode = '';
			if ( is_file( get_template_directory() . '/' . $path_templates . '/' . $template_name . '.php' ) ) {
				$directory_shortcode = get_template_directory() . '/' . $path_templates;
			}
			if ( $directory_shortcode != '' )
				include_once $directory_shortcode . '/' . $template_name . '.php';
		}

		function azirspares_include_shortcode()
		{
			$param_maps = $this->azirspares_param_visual_composer();
			foreach ( $param_maps as $shortcode ) {
				$this->azirspares_get_templates( $shortcode['base'] );
			}
		}
	}

	new Azirspares_Visual_composer();
}
VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Azirspares_Tabs extends WPBakeryShortCode_VC_Tta_Accordion
{
}

class WPBakeryShortCode_Azirspares_Accordion extends WPBakeryShortCode_VC_Tta_Accordion
{
}

class WPBakeryShortCode_Azirspares_Slide extends WPBakeryShortCodesContainer
{
}