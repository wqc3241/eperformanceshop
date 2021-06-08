<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$all_options = famiau_get_all_options();

$tabs_args = array(
	'settings'     => esc_html__( 'General Settings', 'famiau' ),
	'currencies'   => esc_html__( 'Currencies', 'famiau' ),
	'contact_form' => esc_html__( 'Contact Form', 'famiau' ),
	'account'      => esc_html__( 'Automotive Account', 'famiau' ),
	'make'         => esc_html__( 'List Of Makes', 'famiau' ),
	'fuel_type'    => esc_html__( 'List Of Fuel Types', 'famiau' ),
	'years_range'  => esc_html__( 'Years Range', 'famiau' ),
);

/**
 * Additional tabs only for additional car items settings
 */
$additional_tabs_args        = famiau_seler_notes_suggestions();
$additional_tabs_args        = array_merge( $additional_tabs_args, famiau_additional_listing_info() );
$car_features                = famiau_car_features();
$additional_tabs_args        = array_merge( $additional_tabs_args, $car_features );
$tabs_args['imgs_upload']    = esc_html__( 'Gallery Upload Description', 'famiau' );
$tabs_args['video_links']    = esc_html__( 'Video Links Description', 'famiau' );
$tabs_args['price_settings'] = esc_html__( 'Prices Description', 'famiau' );

$active_tab = 'settings';
if ( isset( $_REQUEST['tab'] ) ) {
	if ( array_key_exists( $_REQUEST['tab'], $tabs_args ) ) {
		$active_tab = $_REQUEST['tab'];
	}
}

$tab_head_html               = '';
$additional_tab_head_html    = '';
$additional_tab_content_html = '';
foreach ( $tabs_args as $tab_id => $tab_name ) {
	$nav_class     = $tab_id == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
	$tab_head_html .= '<a data-tab_id="' . esc_attr( $tab_id ) . '" href="?page=famiau&tab=' . esc_attr( $tab_id ) . '" class="' . $nav_class . '">' . $tab_name . '</a>';
}

if ( ! empty( $additional_tabs_args ) ) {
	foreach ( $additional_tabs_args as $tab_id => $tab ) {
		$nav_class                = $tab_id == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
		$additional_tab_head_html .= '<a data-tab_id="' . esc_attr( $tab_id ) . '" href="?page=famiau&tab=' . esc_attr( $tab_id ) . '" class="' . $nav_class . '">' . $tab['tab_name'] . '</a>';
		
		$option_key      = $tab['option_key'];
		$items_list_html = '';
		if ( isset( $all_options[ $option_key ] ) ) {
			if ( ! empty( $all_options[ $option_key ] ) ) {
				foreach ( $all_options[ $option_key ] as $item ) {
					$items_list_html .= '<div class="famiau-item" data-item_val="' . esc_attr( $item ) . '"><div class="famiau-item-inner">' . esc_html( $item ) . '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
				}
			}
		}
		$form_input_class            = isset( $tab['form_input_class'] ) ? esc_attr( $tab['form_input_class'] ) : '';
		$additional_tab_content_html .= '<div id="' . esc_attr( $tab_id ) . '" class="famiau-tab-content tab-content">
                            <div class="famiau-tab-connent-inner">
                                <h3>' . $tab['tab_name'] . '</h3>
                                <p class="description">' . $tab['short_desc'] . '</p>
                                <div class="famiau-inner-wrapper">
                                    <div class="famiau-section">
                                        <form name="famiau-add-car_body-form" class="famiau-add-car_body-form famiau-add-item-form">
                                            <label>' . $tab['form_label'] . '</label>
                                            <div class="famiau-input-wrap">
                                                <input type="text" class="famiau-item-input ' . $form_input_class . '" placeholder="' . $tab['form_input_placeholder'] . '"/>
                                            </div>
                                            <button type="submit" class="button-primary famiau-add-car_body-btn">' . $tab['form_submit_text'] . '</button>
                                        </form>
                                    </div>
                                    <div data-option_key="' . $option_key . '"class="famiau-' . $option_key . '-list-wrap famiau-items-list-wrap">
                                    ' . $items_list_html . '
                                    </div>
                                </div>
                            </div>
                        </div>';
	}
}

