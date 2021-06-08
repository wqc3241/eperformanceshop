<?php
/**
 * The template for displaying famiau content in the single-famiau.php template
 *
 */

defined( 'ABSPATH' ) || exit;

global $post;
$dealer_id   = $post->post_author;
$dealer      = get_userdata( $dealer_id );
$all_options = famiau_get_all_options();

$meta_data = get_post_meta( get_the_ID(), '_famiau_metabox_options', true );
//echo '<pre>';
//print_r($meta_data);
//echo '</pre>';

// https://codepen.io/vilcu/pen/ZQwdGQ
$gallery = array();
if ( has_post_thumbnail() ) {
	if ( ! in_array( get_post_thumbnail_id(), $gallery ) ) {
		$gallery[] = get_post_thumbnail_id();
	}
}
$gallery    = isset( $meta_data['_famiau_gallery'] ) ? array_merge( $gallery, explode( ',', $meta_data['_famiau_gallery'] ) ) : $gallery;
$video_url  = isset( $meta_data['_famiau_video_url'] ) ? trim( $meta_data['_famiau_video_url'] ) : '';
$video_html = '';
if ( trim( $video_url ) != '' ) {
	$video_html = '<a class="famiau-play-video" target="_blank" href="' . esc_url( $video_url ) . '">' . esc_html__( 'Video', 'famiau' ) . '</a>';
}

$all_gearboxes = array(
	'manual'         => esc_html__( 'Manual', 'famiau' ),
	'auto'           => esc_html__( 'Automatic', 'famiau' ),
	'semi-automatic' => esc_html__( 'Semi Automatic', 'famiau' )
);

$gearbox_key = $meta_data['_famiau_gearbox_type'];
$gearbox     = isset( $all_gearboxes[ $gearbox_key ] ) ? $all_gearboxes[ $gearbox_key ] : '';

$all_safely_items   = $all_options['all_car_features_safety'];
$safely_items       = $meta_data['_famiau_car_features_safety'];
$all_comforts_items = $all_options['all_car_features_comforts'];
$comforts_items     = $meta_data['_famiau_car_features_comforts'];

$all_entertainments_items = $all_options['all_car_features_entertainments'];
$entertainments_items     = $meta_data['_famiau_car_features_entertainments'];
$all_seats_items          = $all_options['all_car_features_seats'];
$seats_items              = $meta_data['_famiau_car_features_seats'];
$all_windows_items        = $all_options['all_car_features_windows'];
$windows_items            = $meta_data['_famiau_car_features_windows'];
$all_others_items         = $all_options['all_car_features_others'];
$others_items             = $meta_data['_famiau_car_features_others'];

$price_html   = isset( $meta_data['_famiau_price'] ) ? '<span class="famiau-price-html">' . famiau_get_price_html( $meta_data['_famiau_price'] ) . '</span>' : '';
$car_desc     = isset( $meta_data['_famiau_desc'] ) ? trim( $meta_data['_famiau_desc'] ) : '';
$seller_notes = isset( $meta_data['_famiau_seller_notes_suggestions'] ) ? trim( $meta_data['_famiau_seller_notes_suggestions'] ) : '';
$map_info     = array(
	'zoom'        => 11,
	'center'      => array(
		'lat' => floatval( $meta_data['_famiau_car_latitude'] ),
		'lng' => floatval( $meta_data['_famiau_car_longitude'] )
	),
	'info_window' => array(
		'address'      => sprintf( esc_html__( 'Address: %s', 'famiau' ), trim( $meta_data['_famiau_car_address'] ) ),
		'title'        => get_the_title(),
		'seller_notes' => wpautop( do_shortcode( $seller_notes ) )
	)
);

$car_statuses = array(
	'new'            => esc_html__( 'New', 'famiau' ),
	'used'           => esc_html__( 'Used', 'famiau' ),
	'certified-used' => esc_html__( 'Certified Used', 'famiau' )
);
$car_status   = esc_html( $meta_data['_famiau_car_status'] );
if ( isset( $car_statuses[ $car_status ] ) ) {
	$car_status = $car_statuses[ $car_status ];
}

