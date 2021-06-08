<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $famiau;
$additional_listing_info = famiau_additional_listing_info();

$add_listing_must_accept_term    = isset( $famiau['_famiau_add_listing_must_accept_term'] ) ? $famiau['_famiau_add_listing_must_accept_term'] == 'yes' : true;
$show_listing_form_without_login = $famiau['_famiau_show_listing_form_without_login'] == 'yes';

if ( ! $show_listing_form_without_login && ! current_user_can( 'famiau_user' ) ) {
	return;
}

?>
<div class="famiau-add-new-listing-form-wrap">
    <form name="famiau_add_listing_form" class="famiau-add-new-listing-form">
        <div class="famiau-box famiau-step-box famiau-car-details-box">
            <h4><?php esc_html_e( 'Car Details', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 1', 'famiau' ); ?></span>
            <div class="famiau-input-group">
                <div class="famiau-fields-group famiau-fields-group-primary">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-3">
                            <div class="famiau-select-box form-group">
                                <label class="famiau-lb"><?php esc_html_e( 'Condition*', 'famiau' ); ?></label>
								<?php famiau_car_status_select_html( '', 'famiau-field famiau-required  ', '_famiau_car_status', '_famiau_car_status' ); ?>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3">
                            <div class="famiau-select-box form-group">
                                <label class="famiau-lb"><?php esc_html_e( 'Make*', 'famiau' ); ?></label>
								<?php famiau_makes_select_html( '', 'famiau-field famiau-required  ', '_famiau_make', '_famiau_make' ); ?>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3">
                            <div class="famiau-select-box form-group">
                                <label class="famiau-lb"><?php esc_html_e( 'Model*', 'famiau' ); ?></label>
								<?php famiau_models_select_html( array(), '', 'famiau-field famiau-required  ', '_famiau_model', '_famiau_model' ); ?>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-3">
                            <div class="famiau-select-box form-group">
                                <label class="famiau-lb"><?php esc_html_e( 'Year*', 'famiau' ); ?></label>
                                <input type="number" class="famiau-field famiau-required  " id="_famiau_year"
                                       name="_famiau_year"
                                       min="1700" max="<?php echo date( 'Y' ); ?>"
                                       value="<?php echo date( 'Y' ); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="famiau-fields-group">
                <div class="row famiau-auto-clear">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="famiau-form-horizontal">
                            <div data-key="listing_title" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Title', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input name="listing_title" id="listing_title" class="famiau-field"
                                           value=""
                                           placeholder="<?php esc_html_e( 'The displayed title for listing', 'famiau' ); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_body" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Body', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_select_no_key_html( $famiau['all_car_bodies'], '', 'famiau-field', '_famiau_body', '_famiau_body', array( 'first_item_text' => esc_html__( 'Select Body', 'famiau' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_mileage" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Mileage', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="number" min="0" class="famiau-field"
                                           name="_famiau_mileage"
                                           id="_famiau_mileage"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_fuel_type" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Fuel Type', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_select_no_key_html( $famiau['all_fuel_types'], '', 'famiau-field', '_famiau_fuel_type', '_famiau_fuel_type', array( 'first_item_text' => esc_html__( 'Select Fuel Type', 'famiau' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_engine" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Engine', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="number" min="0" max="10" step="0.1" class="famiau-field"
                                           name="_famiau_engine" id="_famiau_engine"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_gearbox_type" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Transmission', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_gearbox_type_select_html( '', 'famiau-field', '_famiau_gearbox_type', '_famiau_gearbox_type' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_drive" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Drive', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_select_no_key_html( $famiau['all_drives'], '', 'famiau-field', '_famiau_drive', '_famiau_drive', array( 'first_item_text' => esc_html__( 'Select Drive', 'famiau' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_exterior_color" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Exterior Color', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_select_no_key_html( $famiau['all_exterior_colors'], '', 'famiau-field', '_famiau_exterior_color', '_famiau_exterior_color', array( 'first_item_text' => esc_html__( 'Select Exterior Color', 'famiau' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_exterior_color" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Interior Color', 'famiau' ); ?></label>
                                <div class="control-input">
									<?php famiau_select_no_key_html( $famiau['all_interior_colors'], '', 'famiau-field', '_famiau_interior_color', '_famiau_interior_color', array( 'first_item_text' => esc_html__( 'Select Interior Color', 'famiau' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_registered_date" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Registered', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field famiau-date-field"
                                           name="_famiau_registered_date" id="_famiau_registered_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_vin" class="form-group">
                                <label class="famiau-lb control-label"
                                       title="<?php esc_attr_e( 'Vehicle identification number', 'famiau' ); ?>"><?php esc_html_e( 'VIN', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field"
                                           title="<?php esc_attr_e( 'Vehicle identification number', 'famiau' ); ?>"
                                           name="_famiau_vin" id="_famiau_vin"
                                           placeholder="<?php esc_attr_e( 'Vehicle identification number', 'famiau' ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_car_number_of_seats" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Number Of Seats', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="number" min="0" class="famiau-field"
                                           name="_famiau_car_number_of_seats" id="_famiau_car_number_of_seats"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_car_number_of_doors" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Number Of Doors', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="number" min="0" max="10" class="famiau-field"
                                           name="_famiau_car_number_of_doors" id="_famiau_car_number_of_doors"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_fueling_system" class="form-group">
                                <label class="famiau-lb control-label"
                                       title="<?php esc_attr_e( 'Fueling system', 'famiau' ); ?>"><?php esc_html_e( 'Fueling System', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field"
                                           title="<?php esc_attr_e( 'Fueling system', 'famiau' ); ?>"
                                           name="_famiau_fueling_system" id="_famiau_fueling_system"
                                           placeholder="<?php esc_attr_e( 'Fueling system', 'famiau' ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_fuel_consumption" class="form-group">
                                <label class="famiau-lb control-label"
                                       title="<?php esc_attr_e( 'Fuel consumption', 'famiau' ); ?>"><?php esc_html_e( 'Fuel Consumption', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field"
                                           title="<?php esc_attr_e( 'Fuel consumption', 'famiau' ); ?>"
                                           name="_famiau_fuel_consumption" id="_famiau_fuel_consumption"
                                           placeholder="<?php esc_attr_e( 'Fuel consumption', 'famiau' ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="famiau-fields-group">
                <h5><?php esc_html_e( 'Car Location', 'famiau' ); ?></h5>
                <div class="row famiau-auto-clear">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_car_address" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Address', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field  "
                                           name="_famiau_car_address" id="_famiau_car_address">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_car_latitude" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Latitude', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field  "
                                           name="_famiau_car_latitude" id="_famiau_car_latitude">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="famiau-form-horizontal">
                            <div data-key="_famiau_car_longitude" class="form-group">
                                <label class="famiau-lb control-label"><?php esc_html_e( 'Longitude', 'famiau' ); ?></label>
                                <div class="control-input">
                                    <input type="text" class="famiau-field  "
                                           name="_famiau_car_longitude" id="_famiau_car_longitude">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="description"><a href="https://www.latlong.net/"
                                          target="_blank"><?php esc_attr_e( 'Lat and Long Finder', 'famiau' ); ?></a>
                </p>
            </div>
        </div>
        <div class="famiau-box famiau-step-box famiau-car-features-box">
            <h4><?php esc_html_e( 'Select Your Car Features', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 2', 'famiau' ); ?></span>
            <div class="famiau-fields-group">
				<?php famiau_get_template_part( 'listing-form/famiau-car', 'features' ); ?>
            </div>
        </div>
        <div class="famiau-box famiau-step-box famiau-images-upload-box famiau-gallery-box">
            <h4><?php esc_html_e( 'Upload Photos', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 3', 'famiau' ); ?></span>
			<?php famiau_get_template_part( 'listing-form/famiau-upload', 'photos' ); ?>
        </div>
        <div class="famiau-box famiau-step-box famiau-video-links-box">
            <h4><?php esc_html_e( 'Add Video', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 4', 'famiau' ); ?></span>
			<?php famiau_get_template_part( 'listing-form/famiau-video', 'links' ); ?>
        </div>
        <div class="famiau-box famiau-step-box famiau-seller-notes-box">
            <h4><?php esc_html_e( 'Car Description', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 5', 'famiau' ); ?></span>
            <div class="famiau-fields-group">
                <div data-key="_famiau_desc" class="form-group">
                    <textarea name="_famiau_desc" id="_famiau_desc" class="famiau-field  "></textarea>
                </div>
            </div>
        </div>
        <div class="famiau-box famiau-step-box famiau-seller-notes-box">
            <h4><?php esc_html_e( 'Enter Seller\'s Notes', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 6', 'famiau' ); ?></span>
			<?php famiau_get_template_part( 'listing-form/famiau-seller', 'notes' ); ?>
        </div>
        <div class="famiau-box famiau-step-box famiau-prices-box">
            <h4><?php esc_html_e( 'Set Your Asking Price', 'famiau' ); ?></h4>
            <span class="famiau-box-flash"><?php esc_html_e( 'Step 7', 'famiau' ); ?></span>
			<?php famiau_get_template_part( 'listing-form/famiau-asking', 'price' ); ?>
        </div>
        <div class="famiau-box famiau-footer-box">
			<?php if ( $add_listing_must_accept_term ) { ?>
                <div class="famiau-term-agreement-wrap">
                    <label class="famiau-switch">
                        <input type="hidden" name="_famiau_accept_term"
                               id="_famiau_accept_term" class="famiau-field"
                               value="no">
                        <input class="famiau-cb" name="_famiau_accept_term_cb"
                               type="checkbox">
                        <span class="famiau-slider round"></span>
                        <span class="famiau-accept-term-text"><?php echo esc_html( $famiau['_famiau_accept_term_text'] ); ?></span>
                    </label>
                </div>
			<?php } ?>
            <button type="submit"
                    class="famiau-button famiau-submit-new-listing-btn"><?php esc_html_e( 'Submit New Listing', 'famiau' ); ?></button>
        </div>
    </form>
</div>