// Enqueue scripts tab
$nav_class                = 'enqueue_scripts' == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
$additional_tab_head_html .= '<a data-tab_id="enqueue_scripts" href="?page=famiau&tab=enqueue_scripts" class="' . $nav_class . '">' . esc_html__( 'Enqueue Scripts', 'famiau' ) . '</a>';

$nav_class                = 'import_export' == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
$additional_tab_head_html .= '<a data-tab_id="import_export" href="?page=famiau&tab=import_export" class="' . $nav_class . '">' . esc_html__( 'Import/Export Settings', 'famiau' ) . '</a>';

$show_listing_form_without_login = isset( $all_options['_famiau_show_listing_form_without_login'] ) ? $all_options['_famiau_show_listing_form_without_login'] == 'yes' : false;
$sellers_can_create_acc          = isset( $all_options['_famiau_sellers_can_create_acc'] ) ? $all_options['_famiau_sellers_can_create_acc'] == 'yes' : true;
$add_listing_must_accept_term    = isset( $all_options['_famiau_add_listing_must_accept_term'] ) ? $all_options['_famiau_add_listing_must_accept_term'] == 'yes' : true;
$listing_must_moderated          = isset( $all_options['_famiau_listing_must_moderated'] ) ? $all_options['_famiau_listing_must_moderated'] == 'yes' : true;
$all_makes                       = isset( $all_options['all_makes'] ) ? $all_options['all_makes'] : array();
$all_fuel_types                  = isset( $all_options['all_fuel_types'] ) ? $all_options['all_fuel_types'] : array();
$this_year                       = date( 'Y' );
$min_year                        = isset( $all_options['_famiau_min_year'] ) ? $all_options['_famiau_min_year'] : 1700;
$max_year                        = isset( $all_options['_famiau_max_year'] ) ? $all_options['_famiau_max_year'] : $this_year;
if ( $max_year < $min_year ) {
	$max_year = $min_year;
}
if ( $max_year > $this_year ) {
	$max_year = $this_year;
}
$imgs_upload_desc      = isset( $all_options['_famiau_imgs_upload_desc'] ) ? stripslashes( $all_options['_famiau_imgs_upload_desc'] ) : '';
$video_links_desc      = isset( $all_options['_famiau_video_links_desc'] ) ? stripslashes( $all_options['_famiau_video_links_desc'] ) : '';
$prices_desc           = isset( $all_options['_famiau_prices_desc'] ) ? stripslashes( $all_options['_famiau_prices_desc'] ) : '';
$gmap_api_key          = trim( $all_options['_famiau_gmap_api_key'] );
$load_gmap_js          = trim( $all_options['_famiau_load_gmap_js'] ) == 'yes';
$force_cf7_scripts     = trim( $all_options['_famiau_force_cf7_scripts'] ) == 'yes';
$enable_filter_mobile  = trim( $all_options['_famiau_enable_filter_mobile'] ) == 'yes';
$enable_instant_filter = trim( $all_options['_famiau_enable_instant_filter'] ) == 'yes';
$enable_lazy_load      = trim( $all_options['_famiau_enable_lazy_load'] ) == 'yes';

$exapmle_cf7_template_form = '<label>[text* your-name placeholder "Your Name*"] </label>
<label>[email* your-email placeholder "Email*"] </label>
<label>[tel phone-number placeholder "Phone"] </label>
<label>[textarea your-message placeholder "Your Message Here..."] </label>

[submit "Send"]
<div class="famiau-listing-field-wrap famiau-hidden">[textarea famiau-content class:famiau-hidden]</div>';

?>