$listing_contact_html = '<strong>' . esc_html__( 'Listing title: ', 'famiau' ) . '</strong> ' . esc_html( get_the_title() ) . '<br>';
$listing_contact_html .= sprintf( __( 'Click %s to view the listing', 'famiau' ), '<a href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'here', 'famiau' ) . '</a>' );

?>
<div class="famiau-main-wrap">
    <div class="famiau-single-wrap">
        <div class="container famiau-container">
            <header class="famiau-entry-header">
				<?php the_title( '<h1 class="famiau-title">', $price_html . ' </h1>' ); ?>
            </header>
            <div class="famiau-entry-content">
                <div class="row">
                    <div class="col-sm-12 col-lg-9">
						<?php if ( ! empty( $gallery ) ) {
							$slider_for_html = '';
							$slider_nav_html = '';
							?>
                            <div class="famiau-gallery-wrap">
								<?php foreach ( $gallery as $img_id ) {
									$img_big         = famiau_resize_image( $img_id, null, 1040, 610, true, true, false );
									$img_small       = famiau_resize_image( $img_id, null, 192, 120, true, true, false );
									$slider_for_html .= '<div class="famiau-big-img-wrap"><img width="' . esc_attr( $img_big['width'] ) . '" height="' . esc_attr( $img_big['height'] ) . '" src="' . esc_url( $img_big['url'] ) . '" alt="" title="" /></div>';
									$slider_nav_html .= '<div class="famiau-small-img-wrap"><img width="' . esc_attr( $img_small['width'] ) . '" height="' . esc_attr( $img_small['height'] ) . '" src="' . esc_url( $img_small['url'] ) . '" alt="" title="" /></div>';
								} ?>
                                <div class="famiau-slider-big">
                                    <div class="slider slider-for famiau-slider-for">
										<?php echo $slider_for_html; ?>
                                    </div>
									<?php echo $video_html; ?>
                                </div>
                                <div class="famiau-slider-small">
                                    <div class="slider slider-nav famiau-slider-nav">
										<?php echo $slider_nav_html; ?>
                                    </div>
                                </div>
                            </div>
						<?php } ?>
                        <div class="famiau-tabs-wrap">
                            <div class="famiau-tabs">
                                <ul>
                                    <li><a href="#famiau-car-info"><?php esc_html_e( 'Car Info', 'famiau' ); ?></a></li>
                                    <li><a href="#famiau-safety"><?php esc_html_e( 'Safety', 'famiau' ); ?></a></li>
                                    <li><a href="#famiau-comforts"><?php esc_html_e( 'Comforts', 'famiau' ); ?></a></li>
                                    <li>
                                        <a href="#famiau-other-features"><?php esc_html_e( 'Other Features', 'famiau' ); ?></a>
                                    </li>
                                </ul>

                                <div id="famiau-car-info">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Basic Info', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
                                                    <ul class="famiau-info-list">
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Status', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $car_status ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Body', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_body'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Mileage', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_mileage'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Exterior Color', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_exterior_color'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Interior Color', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_interior_color'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Seats Number', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_car_number_of_seats'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Doors Number', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_car_number_of_doors'] ); ?></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Fuel - Engine', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
                                                    <ul class="famiau-info-list">
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Engine', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_engine'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Fuel', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_fuel_type'] ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Fueling System', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_fueling_system'] ); ?></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Transmission', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
                                                    <ul class="famiau-info-list">
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Transmission', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $gearbox ); ?></span>
                                                        </li>
                                                        <li class="famiau-list-item">
                                                            <label><?php esc_html_e( 'Drive', 'famiau' ); ?></label>
                                                            <span><?php echo esc_html( $meta_data['_famiau_drive'] ); ?></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="famiau-safety">
                                    <div class="famiau-box">
                                        <div class="famiau-box-content">
											<?php if ( ! empty( $all_safely_items ) ) {
												foreach ( $all_safely_items as $safely_item ) {
													if ( in_array( $safely_item, $safely_items ) ) {
														echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $safely_item ) . '</label>';
													} else {
														echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $safely_item ) . '</label>';
													}
												}
											} ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="famiau-comforts">
                                    <div class="famiau-box">
                                        <div class="famiau-box-content">
											<?php if ( ! empty( $all_comforts_items ) ) {
												foreach ( $all_comforts_items as $comforts_item ) {
													if ( in_array( $comforts_item, $comforts_items ) ) {
														echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $comforts_item ) . '</label>';
													} else {
														echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $comforts_item ) . '</label>';
													}
												}
											} ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="famiau-other-features">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Entertainments', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
													<?php if ( ! empty( $all_entertainments_items ) ) {
														foreach ( $all_entertainments_items as $entertainments_item ) {
															if ( in_array( $entertainments_item, $entertainments_items ) ) {
																echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $entertainments_item ) . '</label>';
															} else {
																echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $entertainments_item ) . '</label>';
															}
														}
													} ?>
                                                </div>
                                            </div>
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Seats', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
													<?php if ( ! empty( $all_seats_items ) ) {
														foreach ( $all_seats_items as $seats_item ) {
															if ( in_array( $seats_item, $seats_items ) ) {
																echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $seats_item ) . '</label>';
															} else {
																echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $seats_item ) . '</label>';
															}
														}
													} ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Windows', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
													<?php if ( ! empty( $all_windows_items ) ) {
														foreach ( $all_windows_items as $windows_item ) {
															if ( in_array( $windows_item, $windows_items ) ) {
																echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $windows_item ) . '</label>';
															} else {
																echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $windows_item ) . '</label>';
															}
														}
													} ?>
                                                </div>
                                            </div>
                                            <div class="famiau-box">
                                                <h5 class="famiau-box-heading"><?php esc_html_e( 'Others', 'famiau' ); ?></h5>
                                                <div class="famiau-box-content">
													<?php if ( ! empty( $all_others_items ) ) {
														foreach ( $all_others_items as $others_item ) {
															if ( in_array( $others_item, $others_items ) ) {
																echo '<label class="feature-lb has-this-feature"><i class="fa fa-check-circle"></i> ' . esc_html( $others_item ) . '</label>';
															} else {
																echo '<label class="feature-lb"><i class="fa fa-check-circle-o"></i> ' . esc_html( $others_item ) . '</label>';
															}
														}
													} ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<?php if ( $car_desc != '' ) { ?>
                            <div class="famiau-car-description-wrap">
                                <h3><?php esc_html_e( 'Car Description', 'famiau' ); ?></h3>
                                <div class="famiau-car-desc">
									<?php echo wpautop( do_shortcode( $car_desc ) ); ?>
                                </div>
                            </div>
						<?php } ?>
						
						<?php if ( $seller_notes != '' ) { ?>
                            <div class="famiau-seller-notes-wrap">
                                <h3><?php esc_html_e( 'Seller\'s Notes', 'famiau' ); ?></h3>
                                <div class="famiau-seller-notes">
									<?php echo wpautop( do_shortcode( $seller_notes ) ); ?>
                                </div>
                            </div>
						<?php } ?>
                    </div>
                    <div class="col-sm-12 col-lg-3">
						<?php echo famiau_get_dealer_info_html( $dealer, 66 ); ?>
						<?php if ( $map_info['center']['lat'] != '' && $map_info['center']['lng'] != '' ) {
							$gmap_id = uniqid( 'famiau-map' );
							?>
                            <div class="famiau-single-listing-map-wrap">
                                <div data-map_info="<?php echo htmlentities2( json_encode( $map_info ) ); ?>"
                                     id="<?php echo esc_attr( $gmap_id ); ?>" class="famiau-gmap famiau-map">
                                </div>
                            </div>
						<?php } ?>
                        <div class="famiau-listing-contact-wrap"
                             data-famiau_cf7_info="<?php echo htmlentities2( $listing_contact_html ); ?>">
                            <div class="famiau-listing-contact-inner">
                                <h3><?php esc_html_e( 'Contact Dealer', 'famiau' ); ?></h3>
								<?php echo famiau_get_contact_form_html(); ?>
                            </div>
                        </div>
						<?php if ( is_active_sidebar( 'famiau-single-sidebar' ) ) { ?>
							<?php dynamic_sidebar( 'famiau-single-sidebar' ); ?>
						<?php } ?>
                    </div>
                </div>
            </div>
            <footer class="famiau-entry-footer">
            </footer>
        </div>
    </div>
</div>