<div class="wrap">
    <h1><?php esc_html_e( 'Fami Automotive Settings', 'famiau' ); ?></h1>
    <div class="famiau-page-desc">
        <p><?php esc_html_e( 'Fami Buy Together is a plugin for WooCommerce that allows you to create one or more products that come with your main product. Make it more convenient for buyers and stimulate buying more of your products. Buyers just need a single click to buy all the products included.', 'famiau' ) ?></p>
    </div>

    <div class="famiau-admin-page-content-wrap">
        <div class="famiau-tabs fami-all-settings-form">
            <h2 class="nav-tab-wrapper"><?php echo $tab_head_html . $additional_tab_head_html; ?></h2>

            <div id="settings" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Show Add New Listing Form Without Logged In', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_show_listing_form_without_login"
                                           id="_famiau_show_listing_form_without_login" class="famiau-field"
                                           value="<?php echo( $show_listing_form_without_login ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_show_listing_form_without_login_cb"
                                           type="checkbox" <?php echo( $show_listing_form_without_login ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Allows displaying new listing form when not logged', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Listing Page', 'famiau' ); ?></th>
                            <td>
								<?php famiau_all_pages_select_html( $all_options['_famiau_page_for_automotive'], 'famiau-field', '_famiau_page_for_automotive', '_famiau_page_for_automotive' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Account Page', 'famiau' ); ?></th>
                            <td>
								<?php famiau_all_pages_select_html( $all_options['_famiau_page_for_account'], 'famiau-field', '_famiau_page_for_account', '_famiau_page_for_account', array( $all_options['_famiau_page_for_automotive'] ) ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Listing Term Page', 'famiau' ); ?></th>
                            <td>
								<?php famiau_all_pages_select_html( $all_options['_famiau_page_for_term'], 'famiau-field', '_famiau_page_for_term', '_famiau_page_for_term', array(
									$all_options['_famiau_page_for_automotive'],
									$all_options['_famiau_page_for_account']
								) ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Show Term Agreement On Add New Listing Page', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_add_listing_must_accept_term"
                                           id="_famiau_add_listing_must_accept_term" class="famiau-field"
                                           value="<?php echo( $add_listing_must_accept_term ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_add_listing_must_accept_term_cb"
                                           type="checkbox" <?php echo( $add_listing_must_accept_term ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Users must agree to the terms on add new listing page', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Listing Term Text', 'famiau' ); ?></th>
                            <td>
                                <input type="text" name="_famiau_accept_term_text" id="_famiau_accept_term_text"
                                       class="famiau-field"
                                       value="<?php echo esc_attr( $all_options['_famiau_accept_term_text'] ); ?>"/>
                            </td>
                        </tr>
                        <tr style="display: none;">
                            <th><?php esc_html_e( 'New Listings Must Be Moderated', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_listing_must_moderated"
                                           id="_famiau_listing_must_moderated" class="famiau-field"
                                           value="<?php echo( $listing_must_moderated ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_listing_must_moderated_cb"
                                           type="checkbox" <?php echo( $listing_must_moderated ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Users must agree to the terms on add new listing page', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Enable Filter On Mobile', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_enable_filter_mobile"
                                           id="_famiau_enable_filter_mobile" class="famiau-field"
                                           value="<?php echo( $enable_filter_mobile ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_enable_filter_mobile_cb"
                                           type="checkbox" <?php echo( $enable_filter_mobile ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Display a mobile-specific filter instead of responsive on the actual mobile device', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Enable Instant Filter', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_enable_instant_filter"
                                           id="_famiau_enable_instant_filter" class="famiau-field"
                                           value="<?php echo( $enable_instant_filter ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_enable_instant_filter_cb"
                                           type="checkbox" <?php echo( $enable_instant_filter ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Enable Lazy Load', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_enable_lazy_load"
                                           id="_famiau_enable_lazy_load" class="famiau-field"
                                           value="<?php echo( $enable_lazy_load ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_enable_lazy_load_cb"
                                           type="checkbox" <?php echo( $enable_lazy_load ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="currencies" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Currency', 'famiau' ); ?></th>
                            <td>
                                <select id="famiau_currency" name="famiau_currency"
                                        data-placeholder="<?php esc_attr_e( 'Choose a currency&hellip;', 'famiau' ); ?>"
                                        class="famiau-field famiau-select">
                                    <option value=""><?php esc_html_e( 'Choose a currency&hellip;', 'famiau' ); ?></option>
									<?php foreach ( famiau_get_currencies() as $code => $name ) : ?>
                                        <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $all_options['famiau_currency'], $code ); ?>>
											<?php
											$symbol = famiau_get_currency_symbol( $code );
											
											if ( $symbol === $code ) {
												/* translators: 1: currency name 2: currency code */
												echo esc_html( sprintf( __( '%1$s (%2$s)', 'famiau' ), $name, $code ) );
											} else {
												/* translators: 1: currency name 2: currency symbol, 3: currency code */
												echo esc_html( sprintf( __( '%1$s (%2$s / %3$s)', 'famiau' ), $name, famiau_get_currency_symbol( $code ), $code ) );
											}
											?>
                                        </option>
									<?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Currency Position', 'famiau' ); ?></th>
                            <td><?php famiau_currency_pos_select_html( $all_options['_famiau_currency_pos'], 'famiau-field', '_famiau_currency_pos', '_famiau_currency_pos' ); ?></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Thousand Separator', 'famiau' ); ?></th>
                            <td>
                                <input value="<?php echo esc_attr( $all_options['_famiau_thousand_separator'] ); ?>"
                                       class="famiau-field" name="_famiau_thousand_separator"
                                       id="_famiau_thousand_separator" type="text" style="width: 50px;"/>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Decimal Separator', 'famiau' ); ?></th>
                            <td>
                                <input value="<?php echo esc_attr( $all_options['_famiau_decimal_separator'] ); ?>"
                                       class="famiau-field" name="_famiau_decimal_separator"
                                       id="_famiau_decimal_separator" type="text" style="width: 50px;"/>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Number Of Decimals', 'famiau' ); ?></th>
                            <td>
                                <input value="<?php echo intval( $all_options['_famiau_num_of_decimals'] ); ?>" min="0"
                                       step="1" class="famiau-field" name="_famiau_num_of_decimals"
                                       id="_famiau_num_of_decimals" type="number" style="width: 50px;"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="contact_form" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Listing Contact Form', 'famiau' ); ?></th>
                            <td>
								<?php famiau_all_post_types_select_html( 'wpcf7_contact_form', $all_options['_famiau_listing_cf7'], 'famiau-field', '_famiau_listing_cf7', '_famiau_listing_cf7' ); ?>
                                <p class="description"><?php esc_html_e( 'You need to install and activate the "Contact Form 7" plugin to use this function. In the form, add "[textarea famiau-content class: famiau-hidden]" to submit the listing content. In the email template, add "[famiau-content]". By default, if one of the components is missing, it will be added at the end.', 'famiau' ); ?></p>
                                <h4><?php esc_html_e( 'Example Contact Form 7 Template:', 'famiau' ); ?></h4>
                                <p><strong><?php esc_html_e( 'Form: ', 'famiau' ); ?></strong></p>
                                <pre><?php echo esc_html( $exapmle_cf7_template_form ); ?></pre>
                                <p class="description"><?php esc_html_e( 'You can copy this template the form editor of Contact Form 7 to use or edit it at your own discretion', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="account" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Account creation', 'famiau' ); ?></th>
                            <td>
                                <label class="famiau-switch">
                                    <input type="hidden" name="_famiau_sellers_can_create_acc" class="famiau-field"
                                           value="<?php echo( $sellers_can_create_acc ? 'yes' : 'no' ); ?>">
                                    <input name="_famiau_sellers_can_create_acc_cb"
                                           type="checkbox" <?php echo( $sellers_can_create_acc ? 'checked' : '' ); ?> >
                                    <span class="famiau-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Allow sellers to create an account on the "Automotive Account" page', 'famiau' ); ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="make" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php esc_html_e( 'List Of Makes', 'famiau' ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Add/Edit list of manufacturers', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
                            <form name="famiau-add-make-form" class="famiau-add-make-form">
                                <label><?php esc_html_e( 'Add Make', 'famiau' ); ?></label>
                                <div class="famiau-input-wrap">
                                    <input type="text" class="famiau-add-make-input"
                                           placeholder="<?php esc_html_e( 'Enter an make/manufacturer', 'famiau' ); ?>"/>
                                </div>
                                <button type="submit"
                                        class="button-primary famiau-add-make-btn"><?php esc_html_e( 'Add New Make', 'famiau' ); ?></button>
                            </form>
                        </div>

                        <div class="famiau-makes-list-wrap famiau-items-list-wrap">
							<?php
							if ( ! empty( $all_makes ) ) {
								foreach ( $all_makes as $make ) {
									$make_name = isset( $make['make'] ) ? $make['make'] : '';
									$models    = isset( $make['models'] ) ? $make['models'] : array();
									echo famiau_add_make_item_template( $make_name, $models );
								}
							}
							?>
                        </div>

                    </div>
                </div>
            </div>

            <div id="fuel_type" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php esc_html_e( 'List Of Fuel Types', 'famiau' ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Add/Edit list of fuel types', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
                            <form name="famiau-add-fuel_type-form" class="famiau-add-fuel_type-form">
                                <label><?php esc_html_e( 'Add Fuel Type', 'famiau' ); ?></label>
                                <div class="famiau-input-wrap">
                                    <input type="text" class="famiau-add-fuel_type-input"
                                           placeholder="<?php esc_html_e( 'Enter a fuel type', 'famiau' ); ?>"/>
                                </div>
                                <button type="submit"
                                        class="button-primary famiau-add-fuel_type-btn"><?php esc_html_e( 'Add New Fuel Type', 'famiau' ); ?></button>
                            </form>
                        </div>

                        <div class="famiau-fuel_types-list-wrap famiau-items-list-wrap">
							<?php
							if ( ! empty( $all_fuel_types ) ) {
								foreach ( $all_fuel_types as $fuel_type ) {
									echo '<div class="famiau-fuel_type-item famiau-item" data-fuel_type="' . esc_attr( $fuel_type ) . '"><div class="famiau-item-inner">' . esc_html( $fuel_type ) . '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
								}
							}
							?>
                        </div>

                    </div>
                </div>
            </div>

            <div id="years_range" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php esc_html_e( 'Years Range', 'famiau' ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Default years range for filter', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
                            <div class="famiau-input-min-max-group">
                                <div class="famiau-input-wrap">
                                    <label><?php esc_html_e( 'Min Year', 'famiau' ); ?></label>
                                    <input type="number" min="0" name="_famiau_min_year"
                                           class="famiau-field famiau-input-num-link-min"
                                           value="<?php echo $min_year; ?>"/>
                                </div>
                                <div class="famiau-input-wrap">
                                    <label><?php esc_html_e( 'Max Year', 'famiau' ); ?></label>
                                    <input type="number" min="0" name="_famiau_max_year"
                                           class="famiau-field famiau-input-num-link-max"
                                           value="<?php echo $max_year; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			<?php echo $additional_tab_content_html; ?>

            <div id="imgs_upload" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php echo esc_html( $tabs_args['imgs_upload'] ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Configure the gallery and description when the seller adds a new car', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
							<?php wp_editor( $imgs_upload_desc, '_famiau_imgs_upload_desc', array(
								'textarea_name' => '_famiau_imgs_upload_desc',
								'editor_class'  => 'famiau-field',
								'editor_height' => 350 // pixel
							) ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="video_links" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php echo esc_html( $tabs_args['video_links'] ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Configure the videos and description when the seller adds a new car', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
							<?php wp_editor( $video_links_desc, '_famiau_video_links_desc', array(
								'textarea_name' => '_famiau_video_links_desc',
								'editor_class'  => 'famiau-field',
								'editor_height' => 350 // pixel
							) ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="price_settings" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php echo esc_html( $tabs_args['price_settings'] ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Configure the prices and description when the seller adds a new car', 'famiau' ); ?></p>
                    <div class="famiau-inner-wrapper">
                        <div class="famiau-section">
							<?php wp_editor( $prices_desc, '_famiau_prices_desc', array(
								'textarea_name' => '_famiau_prices_desc',
								'editor_class'  => 'famiau-field',
								'editor_height' => 350 // pixel
							) ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="enqueue_scripts" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <h3><?php esc_html_e( 'Enqueue Scripts', 'famiau' ); ?></h3>
                    <div class="famiau-inner-wrapper">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th><?php esc_html_e( 'GMap API Key', 'famiau' ); ?></th>
                                <td>
                                    <input type="text" name="_famiau_gmap_api_key" id="_famiau_gmap_api_key"
                                           class="famiau-field " value="<?php echo esc_attr( $gmap_api_key ); ?>"/>
                                    <p class="description"><?php echo wp_kses( sprintf( __( 'Enter your Google Map API key. <a href="%s" target="_blank">How to get?</a>', 'famiau' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ), array(
											'a' => array(
												'href'   => array(),
												'target' => array()
											)
										) ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Load GMap JS', 'famiau' ); ?></th>
                                <td>
                                    <label class="famiau-switch">
                                        <input type="hidden" name="_famiau_load_gmap_js"
                                               id="_famiau_load_gmap_js" class="famiau-field"
                                               value="<?php echo( $load_gmap_js ? 'yes' : 'no' ); ?>">
                                        <input name="_famiau_load_gmap_js_cb"
                                               type="checkbox" <?php echo( $load_gmap_js ? 'checked' : '' ); ?> >
                                        <span class="famiau-slider round"></span>
                                    </label>
                                    <p class="description"><?php esc_html_e( 'Load Gmap JS on frontend? GMap JS is required if you want to display the map on the listing details page. If you are sure GMap is loaded via the theme or other plugin, you can turn it off.', 'famiau' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Force Email AJAX', 'famiau' ); ?></th>
                                <td>
                                    <label class="famiau-switch">
                                        <input type="hidden" name="_famiau_force_cf7_scripts"
                                               id="_famiau_force_cf7_scripts" class="famiau-field"
                                               value="<?php echo( $force_cf7_scripts ? 'yes' : 'no' ); ?>">
                                        <input name="_famiau_force_cf7_scripts_cb"
                                               type="checkbox" <?php echo( $force_cf7_scripts ? 'checked' : '' ); ?> >
                                        <span class="famiau-slider round"></span>
                                    </label>
                                    <p class="description"><?php esc_html_e( 'Turns on if contact form does not send emails via AJAX. This feature will force enqueue Contact Form 7 scripts on the detailed listing page. Note: The "Contact Form 7" plugin must be installed and activate', 'famiau' ); ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="import_export" class="famiau-tab-content tab-content">
                <div class="famiau-tab-connent-inner">
                    <div class="famiau-inner-wrapper">
                        <h3><?php esc_html_e( 'Export Settings', 'famiau' ); ?></h3>
                        <a href="<?php echo famiau_export_settings_link(); ?>" target="_blank"
                           class="button famiau-export-settings"><?php esc_html_e( 'Export Settings', 'famiau' ); ?></a>
                        <h3><?php esc_html_e( 'Import Settings', 'famiau' ); ?></h3>
                        <div class="famiau-import-settings-wrap">
                            <form action="<?php echo famiau_import_settings_action_link(); ?>"
                                  name="famiau_import_settings_form" method="post"
                                  enctype="multipart/form-data">
                                <label><?php esc_html_e( 'Select json file:', 'famiau' ); ?></label>
                                <input type="file" name="famiau_import_file" id="famiau_import_file">
                                <button type="submit"
                                        class="button"><?php esc_html_e( 'Upload File', 'famiau' ); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <button type="button"
                class="button-primary famiau-save-all-settings"><?php esc_html_e( 'Save All Settings', 'famiau' ); ?></button>
    </div>

</div>